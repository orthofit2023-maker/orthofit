<?php 
session_start();
ini_set('max_execution_time', 3000);
if(!isset($_SESSION['loginid'])){
	header("location:login.php");
}
include("db5conn.php");
if($_GET['msg']!="")$msg=$_GET['msg'];

include("top.php");
$type1=$_POST['type1'];
if($_POST['catid']>0) $catid=intval($_POST['catid']);
if($_GET['catid']>0) $catid=intval($_GET['catid']);
if($_GET['prodcode']!='') $prodcode=inpval($_GET['prodcode']);
$prodstatus=intval($_POST['prodstatus']);
?>
<div class="header">
	<h1 class="page-title">Download Photos</h1>
	<ul class="breadcrumb">
	<li><a href="index.php">Home</a> </li>
	<li class="active">Download Photos</li>
	</ul>

</div>
<div class="main-content">
<?php echo ($msg!="") ? '<div class="err">'.$msg."</div>" : "";?>
<div class="row">
  <div class="col-md-6">
    <form name="frm" method="post" action='photodl.php'>
	<input type="hidden" name="prodcode" value="<?php echo $prodcode?>">
    <div id="myTabContent" class="tab-content">
		<div class="form-group col-md-6 left">
        <label>Product Category</label>
        <select name="catid" class="form-control" onchange="document.location.href='<?php echo '?type1='.$type1?>&catid='+this.value">
		<?php echo getcatmenu(0,$catid);?>
		<option value='99' <?php echo ($catid==99 ? " selected" : "")?>>All</option>
		</select>
        </div>
		<div class="form-group col-md-6">
        <label>Product Type</label>
		<select class="form-control" name="type1" id="type1" onchange="document.location.href='<?php echo '?catid='.$catid?>&type1='+this.value">
			<option value=''>Product Type</option>
			<?php $retval='';
				$sql="CALL listType1('$catid')";
				$result = $mysqli->query($sql);
				while($rescon = $result->fetch_array()){$retsel="";
					if($rescon['typeid']==$type1){
						$retsel=" selected";
					}
					$retval=$retval.'<option value="'.$rescon['typeid'].'" '.$retsel.'>'.$rescon['typename'].'</option>';
				} 
				echo $retval;
				mysqli_free_result($result); 
				mysqli_next_result($mysqli); 
			?>
		</select>
        </div>
		<div class="form-group col-md-6 left">
		<label>Product Code</label>
		<input type="text" name="prodcode" value="<?php echo $_GET['prodcode']?>" maxlength="20" class="form-control" placeholder="product code">
		</div>
		<div class="form-group col-md-6">
			<label>Active Products</label>
			<select name="prodstatus" class="form-control">
			<option value="1" <?php echo ($prodstatus==1) ? " selected" : "";?>>Yes</option>
			<option value="0" <?php echo ($prodstatus==0) ? " selected" : "";?>>No</option>
			<option value="2" <?php echo ($prodstatus==2) ? " selected" : "";?>>All</option>
            </select>
        </div>
    </div>
	<div style="clear:both">&nbsp;</div>
    <div class="form-group">
      <button type="submit" class="btn btn-primary" name="btnsubmit"><i class="fa fa-save"></i> Download</button>
    </div>
    </form>

	<?php
	if($_POST['catid']>0 || $_POST['prodcode']!=''){
		$catid=intval($_POST['catid']);
		$type1=intval($_POST['type1']);
		$prodcode=inpval($_POST['prodcode']);
		if($catid==99){
			$zipname="all.zip";
		}else if($prodcode!=''){
			$zipname=$prodcode.'.zip';
		}else{
			$sqlin="select typevalue from ccd9types where typeid='".trim($catid)."'";
			$row=query_first($sqlin);
			$zipname=$row[0].'.zip';
		}
		//echo 'creating zip file '.$zipname.' please wait....';
		$zipfile=$_SERVER['DOCUMENT_ROOT'].'/download/'.$zipname;
		if(file_exists($zipfile)){
			unlink($zipfile);
		}
		if($zipfile!=''){
			$zip = new ZipArchive();
			$zip->open($zipfile,  ZipArchive::CREATE);

			$sql="select prodcode, produrl from ccd9products p join ccd9prod2cat c on c.prodid=p.prodid ";
			if($type1>0){
				$sql=$sql." join ccd9prod2type1 t1 on p.prodid=t1.prodid ";
			}
			$sql=$sql." where p.prodid>0 ".($catid==99 ? "" : ($catid>0 ? " and c.catid='$catid'" :  "")).($prodstatus==2 ? "" : " and p.prodstatus='$prodstatus' ").($type1>0 ? " and t1.typeid='$type1' " : "").($prodcode!='' ? " and p.prodcode='$prodcode' " : "")." order by p.entrydate desc";
			//echo $sql;
			$result = $mysqli->query($sql);
			while($row = $result->fetch_array()){
				for($n=1;$n<20;$n++){	
					$file =trim($row['prodcode']).strtolower(chr($n+64)).'0'.'.jpg';
					$opfile =trim($row['prodcode']).strtolower(chr($n+64)).'.jpg';
					$file1 =trim($row['produrl']).'-'.strtolower(chr($n+64)).'0'.'.jpg';
						//echo $file.'-1<br>';
					if(file_exists($imgpath.$file)){
						$zip->addFile($imgpath.$file, $opfile); 
						//echo $file.'-2<br>';
					}else if(file_exists($imgpath.$file1)){
						$zip->addFile($imgpath.$file1, $opfile); 
							//echo $file1.'-3<br>';
					}else{
						//echo $opfile.'<br>';
					}
				}
				//if($n==1)echo $opfile.'<br>';
			}
		}

		$msg= "Photo zipped successfully!";

		//echo $msg;
		//exit();
		//header("Location:../download/".$zipname);
		echo '<div id="dltext">Please wait... processing your request!</div>';
		echo '<div style="display:none" id="dllink"><a href="../download/'.$zipname.'" target="_blank">Click here to download file '.$zipname.'</a></div>';
	}
	
	?>
  </div>
</div>
<script>
setTimeout(showdl, 10000);
function showdl(){
	//document.getElementById("dltext").style.display="none";
	document.getElementById("dllink").style.display="block";
}
</script>
<?php include("bot.php");?>