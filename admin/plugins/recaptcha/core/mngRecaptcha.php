<?php

declare(strict_types=1);

##############    Damares    ###############
#                                          #
#    A backend project by DM WebLab        #
#   Website: https://www.dmweblab.com      #
#   GitHub: https://github.com/damares86   #
#                                          #
############################################

spl_autoload_register(static function (string $class): void {
    $candidates = [
        __DIR__ . "/../../class/{$class}.php",
        __DIR__ . "/../class/{$class}.php",
        __DIR__ . "/../../../class/{$class}.php",
    ];
    foreach ($candidates as $file) {
        if (is_file($file)) {
            require_once $file;
            return;
        }
    }
});

$database = new Database();
$db = $database->getConnection();

$common = new Common($db);
$verify = new Common($db);
$verify->table = 'verify';

if (filter_input(INPUT_POST, 'recap')) {
    $public = (string) (filter_input(INPUT_POST, 'public', FILTER_DEFAULT) ?? '');
    $secret = (string) (filter_input(INPUT_POST, 'secret', FILTER_DEFAULT) ?? '');

    $verify->public = $public;
    $verify->secret = $secret;
    $verify->id = 1;

    if ($verify->update(['public', 'secret'], 'id')) {
        header('Location: ../index.php?p=setRecaptcha&msg=recapMod');
        exit;
    }

    header('Location: ../index.php?p=setRecaptcha&err=recapNoMod');
    exit;
}

header('Location: ../index.php?p=setRecaptcha&err=noPost');
exit;