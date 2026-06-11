<?php 
session_start();
ini_set( 'display_errors', 1 );
error_reporting( E_ALL );
ini_set('max_execution_time', 10000);
include("db5conn.php");
include_once("image.php");

$dir =$_SERVER['DOCUMENT_ROOT']. "/manage/temp/";
$cnt=0;
if (is_dir($dir)){
  if ($dh = opendir($dir)){
    while (($file = readdir($dh)) !== false){
		$prodcode=substr($file,0,strlen($file)-4);
		echo "prodcode:" . $prodcode . "<br>";
if($prodcode!=''){
		$prodimg=trim($prodcode).'.jpg';
		//$webpimg=trim($prodcode).'.webp';
		$jpgfile=$dir.$prodimg;

	//$image = imagecreatefromstring(file_get_contents($dir.$file));

	//convert E1DXINBI003200.CR2 out.png
	if(!file_exists($jpgfile)){
	exec('convert '.$dir.$file.' '.$jpgfile);

	//list($width, $height) = getimagesize($jpgfile);
	$src = imagecreatefromjpeg($jpgfile);
    $imgResized = imagescale($src , 1800, 1200);
    //imagejpeg($imgResized, $jpgfile); 

	$out = imagecrop($imgResized, ['x' => 200, 'y' => 100, 'width' => 1200, 'height' =>1200]);
	if ($out !== FALSE) {
		imagejpeg($out, $jpgfile);
		imagedestroy($out);
	}
	imagedestroy($out);
	}

	/*
		$newimg = new Image($jpgfile);
		$thumbimg=$jpgfile;
		$newimg->resize(1200,800);
		$newimg->save($thumbimg);*/
	}}}
}

exit();



$dir =$_SERVER['DOCUMENT_ROOT']. "/orthofitin/";

$sql="SELECT pageurl FROM wp_posts";

$result = $mysqli->query($sql);
while($row = $result->fetch_array()){


$url = "https://orthofit.swarom.co.in/".trim($row['pageurl']);
$page = file_get_contents($url);
//$page = preg_replace('/<nav class="navigation">(.*?)<\/nav>/', '', $page);
$outfile = $dir.trim($row['pageurl']).".html";
file_put_contents($outfile, $page);


}




exit();


/*
$imgpath=$_SERVER['DOCUMENT_ROOT']."/collection/";

$dir =$_SERVER['DOCUMENT_ROOT']. "/copy/";
$cnt=0;
if (is_dir($dir)){
  if ($dh = opendir($dir)){
    while (($file = readdir($dh)) !== false){
		$prodcode=substr($file,0,strlen($file)-5);
		echo "prodcode:" . $prodcode . "<br>";
		if($prodcode!=''){
			$n=substr($file,strlen($file)-5,1);
			$newfile =$dir.dbval($prodcode).$n.'0.jpg';
			if(!file_exists($newfile)){
				rename($dir.$file,$newfile);
				echo $file.'<br>';
				echo $newfile.'<br>';
				
			}
			if(file_exists($newfile)){
				//unlink($dir.$file);
				$cnt++;
			}
			
		}
		
    }
    closedir($dh);
  }
}

echo $cnt.'<br>';



$imgpath=$_SERVER['DOCUMENT_ROOT']."/collection/";

$dir =$_SERVER['DOCUMENT_ROOT']. "/painterly/";

if (is_dir($dir)){
  if ($dh = opendir($dir)){
    while (($file = readdir($dh)) !== false){
		if(substr($file,strlen($file)-6,1)=='a'){
			$prodcode=substr($file,0,strlen($file)-6);
			echo "prodcode:" . $prodcode . "<br>";
			for($n=10;$n>=0;$n--){
				$oldfile =$imgpath.dbval($prodcode).strtolower(chr($n+64)).'0.jpg';
				if(file_exists($oldfile)){
					$newfile=$dir.dbval($prodcode).strtolower(chr($n+66)).'0.jpg';
					copy($oldfile,$newfile);
				}

			}
		}
		
    }
    closedir($dh);
  }
}




exit();
*/

//prodcode, shiptime, shippingtype
/*

$sql="SELECT prodid, type1, shippingtype FROM ccd9products WHERE prodstatus=1 order by prodid ";

$result = $mysqli->query($sql);
while($row = $result->fetch_array()){
	//storearray(trim($row['type1']), $row['prodid'], 'ccd9prod2type1','3');
	storearray(trim($row['shippingtype']), $row['prodid'], 'ccd9prod2type9', '9');

}








$rsdata= query_first("select compid, email, username, lastname from ccd9company where compid='2'");
if ($rsdata['compid']>0){

	$emailtext=getpagedata(46);
	$emailtext=str_replace("##customername##",$rsdata['username'].' '.$rsdata['lastname'],$emailtext);
	$emailtext=str_replace("##loginname##",$rsdata['email'],$emailtext);
	$emailtext=str_replace("##loginpasswd##",$newpasswd,$emailtext);

	$subject=trim(getpagetitle(46));
	$to=trim($rsdata['email']);

	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$headers .= "From: $adminuser <$adminid>\r\n";
	//$headers .= "Bcc: ".$technicalemail."\r\n";
	mail($to, $subject, $emailtext, $headers);

	$msg = $subject.' Please check your email for more details.';

echo $msg;
}
exit();
my-default-stack-1d05ce
//clientid: 0c9ad437f68c7640560518386fbd2dfc
//Secret : 1198a4a56183332cbdab136b647cf83b9d964207dbb6071bbbd0f05a91040292

https://support.stackpath.com/hc/en-us/articles/360001134763-Purge-Files-from-the-CDN

Purge a single file via the API
Method: POST

Endpoint: https://gateway.stackpath.com/cdn/v1/stacks/{stack_id}/purge

Request body:

{
  "items":[
    {
      "url":"//<domain_name>/path/to/file"
    }
  ]
}

$dbfield="shippingtype"; //9
$dbfield="whomtype"; //8
$dbfield="occasion1"; //10
$dbfield="occasion2"; //11
$dbname="ccd9prod2type11";
$opt=11;
$sql="select prodid, occasion2 from ccd9products where occasion2!='' order by prodid desc"; // where prodcode like 'PS-GC%'
$result = $mysqli->query($sql);
while($row = $result->fetch_array()){

		$mysqli->query("delete from $dbname where prodid='".$row['prodid']."'");
		$prodarr=array(); 

		$str = dbval($row['occasion2']);
		//echo $str.'<br>';
		if(strstr($str,',')){
			$prodarr=explode(',', $str);
		}else{
			$prodarr[0]=$str;
		}

		for($n=0;$n<count($prodarr);$n++){
			$fieldval=trim($prodarr[$n]);

			$querysql="select typeid from ccd9types where typename='$fieldval' and opt='$opt' ";
			$resultin = $mysqli->query($querysql);
			if($rescon = $resultin->fetch_array()){
				$fieldid = $rescon[0];

				$mysqli->query("insert into $dbname values ('".$row['prodid']."', '".$rescon[0]."')");

			}else{
				echo $row['prodid'].'-'.$fieldval."<br>";
			}
		}
}

echo "completed";
*/




// Show all information, defaults to INFO_ALL
//phpinfo();

//SELECT * FROM `ccd9prod2cat` where catid=18 and prodid in (SELECT prodid FROM `ccd9products` where entrydate>='2020-08-25')

//$db = Database::getInstance();
//$mysqli = $db->getConnection(); 
//SELECT prodsize FROM `ccd9products` where prodid in (select prodid from ccd9prod2cat where catid=148) group by prodsize
//40,42,FREE,L-12,L-14,M-10,M-10,L-12,M-7,M-8,S-4,S-6,M-10,XL-16,XS-0,XS-2;

//SELECT count(*) as cnt, prodid, countrycode FROM `ccd9prodviewed` where countrycode!='' and countrycode!='-' and countrycode='US' group by countrycode, prodid order by countrycode, cnt DESC

//update `ccd9products` set prodstatus=0 where prodcode in ('PS-FW425-W','PS-FW437-T','PS-ST1463','PS-ST0985-B','PS-FW469-E','PS-ST0733-AAA','PS-ST1214-C','PS-FW641-E','PS-ST1330','PS-FW462-M','PS-FW538-M','PS-FW575-C','PS-FW659-R')

//SELECT count(*) as cnt, countrycode, prodid FROM `ccd9prodviewed` where countrycode!='' and countrycode!='-' group by countrycode, prodid order by countrycode, cnt desc


function random_strings($length_of_string) 
{ 
  
    // String of all alphanumeric character 
    $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'; 
  
    // Shufle the $str_result and returns substring 
    // of specified length 
    return substr(str_shuffle($str_result),  
                       0, $length_of_string); 
} 
  
// This function will generate 
// Random string of length 10 
//for($z=1;$z<500;$z++){
/*$i=1;
while ($i<501){
	$disccode= random_strings(10); 
	$row=query_first("select discid from ccd9discounts where `disccode`='$disccode'");
	if($row[0]>0){}else{
		$i++;
		echo $i.'-'.$disccode.'<br>';
		$sql="insert into ccd9discounts (`disccode`, `discamt`, `disctype`, `discuse`, `discexpiry`, `discdescr`) values ('$disccode', '10', '1', '0', '2021-12-31','Wed Me Good')";
		$mysqli->query($sql);
	}
}*/


/*
$myCur="INR";
$catid=trim($_GET['catid']);
$type1=trim($_GET['type1']);
$type2=trim($_GET['type2']);
$type3=trim($_GET['type3']);
$sort="c.sortby, c.entrydate desc, c.prodid";
$arrq=array();
$q=trim($_GET['q']);
if(strstr($q,' ')){
	$arrq=explode(' ',$q);
}else{
	$arrq[0]=$q;
}

$andorarr = array('in', 'or', 'with', 'and', '&', '+', '-');
if(count($arrq)>0){
	for($z=0;$z<count($arrq);$z++){
		if($arrq[$z]!='' && !in_array(trim($arrq[$z]), $andorarr)){
			$sqlsrch=$sqlsrch." and ( c.prodcode like '%".inpval($arrq[$z])."%' or c.prodname like '%".inpval($arrq[$z])."%' or c.proddesc like '%".inpval($arrq[$z])."%') ";
		}
	}
}

//echo $sqlsrch;

$sql="CALL prodList('$myCur','$catid','$type1','$type2','$type3','$sort','".$_SESSION['compid']."')";
$sql="CALL listType1('$catid')";

$result = $mysqli->query($sql);
$numrows=mysqli_num_rows($result);
echo $numrows.'<br>';
if($numrows>0){
while($row = $result->fetch_array()){
	echo $row['typeid'].'-'.$row['typename'].'<br>';
}
}
mysqli_free_result($result); 
mysqli_next_result($mysqli); 

$sql="CALL listType2('$catid')";

$result = $mysqli->query($sql);
$numrows=mysqli_num_rows($result);
echo $numrows.'<br>';
if($numrows>0){
while($row = $result->fetch_array()){
	echo $row['typeid'].'-'.$row['typename'].'<br>';
}
}

BEGIN
SELECT c.*, IFNULL(t1.catid,'0') as noguidecat, IFNULL(w.wishid,'0') as wishid, IF(CURDATE() between c.offerfrdate and c.offertodate, '1', '0') as isoffer, IF(CURDATE() between c.discfrdate and c.disctodate, '1', '0') as isdiscount from ccd9products c 
left join ccd9prod2cat t on t.prodid=c.prodid 
left join ccd9prod2type1 v1 on v1.prodid=c.prodid 
left join ccd9prod2type2 v2 on v2.prodid=c.prodid
left join ccd9prod2type3 v3 on v3.prodid=c.prodid
left join ccd9wishlist w on w.prodid=c.prodid and w.compid=myCompid and myCompid>0
left join ccd9prod2cat t1 on t1.prodid=c.prodid  and t1.catid in (25,149,153)
where c.prodstatus='1' and c.prodid not in (191,192,193,131,132,133,135,136,134,137,164,165,1113,1114,1115) 
and (c.zone=myCUR or c.zone='ALL') 
and (t.catid =myCatid OR myCatid='')
and (v1.typeid =myTypeid1 OR myTypeid1='')
and (v2.typeid =myTypeid2 OR myTypeid2='')
and (v3.typeid =myTypeid3 OR myTypeid3='')
group by c.prodid
order by 
CASE WHEN mySort = 'sort0'  THEN c.sortby END,
CASE WHEN mySort = 'sort0'  THEN c.entrydate END DESC,
CASE WHEN mySort = 'sort0'  THEN c.prodid END,
CASE WHEN mySort = 'sort1'  THEN IF(c.offerprod>0,IF(myCUR!='INR',c.offerusd,c.offerprod),IF(myCUR!='INR',c.prodprice,c.usdprice)) END,
CASE WHEN mySort = 'sort2'  THEN IF(c.offerprod>0,IF(myCUR!='INR',c.offerusd,c.offerprod),IF(myCUR!='INR',c.prodprice,c.usdprice)) END DESC,
CASE WHEN mySort = 'sort3'  THEN IF(myCUR!='INR',round((c.usdprice-c.offerusd)*100/c.usdprice,0),round((c.prodprice-c.offerprod)*100/c.prodprice,0)) END,
CASE WHEN mySort = 'sort4'  THEN IF(myCUR!='INR',round((c.usdprice-c.offerusd)*100/c.usdprice,0),round((c.prodprice-c.offerprod)*100/c.prodprice,0)) END DESC
;
        
END



$imgoldcodepath=$_SERVER['DOCUMENT_ROOT']."/2013/collection/";

BEGIN
SELECT c.*, IFNULL(t1.catid,'0') as noguidecat, IFNULL(w.wishid,'0') as wishid, IF(CURDATE() between c.offerfrdate and c.offertodate, '1', '0') as isoffer, IF(CURDATE() between c.discfrdate and c.disctodate, '1', '0') as isdiscount from ccd9products c 
left join ccd9prod2cat t on t.prodid=c.prodid 
left join ccd9prod2type1 v1 on v1.prodid=c.prodid 
left join ccd9prod2type2 v2 on v2.prodid=c.prodid
left join ccd9prod2type3 v3 on v3.prodid=c.prodid
left join ccd9wishlist w on w.prodid=c.prodid and (w.compid=myCompid OR myCompid='')
left join ccd9prod2cat t1 on t1.prodid=c.prodid  and t1.catid in (25,149,153)
where c.prodstatus='1' and c.prodid not in (191,192,193,131,132,133,135,136,134,137,164,165,1113,1114,1115) 
and (c.zone=myCUR or c.zone='ALL') 
and (t.catid =myCatid OR myCatid='')
and (v1.typeid =myTypeid1 OR myTypeid1='')
and (v2.typeid =myTypeid2 OR myTypeid2='')
and (v3.typeid =myTypeid3 OR myTypeid3='')
group by c.prodid
order by mySort;
END
*/

//$orderid=2134;
//sendordemail($orderid);
//processgiftcard($orderid);
//redimdiscountemail($orderid);
//remsaleprod($orderid);

//-------code disabled------------------
//'PS-DF037','PS-DF038','PS-DF039','PS-DF042','PS-DF056','PS-FW434','PS-FW458','PS-FW326','PS-FW327','PS-FW328','PS-FW329','PS-FW330','PS-FW331','PS-FW162','PS-FW160','PS-FW159','PS-FW342','PS-FW140','PS-FW139','PS-FW119','PS-FW104','PS-FW170','PS-FW171','PS-FW172','PS-FW173','PS-FW174','PS-FW158','PS-ST1200','PS-ST1201','PS-FW111','PS-FW153','PS-ST1290-1','PS-FW564-B-1','PS-FW522-D-1','PS-FW398-C-1','PS-ST0957-B-2','PS-FW479-B-1','PS-FW555-A-1','PS-FW487-A-1','PS-FW537-2','PS-ST0805-1','PS-FW418-E-1,'PS-MN062'
//---------sale images missing----------
//PS-TU0476-2','PS-TU1019-3','PS-ST0814-V-2','PS-ST0814-Z-1','PS-KF0018-B-1','PS-TU1400-2','PS-TU1405-1','PS-TU1389-2','PS-TU1415-3','PS-DR0008-1','PS-KP0022-1','PS-FW646-1','PS-ST1194-C-1','PS-ST1328-1','PS-ST1406-D-1','PS-ST1418-A-1','PS-TP0016-1','PS-ST0733-ZZ-1','PS-FW477-J-1','PS-FW575C-1','PS-FW469-C-1','PS-ST1247-C-1','PS-ST1341-A-1','PS-ST1288-A-1','PS-KS0001-1','PS-ST1281-2','PS-ST1432-1','PS-FW360-15','PS-FW486-F-1','PS-ST0892-A-1','PS-FW535-C-1','PS-FW387-1','PS-FW502-C-1','PS-FW536-C-1','PS-FW563-A-1','PS-FW522-D-1','PS-FW398-C-1','PS-FW555-A-1','PS-FW487-A-1','PS-ST0805-1','PS-FW479-B-1' ,'PS-ST1328-1','PS-ST1247-C-1'

//update `ccd9products` set prodstatus=0 where prodcode in ('PS-DR0008-1','PS-ST1418-A-1','PS-TP0016-1','PS-FW477-J-1','PS-FW575C-1','PS-ST1288-A-1','PS-FW486-F-1','PS-ST0892-A-1','PS-FW387-1','PS-ST1193-E-1','PS-FW484-E-1')

//update `ccd9products` set entrydate='2020-04-21' where entrydate='2020-05-06'

//update  `ccd9products` set prodstatus=0  where prodcode in ('PS-MN005','PS-MN018','PS-MN001','PS-MN002','PS-MN004','PS-MN009','PS-MN003','PS-MN014','PS-MN019')

//SELECT * FROM `ccd9cart` where orderid in (SELECT orderid FROM `cart` where orderid>0 and orderstatus=0) and orderid in(select orderid from ccd9orders where status=0) and status=0

//update `ccd9orderhistory` h join  ccd9orders o on o.orderid=h.orderid set h.datemodified=o.orddate where o.orddate <'2020-04-30'
//update `ccd9orderhistory` h join  ccd9orders o on o.orderid=h.orderid set h.comments=o.orderreply, h.datemodified=o.orddate where o.orddate <'2020-04-30'

//echo getRandomString(8);

$n=0;
/*
$sql="select p.prodcode, p.produrl from ccd9products p join ccd9prod2cat c on p.prodid=c.prodid where c.catid='148' group by p.prodid"; // where prodcode like 'PS-GC%'
$result = $mysqli->query($sql);
while($row = $result->fetch_array()){
		//$file =$imgcodepath.dbval($row['prodcode']).'a0.jpg';
		$newfile =$imgcodepath.dbval($row['produrl']).'-'.strtolower(chr(65)).'0.jpg';
		if(!file_exists($newfile)){
			
				echo $row['prodcode']."','";
		}
}
*/

/*

$sql="select prodcode, produrl from ccd9products where prodcode in( 'PS-DR1001') order by prodid"; //where prodcode ='PYS-300931'   
$result = $mysqli->query($sql);
while($row = $result->fetch_array()){
	for($n=1;$n<20;$n++){
		$oldfile =$imgoldcodepath.dbval($row['prodcode']).strtolower(chr($n+64)).'0.jpg';
		$file =$imgcodepath.dbval($row['prodcode']).strtolower(chr($n+64)).'0.jpg';
		$newfile =$imgcodepath.dbval($row['produrl']).'-'.strtolower(chr($n+64)).'0.jpg';
		for($m=0;$m<4;$m++){
			$delfile =$imgpath.dbval($row['produrl']).'-'.strtolower(chr($n+64)).$m.'.jpg';
			if(file_exists($delfile)){
				unlink($delfile);
				echo 'deleted... '.$delfile.'<BR>';
			}
		}
		
		if(file_exists($file) && !file_exists($newfile)){
			if(copy($file, $newfile)){
				echo $file.'<BR>';
				echo $newfile.'<BR>';
			}
		}else if(file_exists($oldfile) && !file_exists($newfile)){
			if(copy($oldfile, $newfile)){
				echo $oldfile.'<BR>';
				echo $newfile.'<BR>';
			}
		}
	}
}

*/

/*$sql="SELECT userid, email, passwd, username, lastname, dob, gender, address, address1, city, state, zip, countryid, phone, regdate, status, emailcode FROM users where userid<3213";
$result = $mysqli->query($sql);
while($row = $result->fetch_array()){$n++;
	$username=inpval($row['username']);
	$lastname=inpval($row['lastname']);
	$phone =  inpval($row['phone']);
	$passwdold = trim($row['passwd']);
	$address=inpval($row['address']);
	$address1 =inpval($row['address1']);
	$city=inpval($row['city']);
	$phone=inpval($row['phone']);
	$zipcode=inpval($row['zip']);
	$state=inpval($row['state']);
	$city =inpval($row['city']);
	$state =inpval($row['state']);
	$countryid =inpval($row['countryid']);
	$email = inpval($row['email']);
	$regdate =inpval($row['regdate']);
	$compid=intval($row['userid']);

	$passwd = encyrptPassword($passwdold);

	$sql="insert into ccd9company (compid, username, lastname, passwd, passwdold, phone, email, status, regdate) values ('$compid', '$username', '$lastname', '$passwd', '$passwdold', '$phone', '$email', '1', '$regdate')";
	$mysqli->query($sql);
	//$compid=mysqli_insert_id($mysqli);
	//if($compid>0){
		$sql="insert into ccd9address (username, lastname, compid, countryid, phone, address, address1, city, zipcode, state, email) values ('$username', '$lastname', '$compid', '$countryid', '$phone', '$address', '$address1', '$city', '$zipcode', '$state', '$email')";
		$mysqli->query($sql);
		$addressid= mysqli_insert_id($mysqli);
		$mysqli->query("update ccd9company set addressid='$addressid' where compid='$compid'");
	//}
}
*/


/*$sql="SELECT cartid, userid, prodid, prodname, prodprice, prodsize, prodcolor, prodqty, prodcur, entrydate, usersession, comments, orderid, orderstatus FROM cart WHERE 1 order by cartid ";

$result = $mysqli->query($sql);
while($row = $result->fetch_array()){$n++;
	$finalprice = $row['prodprice'];

	$sql="insert into ccd9cart (cartid, compid, prodid, prodname, sessionid, prodcur, finalprice, prodqty, prodsize, prodcolor, orderid, comments, status) values ('".intval($row['cartid'])."', '".intval($row['userid'])."', '".intval($row['prodid'])."', '".inpval($row['prodname'])."', '".$row['usersession']."', '".$row['prodcur']."', '$finalprice', '".$row['prodqty']."', '".$row['prodsize']."', '".$row['prodcolor']."', '".$row['orderid']."', '".inpval($row['comments'])."', '".intval($row['orderstatus'])."')";
	$mysqli->query($sql);
}
*/


/*
$sql="select orderid, userid, username, lastname, address, address1, city, state, zip, countryid, phone, busername, blastname, baddress, baddress1, bcity, bstate, bzip, bcountryid, bphone, ordertotal, ordership, orderstatus, orderdate, email, tx, orderreply, trackcode, tracklink, discid, disccode, discamt, disctype, disctot from orders WHERE 1 order by orderid";

$result = $mysqli->query($sql);
while($row = $result->fetch_array()){$n++;

	$sql="insert into ccd9orders (orderid, compid, username, lastname, address, address1, city, zipcode, state, country, phone, email, billing_username, billing_lastname, billing_address, billing_address1, billing_city, billing_zipcode, billing_state, billing_country, ordtotal, shippingamt, status, orddate, tx, trackcode) values ('".intval($row['orderid'])."', '".intval($row['userid'])."', '".inpval($row['username'])."', '".inpval($row['lastname'])."', '".$row['address']."', '".$row['address1']."', '".$row['city']."', '".$row['zip']."', '".$row['state']."', '".$row['countryid']."', '".$row['phone']."', '".$row['email']."', '".$row['busername']."', '".$row['blastname']."', '".$row['baddress']."', '".$row['baddress1']."', '".$row['bcity']."', '".$row['bzip']."', '".$row['bstate']."', '".$row['bcountryid']."', '".$row['ordertotal']."', '".$row['ordership']."', '".$row['orderstatus']."', '".$row['orderdate']."', '".$row['tx']."', '".$row['trackcode']."')";

	$mysqli->query($sql);

	$sql="insert into ccd9orderhistory (orderid, comments, statusid, loginid, sendemail, datemodified) values ('".intval($row['orderid'])."', '".inpval($row['orderreply'])."', '".$row['orderstatus']."', '0', '1', '".trim($row['orderdate'])."')";
	$mysqli->query($sql);
}
*/
//-----code to update ordcur as per cartcur
//$mysqli->query("update `ccd9orders` o join ccd9cart c on o.orderid=c.orderid set o.ordcur=c.prodcur"); 
//$mysqli->query("update ccd9products set proddesc='' where proddesc='0'"); 
//$mysqli->query("update ccd9products n join products p on n.prodcode=p.prodcode set n.prodstatus='0' where p.prodstatus='0'"); 

//echo $n;
?>