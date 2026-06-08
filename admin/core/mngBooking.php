<?php

##############    Damares    ###############
#                                          #
#    A backend project by DM WebLab        #
#   Website: https://www.dmweblab.com      #
#   GitHub: https://github.com/damares86   #
#                                          #
############################################


require __DIR__ . "/coreConfig.php";


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
} else if ($operation == 'booking') {

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
    $doc->notes = filter_input(INPUT_POST, 'notes');
    $doc->token = $token;


    if ($doc->insert(['name', 'email', 'phone', 'notes', 'slot_booked', 'token'])) {

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
        $output .= "<b>Data della visita: $italian_date - $time</b>";
        $output .= $doc_block2;
        $output .= '<p><a href="http://doc.dmweblab.com?email=' . $email . '&token=' . $token . '" target="_blank">Cancella appuntamento</a></p>';
        $output .= $doc_block3;

        if (mail($to, $subject, $output, $headers)) {
            header("Location: ../../index.php?msg=sentMail");
            exit;
        } else {
            header("Location: ../../index.php?err=errSendMail");
            exit;
        }
    } else {
        header('Location: ../../booking.php?err=notBooked');
        exit;
    }
}
