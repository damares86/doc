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

// get the form data
$postpass = $_POST['password'];

$auth->email = $_POST['email'];
$email = $_POST['email'];

// check if the given email exist in db
$email_exists = $auth->emailExists();

// match the email and the password
if($email_exists && password_verify($postpass,$auth->password)){
    if($_POST['remember']){
        $token = md5($email);
        $addToken= substr(md5(uniqid(rand(),1)),3,10);
        $token = $token . $addToken;
        
        $account->email = $email ;
        $account->auth_token = $token ;
        
        $account->update(['auth_token'],'email') ;
        setcookie("damares-login", $auth->id . "," . $token, time()+(60 * 60 *24 * 365 *10 ),"/");
    }
    
    session_start();
    $accountroles->account_id = $auth->id; 
    
    
    $role_id = $accountroles->showAccountRolesId();
    $role->id = $role_id ;
    
    
    // set session data
    $_SESSION['loggedin'] = true ;
    $_SESSION['account_id'] = $auth->id;
    $_SESSION['internal'] = 1 ;
    $_SESSION['role_id'] = $role_id;
    $_SESSION['rolename'] = $role->showRolenameById();
    $_SESSION['username'] = $auth->username;
    $_SESSION['avatar'] = $auth->avatar;
    
    // update the login log time
    $time=date("Y-m-d G:i:s");
    $auth->updateLog($time);
    
    $setting->name = "role_redirect";
    $stmt = $setting->showAllWhere('id', ['name']);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $redir = $row['value'];
  
    if ($redir == 1) {
      $stmt = $role->showAllWhere('id', ['id']);
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      extract($row);
      if ($row['redirect'] != "none") {
        header("Location: " . $row['redirect'] . "");
        exit;
      }
    }

    header("Location: ../");
    exit;
    
} else {
    
    // header("Location: ../../login/auth-login.php?err=errUserPsw");
    header("Location: ../../index.php?err=errUserPsw");
    exit;
}
