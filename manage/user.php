<?php 
session_start();
if(!isset($_SESSION['loginid'])){
	header("location:login.php");
}
include("db5conn.php");
$upload_path=$upload_path."images/users/";
if($_POST['delid']!=""){
	header("Location:user.php?userid=$userid");
}

if(trim($_POST['name'])!="" && checkuserref()){

	$userid =  $_POST['userid'];
	$p =  $_POST['p'];
	$name=inpval($_POST['name']);
	$email=inpval($_POST['email']);
	$oldemail=inpval($_POST['oldemail']);
	$status=inpval($_POST['status']);
	$cpasswd=trim($_POST['cpasswd']);
	$passwd=trim($_POST['passwd']);

	if($passwd!="" && $passwd == $cpasswd ){
		$newpasswd = encyrptPassword($passwd);
	}
	$err=1;
	if($userid>0 ){
		$sql="update ccd9user set name='$name'  ";

		if($newpasswd!=""){
			$sql=$sql.", passwd='$newpasswd', failedlogin='0', faileddate='', status='1'";
		}else{
			$sql=$sql.", status='$status' ";
		}
		if($email!=$oldemail){
			$row=query_first("select loginid from ccd9user where email='$email' and loginid!='$userid'");
			if($row['loginid']>0){
				$msg= "User already added with this email";
			}else{
				$sql=$sql.", email='$email'";
			}
		}
		$sql=$sql." where loginid='$userid'";
		//echo $sql;
		$mysqli->query($sql); // or die(mysql_error())
		$msg="User updated successfully!";

		//$DB_site->query("insert into ccd9adminlog (loginid, logtable, logtype, logip, logdescr) values ('".$_SESSION['loginid']."', '5', '2', '".$_SERVER['REMOTE_ADDR']."', '$userid')");
		

	}else if($name!="" ){
		$row=query_first("select loginid from ccd9user where email='$email'");
		if($row['loginid']>0){
			$msg= "User already added with this email";
			$err=0;
		}else{
			$sql="insert into ccd9user (name, email, passwd) values ('$name', '$email', '$newpasswd')";
			$query = $mysqli->query($sql);
			$userid=mysqli_insert_id($query);
			if($userid>0){
				$msg="User added successfully!";

				//$sql="INSERT INTO ccd9files2user (fileid, logintype, userid, accesstype) VALUES  (29, 0, '$userid', 1), ( 29, 0, '$userid', 2), (28, 0, '$userid', 1), (28, 0, '$userid', 2)";
				//$DB_site->query($sql);

				//$DB_site->query("insert into ccd9adminlog (loginid, logtable, logtype, logip, logdescr) values ('".$_SESSION['loginid']."', '5', '1', '".$_SERVER['REMOTE_ADDR']."', '$userid')");
			}
		}
	}
	//exit();
	header("location:users.php?userid=$userid&msg=$msg");

}else if($_GET['userid']!=""){
	$userid=$_GET['userid'];
	$p=$_GET['p'];

	$res=query_first("select * from ccd9user u where loginid='$userid'");
	$name=dbval($res['name']);
	$email=dbval($res['email']);
	$status=dbval($res['status']);


} 
$p=$_GET['p'];

include("top.php");?>
<div class="header">
	<h1 class="page-title">Users Master</h1>
	<ul class="breadcrumb">
	<li><a href="index.php">Home</a> </li>
	<li class="active">Users Master</li>
	</ul>

</div>
<div class="main-content">
<?php echo ($msg!="") ? '<div class="err">'.$msg."</div>" : "";?>

<div class="row">
  <div class="col-md-8">
    <form name="frm" method="post" action='user.php' autocomplete="off">
    <div id="myTabContent" class="tab-content">
	  <input type="hidden" name="userid" value="<?php echo $userid?>">
	  <input type="hidden" name="p" value="<?php echo $p?>">
	  <input type="hidden" name="delid">


		<div class="form-group col-md-6 left">
        <label>Name</label>
        <input type="text" name="name" value="<?php echo $name?>" maxlength="120" class="form-control">
        </div>
		<div class="form-group  col-md-6">
        <label>Email (login)</label>
		<input type="hidden" name="oldemail" value="<?php echo $email?>">
		<input class="form-control" type="text" name="email" value="<?php echo $email?>" maxlength="120">
        </div>
		<div class="form-group col-md-6 left">
			<label>Password</label>
			<input type="password" name="passwd" value="" maxlength="10" class="form-control">
        </div>
		<div class="form-group col-md-6">
			<label>Confirm Password</label>
			<input type="password" name="cpasswd" value="" maxlength="10" class="form-control">
        </div>
		<div class="form-group col-md-6 left">
			<label>Active</label>
			<select name="status" class="form-control col-md-6">
			<option value="1" <?php echo ($status==1) ? " selected" : "";?>>Yes</option>
			<option value="0" <?php echo ($status==0) ? " selected" : "";?>>No</option>
            </select>
        </div>

		
		<div style="clear:both;"></div>
	

    </div>

    <div class="form-group col-md-12">
      <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
    </div>
    </form>
  </div>
</div>

<SCRIPT LANGUAGE="JavaScript">
<!--
function validate(){
	if(document.frm.name.value==""){
		alert("please enter name");
		document.frm.name.focus();
		return false;
	}else if(document.frm.email.value==""){
		alert("please enter login");
		document.frm.email.focus();
		return false;
	}
}

//-->
</SCRIPT>
<?php include("bot.php");?>