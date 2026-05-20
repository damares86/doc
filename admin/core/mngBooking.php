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
    $doc->table = "doc_settings" ;

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
}else if ($operation == "timetable"){

    $doc->table = "doc_timetable" ;

    $error = 0 ;

    for($i=1;$i<6;$i++){

        $doc->weekday = $i;
        $doc->start_time = $_POST["start_$i"];
        $doc->end_time = $_POST["end_$i"];
        isset($_POST["booking_$i"] ) ? $doc->booking = 0 : $doc->booking = 1;
        
        if(!$doc->update(['start_time','end_time', 'booking'],'weekday')){
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
}else if($operation == 'booking'){

    $doc->table = 'doc_bookings';

    // formatting datetime
    $date = filter_input(INPUT_POST,'date') ;
    $time = filter_input(INPUT_POST,'time') ;    
    $dateTime = DateTime::createFromFormat('Y-m-d H:i', $date . ' ' . $time);
    $doc->slot_booked = $dateTime->format('Y-m-d H:i:s');

    // checking for double bookings
    if($doc->itemExists('slot_booked')){
        header('Location: ../../booking.php?err=bookingExists');
        exit;
    }

    $doc->name = filter_input(INPUT_POST, 'name') ;
    $doc->email = filter_input(INPUT_POST, 'email') ;
    $doc->phone = filter_input(INPUT_POST, 'phone') ;
    $doc->notes = filter_input(INPUT_POST, 'notes') ;
    $doc->token = bin2hex(random_bytes(16));


    if($doc->insert(['name','email','phone','notes','slot_booked','token'])){
        header('Location: ../../booking.php?msg=booked');
        exit;
    }else{
        header('Location: ../../booking.php?err=notBooked');
        exit;
    }


}
