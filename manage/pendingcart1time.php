<?php 
//php -q /home/payals/public_html/manage/pendingcart.php?m2u=USD >/dev/null 2>&1
//session_start();
ini_set('max_execution_time', 3000);
include("db5conn.php");
//include('SMTPClass.php');

//$mysqli->query("delete FROM `ccd9discounts` where discid not in (select discid from ccd9orders where discid>0) and discexpiry<now() and discdescr='cart discount'"); 

$resdb=$mysqli->query("SELECT  * FROM `ccd9orders` where status=0 and ModifiedTime>DATE_ADD(CURDATE(), INTERVAL -90 DAY) and ordtotal>0 and compid>0 and compid not in (2,26,3798,7,11,3,93,96,97,98,99,100,106,536, 3760,3630,1395,1311,700,845,50,90,164,566) and email!='' group by compid order by orderid"); // limit 0,1
while($resord=$resdb->fetch_array()){ 
	$userid = $resord['compid'];
	$billing_username=dbval($resord['billing_username']);
	$billing_lastname=dbval($resord['billing_lastname']);
	$billing_email = $resord['email'];

	include("cartpg.php");
	
	if($cartrows>0){$i++;

		//$disccode= random_strings(10); 
		//$sql="insert into ccd9discounts (`disccode`, `discamt`, `disctype`, `discuse`, `discexpiry`, compid, discdescr, exclcats ) values ('$disccode', '10', '1', '0', DATE_ADD(CURDATE(), INTERVAL 7 DAY), '$userid', 'cart discount','148,149,335')";
		//$mysqli->query($sql);

		$emailtext=getpagedata('108');
		$subject=getpagetitle('108');
		$disccode='PSSPRING15';

		/*$emailtext=getpagedata('47');
		$subject=getpagetitle('47');*/
		
		$emailtext=str_replace("##customername##",$billing_username.' '.$billing_lastname,$emailtext);
		$emailtext=str_replace("##shoppingcart##",$cartpg,$emailtext);
		$emailtext=str_replace("##promocode##",$disccode,$emailtext);
		

		$to=$billing_email; //"samir.sudrik@gmail.com"; 
	 
		//$to="support@udeeta.com";
		$technicalemail="samir.sudrik@gmail.com";
		
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= "From: $adminuser <$adminid>\r\n";
		$headers .= "Bcc: ".$technicalemail."\r\n";
		mail($to, $subject, $emailtext, $headers);
		
/*
		$SMTPMail = new SMTPClient ($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $adminid, $to, $subject, $emailtext);
		$SMTPChat = $SMTPMail->SendMail();

		$SMTPMail = new SMTPClient ($SmtpServer, $SmtpPort, $SmtpUser, $SmtpPass, $adminid, $technicalemail, $subject, $emailtext);
		$SMTPChat = $SMTPMail->SendMail();
	*/
		//echo $subject.'<br>';
		echo $to.'<br>';
		//echo $emailtext.'<br>';
		echo '---------------------------------------'.$userid.'-------------'.$i.'-<br>';
	
	}

}


function random_strings($length_of_string) 
{ 
  
    $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'; 

	$i=1;
	while($i){
		$disccode=substr(str_shuffle($str_result),0,$length_of_string);
		$row=query_first("select discid from ccd9discounts where disccode='$disccode'");
		if($row[0]>0){}else{
			break;
		}
	}
	return $disccode; 
} 
  

?>