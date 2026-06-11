<?php 
session_start();
if(!isset($_SESSION['loginid'])){
	header("location:login.php");
}
include("db5conn.php");
$filetitle='Review';
$rating='5';
$newfile="review.php";
$retufile="reviews.php";

if(trim($_POST['username'])!=""){
	$p =  $_POST['p'];

	$revid =  $_POST['revid'];
	$prodid =  $_POST['prodid'];
	$review=inpval($_POST['review']);
	$username=inpval($_POST['username']);
	$email=inpval($_POST['email']);
	$revtitle=inpval($_POST['revtitle']);
	$revdate=sqldate($_POST['revdate']);
	$rating =  intval($_POST['rating']);
	$status =  intval($_POST['status']);

	if($revid>0 ){
		$sql="update ccd9reviews set review='$review', revtitle='$revtitle', username='$username', email='$email', prodid='$prodid', rating='$rating', status='$status', revdate='$revdate' where revid='$revid'";
		$query = $mysqli->query($sql) ;
		$msg="$filetitle updated successfully!";

	}else if($username!="" ){
		/*$row=query_first("select revid from ccd9reviews where email='$email' and prodid='$prodid'");
		if($row['revid']>0){
			$msg= "$filetitle already added with this name";
			$err=0;
		}else{*/
			$sql="insert into ccd9reviews (prodid, review, rating, revtitle, username, email, status, revdate, isman) values ('$prodid', '$review', '$rating', '$revtitle', '$username', '$email', '$status', '$revdate', '1')";
			$query = $mysqli->query($sql) ;
			$revid=mysqli_insert_id();
			if($revid>0){
				$msg="$filetitle added successfully!";
			}
		//}
	}
	//echo $sql;
	//exit();
	header("location:$retufile?revid=$revid&msg=$msg");

}else if($_GET['revid']!=""){
	$revid=$_GET['revid'];
	$p=$_GET['p'];
//insert into ccd9files2user (fileid, userid, accessid) SELECT fileid, '3', accessid FROM `ccd9files2user` where userid=183
	$res=query_first("select r.*, date_format(revdate, '%d/%m/%Y') as revdt from ccd9reviews r where revid='$revid'");
	$revid =  $res['revid'];
	$prodid =  $res['prodid'];
	$review=dbval($res['review']);
	$parentid=dbval($res['parentid']);
	$username=dbval($res['username']);
	$email=dbval($res['email']);
	$revtitle=dbval($res['revtitle']);
	$revdate=trim($res['revdt']);
	$rating =  $res['rating'];
	$status =  $res['status'];
} 
$p=$_GET['p'];

include("top.php");?>
<div class="header">
	<h1 class="page-title"><?php echo $filetitle?></h1>
	<ul class="breadcrumb">
	<li><a href="index.php">Home</a> </li>
	<li class="active"><?php echo $filetitle?></li>
	</ul>

</div>
<div class="main-content">
<?php echo ($msg!="") ? '<div class="err">'.$msg."</div>" : "";?>

<div class="row">
  <div class="col-md-4">
    <form name="frm" method="post" autocomplete="off">
    <div id="myTabContent" class="tab-content">
	  <input type="hidden" name="revid" value="<?php echo $revid?>">
	  <input type="hidden" name="p" value="<?php echo $p?>">
	  <input type="hidden" name="delid">


		<div class="form-group">
        <label>Select Product</label>
        <select name="prodid" required class="form-control">
		<?php
		$retval='<option value="">Select Product</option>';

		$result = $mysqli->query("select prodid, prodname from ccd9products order by prodname");
		while($rescon = $result->fetch_array()){$retsel="";
			if($rescon['prodid']==$prodid){
				$retsel=" selected";
			}
			$retval=$retval.'<option value="'.$rescon['prodid'].'" '.$retsel.'>'.$rescon['prodname'].'</option>';
		} 
		echo $retval;
		
		?>
        </select>
        </div>
		<div class="form-group">
        <label>Customer Name</label>
		<input type="text" name="username" value="<?php echo $username?>" maxlength="120" class="form-control" required>
        </div>
		<div class="form-group">
        <label>Email</label>
		<input type="email" name="email" value="<?php echo $email?>" maxlength="120" class="form-control">
        </div>
		<div class="form-group">
        <label>Review Title</label>
		<input type="text" name="revtitle" value="<?php echo $revtitle?>" maxlength="120" class="form-control">
        </div>
		<div class="form-group">
        <label>Review Details</label>
		<textarea name="review" class="form-control" rows="5"><?php echo $review?></textarea>
        </div>
		<div class="form-group col-md-4 left">
        <label>Rating</label>
		<select name="rating" class="form-control">
			<option value="1" <?php echo ($rating==1) ? " selected" : "";?>>1</option>
			<option value="2" <?php echo ($rating==2) ? " selected" : "";?>>2</option>
			<option value="3" <?php echo ($rating==3) ? " selected" : "";?>>3</option>
			<option value="4" <?php echo ($rating==4) ? " selected" : "";?>>4</option>
			<option value="5" <?php echo ($rating==5) ? " selected" : "";?>>5</option>
            </select>
        </div>
		<div class="form-group col-md-4">
        <label>Review Date</label>
		<input type="text" name="revdate" value="<?php echo $revdate?>" maxlength="10" class="form-control datepicker" required>
        </div>
		<div class="form-group col-md-4">
        <label>Active</label>
		<select name="status" class="form-control">
			<option value="1" <?php echo ($status==1) ? " selected" : "";?>>Yes</option>
			<option value="0" <?php echo ($status==0) ? " selected" : "";?>>No</option>
            </select>
        </div>
    </div>

    <div class="btn-toolbar list-toolbar">
      <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
    </div>
    </form>
  </div>
</div>

<script>

</script>
<?php include("bot.php");?>