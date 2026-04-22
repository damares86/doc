<?php

require __DIR__ . "/coreConfig.php";

header('Content-Type: application/json');

try {
    $query = "
    SELECT 
        e.id, 
        e.title, 
        e.start, 
        e.end, 
        e.note, 
        e.url, 
        e.cat_id,
        c.cat_color AS color,
        c.cat_name AS cat_name
    FROM calendar_events e
    LEFT JOIN calendar_cat c ON e.cat_id = c.id
    ";

    $stmt = $db->query($query);
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($events);
} catch (PDOException $e) {
    echo json_encode([]);
}
