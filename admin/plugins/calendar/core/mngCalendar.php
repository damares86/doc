<?php


##############    Damares    ###############
#                                          #
#    A backend project by DM WebLab        #
#   Website: https://www.dmweblab.com      #
#   GitHub: https://github.com/damares86   #
#                                          #
############################################


require __DIR__ . "/coreConfig.php";

if (filter_input(INPUT_GET, "idToDel")) {

    $calendar->table = 'calendar_events';
    $calendar->cat_id = filter_input(INPUT_GET, "idToDel");

    $num = $calendar->countItem('cat_id');

    if ($num == 0) {

        $calendar->id = filter_input(INPUT_GET, "idToDel");
        $calendar->table = 'calendar_cat';

        if ($calendar->delete('id')) {
            header("Location: ../index.php?p=allCalendars&msg=delCalOk");
            exit;
        } else {
            header("Location: ../index.php?p=allCalendars&err=delCalFail");
            exit;
        }
    } else {
        header("Location: ../index.php?p=allCalendars&err=calEventsExists");
        exit;
    }
}

$operation = filter_input(INPUT_POST, "operation");

if ($operation == "add") {

    $calendar->cat_name = filter_input(INPUT_POST, "cat_name");
    $calendar->cat_color = filter_input(INPUT_POST, "cat_color");

    // insert calendar category and color in db
    if ($calendar->insert(['cat_name', 'cat_color'])) {
        header("Location: ../index.php?p=allCalendars&msg=addCalOk");
        exit;
    } else {
        header("Location: ../index.php?p=allCalendars&err=addCalFail");
        exit;
    }
} else if ($operation == "edit") {

    $url_tablePage = filter_input(INPUT_POST, 'url_tablePage');
    $url_pageName = filter_input(INPUT_POST, 'url_pageName');
    $url_data = "&tablePage=$url_tablePage&pageName=$url_pageName";

    $calendar->id = filter_input(INPUT_POST, 'idToMod');
    $calendar->cat_name = filter_input(INPUT_POST, "cat_name");
    $calendar->cat_color = filter_input(INPUT_POST, "cat_color");

    if ($calendar->update(['cat_name', 'cat_color'], 'id')) {
        header("Location: ../index.php?p=allCalendars&msg=editCalOk$url_data");
        exit;
    } else {
        header("Location: ../index.php?p=allCalendars&err=editCalFail$url_data");
        exit;
    }
}
