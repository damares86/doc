<?php

declare(strict_types=1);

##############    Damares    ###############
#                                          #
#    A backend project by DM WebLab        #
#   Website: https://www.dmweblab.com      #
#   GitHub: https://github.com/damares86   #
#                                          #
############################################

require_once __DIR__ . '/coreConfig.php';

if (filter_input(INPUT_POST, 'debug_check')) {
    $debug = filter_input(INPUT_POST, 'debug') ? '1' : '0';

    $setting->name = 'debug';
    $setting->value = $debug;

    if ($setting->update(['value'], 'name')) {
        header('Location: ../index.php?p=damares&msg=debugUpdate');
        exit;
    }

    header('Location: ../index.php?p=damares&err=debugUpdateErr');
    exit;
}

header('Location: ../index.php?p=damares&err=errPost');
exit;