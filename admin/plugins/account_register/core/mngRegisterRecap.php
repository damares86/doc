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

$setting->name="lang" ;
$stmt = $setting->showByName();
$lang = $stmt['value'];

foreach (glob("../locale/$lang/*.php") as $row){
    require "$row";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['recaptcha_response'])) {
	$stmt=$verify->showAll('id');
	$row=$stmt->fetch(PDO::FETCH_ASSOC);
	$secret=$row['secret'];
	// Costruire il POST request:      
	
	$recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
	$recaptcha_secret = $secret;
	$recaptcha_response = $_POST['recaptcha_response'];
	
	// Istanziare e decodificare la richiesta POST:      
	
	$recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
	$recaptcha = json_decode($recaptcha);
	
	// Azioni da compiere basate sul punteggio ottenuto:      
	
	if ($recaptcha->score >= 0.5) {

		if(filter_input(INPUT_POST, "reg_form")){
			$email=filter_input(INPUT_POST, "email");
			
			$auth->email=$email;
			$email_exists=$auth->emailExists();	
			
			if($email_exists){
				header("Location: ../../login/auth-register.php?err=mailExists");
				exit;
			}
			
			$register->email = filter_input(INPUT_POST,"email");
			
			$stmt = $register->showAllWhere('id',['email']);
			$emailTmp="";
			foreach($stmt as $row){
				$emailTmp = $row['email'] ;
			}

			if((!$emailTmp)||(($emailTmp) && ($expDate<$curDate))){
					$stmt=$register->delete('email');
					if(!$stmt){
						header("Location: ../../auth-register.php?err=noRegDelete");
						exit;
					}else {
						$expFormat = mktime(date("H")+2, date("i"), date("s"), date("m") ,date("d"), date("Y"));
						$expDate = date("Y-m-d H:i:s",$expFormat);
						
						$token = md5($email);
						$addToken= substr(md5(uniqid(rand(),1)),3,10);
						$token = $token . $addToken;
						$register->token=$token;
						$register->expDate = $expDate ;
						$register->username=filter_input(INPUT_POST,"username");
						$password=filter_input(INPUT_POST,"password");
						$password_hash = password_hash($password, PASSWORD_BCRYPT);
						$register->password = $password_hash ;

						$register->avatar="default.png";	
						
						require "accountDetails.php";

						$details_arr = [] ;
						$details_opt_arr = [] ;

						foreach($account_details as $item){
							$details_arr[] = array("$item" => "".$_POST[$item]."");
						}

						$details_str = serialize($details_arr);
						$register->details = $details_str;

						foreach($account_details_opt as $item){
							$details_opt_arr[] = array("$item" => "".$_POST[$item]."");
						}
						$details_opt_str = serialize($details_opt_arr);
						$register->details_opt = $details_opt_str ;

						if($register->insert(['email','username','password','avatar','details','details_opt','token','expDate'])){
					
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

						$output=$reg_block1;
						$output.='<p><a href="http://'.$url.'/login/auth-register.php?email='.$email.'&token='.$token.'&op=reg" target="_blank">http://'.$url.'/login/auth-register.php?email='.$email.'&token='.$token.'&op=reg</a></p>';		
						$output.=$reg_block2;

						$to= $email; 
						$subject=$reg_mail_subject ;

						
						if (mail ($to, $subject, $output, $headers)) {
							header("Location: ../../login/auth-register.php?msg=sentRegMail");
							exit;
						} else {
							header("Location: ../../login/auth-register.php?err=errSendMail");
							exit;
						}
					
					}else{	
						
						header("Location: ../../login/auth-register.php?err=noReg");
						exit;
					}
				}
				} else{
					header("Location: ../../login/auth-register.php?err=errRegRequest");
					exit;
				}

			}else if(filter_input(INPUT_POST, "reg_role")){

				$role_id = filter_input(INPUT_POST, "role");
				$role->id = $role_id ;
				$rolename = $role->showRolenameById();

				$setting->name = "reg_role" ;
				$setting->value = $rolename ;
				if(!$setting->updateValue()){
					header("Location: ../index.php?p=setRegister&err=regRoleNotUpdated");
					exit;
				}else{
					header("Location: ../index.php?p=setRegister&msg=regRoleUpdated");
					exit;
				}
			

			}else{
		header("Location: ../../login/auth-register.php?msg=errPost");
		exit;
		}
	}else{
		header("Location: ../../login/auth-register.php?err=errRecaptcha");
		exit;
	}

}else{
	header("Location: ../../login/auth-register.php?msg=errPost");
	exit;
}
?>
