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

spl_autoload_register(static function (string $class): void {
    $file = __DIR__ . "/../class/{$class}.php";
    if (is_file($file)) {
        require_once $file;
    }
});

require_once __DIR__ . '/prefix.php';

$database = new Database();
$db = $database->getConnection();

$setting = new Setting($db);
$doc = new Doc($db);

if (!empty($prefix)) {
    $setting->prx = $prefix . '_';
    $doc->prx = $prefix . '_';
}

if (is_file(__DIR__ . '/../../vendor/autoload.php')) {
    require_once __DIR__ . '/../../vendor/autoload.php';
} elseif (is_file(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Locale loading
$setting->name = 'lang';
$stmtLang = $setting->showByName();
$lang = is_array($stmtLang) && !empty($stmtLang['value']) ? (string) $stmtLang['value'] : 'it';
$_SESSION['lang'] = $lang;

$localeFiles = glob(__DIR__ . "/../locale/{$lang}/*.php") ?: [];
foreach ($localeFiles as $locFile) {
    require_once $locFile;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../index.php?err=errPost');
    exit;
}

$recaptchaSecret = '6Lf5qX4tAAAAAHCtgQlg9TiOHHOeQItDMwZ0_Xuq';
$recaptchaResponse = (string) ($_POST['g-recaptcha-response'] ?? '');

if ($recaptchaResponse === '') {
    header('Location: ../../index.php?err=errRecaptcha');
    exit;
}

$recaptchaUrl = 'https://www.google.com/recaptcha/api/siteverify';
$data = [
    'secret'   => $recaptchaSecret,
    'response' => $recaptchaResponse,
    'remoteip' => $_SERVER['REMOTE_ADDR'] ?? null,
];

$options = [
    'http' => [
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data),
        'timeout' => 10,
    ],
];

$context = stream_context_create($options);
$result = @file_get_contents($recaptchaUrl, false, $context);
$recaptcha = $result ? json_decode($result, false) : null;

if (!$recaptcha || empty($recaptcha->success)) {
    header('Location: ../../index.php?err=errRecaptcha');
    exit;
}

$patientName = trim((string) filter_input(INPUT_POST, 'name'));
$email = trim((string) filter_input(INPUT_POST, 'email'));
$phone = trim((string) filter_input(INPUT_POST, 'phone'));
$notes = trim((string) (filter_input(INPUT_POST, 'notes') ?? ''));
$reasonId = (int) filter_input(INPUT_POST, 'reason_id');

$doc->table = 'doc_contact_reason';
$doc->id = $reasonId;
$stmtReason = $doc->showAllWhere('id', ['id']);
$rowReason = $stmtReason->fetch(PDO::FETCH_ASSOC);
$reasonName = $rowReason ? (string) ($rowReason['name'] ?? 'Richiesta generica') : 'Richiesta generica';

if (isset($_FILES['attachment'])) {
    $attachCheck = $doc->validateAttachment($_FILES['attachment']);
    if ($attachCheck !== true) {
        header('Location: ../../index.php?err=' . urlencode((string) $attachCheck));
        exit;
    }
}

try {
    if (class_exists(PHPMailer::class)) {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'mail.dmweblab.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'noreply@dmweblab.com';
        $mail->Password = 'Salomon-86';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';

        $mail->setFrom('noreply@dmweblab.com', $reasonName);
        $mail->addAddress('dottoressa.crosetto@gmail.com', 'Dottoressa');
        $mail->addReplyTo($email, $patientName);

        $mail->isHTML(true);
        $mail->Subject = $reasonName;

        $safeName = htmlspecialchars($patientName, ENT_QUOTES, 'UTF-8');
        $safeEmail = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
        $safePhone = htmlspecialchars($phone, ENT_QUOTES, 'UTF-8');
        $safeNotes = nl2br(htmlspecialchars($notes, ENT_QUOTES, 'UTF-8'));

        $body = <<<HTML
<html>
<body style="font-family:Arial,sans-serif; font-size:1.1em; line-height:1.6; color:#333;">
    <h3 style="color:#0d6efd;">Nuova richiesta da modulo contatti</h3>
    <p><strong>Tipo richiesta:</strong> {$reasonName}</p>
    <p><strong>Nome:</strong> {$safeName}</p>
    <p><strong>Email:</strong> {$safeEmail}</p>
    <p><strong>Telefono:</strong> {$safePhone}</p>
    <p><strong>Note:</strong><br>{$safeNotes}</p>
</body>
</html>
HTML;

        $mail->Body = $body;

        if (
            isset($_FILES['attachment']) &&
            is_array($_FILES['attachment']) &&
            ($_FILES['attachment']['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK &&
            is_file((string) $_FILES['attachment']['tmp_name'])
        ) {
            $mail->addAttachment(
                (string) $_FILES['attachment']['tmp_name'],
                (string) $_FILES['attachment']['name']
            );
        }

        $mail->send();
    }

    header('Location: ../../index.php?msg=sentContactMail');
    exit;
} catch (\Throwable $e) {
    error_log('Contact Mail Error: ' . $e->getMessage());
    header('Location: ../../index.php?err=errSendContactMail');
    exit;
}
