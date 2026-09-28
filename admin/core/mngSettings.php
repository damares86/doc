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

$post = $_POST;
$error = 0;

foreach ($post as $key => $value) {
    if ($key === 'operation') {
        continue;
    }
    $setting->name = (string) $key;
    $setting->value = (string) $value;
    if (!$setting->updateValue()) {
        $error++;
    }
}

if ($error === 0) {
    header('Location: ../index.php?p=allSettings&msg=settingUpdate');
    exit;
}

header('Location: ../index.php?p=allSettings&err=settingUpdateErr');
exit;