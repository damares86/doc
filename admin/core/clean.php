<?php

declare(strict_types=1);

##############    Damares    ###############
#                                          #
#    A backend project by DM WebLab        #
#   Website: https://www.dmweblab.com      #
#   GitHub: https://github.com/damares86   #
#                                          #
############################################

if (session_status() === PHP_SESSION_ACTIVE) {
    session_destroy();
}

if (is_file(__DIR__ . '/../class/Database.php')) {
    require_once __DIR__ . '/../class/Database.php';
    $database = new Database();
    $db = $database->getConnection();

    $prx = '';
    if (is_file(__DIR__ . '/prefix.php')) {
        $prefix = '';
        require __DIR__ . '/prefix.php';
        $prx = $prefix;
    }

    if ($db) {
        $tables = [
            "{$prx}accounts_roles",
            "{$prx}accounts",
            "{$prx}files",
            "{$prx}home",
            "{$prx}password_reset_temp",
            "{$prx}plugins",
            "{$prx}roles",
            "{$prx}roles_section",
            "{$prx}roles_section_child",
            "{$prx}section_child",
            "{$prx}section_parent",
            "{$prx}settings",
            "{$prx}register_account_temp",
            "{$prx}verify",
        ];
        $escaped = implode(', ', array_map(static fn($t) => "`" . str_replace('`', '', $t) . "`", $tables));
        $db->exec("DROP TABLE IF EXISTS {$escaped}");
    }
}

$excludeArr = ['default', 'default.png', 'Program', 'sd', 'sd.png', '.gitkeep'];

$avatarFiles = glob(__DIR__ . '/../uploads/avatar/*') ?: [];
foreach ($avatarFiles as $aFile) {
    $info = pathinfo($aFile);
    if (!in_array($info['basename'], $excludeArr, true) && !in_array($info['filename'], $excludeArr, true)) {
        @unlink($aFile);
    }
}

$uploadFiles = glob(__DIR__ . '/../uploads/*') ?: [];
foreach ($uploadFiles as $uFile) {
    if (is_file($uFile)) {
        $info = pathinfo($uFile);
        if (!in_array($info['basename'], $excludeArr, true) && !in_array($info['filename'], $excludeArr, true)) {
            @unlink($uFile);
        }
    }
}

@unlink(__DIR__ . '/../class/Database.php');
if (is_file(__DIR__ . '/site.php')) {
    @unlink(__DIR__ . '/site.php');
}
if (is_file(__DIR__ . '/prefix.php')) {
    @unlink(__DIR__ . '/prefix.php');
}
if (is_file(__DIR__ . '/../inc/class_initialize.php')) {
    @unlink(__DIR__ . '/../inc/class_initialize.php');
}

header('Location: ../');
exit;