<?php 
include("db5conn.php");
if(trim($_POST['title'])!=""){
	$title = inpval($_POST['title']);
	$description=inpval($_POST['description']);
	$pageid = inpval($_POST['pageid']);
	$pageurl = inpval($_POST['pageurl']);
	$pagedate = sqldate($_POST['pagedate']);
	$status = inpval($_POST['status']);
	$meta_description=inpval($_POST['meta_description']);
	$meta_keywords=inpval($_POST['meta_keywords']);
	$meta_title = inpval($_POST['meta_title']);
	$pagelink = inpval($_POST['pagelink']);
	$pageord = intval($_POST['pageord']);
	$pageby = intval($_POST['pageby']);
	if($meta_title=='')$meta_title=$title;
	if($pageurl=='')$pageurl = getpageurl($title, $pageid);
	if($pageid>0){
		if($iscart==2){
			$sql="update ccd9products set prodname='$title', proddesc='$description', produrl='$pageurl', prodstatus='$status', prodalt='$meta_description', prodkeys='$meta_keywords' where prodid='$pageid'";
			$mysqli->query($sql);
		}else{
			$sql="update ccd9pages set title='$title', description='$description', pageurl='$pageurl', status='$status', meta_description='$meta_description', meta_keywords='$meta_keywords', pagedate='$pagedate', pagelink='$pagelink', pageord='$pageord', meta_title='$meta_title', pageby='$pageby' where pageid='$pageid'";
			$mysqli->query($sql);
		}
	}else{
		if($iscart!=2){
			$sql="insert into ccd9pages (title, description, pageurl, status, meta_description, meta_keywords, pagedate, pagelink, pageord, meta_title, iscart, pageby) values ('$title', '$description', '$pageurl', '$status', '$meta_description', '$meta_keywords', '$pagedate', '$pagelink', '$pageord', '$meta_title', '$iscart', '$pageby')";
			$mysqli->query($sql);
			$pageid=mysqli_insert_id($mysqli);
		}
	}
			//echo $sql;
//exit();
	if($pageid>0){
		/*$tmpfile = $_FILES['banner']['name'];
		if ($tmpfile !='none'){
			$pic1=$tmpfile;
			$ext=strtolower(substr($pic1,-(strlen($pic1)-(strrpos($pic1, '.')))));
			if ($tmpfile!=''){
				if($ext==".jpg" || $ext==".jpeg" || $ext==".png"){
					$newpic = "blog".$pageid.$ext;
					$dstfile = $bannerpath.$newpic;
					move_uploaded_file($_FILES['banner']['tmp_name'], $dstfile);
					$mysqli->query("update ccd9pages set banner='$newpic' where pageid='$pageid'");
				}
			}
		}*/

		$arrfiles = array();
		$pageid=intval($_POST['pageid']);

		$upload_dir = $mediapath."media".$pageid; 
		if(!is_dir($upload_dir)) {
			mkdir($upload_dir, 0777, true);
		}
		$upload_dir = $upload_dir."/";
		$allowed_types = array('jpg', 'png', 'jpeg', 'gif'); 
		  
		$maxsize = 10 * 1024 * 1024;  
	  
		if(!empty(array_filter($_FILES['files']['name']))) { 
	  
			foreach ($_FILES['files']['tmp_name'] as $key => $value) { 
				  
				$file_tmpname = $_FILES['files']['tmp_name'][$key]; 
				$file_name = $_FILES['files']['name'][$key]; 
				$file_size = $_FILES['files']['size'][$key]; 
				$file_ext = pathinfo($file_name, PATHINFO_EXTENSION); 
	  
				$filepath = $upload_dir.$file_name; 
	  
				if(in_array(strtolower($file_ext), $allowed_types)) { 
	  
					if(file_exists($filepath)) { 
						$filepath = $upload_dir.time().$file_name; 
						  
						if( move_uploaded_file($file_tmpname, $filepath)) { 
							array_push($arrfiles,$file_name);
						}else {                      
							echo "Error uploading {$file_name} <br />";  
						} 
					}else { 
					  
						if( move_uploaded_file($file_tmpname, $filepath)) { 
							array_push($arrfiles,$file_name);
						}else {                      
							echo "Error uploading {$file_name} <br />";  
						} 
					} 
				}else{ 
				  
					echo "Error uploading {$file_name} ";  
					echo "({$file_ext} file type is not allowed)<br / >"; 
				}  
			} 

			/*if(count($arrfiles)>0){
				$sql="update ccd9pages set banners='".implode(',',$arrfiles)."' where pageid='$pageid'";
				$mysqli->query($sql);
			}*/
		}

	}
	$msg="Content updated successfully!";
	header("Location:$retuurl?msg=$msg");
}else if($_GET['pageid']>0 && $_GET['delpic']!=''){
	$pageid=$_GET['pageid'];
	$upload_dir = $mediapath."media".$pageid."/"; 
	$pic=trim($_GET['delpic']);
	unlink($upload_dir.$pic);
	$msg="Content updated successfully!";
	header("Location:$pgurl?msg=$msg&pageid=$pageid");
}else if($_GET['pageid']>0){
	$pageid=$_GET['pageid'];
	if($iscart==2){
		$res=query_first("select p.prodname as title,  p.prodid as pageid,  p.prodstatus as status, p.prodkeys as meta_keywords, p.prodalt as meta_description, p.proddesc as description, p.produrl as pageurl from ccd9products p where prodid='$pageid' ");
	}else{
		$res=query_first("select * from ccd9pages where pageid='$pageid'");
	}
	$title=dbval($res['title']);
	$description=str_replace('\n','<br>',trim($res['description']));
	$pageurl = dbval($res['pageurl']);
	$status = dbval($res['status']);
	$meta_title = dbval($res['meta_title']);
	$meta_description=dbval($res['meta_description']);
	$meta_keywords=dbval($res['meta_keywords']);
	$pagedate = inddate($res['pagedate']);
	$pagelink=dbval($res['pagelink']);
	$pageord = intval($res['pageord']);
	$pageby = intval($res['pageby']);
	$upload_dir = $mediapath."media".$pageid; 
}

include("top.php");?>
<div class="header">
	<h1 class="page-title"><?php echo $pgtitle?></h1>
	<ul class="breadcrumb">
	<li><a href="index.php">Home</a> </li>
	<li class="active"><?php echo $pgtitle?></li>
	</ul>
</div>
<div class="main-content">
<?php echo ($msg!="") ? '<div class="err">'.$msg."</div>" : "";?>
<div class="row">
  <div class="col-md-6">
    <form name="frm" method="post" action='<?php echo $pgurl?>' enctype="multipart/form-data">
    <div id="myTabContent" class="tab-content">
	    <input type="hidden" name="pageid" value="<?php echo $pageid?>">
		<input type="hidden" name="iscart" value="<?php echo $iscart?>">
		
		
		<div class="form-group">
        <label>Title</label>
        <input type="text" name="title" value="<?php echo $title?>" maxlength="120" class="form-control">
        </div>
		<?php if($iscart==3){?>
		<div class="form-group">
		<label>Publication</label>
        <input type="text" name="meta_title" value="<?php echo $meta_title?>" maxlength="120" class="form-control">
        </div>
		<?php }else{?>
		<input type="hidden" name="meta_title" value="<?php echo $meta_title?>">
		<?php }?>
		<div class="form-group">
		<label>Meta Keywords</label>
        <input type="text" name="meta_keywords" value="<?php echo $meta_keywords?>" maxlength="120" class="form-control">
        </div>
		<div class="form-group">
		<label>Meta Description</label>
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
		<?php if($iscart==1){?>
		<div class="form-group">
        <label>Date</label>
        <input type="text" name="pagedate" value="<?php echo $pagedate?>" maxlength="10" class="form-control datepicker">
        </div>
		
		<div class="form-group">
			<label>Author</label>
			<select name="pageby" class="form-control">
			<?php echo getauthormenu($pageby);?>
            </select>
        </div>
		<?php }?>
		<div class="form-group">
			<label>Active</label>
			<select name="status" class="form-control">
			<option value="1" <?php echo ($status==1) ? " selected" : "";?>>Yes</option>
			<option value="0" <?php echo ($status==0) ? " selected" : "";?>>No</option>
            </select>
        </div>
		<!-- <div class="form-group">
        <label>Banners</label>
        <input type="file" name="files[]" multiple class="form-control">
        </div>
		<div class="form-group">
		<label>Banners Uploaded</label>
		<?php
		//$arrdir = scandir($upload_dir);
		//print_r($a);
		//for($x=0;$x<count($arrdir);$x++){
			//if($arrdir[$x]!='.' && $arrdir[$x]!='..'){
				//echo '<a href="?pageid='.$pageid.'&delpic='.$arrdir[$x].'">[X]</a>&nbsp;'.$arrdir[$x].'<br>';
			//}
		//}
		?>
		</div> -->
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