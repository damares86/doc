<?php

declare(strict_types=1);

##############    Damares    ###############
#                                          #
#    A backend project by DM WebLab        #
#   Website: https://www.dmweblab.com      #
#   GitHub: https://github.com/damares86   #
#                                          #
############################################

$prefixFile = __DIR__ . '/../core/prefix.php';
$prefix = '';
if (is_file($prefixFile)) {
    require_once $prefixFile;
}

require_once __DIR__ . '/damares_version.php';

// Register autoloader for class directory
spl_autoload_register(static function (string $class): void {
    $file = __DIR__ . "/../class/{$class}.php";
    if (is_file($file)) {
        require_once $file;
    }
});

// Composer autoloader if present
$vendorAutoload = __DIR__ . '/../vendor/autoload.php';
if (is_file($vendorAutoload)) {
    require_once $vendorAutoload;
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

// Fallback dynamic instantiation file for backward compatibility with plugins
if (!is_file(__DIR__ . '/class_initialize.php')) {
    $classFiles = glob(__DIR__ . '/../class/*.php') ?: [];
    rsort($classFiles);
    $initContent = "<?php\n// Auto-generated class initialization\n";
    foreach ($classFiles as $cFile) {
        $cName = pathinfo($cFile, PATHINFO_FILENAME);
        $varName = strtolower($cName);
        $initContent .= "\${$varName} = new {$cName}(\$db);\n";
    }
    if (!empty($prefix)) {
        $initContent .= "\$common->prx = '{$prefix}_';\n";
    }
    file_put_contents(__DIR__ . '/class_initialize.php', $initContent);
}

// Session check
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check login status
if (!isset($_SESSION['loggedin']) && !isset($_SESSION['account_id'])) {
    require_once __DIR__ . '/check_cookie.php';
    header('Location: ../login/auth-login.php?err=noLogin');
    exit;
}

if (isset($_COOKIE['damares-login'])) {
    $pieces = explode(',', (string) $_COOKIE['damares-login']);
    if (count($pieces) >= 2) {
        $auth->id = (int) $pieces[0];
        $auth->auth_token = $pieces[1];

        if ($auth->checkCookie() <= 0) {
            header('Location: ../login/auth-login.php?err=noLogin');
            exit;
        }

        $role->id = (int) ($_SESSION['role_id'] ?? 0);

        $setting->name = 'role_redirect';
        $stmt = $setting->showAllWhere('id', ['name']);
        $row = $stmt ? $stmt->fetch(PDO::FETCH_ASSOC) : null;
        $redir = $row['value'] ?? '0';

        if ((string) $redir === '1') {
            $stmt = $role->showAllWhere('id', ['id']);
            $row = $stmt ? $stmt->fetch(PDO::FETCH_ASSOC) : null;
            if ($row && !empty($row['redirect']) && $row['redirect'] !== 'none') {
                header('Location: ' . $row['redirect']);
                exit;
            }
        }
    }
}

$export = false;
$plugin->pluginname = 'export_xlsx';
if ($plugin->itemExists('pluginname') && (int) $plugin->isActive() === 1) {
    $export = true;
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

// Determine active page
$page = filter_input(INPUT_GET, 'p', FILTER_DEFAULT);
if (empty($page)) {
    $page = 'index';
}
$page = preg_replace('/[^a-zA-Z0-9_-]/', '', (string) $page);

// Check page tree hierarchy
$pageLabel = '';
$pageLink = '';
$pageId = '';
$check_parent = 0;

$parent = $section->showByLink($page, 'section_parent');
$child = $section->showByLink($page, 'section_child');

if ($parent) {
    $pageLabel = (string) ($parent['label'] ?? '');
    $pageLink = (string) ($parent['link'] ?? '');
    $pageId = (string) ($parent['id'] ?? '');
    $check_parent = (int) $pageId;
} elseif ($child) {
    $pageLabel = (string) ($child['label'] ?? '');
    $pageLink = (string) ($child['link'] ?? '');
    $pageId = (string) ($child['id'] ?? '');
    $check_parent = 0;
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

$apex = '';
