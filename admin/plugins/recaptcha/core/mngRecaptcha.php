<?php


##############    Damares    ###############
#                                          #
#    A backend project by DM WebLab        #
#   Website: https://www.dmweblab.com      #
#   GitHub: https://github.com/damares86   #
#                                          #
############################################

spl_autoload_register('autoloader');

function autoloader($class){
	include("../class/$class.php");
}

$database = new Database();
$db = $database->getConnection();

include "../inc/class_initialize.php";

if(filter_input(INPUT_POST, "recap")){

	$public=filter_input(INPUT_POST, "public");
	$secret=filter_input(INPUT_POST, "secret");

	$verify->public = $public ;
	$verify->secret = $secret ;
	$verify->id = 1 ;

	if($verify->update(['public','secret'],'id')){
		header("Location: ../index.php?p=setRecaptcha&msg=recapMod");
		exit;
	}else{
		header("Location: ../index.php?p=setRecaptcha&err=recapNoMod");
		exit;
	}
	
}else{
	header("Location: ../index.php?p=setRecaptcha&err=noPost");
    exit;
}


?>














?>