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

    // gestire il discorso del colore usato

    $idToDel = filter_input(INPUT_GET, "idToDel") ;
    
    $mc->table = 'mc_contacts' ;
    $mc->id = $idToDel ;

    if($mc->delete('id')){
        header("Location: ../index.php?p=allMcSettings&msg=contactDelSucc");
        exit;
    }else{
        header("Location: ../index.php?p=allMcSettings&err=contactDelFail");
        exit;
    }

}

$operation = filter_input(INPUT_POST, "operation");

$post = $_POST;

$error = 0;


if ($operation == 'settings') {

    $post = $_POST;
   
    $exclude = ['current_logo','img_logo'];

    foreach ($post as $key => $value) {
        if(!in_array($key,$exclude)){
            $mc->table = 'mc_settings' ;
            $mc->name = $key;
            $mc->value = $value;
            if (!$mc->update(['value'],'name')) {
                $error++;
            }
        }
    }

    if($_FILES['img_logo']){
        if ($_FILES['img_logo']['size'] > 0) {
            $file->filename = $_FILES['img_logo']['name'];
            $filename = $_FILES['img_logo']['name'];
            $file->inputFileName = $_FILES['img_logo']['tmp_name'];
            $file->label = 'logo_'.rand();
            $file->path = "../../uploads/img/";
            $file->origin = filter_input(INPUT_POST, "origin");
            
            $file->operation = "add";
            if ($file->uploadFile()) {
                //success
                $mc->table = 'mc_settings' ;
                $mc->name = 'mc_site_logo';
                $mc->value = $_FILES['img_logo']['name'];;
                if (!$mc->update(['value'],'name')) {
                    $error++;
                }

            } else {
                $err_file = "&err=logoImgFail";
            }
        }
    }

} else if ($operation == 'contact') {

    $mc->table = 'mc_contacts' ;
    $mc->label = filter_input(INPUT_POST,'label');
    $mc->email = filter_input(INPUT_POST,'email');

    if(!$mc->insert(['label','email'])){
        $error++;
    }

} else if ($operation == 'maintenance') {

    $mc->table = 'mc_settings' ;
    $mc->name = 'maintenance';
    
    filter_input(INPUT_POST,'maintentance') ? $mc->value = 1 : $mc->value = 0;
    
    if (!$mc->update(['value'],'name')) {
        $error++;
    }
}

if ($error == 0) {
    header("Location: ../index.php?p=allMcSettings&msg=settingUpdate$err_file");
    exit;
} else {
    header("Location: ../index.php?p=allMcSettings&err=settingUpdateErr$err_file");
    exit;
}