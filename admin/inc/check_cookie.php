<?php

if(isset($_COOKIE['damares-login'])){
    $pieces = explode(",", $_COOKIE['damares-login']);
    $auth->id = $pieces[0];
    $id = $pieces[0];
    $auth->auth_token = $pieces[1];
    if($auth->checkCookie()>0){
                     
        session_start();
        $accountroles->account_id = $id; 
        
        $account->id = $id ;

        $stmt = $account->showAllWhere('id',['id']);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $role_id = $accountroles->showAccountRolesId();
        $role->id = $role_id ;
        
        // set session data
        $_SESSION['loggedin'] = true ;
        $_SESSION['account_id'] = $row['id'];
        $_SESSION['internal'] = 1 ;
        $_SESSION['role_id'] = $role_id;
        $_SESSION['rolename'] = $role->showRolenameById();
        $_SESSION['username'] = $row['username'];
        $_SESSION['avatar'] = $row['avatar'];
        
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
      
    } else {
        header("Location: ../login/auth-login.php?err=noLogin");
        exit;
    }
}