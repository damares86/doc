<?php

declare(strict_types=1);

spl_autoload_register(static function (string $class): void {
    $file = __DIR__ . "/../class/{$class}.php";
    if (is_file($file)) {
        require_once $file;
    }
});

require_once __DIR__ . '/prefix.php';

$database = new Database();
$db = $database->getConnection();
$doc = new Doc($db);
if (!empty($prefix)) {
    $doc->prx = $prefix . '_';
}

header('Content-Type: application/json; charset=UTF-8');

$date = (string) ($_GET['date'] ?? date('Y-m-d'));
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
    $date = date('Y-m-d');
}

// Giorno della settimana (1=Lunedì ... 7=Domenica)
$weekday = (int) date('N', (int) strtotime($date));

$doc->weekday = $weekday;
$doc->table = 'doc_timetable';

$stmt = $doc->showAllWhere('id', ['weekday']);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$free = false;
if ($row) {
    $free = ((int) ($row['booking'] ?? 1) === 0);
}

$slots = $doc->getAvailableSlots($db, $date);

echo json_encode([
    'date'  => $date,
    'free'  => $free,
    'slots' => $slots,
], JSON_THROW_ON_ERROR);