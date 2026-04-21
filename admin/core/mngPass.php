<?php


##############    Damares    ###############
#                                          #
#    A backend project by DM WebLab        #
#   Website: https://www.dmweblab.com      #
#   GitHub: https://github.com/damares86   #
#                                          #
############################################


require '../vendor/autoload.php';		// If installed via composer
$debug = new \bdk\Debug(array(
	'collect' => true,
	'output' => true,
));

spl_autoload_register('autoloader');

function autoloader($class){
	include("../class/$class.php");
}

$database = new Database();
$db = $database->getConnection();

include "../inc/class_initialize.php";

$email=$_POST['email'];

$setting->name="lang" ;
$stmt = $setting->showByName();
$lang = $stmt['value'];

foreach (glob("../locale/$lang/*.php") as $row){
    require "$row";
}

$resetForm = filter_input(INPUT_POST, "resetForm");
$resetMail = filter_input(INPUT_POST, "resetMail");

if($resetForm){
	
	$auth->email=$email;
	$email_exists=$auth->emailExists();	
	
	if(!$email_exists){
		header("Location: ../../login/auth-forgot-password.php?err=mailNotReg");
		exit;
	}
	$account->email = $email ;

	$pswTmp = $account->getPswTmpDataByEmail();


		$curDate=date("Y-m-d H:i:s");
		$expDate=$pswTmp['expDate'];
		
		if((!$pswTmp['email']||(($pswTmp['email']) && ($expDate<$curDate)))){
			$account->table = 'password_reset_temp' ;
			$stmt=$account->delete('email');
			if(!$stmt){
				header("Location: ../../login.php?err=noResetDelete");
				exit;
			}else {
				$expFormat = mktime(date("H")+2, date("i"), date("s"), date("m") ,date("d"), date("Y"));
				$expDate = date("Y-m-d H:i:s",$expFormat);
				
				$token = md5($email);
				$addToken= substr(md5(uniqid(rand(),1)),3,10);
				$token = $token . $addToken;
				$account->token=$token;
				$account->expDate = $expDate ;
				$account->table = 'password_reset_temp' ;
			if($account->insert(['email','token','expDate'])){

				$url = $_SERVER['SERVER_NAME'];

				$setting->name="noreply";
				$stmt=$setting->showAllWhere('id',['name']);
				$row=$stmt->fetch(PDO::FETCH_ASSOC);
				$from=$row['value'];

				$setting->name="noreply" ;
				$stmt = $setting->showByName();
				$noreply = $stmt['value'];

				$from = $noreply ;

				// To send HTML mail, the Content-type header must be set
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				// Create email headers
				$headers .= 'From: '.$from."\r\n".
				'Reply-To: '.$from."\r\n" .
				'X-Mailer: PHP/' . phpversion();

				$output=$block1;
				$output.='<p><a href="http://'.$url.'/login/auth-forgot-password.php?email='.$email.'&token='.$token.'&op=reset" target="_blank">http://'.$url.'/login/auth-forgot-password.php?email='.$email.'&token='.$token.'&op=reset</a></p>';		
				$output.=$block2;

				$to= $email; 
				$subject="Reset password Damares";

				
				if (mail ($to, $subject, $output, $headers)) {
					header("Location: ../../login/auth-login.php?msg=sentMail");
					exit;
				} else {
					header("Location: ../../login/auth-login.php?err=errSendMail");
					exit;
				}
			
			}else{	
				header("Location: ../../login/auth-login.php?err=noReset");
				exit;
			}
		}
		} else{
			header("Location: ../../login/auth-login.php?err=errResetRequest");
			exit;
		}
	}else if($resetMail) {

		$email=filter_input(INPUT_POST, "email");
		$account->email=$email;
		$stmt = $account->showAllWhere('id',['email']);
		$row=$stmt->fetch(PDO::FETCH_ASSOC);
		
		if(!$_POST['password']){
			header("Location: ../../login.php?msg=pswEmpty");
			exit;
		}
		
		$password = $_POST['password'];
        $password_hash = password_hash($password, PASSWORD_BCRYPT);
		$account->password = $password_hash;
		$account->id = $row['id'] ;
		$account->table = 'password_reset_temp';

		// update the post
		if($account->update(['password'],'id')){
			if($account->delete('email')){
				header("Location: ../../login/auth-login.php?msg=newPass");
				exit;
			}else{
				header("Location: ../../login/auth-login.php?err=keyDelErr");
				exit;
			}
			// empty posted values
			// $_POST=array();
			
		}else{
			header("Location: ../../login/auth-login.php?err=pswEditErr");
			exit;
		}
	}else{
header("Location: ../../login/auth-login.php?msg=errPost");
exit;
}
exit;

?>










