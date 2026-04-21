<?php

##############    Damares    ###############
#                                          #
#    A backend project by DM WebLab        #
#   Website: https://www.dmweblab.com      #
#   GitHub: https://github.com/damares86   #
#                                          #
############################################


require __DIR__."/coreConfig.php";


$operation = filter_input(INPUT_POST,"operation") ;

$post = $_POST ;

$error = 0 ;

foreach($post as $key => $value){

    $setting->name = $key ;
    $setting->value = $value ;
    if(!$setting->updateValue()){
        $error++ ;
    }

}

if($error==0){
    header("Location: ../index.php?p=allSettings&msg=settingUpdate");
    exit;
}else{
    header("Location: ../index.php?p=allSettings&err=settingUpdateErr");
    exit;
}