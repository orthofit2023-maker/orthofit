<?php 
session_start();
if(!isset($_SESSION['loginid'])){
	header("location:login.php");
}
include("db5conn.php");
$bannerpath=$_SERVER['DOCUMENT_ROOT']."/images/";
if(trim($_POST['title'])!=""){
	$title = inpval($_POST['title']);
	$description=inpval($_POST['description']);
	$pageid = inpval($_POST['pageid']);
	$pageurl = inpval($_POST['pageurl']);
	$pagedate = sqldate($_POST['pagedate']);
	$status = inpval($_POST['status']);
	$meta_description=inpval($_POST['meta_description']);
	$meta_keywords=inpval($_POST['meta_keywords']);
	$iscart = inpval($_POST['iscart']);
	if($pageurl=='')$pageurl = getpageurl($title, $pageid);
	if($pageid>0){
		$sql="update ccd9pages set title='$title', description='$description', pageurl='$pageurl', status='$status', meta_description='$meta_description', meta_keywords='$meta_keywords', pagedate='$pagedate' where pageid='$pageid'";
		$mysqli->query($sql);
	}else{
		$sql="insert into ccd9pages (title, description, pageurl, status, meta_description, meta_keywords, iscart, pagedate) values ('$title', '$description', '$pageurl', '$status', '$meta_description', '$meta_keywords', '$iscart', '$pagedate')";
		$mysqli->query($sql);
		$pageid=mysqli_insert_id($mysqli);
	}

	if($pageid>0){
		$tmpfile = $_FILES['banner']['name'];
		if ($tmpfile !='none'){
			$pic1=$tmpfile;
			$ext=strtolower(substr($pic1,-(strlen($pic1)-(strrpos($pic1, '.')))));
			if ($tmpfile!=''){
				if($ext==".jpg" || $ext==".jpeg" || $ext==".png"){
					$newpic = "blog".$pageid.$ext;
					$dstfile = $bannerpath.$newpic;
					move_uploaded_file($_FILES['banner']['tmp_name'], $dstfile);
					//echo $dstfile;
					$mysqli->query("update ccd9pages set banner='$newpic' where pageid='$pageid'");

					//echo "update ccd9pages set banner='$newpic' where pageid='$pageid'";
					//exit();
				}
			}
		}

	}
	$msg="Content updated successfully!";
	header("Location:blogs.php?msg=$msg");
}else if($_GET['pageid']>0){
	$pageid=$_GET['pageid'];
	$res=query_first("select * from ccd9pages where pageid='$pageid'");
	$title=dbval($res['title']);
	$description=dbval($res['description']);
	$pageurl = dbval($res['pageurl']);
	$status = dbval($res['status']);
	$iscart = dbval($res['iscart']);
	$meta_description=dbval($res['meta_description']);
	$meta_keywords=dbval($res['meta_keywords']);
	$pagedate = inddate($res['pagedate']);
}

include("top.php");?>
<div class="header">
	<h1 class="page-title">PS Blog</h1>
	<ul class="breadcrumb">
	<li><a href="index.php">Home</a> </li>
	<li class="active">PS Blog</li>
	</ul>
</div>
<div class="main-content">
<?php echo ($msg!="") ? '<div class="err">'.$msg."</div>" : "";?>
<div class="row">
  <div class="col-md-6">
    <form name="frm" method="post" action='blog.php' enctype="multipart/form-data">
    <div id="myTabContent" class="tab-content">
	    <input type="hidden" name="pageid" value="<?php echo $pageid?>">
		<input type="hidden" name="iscart" value="1">
		
		
		<div class="form-group">
        <label>Title</label>
        <input type="text" name="title" value="<?php echo $title?>" maxlength="120" class="form-control">
        </div>
		<div class="form-group">
		<label>Meta Keywords</label>
        <input type="text" name="meta_keywords" value="<?php echo $meta_keywords?>" maxlength="120" class="form-control">
        </div>
		<div class="form-group">
		<label>Short Description</label>
        <input type="text" name="meta_description" value="<?php echo $meta_description?>" maxlength="200" class="form-control">
        </div>
		<div class="form-group">
          <label>Content</label>
          <textarea id="editor1" rows="10" name="description" class="form-control"><?php echo $description?></textarea>
        </div>
		
		<div class="form-group">
        <label>URL</label>
        <input type="text" name="pageurl" value="<?php echo $pageurl?>" maxlength="120" class="form-control">
        </div>
		<div class="form-group col-md-4">
        <label>Date</label>
        <input type="text" name="pagedate" value="<?php echo $pagedate?>" maxlength="10" class="form-control datepicker">
        </div>
		<div class="form-group col-md-4">
        <label>Banner</label>
        <input type="file" name="banner" maxlength="120" class="form-control">
        </div>
		<div class="form-group col-md-4">
			<label>Active</label>
			<select name="status" class="form-control">
			<option value="1" <?php echo ($status==1) ? " selected" : "";?>>Yes</option>
			<option value="0" <?php echo ($status==0) ? " selected" : "";?>>No</option>
            </select>
        </div>
		<div style="clear:both"></div>
	</div>
    <div class="btn-toolbar list-toolbar">
      <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Submit</button>
    </div>
    </form>
  </div>
</div>

<SCRIPT LANGUAGE="JavaScript">
<!--
function validate(){
	if(document.frm.title.value==""){
		alert("Please enter title");
		document.frm.title.focus();
		return false;
	}
}
//-->
</SCRIPT>
<?php include("bot.php");?>