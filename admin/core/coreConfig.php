<?php

declare(strict_types=1);

##############    Damares    ###############
#                                          #
#    A backend project by DM WebLab        #
#   Website: https://www.dmweblab.com      #
#   GitHub: https://github.com/damares86   #
#                                          #
############################################

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['loggedin']) || !isset($_SESSION['account_id'])) {
    header('Location: ../../login/auth-login.php?err=noLogin');
    exit;
}

// Autoloader for classes
spl_autoload_register(static function (string $class): void {
    $file = __DIR__ . "/../class/{$class}.php";
    if (is_file($file)) {
        require_once $file;
    }
});

// Composer autoloader
$vendorAutoload = __DIR__ . '/../vendor/autoload.php';
if (is_file($vendorAutoload)) {
    require_once $vendorAutoload;
}

$prefixFile = __DIR__ . '/prefix.php';
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

// Fallback initialization file
if (is_file(__DIR__ . '/../inc/class_initialize.php')) {
    include_once __DIR__ . '/../inc/class_initialize.php';
}

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

$localeFiles = glob(__DIR__ . "/../locale/{$lang}/*.php") ?: [];
foreach ($localeFiles as $lFile) {
    require_once $lFile;
}
