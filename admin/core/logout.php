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

$_SESSION = [];
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params['path'],
        $params['domain'],
        $params['secure'],
        $params['httponly']
    );
}
session_destroy();

spl_autoload_register(static function (string $class): void {
    $file = __DIR__ . "/../class/{$class}.php";
    if (is_file($file)) {
        require_once $file;
    }
});

if (is_file(__DIR__ . '/../class/Database.php')) {
    require_once __DIR__ . '/../class/Database.php';
    $database = new Database();
    $db = $database->getConnection();

    if ($db && isset($_COOKIE['damares-login'])) {
        $pieces = explode(',', (string) $_COOKIE['damares-login']);
        if (!empty($pieces[0])) {
            $account = new Account($db);
            $account->id = (int) $pieces[0];
            $account->auth_token = 'none';
            $account->update(['auth_token'], 'id');
        }
    }
}

if (isset($_COOKIE['damares-login'])) {
    unset($_COOKIE['damares-login']);
    setcookie('damares-login', '', [
        'expires' => time() - 3600,
        'path' => '/',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
}

header('Location: ../index.php');
exit;