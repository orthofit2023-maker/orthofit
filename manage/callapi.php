<?php
session_start();
include("db5conn.php");

//$result = array();
$arrdata = array ('id'=>'0');
$msg='Invalid request!'; //.$studentid.$postexam
$status='SUCCESS';
$apireq=$_GET['req'];
$cat=inpval($_GET['cat']);
/*
if($_POST['apikey']=='aesoc15jun2018'){



if($_GET['loginid']!="")$loginid=intval($_GET['loginid']);
if($_POST['isStudent']!="")$isStudent=$_POST['isStudent'];
if($_POST['id']!=""){
	if($isStudent=='true'){
		$studentid=intval($_POST['id']);
	}else{
		$teacherid=intval($_POST['id']);
	}
}

*/
if($cat!=''){
	$result=$mysqli->query("select pageid from ccd9pages where pageurl='".inpval($cat)."'");
	$count = mysqli_num_rows($result);
	if($inf = $result->fetch_array()){
		$apireq='PAGE';
	}else{
		$apireq='LIST';
	}
}

if($apireq=='PAGE'){
	$opt=trim($_GET['opt']);
	$arrdata = array();
	$result=$mysqli->query("select description from ccd9pages where pageurl='$opt'");
	$inf = $result->fetch_array();
	$json = array("status"=> $status, "message"=> $opt, "data"=> $inf['description'] );
}else if($apireq=='MENU1'){
	$opt=trim($_GET['opt']);
	$arrdata = array();$x=0;
	$sql="select t.typevalue, t.typename from ccd9types t where t.opt='2' order by CAST(t.typevalue1 AS UNSIGNED )";
	$result = $mysqli->query($sql);
	while($inf = $result->fetch_array()){
		$arrdata[$x]=array("no"=>$x,"url"=>$inf['typevalue'], "name"=>$inf['typename']);
		$x++;
	} 
	$json = array("status"=> $status, "message"=> $opt, "data"=> $arrdata );
}else if($apireq=='MENU'){
	$opt=trim($_GET['opt']);
	$arrdata = array();$x=0;
	$sql="select t.typevalue, t.typename from ccd9types t where t.typevalue2='1' and t.opt='2' order by CAST(t.typevalue1 AS UNSIGNED )";
	$result = $mysqli->query($sql);
	while($inf = $result->fetch_array()){
		$arrdata[$x]=array("no"=>$x,"url"=>$inf['typevalue'], "name"=>$inf['typename']);
		$x++;
	} 
	$json = array("status"=> $status, "message"=> $opt, "data"=> $arrdata );
}else if($apireq=='HOME'){
	$opt=trim($_GET['opt']);
	$arrdata = array();$x=0;
	$sql="CALL homeBanner1('".($_SESSION['myCUR']=="US $" ? 'USD' : 'INR')."')";
	$result = $mysqli->query($sql);
	while($inf = $result->fetch_array()){
		list($banimg,$banurl)=explode(',',$inf['typename']);
		$arrdata[$x]=array("no"=>$x,"url"=>$banurl, "banjpg"=>$stackurl.'images/'.$banimg, "banwebp"=> $stackurl.'images/banner101-'.$inf['typeid'].'.webp');
		$x++;
	} 
	mysqli_free_result($result); 
	mysqli_next_result($mysqli); 

	$arrimgs = array();$x=0;
	$sql="CALL homeBanner3('".($_SESSION['myCUR']=="US $" ? 'USD' : 'INR')."')";
	$result = $mysqli->query($sql);
	while($inf = $result->fetch_array()){
		list($banimg,$banurl)=explode(',',$inf['typename']);
		$arrimgs[$x]=array("no"=>$x,"url"=>$banurl, "banjpg"=>$stackurl.'images/'.$banimg, "banwebp"=> $stackurl.'images/banner103-'.$inf['typeid'].'.webp');
		$x++;
	} 

	mysqli_free_result($result); 
	mysqli_next_result($mysqli); 

	$arrprods = array();$x=0;
	$sqllist="CALL prodGroup('INR','18','0','0','0')";
	$result = $mysqli->query($sqllist);
	while($inf = $result->fetch_array()){
		$arrprods[$x]=array("no"=>$x,"id"=>$inf["prodid"], "url"=>'new-arrivals/'.$inf['produrl'], "name"=>$inf['prodname'], "price"=>$inf['prodprice'], "imgjpg"=>$stackurl.'collection/'.$inf['produrl'].'-a3.jpg', "imgwebp"=> $stackurl.'collection/'.$inf['produrl'].'-a3.webp');
		$x++;
		if($x==8)break;
	} 
	mysqli_free_result($result); 
	mysqli_next_result($mysqli);

	$arrmenu = array();$x=0;
	$sql="select t.typevalue, t.typename from ccd9types t where t.typevalue2='1' and t.opt='2' order by CAST(t.typevalue1 AS UNSIGNED )";
	$result = $mysqli->query($sql);
	while($inf = $result->fetch_array()){
		$arrmenu[$x]=array("no"=>$x,"url"=>$inf['typevalue'], "name"=>$inf['typename']);
		$x++;
	}

	mysqli_free_result($result); 
	mysqli_next_result($mysqli);

	$json = array("status"=> $status, "message"=> $opt, "data"=> $arrdata, "banners"=> $arrimgs, "products"=> $arrprods, "topmenu"=> $arrmenu );

}else if($apireq=='PRODUCT'){
	$opt=trim($_GET['opt']);
	$cat=inpval($_GET['pcat']);

	$row= query_first("select c.typevalue, c.typeid, c.typename from ccd9types c where c.typevalue='$cat'");
	$catname=dbval($row['typename']);
	$caturl=dbval($row['typevalue']);
	$catid=intval($row['typeid']);

	$arrmenu = array();$x=0;
	$sql="select t.typevalue, t.typename from ccd9types t where t.typevalue2='1' and t.opt='2' order by CAST(t.typevalue1 AS UNSIGNED )";
	$result = $mysqli->query($sql);
	while($inf = $result->fetch_array()){
		$arrmenu[$x]=array("no"=>$x,"url"=>$inf['typevalue'], "name"=>$inf['typename']);
		$x++;
	}

	mysqli_free_result($result); 
	mysqli_next_result($mysqli);

	$arrdata = array();
	$result=$mysqli->query("CALL getProd('$opt','0')");
	$inf = $result->fetch_array();
	$arrdata = array("id"=>$inf["prodid"], "code"=>$inf['prodcode'], "name"=>dbval($inf['prodname']), "desc"=>dbval($inf['proddesc']), "price"=>$inf['prodprice'], "video"=>trim($inf['video1']), "prodmeas"=>$inf['prodmeas'], "alt"=>$inf['prodalt']);
	$prodid=$inf["prodid"];
	$prodprice=$inf["prodprice"];

	$arrimg = array();
	for($n=1;$n<20;$n++){
		$file =$imgpath.dbval($inf['produrl']).'-'.strtolower(chr($n+64)).'0.jpg';
		if(file_exists($file)){
			array_push($arrimg,strtolower(chr($n+64)));
		} 
	}

	mysqli_free_result($result); 
	mysqli_next_result($mysqli);


	$sizedata=array();

	$prodsize=$inf['prodsize'];
	if(strstr($prodsize,'KIDS'))$prodsize=str_replace('KIDS:','',$prodsize);
	if(strstr($prodsize,',')){
		$arrsize=explode(',', dbval($prodsize));
	}else{
		$arrsize[0]=dbval($prodsize);
	}

	for($x=0;$x<count($arrsize);$x++){
		$sizedata[$x]=array("value"=>$arrsize[$x], "label"=>$arrsize[$x]);
	}

	$arrprods = array();
	$sqllist="CALL prodGroup('INR','$catid','0','$prodprice','$prodid')";
	$result = $mysqli->query($sqllist); $x=0;
	while($inf = $result->fetch_array()){
		$arrprods[$x]=array("no"=>$x,"id"=>$inf["prodid"], "url"=>'/'.$caturl.'/'.$inf['produrl'].'#', "name"=>$inf['prodname'], "price"=>$inf['prodprice'], "imgjpg"=>$stackurl.'collection/'.$inf['produrl'].'-a3.jpg', "imgwebp"=> $stackurl.'collection/'.$inf['produrl'].'-a3.webp');
		$x++;
	} 
	mysqli_free_result($result); 
	mysqli_next_result($mysqli);

	$json = array("status"=> $status, "message"=> $opt, "data"=> $arrdata, "imgs"=> $arrimg, "sizes"=> $sizedata, "prodlist"=> $arrprods, "topmenu"=> $arrmenu );

}else if($apireq=='LIST'){

	$arrdata = array();$x=0;
	$type1=intval($_GET['t1']); $type2=intval($_GET['t2']); $type3=intval($_GET['t3']); $sort=trim($_GET['t4']); 
	$sortsize='';
	
    $sort="sort0";
	$row=query_first("select  c.typename, c.typeid from ccd9types c where c.typevalue='".inpval($cat)."'");
	$listtitle=trim($row['typename']);
	if($listtitle=='')$listtitle=$cat;
	$catid=intval($row['typeid']);
	if($catid==0)$catid=18;
	//$sqllist="CALL prodList1('INR','$catid','$type1','$type2','$type3','$sort','0','$sortsize')";
	$sqllist="CALL prodList1('INR','$catid','$type1','$type2','$type3','$sort','0','$sortsize')";
	$result = $mysqli->query($sqllist); 
	$listcount = mysqli_num_rows($result);
	while($inf = $result->fetch_array()){
		$arrdata[$x]=array("no"=>$x,"id"=>$inf["prodid"], "url"=>$inf['produrl'], "name"=>$inf['prodname'], "price"=>$inf['prodprice'],"imgjpg"=>$stackurl.'collection/'.$inf['produrl'].'-a3.jpg', "imgwebp"=> $stackurl.'collection/'.$inf['produrl'].'-a3.webp');
		$x++;
		if($x==16)break;
	} 
	mysqli_free_result($result); 
	mysqli_next_result($mysqli); 

	$type1data=array();$x=0;
	$type1data[$x]=array("value"=>0, "label"=>'Select Product Type');
	$sqllist="CALL listType11('$catid','$type2','$type3')";
	$result = $mysqli->query($sqllist); 
	while($inf = $result->fetch_array()){$x++;
		$type1data[$x]=array("value"=>$inf["typeid"], "label"=>$inf['typename']);
		
	}
	mysqli_free_result($result); 
	mysqli_next_result($mysqli); 

	$type2data=array();$x=0;
	$sqllist="CALL listType12('$catid','$type1','$type3')";
	$type2data[$x]=array("value"=>0, "label"=>'Select Finishing');
	$result = $mysqli->query($sqllist); 
	while($inf = $result->fetch_array()){$x++;
		$type2data[$x]=array("value"=>$inf["typeid"], "label"=>$inf['typename']);
		
	}
	mysqli_free_result($result); 
	mysqli_next_result($mysqli); 

	$type3data=array();$x=0;
	$sqllist="CALL listType13('$catid','$type1','$type2')";
	$type4data[$x]=array("value"=>0, "label"=>'Select Hue');
	$result = $mysqli->query($sqllist); 
	while($inf = $result->fetch_array()){$x++;
		$type3data[$x]=array("value"=>$inf["typeid"], "label"=>$inf['typename']);
		
	}
	mysqli_free_result($result); 
	mysqli_next_result($mysqli); 

	//}//---end apikey

	$arrmenu = array();$x=0;
	$sql="select t.typevalue, t.typename from ccd9types t where t.typevalue2='1' and t.opt='2' order by CAST(t.typevalue1 AS UNSIGNED )";
	$result = $mysqli->query($sql);
	while($inf = $result->fetch_array()){
		$arrmenu[$x]=array("no"=>$x,"url"=>$inf['typevalue'], "name"=>$inf['typename']);
		$x++;
	}

	mysqli_free_result($result); 
	mysqli_next_result($mysqli);

	$json = array("status"=> $status, "message"=> $msg, "data"=> $arrdata, "type1"=> $type1data, "type2"=> $type2data, "type3"=> $type3data, "listcount"=> $listcount, "listtitle"=> $listtitle, "topmenu"=> $arrmenu ); //, "subs"=>$arrsubs

}

$myJSON = json_encode($json);

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
echo $myJSON;
?>