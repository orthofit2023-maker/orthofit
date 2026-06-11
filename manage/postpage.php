<?php 
session_start();
if(!isset($_SESSION['loginid'])){
	header("location:login.php");
}
include("db5conn.php");

if(trim($_POST['meta_title'])!=""){
	$meta_title = inpval($_POST['meta_title']);
	$description=inpval($_POST['description']);
	$pageid = inpval($_POST['pageid']);
	$pageurl = inpval($_POST['pageurl']);
	$status = inpval($_POST['status']);
	$catid = inpval($_POST['catid']);
	$meta_description=inpval($_POST['meta_description']);
	$meta_keywords=inpval($_POST['meta_keywords']);
	$status=1;

	$sql="update ccd9pages set meta_title='$meta_title', description='$description', pageurl='$pageurl', status='$status', meta_description='$meta_description', meta_keywords='$meta_keywords' where pageid='$pageid'";
	$mysqli->query($sql);
	$msg="Content updated successfully!";
	header("Location:postpage.php?msg=$msg");
}else{
	//$pageid=$_GET['pageid'];
	$pageid=6;
	$res=query_first("select * from ccd9pages where pageid='$pageid'");
	$meta_title=dbval($res['meta_title']);
	$description=dbval($res['description']);
	$pageurl = dbval($res['pageurl']);
	$status = dbval($res['status']);
	$catid = dbval($res['catid']);
	$meta_description=dbval($res['meta_description']);
	$meta_keywords=dbval($res['meta_keywords']);
}

include("top.php");?>
<div class="header">
	<h1 class="page-title">PS Diary Content</h1>
	<ul class="breadcrumb">
	<li><a href="index.php">Home</a> </li>
	<li class="active">PS Diary Content</li>
	</ul>
</div>
<div class="main-content">
<?php echo ($msg!="") ? '<div class="err">'.$msg."</div>" : "";?>
<div class="row">
  <div class="col-md-6">
    <form name="frm" method="post" action='postpage.php' enctype="multipart/form-data">
    <div id="myTabContent" class="tab-content">
	    <input type="hidden" name="pageid" value="<?php echo $pageid?>">
		<input type="hidden" name="pageurl" value="<?php echo $pageurl?>">
		<div style="clear:both"></div>
		
		<div class="form-group">
        <label>Title</label>
        <input type="text" name="meta_title" value="<?php echo $meta_title?>" maxlength="120" class="form-control">
        </div>
		<div class="form-group">
        <label>Meta Title</label>
        <input type="text" name="meta_keywords" value="<?php echo $meta_keywords?>" maxlength="120" class="form-control">
        </div>
		<div class="form-group">
		<label>Meta Description</label>
        <input type="text" name="meta_description" value="<?php echo $meta_description?>" maxlength="120" class="form-control">
        </div>
		<div class="form-group">
          <label>Content</label>
          <textarea id="editor1" rows="10" name="description" class="form-control"><?php echo $description?></textarea>
        </div>
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
	if(document.frm.meta_title.value==""){
		alert("Please enter page title");
		document.frm.meta_title.focus();
		return false;
	}
	if(document.frm.description.value==""){
		alert("Please enter details");
		document.frm.description.focus();
		return false;
	}
}
//-->
</SCRIPT>
<?php include("bot.php");?>