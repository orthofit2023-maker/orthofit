<?php 
session_start();
if(!isset($_SESSION['loginid'])){
	header("location:login.php");
}
include("connection.php");
$upload_path=$upload_path."images/cms/";

if($_POST['delid']!="" && checkuseraccess($_SESSION['loginid'], 58, 6)){

	$delimg=$_POST['delid'];
	if(file_exists($upload_path.$delimg)){
		unlink($upload_path.$delimg);
	}
	header("Location:images.php");
}

if(trim($_POST['pgname'])!=""){


	$tmpfile = $_FILES['file1']['name'];
	if ($tmpfile !='none'){
		$pic1=$tmpfile;
		$ext=strtolower(strstr($pic1, '.'));
		if (is_uploaded_file($_FILES['file1']['tmp_name'])){
			if(($ext==".jpg")|| ($ext==".gif") || ($ext==".tif")|| ($ext==".png")){
				//$newpic = $artistid."artt".$ext;
				$newpic = $pic1;

				$dstfile = $upload_path.$newpic;
				move_uploaded_file($_FILES['file1']['tmp_name'], $dstfile);
			}
		}
	}	
	
	$tmpfile = $_FILES['file2']['name'];
	if ($tmpfile !='none'){
		$pic1=$tmpfile;
		$ext=strtolower(strstr($pic1, '.'));
		if (is_uploaded_file($_FILES['file2']['tmp_name'])){
			if(($ext==".jpg")|| ($ext==".gif") || ($ext==".tif")|| ($ext==".png")){
				//$newpic = $artistid."artt".$ext;
				$newpic = $pic1;

				$dstfile = $upload_path.$newpic;
				move_uploaded_file($_FILES['file2']['tmp_name'], $dstfile);
			}
		}
	}	

	$tmpfile = $_FILES['file3']['name'];
	if ($tmpfile !='none'){
		$pic1=$tmpfile;
		$ext=strtolower(strstr($pic1, '.'));
		if (is_uploaded_file($_FILES['file3']['tmp_name'])){
			if(($ext==".jpg")|| ($ext==".gif") || ($ext==".tif")|| ($ext==".png")){
				//$newpic = $artistid."artt".$ext;
				$newpic = $pic1;

				$dstfile = $upload_path.$newpic;
				move_uploaded_file($_FILES['file3']['tmp_name'], $dstfile);
			}
		}
	}	

	$tmpfile = $_FILES['file4']['name'];
	if ($tmpfile !='none'){
		$pic1=$tmpfile;
		$ext=strtolower(strstr($pic1, '.'));
		if (is_uploaded_file($_FILES['file4']['tmp_name'])){
			if(($ext==".jpg")|| ($ext==".gif") || ($ext==".tif")|| ($ext==".png")){
				//$newpic = $artistid."artt".$ext;
				$newpic = $pic1;

				$dstfile = $upload_path.$newpic;
				move_uploaded_file($_FILES['file4']['tmp_name'], $dstfile);
			}
		}
	}	


	header("Location:images.php");

}
include("top.php");?>
<SCRIPT LANGUAGE="JavaScript">
<!--
function condel(y){
	var x =confirm("Please confirm deletion of this image");
	if(x){
		document.frm.delimg.value=y;
		document.frm.submit();
	}
}
//-->
</SCRIPT>
<div class="header">
	<h1 class="page-title">Image Library</h1>
	<ul class="breadcrumb">
	<li><a href="index.php">Home</a> </li>
	<li class="active">Image Library</li>
	</ul>

</div>
<div class="main-content">
<?php echo ($msg!="") ? '<div class="err">'.$msg."</div>" : "";?>
<div class="row">
<div class="col-md-12">
<p>
	<FORM METHOD="POST" ACTION="images.php" name="frm">
	<div id="myTabContent" class="tab-content">
	<input type="hidden" name="delid">
	<table class="table">
	<tr> 
	<?php 
	$x=0;
	$dir=$upload_path;
	if (is_dir($dir)) {
		if ($dh = opendir($dir)) {
			while (($file = readdir($dh)) !== false) {
				$ext=strtolower(strstr($file, '.'));
				if(($ext==".jpg")|| ($ext==".gif") || ($ext==".tif")|| ($ext==".png")){
					list($width, $height, $type, $attr) = getimagesize("../images/cms/".$file);
					if($width>150){$width=150;}
					$x++;
					?>
					<td valign="top" align="center">
					<a href="#" data-href="<?php echo $file?>" role="button" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
					<BR>
					<A HREF="../images/cms/<?php echo $file?>" target="_blank"><img src="../images/cms/<?php echo $file?>" width="<?php echo $width?>" style="border:#ccc 1px solid;"></A> 
					</td>
					<?php 
				}
				if($x%4==0){echo "</tr><tr>";}
			}
			closedir($dh);
		}
	}
	?>
	</tr>
  </table>
  </div>
  </form>
</p>

<p>
<FORM METHOD="POST" ACTION="images.php" enctype="multipart/form-data">
<div id="myTabContent" class="tab-content">
	<INPUT TYPE="hidden" NAME="pgname" value="upload">
	<div class="form-group">
        <label>Image 1</label>
		<input name="file1" type="file" class="form-control">
    </div>
	<div class="form-group">
        <label>Image 2</label>
		<input name="file2" type="file" class="form-control">
    </div>
	<div class="form-group">
        <label>Image 3</label>
		<input name="file3" type="file" class="form-control">
    </div>
	<div class="form-group">
        <label>Image 4</label>
		<input name="file4" type="file" class="form-control">
    </div>
</div>

    <div class="btn-toolbar list-toolbar">
      <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
    </div>

	</FORM>
</p>
<?php include("bot.php");?>
