<?php

declare(strict_types=1);

require_once __DIR__ . '/../admin/core/prefix.php';
require_once __DIR__ . '/../admin/inc/damares_version.php';

spl_autoload_register(static function (string $class): void {
    $file = __DIR__ . "/../admin/class/{$class}.php";
    if (is_file($file)) {
        require_once $file;
    }
});

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$database = new Database();
$db = $database->getConnection();

$account = new Account($db);
$accountroles = new AccountRoles($db);
$auth = new Auth($db);
$common = new Common($db);
$doc = new Doc($db);
$file = new File($db);
$home = new Home($db);
$plugin = new Plugin($db);
$role = new Role($db);
$rolessection = new RolesSection($db);
$section = new Section($db);
$setting = new Setting($db);

$prx = !empty($prefix) ? $prefix . '_' : '';
$account->prx = $prx;
$accountroles->prx = $prx;
$auth->prx = $prx;
$common->prx = $prx;
$doc->prx = $prx;
$file->prx = $prx;
$home->prx = $prx;
$plugin->prx = $prx;
$role->prx = $prx;
$rolessection->prx = $prx;
$section->prx = $prx;
$setting->prx = $prx;

$adminUser = 0;

// Check if user is logged in via cookie
if (isset($_COOKIE['damares-login'])) {
    $cookieVal = (string) $_COOKIE['damares-login'];
    $pieces = explode(',', $cookieVal);
    if (count($pieces) >= 2) {
        $authId = (int) $pieces[0];
        $authToken = (string) $pieces[1];

        $auth->id = $authId;
        $auth->auth_token = $authToken;

        if ($auth->checkCookie() > 0) {
            $accountroles->account_id = $authId;
            $account->id = $authId;

            $stmt = $account->showAllWhere('id', ['id']);
            $row = $stmt ? $stmt->fetch(PDO::FETCH_ASSOC) : null;

            $role_id = (int) $accountroles->showAccountRolesId();
            $role->id = $role_id;

            $_SESSION['loggedin'] = true;
            $_SESSION['account_id'] = $authId;
            $_SESSION['internal'] = 1;
            $_SESSION['role_id'] = $role_id;
            $_SESSION['rolename'] = (string) $role->showRolenameById();
            $_SESSION['username'] = (string) ($row['username'] ?? '');
            $_SESSION['avatar'] = (string) ($row['avatar'] ?? 'default.png');

            $auth->updateLog(date('Y-m-d H:i:s'));
            $adminUser = 1;
        }
    }
}

// Debug mode check
$setting->name = 'debug';
$dbg = $setting->showAllWhere('id', ['name']);
$rowDebug = $dbg ? $dbg->fetch(PDO::FETCH_ASSOC) : null;
if (($rowDebug['value'] ?? '0') === '1' && is_file(__DIR__ . '/../admin/vendor/autoload.php')) {
    require_once __DIR__ . '/../admin/vendor/autoload.php';
    if (class_exists('\bdk\Debug')) {
        new \bdk\Debug(['collect' => true, 'output' => true]);
    }
}

// Locale loading
$setting->name = 'lang';
$stmtLang = $setting->showByName();
$lang = is_array($stmtLang) && !empty($stmtLang['value']) ? (string) $stmtLang['value'] : 'it';
$_SESSION['lang'] = $lang;

$localeFiles = glob(__DIR__ . "/../admin/locale/{$lang}/*.php") ?: [];
foreach ($localeFiles as $locFile) {
    require_once $locFile;
}
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang, ENT_QUOTES, 'UTF-8') ?>">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Studio Medico Dott.ssa Maria Chiara Crosetto">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black">

  <title>Dottoressa Crosetto Maria Chiara - Giaveno</title>

  <!-- Favicon -->
  <link rel="icon" href="admin/assets/images/logo/favicon.ico">

  <!-- Style CSS -->
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="assets/css/custom.css">

  <!-- Web App Manifest -->
  <link rel="manifest" href="manifest.json">
  <script src="https://cdn.cookie-script.com/s/e351f680b4229f9cc2448b88296e0bf2.js"></script>
  <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
</head>

<body>
  <!-- Header Area -->
  <div class="header-area" id="headerArea">
    <div class="container">
      <div class="header-content header-style-five position-relative p-2 align-items-center justify-content-between">
        <div class="logo-wrapper text-center">
          <a href="index.php">Dott.ssa Maria Chiara Crosetto</a>
        </div>
      </div>
    </div>
  </div>