<?php 
session_start();

include('includes/Crypto.php');

	//error_reporting(0);
	
	$workingKey='A6AC467601A3DD5F1AB9D9B0A86DDABA';		//Working Key should be provided here.
	$encResponse=$_POST["encResp"];			//This is the response sent by the CCAvenue Server
	$rcvdString=decrypt($encResponse,$workingKey);		//Crypto Decryption used as per the specified working key.
	$order_status="";
	$decryptValues=explode('&', $rcvdString);
	$dataSize=sizeof($decryptValues);
	
	//echo "<center>";

	for($i = 0; $i < $dataSize; $i++){
		$information=explode('=',$decryptValues[$i]);
		if($i==3)	$order_status=$information[1];
	} 
/*
	echo "<br><br>";

	echo "<table cellspacing=4 cellpadding=4>";
	for($i = 0; $i < $dataSize; $i++){
		$information=explode('=',$decryptValues[$i]);
		echo '<tr><td>'.$i.'='.$information[0].'</td><td>'.$information[1].'</td></tr>';

		if($i==3)	$order_status=$information[1];
		if($i==0)	$order_id=$information[1];
		if($i==1)	$tracking_id=$information[1];
		if($i==2)	$bank_ref_no=$information[1];
		if($i==26)	$orderid=$information[1];
	}

	echo "</table><br>";
	echo "</center>";
	

*/

	for($i = 0; $i < $dataSize; $i++) 
	{
		$information=explode('=',$decryptValues[$i]);
		if($i==3)	$order_status=$information[1];
		if($i==0)	$order_id=$information[1];
		if($i==1)	$tracking_id=$information[1];
		if($i==2)	$bank_ref_no=$information[1];
		if($i==26)	$orderid=$information[1];
		if($i==27)	$compid=$information[1];
		if($i==28)	$oldsession=$information[1];
		if($i==10)	$rettotal=$information[1];
		if($i==9)	$retcur=$information[1];
	}



		require('manage/db5conn.php');
		if($_SESSION["compid"]>0){ }else if($compid>0){
			$row=query_first("SELECT compid, email, username FROM ccd9orders where compid='$compid' and orderid='$orderid'");
			if($row['compid']>0){
				session_start();
				$_SESSION["compid"] = $row['compid'];
				$_SESSION["email"] = $row['email'];
				$_SESSION["name"] = $row['username'];
			}
		}

	//echo $_SESSION["compid"].'<br>'.$compid;

	//exit("data received for ".$orderid);

		$row=query_first("SELECT compid FROM ccd9cart where sessionid='".session_id()."' and compid='".$_SESSION["compid"]."' and status='0'");
		if($row['compid']>0){
		}else{
			$mysqli->query("update ccd9cart set sessionid='".session_id()."' where compid='".$_SESSION["compid"]."' and status='0'");
			$mysqli->query("update ccd9orders set sessionid='".session_id()."' where compid='".$_SESSION["compid"]."' and status='0' and orderid='$orderid'");
		}

		$row=query_first("SELECT sum(finalprice*prodqty) as tot FROM `ccd9cart` where sessionid='".session_id()."' and status='0'");
		$prodtot=$row['tot'];
		$row=query_first("SELECT ordtotal, discountamt, shippingamt FROM ccd9orders where sessionid='".session_id()."' and orderid='$orderid'");
		if($row['ordtotal']!=$rettotal || ($prodtot-$row['discountamt']+$row['shippingamt'])!=$rettotal){
			$to="samir.sudrik@gmail.com";
					
			$subject="order total error from $adminuser";

			$orderemail="<br> compid=".$_SESSION["compid"];
			$orderemail=$orderemail."<br> order # ".$orderid;
			$orderemail=$orderemail."<br> paymentID ".$tracking_id;
			$orderemail=$orderemail."<br> totalAmount ".$rettotal;
			$orderemail=$orderemail."<br> status ".$order_status;

			sendsmtpmail($to,$subject,$orderemail);

		}


	if($order_status==="Success")
	{
		$ordermsg= "Payment successfully credited"; //"Thank you for shopping with us. Your credit card has been charged and your transaction is successful. We will be shipping your order to you soon.";
		$mysqli->query("update ccd9orders set orddate=now(), status='1', tx='$tracking_id', bankref='$bank_ref_no' where orderid='$orderid' and compid='".$_SESSION["compid"]."'");
		$mysqli->query("update ccd9cart set orderid='$orderid', status='1' where sessionid='".session_id()."' and compid='".$_SESSION["compid"]."' and prodqty>0 and status='0'");

		sendordemail($orderid);

		//processgiftcard($orderid);

		//redimdiscountemail($orderid);

		//remsaleprod($orderid);

		header("location:success?id=".$orderid."&errmsg=".$ordermsg);
		exit();
		
	}
	else if($order_status==="Aborted")
	{
		
		$ordermsg="Thank you for shopping with us. However,the transaction has been failed.";
		header("location:shopping-cart?errmsg=".$ordermsg);
		exit();
	
	}
	else if($order_status==="Failure")
	{
		
		$ordermsg="Thank you for shopping with us. However, the transaction has been declined.";
		header("location:shopping-cart?errmsg=".$ordermsg);
		exit();
	}
	else
	{
		$ordermsg="Security Error. Illegal access detected";
		header("location:shopping-cart?errmsg=".$ordermsg);
		exit();
	
	}
?>
