<?php

require "coreConfig.php";

header('Content-Type: application/json');

$date = $_GET['date'] ?? date('Y-m-d');

$slots = $doc->getAvailableSlots($db, $date);

echo json_encode([
    'date'  => $date,
    'slots' => $slots
]);