<?php

declare(strict_types=1);

##############    Damares    ###############
#                                          #
#    A backend project by DM WebLab        #
#   Website: https://www.dmweblab.com      #
#   GitHub: https://github.com/damares86   #
#                                          #
############################################

require __DIR__ . '/coreConfig.php';

$idToDel = filter_input(INPUT_GET, 'idToDel', FILTER_VALIDATE_INT);
if ($idToDel !== false && $idToDel !== null) {
    $calendar->table = 'calendar_events';
    $calendar->cat_id = $idToDel;

    $num = $calendar->countItem('cat_id');

    if ($num === 0) {
        $calendar->id = $idToDel;
        $calendar->table = 'calendar_cat';

        if ($calendar->delete('id')) {
            header('Location: ../index.php?p=allCalendars&msg=delCalOk');
            exit;
        }

        header('Location: ../index.php?p=allCalendars&err=delCalFail');
        exit;
    }

    header('Location: ../index.php?p=allCalendars&err=calEventsExists');
    exit;
}

$operation = (string) (filter_input(INPUT_POST, 'operation', FILTER_DEFAULT) ?? '');

if ($operation === 'add') {
    $calendar->table = 'calendar_cat';
    $calendar->cat_name = (string) (filter_input(INPUT_POST, 'cat_name', FILTER_DEFAULT) ?? '');
    $calendar->cat_color = (string) (filter_input(INPUT_POST, 'cat_color', FILTER_DEFAULT) ?? '#008db1');

    if ($calendar->insert(['cat_name', 'cat_color'])) {
        header('Location: ../index.php?p=allCalendars&msg=addCalOk');
        exit;
    }

    header('Location: ../index.php?p=allCalendars&err=addCalFail');
    exit;
}

if ($operation === 'edit') {
    $url_tablePage = (string) (filter_input(INPUT_POST, 'url_tablePage', FILTER_DEFAULT) ?? '');
    $url_pageName = (string) (filter_input(INPUT_POST, 'url_pageName', FILTER_DEFAULT) ?? '');
    $url_data = '&tablePage=' . urlencode($url_tablePage) . '&pageName=' . urlencode($url_pageName);

    $calendar->table = 'calendar_cat';
    $calendar->id = (int) (filter_input(INPUT_POST, 'idToMod', FILTER_VALIDATE_INT) ?? 0);
    $calendar->cat_name = (string) (filter_input(INPUT_POST, 'cat_name', FILTER_DEFAULT) ?? '');
    $calendar->cat_color = (string) (filter_input(INPUT_POST, 'cat_color', FILTER_DEFAULT) ?? '#008db1');

    if ($calendar->update(['cat_name', 'cat_color'], 'id')) {
        header("Location: ../index.php?p=allCalendars&msg=editCalOk{$url_data}");
        exit;
    }

    header("Location: ../index.php?p=allCalendars&err=editCalFail{$url_data}");
    exit;
}

header('Location: ../index.php?p=allCalendars&msg=noPost');
exit;
