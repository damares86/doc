<?php

declare(strict_types=1);

##############    Damares    ###############
#                                          #
#    A backend project by DM WebLab        #
#   Website: https://www.dmweblab.com      #
#   GitHub: https://github.com/damares86   #
#                                          #
############################################

$vendorAutoload = __DIR__ . '/../vendor/autoload.php';
if (is_file($vendorAutoload)) {
    require_once $vendorAutoload;
}

// Create Database class file if not present
if (!is_file(__DIR__ . '/../class/Database.php')) {
    $db_name = (string) (filter_input(INPUT_POST, 'dbname') ?? '');
    $username = (string) (filter_input(INPUT_POST, 'username') ?? '');
    $db_password = (string) (filter_input(INPUT_POST, 'db_password') ?? '');
    $host = (string) (filter_input(INPUT_POST, 'host') ?? 'localhost');

    $content = <<<PHP
<?php

declare(strict_types=1);

class Database
{
    public string \$db_name = '{$db_name}';
    public string \$username = '{$username}';
    public string \$password = '{$db_password}';
    public string \$host = '{$host}';
    public ?PDO \$conn = null;

    public function getConnection(): ?PDO
    {
        \$this->conn = null;
        try {
            \$this->conn = new PDO(
                "mysql:host={\$this->host};dbname={\$this->db_name};charset=utf8mb4",
                \$this->username,
                \$this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
        } catch (PDOException \$exception) {
            echo "Connection error: " . htmlspecialchars(\$exception->getMessage(), ENT_QUOTES, 'UTF-8');
        }
        return \$this->conn;
    }
}
PHP;

    file_put_contents(__DIR__ . '/../class/Database.php', $content);
    @chmod(__DIR__ . '/../class/Database.php', 0644);
}

require_once __DIR__ . '/../class/Database.php';

$database = new Database();
$db = $database->getConnection();

if (!$db) {
    echo 'Unable to connect to database. Please check your credentials.';
    exit;
}

$rawPrefix = (string) ($_POST['prefix'] ?? '');
$prefix = '';
if (!empty($rawPrefix)) {
    $cleanPrx = preg_replace('/[^a-zA-Z0-9_]/', '', $rawPrefix);
    $prefix = str_ends_with($cleanPrx, '_') ? $cleanPrx : $cleanPrx . '_';
}

// Write prefix.php
$prefixContent = "<?php\ndeclare(strict_types=1);\n\$prefix = '{$prefix}';\n";
file_put_contents(__DIR__ . '/prefix.php', $prefixContent);
@chmod(__DIR__ . '/prefix.php', 0644);

// User credentials
$user_email = (string) ($_POST['email'] ?? '');
$password = (string) ($_POST['password'] ?? '');
$password_hash = password_hash($password, PASSWORD_DEFAULT);

/////////////////////////////////////////////////////////////
// Create DB tables
/////////////////////////////////////////////////////////////

$db->exec("CREATE TABLE IF NOT EXISTS {$prefix}files (
    id INT(5) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    filename VARCHAR(255) NOT NULL,
    label VARCHAR(255) NOT NULL
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

$db->exec("CREATE TABLE IF NOT EXISTS {$prefix}accounts (
    id INT(5) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) DEFAULT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    avatar VARCHAR(255) DEFAULT 'default.png',
    details TEXT DEFAULT NULL,
    details_opt TEXT DEFAULT NULL,
    auth_token VARCHAR(255) DEFAULT 'none',
    last_login DATETIME DEFAULT CURRENT_TIMESTAMP
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

$db->exec("CREATE TABLE IF NOT EXISTS {$prefix}roles (
    id INT(5) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    rolename VARCHAR(255) NOT NULL,
    redirect VARCHAR(255) DEFAULT 'none'
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

$db->exec("CREATE TABLE IF NOT EXISTS {$prefix}accounts_roles (
    id INT(5) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    account_id INT(5) NOT NULL,
    role_id INT(5) NOT NULL,
    FOREIGN KEY (account_id) REFERENCES {$prefix}accounts(id) ON DELETE CASCADE,
    FOREIGN KEY (role_id) REFERENCES {$prefix}roles(id) ON DELETE CASCADE
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

$db->exec("CREATE TABLE IF NOT EXISTS {$prefix}section_parent (
    id INT(5) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    link VARCHAR(255) NOT NULL,
    label VARCHAR(255) NOT NULL,
    icon VARCHAR(255) NOT NULL
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

$db->exec("CREATE TABLE IF NOT EXISTS {$prefix}section_child (
    id INT(5) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    link VARCHAR(255) NOT NULL,
    label VARCHAR(255) NOT NULL,
    icon VARCHAR(255) NOT NULL,
    parent_id INT(5) NOT NULL,
    show_menu INT(1) DEFAULT 1
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

$db->exec("CREATE TABLE IF NOT EXISTS {$prefix}roles_section (
    id INT(5) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    section_id VARCHAR(255) NOT NULL,
    role_id INT(5) DEFAULT NULL
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

$db->exec("CREATE TABLE IF NOT EXISTS {$prefix}roles_section_child (
    id INT(5) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    section_id VARCHAR(255) NOT NULL,
    role_id INT(5) DEFAULT NULL
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

$db->exec("CREATE TABLE IF NOT EXISTS {$prefix}password_reset_temp (
    email VARCHAR(250) NOT NULL PRIMARY KEY,
    token VARCHAR(250) NOT NULL,
    expDate DATETIME NOT NULL
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

$db->exec("CREATE TABLE IF NOT EXISTS {$prefix}settings (
    id INT(5) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    value VARCHAR(255) NOT NULL
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

$db->exec("CREATE TABLE IF NOT EXISTS {$prefix}plugins (
    id INT(5) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    pluginname VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    installed INT(1) DEFAULT 0,
    active INT(1) DEFAULT 0
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

$db->exec("CREATE TABLE IF NOT EXISTS {$prefix}home (
    id INT(5) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    content TEXT,
    size INT(2) NOT NULL
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

/////////////////////////////////////////////////////////////
// Seed Default Data via Prepared Statements
/////////////////////////////////////////////////////////////

// Accounts
$stmtAcc = $db->prepare("INSERT INTO {$prefix}accounts (id, username, password, email, avatar) VALUES (:id, :username, :password, :email, :avatar)");
$stmtAcc->execute([
    ':id' => 1,
    ':username' => 'dmweblab',
    ':password' => '$2y$10$EibgaewcRGXQhrMQswfqRuJQeWKMkGxHcWC62HV1g39CmtuU3lG9.',
    ':email' => 'davidemasera@gmail.com',
    ':avatar' => 'sd.png',
]);

$stmtAcc->execute([
    ':id' => 2,
    ':username' => 'admin',
    ':password' => $password_hash,
    ':email' => $user_email,
    ':avatar' => 'default.png',
]);

// Roles
$stmtRole = $db->prepare("INSERT INTO {$prefix}roles (id, rolename) VALUES (:id, :rolename)");
$stmtRole->execute([':id' => 1, ':rolename' => 'Root']);
$stmtRole->execute([':id' => 2, ':rolename' => 'Admin']);

// AccountsRoles
$stmtAccRole = $db->prepare("INSERT INTO {$prefix}accounts_roles (id, account_id, role_id) VALUES (:id, :acc, :role)");
$stmtAccRole->execute([':id' => 1, ':acc' => 1, ':role' => 1]);
$stmtAccRole->execute([':id' => 2, ':acc' => 2, ':role' => 2]);

// Settings
$stmtSet = $db->prepare("INSERT INTO {$prefix}settings (id, name, value) VALUES (:id, :name, :value)");
$defaultSettings = [
    [1, 'lang', 'en'],
    [2, 'noreply', 'noreply@mail.com'],
    [3, 'license', 'none'],
    [4, 'debug', '0'],
    [5, 'layout', 'v'],
    [6, 'role_redirect', '0'],
];
foreach ($defaultSettings as [$sId, $sName, $sVal]) {
    $stmtSet->execute([':id' => $sId, ':name' => $sName, ':value' => $sVal]);
}

// Section Parents
$stmtSecP = $db->prepare("INSERT INTO {$prefix}section_parent (id, link, label, icon) VALUES (:id, :link, :label, :icon)");
$parents = [
    [1, 'index', 'Dashboard', 'grid-fill'],
    [2, 'accounts', 'Accounts', 'people-fill'],
    [3, 'allFiles', 'Files', 'folder-fill'],
    [4, 'allSettings', 'Settings', 'tools'],
    [5, 'damares', 'Damares', 'dice-6-fill'],
    [6, 'allPlugins', 'Modules', 'plus-circle-fill'],
];
foreach ($parents as [$pId, $pLink, $pLabel, $pIcon]) {
    $stmtSecP->execute([':id' => $pId, ':link' => $pLink, ':label' => $pLabel, ':icon' => $pIcon]);
}

// Section Children
$stmtSecC = $db->prepare("INSERT INTO {$prefix}section_child (id, link, label, icon, parent_id, show_menu) VALUES (:id, :link, :label, :icon, :parent_id, :show_menu)");
$children = [
    [1, 'allAccounts', 'All accounts', 'people-fill', 2, 1],
    [2, 'addAccount', 'Add account', 'person-plus-fill', 2, 1],
    [3, 'editAccount', 'Edit account', 'icon', 2, 0],
    [4, 'allRoles', 'All Roles', 'key-fill', 2, 1],
    [5, 'addRole', 'Add role', 'icon', 2, 0],
    [6, 'editRole', 'Edit role', 'icon', 2, 0],
];
foreach ($children as [$cId, $cLink, $cLabel, $cIcon, $cPid, $cShow]) {
    $stmtSecC->execute([':id' => $cId, ':link' => $cLink, ':label' => $cLabel, ':icon' => $cIcon, ':parent_id' => $cPid, ':show_menu' => $cShow]);
}

// Roles Section Permissions
$stmtRS = $db->prepare("INSERT INTO {$prefix}roles_section (id, section_id, role_id) VALUES (:id, :section_id, :role_id)");
$stmtRS->execute([':id' => 1, ':section_id' => '1,2,3,4,5,6', ':role_id' => 1]);
$stmtRS->execute([':id' => 2, ':section_id' => '1,2,3', ':role_id' => 2]);

$stmtRSC = $db->prepare("INSERT INTO {$prefix}roles_section_child (id, section_id, role_id) VALUES (:id, :section_id, :role_id)");
$stmtRSC->execute([':id' => 1, ':section_id' => '1,2,3,4,5,6', ':role_id' => 1]);
$stmtRSC->execute([':id' => 2, ':section_id' => '1,2,3,4,5,6', ':role_id' => 2]);

// Home Blocks
$stmtHome = $db->prepare("INSERT INTO {$prefix}home (id, content, size) VALUES (:id, :content, :size)");
$stmtHome->execute([':id' => 1, ':content' => 'welcome.php', ':size' => 6]);
$stmtHome->execute([':id' => 2, ':content' => 'manuals.php', ':size' => 3]);
$stmtHome->execute([':id' => 3, ':content' => 'last_login.php', ':size' => 3]);

// Plugins scanning
$pluginsDir = __DIR__ . '/../plugins';
if (is_dir($pluginsDir)) {
    $plugins = scandir($pluginsDir) ?: [];
    $exclude = ['..', '.', '.gitkeep', 'base_module'];
    $stmtPlug = $db->prepare("INSERT INTO {$prefix}plugins (id, pluginname, description, installed, active) VALUES (:id, :pluginname, :description, 0, 0)");
    $pIdx = 1;

    foreach ($plugins as $val) {
        if (!in_array($val, $exclude, true) && is_dir("{$pluginsDir}/{$val}")) {
            $desc = '';
            $cfg = "{$pluginsDir}/{$val}/config.php";
            if (is_file($cfg)) {
                $description = '';
                require $cfg;
                $desc = $description ?? '';
            }
            $stmtPlug->execute([
                ':id' => $pIdx,
                ':pluginname' => $val,
                ':description' => $desc,
            ]);
            $pIdx++;
        }
    }
}

header('Location: ../../login/auth-login.php');
exit;
