<?php

##############    Damares    ###############
#                                          #
#    A backend project by DM WebLab        #
#   Website: https://www.dmweblab.com      #
#   GitHub: https://github.com/damares86   #
#                                          #
############################################


require __DIR__ . "/coreConfig.php";

// check if there's a page to delete

if (filter_input(INPUT_GET, "idToDel")) {

    // gestire il discorso del colore usato

    $idToDel = filter_input(INPUT_GET, "idToDel");

    $mc->table = 'mc_color';
    $mc->id = $idToDel;

    if ($mc->delete('id')) {
        header("Location: ../index.php?p=allColors&msg=colorDelSucc");
        exit;
    } else {
        header("Location: ../index.php?p=allColors&err=colorDelFail");
        exit;
    }
}

$operation = filter_input(INPUT_POST, "operation");

// check if there's an account to edit or add

if ($operation == 'addColor') {

    $color = filter_input(INPUT_POST, 'color');
    $mc->table = 'mc_color';
    $mc->color = $color;

    $stmt = $mc->countItem('color');
    if ($stmt == 0) {

        $mc->table = 'mc_color';
        $mc->color = $color;
        if (!$mc->insert(['color'])) {
            $err_count++;
        }
    }

    if ($err_count == 0) {
        header("Location: ../index.php?p=allColors&msg=colorAddSucc");
        exit;
    } else {
        header("Location: ../index.php?p=allColors&err=colorAddFail");
        exit;
    }
} else {
    header("Location: ../index.php?p=allColors&err=noPost");
    exit;
}
