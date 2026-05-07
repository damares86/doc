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
}
