<?php

declare(strict_types=1);

require __DIR__ . '/coreConfig.php';

header('Content-Type: application/json; charset=utf-8');

$title = filter_input(INPUT_POST, 'title', FILTER_DEFAULT);
$start = filter_input(INPUT_POST, 'start', FILTER_DEFAULT);
$end   = filter_input(INPUT_POST, 'end', FILTER_DEFAULT);
$url   = filter_input(INPUT_POST, 'url', FILTER_DEFAULT);
$note  = filter_input(INPUT_POST, 'note', FILTER_DEFAULT);
$color = filter_input(INPUT_POST, 'calendar_color', FILTER_DEFAULT) ?? '1';

if (!$title || !$start || !$end) {
    echo json_encode(['success' => false, 'error' => 'Missing required fields']);
    exit;
}

$isRecurring = (int) (filter_input(INPUT_POST, 'is_recurring', FILTER_VALIDATE_INT) ?? 0);
$recFreq     = filter_input(INPUT_POST, 'recurrence_freq', FILTER_DEFAULT);
$recInterval = (int) (filter_input(INPUT_POST, 'recurrence_interval', FILTER_VALIDATE_INT) ?? 1);
if ($recInterval < 1) {
    $recInterval = 1;
}
$recDays     = filter_input(INPUT_POST, 'recurrence_days', FILTER_DEFAULT);
$recUntil    = filter_input(INPUT_POST, 'recurrence_until', FILTER_DEFAULT);

$rrule = null;
if ($isRecurring === 1 && $recFreq) {
    $rruleParts = ['FREQ=' . strtoupper((string) $recFreq), 'INTERVAL=' . $recInterval];
    if (strtoupper((string) $recFreq) === 'WEEKLY' && !empty($recDays)) {
        $rruleParts[] = 'BYDAY=' . strtoupper((string) $recDays);
    }
    if (!empty($recUntil)) {
        try {
            $dtUntil = new DateTime((string) $recUntil);
            $rruleParts[] = 'UNTIL=' . $dtUntil->format('Ymd\THis\Z');
        } catch (Throwable) {
            $recUntil = null;
        }
    } else {
        $recUntil = null;
    }
    $rrule = implode(';', $rruleParts);
} else {
    $isRecurring = 0;
    $recFreq = null;
    $recInterval = 1;
    $recDays = null;
    $recUntil = null;
}

$prx = $prx ?? '';

try {
    $stmt = $db->prepare("INSERT INTO {$prx}calendar_events 
        (`title`, `start`, `end`, `note`, `url`, `cat_id`, `is_recurring`, `recurrence_freq`, `recurrence_interval`, `recurrence_days`, `recurrence_until`, `rrule`) 
        VALUES 
        (:title, :start, :end, :note, :url, :cat_id, :is_recurring, :recurrence_freq, :recurrence_interval, :recurrence_days, :recurrence_until, :rrule)");
    $stmt->execute([
        ':title'               => $title,
        ':start'               => $start,
        ':end'                 => $end,
        ':note'                => $note,
        ':url'                 => $url,
        ':cat_id'              => $color,
        ':is_recurring'        => $isRecurring,
        ':recurrence_freq'     => $recFreq,
        ':recurrence_interval' => $recInterval,
        ':recurrence_days'     => $recDays,
        ':recurrence_until'    => $recUntil,
        ':rrule'               => $rrule,
    ]);

    echo json_encode(['success' => true, 'id' => (int) $db->lastInsertId()]);
} catch (PDOException $e) {
    // Fallback if table hasn't been upgraded with ALTER TABLE yet
    try {
        $stmt = $db->prepare("INSERT INTO {$prx}calendar_events (`title`, `start`, `end`, `note`, `url`, `cat_id`) VALUES (:title, :start, :end, :note, :url, :cat_id)");
        $stmt->execute([
            ':title'   => $title,
            ':start'   => $start,
            ':end'     => $end,
            ':note'    => $note,
            ':url'     => $url,
            ':cat_id'  => $color,
        ]);
        echo json_encode(['success' => true, 'id' => (int) $db->lastInsertId(), 'warning' => 'Event saved without recurrence (database upgrade needed)']);
    } catch (PDOException $e2) {
        echo json_encode(['success' => false, 'error' => $e2->getMessage()]);
    }
}
