<?php

require __DIR__ . "/coreConfig.php";

header('Content-Type: application/json');
file_put_contents("debug.log", print_r($_POST, true));

$title = $_POST['title'] ?? null;
$start = $_POST['start'] ?? null;
$end   = $_POST['end'] ?? null;
$url   = $_POST['url'] ?? null;
$url   = $_POST['note'] ?? null;
$color = $_POST['calendar_color'] ?? '1';

if (!$title || !$start || !$end) {
    echo json_encode(["success" => false, "error" => "Campi obbligatori mancanti"]);
    exit;
}

try {
    $stmt = $db->prepare("INSERT INTO calendar_events (title, start, end, url, cat_id) VALUES (:title, :start, :end, :url, :cat_id)");
    $stmt->execute([
        ':title' => $title,
        ':start' => $start,
        ':end'   => $end,
        ':url'   => $url,
        ':cat_id' => $color
    ]);

    echo json_encode(["success" => true, "id" => $db->lastInsertId()]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
