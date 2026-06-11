<?php 
//session_start();
ini_set('max_execution_time', 3000);
include("db5conn.php");

$resdb=$mysqli->query("SELECT  * FROM `ccd9orders` where status=0 and ModifiedTime between DATE_ADD(NOW(), INTERVAL -24 HOUR) and DATE_ADD(NOW(), INTERVAL -4 HOUR) and ordtotal>0 and compid>0 and compid not in (select compid from ccd9company where discmail='0') and email!='' group by compid order by orderid"); // limit 0,1 
while($resord=$resdb->fetch_array()){ 
	$userid = $resord['compid'];
	$billing_username=dbval($resord['billing_username']);
	$billing_lastname=dbval($resord['billing_lastname']);
	$billing_email = $resord['email'];

	include("cartpg.php");
	
	if($cartrows>0){$i++;

		$sql="select typevalue2 from ccd9types where typevalue1='1' and opt='106'";
		$result = $mysqli->query($sql);
		if($rescon = $result->fetch_array()){
			$emailtext=getpagedata('108');
			$subject=getpagetitle('108');
			$disccode=$rescon['typevalue2'];
		}else{

			$emailtext=getpagedata('23'); 
			$subject=getpagetitle('23');
		}
		
		$emailtext=str_replace("##customername##",$billing_username.' '.$billing_lastname,$emailtext);
		$emailtext=str_replace("##shoppingcart##",$cartpg,$emailtext);
		$emailtext=$emailtext.'<br><br><a href="'.$serverurl.'unsubscribe?ref='.urlencode(encyrptPassword($resord['compid'])).'" target="_blank">Unsubscribe</a><br><br>';

		$to=$billing_email; //"samir.sudrik@gmail.com"; // "samir@swarom.com,nirvaan@orthofitmart.com,suraj@orthofitmart.com"; //
	 
		//$to="support@udeeta.com";
		$technicalemail="samir.sudrik@gmail.com";

		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= "From: $adminuser <$adminid>\r\n";
		$headers .= "Bcc: ".$technicalemail."\r\n";
		//mail($to, $subject, $emailtext, $headers);

		//sendsmtpmail($to,$subject,$emailtext,$technicalemail);
	
		echo $subject.'<br>';
		echo $to.'<br>';
		echo $emailtext.'<br>';
		echo '---------------------------------------'.$userid.'-------------'.$i.'-<br>';
	
	}

}


?>