<?php

require __DIR__ . "/coreConfig.php";
header('Content-Type: application/json');

$id = $_POST['id'] ?? null;
$title = $_POST['title'] ?? null;
$start = $_POST['start'] ?? null;
$end   = $_POST['end'] ?? null;
$note  = $_POST['note'] ?? '';
$url   = $_POST['url'] ?? '';

if (!$id || !$title || !$start || !$end) {
    echo json_encode(["success" => false, "error" => $cal_missing]);
    exit;
}

try {
    $stmt = $db->prepare("UPDATE calendar_events SET title = :title, start = :start, end = :end, note = :note, url = :url WHERE id = :id");
    $stmt->execute([
        ':title' => $title,
        ':start' => $start,
        ':end'   => $end,
        ':note'  => $note,
        ':url'   => $url,
        ':id'    => $id
    ]);
    echo json_encode(["success" => true]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
