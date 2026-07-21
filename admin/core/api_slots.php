<?php


spl_autoload_register('autoloader');

function autoloader($class)
{
	include("../class/$class.php");
}

require "../core/prefix.php";

$database = new Database();
$db = $database->getConnection();
include "../inc/class_initialize.php";

$setting->name = "debug";
$dbg = $setting->showAllWhere('id', ['name']);
$row_debug = $dbg->fetch(PDO::FETCH_ASSOC);
extract($row_debug);

if ($row_debug['value'] == 1) {
	require '../vendor/autoload.php';		// If installed via composer
	$debug = new \bdk\Debug(array(
		'collect' => true,
		'output' => true,
	));
}

// check the language set
$setting->name = "lang";
$stmt = $setting->showByName();
$lang = $stmt['value'];
$_SESSION['lang'] = $lang;


foreach (glob("../locale/$lang/*.php") as $row) {
	require "$row";
}

header('Content-Type: application/json');

$date = $_GET['date'] ?? date('Y-m-d');

// Giorno della settimana (1=Lunedì ... 7=Domenica)
$weekday = date('N', strtotime($date));

$doc->weekday = $weekday;
$doc->table = 'doc_timetable';

$stmt = $doc->showAllWhere('id', ['weekday']);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

// Se non esiste alcuna configurazione considero non libero
$free = false;

if ($row) {
    $free = ((int)$row['booking'] === 0);
}

$slots = $doc->getAvailableSlots($db, $date);

echo json_encode([
    'date'  => $date,
    'free'  => $free,
    'slots' => $slots
]);