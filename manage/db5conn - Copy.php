<?php
//https://www.w3schools.com/php/php_ref_mysqli.asp
include("dbconfig.php");
$acccodes = array('PS-DF042','PS-DF039','PS-DF038','PS-DF037','PS-DF036','PS-DF035', 'PS-DF034','PS-DF033','PS-DF032','PS-DF031','PS-DF030','PS-DF020','PS-DF022','PS-DF019','PS-DF097','PS-DF098');

$db = Database::getInstance();
$mysqli = $db->getConnection();

$filename = trim(substr($_SERVER['SCRIPT_NAME'],strrpos($_SERVER['SCRIPT_NAME'],"/")+1));

function num_rows($result){
	return mysqli_num_rows($result);
}

function query_first($sql){
	$db = Database::getInstance();
	$mysqli = $db->getConnection();
	
	$result = $mysqli->query($sql);
	return $result->fetch_array();
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


function checkuserref(){
	if(strpos($_SERVER['HTTP_REFERER'],$GLOBALS['serverdomain'])){
		return true;
	}else{
		return false;
	}
}

function encyrptPassword($pwd){
	 $salt = CRYPT_MD5 ;
	 srand(time());
	 for( $i = 3; $i < 11; $i++ )
	 {
		 $n = 32 + (rand() % 94);
		 $salt .= chr($n);
	 }
	 $epwd = crypt($pwd, $salt);
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
				return "PS".substr($rescon[0],0,4)."/".$id;
			}else{
				return "PS".date("Y")."/".$id;
			}
		}
	}else{
		return "PS".substr($dt,0,4)."/".$id;
	}
}

function storearray($str,$id,$dbname,$opt=0){

	$db = Database::getInstance();
	$mysqli = $db->getConnection();
	$mysqli->query("delete from $dbname where prodid='$id'");
	
	//echo '<BR>dbname: '.$dbname;
	//echo '<BR>str: '.$str;
	$prodarr=array();
	if($opt==1){
		$prodarr=$str;
	}else{
		if(strstr($str,',')){
			$prodarr=explode(',', $str);
		}else{
			$prodarr[0]=$str;
		}
	}
	//print_r($prodarr);
	for($n=0;$n<count($prodarr);$n++){
		$fieldval=trim($prodarr[$n]);
		if($fieldval!=''){
			if($dbname=="ccd9prod2cat" || $dbname=="ccd9prod2type1"  || $dbname=="ccd9prod2type2"){
				if($opt==0){
					$fieldid = getfieldvalue($fieldval, 'typeid', 'typename', 'ccd9types');
				}else{
					$fieldid =$fieldval;
				}
				if($fieldid>0){
					$mysqli->query("insert into $dbname values ('$id', '$fieldid')");
					//echo "<BR>insert into $dbname values ('$id', '$fieldid')";
				}
			}else if($dbname=="ccd9prod2type3"){
				if($opt==0){
					$fieldid = getfieldvalue($fieldval, 'typeid', 'typename', 'ccd9types', " and opt='7'");
				}else{
					$fieldid =$fieldval;
				}
				if($fieldid>0){
					$mysqli->query("insert into $dbname values ('$id', '$fieldid')");
				}else if($opt==0){
					$mysqli->query("insert into ccd9types (typename, opt) values ('".$fieldval."', '7')");
					$fieldid=mysqli_insert_id($mysqli);
					$mysqli->query("insert into $dbname values ('$id', '$fieldid')");
				}
				//echo "<BR>insert into $dbname values ('$id', '$fieldid')";
			}else{
				if($opt==0){
					$fieldid = getfieldvalue($fieldval, 'prodid', 'prodcode', 'ccd9products');
				}else{
					$fieldid =$fieldval;
				}
				if($fieldid>0){
					$mysqli->query("insert into $dbname values ('$id', '$fieldid')");
				}
				//echo "<BR>insert into $dbname values ('$id', '$fieldid')";
			}
		}
	}
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

function getcountrymenu($id=0){
	$retval=$retval.'<option value="0">Select Country</option>';

	$db = Database::getInstance();
	$mysqli = $db->getConnection(); 
	$sql="select countryid, countryname from ccd9country ".($_SESSION['myCUR']!="US $" ? " where countryid='99' " : "")."order by countryname";
	$result = $mysqli->query($sql);
	while($rescon = $result->fetch_array()){$retsel="";
		if($rescon['countryid']==$id){
			$retsel=" selected";
		}
		$retval=$retval.'<option value="'.$rescon['countryid'].'" '.$retsel.'>'.$rescon['countryname'].'</option>';
	} 
	return $retval;
}

function showhgthlhgt($height,$heelheight){ //height & heel height

	if($height!=''){
		list($heightft,$heightin)=explode('.',$height);
	}
	return '<BR>Full Body Height: '.$heightft.' Ft'.($heightin>0 ? '&nbsp;'.$heightin.' In':'').' &nbsp;|&nbsp;Heels Height: '.$heelheight.' In</small>';
}


function sendordemail($id){
	$orderid=$id;
	$db = Database::getInstance();
	$mysqli = $db->getConnection();
	include("orderpg.php");

	$itemName="Order # PS".$dtyr."/".$orderid;
	$emailtext="
	<a href='".$serverurl."'><img width='253' height='80' border='0' src='http://www.payalsinghal.com/images/logo-top.jpg'></img></a><BR><BR>
	<div style='font-family:\"trebuchet MS\"; font-size:12px;'>
	Dear ".$billing_username.",<BR>
	Thank you for shopping with us at www.payalsinghal.com<BR><BR>

	We have received the following order and it will reach you within 4-6 weeks to the address specified below.<BR>Should we require any clarifications to process your order, you will receive an email from us.

	<BR><BR>$ordpg<BR><BR>
	You can track the progress of your order on our website by clicking the following link  <A HREF='".$serverurl."track-order?orderno=PS".$dtyr."/".$orderid."'>".$itemName."</A><BR>or visit the 'Track Order' section on our website.<BR><BR>
	If you have any questions regarding this order, please quote your order number and email us at  <A HREF='mailto:customersupport@payalsinghal.com'>customersupport@payalsinghal.com</A><BR>Please allow upto 48 hours for a response from our customer service representative.<BR><BR>

	Thanks,<BR>
	Payal Singhal.<BR><BR>


	Please note: <BR>
	If you made a purchase from outside of India the charge will appear on your credit card statement as 'PAYPAL *PS INC..' 
	<BR>If you made a purchase from within India the charge will appear on your credit card statement as 'ccavenue.com/charge'<BR><BR>

	</div>";



	$to=$billing_email;

	//$from="customersupport@payalsinghal.com";
	$subject="Your ".$itemName." from payalsinghal.com";

	//echo $to.'<BR>';
	//echo $emailtext;
	include("dbconfig.php");

	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$headers .= "From: $adminuser <$adminid>\r\n";
	$headers .= "Bcc: ".$technicalemail."\r\n";
	mail($to, $subject, $emailtext, $headers);

	/*$to="ps@payalsinghal.com,nirvaan@payalsinghal.com,accounts@payalsinghal.com,customersupport@payalsinghal.com";
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$headers .= 'From: Payal Singhal <'.$from.'>'."\r\n";
	mail ($to,$subject,$emailtext,$headers);*/


}

function getcatname($id){

	$db = Database::getInstance();
	$mysqli = $db->getConnection(); 
	$sql="select t.typename from ccd9types t left join ccd9prod2cat p on p.catid=t.typeid where t.opt='2' and p.prodid='$id' order by t.typeid";
	$result = query_first($sql);
	return $result[0];
}

function checkgiftcat($id){

	$db = Database::getInstance();
	$mysqli = $db->getConnection(); 
	$sql="select catid from ccd9prod2cat p where p.prodid='$id' and p.catid='149'"; //gift category
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
	$sql="select count(*) as cnt from ccd9prod2cat where prodid='$id' and catid in (25,149) "; //Accessories & Gift Cards
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
	if($cur=='')$cur=$_SESSION['myCUR'];
	return (($cur=="USD" || $cur=="US $") ? '<i class="fa fa-usd" aria-hidden="true"></i>' : '<i class="fa fa-inr" aria-hidden="true"></i>');
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
	if($row['isdiscount']==1 && $row['proddisc']>0){
		$offerprice=$prodprice-round($prodprice*$row['proddisc']/100,0);
		$isoffer=1;
	}
	$prodprice=number_format($prodprice);
	if($offerprice>0 || $isoffer==1)$prodprice='<strike>'.$prodprice.'</strike>';

	//return money_format('%.0n', $prodprice).' '.($offerprice>0 ? $offerprice : '');
	return array($cur.$prodprice.' '.($offerprice>0 ? '&nbsp;&nbsp;'.$cur.number_format($offerprice) : ''), $offerprice);
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
	return ($shipprice>0 ? $cur.$shipprice : '*All prices').' include packing, shipping & handling.';
}

function getprodurl($url,$opt=''){
	if($opt==''){
		$db = Database::getInstance();
		$mysqli = $db->getConnection();

		$query=query_first("select t.typevalue from ccd9types t join ccd9prod2cat p2c on p2c.catid=t.typeid join ccd9products p on p2c.prodid=p.prodid where p.produrl='$url' order by t.typeid");
		$opt=$query['typevalue'];
	}
	return '//www.'.$GLOBALS['serverdomain'].'/jan2018/'.$opt.'/'.$url; //$opt
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

	$query=query_first("select IFNULL(sum(prodqty),0) as cnt from ccd9cart where sessionid='".session_id()."' and orderid='0'");
	echo $query['cnt'];
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
	var $_username = "payals_db2018";
	var $_password = "[CL]tF&zL@uq";
	var $_database = "payals_2018";
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
			trigger_error("Failed to conencto to MySQL: " . mysql_connect_error(),
				 E_USER_ERROR);
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