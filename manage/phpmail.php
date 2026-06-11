<?php
ini_set("log_errors", 1);
error_reporting(E_ALL);
	
use PHPMailer\PHPMailer\PHPMailer;
require 'vendor/autoload.php';

$adminid = "orders@orthofit.in";  
$adminuser = "Orthofit Healthcare";

$fromid=$adminid;
$fromuser=$adminuser;

$to="samir.sudrik@gmail.com";
//$to="support@udeeta.com";
$subject="mailhostbox subject";
$emailtext=" mailhostbox email";


$SmtpServer="sg1-ss105.a2hosting.com"; //"smtp.orthofit.in"; //"us2.smtp.mailhostbox.com"; //smtp.orthofit.in
$SmtpPort="465";
$SmtpUser="orders@orthofit.in";
$SmtpPass="tH#mHTdiO0";


	$mail = new PHPMailer();
	$mail->isSMTP();
	#$mail->SMTPDebug = 2;
//$mail->Debugoutput = 'html';
	//$mail->Host = gethostname();
	$mail->Host = $SmtpServer;
	$mail->SMTPAuth = true;
	$mail->Username = $SmtpUser;
	$mail->Password = $SmtpPass;
	$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Explicit TLS
	$mail->setFrom($SmtpUser);

	$mail->From = $fromid;
	$mail->FromName = $fromuser;
	$mail->AddAddress($to);

	$mail->IsHTML(true); 
	$mail->Subject = $subject;
	$mail->Body    = $emailtext;

	if(!$mail->Send()) {
	   echo 'Message could not be sent.';
	   echo 'Mailer Error: ' . $mail->ErrorInfo;
	   exit();
	} 

 echo 'done';

?>