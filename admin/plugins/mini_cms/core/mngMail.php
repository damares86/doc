<?php

require '../vendor/autoload.php';		// If installed via composer
$debug = new \bdk\Debug(array(
	'collect' => true,
	'output' => true,
));


session_start();

// loading class
spl_autoload_register('autoloader');
    function autoloader($class){
        include("../class/$class.php");
    }


$database = new Database();
$db = $database->getConnection();

include "../inc/class_initialize.php";



	// if(!$_POST['name']||!$_POST['email']||!$_POST['message']||!$_POST['subject']){
	// 	header("Location: ../../contact.php?msg=contactFormEmpty");
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
		header("Location: ../../contact.php?err=errSendContact");
		exit;
	}	

