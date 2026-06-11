<?php
//phpinfo();


$emailtxt="<table border='1' cellspacing='0' rules='all' style='border-color: #666;' cellpadding='10'><tr style='background: #eee;'><td nowrap>Your OTP Code: ".$userotp."</td></tr></table><BR><BR>$adminuser";

$technicalemails="";
sendsmtpmail("samir.sudrik@gmail.com","OTP Code PS",$emailtxt,$technicalemails);

function sendsmtpmail($to,$subject,$emailtext,$bcc=''){

	require("phpmailer/class.phpmailer.php");
	include("dbconfig.php");

	$arremail = array();
	if(strstr($bcc,',')){
		$arremail=explode(',',$bcc);
	}else if($bcc!=''){
		$arremail[0]=$bcc;
	}

	$mail = new PHPMailer;

	$mail->IsSMTP();                                      // Set mailer to use SMTP
	$mail->Host = $SmtpServer;                 // Specify main and backup server
	$mail->Port = $SmtpPort;                                    // Set the SMTP port
	$mail->SMTPAuth = true;                               // Enable SMTP authentication
	$mail->Username = $SmtpUser;                // SMTP username
	$mail->Password = $SmtpPass;                  // SMTP password
	$mail->SMTPSecure = 'plain';                            // Enable encryption, 'ssl' also accepted

	$mail->From = $adminid;
	$mail->FromName = $adminuser;
	$mail->AddAddress($to);

	for($z=0;$z<count($arremail);$z++){
		if(trim($arremail[$z])!=''){
			$mail->AddBcc($arremail[$z]);

		}
	}
	$mail->IsHTML(true);                                  // Set email format to HTML

	$mail->Subject = $subject;
	$mail->Body    = $emailtext;
	//$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

	if(!$mail->Send()) {
	   echo 'Message could not be sent.';
	   echo 'Mailer Error: ' . $mail->ErrorInfo;
	   exit;
	}

	//echo 'Message has been sent';

}
?>