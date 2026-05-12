<?php
require "admin/core/prefix.php";
require "admin/inc/damares_version.php";

spl_autoload_register('autoloader');

function autoloader($class)
{
  include("admin/class/$class.php");
}

$database = new Database();
$db = $database->getConnection();

// recall of all the classes
$files = glob("admin/class/*.php", GLOB_BRACE);
rsort($files);

// creation of the file with all the initialization of the classes
if (!is_file('admin/inc/class_initialize.php')) {
  $file_handle = fopen('admin/inc/class_initialize.php', 'w');
  fwrite($file_handle, '<?php');
  fwrite($file_handle, "\n");
  foreach ($files as $filename) {
    $nomefile = pathinfo($filename);
    $file = $nomefile['filename'];
    $file_var = strtolower($file);
    fwrite($file_handle, '$' . $file_var . ' = new ' . $file . '($db);');
    fwrite($file_handle, "\n");
  }
  if ($prefix) {
    fwrite($file_handle, '$common->prx = "' . $prefix . '_";');
    fwrite($file_handle, "\n");
  }
  fwrite($file_handle, "?>");
  chmod('admin/inc/class_initialize.php', 0777);
}
include "admin/inc/class_initialize.php";

$adminUser = 0;
// check if the user is logged
if (isset($_COOKIE['damares-login'])) {
  $pieces = explode(",", $_COOKIE['damares-login']);
  $auth->id = $pieces[0];
  $id = $pieces[0];
  $auth->auth_token = $pieces[1];
  if ($auth->checkCookie() > 0) {

    session_start();
    $accountroles->account_id = $id;

    $account->id = $id;

    $stmt = $account->showAllWhere('id', ['id']);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $role_id = $accountroles->showAccountRolesId();
    $role->id = $role_id;

    // set session data
    $_SESSION['loggedin'] = true;
    $_SESSION['account_id'] = $row['id'];
    $_SESSION['internal'] = 1;
    $_SESSION['role_id'] = $role_id;
    $_SESSION['rolename'] = $role->showRolenameById();
    $_SESSION['username'] = $row['username'];
    $_SESSION['avatar'] = $row['avatar'];

    // update the login log time
    $time = date("Y-m-d G:i:s");
    $auth->updateLog($time);

    $adminUser = 1;
  }
}


// check if the debug mode is active
$setting->name = "debug";
$dbg = $setting->showAllWhere('id', ['name']);
$row_debug = $dbg->fetch(PDO::FETCH_ASSOC);
extract($row_debug);

if ($row_debug['value'] == 1) {
  require 'admin/vendor/autoload.php';        // If installed via composer
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

foreach (glob("admin/locale/$lang/*.php") as $row) {
  require "$row";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Agnelli Manager">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <!-- The above 4 meta tags *must* come first in the head; any other head content must come *after* these tags -->

  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black">

  <!-- Title -->
  <title>Dottoressa Crosetto Maria Chiara - Giaveno</title>

  <!-- Favicon -->
  <link rel="icon" href="admin/assets/images/logo/favicon.ico">

  <!-- Style CSS -->
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="assets/css/custom.css">

  <!-- Web App Manifest -->
  <link rel="manifest" href="manifest.json">
  <!-- Bootstrap 5.3 -->
  <!--<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">-->

  <!-- DataTables -->
  <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

</head>

<body>
  <!-- Preloader -->
  <div id="preloader">
    <div class="spinner-grow text-primary" role="status">
      <span class="visually-hidden">Loading...</span>
    </div>
  </div>

  <!-- Header Area -->
  <div class="header-area" id="headerArea">
    <div class="container">
      <!-- Header Content -->
      <div class="header-content header-style-five position-relative d-flex align-items-center justify-content-between">
        <!-- Logo Wrapper -->
        <div class="logo-wrapper">
          <a href="index.php">
            <img class="logo-light" src="admin/assets/images/logo/dmweblab_logo.png" alt="">
            <img class="logo-dark" src="admin/assets/images/logo/dmweblab_logo.png" alt="">
          </a>
        </div>

        <!-- Navbar Toggler -->
        <div class="navbar-toggler" id="affanNavbarToggler" data-bs-toggle="offcanvas" data-bs-target="#affanOffcanvas"
          aria-controls="affanOffcanvas">
          <span class="d-block"></span>
          <span class="d-block"></span>
          <span class="d-block"></span>
        </div>
      </div>
    </div>
  </div>