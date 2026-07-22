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
