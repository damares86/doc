<?php

##############    Damares    ###############
#                                          #
#    A backend project by DM WebLab        #
#   Website: https://www.dmweblab.com      #
#   GitHub: https://github.com/damares86   #
#                                          #
############################################


if(session_status() == PHP_SESSION_ACTIVE){
  session_destroy();
 }
 
 if(is_file("../class/Database.php")){
 
 include ("../class/Database.php");
 $database=new Database();
 $db = $database->getConnection();

 $prx="";
 if(is_file("prefix.php")){
  require "prefix.php";
  $prx=$prefix;
 }

 
 $query = "DROP TABLE IF EXISTS `".$prx."accountsRoles`, `".$prx."accounts`,  `".$prx."files`, `".$prx."home`, `".$prx."password_reset_temp`, `".$prx."plugins`, `".$prx."roles`, `".$prx."rolesSection`, `".$prx."rolesSectionChild`, `".$prx."sectionChild`, `".$prx."sectionParent`, `".$prx."settings`,`".$prx."register_account_temp`";

 
 $stmt = $database->conn->prepare($query);
 
 $stmt->execute();
 }


$exclude_arr=array("default","Program","sd");

foreach (glob("../uploads/avatar/*") as $row){
    $file = pathinfo($row);
    $filename = $file['filename'];
  if(!in_array($filename,$exclude_arr)){
    unlink($row);
  }
}

foreach (glob("../uploads/*") as $row){
    $file = pathinfo($row);
    $filename = $file['filename'];
    if(!in_array($filename,$exclude_arr)){
      unlink($row);
    }
  }

unlink("../class/Database.php");
if(is_file("site.php")){
  unlink("site.php");
}
if(is_file("prefix.php")){
  unlink("prefix.php");
 }
unlink("../inc/class_initialize.php");


header("Location: ../");
exit;