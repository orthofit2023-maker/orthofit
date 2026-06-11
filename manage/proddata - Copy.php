<?php 
session_start();
ini_set('max_execution_time', 3000);
if(!isset($_SESSION['loginid'])){
	header("location:login.php");
}
include("db5conn.php");

if($_GET['dl']=='csv'){

	header('Content-Type: text/csv; charset=utf-8');
	header("Content-disposition:attachment;filename=download.csv"); 

	// create a file pointer connected to the output stream
	$output = fopen('php://output', 'w');

	// output the column headings
	fputcsv($output, array('Code','Keys','Alt text','MAIN MENU','PRODUCT TYPE','FINISHING','Hue','Garment Colors','Title','Details','Sizes (standard Sizes)','Measurement Chart Category',' Related Products','INR','USD','POUND','EURO','In the box/What you get','Care Instructions','Shipping time',' Weight of the product','Size of the packing','Shipping cost INR','Shipping cost USD','Free shipping (Yes/No)','Discount %','Discount start date','Discount end date','Special/offer price IND','Special/offer price USD','Special/offer start date','Special/offer end date','Activate product start date','Active (Yes/No)','Product sort no.','Cross Sell','Related accessories','matching products for kids','combo product codes','zone','discount text','size guide'));


	$sql="select * from ccd9products order by prodid";
	$result = $mysqli->query($sql);
	while($row = $result->fetch_array()){ //$csv=$csv.
		fputcsv($output, array($row['prodcode'],$row['prodkeys'],$row['prodalt'],getlist($row['prodid'], 'ccd9prod2cat', 'catid'),getlist($row['prodid'], 'ccd9prod2type1', 'typeid'),getlist($row['prodid'], 'ccd9prod2type2', 'typeid'),getlist($row['prodid'], 'ccd9prod2type3', 'typeid'),$row['prodcolor'],$row['prodname'],$row['proddesc'],$row['prodsize'],getfieldvalue($row['prodmeas'], 'typevalue', 'typeid', 'ccd9types', " and opt='1'"),getprodlist($row['prodid'], 'ccd9prodrelated', 'relprodid'),$row['prodprice'],$row['usdprice'],$row['poundprice'],$row['europrice'],$row['prodbox'],$row['prodcare'],$row['shiptime'],$row['prodwgt'],$row['prodpack'],$row['shipprod'],$row['shipusd'],($row['freeship']==1 ? 'Yes' : ''),$row['proddisc'],inddate($row['discfrdate']),inddate($row['disctodate']),$row['offerprod'],$row['offerusd'],inddate($row['offerfrdate']),inddate($row['offertodate']),inddate($row['prodfrdate']),($row['prodstatus']==1 ? 'Yes' : 'No'),$row['sortby'],getprodlist($row['prodid'], 'ccd9prodxsell', 'relprodid'),getprodlist($row['prodid'], 'ccd9prodacces', 'relprodid'),getprodlist($row['prodid'], 'ccd9prod4kids', 'relprodid'),getprodlist($row['prodid'], 'ccd9prodcombo', 'relprodid'),(trim($row['zone'])=='' ? "ALL" : $row['zone']),$row['disctext'],$row['sizeguide']));

	}
	fseek($output, 0);
	fpassthru($output);
	exit();
}

function getprodlist($id, $dbname, $fld){
	$retval='';
	$db = Database::getInstance();
	$mysqli = $db->getConnection();
	$result = $mysqli->query("select t.prodcode from $dbname p join ccd9products t on t.prodid=p.$fld where p.prodid='$id'");
	while($rescon = $result->fetch_array()){
		if($retval!=""){
			$retval=$retval.','.$rescon["prodcode"];
		}else{
			$retval=$rescon["prodcode"];
		}
	}
	return $retval;


}

function getlist($id, $dbname, $fld){
	$retval='';
	$db = Database::getInstance();
	$mysqli = $db->getConnection();
	$result = $mysqli->query("select t.typename from $dbname p join ccd9types t on t.typeid=p.$fld where p.prodid='$id'");
	while($rescon = $result->fetch_array()){
		if($retval!=""){
			$retval=$retval.','.dbval($rescon["typename"]);
		}else{
			$retval=dbval($rescon["typename"]);
		}
	}
	return $retval;


}

//exit();

$arr=array( 'prodcode', 'prodkeys', 'prodalt', 'prodcats', 'type1', 'type2', 'type3', 'prodcolor', 'prodname', 'proddesc', 'prodsize', 'prodmeas', 'prodrelated', 'prodprice', 'usdprice', 'poundprice', 'europrice', 'prodbox', 'prodcare', 'shiptime', 'prodwgt', 'prodpack', 'shipprod', 'shipusd', 'freeship', 'proddisc', 'discfrdate', 'disctodate', 'offerprod', 'offerusd', 'offerfrdate', 'offertodate', 'prodfrdate', 'prodstatus', 'sortby', 'crosssell', 'accessories', 'forkids', 'combocodes', 'zone', 'disctext', 'sizeguide');

if($_POST['session']!="" && checkuserref()){
	$fieldseparator = ",";
	$lineseparator = "\n";
	$addauto = 0;

	$file=$_POST['pinfile'];
	$tmpname = $_FILES['pinfile']['tmp_name'];
	$file = fopen($tmpname, 'r');
	$size = filesize($tmpname);
	if(!$size) {
		$msg= "File is empty.\n";
		//exit;
	}
	$cnti=0;$cntu=0;
	$lines = 0;
	$queries = "";
	$linearray = array();
	while(($linearray = fgetcsv($file, 5000, ",")) !== false){

		$lines++;
		$inputyes=0;
		$inputyes=1;

		$linemysql = implode("','",$linearray);
		//echo '<BR> <BR>'.$linemysql.'<BR>';

		if($inputyes==1 && $linearray[0]!='' && $linearray[0]!='Code'){
			if(trim($linearray[26])!='')$linearray[26]=sqldate($linearray[26]);
			if(trim($linearray[27])!='')$linearray[27]=sqldate($linearray[27]);
			if(trim($linearray[30])!='')$linearray[30]=sqldate($linearray[30]);
			if(trim($linearray[31])!='')$linearray[31]=sqldate($linearray[31]);
			if(trim($linearray[32])!='')$linearray[32]=sqldate($linearray[32]);
			if(trim($linearray[11])!='')$linearray[11]=getfieldvalue(trim($linearray[11]), 'typeid', 'typevalue', 'ccd9types', " and opt='1'");
			if(trim($linearray[32])=='')$linearray[32]=date("Y-m-d");
			if(trim($linearray[39])=='')$linearray[39]='ALL'; //zone
			if(trim($linearray[10])=='')$linearray[10]='NA'; //sizes
			if(trim($linearray[33])=='Yes' || trim($linearray[33])==''){
				$linearray[33]=1;
			}else{
				$linearray[33]=0;
			}
			if(trim($linearray[24])=='Yes' || trim($linearray[24])==''){
				$linearray[24]=1;
			}else{
				$linearray[24]=0;
			}
			
			

			$sqlin="select prodid, prodcode from ccd9products where prodcode='".trim($linearray[0])."'";
			$row=query_first($sqlin);
			if($row[0]>0){
				$prodid=$row[0];

				$query = "update ccd9products set ";
				for($n=0;$n<42;$n++){
					$query =$query.$arr[$n]."='".inpval($linearray[$n])."', ";
				}
				$query =$query." loginid='".$_SESSION['loginid']."' where prodid='$prodid'";

				//echo "<BR>".$query."<BR><BR>";
				$mysqli->query($query);
				$cntu++;
				
				
			}else{
				$produrl=geturl(trim($linearray[8]), trim($linearray[0]));

				$query = "insert into ccd9products (";
				for($n=0;$n<42;$n++){
					$query =$query.$arr[$n].", ";
				}
				$query =$query." entrydate, loginid, produrl) values(";

				for($n=0;$n<42;$n++){
					$query =$query ."'".inpval($linearray[$n])."', ";
				}
				$query =$query ." CURDATE(), '".$_SESSION['loginid']."', '$produrl')";
				//echo "<BR>".$query;

				$cnti++;

				$mysqli->query($query);
				$prodid=mysqli_insert_id($mysqli);
			}

			//echo "<BR>prodid: ".$prodid;
			//if($lines==2)exit();
			if($prodid>0){
				//--------------product categories----------------------------
				storearray(trim($linearray[3]), $prodid, 'ccd9prod2cat');

				//--------------product type 1----------------------------
				storearray(trim($linearray[4]), $prodid, 'ccd9prod2type1');

				//--------------product type 2----------------------------
				storearray(trim($linearray[5]), $prodid, 'ccd9prod2type2');

				//--------------product type 3----------------------------
				storearray(trim($linearray[6]), $prodid, 'ccd9prod2type3');

				//--------------product related----------------------------
				storearray(trim($linearray[12]), $prodid, 'ccd9prodrelated');

				//--------------product cross sell----------------------------
				storearray(trim($linearray[35]), $prodid, 'ccd9prodxsell');

				//--------------product accessories----------------------------
				storearray(trim($linearray[36]), $prodid, 'ccd9prodacces');

				//--------------product kids----------------------------
				storearray(trim($linearray[37]), $prodid, 'ccd9prod4kids');

				//--------------product combo----------------------------
				storearray(trim($linearray[38]), $prodid, 'ccd9prodcombo');
			}


		}
		
	}
	fclose($file);

	
	$sql="select prodcode, produrl from ccd9products order by prodid";
	$result = $mysqli->query($sql);
	while($row = $result->fetch_array()){
		for($n=1;$n<20;$n++){	
			$file =$imgcodepath.dbval($row['prodcode']).strtolower(chr($n+64)).'0.jpg';
			$newfile =$imgpath.dbval($row['produrl']).'-'.strtolower(chr($n+64)).'0.jpg';
			if(file_exists($file) && !file_exists($newfile)){
				if(copy($file, $newfile)){
					//echo $file.'<BR>';
					//echo $newfile.'<BR>';
				}
			}
		}
	}

	$arrsize=array();
	$sql="SELECT prodsize FROM ccd9products where prodid in (select prodid from ccd9prod2cat where catid=148) group by prodsize";
	$result = $mysqli->query($sql);
	while($row = $result->fetch_array()){
		$str=dbval($row['prodcode']);
		if(strstr($str,',')){
			$prodarr=explode(',', $str);
		}else{
			$prodarr[0]=trim($str);
		}
		for($x=0;$x<count($prodarr);$x++){
			if(!in_array(trim($prodarr[$x]),$arrsize)){
				array_push($arrsize,trim($prodarr[$x]);

			}
		}
	}
	if(count($arrsize)>0){
		$mysqli->query("update ccd9types set typename='".implode(",",$arrsize)."' where opt='99' ");
	}

	$mysqli->query("delete FROM ccd9prod2cat where prodid not in (select prodid from ccd9products ) ");
	$mysqli->query("delete FROM ccd9prod2type1 where prodid not in (select prodid from ccd9products ) ");
	$mysqli->query("delete FROM ccd9prod2type2 where prodid not in (select prodid from ccd9products ) ");
	$mysqli->query("delete FROM ccd9prod2type3 where prodid not in (select prodid from ccd9products ) ");


	$msg= "Added ".$cnti." and ";
	$msg= $msg." Updated ".$cntu." Products";

	//echo $msg;
	header("Location:proddata.php?msg=$msg&p=$p");
	exit();
}

if($_GET['msg']!="")$msg=$_GET['msg'];
$p=$_GET['p'];
include("top.php");
?>
<div class="header">
	<h1 class="page-title">Import Products</h1>
	<ul class="breadcrumb">
	<li><a href="index.php">Home</a> </li>
	<li class="active">Import Products</li>
	</ul>

</div>
<div class="main-content">
<?php echo ($msg!="" ? '<div class="err">'.$msg."</div>" : "");?>
<div class="row">
  <div class="col-md-6">
    <form name="frm" method="post" action='proddata.php' enctype="multipart/form-data" onsubmit="return validate()">
    <div id="myTabContent" class="tab-content">
		<input type="hidden" name="session" value="<?php echo session_id()?>">
		<div class="form-group">
        <label>Import Products ('.CSV' file) (<a href="?dl=csv" target="_blank">Download file</a>)</label>
        <input type="file" name="pinfile" class="form-control">
        </div>
    </div>

    <div class="form-group">
      <button type="submit" class="btn btn-primary" name="btnsubmit"><i class="fa fa-save"></i> Save</button>
    </div>
    </form>
  </div>
</div>

<SCRIPT LANGUAGE="JavaScript">
<!--
function validate(){
	if(document.frm.pinfile.value==""){
		alert("Please select CSV file");
		document.frm.pinfile.focus();
		return false;
	}
	document.frm.btnsubmit.disabled=true;
}
//-->
</SCRIPT>
<?php include("bot.php");?>