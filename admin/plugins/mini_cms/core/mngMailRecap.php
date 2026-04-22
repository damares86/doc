<?php

// require '../vendor/autoload.php';		// If installed via composer
// $debug = new \bdk\Debug(array(
// 	'collect' => true,
// 	'output' => true,
// ));


session_start();
// if (!isset($_SESSION['loggedin'])) {
// 	header('Location: ../');
//     exit;
// }

// loading class
spl_autoload_register('autoloader');
    function autoloader($class){
        include("../class/$class.php");
    }


$database = new Database();
$db = $database->getConnection();

include "../inc/class_initialize.php";



	if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['recaptcha_response'])) {
		$stmt=$verify->showAll();
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

			// if(!$_POST['name']||!$_POST['email']||!$_POST['message']||!$_POST['subject']){
			// 	header("Location: ../../contact.php?msg=contactEmpty");
			// 	exit;
			// }
		
		
			$name=$_POST['name'];
			$email=$_POST['email'];
			$message=$_POST['message'];
			$subject=$_POST['subject'];
		
		
			// $stmt=$contact->showAll();
			// $row=$stmt->fetch(PDO::FETCH_ASSOC);
			// $from=$row['inbox'];
			// $to=$row['inbox'];
		
			$from=$_POST['contact'];
			$to=$_POST['contact'];

			// To send HTML mail, the Content-type header must be set
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			// Create email headers
			$headers .= 'From: '.$from."\r\n".
			'Reply-To: '.$from."\r\n" .
			'X-Mailer: PHP/' . phpversion();
		
			$output='<html><body>';
			$output.='<p>Message from <b>'.$name.'</b> ('.$email.')</p>';
			$output.='<br>';
			$output.= $message;
			$output.='<br>';
			$output.='<br>';
			$output.='</body></html>';
			
		
			if (mail ($to, $subject, $output, $headers)) {
				header("Location: ../../contact.php?msg=sentContact");
				exit;
			} else {
				header("Location: ../../contact.php?msg=errSendContact");
				exit;
			}	
		

		}else{
			header("Location: ../../contact.php?err=errRecaptcha");
			exit;
		}
	
	}else{
		header("Location: ../../contact.php?err=errPost");
		exit;
	}
	
	
	
	
	
	  
	?>