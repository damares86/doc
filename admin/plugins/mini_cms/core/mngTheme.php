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

// if (filter_input(INPUT_GET, "idToDel")) {

//     // gestire il discorso del colore usato

//     $idToDel = filter_input(INPUT_GET, "idToDel");

//     $mc->table = 'mc_color';
//     $mc->id = $idToDel;

//     if ($mc->delete('id')) {
//         header("Location: ../index.php?p=allTheme&msg=colorDelSucc");
//         exit;
//     } else {
//         header("Location: ../index.php?p=allTheme&err=colorDelFail");
//         exit;
//     }
// }

$operation = filter_input(INPUT_POST, "operation");

// check if there's an account to edit or add

if ($operation == 'editTheme') {

    $err_count = 0;
    $err = '';

    // update theme
    $theme = filter_input(INPUT_POST, 'theme');
    $mc->table = 'mc_settings';
    $mc->name = 'mc_theme';
    $mc->value = $theme;
    if (!$mc->update(['value'], 'name')) {
        $err_count++;
    }

    // update theme_one
    if (filter_input(INPUT_POST,'mc_theme_one')) {
        $mc->value = 1;
    } else {
        $mc->value = 0;
    }
    $mc->table = 'mc_settings';
    $mc->name = 'mc_theme_one';
    if(!$mc->update(['value'], 'name')){
        $err_count++;
    }


    if (isset($_POST['code'])) {
        $css_code = $_POST['code']; // NON usare htmlspecialchars qui!

        $css_file = '../../assets/themes/' . $theme . '/custom.css';

        if (!file_put_contents($css_file, $css_code)) {
            $err_count++;
        }
    }


    if ($err_count == 0) {
        header("Location: ../index.php?p=allTheme&msg=themeEditSucc");
        exit;
    } else {
        header("Location: ../index.php?p=allTheme&err=themeEditFail");
        exit;
    }
} else {
    header("Location: ../index.php?p=allTheme&err=noPost");
    exit;
}
