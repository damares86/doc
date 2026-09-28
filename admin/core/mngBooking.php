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

// Debug check
$setting->name = 'debug';
$dbgStmt = $setting->showAllWhere('id', ['name']);
$rowDebug = $dbgStmt->fetch(PDO::FETCH_ASSOC);
if (($rowDebug['value'] ?? '0') === '1' && class_exists('\bdk\Debug')) {
    new \bdk\Debug(['collect' => true, 'output' => true]);
}

// Locale loading
$setting->name = 'lang';
$stmtLang = $setting->showByName();
$lang = is_array($stmtLang) && !empty($stmtLang['value']) ? (string) $stmtLang['value'] : 'it';
$_SESSION['lang'] = $lang;

$localeFiles = glob(__DIR__ . "/../locale/{$lang}/*.php") ?: [];
foreach ($localeFiles as $locFile) {
    require_once $locFile;
}

$operation = (string) filter_input(INPUT_POST, 'operation');

if ($operation === 'settings') {
    if (!isset($_SESSION['loggedin'])) {
        header('Location: ../../login/auth-login.php?err=noLogin');
        exit;
    }

    $post = $_POST;
    unset($post['operation']);

    $error = 0;
    $doc->table = 'doc_settings';

    foreach ($post as $key => $value) {
        $doc->name = (string) $key;
        $doc->value = is_scalar($value) ? (string) $value : '';
        if (!$doc->updateValue()) {
            $error++;
        }
    }

    if ($error === 0) {
        header('Location: ../index.php?p=bookingSettings&msg=settingUpdate');
    } else {
        header('Location: ../index.php?p=bookingSettings&err=settingUpdateErr');
    }
    exit;
}

if ($operation === 'timetable') {
    if (!isset($_SESSION['loggedin'])) {
        header('Location: ../../login/auth-login.php?err=noLogin');
        exit;
    }

    $doc->table = 'doc_timetable';
    $error = 0;

    for ($i = 1; $i <= 5; $i++) {
        $doc->weekday = $i;
        $doc->start_time = isset($_POST["start_{$i}"]) ? (string) $_POST["start_{$i}"] : '08:00';
        $doc->end_time = isset($_POST["end_{$i}"]) ? (string) $_POST["end_{$i}"] : '12:00';
        $doc->booking = isset($_POST["booking_{$i}"]) ? 0 : 1;

        if (!$doc->update(['start_time', 'end_time', 'booking'], 'weekday')) {
            $error++;
        }
    }

    if ($error === 0) {
        header('Location: ../index.php?p=bookingSettings&msg=timetableUpdate');
    } else {
        header('Location: ../index.php?p=bookingSettings&err=timetableUpdateErr');
    }
    exit;
}

if ($operation === 'msg') {
    if (!isset($_SESSION['loggedin'])) {
        header('Location: ../../login/auth-login.php?err=noLogin');
        exit;
    }

    $doc->table = 'doc_messages';
    $doc->id = 1;
    $doc->msg = (string) (filter_input(INPUT_POST, 'msg') ?? '');
    $doc->start_time = (string) (filter_input(INPUT_POST, 'start') ?? '');
    $doc->end_time = (string) (filter_input(INPUT_POST, 'end') ?? '');

    if ($doc->update(['msg', 'start_time', 'end_time'], 'id')) {
        header('Location: ../index.php?p=addMessage&msg=msgUpdate');
    } else {
        header('Location: ../index.php?p=addMessage&err=msgUpdateErr');
    }
    exit;
}

if ($operation === 'booking') {
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

    $doc->table = 'doc_bookings';

    $date = (string) filter_input(INPUT_POST, 'date');
    $time = (string) filter_input(INPUT_POST, 'time');

    $dtObj = DateTime::createFromFormat('Y-m-d H:i', $date . ' ' . $time);
    if (!$dtObj) {
        header('Location: ../../booking.php?err=notBooked');
        exit;
    }

    $italianDate = $dtObj->format('d/m/Y');
    $doc->slot_booked = $dtObj->format('Y-m-d H:i:s');

    // Controllo doppie prenotazioni
    if ($doc->itemExists('slot_booked')) {
        header('Location: ../../booking.php?err=bookingExists');
        exit;
    }

    $patientName = trim((string) filter_input(INPUT_POST, 'name'));
    $email = trim((string) filter_input(INPUT_POST, 'email'));
    $phone = trim((string) filter_input(INPUT_POST, 'phone'));
    $reasonId = (int) filter_input(INPUT_POST, 'reason_id');
    $notes = trim((string) (filter_input(INPUT_POST, 'notes') ?? ''));
    $token = bin2hex(random_bytes(16));

    $doc->name = $patientName;
    $doc->email = $email;
    $doc->phone = $phone;
    $doc->reason_id = $reasonId;
    $doc->notes = $notes;
    $doc->token = $token;

    if ($doc->insert(['name', 'email', 'phone', 'reason_id', 'notes', 'slot_booked', 'token'])) {
        // Recupero email mittente
        $doc->table = 'doc_settings';
        $doc->name = 'email';
        $stmt = $doc->showAllWhere('id', ['name']);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $from = !empty($row['value']) ? (string) $row['value'] : 'noreply@dmweblab.com';

        $subject = "Prenotazione visita - {$italianDate}";

        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'dottoressacrosetto.it';
        $deleteUrl = "{$scheme}://{$host}/delete_booking.php?email=" . urlencode($email) . '&token=' . urlencode($token);

        $block1 = $doc_block1 ?? '<html><body><p>Buongiorno,</p><p>Si conferma la prenotazione della visita. Di seguito i dati:</p>';
        $block2 = $doc_block2 ?? '<p>In caso non potesse più venire in visita, chiedo gentilmente di eliminare la prenotazione cliccando sul seguente link:</p>';
        $block3 = $doc_block3 ?? '<p>Grazie,<br>Dottoressa Crosetto Maria Chiara</p></body></html>';

        $output = $block1;
        $output .= "<p>Paziente: <b>" . htmlspecialchars($patientName, ENT_QUOTES, 'UTF-8') . "</b><br>";
        $output .= "Data della visita: <b>{$italianDate} - {$time}</b></p>";
        $output .= $block2;
        $output .= '<p><a href="' . htmlspecialchars($deleteUrl, ENT_QUOTES, 'UTF-8') . '" target="_blank">Cancella appuntamento</a></p>';
        $output .= $block3;

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
                $mail->setFrom($from, 'Prenotazioni - Dottoressa Crosetto');
                $mail->addAddress($email, $patientName);
                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body = $output;
                $mail->send();
            }

            header('Location: ../../index.php?msg=sentBookingMail');
            exit;
        } catch (\Throwable $e) {
            error_log('PHPMailer Error: ' . $e->getMessage());
            header('Location: ../../index.php?err=errSendBookingMail');
            exit;
        }
    } else {
        header('Location: ../../booking.php?err=notBooked');
        exit;
    }
}

if ($operation === 'deleteEmail') {
    $doc->table = 'doc_bookings';
    $email = (string) filter_input(INPUT_POST, 'email');
    $token = (string) filter_input(INPUT_POST, 'token');

    $doc->email = $email;
    $doc->token = $token;

    $stmt = $doc->showAllWhere('id', ['email', 'token']);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row && !empty($row['id'])) {
        $doc->id = (int) $row['id'];
        if ($doc->delete('id')) {
            header('Location: ../../index.php?msg=bookingDeleteSucc');
        } else {
            header('Location: ../../index.php?err=bookingDeleteFail');
        }
    } else {
        header('Location: ../../index.php?err=bookingDeleteFail');
    }
    exit;
}

header('Location: ../../index.php');
exit;
