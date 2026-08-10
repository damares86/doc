<?php

##############    Damares    ###############
#                                          #
#    A backend project by DM WebLab        #
#   Website: https://www.dmweblab.com      #
#   GitHub: https://github.com/damares86   #
#                                          #
############################################


spl_autoload_register('autoloader');

function autoloader($class)
{
    include("../class/$class.php");
}

require "../core/prefix.php";

$database = new Database();
$db = $database->getConnection();
include "../inc/class_initialize.php";

$setting->name = "debug";
$dbg = $setting->showAllWhere('id', ['name']);
$row_debug = $dbg->fetch(PDO::FETCH_ASSOC);
extract($row_debug);

if ($row_debug['value'] == 1) {
    require '../vendor/autoload.php';        // If installed via composer
    $debug = new \bdk\Debug(array(
        'collect' => true,
        'output' => true,
    ));
}

// check the language set
$setting->name = "lang";
$stmt = $setting->showByName();
$lang = $stmt['value'];
$_SESSION['lang'] = $lang;


foreach (glob("../locale/$lang/*.php") as $row) {
    require "$row";
}


$operation = filter_input(INPUT_POST, "operation");

if ($operation == "settings") {

    $post = $_POST;

    $error = 0;
    $doc->table = "doc_settings";

    foreach ($post as $key => $value) {

        $doc->name = $key;
        $doc->value = $value;
        if (!$doc->updateValue()) {
            $error++;
        }
    }

    if ($error == 0) {
        header("Location: ../index.php?p=bookingSettings&msg=settingUpdate");
        exit;
    } else {
        header("Location: ../index.php?p=bookingSettings&err=settingUpdateErr");
        exit;
    }
} else if ($operation == "timetable") {

    $doc->table = "doc_timetable";

    $error = 0;

    for ($i = 1; $i < 6; $i++) {

        $doc->weekday = $i;
        $doc->start_time = $_POST["start_$i"];
        $doc->end_time = $_POST["end_$i"];
        isset($_POST["booking_$i"]) ? $doc->booking = 0 : $doc->booking = 1;

        if (!$doc->update(['start_time', 'end_time', 'booking'], 'weekday')) {
            $error++;
        }
    }

    if ($error == 0) {
        header("Location: ../index.php?p=bookingSettings&msg=timetableUpdate");
        exit;
    } else {
        header("Location: ../index.php?p=bookingSettings&err=timetableUpdateErr");
        exit;
    }
} else if ($operation == "msg") {

    $doc->table = "doc_messages";
    $doc->id = 1;
    $doc->msg = filter_input(INPUT_POST, 'msg');
    $doc->start_time = filter_input(INPUT_POST, 'start');
    $doc->end_time = filter_input(INPUT_POST, 'end');

    if ($doc->update(['msg', 'start_time', 'end_time'], 'id')) {
        header("Location: ../index.php?p=addMessage&msg=msgUpdate");
        exit;
    } else {
        header("Location: ../index.php?p=addMessage&err=msgUpdateErr");
        exit;
    }
} else if ($operation == 'booking') {
    /*   if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['recaptcha_response'])) {


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
            $doc->table = 'doc_bookings';

            // formatting datetime
            $date = filter_input(INPUT_POST, 'date');
            $italian_date = DateTime::createFromFormat('Y-m-d', $date)->format('d/m/Y');
            $time = filter_input(INPUT_POST, 'time');
            $dateTime = DateTime::createFromFormat('Y-m-d H:i', $date . ' ' . $time);
            $doc->slot_booked = $dateTime->format('Y-m-d H:i:s');

            // checking for double bookings
            if ($doc->itemExists('slot_booked')) {
                header('Location: ../../booking.php?err=bookingExists');
                exit;
            }

            $patient_name = filter_input(INPUT_POST, 'name');
            $email = filter_input(INPUT_POST, 'email');
            $token =  bin2hex(random_bytes(16));
            $doc->name = $patient_name;
            $doc->email = $email;
            $doc->phone = filter_input(INPUT_POST, 'phone');
            $doc->reason_id = filter_input(INPUT_POST, 'reason_id');
            $doc->notes = filter_input(INPUT_POST, 'notes');
            $doc->token = $token;


            if ($doc->insert(['name', 'email', 'phone', 'reason_id', 'notes', 'slot_booked', 'token'])) {

                ///////////////////////////////////////////////////////////////
                ////    INVIO EMAIL CON LINK PER MODIFICA O CANCELLAZIONE
                ///////////////////////////////////////////////////////////////

                $doc->table = "doc_settings";
                $doc->name = "email";
                $stmt = $doc->showAllWhere('id', ['name']);

                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                extract($row);

                $from = $row['value'];
                $to = filter_input(INPUT_POST, 'email');

                // To send HTML mail, the Content-type header must be set
                $headers  = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                // Create email headers
                $headers .= 'From: ' . $from . "\r\n" .
                    'Reply-To: ' . $from . "\r\n" .
                    'X-Mailer: PHP/' . phpversion();

                $subject = "Prenotazione visita - $italian_date";

                $output = $doc_block1;
                $output .= "<p>Paziente: <b>$patient_name</b><br>";
                $output .= "Data della visita:<b> $italian_date - $time</b>";
                $output .= $doc_block2;
                $output .= '<p><a href="http://dottoressacrosetto.it/delete_booking.php?email=' . $email . '&token=' . $token . '" target="_blank">Cancella appuntamento</a></p>';
                $output .= $doc_block3;

                if (mail($to, $subject, $output, $headers)) {
                    header("Location: ../../index.php?msg=sentBookingMail");
                    exit;
                } else {
                    header("Location: ../../index.php?err=errSendBookingMail");
                    exit;
                }
            } else {
                header('Location: ../../booking.php?err=notBooked');
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
} else if ($operation == 'deleteEmail') {

    $doc->table = 'doc_bookings';
    $doc->email = filter_input(INPUT_POST, 'email');
    $doc->token = filter_input(INPUT_POST, 'token');

    $stmt = $doc->showAllWhere('id', ['email', 'token']);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    extract($row);

    $doc->table = 'doc_bookings';
    $doc->id = $row['id'];
    if ($doc->delete('id')) {
        header("Location: ../../index.php?msg=bookingDeleteSucc");
        exit;
    } else {
        header("Location: ../../index.php?err=bookingDeleteFail");
        exit;
    }
}
