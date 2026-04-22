<?php

require __DIR__ . "/coreConfig.php";

header('Content-Type: application/json');

$id = $_POST['id'] ?? null;
if (!$id || !is_numeric($id)) {
    echo json_encode(["success" => false, "error" => "ID non valido"]);
    exit;
}

try {
    $stmt = $db->prepare("DELETE FROM calendar_events WHERE id = :id");
    $stmt->execute([':id' => $id]);

    echo json_encode(["success" => true]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}