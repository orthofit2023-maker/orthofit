<?php 
session_start();
ini_set('max_execution_time', 300);
if(!isset($_SESSION['loginid'])){
	header("location:login.php");
}
include("db5conn.php");
if(intval($_POST['prodid'])>0){
	$arrfile = array(); $listimg = '';
	$dir = $imgpath;

	// Open a directory, and read its contents
	if (is_dir($dir)){
		if ($dh = opendir($dir)){
			while (($file = readdir($dh)) !== false){
				if($file!='.' && $file!='..'){
				  
				  $prodseq=intval(substr($file,strrpos( $file, '.' )-1,1));
				  $arrfile[$prodseq]="https://www.orthofit.in/assets/images/products/".$file;
				  echo "filename:" . $file .'-'.$prodseq. "<br>";
				  
					  copy($imgpath.$file, $prodpath.$file);
					  unlink($imgpath.$file);
				}
			}
			closedir($dh);
		}
	}
	ksort($arrfile);
	$photos = implode(",",$arrfile);
	

	$prodid =  intval($_POST['prodid']);
	$prodcol =  trim($_POST['prodcol']);
	$prodfit =  trim($_POST['prodfit']);

		$res=query_first("SELECT typeid FROM `ccd9types` where lower(typename)='$prodfit' and opt= '3';");
		$fitid=$res['typeid'];

		$res=query_first("SELECT typeid FROM `ccd9types` where lower(typename)='$prodcol' and opt= '7';");
		$colid=$res['typeid'];

	if($prodid>0 && $colid>0 && $fitid>0 ){

		$res=query_first("SELECT prodid FROM `ccd9prodphotos` where prodid='$prodid' and type1='$fitid' and type3='$colid' ");
		if($res['prodid']>0){
			$sql="update `ccd9prodphotos` set photo='$photos' where prodid='$prodid' and type1='$fitid' and type3='$colid'";
			$mysqli->query($sql);
		}else{
			$sql="insert into `ccd9prodphotos` (prodid, type1, type3, photo) values ('$prodid', '$fitid', '$colid', '$photos')";
			$mysqli->query($sql);
		}

	}

	echo $sql;
	exit();
}

if($_GET['msg']!="")$msg=$_GET['msg'];
$p=$_GET['p'];
include("top.php");?>
<link rel="stylesheet" type="text/css" href="js/dropzone.css" />
<div class="header">
	<h1 class="page-title">Product Photos</h1>
	<ul class="breadcrumb">
	<li><a href="index.php">Home</a> </li>
	<li class="active">Product Photos</li>
	</ul>

</div>
<div class="main-content">
<?php echo ($msg!="") ? '<div class="err">'.$msg."</div>" : "";?>
<div class="row">
  <div class="col-md-6">
	<form action="file_upload.php" class="dropzone" name="frm">
	<input type="text" name="prodname" class="form-control" placeholder="name">
	<input type="text" name="prodcol" class="form-control" placeholder="color">
	<input type="text" name="prodfit" class="form-control" placeholder="fit">
	<input type="text" name="prodid" class="form-control" placeholder="id">
	<div class="dz-message needsclick">
	<strong>Drop files here or click to upload.</strong><br />
	<span class="note needsclick">(Please select pics)</span>
	</div>
 	</form>
	<form  name="frmin" action="prodphoto.php" method="post">
	<input type="hidden" name="prodcol" class="form-control" placeholder="color">
	<input type="hidden" name="prodfit" class="form-control" placeholder="fit">
	<input type="hidden" name="prodid" class="form-control" placeholder="id">
		<input type="button" value="Update DB" onclick="callsub()" class="btn btn-primary">
	</form>
  </div>
</div>
<script>
function callsub(){
	document.frmin.prodid.value=document.frm.prodid.value;
	document.frmin.prodcol.value=document.frm.prodcol.value;
	document.frmin.prodfit.value=document.frm.prodfit.value;
	document.frmin.submit();
}
</script>
<?php include("bot.php");?>