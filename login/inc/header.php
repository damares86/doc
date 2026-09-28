<?php

declare(strict_types=1);

##############    Damares    ###############
#                                          #
#    A backend project by DM WebLab        #
#   Website: https://www.dmweblab.com      #
#   GitHub: https://github.com/damares86   #
#                                          #
############################################

if (!is_file(__DIR__ . '/../../admin/class/Database.php')) {
    require_once __DIR__ . '/../../admin/inc/dbdata.php';
    exit;
}

// Autoloader for classes
spl_autoload_register(static function (string $class): void {
    $file = __DIR__ . "/../../admin/class/{$class}.php";
    if (is_file($file)) {
        require_once $file;
    }
});

// Composer autoloader
$vendorAutoload = __DIR__ . '/../../admin/vendor/autoload.php';
if (is_file($vendorAutoload)) {
    require_once $vendorAutoload;
}

$prefixFile = __DIR__ . '/../../admin/core/prefix.php';
$prefix = '';
if (is_file($prefixFile)) {
    require_once $prefixFile;
}

$database = new Database();
$db = $database->getConnection();

// Instantiate core models
$common = new Common($db);
$account = new Account($db);
$auth = new Auth($db);
$role = new Role($db);
$setting = new Setting($db);
$section = new Section($db);
$file = new File($db);
$plugin = new Plugin($db);
$home = new Home($db);
$rolessection = new RolesSection($db);
$accountroles = new AccountRoles($db);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$setting->name = 'role_redirect';
$stmt = $setting->showAllWhere('id', ['name']);
$row = $stmt ? $stmt->fetch(PDO::FETCH_ASSOC) : null;
$redir = (string) ($row['value'] ?? '0');

if (isset($_COOKIE['damares-login'])) {
    $pieces = explode(',', (string) $_COOKIE['damares-login']);
    if (count($pieces) >= 2) {
        $auth->id = (int) $pieces[0];
        $auth->auth_token = $pieces[1];

        if ($auth->checkCookie() > 0) {
            $accountroles->account_id = $auth->id;
            $account->id = $auth->id;

            $stmtAcc = $account->showAllWhere('id', ['id']);
            $rowAcc = $stmtAcc ? $stmtAcc->fetch(PDO::FETCH_ASSOC) : null;

            if ($rowAcc) {
                $roleId = $accountroles->showAccountRolesId();
                $role->id = $roleId;

                $_SESSION['loggedin'] = true;
                $_SESSION['account_id'] = $rowAcc['id'];
                $_SESSION['internal'] = 1;
                $_SESSION['role_id'] = $roleId;
                $_SESSION['rolename'] = $role->showRolenameById() ?? '';
                $_SESSION['username'] = $rowAcc['username'] ?? '';
                $_SESSION['avatar'] = $rowAcc['avatar'] ?? 'default.png';

                $auth->updateLog(date('Y-m-d H:i:s'));

                if ($redir === '1') {
                    $stmtRole = $role->showAllWhere('id', ['id']);
                    $rowRole = $stmtRole ? $stmtRole->fetch(PDO::FETCH_ASSOC) : null;
                    if ($rowRole && !empty($rowRole['redirect']) && $rowRole['redirect'] !== 'none') {
                        header('Location: ' . $rowRole['redirect']);
                        exit;
                    }
                }

                header('Location: ../admin/');
                exit;
            }
        }
    }
} elseif (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    if ($redir === '1' && isset($_SESSION['role_id'])) {
        $role->id = (int) $_SESSION['role_id'];
        $stmtRole = $role->showAllWhere('id', ['id']);
        $rowRole = $stmtRole ? $stmtRole->fetch(PDO::FETCH_ASSOC) : null;
        if ($rowRole && !empty($rowRole['redirect']) && $rowRole['redirect'] !== 'none') {
            header('Location: ' . $rowRole['redirect']);
            exit;
        }
    }
    header('Location: ../admin/');
    exit;
}

// Check debug mode
$setting->name = 'debug';
$dbg = $setting->showAllWhere('id', ['name']);
$row_debug = $dbg ? $dbg->fetch(PDO::FETCH_ASSOC) : null;

if ($row_debug && (string) ($row_debug['value'] ?? '0') === '1') {
    if (class_exists(\bdk\Debug::class)) {
        $debug = new \bdk\Debug([
            'collect' => true,
            'output' => true,
        ]);
    }
}

// Language setting
$setting->name = 'lang';
$stmt = $setting->showByName();
$lang = is_array($stmt) && !empty($stmt['value']) ? (string) $stmt['value'] : 'en';
$_SESSION['lang'] = $lang;

$localeFiles = glob(__DIR__ . "/../../admin/locale/{$lang}/*.php") ?: [];
foreach ($localeFiles as $lFile) {
    require_once $lFile;
}

$plugin->pluginname = 'account_register';
$reg = false;
if ($plugin->itemExists('pluginname') && (int) $plugin->isActive() === 1) {
    $reg = true;
}

$op = (string) (filter_input(INPUT_GET, 'op', FILTER_DEFAULT) ?? '');
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars((string) ($login_titlebar ?? 'Login'), ENT_QUOTES, 'UTF-8') ?> - damares</title>
  <link rel="stylesheet" href="../admin/assets/css/main/app.css" />
  <link rel="stylesheet" href="../admin/assets/css/pages/auth.css" />
  <link rel="stylesheet" href="../admin/assets/css/custom.css">
  <link rel="shortcut icon" href="../admin/assets/images/logo/favicon.ico" type="image/x-icon" />
  <link rel="shortcut icon" href="../admin/assets/images/logo/favicon.ico" type="image/png" />
</head>

<body>
  <div id="auth">
    <div class="row h-100">
      <div class="col-lg-5 col-12">
        <div id="auth-left">
          <div class="auth-logo">
            <a href="../index.php"><img src="../admin/assets/images/logo/damares_logo.png" alt="Logo" /></a>
          </div>

          <?php
          require_once __DIR__ . '/alert.php';
          ?>