<?php

declare(strict_types=1);

##############    Damares    ###############
#                                          #
#    A backend project by DM WebLab        #
#   Website: https://www.dmweblab.com      #
#   GitHub: https://github.com/damares86   #
#                                          #
############################################

spl_autoload_register(static function (string $class): void {
    $file = __DIR__ . "/../class/{$class}.php";
    if (is_file($file)) {
        require_once $file;
    }
});

$database = new Database();
$db = $database->getConnection();

$common = new Common($db);
$account = new Account($db);
$auth = new Auth($db);
$role = new Role($db);
$setting = new Setting($db);
$accountroles = new AccountRoles($db);
$verify = new Common($db);
$verify->table = 'verify';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['recaptcha_response'])) {
    $stmt = $verify->showAll('id');
    $row = $stmt ? $stmt->fetch(PDO::FETCH_ASSOC) : null;
    $secret = (string) ($row['secret'] ?? '');

    $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
    $recaptcha_response = (string) ($_POST['recaptcha_response'] ?? '');

    $url = $recaptcha_url . '?secret=' . urlencode($secret) . '&response=' . urlencode($recaptcha_response);
    $response = @file_get_contents($url);
    $recaptcha = $response ? json_decode($response) : null;

    if ($recaptcha && isset($recaptcha->score) && $recaptcha->score >= 0.5) {
        $postpass = (string) ($_POST['password'] ?? '');
        $email = (string) (filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL) ?? '');
        $auth->email = $email;

        $email_exists = $auth->emailExists();

        if ($email_exists && !empty($auth->password) && password_verify($postpass, (string) $auth->password)) {
            if (!empty($_POST['remember'])) {
                $token = bin2hex(random_bytes(32));
                $account->email = $email;
                $account->auth_token = $token;
                $account->update(['auth_token'], 'email');

                setcookie('damares-login', "{$auth->id},{$token}", [
                    'expires' => time() + (86400 * 30),
                    'path' => '/',
                    'httponly' => true,
                    'samesite' => 'Lax',
                ]);
            }

            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $accountroles->account_id = $auth->id;
            $role_id = $accountroles->showAccountRolesId();
            $role->id = $role_id;

            $_SESSION['loggedin'] = true;
            $_SESSION['account_id'] = $auth->id;
            $_SESSION['internal'] = 1;
            $_SESSION['role_id'] = $role_id;
            $_SESSION['rolename'] = $role->showRolenameById() ?? '';
            $_SESSION['username'] = $auth->username ?? '';
            $_SESSION['avatar'] = $auth->avatar ?? 'default.png';

            $auth->updateLog(date('Y-m-d H:i:s'));

            $setting->name = 'role_redirect';
            $stmt = $setting->showAllWhere('id', ['name']);
            $row = $stmt ? $stmt->fetch(PDO::FETCH_ASSOC) : null;
            $redir = (string) ($row['value'] ?? '0');

            if ($redir === '1') {
                $stmtRole = $role->showAllWhere('id', ['id']);
                $rowRole = $stmtRole ? $stmtRole->fetch(PDO::FETCH_ASSOC) : null;
                if ($rowRole && !empty($rowRole['redirect']) && $rowRole['redirect'] !== 'none') {
                    header('Location: ' . $rowRole['redirect']);
                    exit;
                }
            }

            header('Location: ../');
            exit;
        }

        header('Location: ../../login/auth-login.php?err=errUserPsw');
        exit;
    }

    header('Location: ../../login/auth-login.php?err=errRecaptcha');
    exit;
}

header('Location: ../../login/auth-login.php?msg=errPost');
exit;
