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

    $idToDel = filter_input(INPUT_GET, "idToDel") ;
    
    $mc->table = 'mc_popup' ;
    $mc->id = $idToDel ;

    if($mc->delete('id')){
        header("Location: ../index.php?p=allPopup&msg=popupDelSucc");
        exit;
    }else{
        header("Location: ../index.php?p=allPopup&err=popupDelFail");
        exit;
    }

}

$operation = filter_input(INPUT_POST, "operation");

// check if there's an account to edit or add

if ($operation == 'add') {

    $mc->title = filter_input(INPUT_POST,'title') ;
    $mc->content = filter_input(INPUT_POST,'content') ;
    $mc->page_id = filter_input(INPUT_POST,'page_id') ;
    $mc->popup_cat_id = filter_input(INPUT_POST,'cat_id') ;
    $mc->table = 'mc_popup' ;

    if($mc->insert(['title','content','page_id','popup_cat_id']) ){
        header("Location: ../index.php?p=allPopup&msg=popupAddSucc");
        exit;
    }else{
        header("Location: ../index.php?p=allPopup&err=popupAddFail");
        exit;
    }
    
} else if ($operation == 'edit') {

    $mc->title = filter_input(INPUT_POST,'title') ;
    $mc->content = filter_input(INPUT_POST,'content') ;
    $mc->page_id = filter_input(INPUT_POST,'page_id') ;
    $mc->popup_cat_id = filter_input(INPUT_POST,'cat_id') ;
    $mc->id = filter_input(INPUT_POST,'idToMod') ; 
    $mc->table = 'mc_popup' ;

    if($mc->update(['title','content','page_id','popup_cat_id'],'id') ){
        header("Location: ../index.php?p=allPopup&msg=popupEditSucc");
        exit;
    }else{
        header("Location: ../index.php?p=allPopup&err=popupEditFail");
        exit;
    }

}else {
    header("Location: ../index.php?p=allTheme&err=noPost");
    exit;
}