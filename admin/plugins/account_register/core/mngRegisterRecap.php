<?php

declare(strict_types=1);

##############    Damares    ###############
#                                          #
#    A backend project by DM WebLab        #
#   Website: https://www.dmweblab.com      #
#   GitHub: https://github.com/damares86   #
#                                          #
############################################

$vendorAutoload = __DIR__ . '/../../../vendor/autoload.php';
if (is_file($vendorAutoload)) {
    require_once $vendorAutoload;
}

spl_autoload_register(static function (string $class): void {
    $candidates = [
        __DIR__ . "/../../class/{$class}.php",
        __DIR__ . "/../class/{$class}.php",
        __DIR__ . "/../../../class/{$class}.php",
    ];
    foreach ($candidates as $file) {
        if (is_file($file)) {
            require_once $file;
            return;
        }
    }
});

$database = new Database();
$db = $database->getConnection();

$common = new Common($db);
$account = new Account($db);
$auth = new Auth($db);
$role = new Role($db);
$setting = new Setting($db);
$register = new Register($db);
$verify = new Common($db);
$verify->table = 'verify';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['recaptcha_response'])) {
    $stmt = $verify->showAll('id');
    $row = $stmt ? $stmt->fetch(PDO::FETCH_ASSOC) : null;
    $secret = (string) ($row['secret'] ?? '');

    $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
    $recaptcha_response = (string) ($_POST['recaptcha_response'] ?? '');

    $response = @file_get_contents($recaptcha_url . '?secret=' . urlencode($secret) . '&response=' . urlencode($recaptcha_response));
    $recaptcha = $response ? json_decode($response) : null;

    if ($recaptcha && isset($recaptcha->score) && $recaptcha->score >= 0.5) {
        $setting->name = 'lang';
        $stmt = $setting->showByName();
        $lang = is_array($stmt) && !empty($stmt['value']) ? (string) $stmt['value'] : 'en';

        $localeFiles = glob(__DIR__ . "/../../locale/{$lang}/*.php") ?: [];
        foreach ($localeFiles as $lFile) {
            require_once $lFile;
        }

        if (filter_input(INPUT_POST, 'reg_form')) {
            $email = (string) (filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL) ?? '');
            $auth->email = $email;
            $email_exists = $auth->emailExists();

            if ($email_exists) {
                header('Location: ../../login/auth-register.php?err=mailExists');
                exit;
            }

            $register->email = $email;
            $stmt = $register->showAllWhere('id', ['email']);
            $emailTmp = '';
            $expDate = '';
            if ($stmt) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($row) {
                    $emailTmp = (string) ($row['email'] ?? '');
                    $expDate = (string) ($row['expDate'] ?? '');
                }
            }

            $curDate = date('Y-m-d H:i:s');

            if (empty($emailTmp) || ($expDate < $curDate)) {
                $register->delete('email');

                $expDateNew = date('Y-m-d H:i:s', time() + 7200);
                $token = bin2hex(random_bytes(32));

                $register->token = $token;
                $register->expDate = $expDateNew;
                $register->username = (string) (filter_input(INPUT_POST, 'username', FILTER_DEFAULT) ?? '');

                $password = (string) (filter_input(INPUT_POST, 'password', FILTER_DEFAULT) ?? '');
                $register->password = password_hash($password, PASSWORD_DEFAULT);
                $register->avatar = 'default.png';

                $accountDetailsFile = __DIR__ . '/../../core/accountDetails.php';
                if (!is_file($accountDetailsFile)) {
                    $accountDetailsFile = __DIR__ . '/../../../core/accountDetails.php';
                }
                if (is_file($accountDetailsFile)) {
                    require $accountDetailsFile;
                }

                $details_arr = [];
                if (isset($account_details) && is_array($account_details)) {
                    foreach ($account_details as $item) {
                        $details_arr[] = [$item => (string) ($_POST[$item] ?? '')];
                    }
                }
                $register->details = !empty($details_arr) ? serialize($details_arr) : null;

                $details_opt_arr = [];
                if (isset($account_details_opt) && is_array($account_details_opt)) {
                    foreach ($account_details_opt as $item) {
                        $details_opt_arr[] = [$item => (string) ($_POST[$item] ?? '')];
                    }
                }
                $register->details_opt = !empty($details_opt_arr) ? serialize($details_opt_arr) : null;

                if ($register->insert(['email', 'username', 'password', 'avatar', 'details', 'details_opt', 'token', 'expDate'])) {
                    $url = $_SERVER['SERVER_NAME'] ?? 'localhost';
                    $setting->name = 'noreply';
                    $stmt = $setting->showByName();
                    $from = is_array($stmt) && !empty($stmt['value']) ? (string) $stmt['value'] : 'noreply@example.com';

                    $headers = "MIME-Version: 1.0\r\n";
                    $headers .= "Content-type: text/html; charset=utf-8\r\n";
                    $headers .= "From: {$from}\r\n";
                    $headers .= "Reply-To: {$from}\r\n";
                    $headers .= 'X-Mailer: PHP/' . phpversion();

                    $regUrl = "http://{$url}/login/auth-register.php?email=" . urlencode($email) . "&token={$token}&op=reg";
                    $output = ($reg_block1 ?? '') . "<p><a href=\"{$regUrl}\" target=\"_blank\">{$regUrl}</a></p>" . ($reg_block2 ?? '');

                    $subject = $reg_mail_subject ?? 'Account Confirmation';

                    if (@mail($email, $subject, $output, $headers)) {
                        header('Location: ../../login/auth-register.php?msg=sentRegMail');
                        exit;
                    }

                    header('Location: ../../login/auth-register.php?err=errSendMail');
                    exit;
                }

                header('Location: ../../login/auth-register.php?err=noReg');
                exit;
            }

            header('Location: ../../login/auth-register.php?err=errRegRequest');
            exit;
        }

        if (filter_input(INPUT_POST, 'reg_role')) {
            $role_id = (int) (filter_input(INPUT_POST, 'role', FILTER_VALIDATE_INT) ?? 0);
            $role->id = $role_id;
            $rolename = $role->showRolenameById() ?? '';

            $setting->name = 'reg_role';
            $setting->value = $rolename;
            if ($setting->updateValue()) {
                header('Location: ../index.php?p=setRegister&msg=regRoleUpdated');
                exit;
            }

            header('Location: ../index.php?p=setRegister&err=regRoleNotUpdated');
            exit;
        }

        header('Location: ../../login/auth-register.php?msg=errPost');
        exit;
    }

    header('Location: ../../login/auth-register.php?err=errRecaptcha');
    exit;
}

header('Location: ../../login/auth-register.php?msg=errPost');
exit;
