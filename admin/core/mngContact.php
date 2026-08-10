<?php

##############    Damares    ###############
#                                          #
#    A backend project by DM WebLab        #
#   Website: https://www.dmweblab.com      #
#   GitHub: https://github.com/damares86   #
#                                          #
############################################

require '../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

spl_autoload_register('autoloader');

function autoloader($class)
{
    include("../class/$class.php");
}

require "../core/prefix.php";

$database = new Database();
$db = $database->getConnection();
include "../inc/class_initialize.php";

//$setting->name = "debug";
//$dbg = $setting->showAllWhere('id', ['name']);
//$row_debug = $dbg->fetch(PDO::FETCH_ASSOC);
//extract($row_debug);

$mail = new PHPMailer(true);

/*if ($row_debug['value'] == 1) {
    $debug = new \bdk\Debug(array(
        'collect' => true,
        'output' => true,
    ));
}*/

// check the language set
$setting->name = "lang";
$stmt = $setting->showByName();
$lang = $stmt['value'];
$_SESSION['lang'] = $lang;


foreach (glob("../locale/$lang/*.php") as $row) {
    require "$row";
}

/* if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['recaptcha_response'])) {


    $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
    $recaptcha_secret = '6LetcWAtAAAAAH52Fj00DbbGbjFaxeI-_rkHYX9c';
    $recaptcha_response = $_POST['recaptcha_response'];

    // Istanziare e decodificare la richiesta POST:      

    $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
    $recaptcha = json_decode($recaptcha);

    // Azioni da compiere basate sul punteggio ottenuto:      

    if ($recaptcha->score >= 0.5) { */

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $recaptcha_secret = '6Lf5qX4tAAAAAHCtgQlg9TiOHHOeQItDMwZ0_Xuq';
    $recaptcha_response = $_POST['g-recaptcha-response'] ?? '';

    if (empty($recaptcha_response)) {
        die('Verifica reCAPTCHA non completata.');
    }

    $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';

    $data = [
        'secret'   => $recaptcha_secret,
        'response' => $recaptcha_response,
        'remoteip' => $_SERVER['REMOTE_ADDR'] ?? null
    ];

    $options = [
        'http' => [
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data)
        ]
    ];

    $context = stream_context_create($options);

    $result = file_get_contents($recaptcha_url, false, $context);
    $recaptcha = json_decode($result);

    if (!$recaptcha || empty($recaptcha->success)) {
        die('Verifica reCAPTCHA fallita.');
    }
    if ($recaptcha->success) {

        $patient_name      = filter_input(INPUT_POST, 'name');
        $email     = filter_input(INPUT_POST, 'email');
        $phone     = filter_input(INPUT_POST, 'phone');
        $notes     = filter_input(INPUT_POST, 'notes') ?? '';

        $reason_id = filter_input(INPUT_POST, 'reason_id');

        $doc->table = 'doc_contact_reason';
        $doc->id = $reason_id;
        $stmt_reason = $doc->showAllWhere('id', ['id']);
        $row_reason = $stmt_reason->fetch(PDO::FETCH_ASSOC);
        extract($row_reason);

        try {

            /*
    |--------------------------------------------------------------------------
    | Configurazione SMTP
    |--------------------------------------------------------------------------
    */

            $mail->isSMTP();

            $mail->Host       = 'mail.dmweblab.com ';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'noreply@dmweblab.com';
            $mail->Password   = 'Salomon-86';

            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->CharSet = 'UTF-8';


            /*
    |--------------------------------------------------------------------------
    | Mittente e destinatari
    |--------------------------------------------------------------------------
    */

            $mail->setFrom(
                'noreply@dmweblab.com',
                $row_reason['name']
            );

            // medico
            $mail->addAddress(
                'dottoressa.crosetto@gmail.com',
                'Dottoressa'
            );

            // eventualmente copia segreteria
            //$mail->addCC('segreteria@dominio.it');


            /*
    |--------------------------------------------------------------------------
    | Contenuto
    |--------------------------------------------------------------------------
    */

            $mail->isHTML(true);

            $mail->Subject = $row_reason['name'];

            $body = '

    <html>
    <body style="font-family:Arial,sans-serif; font-size:1.5em">
        <p>
            <strong>Nome:</strong><br>
            ' . $patient_name . '
        </p>

        <p>
            <strong>Email:</strong><br>
            ' . htmlspecialchars($email) . '
        </p>

        <p>
            <strong>Telefono:</strong><br>
            ' . htmlspecialchars($phone) . '
        </p>

        <p>
            <strong>Note:</strong><br>
            ' . nl2br(htmlspecialchars($notes)) . '
        </p>


    </body>
    </html>

    ';

            $result = $doc->validateAttachment($_FILES['attachment']);

            if ($result !== true) {

                header('Location: ../../index.php?err=' . $result);
                exit;
            }

            // Da qui in poi sei sicuro che il file è valido

            $mail->Body = $body;

            /*
|--------------------------------------------------------------------------
| Allegato
|--------------------------------------------------------------------------
*/

            if (
                isset($_FILES['attachment']) &&
                $_FILES['attachment']['error'] === UPLOAD_ERR_OK
            ) {

                $mail->addAttachment(
                    $_FILES['attachment']['tmp_name'],
                    $_FILES['attachment']['name']
                );
            }

            /*
|--------------------------------------------------------------------------
| Invio
|--------------------------------------------------------------------------
*/

            $mail->send();

            header('Location: ../../index.php?msg=sentContactMail');
            exit;
        } catch (Exception $e) {

            header('Location: ../../index.php?err=errSendContactMail');
            exit;
        }
    } else {
        header("Location: ../../index.php?err=errRecaptcha");
        exit;
    }
} else {
    header("Location: ../../index.php?msg=errPost");
    exit;
}
