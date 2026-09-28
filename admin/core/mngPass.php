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

$email = (string) (filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL) ?? '');

$setting->name = 'lang';
$stmt = $setting->showByName();
$lang = is_array($stmt) && !empty($stmt['value']) ? (string) $stmt['value'] : 'en';

$localeFiles = glob(__DIR__ . "/../locale/{$lang}/*.php") ?: [];
foreach ($localeFiles as $lFile) {
    require_once $lFile;
}

$resetForm = filter_input(INPUT_POST, 'resetForm', FILTER_DEFAULT);
$resetMail = filter_input(INPUT_POST, 'resetMail', FILTER_DEFAULT);

if ($resetForm) {
    $auth->email = $email;
    $email_exists = $auth->emailExists();

    if (!$email_exists) {
        header('Location: ../../login/auth-forgot-password.php?err=mailNotReg');
        exit;
    }

    $account->email = $email;
    $pswTmp = $account->getPswTmpDataByEmail();

    $curDate = date('Y-m-d H:i:s');
    $expDate = $pswTmp['expDate'] ?? '';

    if (!$pswTmp || empty($pswTmp['email']) || ($expDate < $curDate)) {
        $account->table = 'password_reset_temp';
        $account->delete('email');

        $expDateNew = date('Y-m-d H:i:s', time() + 7200); // 2 hours validity
        $token = bin2hex(random_bytes(32));

        $account->token = $token;
        $account->expDate = $expDateNew;
        $account->table = 'password_reset_temp';

        if ($account->insert(['email', 'token', 'expDate'])) {
            $url = $_SERVER['SERVER_NAME'] ?? 'localhost';
            $setting->name = 'noreply';
            $stmt = $setting->showByName();
            $from = is_array($stmt) && !empty($stmt['value']) ? (string) $stmt['value'] : 'noreply@example.com';

            $headers = "MIME-Version: 1.0\r\n";
            $headers .= "Content-type: text/html; charset=utf-8\r\n";
            $headers .= "From: {$from}\r\n";
            $headers .= "Reply-To: {$from}\r\n";
            $headers .= 'X-Mailer: PHP/' . phpversion();

            $resetUrl = "http://{$url}/login/auth-forgot-password.php?email=" . urlencode($email) . "&token={$token}&op=reset";
            $output = ($block1 ?? '') . "<p><a href=\"{$resetUrl}\" target=\"_blank\">{$resetUrl}</a></p>" . ($block2 ?? '');

            $subject = 'Reset password Damares';

            if (@mail($email, $subject, $output, $headers)) {
                header('Location: ../../login/auth-login.php?msg=sentMail');
                exit;
            }

            header('Location: ../../login/auth-login.php?err=errSendMail');
            exit;
        }

        header('Location: ../../login/auth-login.php?err=noReset');
        exit;
    }

    header('Location: ../../login/auth-login.php?err=errResetRequest');
    exit;
}

if ($resetMail) {
    $account->email = $email;
    $stmt = $account->showAllWhere('id', ['email']);
    $row = $stmt ? $stmt->fetch(PDO::FETCH_ASSOC) : null;

    $password = (string) ($_POST['password'] ?? '');
    if (empty($password) || !$row) {
        header('Location: ../../login/auth-forgot-password.php?msg=pswEmpty');
        exit;
    }

    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $account->password = $password_hash;
    $account->id = (int) $row['id'];
    $account->table = 'accounts';

    if ($account->update(['password'], 'id')) {
        $account->table = 'password_reset_temp';
        $account->delete('email');
        header('Location: ../../login/auth-login.php?msg=newPass');
        exit;
    }

    header('Location: ../../login/auth-login.php?err=pswEditErr');
    exit;
}

header('Location: ../../login/auth-login.php?msg=errPost');
exit;
