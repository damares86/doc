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

$prx = $prx ?? '';

try {
    $stmt = $db->prepare("INSERT INTO {$prx}calendar_events (`title`, `start`, `end`, `note`, `url`, `cat_id`) VALUES (:title, :start, :end, :note, :url, :cat_id)");
    $stmt->execute([
        ':title' => $title,
        ':start' => $start,
        ':end'   => $end,
        ':note'  => $note,
        ':url'   => $url,
        ':cat_id' => $color,
    ]);

    echo json_encode(['success' => true, 'id' => $db->lastInsertId()]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
