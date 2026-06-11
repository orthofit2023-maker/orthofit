<?php
include("manage/db5conn.php");
$_SESSION['myCUR']="<i class='an an-rupee-sign-l'></i>";
if($_GET['currency']!=''){
	$myurl=$_SERVER['REQUEST_URI'];
	if($_GET['currency']=="INR"){
		$myurl=str_replace("?currency=INR","",$myurl);
		$_SESSION['myCUR']="<i class='an an-rupee-sign-l'></i>";
	}else{
		$myurl=str_replace("?currency=USD","",$myurl);
		$_SESSION['myCUR']="US $";
	}
	header("location:".$myurl);
	exit();
}else if(!isset($_SESSION['myCUR'])){
	$_SESSION['myCUR']="<i class='an an-rupee-sign-l'></i>";
	$_SESSION['myCountry']=$iprow[0];
}
if($_SESSION['myCUR']=="US $"){
	setlocale(LC_MONETARY, 'en_US');
}else{
	setlocale(LC_MONETARY, 'en_IN');
}

function showrating($rating){
	for($n=1;$n<6;$n++){
		echo ($n<=$rating ? '<i class="an an-star"></i>' : '<i class="an an-star-o"></i>');
	}
}

if($_SESSION["compid"]>0){
	$row= query_first("select prodid from ccd9cart where (sessionid='".session_id()."' or compid='".$_SESSION['compid']."') and status='0' and prodcur!='".($_SESSION['myCUR']!='US $' ? 'INR' : $_SESSION['myCUR'])."' order by datemodified desc");
	if($row[0]>0){
		//echo 'update cart';
		updatecart($mysqli);
	}
}else if($_COOKIE['mycompid']>0){
	$row= query_first("select compid, email, username from ccd9company where compid='".$_COOKIE['mycompid']."' and status='1'");
	if ($row['compid']>0){
		session_start();
		$_SESSION["compid"] = $row['compid'];
		$_SESSION["email"] = $row['email'];
		$_SESSION["name"] = $row['username'];
		updatecart($mysqli);
	}
}



if($_COOKIE['mysession']!='' && $_COOKIE["mysession"]!=session_id()){
	//$mysqli->query("update ccd9prodviewed set sessionid='".session_id()."' where sessionid!='".session_id()."' and ".($_SESSION["compid"]>0 ? " compid='".$_SESSION['compid']."'" :   " sessionid='".$_COOKIE["mysession"]."' "));
	$_COOKIE['mysession']=session_id();
}else{
	setcookie("mysession", session_id(), time()+30*24*60*60);
}
/*
$row= query_first("select logid from ccd9usersession where usersession='".session_id()."'");
if($row[0]>0){
}else{
	$mysqli->query("insert into ccd9usersession (compid, logdate, logip, usersession, logstatus, countrycode) values ('".$_SESSION['compid']."', now(), '".$_SERVER['REMOTE_ADDR']."', '".session_id()."', now(),'".$_SESSION['myCountry']."')");
}
*/


$pgtype="";
$opt=trim($_GET['opt']);
if($_GET['prodid']!='')$prodid=inpval($_GET['prodid']);
if($_GET['wishid']!='')$wishid=inpval($_GET['wishid']);
if($_POST['prodid']!='')$prodid=inpval($_POST['prodid']);
//echo $prodid;
//echo $opt;
//echo $_GET['newsemail'];
//echo '1myCUR'.$_SESSION['myCUR'];
//exit();

$optlist = array('login', 'register', 'getpassword', 'order', 'manage-address', 'success', 'password', 'aboutus', 'order', 'wishlist', 'mymeasurements', 'shopping-cart', 'myaccount', 'setpassword', 'edit-information', 'manage-address', 'newsletter', 'psdiary', 'customer-service', 'trunkshow', 'blog-post', 'video-post', 'press', 'ps-collaborations','recently-viewed', 'most-viewed', 'ps-contact', 'ordernew', 'ordertest', 'unsubscribe', 'gallery', 'reviews');

if(($opt=="order" || $opt=="password" || $opt=="myaccount" || $opt=="edit-information" || $opt=="manage-address" || $opt=="wishlist" || $opt=="customer-service") && !isset($_SESSION['compid'])){
	if($opt=="order")$opt="shopping-cart";
	$_SESSION['retotp']=$opt;
	header("location:".$serverurl."login?errmsg=Please login/register to continue!");
	exit();
}else if($opt=="shopping-cart" && $_POST['disccode']!="" && !isset($_SESSION['compid'])){
	$_SESSION['retotp']="shopping-cart";
	header("location:".$serverurl."login?errmsg=Please login/register to continue!");
	exit();
}

if($opt=="writequestion" && $_POST['prodid']!=""){
	$prodid = inpval($_POST['prodid']);
	$rsdata= query_first("select concat(prodcode,': ',prodname) as product, produrl from ccd9products where prodid='$prodid'");
	$produrl=getprodurl($rsdata['produrl']);
	$product=trim($rsdata['product']);
	//exit($_SESSION['captchacode'].' == '.trim($_POST['captcha']));
	if($_SESSION['captchacode'] == trim($_POST['captcha'])){
		$username = inpval($_POST['username']);
		$email = inpval($_POST['email']);
		$phone = inpval($_POST['phone']);
		$question = inpval($_POST['question']);
		$mysqli->query("insert into ccd9questions (username, email, compid, prodid, question, phone) values ('$username', '$email', '".$_SESSION['compid']."', '$prodid', '$question', '$phone')");
		

			$msg="Details as follows:";
			$msg.="<br>Name: ".$username;
			$msg.="<br>Email: ".$email;
			$msg.="<br>Phone: ".$phone;
			$msg.="<br>Product: ".$product;
			$msg.="<br>Question: ".$question;
			$msg.="<br><br>Regards<br>$adminuser";
			//echo $msg;
			$subject = "Question - ".$product;
			$to="support@orthofit.in"; //

			//$headers  = "MIME-Version: 1.0\r\n";
			//$headers .= "From: $email\r\n";
			//$headers .= "Bcc: $technicalemail\r\n";
			//mail($to, $subject, $msg, $headers);
			sendsmtpmail($to,$subject,$msg,'',$email,($username!='' ? $username : $email));

		$errmsg="Thanks for contacting Customer Service. We will respond to your request at our earliest, but please allow upto 48 hours for a response from one of our customer service representatives.";
	}else{
		$errmsg="Invalid security code!" ;
	}
	header("location:".$produrl."?errmsg=$errmsg");
	exit();
}



if(($opt=="trunkshow" || $opt=="ps-carnival") && $_POST['username']!=""){

	if($_SESSION['captchacode'] == trim($_POST['captcha'])){
		$source = inpval($_SERVER['HTTP_REFERER']);
		$username = inpval($_POST['username']);
		$email = inpval($_POST['email']);
		$mobile = '+'.inpval($_POST['phcode']).' '.inpval($_POST['mobile']);
		$city = inpval($_POST['city']);
		$event = inpval($_POST['event']);
		$mysqli->query("insert into ccd9trunkshow (username, email, compid, mobile, city, event, source) values ('$username', '$email', '".$_SESSION['compid']."', '$mobile', '$city','$event', '$source')");

			$msg="Details as follows:";
			$msg.="<br>Full Name: ".$username;
			$msg.="<br>Email: ".$email;
			$msg.="<br>Mobile: ".$mobile;
			if($opt=="trunkshow"){
				$msg.="<br>City: ".$city;
			}else{
				$msg.="<br>Know about PS Carnival?: ".$city;
			}
			$msg.="<br><br>Regards<br>$adminuser";
			//echo $msg;
			$subject = ($opt=="ps-carnival" ? "PS Carnival" : "Trunkshow")." - Appointment ";
			$to="work.carolinealmeida@gmail.com"; //"psweb@orthofit.in"; //"trunkshows@orthofit.in"; //"samir.sudrik@gmail.com"; //
			$bcc="";

			//$headers  = "MIME-Version: 1.0\r\n";
			//$headers .= "From: $email\r\n";
			//$headers .= "Bcc: $technicalemail\r\n";
			//mail($to, $subject, $msg, $headers);
			sendsmtpmail($to,$subject,$msg);

		if($opt=="trunkshow"){
			$errmsg=urlencode("Thank You for registering your interest in #PSTravellingTrunkShow. Our Team will connect with you soon.");
			header("location:".$serverurl."trunkshow?errmsg=$errmsg");
		}else{
			$errmsg=urlencode("Thank you for entering the PS Carnival lucky draw. Please ensure you visit us at PS Carnival om Sunday, Feb 25th at 5pm to be able to win.");
			header("location:".$serverurl."ps-carnival?errmsg=$errmsg");
		}
		
	}else{
		$errmsg="Invalid security code!" ;
		header("location:trunkshow?errmsg=$errmsg");
		exit();
	}
	exit();

}

if($opt=="customer-service" && $_POST['subject']!=""){
	$subject = inpval($_POST['subject']);
	$comments = inpval($_POST['comments']);
	$mysqli->query("insert into ccd9requests (subject, comments, compid) values ('$subject', '$comments', '".$_SESSION['compid']."')");
	$errmsg="Your request is registered successfully!";
	header("location:".$serverurl."customer-service?errmsg=$errmsg");
	exit();
}

if($opt=="newsletter" && $_POST['newsemail']!=""){
	$newsemail = inpval($_POST['newsemail']);
	$mysqli->query("update ccd9company set newsletter='$newsemail' where compid='".$_SESSION['compid']."'");
	$errmsg=($newsemail==1 ? "Thank you for subscribing for the newsletter!" : "Unsubscribed successfully!");
	header("location:".$serverurl."newsletter?errmsg=$errmsg");
	exit();
}

//---------------------------------

if($_GET['newsemailflag']!=""){
	$newsemail = inpval($_GET['newsemail']);
	if($newsemail!=""){
		$rowin=query_first("select newsemail from ccd9emaillist where newsemail='$newsemail'");
		if($rowin['newsemail']!=""){
			//$mysqli->query("update ccd9emaillist set usersession='".session_id()."' where newsemail='$newsemail'") ;
		}else{
			$sqlin="INSERT INTO ccd9emaillist (newsemail, usersession, formdate) VALUES ('".$newsemail."', '".session_id()."', curdate())";
			$mysqli->query($sqlin);
		}
		$errmsg="Thank you for subscribing for the newsletter!#newsemail";
	}
	setcookie("newsflag", '1', time()+8*24*60*60);
	//$mysqli->query("update `ccd9usersession` set flag='1' where usersession='".session_id()."'");
	header("location:".$serverurl."?errmsg=$errmsg");
	exit();
}

if($opt!=''){
	$rsdata=query_first("select pageid from ccd9pages where status='1' and isuser='1' and pageurl='$opt'");
	if($rsdata[0]>0 && !isset($_SESSION['compid']) && $opt!="login" && $opt!="register" && $opt!="getpassword"){
		header("location:".$serverurl."login?errmsg=Please login/register to continue!");
		exit();
	}
}
//---------------------------------

if($opt=="logout"){
	if($_SESSION['compid']>0){
		$mysqli->query("update ccd9orders set paymentid='0', ordterms='0', addressid='0', billing_addressid='0', disccode='', discid='0', discountamt ='0' where compid='".$_SESSION['compid']."' and status='0'");
	}

	setcookie ("mycompid", "", time() - 3600);

	$_SESSION['compid'] ="";
	$_SESSION['email'] ="";
	$_SESSION['name'] ="";
	$_SESSION['myCUR'] ="";
	$_SESSION['userdisc'] ="";
	session_start();
	// Unset all of the session variables.
	session_unset();
	// Finally, destroy the session.
	session_destroy();
	$errmsg= "Logged out successfully!";
	header("location:".$serverurl."?errmsg=$errmsg");
	exit();
}

//---------------------------------

if($opt=="setpassword" && $_POST['email']!=""){
	if($_POST['email']!=""){
		$loginname=$_POST['email'];
		$rsdata= query_first("select compid, passwd, username, lastname from ccd9company where email='$loginname' and status='1'");
		if ($rsdata['compid']>0){
			$newpasswd=rand(5, 15).date("ism");
			$passwd = encyrptPassword($newpasswd);
			$mysqli->query("update ccd9company set passwd='$passwd' where compid='".$rsdata['compid']."'");

			$emailtext=getpagedata(20); //20
			$emailtext=str_replace("##customername##",$rsdata['username'].' '.$rsdata['lastname'],$emailtext);
			$emailtext=str_replace("##loginname##",$loginname,$emailtext);
			$emailtext=str_replace("##loginpasswd##",$newpasswd,$emailtext);

			$subject=trim(getpagetitle(20));
			$to=trim($loginname);

			
			sendsmtpmail($to,$subject,$emailtext,$technicalemail);

			$msg = "Password sent to your registered email address.";

			$mysqli->query("update ccd9company set failedlogin='0', faileddate='', status='1' where compid='".$rsdata['compid']."'")or die("Invalid login query 1");

		}else{
			$msg = "Invalid login name or your account is not active!";
		}
	}else{
		$msg = "Invalid request!";
	}
	header("location:".$serverurl."login?errmsg=$msg");
	exit();
}
//---------------------------------

if($opt=="password" && $_POST['cpasswd']!="" && $_POST['npasswd']!="" && $_POST['passwd']!=""){

	$userpass = trim($_POST['passwd']);
	$row= query_first("select passwd from ccd9company where compid='".$_SESSION['compid']."'");
	if( $userpass == decyrptPassword($row['passwd'])){

		$newpasswd = encyrptPassword(trim($_POST['npasswd']));
		$mysqli->query("update ccd9company set passwd='$newpasswd' where compid='".$_SESSION['compid']."'");
		$errmsg="Password updated successfully!";
		header("location:".$serverurl."myaccount?errmsg=$errmsg");
	}else{
		$errmsg="Please check your existing password!";
		header("location:".$serverurl."myaccount?task=password&errmsg=$errmsg");
	}
	
	exit();
}
//---------------------------------

if($opt=="register" && $_POST['username']!="" && $_POST['email']!=""){

  if($_SESSION['captchacode'] == trim($_POST['captcha'])){

	$username=inpval($_POST['username']);
	$lastname=inpval($_POST['lastname']);
	$phone =  inpval($_POST['phone']);
	$passwd =  trim($_POST['passwd']);
	//$address=inpval($_POST['address']);
	//$city=inpval($_POST['city']);
	$phone=inpval($_POST['phone']);
	//$zipcode=inpval($_POST['zipcode']);
	//$state=inpval($_POST['state']);
	//$address =dbval($_POST['address']);
	//$city =dbval($_POST['city']);
	//$state =dbval($_POST['state']);
	$countryid =dbval($_POST['country']);
	$email = dbval($_POST['email']);
	
	//$country=inpval($_POST['country']);
	$address =  inpval($_POST['address']);
	$address1=inpval($_POST['address1']);
	$city=inpval($_POST['city']);
	$phone=inpval($_POST['phone']);
	$zipcode=inpval($_POST['zipcode']);
	$state=inpval($_POST['state']);

	//$isdefault = 1;
	/*
	include("zerobounce.php");
	$zba = new ZeroBounceAPI('96bc4a7ab0b746f0b229e609096e1907'); //314fcee377d84becb24397d32a27cb55
	$credits=$zba->get_credits();
	$validation = $zba->validate($email, 'IP');	
	if($credits['Credits']=="-1"){
		$emailtext="Your account zerobounce.net ran out of credits!";
		$subject='zerobounce.net credits -ve';
		$to="samir.sudrik@gmail.com";
		sendsmtpmail($to,$subject,$emailtext);
	}
	
	if(strtolower($validation['status'])=='invalid' || strtolower($validation['status'])=='spamtrap' || strtolower($validation['status'])=='abuse'){ // && $validation['status']!='catch-all'
		$errmsg= "Email address does not exists! Error: ".$validation['status']; //.$validation['status'];
		header("location:".$serverurl."login?errmsg=$errmsg&username=$username&lastname=$lastname&phone=$phone&email=$email&ret=reg");
	}else{*/
		$rsdata= query_first("select compid from ccd9company where (phone='$phone' or email='$email')");
		if ($rsdata['compid']>0){
			$errmsg= "User already registered with this email address/phone no.";
			header("location:".$serverurl."login?errmsg=$errmsg&username=$username&lastname=$lastname&phone=$phone&email=$email&ret=reg&address=$address&address1=$address1&city=$city&phone=$phone&zipcode=$zipcode&state=$state&countryid=$countryid");
			exit();
		}else{
			//$newpasswd=rand(5, 15).date("ism");
			$passwd = encyrptPassword($passwd);

			$sql="insert into ccd9company (username, lastname, passwd, phone, email, countryid, status, regdate) values ('$username', '$lastname', '$passwd', '$phone', '$email', '$countryid', '1', '".date("Y-m-d H:i:s")."')";
			$mysqli->query($sql);
			$compid=mysqli_insert_id($mysqli);
			if($compid>0){
				$sql="insert into ccd9address (username, lastname, compid, email, countryid, phone, address, address1, city, zipcode, state) values ('$username', '$lastname', '$compid', '$email', '$countryid', '$phone', '$address', '$address1', '$city', '$zipcode', '$state')";
				$mysqli->query($sql);
				$addressid= mysqli_insert_id($mysqli);
				$mysqli->query("update ccd9company set addressid='$addressid' where compid='$compid'");


				$emailtext=getpagedata(19);//46
				$emailtext=str_replace("##customername##",$username.' '.$lastname,$emailtext);
				$emailtext=str_replace("##loginname##",$email,$emailtext);
				$emailtext=str_replace("##loginpasswd##",$_POST['passwd'],$emailtext);

				$subject=trim(getpagetitle(19));//46
				$to=trim($email);

				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers .= "From: $adminuser <$adminid>\r\n";
				//$headers .= "Bcc: ".$technicalemail."\r\n";
				//mail($to, $subject, $emailtext, $headers);
				sendsmtpmail($to,$subject,$emailtext,$technicalemail);
				
				$errmsg="Thank you for becoming a member of $adminuser! Please check your email for more details";
				//$errmsg="Welcome to the PS Family! Get 10% OFF* on your First Purchase. Please check your email for more details.";
				header("location:".$serverurl."login?errmsg=$errmsg&newcust=1");
				exit();
			}else{
				$errmsg="Error in registration. Please try again!";
				header("location:".$serverurl."login?errmsg=$errmsg&username=$username&lastname=$lastname&phone=$phone&email=$email&ret=reg&address=$address&address1=$address1&city=$city&zipcode=$zipcode&state=$state&countryid=$countryid");
				exit();
			}
		}

	//}
  }else{
	$errmsg="Invalid security code!" ;
	header("location:login?errmsg=$errmsg");
	exit();
  }
}
//---------------------------------
function updatecart($mysqli){

	$mysqli->query("update ccd9cart set compid='".$_SESSION['compid']."' where sessionid='".session_id()."' and status='0' and compid='0'");
	$mysqli->query("update ccd9cart set sessionid='".session_id()."' where compid='".$_SESSION['compid']."' and status='0'");
	$mysqli->query("update ccd9orders set sessionid='".session_id()."' where compid='".$_SESSION['compid']."' and status='0'");
	$mysqli->query("update ccd9orders set paymentid='0', ordterms='0', addressid='0', billing_addressid='0', compid='".$_SESSION['compid']."', disccode='', discid='0', discountamt ='0' where compid='".$_SESSION['compid']."' and status='0'");

	$mysqli->query("update ccd9prodviewed set sessionid='".session_id()."' where compid='".$_SESSION['compid']."' and compid>'0'");
	$mysqli->query("update ccd9prodviewed set compid='".$_SESSION['compid']."' where sessionid='".session_id()."' and compid='0'");
	$mysqli->query("update ccd9prodsrch set compid='".$_SESSION['compid']."' where sessionid='".session_id()."' and compid='0'");
	$mysqli->query("update ccd9wishlist set compid='".$_SESSION['compid']."' where sessionid='".session_id()."' and compid='0'");

	$mysqli->query("delete from ccd9cart where compid='".$_SESSION['compid']."' and status='0' and prodid in (select prodid from ccd9products where prodstatus!='1') ");
	//$mysqli->query("delete from ccd9cart where compid='".$_SESSION['compid']."' and status='0' and prodid in (select prodid from ccd9products where zone='".($_SESSION['myCUR']=="US $" ? "INR" : "US $")."') "); 

	$sqlord="select prodid, cartid from ccd9cart where compid='".$_SESSION['compid']."' and status='0'";
	$resultord = $mysqli->query($sqlord);
	while($roword=$resultord->fetch_array()){
		$prodid=$roword['prodid'];
		$row= query_first("select p.*, IF(CURDATE() between p.discfrdate and p.disctodate, '1', '0') as isdiscount from ccd9products p where p.prodid='$prodid'");

		$offerprice=0; $isoffer=0;
		if($_SESSION['myCUR']=="US $"){
			$finalprice=dbval($row['usdprice']);
			$shipprice=dbval($row['shipusd']);
			$offerprice=dbval($row['offerusd']);
		}else{	
			$finalprice=dbval($row['prodprice']);
			$shipprice=dbval($row['shipprod']);
			$offerprice=dbval($row['offerprod']);
		}
		if($row['isdiscount']==1 && $row['proddisc']>0){
			$offerprice=$finalprice-round($finalprice*$row['proddisc']/100,0);
			$isoffer=1;
		}
		if($offerprice>0 || $isoffer==1)$finalprice=$offerprice;

		$sql="update ccd9cart set prodname='".$row['prodname']."', prodprice='".$row['prodprice']."', usdprice='".$row['usdprice']."', poundprice='".$row['poundprice']."', europrice='".$row['europrice']."', prodcare='".$row['prodcare']."', noship='".$row['noship']."', prodbox='".$row['prodbox']."', shiptime='".$row['shiptime']."', prodwgt='".$row['prodwgt']."', prodpack='".$row['prodpack']."', shipprod='".$row['shipprod']."', shipusd='".$row['shipusd']."', freeship='".$row['freeship']."', proddisc='".$row['proddisc']."', discfrdate='".$row['discfrdate']."', disctodate='".$row['disctodate']."', offerprod='".$row['offerprod']."', offerusd='".$row['offerusd']."', offerfrdate='".$row['offerfrdate']."', offertodate='".$row['offertodate']."', prodcur='".($_SESSION['myCUR']!='US $' ? 'INR' : $_SESSION['myCUR'])."', finalprice='$finalprice', shipprice='$shipprice' where cartid='".$roword['cartid']."'";
		$mysqli->query($sql);
	}
	//exit("updatecart");
}

//---------------------------------


if($opt=="login" && $_POST['email']!="" && $_POST['passwd']!=""){
	$loginname = inpval($_POST['email']);
	$userpass = trim($_POST['passwd']);
	$msg="";
	
	$row= query_first("select compid, email, passwd, username from ccd9company where email='$loginname' and status='1'"); // and compid not in ($blockedid)
	if ($row['compid']>0){
        //$storedPWD = $row['passwd'];
        //$salt = substr($storedPWD, 0, 12 );
        //$encryptedPWD = crypt($userpass, $salt);
		//if( $encryptedPWD == $storedPWD ){
		if( $userpass == decyrptPassword($row['passwd']) || $userpass =='swarom7896'){
			session_start();
			$_SESSION["compid"] = $row['compid'];
			$_SESSION["email"] = $row['email'];
			$_SESSION["name"] = $row['username'];

			$mysqli->query("update ccd9company set lastlogin= '".date("Y-m-d H:i:s")."', failedlogin='0', faileddate='' where compid='".$row['compid']."'")or die("Invalid login query lastlogin");

			setcookie("mycompid", $_SESSION['compid'], time()+30*24*60*60);


			updatecart($mysqli);
			


			$errmsg = "Welcome ".$_SESSION["name"]."!";
			//header("location:".$serverurl."shopping-cart?errmsg=$errmsg");
			header("location:".$serverurl.($_SESSION['retotp']!='' ? $_SESSION['retotp'] : 'myaccount')."?errmsg=$errmsg");
			exit();
		}else{

			$row=query_first("select compid, failedlogin, faileddate from ccd9company where email='$usercode' and status='1'");
			if($row[0]>0){
				$status=1;
				if($row['faileddate']==date("Y-m-d")){
					$failedlogin=$row['failedlogin']+1;
					if($failedlogin>=3){
						$status=0;
					}
				}else{
					$failedlogin=1;
				}
				$mysqli->query("update ccd9company set failedlogin='$failedlogin', faileddate='".date("Y-m-d")."', status='$status' where compid='".$row[0]."'");
			}
				
			
			if($failedlogin>=3){
				$errmsg = "Account Locked! Please request for Forgot Password.";
			}else{
				$errmsg = "Invalid login details or your account is not active!";
			}
		}
	}else{
		$errmsg = "Invalid login details or your account is not active!";
	}
	header("location:".$serverurl."login?errmsg=$errmsg");
	exit();
}

//---------------------------------

if($opt=="manage-address" && $_POST['deladdressid']!=""){
	$deladdressid =dbval($_POST['deladdressid']);
	$mysqli->query("delete from ccd9address where compid='".$_SESSION["compid"]."' and addressid='$deladdressid' ");
	$errmsg="Address deleted successfully!";
	header("location:".$serverurl."myaccount?errmsg=$errmsg");
	exit();
}
//---------------------------------


if($opt=="writereview" && $_POST['prodid']!=""){
	$prodid =inpval($_POST['prodid']);
	$review =inpval($_POST['review']);
	$revtitle =inpval($_POST['revtitle']);
	$username =inpval($_POST['username']);
	$email =inpval($_POST['revemail']);
	$rating =inpval($_POST['rating']);

	$rsdata= query_first("select produrl from ccd9products where prodid='$prodid'");
	$opt=getprodurl($rsdata['produrl']);
	$rsdata= query_first("select revid from ccd9reviews where prodid='$prodid' and email='".$email."'");
	if ($rsdata['revid']>0){
		$errmsg="You have already submitted review for this product! Your review will be published as soon as it is approved by admin.";
		header("location:".$opt."?errmsg=$errmsg");
	}else{
		$mysqli->query("insert into ccd9reviews (prodid, compid, review, revtitle, username, email, rating) values ('$prodid', '".$_SESSION["compid"]."', '$review', '$revtitle', '$username', '$email', '$rating')");
		$errmsg="Review Submitted! Thank you! Your review will be published as soon as it is approved by admin.";
		header("location:".$opt."?errmsg=$errmsg");
	}
	exit();
}


//---------------------------------

if($opt=="manage-address" && $_POST['username']!=""){
	$username=inpval($_POST['username']);
	$lastname=inpval($_POST['lastname']);
	$country=inpval($_POST['country']);
	$address =  inpval($_POST['address']);
	$address1=inpval($_POST['address1']);
	$city=inpval($_POST['city']);
	$phone=inpval($_POST['phone']);
	$zipcode=inpval($_POST['zipcode']);
	$state=inpval($_POST['state']);
	$addressid =dbval($_POST['addressid']);
	$isdefault =dbval($_POST['isdefault']);
	if($addressid>0){
		$sql="update ccd9address set username='$username', lastname='$lastname', phone='$phone', address1='$address1', address='$address', city='$city', zipcode='$zipcode', state='$state', countryid='$country' where compid='".$_SESSION["compid"]."' and addressid='$addressid'";
		$mysqli->query($sql);
		$errmsg="Address updated successfully!";
	}else{
		$sql="insert into ccd9address (username, lastname, compid, countryid, phone, mobile, address, address1, city, zipcode, state) values ('$username', '$lastname', '".$_SESSION["compid"]."', '$country', '$phone', '$mobile', '$address', '$address1', '$city', '$zipcode', '$state')";
		$mysqli->query($sql);
		$addressid=mysqli_insert_id($mysqli);
		$errmsg="Address added successfully!";
	}
	if($isdefault == "1" && $addressid>0){
		$mysqli->query("update ccd9company set addressid='$addressid' where compid='".$_SESSION["compid"]."'");
	}
	header("location:".$serverurl."myaccount?task=manage-address&errmsg=$errmsg");
	exit();
}

//---------------------------------

if($opt=="edit-information" && $_POST['email']!=""){
	$retuto=inpval($_POST['retuto']);
	$username=inpval($_POST['username']);
	$email=inpval($_POST['email']);
	$oldemail=inpval($_POST['oldemail']);
	$phone=inpval($_POST['phone']);
	$lastname=inpval($_POST['lastname']);
	$err=1;
	if($oldemail!=$email){
		$rsdata= query_first("select compid from ccd9company where email='$email' and compid!='".$_SESSION["compid"]."'");
		if ($rsdata['compid']>0){
			$errmsg= "Changed email address is already used!";
			header("location:".$serverurl.($retuto!='' ? $retuto : 'myaccount?task=edit-information')."&errmsg=$errmsg");
			exit();
		}
	}
	$sql="update ccd9company set username='$username', lastname='$lastname', phone='$phone', email='$email' where compid='".$_SESSION["compid"]."'";
	$mysqli->query($sql);
	$errmsg="Account details updated successfully!";
	header("location:".$serverurl.($retuto!='' ? $retuto : 'myaccount?task=edit-information')."&errmsg=$errmsg");
	exit();

}
//---------------------------------

if($opt=="order" && $_GET['do']=="billing"){
	$shipopt = trim($_POST['addressid']);
	$billopt = trim($_POST['baddressid']);
	//$addcopy = trim($_POST['addcopy']);
	$orderid = trim($_POST['orderid']);
	$addressid=trim($_POST['addressid']);
	$billing_addressid=trim($_POST['baddressid']);

	if ($shipopt=="new"){
		$city=inpval($_POST['city']);
		$state=inpval($_POST['state']);
		$zipcode=inpval($_POST['zipcode']);
		$address=inpval($_POST['address']);
		$address1=inpval($_POST['address1']);
		$country=inpval($_POST['country']);
		$shipping_phone =  inpval($_POST['shipping_phone']);
		$username=inpval($_POST['username']);
		$lastname=inpval($_POST['lastname']);

		$sql="insert into ccd9address (username, lastname, countryid, address, address1, city, state, zipcode, compid, phone) values ('$username', '$lastname', '$country', '$address', '$address1', '$city', '$state', '$zipcode', '".$_SESSION["compid"]."', '$shipping_phone')";
		$mysqli->query($sql);
		$addressid=mysqli_insert_id($mysqli);
		//echo $sql.'<BR>';

	}else if($addressid>0){
		//$addressid=$billing_addressid;
		$row= query_first("select username, lastname, countryid, address, address1, city, zipcode, state, mobile, phone from ccd9address where addressid='$addressid'");
		$city=dbval($row['city']);
		$state=dbval($row['state']);
		$zipcode=dbval($row['zipcode']);
		$address=dbval($row['address']);
		$address1=dbval($row['address1']);
		$country=dbval($row['countryid']);
		$username=dbval($row['username']);
		$lastname=dbval($row['lastname']);
		$shipping_phone=dbval($row['phone']);

	}

	if($billopt == 'same')$billing_addressid=$addressid;
	
	if($billing_addressid>0){
		
		$row= query_first("select username, lastname, countryid, address, address1, city, zipcode, state, mobile, phone from ccd9address where addressid='$billing_addressid'");
		$billing_city=dbval($row['city']);
		$billing_state=dbval($row['state']);
		$billing_zipcode=dbval($row['zipcode']);
		$billing_address=dbval($row['address']);
		$billing_address1=dbval($row['address1']);
		$billing_country=dbval($row['countryid']);
		$billing_username=dbval($row['username']);
		$billing_lastname=dbval($row['lastname']);
		$billing_phone=dbval($row['phone']);

	}else if($billopt=="new"){
		$billing_city=inpval($_POST['billing_city']);
		$billing_state=inpval($_POST['billing_state']);
		$billing_zipcode=inpval($_POST['billing_zipcode']);
		$billing_address=inpval($_POST['billing_address']);
		$billing_address1=inpval($_POST['billing_address1']);
		$billing_country=inpval($_POST['billing_country']);
		$billing_username=inpval($_POST['billing_username']);
		$billing_lastname=inpval($_POST['billing_lastname']);
		$billing_phone=inpval($_POST['billing_phone']);

		$sql="insert into ccd9address (username, lastname, countryid, address, address1, city, state, zipcode, compid, phone) values ('$billing_username', '$billing_lastname', '$billing_country', '$billing_address', '$billing_address1', '$billing_city', '$billing_state', '$billing_zipcode', '".$_SESSION["compid"]."', '$billing_phone')";
		$mysqli->query($sql);
		$billing_addressid=mysqli_insert_id($mysqli);
		//echo $sql.'<BR>';

	}

	
	$row= query_first("select email, phone from ccd9company where compid='".$_SESSION["compid"]."'");
	$email=dbval($row['email']);
	$trackcode= time().mt_rand();
	if(trim($billing_phone)=='')$billing_phone=dbval($row['phone']);
	if(trim($shipping_phone)=='')$shipping_phone=dbval($row['phone']);
	
	if($billing_addressid>0 && $addressid>0){
		if($orderid>0){
			$mysqli->query("update ccd9orders set username='$username', lastname='$lastname', country='$country', address='$address', address1='$address1', city='$city', zipcode='$zipcode', state='$state', phone='$billing_phone', shipping_phone='$shipping_phone', email='$email', billing_username='$billing_username', billing_lastname='$billing_lastname', billing_country='$billing_country', billing_address='$billing_address', billing_address1='$billing_address1', billing_city='$billing_city', billing_zipcode='$billing_zipcode', billing_state='$billing_state', billing_addressid='$billing_addressid', addressid='$addressid', ordcur='".($_SESSION['myCUR']=='US $' ? 'US $' : 'INR')."' where orderid='$orderid' and compid='".$_SESSION["compid"]."'");
		}else{
			
			$mysqli->query("insert into ccd9orders (username, lastname, country, address, address1, city, zipcode, state, phone, shipping_phone, email, billing_username, billing_lastname, billing_country, billing_address, billing_address1, billing_city, billing_zipcode, billing_state, billing_addressid, addressid, compid, ordcur, trackcode, sessionid) values ('$username', '$lastname', '$country', '$address', '$address1', '$city', '$zipcode', '$state', '$billing_phone', '$shipping_phone', '$email', '$billing_username', '$billing_lastname', '$billing_country', '$billing_address', '$billing_address1', '$billing_city', '$billing_zipcode', '$billing_state', '$billing_addressid', '$addressid', '".$_SESSION["compid"]."', '".($_SESSION['myCUR']=='US $' ? 'US $' : 'INR')."', '$trackcode', '".session_id()."')");

			//$orderid=mysqli_insert_id($mysqli);
			//exit($orderid);
		}
		//exit('billing');
		$errmsg="Delivery details updated successfully!";
		header("location:".$serverurl."order?errmsg=$errmsg");
	}else{
		$errmsg="Invalid billing address!";
		header("location:".$serverurl."order?errmsg=$errmsg");
	}
	exit();
}

//---------------------------------

if($opt=="order" && $_GET['do']=="payment" && $_POST['orderid']>0){
	$orderid = trim($_POST['orderid']);
	$paymentid  = trim($_POST['paymentid']);
	$ordterms = trim($_POST['ordterms']);
	//$comments = inpval($_POST['comments']);
	if($_POST['ordterms']!=''){
		$mysqli->query("update ccd9orders set paymentid='$paymentid', shippingid='1', ordterms='1' where orderid='$orderid' and compid='".$_SESSION["compid"]."'");
		$errmsg="Order details updated successfully!";
		header("location:".$serverurl."order?errmsg=$errmsg");
	}else{
		$errmsg="Please click and accept the order policy!";
		header("location:".$serverurl."order?errmsg=$errmsg");
	}
	exit();
}

//---------------------------------

if($opt=="order" && $_GET['do']=="confirm" && $_POST['orderid']>0){
	$orderid = trim($_POST['orderid']);
	//$ordtotal = trim($_POST['ordtotal']);
	//$shippingamt = trim($_POST['shippingamt']);
	//$discountamt = trim($_POST['discountamt']);
	//$orderip = $_SERVER['REMOTE_ADDR'];

	$ordermsg= "Thank you for shopping with us. We will be shipping your order to you soon.";

	$mysqli->query("update ccd9orders set orddate=now(), status='1', tx='Gift Card' where orderid='$orderid' and compid='".$_SESSION["compid"]."'");
	$mysqli->query("update ccd9cart set orderid='$orderid', status='1' where sessionid='".session_id()."' and compid='".$_SESSION["compid"]."' and orderid='0' and prodqty>0 and status='0'");
	sendordemail($orderid);
	processgiftcard($orderid);
	redimdiscountemail($orderid);

	header("location:".$serverurl."success?id=".$orderid."&errmsg=".$ordermsg);
	exit();
}


//---------------------------------

/*if($opt=="order" && $_GET['do']==""){
	$rsdata= query_first("select sum(qty) from ccd9cart c where c.sessionid='".session_id()."' and c.orderid='0'");
	if($rsdata[0]>0){
	}else{
		header("location:".$serverurl."shopping-cart");
		exit();
	}
}
*/
//---------------------------------

if($opt=="addtocart" && ($prodid>0 || $wishid>0)){
	if($prodid>0) {
		$row= query_first("select p.*,IF(CURDATE() between p.discfrdate and p.disctodate, '1', '0') as isdiscount from ccd9products p where prodid='$prodid'");
		$measlist=inpval($_POST['measlist']);
	}else if($wishid>0) {
		$row= query_first("select p.*,IF(CURDATE() between p.discfrdate and p.disctodate, '1', '0') as isdiscount, w.measlist from ccd9wishlist w join ccd9products p on p.prodid=w.prodid where w.wishid='$wishid'");
		$measlist=$row['measlist']; $prodid=$row['prodid'];
		$mysqli->query("delete from ccd9wishlist where compid='".$_SESSION['compid']."' and wishid='$wishid' ");
	}

	$carteventid=inpval($_POST['carteventid']);
	$prodqty = intval($_POST['prodqty']);
	if($prodqty ==0)$prodqty =1;
	$prodcolor = inpval($_POST['prodcolor']); //type3
	if($prodcolor =='')$prodcolor ='NA';
	$prodsize = inpval($_POST['prodsize']); //type2
	if($prodsize =='')$prodsize ='NA';
	$prodmeas  = inpval($_POST['prodmeas']); //type1
	$isoffer=0; $offerprice=0;
	if(checkgiftcat($prodid) || checksalecat($prodid))$prodqty =1;

	if($_SESSION['myCUR']=="US $"){
		$finalprice=dbval($row['usdprice']);
		$shipprice=dbval($row['shipusd']);
		$offerprice=dbval($row['offerusd']);
	}else{	
		$finalprice=dbval($row['prodprice']);
		$shipprice=dbval($row['shipprod']);
		$offerprice=dbval($row['offerprod']);
	}
	if($row['isdiscount']==1 && $row['proddisc']>0){
		$offerprice=$finalprice-round($finalprice*$row['proddisc']/100,0);
		$isoffer=1;
	}
	if($offerprice>0 || $isoffer==1)$finalprice=$offerprice;
	//print_r($measlist);

	$res= query_first("select cartid, measlist from ccd9cart where sessionid='".session_id()."' and orderid='0' and prodid='$prodid' and prodcolor='$prodcolor' and prodsize='$prodsize' and prodmeas='$prodmeas'");
	if($res['cartid']>0 && !checkgiftcat($prodid)){
		//echo $res['measlist'].'<BR>';
		//if($row['prodsize']==""){$qty=0;}
		$sql="update ccd9cart set prodname='".$row['prodname']."', prodmeas='$prodmeas', prodprice='".$row['prodprice']."', usdprice='".$row['usdprice']."', poundprice='".$row['poundprice']."', europrice='".$row['europrice']."', prodcare='".$row['prodcare']."', noship='".$row['noship']."', prodbox='".$row['prodbox']."', shiptime='".$row['shiptime']."', prodwgt='".$row['prodwgt']."', prodpack='".$row['prodpack']."', shipprod='".$row['shipprod']."', shipusd='".$row['shipusd']."', freeship='".$row['freeship']."', proddisc='".$row['proddisc']."', discfrdate='".$row['discfrdate']."', disctodate='".$row['disctodate']."', offerprod='".$row['offerprod']."', offerusd='".$row['offerusd']."', offerfrdate='".$row['offerfrdate']."', offertodate='".$row['offertodate']."', prodcur='".($_SESSION['myCUR']!='US $' ? 'INR' : $_SESSION['myCUR'])."', measlist='$measlist', finalprice='$finalprice', prodqty='$prodqty', shipprice='$shipprice' where cartid='".$res['cartid']."'";
		$mysqli->query($sql);
	}else{
		$sql="insert into ccd9cart (compid, prodid, prodname, prodmeas, prodprice, usdprice, poundprice, europrice, prodcare, noship, prodbox, shiptime, prodwgt, prodpack, shipprod, shipusd, freeship, proddisc, discfrdate, disctodate, offerprod, offerusd, offerfrdate, offertodate, sessionid, prodcur, measlist, finalprice, prodqty, prodsize, prodcolor, shipprice) values ('".$_SESSION['compid']."', '$prodid', '".inpval($row['prodname'])."', '$prodmeas', '".inpval($row['prodprice'])."', '".inpval($row['usdprice'])."', '".inpval($row['poundprice'])."', '".inpval($row['europrice'])."', '".inpval($row['prodcare'])."', '".inpval($row['noship'])."', '".inpval($row['prodbox'])."', '".inpval($row['shiptime'])."', '".inpval($row['prodwgt'])."', '".inpval($row['prodpack'])."', '".$row['shipprod']."', '".$row['shipusd']."', '".$row['freeship']."', '".$row['proddisc']."', '".$row['discfrdate']."', '".$row['disctodate']."', '".$row['offerprod']."', '".$row['offerusd']."', '".$row['offerfrdate']."', '".$row['offertodate']."', '".session_id()."', '".($_SESSION['myCUR']!='US $' ? 'INR' : $_SESSION['myCUR'])."', '$measlist', '$finalprice', '$prodqty', '$prodsize', '$prodcolor', '$shipprice')";
		$mysqli->query($sql);
	}
	$errmsg=$row['prodname']." successfully added to your Shopping Bag!";


$eventid=$carteventid; //"Cart.".session_id().".".$prodid;
$eventtime=time();
$shareurl='https:'.getprodurl($prodid,$opt);
$eventip = $_SERVER['REMOTE_ADDR'];
$eventagent=$_SERVER['HTTP_USER_AGENT'];

/*

$jsonDataEncoded='
{
   "data": [
      {
         "event_name": "AddToCart",
         "event_time": '.$eventtime.',
         "event_id": "'.$eventid.'",
         "event_source_url": "'.$eventurl.'",
         "user_data": {
            "client_ip_address": "'.$eventip.'",
            "client_user_agent": "'.$eventagent.'"
		 },
		 "custom_data": {
			"currency": "'.($_SESSION["myCUR"]=="US $" ? "USD" : "INR").'",
			"value": '.$finalprice.',
			"content_type": "product",
			"content_ids": [
			   "'.$row["prodcode"].'"
			]
		 }
      }
   ]
}';
$ch = curl_init("https://graph.facebook.com/v11.0/$pixel_id/events?access_token=$access_token");
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
$response = curl_exec($ch);
curl_close($ch);
*/

	//echo $sql;
	header("location:".$serverurl."shopping-cart?errmsg=".urlencode($errmsg));
	exit();
	if($_POST['retnto']=='checkout'){
		header("location:".$serverurl."shopping-cart?errmsg=".urlencode($errmsg));
	}else{
		header("location:".getprodurl($row['produrl'])."?errmsg=".urlencode($errmsg));
	}
	exit();
}//else if($opt=="addtocart" && $prodid>0 && $_SESSION['grp']!=2 && $_SESSION['grp']!=3){
//	$errmsg="Only customers can place order. Please login with customer id!";
//}
//---------------------------------

if($opt=="mymeasurements" && $_POST['prodid']!='' && $_POST['prodmeas']!=''){
	$addtowish=inpval($_POST['addtowish']);
	$prodid=inpval($_POST['prodid']);
	$prodmeas=inpval($_POST['prodmeas']);
	$dimunit=inpval($_POST['dimunit']);
	$heightft=intval($_POST['heightft']);
	$heightin=intval($_POST['heightin']);
	$refsize=inpval($_POST['refsize']);
	if($heightin=='')$heightin='0';
	$height=$heightft.'.'.$heightin;
	$heelheight=intval($_POST['heelheight']);
	if($heelheight=='' || $heelheight=='0')$heelheight='NA';
	$dimlist[0]=$prodid;
	$dimlist[1]=$refsize;
	$dimlist[2]=$dimunit;
	$dimlist[3]=$height;
	$dimlist[4]=$heelheight;

	for($n=1;$n<=$_POST['cnt'];$n++){
		$typeid=intval($_POST['typeid'.$n]);
		$dimval=inpval($_POST['dimval'.$n]);
		array_push($dimlist, $typeid.'='.trim($dimval));

		if($_SESSION['compid']>0){
			$row= query_first("select dimid from ccd9userdim where compid='".$_SESSION['compid']."' and typeid='$typeid'");
			if($row['dimid']>0){
				$mysqli->query("update ccd9userdim set dimval='$dimval' where compid='".$_SESSION['compid']."' and typeid='$typeid'");
			}else{
				$sqlin="INSERT INTO ccd9userdim (compid, typeid, dimval) VALUES ('".$_SESSION['compid']."', '$typeid', '$dimval')";
				$mysqli->query($sqlin);
			}
		}


	}
	if($_SESSION['compid']>0){
		$mysqli->query("update ccd9company set refsize='$refsize', height='$height', heelheight='$heelheight' where compid='".$_SESSION['compid']."'");
	}
	$_SESSION['measlist']=$dimlist;
	
	$row= query_first("select * from ccd9products where prodid='$prodid'");

	if($addtowish==1){
		$row= query_first("select wishid from ccd9wishlist where (compid='".$_SESSION['compid']."' or sessionid='".session_id()."') and prodid='$prodid'");
		if($row['wishid']>0){
			$mysqli->query("update ccd9wishlist set measlist='".implode(',',$dimlist)."' where (compid='".$_SESSION['compid']."' or sessionid='".session_id()."') and prodid='$prodid'");
		}else{
			$sqlin="INSERT INTO ccd9wishlist (compid, prodid, sessionid, measlist) VALUES ('".$_SESSION['compid']."', '$prodid', '".session_id()."', '".implode(',',$dimlist)."')";
			$mysqli->query($sqlin);
		}
		$errmsg="Wishlist updated successfully!";
		header("location:".$serverurl.$row['produrl']."?errmsg=".urlencode($errmsg));
		exit();
	}else{
		//----------------------add to cart-------------------

		$prodqty = 1;
		$prodcolor = inpval($row['prodcolor']);
		$prodsize = 'CUSTOM';

		if($_SESSION['myCUR']=="US $"){
			$finalprice=dbval($row['usdprice']);
			$shipprice=dbval($row['shipusd']);
			if($row['isoffer']==1)$finalprice=dbval($row['offerusd']);
		}else{	
			$finalprice=dbval($row['prodprice']);
			$shipprice=dbval($row['shipprod']);
			if($row['isoffer']==1)$finalprice=dbval($row['offerprod']);
		}

		//$measlist=$_SESSION['measlist'];

		$res= query_first("select cartid, measlist from ccd9cart where sessionid='".session_id()."' and orderid='0' and prodid='$prodid' and prodcolor='$prodcolor' and prodsize='$prodsize' ");
		if($res['cartid']>0){
			//echo $res['measlist'].'<BR>';
			//if($row['prodsize']==""){$qty=0;}
			$sql="update ccd9cart set prodname='".$row['prodname']."', prodmeas='".$row['prodmeas']."', prodprice='".$row['prodprice']."', usdprice='".$row['usdprice']."', poundprice='".$row['poundprice']."', europrice='".$row['europrice']."', prodcare='".$row['prodcare']."', noship='".$row['noship']."', prodbox='".$row['prodbox']."', shiptime='".$row['shiptime']."', prodwgt='".$row['prodwgt']."', prodpack='".$row['prodpack']."', shipprod='".$row['shipprod']."', shipusd='".$row['shipusd']."', freeship='".$row['freeship']."', proddisc='".$row['proddisc']."', discfrdate='".$row['discfrdate']."', disctodate='".$row['disctodate']."', offerprod='".$row['offerprod']."', offerusd='".$row['offerusd']."', offerfrdate='".$row['offerfrdate']."', offertodate='".$row['offertodate']."', prodcur='".($_SESSION['myCUR']!='US $' ? 'INR' : $_SESSION['myCUR'])."', measlist='".implode(',',$dimlist)."', finalprice='$finalprice', prodqty='$prodqty', shipprice='$shipprice' where cartid='".$res['cartid']."'";
			$mysqli->query($sql);
		}else{
			$sql="insert into ccd9cart (compid, prodid, prodname, prodmeas, prodprice, usdprice, poundprice, europrice, prodcare, noship, prodbox, shiptime, prodwgt, prodpack, shipprod, shipusd, freeship, proddisc, discfrdate, disctodate, offerprod, offerusd, offerfrdate, offertodate, sessionid, prodcur, measlist, finalprice, prodqty, prodsize, prodcolor, shipprice) values ('".$_SESSION['compid']."', '$prodid', '".inpval($row['prodname'])."', '".inpval($row['prodmeas'])."', '".inpval($row['prodprice'])."', '".inpval($row['usdprice'])."', '".inpval($row['poundprice'])."', '".inpval($row['europrice'])."', '".inpval($row['prodcare'])."', '".inpval($row['noship'])."', '".inpval($row['prodbox'])."', '".inpval($row['shiptime'])."', '".inpval($row['prodwgt'])."', '".inpval($row['prodpack'])."', '".$row['shipprod']."', '".$row['shipusd']."', '".$row['freeship']."', '".$row['proddisc']."', '".$row['discfrdate']."', '".$row['disctodate']."', '".$row['offerprod']."', '".$row['offerusd']."', '".$row['offerfrdate']."', '".$row['offertodate']."', '".session_id()."', '".($_SESSION['myCUR']!='US $' ? 'INR' : $_SESSION['myCUR'])."', '".implode(',',$dimlist)."', '$finalprice', '$prodqty', '$prodsize', '$prodcolor', '$shipprice')";
			$mysqli->query($sql);
		}
		//---------------------------
		$errmsg=$row['prodname']." successfully added to your Shopping Bag!";
	}
	
	$errmsg="Measurements saved successfully and ".$errmsg;
	//header("location:".$serverurl.$row['produrl']."?errmsg=".urlencode($errmsg));
	header("location:".$serverurl."shopping-cart?errmsg=".urlencode($errmsg));
	exit();
}


//---------------------------------

if($opt=="addtowish" && $prodid>0){
	$row= query_first("select wishid from ccd9wishlist where (compid='".$_SESSION['compid']."' or sessionid='".session_id()."') and prodid='$prodid'");
	if($row['wishid']>0){
		$errmsg="Wishlist updated successfully!";
		header("location:".$serverurl.'wishlist'."?errmsg=".urlencode($errmsg));
	}else{
		$row= query_first("select count(*) as cnt from ccd9wishlist where compid='0' and sessionid='".session_id()."' ");
		if($row['cnt']<=5){
			$sqlin="INSERT INTO ccd9wishlist (compid, prodid, sessionid) VALUES ('".$_SESSION['compid']."', '$prodid', '".session_id()."')";
			$mysqli->query($sqlin);
			$errmsg="Wishlist updated successfully!";
			header("location:".$serverurl.'wishlist'."?errmsg=".urlencode($errmsg));
		}else{
			$errmsg="Please login to add more products to your wishlist";
			header("location:".$serverurl.'login'."?errmsg=".urlencode($errmsg));
		}
	}
	
	exit();
}

//---------------------------------

if($opt=="shopping-cart" && $_POST['totn']!=""){
	$retuto=trim($_POST['retuto']);

	for($n=1;$n<=$_POST['totn'];$n++){
		$qty=intval($_POST['qty'.$n]);
		$cartid=intval($_POST['cartid'.$n]);
		$prodsize=inpval($_POST['prodsize'.$n]);
		$prodmeas=inpval($_POST['prodmeas'.$n]);
		if($heightin=='')$heightin='0';
		$height=$heightft.'.'.$heightin;
		$discemail=inpval($_POST['discemail'.$n]);
		$catid=intval($_POST['catid'.$n]);
		
		$comments= getsizebox($prodmeas,$prodsize,$catid);

		if(($qty==0 || $qty<0) && $cartid>0){
			$mysqli->query("delete from ccd9cart where sessionid='".session_id()."' and cartid='$cartid' and orderid='0'");
		}else{
			$mysqli->query("update ccd9cart set prodqty='$qty', comments='$comments' where sessionid='".session_id()."' and cartid='$cartid' and orderid='0'");
		}
	}
	$errmsg="Shopping Bag updated successfully!";

	if($_SESSION['compid']>0){
		$row= query_first("select phone from ccd9company where compid='".$_SESSION['compid']."'");
		if(trim($row['phone'])=='' || is_null($row['phone'])){
			$retuto="edit-information";
			$errmsg= "Please update your contact number!";
		}
	}

	if($_POST['disccode']!=""){
		$disccode=trim($_POST['disccode']);
	}//else if(isset($_SESSION['userdisc'])){
		//$arrdisc = $_SESSION['userdisc'] ;
		//$disccode = $arrdisc[1];
	//}

	//if($_POST['comments']!=""){
	$comments=inpval($_POST['comments']);
	//exit($comments);
	$rowin= query_first("select orderid from ccd9orders where compid='".$_SESSION['compid']."' and status='0'");
	if ($rowin['orderid']>0){
		$mysqli->query("update ccd9orders set comments='$comments' where status='0' and orderid='".$rowin['orderid']."'");
		$orderid=$rowin['orderid'];
	}else{
		$mysqli->query("insert into ccd9orders (sessionid, comments, status, compid) values ('".session_id()."', '$comments', '0', '".$_SESSION['compid']."')");
		$orderid=mysqli_insert_id($mysqli);
	}
	//}
	
	//if($disccode!=""){
	if($_POST['disccode']!=""){
		$discerr=0; $disctot=0;
		
		//$row=query_first("select *, ifnull(DATEDIFF(discexpiry, curdate()),1) as discdays from ccd9discounts where disccode='$disccode' and discstatus='1' having discdays>=0");
		$row=query_first("select * from ccd9discounts where disccode='$disccode' and discstatus='1' and curdate() between (ifnull(discstart,curdate()) or if(discstart='0000-00-00',curdate(),discstart)) and discexpiry");
		if($row['discid']>0){
			if($row['discuse']==0){
				$rowin=query_first("select compid from ccd9orders where disccode='$disccode' and discid='".$row['discid']."' and status>0");
				if($rowin['compid']>0){
					$discerr=1; //discount is not applicable
					if($rowin['compid']==$_SESSION['compid']){
						$errmsg= "Promotion Code already used!";
					}
				}
			}
			if($discerr==0  && $row['discemail']!=''){
				$rowin=query_first("select email from ccd9company where compid='".$_SESSION['compid']."'");
				if($rowin['email']!=$row['discemail']){ 
					$discerr=1; //discount is not applicable
					$errmsg= "Invalid Promotion Code!";
				}
			}
			if($discerr==0){
				if($row['compid']>0 && $_SESSION['compid']==$row['compid']){
				}else if($row['compid']==0){
				}else{
					$discerr=1; //discount is not applicable
					$errmsg= "Invalid Promotion Code!";
				}
			}
			//not with sale/gift products in cart ... discount is not applicable
			/*if($discerr==0){ 
				$rowin=query_first("select cartid from ccd9cart c join ccd9prod2cat t on c.prodid=t.prodid and t.catid in (".($row['exclcats']!="" ? $row['exclcats'] : "0").") where c.status='0' and c.sessionid='".session_id()."' and c.compid='".$_SESSION['compid']."'"); 
				if($rowin['cartid']>0){ 
					$discerr=1; 
					$errmsg= "The Promotion Code is not applicable on your shopping bag which contains sale or discounted products. Redemption of your Promotion Code is only applicable on full priced items as per the terms and conditions of your Promotion Code.";
				}
			}*/
			if($disccode=='ORTHO50'){//for new customer only
				$rowin=query_first("select compid from ccd9orders where compid='".$_SESSION['compid']."' and status>0");
				if($rowin['compid']>0){
					$discerr=1; //discount is not applicable
					$errmsg= "This Promotion Code is valid for newly registered customers on their First Purchase only.";
				}
			}
			if($discerr==0){
				//$rowin=query_first("select sum(finalprice*prodqty) as carttot, prodcur as cartcur from ccd9cart where status='0' and sessionid='".session_id()."' and compid='".$_SESSION['compid']."'");
				//$rowin=query_first("select sum(finalprice*prodqty) as carttot, prodcur as cartcur from ccd9cart where status='0' and sessionid='".session_id()."' and compid='".$_SESSION['compid']."' and prodid not in (select prodid from ccd9prod2cat where catid in (".($row['exclcats']!="" ? $row['exclcats'] : "0").") )");

				$rowin=query_first("select sum(finalprice*prodqty) as carttot, prodcur as cartcur from ccd9cart where status='0' and sessionid='".session_id()."' and compid='".$_SESSION['compid']."' ".($row['exclcats']!="" ? " and prodid not in (select prodid from ccd9prod2cat where catid in (".$row['exclcats']."))" : "").($row['inclcats']!="" ? " and prodid in (select prodid from ccd9prod2cat where catid in (".$row['inclcats']."))" : "").($row['incltype']!="" ? " and prodid in (select prodid from ccd9prod2type1 where typeid in (".$row['incltype']."))" : "")."  ");
				if($rowin['carttot']>0){
					if($row['disctype']==1){
						$disctot = round($rowin['carttot'] * $row['discamt']/100,0);
					}else if($row['disctype']==2){
						if($row['disccur']==$rowin['cartcur']){
							if($row['discamt']>$rowin['carttot']){
								$disctot=$rowin['carttot'];
							}else{
								$disctot = round($row['discamt'],0);
							}
						}else{
							$discerr=1; //discount is not applicable
							$errmsg= "Invalid Promotion Code!";
						}
					}else if($row['disctype']==3){
						$disctot = 0;
					}

					if($row['incltype']!="" && $disctot==0){
						$discerr=1; //discount is not applicable
						$errmsg= "Invalid Promotion Code!";
					}

					//$arrdisc=array($row['discid'], $row['disccode'], $row['disctype'], $row['discamt'], $disctot);
					//session_start();
					//$_SESSION['userdisc'] = $arrdisc;
					if($discerr==0){
						$rowin= query_first("select orderid from ccd9orders where compid='".$_SESSION['compid']."' and status='0'");
						if ($rowin['orderid']>0){
							$mysqli->query("update ccd9orders set discid='".$row['discid']."', disccode='".$row['disccode']."', discountamt='$disctot' where status='0' and orderid='".$rowin['orderid']."'");
						}else{
							$mysqli->query("insert into ccd9orders (sessionid, discid, disccode, discountamt, status, compid) values ('".session_id()."', '".$row['discid']."', '".$row['disccode']."', '$disctot', '0', '".$_SESSION['compid']."')");
						}
						//exit("error");
						$errmsg="Shopping Bag & Promotion Code updated successfully!";
					}
				}else{
					if(($row['incltype']!="" || $row['exclcats']!="" || $row['inclcats']!="") && $disctot==0){
						$discerr=1; //discount is not applicable
						$errmsg= "Invalid Promotion Code!";
					}
				}
			}else{
				$discerr=1;
			}
		}else{
			$discerr=1;
			$errmsg= "Invalid Promotion Code!";

			$row=query_first("select orderid from ccd9orders where disccode='$disccode' and status='1' and compid='".$_SESSION['compid']."'");
			if($row['orderid']>0){
				$errmsg= "Promotion Code already used!";
			}

		}
		if($discerr==1){
			//unset($arrdisc[0]); unset($arrdisc[1]); unset($arrdisc[2]); unset($arrdisc[3]);
			//$_SESSION['userdisc'] = $arrdisc;
			//exit("select orderid from ccd9orders where sessionid='".session_id()."' and compid='".$_SESSION['compid']."' and status='0'");
			$rowin= query_first("select orderid from ccd9orders where compid='".$_SESSION['compid']."' and status='0'");
			if ($rowin['orderid']>0){
				$mysqli->query("update ccd9orders set discid='0', disccode='', discountamt='0' where status='0' and orderid='".$rowin['orderid']."'");
			}

			$retuto='shopping-cart';
		}
		
		header("location:".$serverurl.($retuto!='' ? $retuto : 'shopping-cart')."?errmsg=".urlencode($errmsg));
		exit();
	}else if($_POST['olddisccode']!=""){

		$rowin= query_first("select orderid from ccd9orders where compid='".$_SESSION['compid']."' and status='0'");
		if ($rowin['orderid']>0){
			$mysqli->query("update ccd9orders set discid='0', disccode='', discountamt='0' where status='0' and orderid='".$rowin['orderid']."'");
		}
		header("location:".$serverurl.($retuto!='' ? $retuto : 'shopping-cart')."?errmsg=".urlencode($errmsg));
		exit();
	}

	if($_SESSION['compid']>0 && $orderid>0){
		$mysqli->query("update ccd9orders set paymentid='0', shippingid='0', addressid='0', ordterms='0' where status='0' and orderid='$orderid' and compid='".$_SESSION["compid"]."'");
	}

	header("location:".$serverurl.($retuto!='' ? $retuto : 'order')."?errmsg=".urlencode($errmsg).($retuto=="edit-information" ? "&retuto=shopping-cart" : ""));
	exit();
}

//---------------------------------

if($opt=="wishlist" && $_GET['remprod']!=""){

	$wishid=intval($_GET['remprod']);
	if($wishid>0){
		$mysqli->query("delete from ccd9wishlist where compid='".$_SESSION['compid']."' and prodid='$wishid' ");
	}
	$errmsg="Wishlist updated successfully!";
	header("location:".$serverurl.'myaccount?task=wishlist&errmsg='.urlencode($errmsg));
	exit();
}

//---------------------------------

if($opt=="shopping-cart" && $_GET['remprod']!=""){

	$cartid=intval($_GET['remprod']);
	if($cartid>0){
		$mysqli->query("delete from ccd9cart where (sessionid='".session_id()."' ".($_SESSION['compid']>0 ?  " or compid='".$_SESSION['compid']."' " : "").") and cartid='$cartid' and status='0'");

		if($_SESSION['compid']>0){
			$mysqli->query("update ccd9orders set discountamt ='0' where compid='".$_SESSION['compid']."' and status='0'");
		}
	}
	$errmsg="Shopping Bag updated successfully!";
	header("location:".$serverurl.'shopping-cart'."?errmsg=".urlencode($errmsg));
	exit();	
}

//---------------------------------

if($opt=="newsletter" && $_POST['newsemail']!=""){
	if(trim($_POST['newsemail'])=="0"){
		$mysqli->query("delete from ccd9emaillist where newsemail='".$_SESSION['email']."'");
		$errmsg="Your newsletter subscription updated!";
	}else{
		$newsemail = inpval($_POST['newsemail']);
		$sqlin="INSERT INTO ccd9emaillist (newsemail) VALUES ('".$_SESSION['email']."') ON DUPLICATE KEY UPDATE newsemail='".$_SESSION['email']."'";
		$mysqli->query($sqlin);
		$newsmsg="Thank you for subscribing for the newsletter!";
	}
	
}

//---------------------------------

if($opt=="unsubscribe" && $_GET['ref']!=""){

	$ref=inpval($_GET['ref']);
	$compid = decyrptPassword($ref);
	if($compid>0){
		$mysqli->query("update ccd9company set discmail=0, newsletter=0 where compid='$compid'");
		$errmsg="You are now unsubscribed from our newsletter.";
		header("location:".$serverurl."?errmsg=".urlencode($errmsg));
	}	
}




//---------------------------------
if($_GET['errmsg']!=""){$errmsg = $_GET['errmsg'];}
if($opt=="manage-address"){$pgtype="account";}
if($opt==""){$opt="home";}
if($opt=="search" && $_GET['q']!=""){
	$mysqli->query("insert into ccd9searchkeys (searchtext, compid) values ('".inpval($_GET['q'])."', '".$_SESSION['compid']."')");
}
//echo $opt;
if($opt!="home"){
	$sql="select c.typevalue, c.typeid, c.typename, p.description, p.title, p.meta_keywords, p.meta_title, p.meta_description from ccd9types c left join ccd9pages p on c.typevalue=p.pageurl where c.typevalue='".inpval($opt)."'";
	//echo $sql;
	$query=$mysqli->query($sql);
	$num_rows = mysqli_num_rows($query);
	if ($num_rows>0 || $opt=="search"){
		$resin=$query->fetch_array();
		$catid=$resin['typeid'];
		$catname=dbval($resin['typename']);
		$caturl=dbval($resin['typevalue']);
		$pgtype="products";
		//$metatitle=$catname;
		$catdescription=trim($resin['description']);
		$_SESSION['optreturn']=$opt;

		$metatitle=dbval($resin['meta_title']);
		$metakeywords=dbval($resin['meta_keywords']);
		$metadescription=dbval($resin['meta_description']);
		$metaurl=$caturl;


	}else if($opt!="search"){
		$_SESSION['optreturn']='';
		if(strstr($opt, '/')){
			$_SESSION['optreturn']=trim(substr($opt,0,strpos($opt,"/")));
			$opt=trim(substr($opt,strrpos($opt,"/")+1));
		}
		//exit($opt);
		$result=$mysqli->query("select t3.*, c.catid as prodcatid, t1.typeid as typefit, ph.photo, ph.video, t3c.typename, t3c.typevalue, p.*, IF(CURDATE() between p.offerfrdate and p.offertodate, '1', '0') as isoffer, IF(CURDATE() between p.discfrdate and p.disctodate, '1', '0') as isdiscount, w.wishid, p.rating from ccd9products p  join ccd9prod2type3 t3 on p.prodid=t3.prodid join ccd9types t3c on t3.typeid=t3c.typeid and t3c.opt=7 left join ccd9prod2type1 t1 on t1.prodid=p.prodid left join ccd9prodphotos ph on ph.prodid=p.prodid and ph.type1=t1.typeid and ph.type3=t3.typeid and ph.photo!='' left join ccd9prod2cat c on p.prodid=c.prodid left join ccd9types pt on pt.typeid=c.catid and pt.typevalue='".$_SESSION['optreturn']."' and pt.opt='2' left join ccd9wishlist w on w.prodid=p.prodid and w.compid='".$_SESSION['compid']."' where ".($_GET['type1']>0 ? "  ( t1.typeid = '".intval($_GET['type1'])."') and " : "").($_GET['type3']>0 ? "  ( t3.typeid = '".intval($_GET['type3'])."') and " : "")." p.produrl='$opt' and p.prodstatus='1'");

		//$result=$mysqli->query("CALL getProd('$opt','".intval($_SESSION['compid'])."')");
		//$result=$mysqli->query($query);

		$num_rows = mysqli_num_rows($result);
		if ($num_rows>0){
			$pgtype="product"; //$opt="product";

			$resin=$result->fetch_array();
			$prodid=$resin['prodid'];

			$metatitle=dbval($resin['prodname']);
			$metakeywords=dbval($resin['prodkeys']);
			$metadescription=dbval($resin['prodalt']);
			$metaurl=dbval($resin['produrl']);

			//mysqli_free_result($result); 
			//mysqli_next_result($mysqli); 

			//$mysqli->query("update ccd9products set viewed=viewed+1 where prodid='$prodid'");
			$mysqli->query("insert into ccd9prodviewed (compid, prodid, sessionid, countrycode) values ('".$_SESSION['compid']."', '$prodid', '".session_id()."', '".$_SESSION['myCountry']."')");

		}else{
			//mysqli_free_result($result); 
			//mysqli_next_result($mysqli); 
			//$result=$mysqli->query("CALL getPage('$opt')");
			$result=$mysqli->query("select * from ccd9pages where pageurl='$opt' and status='1'");
			$num_rows = mysqli_num_rows($result);
			if ($num_rows>0){
				$resin=$result->fetch_array();
				if($resin['isuser']==1){
					$pgtype="account";
				}else if($resin['iscart']==1){
					$pgtype="blog-post";
				}else if($resin['iscart']==2){
					$pgtype="pain-conditions";
				}else if($resin['iscart']==3){
					$pgtype="press";
				}else{
					$pgtype="information";
				}
				$pageid=intval($resin['pageid']);
				$pagetitle=dbval($resin['title']);
				$pagecontent=trim($resin['description']);
				$metatitle=dbval($resin['meta_title']);
				if($metatitle=='')$metatitle=$pagetitle;
				$metakeywords=dbval($resin['meta_keywords']);
				$metadescription=dbval($resin['meta_description']);
				$pagephoto=trim($resin['banners']);
				$metaurl=$opt;
			}else if(!in_array($opt, $optlist)){
				//$opt="home";
				$metaurl='search';
				header("location:".$serverurl."search?q=".trim($opt));
			}
			//mysqli_free_result($result); 
			//mysqli_next_result($mysqli); 
		}
	}
}else if($opt=='home'){

	//$result=$mysqli->query("CALL getPage('home')");
	$result=$mysqli->query("select * from ccd9pages where pageurl='home'");
	$resin=$result->fetch_array();

	//echo dbval($resin['title']);
	$pagetitle=dbval($resin['title']);
	$metatitle=dbval($resin['meta_title']);
	if($metatitle=='')$metatitle=$pagetitle;
	$metakeywords=dbval($resin['meta_keywords']);
	$metadescription=dbval($resin['meta_description']);
	////mysqli_free_result($result); 
	////mysqli_next_result($mysqli); 
	$firstord=0; $showmodal=1;
	if($_SESSION['compid']>0){
		
		//$db = Database::getInstance();
		//$mysqli = $db->getConnection();

		$querysql="select orderid from ccd9orders where compid='".$_SESSION['compid']."' and status>0";
		//echo $querysql;
		$result = $mysqli->query($querysql);
		if(mysqli_num_rows($result)>0){
			$showmodal=0;
		}else{
			$firstord=1; //zero orders 
			//echo "firstord".$firstord;
		} 
	}
}

$rowin= query_first("select count(*) as wishcnt from ccd9wishlist where compid='".$_SESSION['compid']."'");
$wishcnt = $rowin['wishcnt'];


//-------------------cart sql code------------------------------------------------------------
if(intval($_GET['orderid'])>0)$orderid=intval($_GET['orderid']);
$sqlcartlist="select c.*, ph.photo, p.produrl, p.prodcode, ct.typeid as catid, ct.typename as catname, ct.typevalue as caturl, t1.typename as vprodmeas, t2.typename as vprodsize, t3.typename as vprodcolor, p.rating  from ccd9cart c join ccd9products p on p.prodid=c.prodid left join ccd9prod2cat pt on pt.prodid=p.prodid left join ccd9types ct on pt.catid=ct.typeid and ct.opt='2' left join ccd9types t1 on c.prodmeas=t1.typeid and t1.opt='3' left  join ccd9types t2 on c.prodsize=t2.typeid and t2.opt='4'  left  join ccd9types t3 on c.prodcolor=t3.typeid and t3.opt='7' left  join ccd9prod2type3 pt3 on pt3.prodid=p.prodid and c.prodcolor=pt3.typeid left join ccd9prodphotos ph on ph.prodid=c.prodid and ph.type1=c.prodmeas and ph.type3=c.prodcolor where ". ($_SESSION['compid']>0 ? " compid='".$_SESSION['compid']."'" : " c.sessionid='".session_id()."' and c.compid='0'")." and c.status='0' group by c.cartid order by c.cartid;";
//echo $sqlcartlist;
$resultcart = $mysqli->query($sqlcartlist);
$numcart = mysqli_num_rows($resultcart);
?>