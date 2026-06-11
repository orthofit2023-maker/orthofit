<?php 
session_start();
if(!isset($_SESSION['loginid'])){
	header("location:login.php");
}
include("db5conn.php");
$bannerpath=$_SERVER['DOCUMENT_ROOT']."/images/";
if($_POST['opt']!=''){
	$bannerurl=inpval($_POST['bannerurl']);
	$typeid=intval($_POST['typeid']);
	$typevalue1=inpval($_POST['typevalue1']);
	$typevalue=inpval($_POST['typevalue']);
	$typevalue2=inpval($_POST['typevalue2']);
	$opt=intval($_POST['opt']);
	if($typeid>0){ }else{
		$sql="insert into ccd9types (typevalue, typevalue1, typevalue2, opt) values ('$typevalue', '$typevalue1', '$typevalue2', '$opt')";
		$mysqli->query($sql);
		$typeid=mysqli_insert_id($mysqli);
	}
	if($typeid>0){
		$newpic=trim($_POST['oldfile']);
		$tmpfile = $_FILES['bannerfile']['name'];
		if ($tmpfile !='none'){
			$pic1=$tmpfile;
			$ext=strtolower(substr($pic1,-(strlen($pic1)-(strrpos($pic1, '.')))));
			if ($tmpfile!=''){
				if($ext==".jpg" || $ext==".jpeg" || $ext==".png"){
					$newpic = "banner".$opt."-".$typeid.$ext;
					$dstfile = $bannerpath.$newpic;
					move_uploaded_file($_FILES['bannerfile']['tmp_name'], $dstfile);
					//echo $dstfile;

					$image = imagecreatefromstring(file_get_contents($dstfile));
					ob_start();
					imagejpeg($image,NULL,100);
					$cont = ob_get_contents();
					ob_end_clean();
					imagedestroy($image);
					$content = imagecreatefromstring($cont);
					$output = $bannerpath."banner".$opt."-".$typeid.".webp";
					imagewebp($content,$output);
					imagedestroy($content);

				}
			}
		}

		$typename=$newpic.",".$bannerurl;
		$mysqli->query("update ccd9types set typename='$typename', typevalue='$typevalue', typevalue1='$typevalue1', typevalue2='$typevalue2', opt='$opt' where typeid='$typeid'");
		//echo "<br>update ccd9types set typename='$typename', typevalue='$typevalue', typevalue1='$typevalue1', opt='$opt' where typeid='$typeid'";

		//exit();
	}

	$msg= "Banner updated successfully!";
	header("Location:herobanners.php?msg=$msg&opt=$opt");
	exit();
}else if($_GET['typeid']!=''){
	$typeid=intval($_GET['typeid']);
	$sql="select * from ccd9types t where t.typeid='$typeid'";
	$result = $mysqli->query($sql);
	if($rescon = $result->fetch_array()){
		list($banner,$url)=explode(",",dbval($rescon['typename']));
		$typevalue1=dbval($rescon['typevalue1']);
		$typevalue=dbval($rescon['typevalue']);
		$opt=dbval($rescon['opt']);
		$typevalue2=dbval($rescon['typevalue2']);
	}
}
include("top.php");
if($_GET['msg']!="")$msg=$_GET['msg'];
$p=$_GET['p'];
?>
<div class="header">
	<h1 class="page-title">Banners</h1>
	<ul class="breadcrumb">
	<li><a href="index.php">Home</a> </li>
	<li class="active">Banners</li>
	</ul>

</div>
<div class="main-content">
<?php echo ($msg!="") ? '<div class="err">'.$msg."</div>" : "";?>
<div class="row">
  <div class="col-md-4">
    <form method="post" name="frmin" action="herobanner.php" enctype="multipart/form-data" >
	<input type="hidden" name="typeid" value="<?php echo $typeid?>">
	<input type="hidden" name="oldfile" value="<?php echo $banner?>">


	<div id="myTabContent" class="tab-content">
		<div class="form-group">
        <label>URL</label>
        <input type="text" name="bannerurl" class="form-control" value="<?php echo $url;?>">
        </div>
		<div class="form-group">
        <label>Banner Photo</label>
        <input type="file" name="bannerfile" class="form-control">
        </div>
		<div class="form-group">
        <label>Banner Seq. No.</label>
        <input type="text" name="typevalue1" class="form-control" value="<?php echo $typevalue1;?>">
        </div>
		<div class="form-group">
		<label>INR/USD</label>
		<select name="typevalue" class="form-control">
			<option value="ALL" <?php echo ($typevalue=='ALL' ? 'selected' : '')?>>ALL</option>
			<option value="INR" <?php echo ($typevalue=='INR' ? 'selected' : '')?>>INR</option>
			<option value="USD" <?php echo ($typevalue=='USD' ? 'selected' : '')?>>USD</option>
		</select>
        </div>
		<div class="form-group">
		<label>Banner Type</label>
		<select name="opt" class="form-control">
			<option value="101" <?php echo ($opt=='101' ? 'selected' : '')?>>Desktop</option>
			<option value="102" <?php echo ($opt=='102' ? 'selected' : '')?>>Mobile</option>
			<option value="103" <?php echo ($opt=='103' ? 'selected' : '')?>>Other</option>
		</select>
        </div>
		<div class="form-group">
		<label>Active</label>
		<select name="typevalue2" class="form-control">
			<option value="1" <?php echo ($typevalue2=='1' ? 'selected' : '')?>>Yes</option>
			<option value="2" <?php echo ($typevalue2=='2' ? 'selected' : '')?>>No</option>
		</select>
        </div>
    </div>

    <div class="form-group">
      <button type="submit" class="btn btn-primary" name="btnsubmit"><i class="fa fa-save"></i> Save</button>
    </div>
    </form>
  </div>
</div>
<?php include("bot.php");?>