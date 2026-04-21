<?php


session_start();

if (!isset($_SESSION['loggedin'])) {
	header('Location: ../../login/auth-login.php?err=noLogin');
    exit;
}

spl_autoload_register('autoloader');

function autoloader($class){
	include("../class/$class.php");
}

require "../core/prefix.php";

$database = new Database();
$db = $database->getConnection();

include "../inc/class_initialize.php";

$setting->name = "debug" ;
$dbg = $setting->showAllWhere('id',['name']);
$row_debug = $dbg->fetch(PDO::FETCH_ASSOC);
extract($row_debug);

if($row_debug['value']==1){
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
