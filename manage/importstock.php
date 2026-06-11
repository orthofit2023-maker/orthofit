<?php
include("db5conn.php");

$totcol=7;

if($_POST['session']!="" && checkuserref()){

	$mysqli->query("TRUNCATE TABLE ccd9stocks");

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
	$cnti=0;$cntu=0; $cntup='';
	$lines = 0;
	$queries = "";
	$linearray = array();
	while(($linearray = fgetcsv($file, 2000, ",")) !== false){

		$lines++;
		$inputyes=0;
		$inputyes=1;

		$linemysql = implode("','",$linearray);
		//echo print_r($linearray[$x]);
		$prodcode = str_replace('/','-',inpval($linearray[1]));
		$prodsize = inpval($linearray[2]);
		$prodqty = inpval($linearray[5]);


		$sqlin="select prodid from ccd9products where prodcode='".$prodcode."' and prodstatus='1'";
		$row=query_first($sqlin);
		if($row['prodid']>0){
			//echo $prodcode;
			//echo '<br>'.$prodsize;
			//echo '<br>'.$prodqty;
			//echo '<br>------------------------------------------------------------------------<br>';


			$sqlin="select typeid, typevalue from ccd9types where typename='".$prodsize."'  and opt='107'";
			$row=query_first($sqlin);
			if($row['typeid']>0){
				$prodsize = trim($row['typevalue']);
			}

			$sqlin="select stockid, prodqty from ccd9stocks where prodcode='".$prodcode."' and prodsize='".$prodsize."'";
			$row=query_first($sqlin);
			if($row['stockid']>0){
					$mysqli->query("update ccd9stocks set prodqty=prodqty+'$prodqty' where stockid='".$row['stockid']."'");
			}else{
					$mysqli->query("insert into ccd9stocks (prodcode, prodsize, prodqty ) values ('$prodcode', '$prodsize', '$prodqty')");
			}
		}
	}

	$msg= "Products stock updated";

	//echo $msg;
	header("Location:importstock.php?msg=$msg");
}
if($_GET['msg']!="")$msg=$_GET['msg'];
$p=$_GET['p'];
include("top.php");
?>
<div class="header">
	<h1 class="page-title">Import Stock Data</h1>
	<ul class="breadcrumb">
	<li><a href="index.php">Home</a> </li>
	<li class="active">Import Stock Data</li>
	</ul>

</div>
<div class="main-content">
<?php echo ($msg!="" ? '<div class="err">'.$msg."</div>" : "");?>
<div class="row">
  <div class="col-md-6">
    <form name="frm" method="post" action='importstock.php' enctype="multipart/form-data" onsubmit="return validate()">
    <div id="myTabContent" class="tab-content">
		<input type="hidden" name="session" value="<?php echo session_id()?>">
		<div class="form-group">
        <label>Import Stock ('.CSV' file) (<a href="ps-stock-05.03.2024.csv" target="_blank">Download sample file</a>)</label>
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