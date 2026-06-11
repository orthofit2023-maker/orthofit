<?php
//https://vimm.com/tracking-constant-contact-email-google-analytics/
//https://www.w3schools.com/php/php_ref_mysqli.asp
ini_set("log_errors", 0);
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_WARNING);
include("dbconfig.php");
//require("phpmailer/class.phpmailer.php");
$acccodes = array();

$db = Database::getInstance();
$mysqli = $db->getConnection();

$filename = trim(substr($_SERVER['SCRIPT_NAME'],strrpos($_SERVER['SCRIPT_NAME'],"/")+1));



function num_rows($result){
	return mysqli_num_rows($result);
}

function query_first($sql){
	$db = Database::getInstance();
	$mysqli = $db->getConnection();
	
	//$result = $mysqli->query($sql);
	//return $result->fetch_array();

	$result = $mysqli->query($sql);
	$numrow = mysqli_num_rows($result);
	if($numrow>0){
		return $result->fetch_array();
	}else{
		return array();
	}
}

function geturl($title, $id){
	
	$db = Database::getInstance();
	$mysqli = $db->getConnection();

	$produrl=strtolower(preg_replace('/[^a-z\d]+/i', '-', $title));

	$querysql="select prodid from ccd9products where produrl='$produrl' and prodcode!='$id'";
	$result = $mysqli->query($querysql);
	if($rescon = $result->fetch_array()){
		$produrl=$produrl.'-'.trim(substr($id,5,4));
	}
	$produrl=str_replace('--','-',$produrl);
	return $produrl;
}

function getpageurl($title, $id=0){
	
	$db = Database::getInstance();
	$mysqli = $db->getConnection();

	$pageurl=strtolower(preg_replace('/[^a-z\d]+/i', '-', $title));

	$querysql="select pageid from ccd9pages where pageurl='$pageurl' and pageid!='$id' and iscart>0";
	$result = $mysqli->query($querysql);
	if($rescon = $result->fetch_array()){
		$pageurl=$pageurl.'-'.trim($id);
	}
	$pageurl=str_replace('--','-',$pageurl);
	return $pageurl;
}


function checkuserref(){
	if(strpos($_SERVER['HTTP_REFERER'],$GLOBALS['serverdomain'])){
		return true;
	}else{
		return false;
	}
}

function decyrptPassword($pwd){
	$epwd = openssl_decrypt($pwd, "AES-128-ECB", SECRETKEY);
	return $epwd;
}

function encyrptPassword($pwd){
	$epwd = openssl_encrypt($pwd, "AES-128-ECB", SECRETKEY);

	/* $salt = CRYPT_MD5 ;
	 srand(time());
	 for( $i = 3; $i < 11; $i++ )
	 {
		 $n = 32 + (rand() % 94);
		 $salt .= chr($n);
	 }
	 $epwd = crypt($pwd, $salt);*/
	 return $epwd;
}

function checkuseraccess($userid=0, $fileid=0, $accesstype=0){
	$db = Database::getInstance();
	$mysqli = $db->getConnection();

	if($userid==0)$userid=$_SESSION['loginid'];
	if($fileid==0){
		$myfile = trim(substr($_SERVER['SCRIPT_NAME'],strrpos($_SERVER['SCRIPT_NAME'],"/")+1));
		$fileid=getfileid($myfile);
	}
	$chksql="select accessid from ccd9files2user u join ccd9accessfiles a on u.fileid=a.fileid where u.userid='$userid' and u.fileid='$fileid'  ";
	if($accesstype>0){
		$chksql=$chksql." and u.accesstype='$accesstype'";
	}
	//exit($chksql);
	$result = $mysqli->query($chksql);
	if($rowaccess = $result->fetch_array()){
		return true;
	}else{
		return false;
	}
}


function getcurmenu($id){
	$retval=$retval.'<option value="">Select Currency</option>';
	$retval=$retval.'<option value="US $"'.($id=="US $" ? " selected" : "").'>US $</option>';
	$retval=$retval.'<option value="INR"'.($id=="INR" ? " selected" : "").'>INR</option>';
	return $retval;
}


function getfileid($filename){
	return getfieldvalue($filename, 'fileid', 'filename', 'ccd9accessfiles');
}


function getfieldvalue($id, $field, $idfield, $dbname, $xtra=""){
	$db = Database::getInstance();
	$mysqli = $db->getConnection();

	$querysql="select $field from $dbname where $idfield='$id' $xtra";
	$result = $mysqli->query($querysql);
	if($rescon = $result->fetch_array()){
		return $rescon[0];
	}
}

function getordno($id, $dt=''){
	$db = Database::getInstance();
	$mysqli = $db->getConnection();
	if($dt==''){
		$querysql="select date_format(orddate, '%Y') as dt from ccd9orders where orderid='$id'";
		$result = $mysqli->query($querysql);
		if($rescon = $result->fetch_array()){
			if($rescon[0]!='0000'){
				return "OH".substr($rescon[0],0,4)."/".$id;
			}else{
				return "OH".date("Y")."/".$id;
			}
		}
	}else{
		return "OH".substr($dt,0,4)."/".$id;
	}
}

function storearray($str,$id,$dbname,$opt=0){

	$db = Database::getInstance();
	$mysqli = $db->getConnection();
	$mysqli->query("delete from $dbname where prodid='$id'");
	
	//echo '<BR>dbname: '.$dbname;
	//echo '<BR>str: '.$str;
	$str=preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $str); //-----utf-8 '/[\x00-\x1F\x7F]/u'

	if(is_array($str)){
		$prodarr=$str;
	}else{
		$prodarr=array();
		//if($opt==1 || $opt==8 || $opt==9 || $opt==10 || $opt==11){
		//	$prodarr=$str;
		//}else{
			if(strstr($str,',')){
				$prodarr=explode(',', $str);
			}else{
				$prodarr[0]=$str;
			}
		//}
	}
	//print_r($prodarr);
	//echo '<br>opt: '.$opt.'<br>';
	for($n=0;$n<count($prodarr);$n++){
		$fieldval=inpval($prodarr[$n]);
		if($fieldval!=''){
			$fieldid=0;
			//echo 'fieldval'.$fieldval;
			if($dbname=="ccd9prod2cat" || $dbname=="ccd9prod2type1"  || $dbname=="ccd9prod2type2" || $dbname=="ccd9prod2type3"){
				$querysql="select typeid from ccd9types where typename='$fieldval' and opt='$opt'";
				$result = $mysqli->query($querysql);
				if($rescon = $result->fetch_array()){
					$fieldid = $rescon[0];
				}
				if($fieldid>0){ }else{
					$mysqli->query("insert into ccd9types (typename, opt) values ('".$fieldval."', '$opt')");
					$fieldid=mysqli_insert_id($mysqli);
				}

				if($dbname=="ccd9prod2cat"){
					$mysqli->query("insert into ccd9prod2cat (prodid, catid) values ('$id', '$fieldid')");
				/*}else if($dbname=="ccd9prod2type3"){

					$sqlin="select images, galleryimages from `wc_products` where parent like '%$id' and (attribute1values='$fieldval' or attribute2values='$fieldval' or attribute3values='$fieldval') and images!='' order by position";
					$result = $mysqli->query($sqlin);
					if($res = $result->fetch_array()){
						if(trim($res['images'])!=''){
							$photo=trim($res['images']); 
						}
						if(trim($res['galleryimages'])!=''){
							$photo=$photo.','.trim($res['galleryimages']);
						}
					}else{
						$sqlin="select images, galleryimages from `wc_products` where id='$id'";
						$result = $mysqli->query($sqlin);
						if($res = $result->fetch_array()){
							if(trim($res['images'])!=''){
								$photo=trim($res['images']); 
							}
							if(trim($res['galleryimages'])!=''){
								$photo=$photo.','.trim($res['galleryimages']);
							}
						}

					}
					echo "insert into ccd9prod2type3 (prodid, typeid, photo, gallery) values ('$id', '$fieldid', '$photo', '$gallery')<br>";

					$mysqli->query("insert into ccd9prod2type3 (prodid, typeid, photo, gallery) values ('$id', '$fieldid', '$photo', '$gallery')");*/
				}else{
					$mysqli->query("insert into $dbname (prodid, typeid) values ('$id', '$fieldid')");
					echo  "insert into $dbname values ('$id', '$fieldid')<br>";
				}

			}
		}
	}
	//echo $fieldid;
	//return $fieldid;
}

function checkusersession($userid){
	if($userid>0){
		$db = Database::getInstance();
		$mysqli = $db->getConnection();
		
		$chksql="select logid from ccd9usersession u where u.userid='$userid' and u.logip='".$_SERVER['REMOTE_ADDR']."'  ";
		$result = $mysqli->query($chksql);
		$rowaccess = $result->fetch_array();
		if($rowaccess['logid']>0){
			return true;
		}else{
			return false;
		}
	}else{
		return false;
	}
}


function getsizebox($type1,$type2,$catid){
	$db = Database::getInstance();
	$mysqli = $db->getConnection();
	$sqlsize = "select * from ccd9sizes where (type1='".($type1==44 ? 0 : $type1)."' or  type1='0') and indiasize='$type2' and catid='$catid' and catid in (2,14)"; //
	//echo $sqlsize;
	$retval='';
	$result = $mysqli->query($sqlsize); 
	$num_rows = mysqli_num_rows($result);
	if ($num_rows>0){
		$retval=$retval. '<table class="table table-bordered align-middle table-hover text-center mb-1"><tr  id="sizemain"><th>India</th><th>UK</th><th>Length(MM)</th><th>Width(MM)</th></tr>';
		while($rescon = $result->fetch_array()){ 
			$retval=$retval. '<tr id="size'.trim($rescon['indiasize']).'" ><td>'.trim($rescon['indiasize']).'</td><td>'.trim($rescon['uksize']).'</td><td>'.trim($rescon['length']).'</td><td>'.trim($rescon['width']).'</td></tr>';
		}
		$retval=$retval. '</table>';
	}

	return $retval;
}


function getprodphotos($prodphoto, $prodimages){
	if($prodimages!='' && $prodphoto==''){
		$prodphoto=$prodimages;
	}
	$arrphoto = array();
	if(strstr($prodphoto,',')){
		$arrphoto=explode(',', trim($prodphoto));
	}else{
		$arrphoto[0]=trim($prodphoto);
	}
	$photo1=trim($arrphoto[0]);
	if(count($arrphoto)>1){
		$photo2=trim($arrphoto[1]);
	}else{
		$photo2=$photo1;
	}
	
	return array($photo1,$photo2);
}

function getmeasmenu($id){
	$retval=$retval.'<option value="0">Select Measurement</option>';

	$db = Database::getInstance();
	$mysqli = $db->getConnection(); 
	$sql="select typeid, typevalue, typename from ccd9types where opt='1' order by typeid";
	$result = $mysqli->query($sql);
	while($rescon = $result->fetch_array()){$retsel="";
		if($rescon['typeid']==$id){
			$retsel=" selected";
		}
		$retval=$retval.'<option value="'.$rescon['typeid'].'" '.$retsel.'>'.$rescon['typevalue'].'->'.$rescon['typename'].'</option>';
	} 
	return $retval;
}

function getcolors($id,$produrl){
	$retval='';

	$db = Database::getInstance();
	$mysqli = $db->getConnection(); 
	$sql="select t3c.typeid,t3c.typename, t3c.typevalue from ccd9prod2type3 t3 join ccd9types t3c on t3.typeid=t3c.typeid and t3c.opt=7  where t3.prodid='$id' and  t3c.typename!='NA' order by t3c.typeid";
	$result = $mysqli->query($sql);
	while($rescon = $result->fetch_array()){$retsel="";
		$retval=$retval.'<a href="'.$produrl.'&type3='.$rescon['typeid'].'"><li class="swatch medium radius " style="background-color:'.$rescon['typevalue'].'"><span class="tooltip-label">'.$rescon['typename'].'</span></li></a>';
	} 
	return $retval;
}

function getcountrymenu($id=0){
	//$retval=$retval.'<option value="0">Select Country</option>';

	$db = Database::getInstance();
	$mysqli = $db->getConnection(); 
	$sql="select countryid, countryname from ccd9country ".($_SESSION['myCUR']!="US $" ? " where countryid='99' " : "")." order by countryname"; 
	$result = $mysqli->query($sql);
	while($rescon = $result->fetch_array()){$retsel="";
		if(($rescon['countryid']==$id && $id>0) || $rescon['countryid']==99){
			$retsel=" selected";
		}
		$retval=$retval.'<option value="'.$rescon['countryid'].'" '.$retsel.'>'.$rescon['countryname'].'</option>';
	} 
	return $retval;
}

function getstatemenu($id=0){
	$retval=$retval.'<option value="">Select State</option>';

	$db = Database::getInstance();
	$mysqli = $db->getConnection(); 
	$sql="select statename from ccd9states order by statename"; 
	$result = $mysqli->query($sql);
	while($rescon = $result->fetch_array()){$retsel="";
		if(($rescon['statename']==$id && $id>0) || $rescon['statename']=="Maharashtra"){
			$retsel=" selected";
		}
		$retval=$retval.'<option value="'.$rescon['statename'].'" '.$retsel.'>'.$rescon['statename'].'</option>';
	} 
	return $retval;
}


function showhgthlhgt($height,$heelheight){ //height & heel height

	if($height!=''){
		list($heightft,$heightin)=explode('.',$height);
	}
	return '<BR>Full Body Height: '.$heightft.' Ft'.($heightin>0 ? '&nbsp;'.$heightin.' In':'').' &nbsp;|&nbsp;Heels Height: '.$heelheight.' In';
}

function getRandomString($length = 6) {
    $validCharacters = "abcdefghijklmnopqrstuxyvwzABCDEFGHIJKLMNOPQRSTUXYVWZ0123456789"; //+-*#&@!?
    $validCharNumber = strlen($validCharacters);
 
    $result = "";
 
    for ($i = 0; $i < $length; $i++) {
        $index = mt_rand(0, $validCharNumber - 1);
        $result .= $validCharacters[$index];
    }

	return $result;
}

function sendsmtpmail($to,$subject,$emailtext,$bcc='',$fromid='',$fromuser=''){

	
	$db = Database::getInstance();
	$mysqli = $db->getConnection();
	include("dbconfig.php");

	$sql="select typevalue from ccd9types where typevalue1='1' and opt='106'";
	$result = $mysqli->query($sql);
	if($rescon = $result->fetch_array()){
		$emailtext=$emailtext.'<br><p><a href="'.$serverurl.'" target="_blank"><img src="'.$serverurl.'/promotions/'.$rescon['typevalue'].'" width="600"></a></p>';
	}

	if($fromid!=''){
		$email=$fromid;
	}else{
		$email=$SmtpUser;
	}

	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	$headers .= "From: OrthoFit Mart<$email>\r\n";
	if($bcc!=''){
		$headers .= "Bcc: $bcc\r\n";
	}
	//echo $to.'<br>';
	//echo $bcc.'<br>';
	mail($to, $subject, $emailtext, $headers);
	//exit();
}
 
function redimdiscountemail($id){
	$db = Database::getInstance();
	$mysqli = $db->getConnection();
	include("dbconfig.php");

	$sql="select o.discid, o.disccode, concat(o.billing_username,' ',o.billing_lastname) as username, o.email, d.discamt, o.ordcur from ccd9orders o join ccd9discounts d on o.discid=d.discid where o.orderid='$id' and o.status='1' and o.discid>0 and d.cartid>0 and o.discountamt>0";
	$result = $mysqli->query($sql);
	if($rescon = $result->fetch_array()){

		$emailtext=getpagedata('16');
		$emailtext=str_replace("##customername##",$rescon['username'],$emailtext);
		$emailtext=str_replace("##disccode##",$rescon['disccode'],$emailtext);
		$emailtext=str_replace("##discamt##",$rescon['ordcur'].' '.$rescon['discamt'],$emailtext);
		$subject=trim(getpagetitle('16'));

		$to=trim($rescon['email']);

		sendsmtpmail($to,$subject,$emailtext,$technicalemail);

		$sql="update ccd9discounts set discstatus='0' where discid='".$rescon['discid']."' and discuse='0'";
		$mysqli->query($sql);
	}
}

function remsaleprod($id){
	$db = Database::getInstance();
	$mysqli = $db->getConnection();

	$sqlmain="select prodid from ccd9cart where orderid='$id' and status='1' and prodid in (select prodid from ccd9prod2cat p where p.catid in (148))"; //sale category
	$result = $mysqli->query($sqlmain);
	while($rescon = $result->fetch_array()){
		$sql="update ccd9products set prodstatus='0' where prodid='".$rescon['prodid']."'";
		$mysqli->query($sql);
	}
}

function sendgcemail($id){

	$db = Database::getInstance();
	$mysqli = $db->getConnection();
	include("dbconfig.php");

	$sql="select cartid, prodid, prodprice, usdprice, prodcur, discemail, prodname from ccd9cart where orderid='$id' and status='1' and prodid in (select prodid from ccd9prod2cat p where p.catid='149')"; //gift category
	$result = $mysqli->query($sql);
	if($rescon = $result->fetch_array()){
		$cartid=$rescon['cartid'];
		$discemail=$rescon['discemail'];
		$prodname=$rescon['prodname'];
		$prodcur=$rescon['prodcur'];
		if($prodcur!='US $'){
			$discamt=$rescon['prodprice'];
		}else{
			$discamt=$rescon['usdprice'];
		}

		//---------code to send gift card code. 13/15------------------

		$sql="select date_format(o.orddate, '%Y') as dt, o.billing_username, o.email from ccd9orders o where o.orderid='$id'";
		$result = $mysqli->query($sql);
		if($rescon = $result->fetch_array()){

			$itemName="OH".$rescon['dt']."/".$id;

			$emailtext=getpagedata('14');
			$emailtext=str_replace("##customername##",$rescon['billing_username'],$emailtext);
			$emailtext=str_replace("##discemail##",$discemail,$emailtext);
			$emailtext=str_replace("##discamt##",$discamt,$emailtext);
			$subject=str_replace("##orderno##",$itemName,getpagetitle('14'));

			$to=trim($rescon['email']);

			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= "From: $adminuser <$adminid>\r\n";
			$headers .= "Bcc: ".$technicalemail."\r\n";
			//mail($to, $subject, $emailtext, $headers);
			sendsmtpmail($to,$subject,$emailtext,$technicalemail);
		}
	}
}

function processgiftcard($id){
	include("dbconfig.php");
	//echo getRandomString();
	$db = Database::getInstance();
	$mysqli = $db->getConnection();

	$rescon = query_first("select date_format(o.orddate, '%Y') as dt, concat(o.billing_username,' ',o.billing_lastname) as username, o.email from ccd9orders o where o.orderid='$id'");
	$itemName="OH".$rescon['dt']."/".$id;
	$customername=$rescon['username'];
	$purchaser=$rescon['email'];
	
	$sqlmain="select cartid, prodid, prodprice, usdprice, prodcur, discemail, prodname from ccd9cart where orderid='$id' and status='1' and prodid in (select prodid from ccd9prod2cat p where p.catid='149')"; //gift category
	$resmain = $mysqli->query($sqlmain);
	while($resrows = $resmain->fetch_array()){
		$cartid=$resrows['cartid'];
		$discemail=$resrows['discemail'];
		$prodname=$resrows['prodname'];
		$prodcur=$resrows['prodcur'];
		$disctype=2; //fixed amount discount
		$discuse=0; //single use coupon
		if($prodcur!='US $'){
			$discamt=$resrows['prodprice'];
			$epgid=13;
		}else{
			$discamt=$resrows['usdprice'];
			$epgid=15;
		}
		$userid=0;
	//}
	//if($cartid>0){
		$sql="select compid from ccd9company where email='$discemail'"; //search if user is registered
		$result = $mysqli->query($sql);
		if($rescon = $result->fetch_array()){
			$userid=$rescon['compid'];
		}

		$codevalid=true;
		while($codevalid){
			$disccode=getRandomString(8);
			$sql="select discid from ccd9discounts where disccode='$disccode'";
			$result = $mysqli->query($sql);
			if($rescon = $result->fetch_array()){}else{
				$codevalid=false; break;
			}
		}
		
		$discdate=date('Y-m-d', strtotime(date("Y-m-d"). ' + 186 days'));

		$sql="insert into ccd9discounts (disccode, discamt, disctype, discuse, discemail, compid, cartid, discexpiry, discdescr, disccur, exclcats) values ('$disccode', '$discamt', '$disctype', '$discuse', '$discemail', '$userid', '$cartid', '$discdate', '$prodname', '$prodcur', '148,149,335')";
		//echo $sql;
		$mysqli->query($sql);

		

		//---------gift card email to recipient

			$sqlin="select p.produrl from ccd9cart t join ccd9products p on p.prodid=t.prodid where t.cartid='$cartid'";
			$resin = query_first($sqlin);

			$disphoto='<a href="'.getprodurl($resin['produrl']).'" target="_blank"><img style="width:300px;" src="'.$serverurl.getprodimg($resin['produrl'],'a','3').'"></a>';

			$emailtext=getpagedata($epgid);
			$emailtext=str_replace("##customername##",$customername,$emailtext);
			$emailtext=str_replace("##disccode##",$disccode,$emailtext);
			$emailtext=str_replace("##discamt##",$prodcur.' '.$discamt,$emailtext);
			$emailtext=str_replace("##discdate##",inddate($discdate),$emailtext);
			$emailtext=str_replace("##disphoto##",$disphoto,$emailtext);

			$subject=trim(getpagetitle($epgid));

			$to=trim($discemail);

			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= "From: $adminuser <$adminid>\r\n";
			$headers .= "Bcc: ".$technicalemail."\r\n";
			//mail($to, $subject, $emailtext, $headers);
			sendsmtpmail($to,$subject,$emailtext,$technicalemail);
		
		//---------end gift card email to recipient

		//---------gift card email to purchaser

			$emailtext=getpagedata('14');
			$emailtext=str_replace("##customername##",$customername,$emailtext);
			$emailtext=str_replace("##discemail##",$discemail,$emailtext);
			$emailtext=str_replace("##discamt##",$prodcur.' '.$discamt,$emailtext);
			$subject=str_replace("##orderno##",$itemName,getpagetitle('14'));

			$to=trim($purchaser);

			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= "From: $adminuser <$adminid>\r\n";
			$headers .= "Bcc: ".$technicalemail."\r\n";
			//mail($to, $subject, $emailtext, $headers);
			sendsmtpmail($to,$subject,$emailtext,$technicalemail);

		//---------end gift card email to purchaser
		


	}
}

function sendordemail($id){
	$orderid=$id;
	$db = Database::getInstance();
	$mysqli = $db->getConnection();
	include("dbconfig.php");
	include("orderpg.php");

	/*if($shiptime=='Ready to Ship' || $shiptime=='Within 1 Week'){
		$shiptime='within 1 week*';
	}else if($shiptime!=''){
		$shiptime='within '.strtolower($shiptime).'*';
	}else{*/
		$shiptime='within 2-3 weeks*';
	//}
	

	$itemName="OH".$dtyr."/".$orderid; 
	//$trackcode="<a href='".$serverurl."track-order?orderno=".$itemName."'>".$itemName."</a>";
	//echo "cartrows".$cartrows;
	if($cartrows>0){

		/*if($ordhasgc==$cartrows){
			$emailtext=getpagedata('18');
			$subject=str_replace("##orderno##",$itemName,getpagetitle('18'));
		}else if($cartrows>$ordhasgc && $ordhasgc>0){
			$emailtext=getpagedata('21');
			$subject=str_replace("##orderno##",$itemName,getpagetitle('21'));
		}else{*/
			$emailtext=getpagedata('17');
			$subject=str_replace("##orderno##",$itemName,getpagetitle('17'));
		//}
		$emailtext=str_replace("##customername##",$billing_username.' '.$billing_lastname,$emailtext);
		$emailtext=str_replace("##trackcode##",$trackcode,$emailtext);
		$emailtext=str_replace("##shoppingcart##",$ordpg,$emailtext);
		$emailtext=str_replace("##discemail##",implode(',',$discemail),$emailtext);
		$emailtext=str_replace("##shiptime##",$shiptime,$emailtext);
		

		$to=$billing_email;

		//echo $emailtext;

		sendsmtpmail($to,$subject,$emailtext,$technicalemail);

	}else if($cartrows==0){
		//sendsmtpmail("samir.sudrik@gmail.com",$itemName,"error in order email compid=".$_SESSION["compid"]." COOKIE=".$_COOKIE['mycompid']." sessionid=".session_id());
	}
}

function getcaturl($id){

	$db = Database::getInstance();
	$mysqli = $db->getConnection(); 
	$sql="select t.typevalue, t.typeid from ccd9types t left join ccd9prod2cat p on p.catid=t.typeid where t.opt='2' and p.prodid='$id' order by t.typeid";
	$result = query_first($sql);
	return array($result[0],$result[1]);
}

function getcatname($id){

	$db = Database::getInstance();
	$mysqli = $db->getConnection(); 
	$sql="select t.typename from ccd9types t left join ccd9prod2cat p on p.catid=t.typeid where t.opt='2' and p.prodid='$id' order by t.typeid";
	$result = query_first($sql);
	return $result[0];
}

function checkmencat($id){

	$db = Database::getInstance();
	$mysqli = $db->getConnection(); 
	$sql="select catid from ccd9prod2cat p where p.prodid='$id' and p.catid='151'"; //men category
	$result = query_first($sql);
	return $result[0];
}

function checkpretcat($id){

	$db = Database::getInstance();
	$mysqli = $db->getConnection(); 
	$sql="select catid from ccd9prod2cat p where p.prodid='$id' and p.catid='23'"; //Pret category
	$result = query_first($sql);
	return $result[0];
}

function checkgiftcat($id){

	/*$db = Database::getInstance();
	$mysqli = $db->getConnection(); 
	$sql="select catid from ccd9prod2cat p where p.prodid='$id' and p.catid='149'"; //gift category
	$result = query_first($sql);
	return $result[0];*/
	return 0;
}

function checkmaskcat($id){

	$db = Database::getInstance();
	$mysqli = $db->getConnection(); 
	$sql="select catid from ccd9prod2cat p where p.prodid='$id' and p.catid='153'"; //mask category
	$result = query_first($sql);
	return $result[0];
}

function checksalecat($id){

	$db = Database::getInstance();
	$mysqli = $db->getConnection(); 
	$sql="select catid from ccd9prod2cat p where p.prodid='$id' and p.catid in (148)"; //sale category
	$result = query_first($sql);
	return $result[0];
}

function getpagedata($id){

	$db = Database::getInstance();
	$mysqli = $db->getConnection(); 
	$sql="select description from ccd9pages where pageid='$id'";
	$result = query_first($sql);
	return $result[0];
}

function getpagetitle($id){

	$db = Database::getInstance();
	$mysqli = $db->getConnection(); 
	$sql="select meta_title from ccd9pages where pageid='$id'";
	$result = query_first($sql);
	return $result[0];
}

function getcountry($id){

	$db = Database::getInstance();
	$mysqli = $db->getConnection(); 
	$sql="select countryname from ccd9country where countryid='$id'";
	$result = query_first($sql);
	return $result[0];
}

function chkkidscat($id){
	$sql="select count(*) as cnt from ccd9prod2cat where prodid='$id' and catid in (24) ";
	$result = query_first($sql);
	return $result[0];
}


function getnoguidecat($id){
	$sql="select count(*) as cnt from ccd9prod2cat where prodid='$id' and catid in (25,149,153,360,425,246,151,561,426) "; //Accessories & Gift Cards menswear
	$result = query_first($sql);
	return $result[0];

	//$query=query_first("select count(*) as cnt from ccd9prod2cat p2c join  ccd9types t on p2c.catid=t.typeid where p2c.prodid='$id' and t.opt='2' and  t.typeid in (25)");
	//return $query['cnt'];
}

function getcatmenu($id=0, $cid=0){
	$retval=$retval.'<option value="0">Select Category</option>';

	$db = Database::getInstance();
	$mysqli = $db->getConnection(); 
	$sql="select t.typeid, t.typename, p.catid from ccd9types t left join ccd9prod2cat p on p.catid=t.typeid and p.prodid='$id' where t.opt='2' order by t.typeid";
	$result = $mysqli->query($sql);
	while($rescon = $result->fetch_array()){$retsel="";
		if($rescon['catid']>0 || $rescon['typeid']==$cid){
			$retsel=" selected";
		}
		$retval=$retval.'<option value="'.$rescon['typeid'].'" '.$retsel.'>'.$rescon['typename'].'</option>';
	} 
	return $retval;
}

function gettypemenu($id=0, $tid=0, $dbname, $opt){
	$retval='';

	$db = Database::getInstance();
	$mysqli = $db->getConnection(); 
	$sql="select t.typeid, t.typename, p.typeid as type1 from ccd9types t left join $dbname p on p.typeid=t.typeid and p.prodid='$id' where t.opt='$opt' order by t.typename";
	echo $sql;
	$result = $mysqli->query($sql);
	while($rescon = $result->fetch_array()){$retsel="";
		if($rescon['type1']>0 || $rescon['typeid']==$tid){
			$retsel=" selected";
		}
		$retval=$retval.'<option value="'.$rescon['typeid'].'" '.$retsel.'>'.$rescon['typename'].'</option>';
	} 
	return $retval;
}

function gettype1menu($id=0, $tid=0){
	$retval=$retval.'<option value="0">Select Type</option>';

	$db = Database::getInstance();
	$mysqli = $db->getConnection(); 
	$sql="select t.typeid, t.typename, p.typeid as type1 from ccd9types t left join ccd9prod2type1 p on p.typeid=t.typeid and p.prodid='$id' where t.opt='3' order by t.typename";
	$result = $mysqli->query($sql);
	while($rescon = $result->fetch_array()){$retsel="";
		if($rescon['type1']>0 || $rescon['typeid']==$tid){
			$retsel=" selected";
		}
		$retval=$retval.'<option value="'.$rescon['typeid'].'" '.$retsel.'>'.$rescon['typename'].'</option>';
	} 
	return $retval;
}

function gettype2menu($id=0, $tid=0){
	$retval=$retval.'<option value="0">Select Type</option>';

	$db = Database::getInstance();
	$mysqli = $db->getConnection(); 
	$sql="select t.typeid, t.typename, p.typeid as type2 from ccd9types t left join ccd9prod2type2 p on p.typeid=t.typeid and p.prodid='$id' where t.opt='4' order by t.typename";
	$result = $mysqli->query($sql);
	while($rescon = $result->fetch_array()){$retsel="";
		if($rescon['type2']>0 || $rescon['typeid']==$tid){
			$retsel=" selected";
		}
		$retval=$retval.'<option value="'.$rescon['typeid'].'" '.$retsel.'>'.$rescon['typename'].'</option>';
	} 
	return $retval;
}

function gettype3menu($id=0, $tid=0){
	$retval=$retval.'<option value="0">Select Type</option>';

	$db = Database::getInstance();
	$mysqli = $db->getConnection(); 
	$sql="select t.typeid, t.typename, p.typeid as type3 from ccd9types t left join ccd9prod2type3 p on p.typeid=t.typeid and p.prodid='$id' where t.opt='7' order by t.typename";
	$result = $mysqli->query($sql);
	while($rescon = $result->fetch_array()){$retsel="";
		if($rescon['type3']>0 || $rescon['typeid']==$tid){
			$retsel=" selected";
		}
		$retval=$retval.'<option value="'.$rescon['typeid'].'" '.$retsel.'>'.$rescon['typename'].'</option>';
	} 
	return $retval;
}

function getrelatmenu($id=0){
	$retval=$retval.'<option value="0">Select Products</option>';

	$db = Database::getInstance();
	$mysqli = $db->getConnection(); 
	$sql="select p.prodid, p.prodcode, p.prodname, r.relprodid from ccd9products p left join ccd9prodrelated r on p.prodid=r.prodid and p.prodid='$id' where p.prodstatus='1' order by p.prodcode, p.prodname";
	$result = $mysqli->query($sql);
	while($rescon = $result->fetch_array()){$retsel="";
		if($rescon['relprodid']>0){
			$retsel=" selected";
		}
		$retval=$retval.'<option value="'.$rescon['prodid'].'" '.$retsel.'>'.$rescon['prodcode'].'-'.$rescon['prodname'].'</option>';
	} 
	return $retval;
}

function getcrossmenu($id=0){
	$retval=$retval.'<option value="0">Select Products</option>';

	$db = Database::getInstance();
	$mysqli = $db->getConnection(); 
	$sql="select p.prodid, p.prodcode, p.prodname, r.relprodid from ccd9products p left join ccd9prodxsell r on p.prodid=r.prodid and p.prodid='$id' where p.prodstatus='1' order by p.prodcode, p.prodname";
	$result = $mysqli->query($sql);
	while($rescon = $result->fetch_array()){$retsel="";
		if($rescon['relprodid']>0){
			$retsel=" selected";
		}
		$retval=$retval.'<option value="'.$rescon['prodid'].'" '.$retsel.'>'.$rescon['prodcode'].'-'.$rescon['prodname'].'</option>';
	} 
	return $retval;
}

function getaccesmenu($id=0){
	$retval=$retval.'<option value="0">Select Products</option>';

	$db = Database::getInstance();
	$mysqli = $db->getConnection(); 
	$sql="select p.prodid, p.prodcode, p.prodname, r.relprodid from ccd9products p left join ccd9prodacces r on p.prodid=r.prodid and p.prodid='$id' where p.prodstatus='1' order by p.prodcode, p.prodname";
	$result = $mysqli->query($sql);
	while($rescon = $result->fetch_array()){$retsel="";
		if($rescon['relprodid']>0){
			$retsel=" selected";
		}
		$retval=$retval.'<option value="'.$rescon['prodid'].'" '.$retsel.'>'.$rescon['prodcode'].'-'.$rescon['prodname'].'</option>';
	} 
	return $retval;
}

function getkidsmenu($id=0){
	$retval=$retval.'<option value="0">Select Products</option>';

	$db = Database::getInstance();
	$mysqli = $db->getConnection(); 
	$sql="select p.prodid, p.prodcode, p.prodname, r.relprodid from ccd9products p left join ccd9prod4kids r on p.prodid=r.prodid and p.prodid='$id' where p.prodstatus='1' order by p.prodcode, p.prodname";
	$result = $mysqli->query($sql);
	while($rescon = $result->fetch_array()){$retsel="";
		if($rescon['relprodid']>0){
			$retsel=" selected";
		}
		$retval=$retval.'<option value="'.$rescon['prodid'].'" '.$retsel.'>'.$rescon['prodcode'].'-'.$rescon['prodname'].'</option>';
	} 
	return $retval;
}

function getcombomenu($id=0){
	$retval=$retval.'<option value="0">Select Products</option>';

	$db = Database::getInstance();
	$mysqli = $db->getConnection(); 
	$sql="select p.prodid, p.prodcode, p.prodname, r.relprodid from ccd9products p left join ccd9prodcombo r on p.prodid=r.prodid and p.prodid='$id' where p.prodstatus='1' order by p.prodcode, p.prodname";
	$result = $mysqli->query($sql);
	while($rescon = $result->fetch_array()){$retsel="";
		if($rescon['relprodid']>0){
			$retsel=" selected";
		}
		$retval=$retval.'<option value="'.$rescon['prodid'].'" '.$retsel.'>'.$rescon['prodcode'].'-'.$rescon['prodname'].'</option>';
	} 
	return $retval;
}
 
function dbval($var){
	return trim(htmlspecialchars(stripslashes($var),ENT_QUOTES));
}

function inpval($var){
	return trim(addslashes($var));
}

function sqldate($dt){
	if($dt!="" && $dt!="00/00/0000"){
		list($day,$month,$year)=explode("/",$dt);
		$dt=$year."-".$month."-".$day;
	}else{
		$dt="";
	}
	return $dt;

}
function inddate($dt){
	if(($dt!="")&&($dt!="0000-00-00")){
		list($year,$month,$day)=explode("-",$dt);
		$dt="$day/$month/$year";
	}else{
		$dt="";
	}
	return $dt;
}

function is_decimal( $val ){
    return is_numeric( $val ) && floor( $val ) != $val;
}

function debug($o)
{
	print '<pre>';
	print_r($o);
	print '</pre>';
}


function showcursymb($cur){
	//if($cur=='')$cur=$_SESSION['myCUR'];
	//return (($cur=="USD" || $cur=="US $") ? '<i class="fa fa-usd" aria-hidden="true"></i>' : '<i class="fa fa-inr" aria-hidden="true"></i>');
	return $_SESSION['myCUR'];
}

function getprice($row){
	$isoffer=$row['isoffer'];
	if($_SESSION['myCUR']=="US $"){
		$prodprice = $row['usdprice'];
		$offerprice=$row['offerusd'];
		$cur='<i class="fa fa-usd" aria-hidden="true"></i>';
	}else{
		$prodprice = $row['prodprice'];
		$offerprice=$row['offerprod'];
		$cur='<i class="fa fa-inr" aria-hidden="true"></i>';
	}
	$cur=$_SESSION['myCUR'];
	if($row['isdiscount']==1 && $row['proddisc']>0){
		$offerprice=$prodprice-round($prodprice*$row['proddisc']/100,0);
		$isoffer=1;
	}
	//$prodprice=number_format($prodprice);
	//if($offerprice>0 || $isoffer==1)$prodprice='<strike>'.$prodprice.'</strike>';

	//return money_format('%.0n', $prodprice).' '.($offerprice>0 ? $offerprice : '');
	return array(($offerprice>0 ? ' <span class="old-price">'.$cur.number_format($prodprice).'</span>&nbsp;&nbsp;'.$cur.number_format($offerprice).'&nbsp;&nbsp;<span class="redText">'.(round(($prodprice-$offerprice)*100/$prodprice,0)).'% OFF</span>' : $cur.number_format($prodprice)), ($offerprice>0 ? $offerprice : $prodprice));
}

function getshipprice($row){
	if($_SESSION['myCUR']=="US $"){
		$shipprice = $row['shipusd'];
		$cur='<i class="fa fa-usd" aria-hidden="true"></i>';
	}else{
		$shipprice = $row['shipprod'];
		$cur='<i class="fa fa-inr" aria-hidden="true"></i>';
	}
	//return ($shipprice>0 ? money_format('%n', $shipprice) : '*All prices') .' include packing, shipping & handling.';
	return ($shipprice>0 ? $cur.$shipprice : '').'*Prices are inclusive of all taxes, packaging and handling.<br>For international purchases, duties and taxes may be applicable based on the import laws of your country.';
}

function getprodurl($url,$caturl=''){
	if($caturl==''){ // || $opt=='search'
		$db = Database::getInstance();
		$mysqli = $db->getConnection();

		$query=query_first("select t.typevalue from ccd9types t join ccd9prod2cat p2c on p2c.catid=t.typeid join ccd9products p on p2c.prodid=p.prodid where p.produrl='$url' order by t.typeid");
		$caturl=$query['typevalue'];
		if($caturl=='')$caturl='search';
	}
	return $GLOBALS['serverurl'].$caturl.'/'.$url; //$opt
}
function getprodimg($code,$n='a',$x=0){
	return 'collection/'.$code.'-'.$n.$x.'.jpg'; //'http://www.'.$GLOBALS['serverdomain'].
}

function generateimages($x, $x1, $dimy=512){
	include_once("image.php");
	for($n=1;$n<20;$n++){
		$file =$x.'-'.strtolower(chr($n+64)).'0.jpg';
		$file1 =$x1.strtolower(chr($n+64)).'0.jpg';
	//echo $file1.'....'.$file;
		if(file_exists($file)){
			$a3file =$x.'-'.strtolower(chr($n+64)).'3.jpg';
			if(!file_exists($a3file)){
				$newimg = new Image($file);
				$thumbimg=$a3file;
				$newimg->resize(343,$dimy);
				$newimg->save($thumbimg);
			}
		}else if(file_exists($file1)){
			if (!copy($file1,$file)) {
			   //exit( "failed to copy $file1...to $file<br>" );
			} 
			if(file_exists($file)){
				$a3file =$x.'-'.strtolower(chr($n+64)).'3.jpg';
				if(!file_exists($a3file)){
					$newimg = new Image($file);
					$thumbimg=$a3file;
					$newimg->resize(343,$dimy);
					$newimg->save($thumbimg);
				}
			}
		}

		if($n==1){

			$a3file =$x.'-'.strtolower(chr($n+64)).'3.jpg';
			$a3filewebp=$x.'-'.strtolower(chr($n+64)).'3.webp';

			if(!file_exists($a3filewebp)){
				$image = imagecreatefromstring(file_get_contents($a3file));
				ob_start();
				imagejpeg($image,NULL,100);
				$cont = ob_get_contents();
				ob_end_clean();
				imagedestroy($image);
				$content = imagecreatefromstring($cont);
				$output = $a3filewebp;
				imagewebp($content,$output);
				imagedestroy($content);
			}
		}
		
	}
}

function getwishcnt(){
	if($_SESSION['compid']>0){
		$db = Database::getInstance();
		$mysqli = $db->getConnection();

		$query=query_first("select count(*) as cnt from ccd9wishlist where compid='".$_SESSION['compid']."'");
		return $query['cnt'];
	}else{
		return 0;
	}
}

function getcartcnt(){
	$db = Database::getInstance();
	$mysqli = $db->getConnection();

	$query=query_first("select IFNULL(sum(prodqty),0) as cnt from ccd9cart c where ".($_SESSION['compid']>0 ? " compid='".$_SESSION['compid']."'" : " c.sessionid='".session_id()."' and c.compid='0'")." and status='0'");
	echo ($query['cnt']>0 ? $query['cnt'] : '&nbsp;');
}

function getmeas($dimstr){
	$dimlist=explode(",", $dimstr);
	list($heightft,$heightin)=explode('.',$dimlist[3]);
	$retval='Ref. Size: '.$dimlist[1].' | Height: '.$heightft.'Ft '.$heightin.'In | Heels Height: '.$dimlist[4].'In | Measurements Unit: '.$dimlist[2];

	for($n=5;$n<count($dimlist);$n++){
		list($typeval,$dimval)=explode('=',$dimlist[$n]);
		if($dimval>0){
			$unitname = getfieldvalue($typeval, 'typename', 'typeid', 'ccd9types');
			$retval=$retval.' | '.ucwords(strtolower($unitname)).': '.$dimval;
		}
	}
	return $retval;
}

function getinrs($x){
	if($x<=0){
		$rsnote="Nil";
	}else{
		$rsnote="Rupees ";
		if(intval($x/10000000)>0){
			$rsnote=$rsnote." ".gettens(intval($x/10000000))." Crore ";
			$x=$x-(intval($x/10000000)*10000000);
		}
		if(intval($x/100000)>0){
			$rsnote=$rsnote." ".gettens(intval($x/100000))." Lakh ";
			$x=$x-(intval($x/100000)*100000);
		}
		if(intval($x/1000)>0){
			$rsnote=$rsnote." ".gettens(intval($x/1000))." Thousand ";
			$x=$x-(intval($x/1000)*1000);
		}
		if(intval($x/100)>0){
			$rsnote=$rsnote." ".gettens(intval($x/100))." Hundred ";
			$x=$x-(intval($x/100)*100);
		}
		$rsnote=$rsnote." ".gettens($x)." Only";
	}
	




	return $rsnote;
}

function gettens($y){

//$arrtens[1]=" Ten ";
$arrtens[2]=" Twenty ";
$arrtens[3]=" Thirty ";
$arrtens[4]=" Fourty ";
$arrtens[5]=" Fifty ";
$arrtens[6]=" Sixty ";
$arrtens[7]=" Seventy ";
$arrtens[8]=" Eighty ";
$arrtens[9]=" Ninety ";

$arrons[1]=" One ";
$arrons[2]=" Two ";
$arrons[3]=" Three ";
$arrons[4]=" Four ";
$arrons[5]=" Five ";
$arrons[6]=" Six ";
$arrons[7]=" Seven ";
$arrons[8]=" Eight ";
$arrons[9]=" Nine ";
$arrons[10]=" Ten ";
$arrons[11]=" Eleven ";
$arrons[12]=" Twelve ";
$arrons[13]=" Thirteen ";
$arrons[14]=" Fourteen ";
$arrons[15]=" Fifteen ";
$arrons[16]=" Sixteen ";
$arrons[17]=" Seventeen ";
$arrons[18]=" Eighteen ";
$arrons[19]=" Nineteen ";


	//if(intval($y/10)>0){
	if($y>=100){
		return $arrons[intval($y/100)]." Hundred ".$arrtens[intval($y/10)]." ".$arrons[$y-(intval($y/10)*10)];
	}else if($y>19){
		return $arrtens[intval($y/10)]." ".$arrons[$y-(intval($y/10)*10)];
	}else{
		return $arrons[$y];
	}

}


class Database {
	private $_connection;
	private static $_instance; //The single instance
	var $_host = "localhost";
	var $_username = "orthofit_052024";
	var $_password = "Mgk{Au+BirWg";
	var $_database = "orthofit_052024";
	/*
	Get an instance of the Database
	@return Instance
	*/
	public static function getInstance() {
		if(!self::$_instance) { // If no instance then make one
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	// Constructor
	private function __construct() {
		$this->_connection = new mysqli($this->_host, $this->_username, 
			$this->_password, $this->_database);
	
		// Error handling
		if(mysqli_connect_error()) {
			trigger_error("Failed to conencto to MySQL: " . mysqli_connect_error(),
				 E_USER_ERROR);

			$emailtxt="Failed to conencto to MySQL: " . mysqli_connect_error();

			sendsmtpmail('samir.sudrik@gmail.com','Failed to conencto to MySQL',$emailtxt);
		}
	}
	// Magic method clone is empty to prevent duplication of connection
	private function __clone() { }
	// Get mysqli connection
	public function getConnection() {
		return $this->_connection;
	}
}



?>