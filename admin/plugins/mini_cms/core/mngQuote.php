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

    $idToDel = filter_input(INPUT_GET, "idToDel") ;
    
    $mc->table = 'mc_quotes' ;
    $mc->id = $idToDel ;

    if($mc->delete('id')){
        header("Location: ../index.php?p=allQuotes&msg=quoteDelSucc");
        exit;
    }else{
        header("Location: ../index.php?p=allQuotes&err=quoteDelFail");
        exit;
    }

}

$operation = filter_input(INPUT_POST, "operation");

// check if there's an account to edit or add

if ($operation == 'add') {

    $mc->quote = filter_input(INPUT_POST,'quote') ;
    $mc->author = filter_input(INPUT_POST,'author') ;
    $mc->table = 'mc_quotes' ;

    if($mc->insert(['quote','author']) ){
        header("Location: ../index.php?p=allQuotes&msg=quoteAddSucc");
        exit;
    }else{
        header("Location: ../index.php?p=allQuotes&err=quoteAddFail");
        exit;
    }
    
} else if ($operation == 'edit') {

    $mc->quote = filter_input(INPUT_POST,'quote') ;
    $mc->author = filter_input(INPUT_POST,'author') ;
    $mc->id = filter_input(INPUT_POST,'idToMod') ; 
    $mc->table = 'mc_quotes' ;

    if($mc->update(['quote','author'],'id') ){
        header("Location: ../index.php?p=allQuotes&msg=quoteEditSucc");
        exit;
    }else{
        header("Location: ../index.php?p=allQuotes&err=quoteEditFail");
        exit;
    }

}else {
    header("Location: ../index.php?p=allTheme&err=noPost");
    exit;
}