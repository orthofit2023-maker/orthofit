<?php 
session_start();
ini_set('max_execution_time', 10000);
if(!isset($_SESSION['loginid'])){
	header("location:login.php");
}
include("db5conn.php");
include("top.php");
?>
<div class="header">
	<h1 class="page-title">Promotional Mailer</h1>
	<ul class="breadcrumb">
	<li><a href="index.php">Home</a> </li>
	<li class="active">Promotional Mailer</li>
	</ul>

</div>
<div class="main-content">
<?php 
include("image.php");
$mailerpath=$_SERVER['DOCUMENT_ROOT']."/promotions/";
if($_POST['pageid']!=''){
	$mailerurl=inpval($_POST['mailerurl']);
	$pageid=intval($_POST['pageid']);
	$subject=inpval($_POST['subject']);

	if($pageid>0){
		$tmpfile = $_FILES['mailerfile']['name'];
		if ($tmpfile !='none'){
			$pic1=$tmpfile;
			$ext=strtolower(substr($pic1,-(strlen($pic1)-(strrpos($pic1, '.')))));
			if ($tmpfile!=''){
				if($ext==".jpg" || $ext==".jpeg" || $ext==".png"){
					$newpic = "mailer".date("dmYhi");
					$dstfile = $mailerpath.$newpic.$ext;
					move_uploaded_file($_FILES['mailerfile']['tmp_name'], $dstfile);

					if(file_exists($dstfile)){
						list($width, $height, $type, $attr) = getimagesize($dstfile);
						$height=intval($height*720/$width);
					}else{
						exit("error loading ".$dstfile);
					}

				}
			}


			$emailtext=getpagedata($pageid);
			
			$emailtext=str_replace("##subject##",$subject,$emailtext);
			$emailtext=str_replace("##photo##",$newpic.$ext,$emailtext);
			$emailtext=str_replace("##url##",$mailerurl,$emailtext);
			$emailtext=str_replace("##height##",$height,$emailtext);


			echo '<textarea name="" rows="" cols="" style="width:600px;height:600px">'.$emailtext.'</textarea>';
		}

	}

	exit();
}else{
?>


<div class="row">
  <div class="col-md-4">
    <form method="post" name="frmin" action="promomailer.php" enctype="multipart/form-data" >
	<input type="hidden" name="pageid" value="39">

	<div id="myTabContent" class="tab-content">
		<div class="form-group">
        <label>Subject</label>
        <input type="text" name="subject" class="form-control" value="<?php echo $url;?>">
        </div>
		<div class="form-group">
        <label>URL</label>
        <input type="text" name="mailerurl" class="form-control" value="https://www.payalsinghal.com/<?php echo $url;?>">
        </div>
		<div class="form-group">
        <label>Mailer Photo</label>
        <input type="file" name="mailerfile" class="form-control">
        </div>
    </div>

    <div class="form-group">
      <button type="submit" class="btn btn-primary" name="btnsubmit"><i class="fa fa-save"></i> Generate Code</button>
    </div>
    </form>
  </div>
</div>
<?php 
}
include("bot.php");?>