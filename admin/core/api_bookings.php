<?php
require __DIR__ . "/coreConfig.php";

$doc->table = 'doc_bookings';

$stmt = $doc->showAll('id');
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$events = [];

foreach ($rows as $row) {
    $start = $row['slot_booked'];

    // durata 15 min (puoi cambiarla)
    $end = date("Y-m-d H:i:s", strtotime($start . " +15 minutes"));

    $events[] = [
        'title' => $row['name'],
        'start' => $start,
        'end' => $end
    ];
}

echo json_encode($events);

?>