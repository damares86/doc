<?php

declare(strict_types=1);

require __DIR__ . '/coreConfig.php';

header('Content-Type: application/json; charset=utf-8');

$id    = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$title = filter_input(INPUT_POST, 'title', FILTER_DEFAULT);
$start = filter_input(INPUT_POST, 'start', FILTER_DEFAULT);
$end   = filter_input(INPUT_POST, 'end', FILTER_DEFAULT);
$note  = (string) (filter_input(INPUT_POST, 'note', FILTER_DEFAULT) ?? '');
$url   = (string) (filter_input(INPUT_POST, 'url', FILTER_DEFAULT) ?? '');

if (!$id || !$title || !$start || !$end) {
    echo json_encode(['success' => false, 'error' => $cal_missing ?? 'Missing parameters']);
    exit;
}

$prx = $prx ?? '';

try {
    $stmt = $db->prepare("UPDATE {$prx}calendar_events SET `title` = :title, `start` = :start, `end` = :end, `note` = :note, `url` = :url WHERE `id` = :id");
    $stmt->execute([
        ':title' => $title,
        ':start' => $start,
        ':end'   => $end,
        ':note'  => $note,
        ':url'   => $url,
        ':id'    => $id,
    ]);
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
