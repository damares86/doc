<?php

##############    Damares    ###############
#                                          #
#    A backend project by DM WebLab        #
#   Website: https://www.dmweblab.com      #
#   GitHub: https://github.com/damares86   #
#                                          #
############################################


require __DIR__."/coreConfig.php";

if(filter_input(INPUT_POST,'debug_check')){

    $debug = 0 ;

    if(filter_input(INPUT_POST,'debug')){
        $debug = 1 ;
    }

    $setting->name = "debug" ;
    $setting->value = $debug ;

    if($setting->update(['value'],'name')){
        header("Location: ../index.php?p=damares&msg=debugUpdate");
        exit;
    }else{
        header("Location: ../index.php?p=damares&err=debugUpdateErr");
        exit;
    }
}else{
    header("Location: ../index.php?p=damares&err=errPost");
    exit;
}


?>