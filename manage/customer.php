<?php 
session_start();
if(!isset($_SESSION['loginid'])){
	header("location:login.php");
}
include("db5conn.php");
if($_POST['delid']!=""){
	header("Location:customer.php?userid=$userid");
}

if($_POST['email']!=""){
	$username=inpval($_POST['username']);
	$lastname=inpval($_POST['lastname']);
	$phone =  inpval($_POST['phone']);
	$passwd =  trim($_POST['passwd']);
    $email = dbval($_POST['email']);
    $compid = dbval($_POST['compid']);
    $oldemail = dbval($_POST['oldemail']);

	$countryid =dbval($_POST['countryid']);
	$address =  inpval($_POST['address']);
	$address1=inpval($_POST['address1']);
	$city=inpval($_POST['city']);
	$phone=inpval($_POST['phone']);
	$zipcode=inpval($_POST['zipcode']);
	$state=inpval($_POST['state']);

    $scountryid =dbval($_POST['sscountryid']);
	$saddress =  inpval($_POST['saddress']);
	$saddress1=inpval($_POST['saddress1']);
	$scity=inpval($_POST['scity']);
	$sphone=inpval($_POST['sphone']);
	$szipcode=inpval($_POST['szipcode']);
	$sstate=inpval($_POST['sstate']);

    $newuser=0;
    if($compid>0){
        $sql="update ccd9company set username='$username', lastname='$lastname', phone='$phone', email='$email' where compid='$compid'";
	    $mysqli->query($sql);
    }else{

        $rsdata= query_first("select compid from ccd9company where (phone='$phone' or email='$email')");
        if ($rsdata['compid']>0){
            $errmsg= "User already registered with this email address/phone no.";
            header("location:customer.php?msg=$errmsg&username=$username&lastname=$lastname&phone=$phone&email=$email&address=$address&address1=$address1&city=$city&phone=$phone&zipcode=$zipcode&state=$state&countryid=$countryid&saddress=$saddress&saddress1=$saddress1&scity=$scity&sphone=$sphone&szipcode=$szipcode&sstate=$sstate&scountryid=$scountryid");
            exit();
        }else{
            $newpasswd=rand(5, 15).date("ism");
            $passwd = encyrptPassword($newpasswd);

            $sql="insert into ccd9company (username, lastname, passwd, phone, email, countryid, status, regdate) values ('$username', '$lastname', '$passwd', '$phone', '$email', '$countryid', '1', now())";
            $mysqli->query($sql);
            $compid=mysqli_insert_id($mysqli);

            $emailtext=getpagedata(19);//46
            $emailtext=str_replace("##customername##",$username.' '.$lastname,$emailtext);
            $emailtext=str_replace("##loginname##",$email,$emailtext);
            $emailtext=str_replace("##loginpasswd##",$_POST['passwd'],$emailtext);

            $subject=trim(getpagetitle(19));//46
            $to=trim($email);
            
            //sendsmtpmail($to,$subject,$emailtext);
        }
    }

    if($compid>0){
        $sql="insert into ccd9address (username, lastname, compid, email, countryid, phone, address, address1, city, zipcode, state) values ('$username', '$lastname', '$compid', '$email', '$countryid', '$phone', '$address', '$address1', '$city', '$zipcode', '$state')";
        $mysqli->query($sql);
        $addressid= mysqli_insert_id($mysqli);
        $mysqli->query("update ccd9company set addressid='$addressid' where compid='$compid'");
    }
    
}else if($_GET['compid']!=""){
	$compid = $_GET['compid'];
	$row=query_first("select * from ccd9company where compid='$compid'");
	if($row['compid']>0){
		$username=dbval($row['username']);
        $lastname=dbval($row['lastname']);
        $email = dbval($row['email']);
        $addressid = dbval($row['addressid']);

        $row=query_first("select * from ccd9address where compid='$compid' and addressid='$addressid'");

        $phone =  dbval($row['phone']);
        $countryid =dbval($row['countryid']);
        $address =  dbval($row['address']);
        $address1=dbval($row['address1']);
        $city=dbval($row['city']);
        $zipcode=dbval($row['zipcode']);
        $state=dbval($row['state']);

        /*
        $row=query_first("select * from ccd9address where compid='$compid' and addressid!='$addressid'");
        if($row['addressid']>0){
            $sphone =  dbval($row['phone']);
            $scountryid =dbval($row['countryid']);
            $saddress =  dbval($row['address']);
            $saddress1=dbval($row['address1']);
            $scity=dbval($row['city']);
            $szipcode=dbval($row['zipcode']);
            $sstate=dbval($row['state']);
        }
        */
	}
}else{
    $countryid =99; $scountryid =99; 
}
$p=$_GET['p'];

include("top.php");?>
<div class="header">
	<h1 class="page-title">Customer</h1>
	<ul class="breadcrumb">
	<li><a href="index.php">Home</a> </li>
	<li class="active">Customer</li>
	</ul>

</div>
<div class="main-content">
<?php echo ($msg!="") ? '<div class="err">'.$msg."</div>" : "";?>
<div class="row">
  <div class="col-md-8">
    <form name="frm" method="post" action='customer.php' autocomplete="off">
    <div id="myTabContent" class="tab-content">
	  <input type="hidden" name="compid" value="<?php echo $compid?>">
      <input type="hidden" name="addressid" value="<?php echo $addressid?>">
      <input type="hidden" name="oldemail" value="<?php echo $email?>">
	  <input type="hidden" name="p" value="<?php echo $p?>">
	  <input type="hidden" name="delid">

		<div class="form-group col-md-6 left">
        <label>First Name</label>
        <input type="text" name="username" value="<?php echo $username?>" maxlength="60" class="form-control" required>
        </div>
		<div class="form-group  col-md-6">
        <label>Last Name</label>
		<input class="form-control" type="text" name="lastname" value="<?php echo $lastname?>" maxlength="60" required>
        </div>
		<div class="form-group col-md-6 left">
			<label>Email</label>
			<input type="email" name="email" value="<?php echo $email?>" maxlength="120" class="form-control" required>
        </div>
		<div class="form-group col-md-6">
			<label>Phone</label>
			<input type="text" name="phone" value="<?php echo $phone?>" maxlength="20" class="form-control" required>
        </div>

        <div class="form-group col-md-12"><h3>Billing Address</h3></div>
        <div class="form-group col-md-6 left">
			<label>Address</label>
			<input type="text" name="address" value="<?php echo $address?>" maxlength="120" class="form-control" required>
        </div>
		<div class="form-group col-md-6">
        <label>Address</label>
			<input type="text" name="address1" value="<?php echo $address1?>" maxlength="120" class="form-control">
        </div>

        <div class="form-group col-md-6 left">
			<label>City</label>
			<input type="text" name="city" value="<?php echo $city?>" maxlength="60" class="form-control" required>
        </div>
        <div class="form-group col-md-6">
        <label>Post Code</label>
			<input type="text" name="zipcode" value="<?php echo $zipcode?>" maxlength="20" class="form-control" required>
        </div> 

		<div class="form-group col-md-6 left">
        <label>State</label>
			<input type="text" name="state" value="<?php echo $state?>" maxlength="60" class="form-control" required>
        </div>

		<div class="form-group col-md-6 left">
			<label>Country</label>
            <select class="form-control" name="countryid" required>
                <?php
                $sql="select countryid, countryname from ccd9country order by countryname"; 
                $result = $mysqli->query($sql);
                while($rescon = $result->fetch_array()){$retsel="";
                    if($rescon['countryid']==$countryid){
                        $retsel=" selected";
                    }
                    $retval=$retval.'<option value="'.$rescon['countryid'].'" '.$retsel.'>'.$rescon['countryname'].'</option>';
                } 
                echo $retval;
                ?>
			</select>
        </div>
        <div class="form-group col-md-12"><h3>Shipping Address</h3></div>
        <div class="form-group col-md-12 left">
            <label><input type="checkbox" name="addcopy" value="1" class="checkbox-inline">
            If not same as Billing Address</label>
        </div>
        <div class="form-group col-md-6 left">
        <label>First Name</label>
        <input type="text" name="susername" value="<?php echo $susername?>" maxlength="60" class="form-control" required>
        </div>
		<div class="form-group  col-md-6">
        <label>Last Name</label>
		<input class="form-control" type="text" name="slastname" value="<?php echo $slastname?>" maxlength="60" required>
        </div>
        <div class="form-group col-md-6 left">
			<label>Address</label>
			<input type="text" name="saddress" value="<?php echo $saddress?>" maxlength="120" class="form-control" required>
        </div>
		<div class="form-group col-md-6">
        <label>Address</label>
			<input type="text" name="saddress1" value="<?php echo $saddress1?>" maxlength="120" class="form-control">
        </div>

        <div class="form-group col-md-6 left">
			<label>City</label>
			<input type="text" name="scity" value="<?php echo $scity?>" maxlength="60" class="form-control" required>
        </div>
        <div class="form-group col-md-6">
        <label>Post Code</label>
			<input type="text" name="szipcode" value="<?php echo $szipcode?>" maxlength="20" class="form-control" required>
        </div> 

		<div class="form-group col-md-6 left">
        <label>State</label>
			<input type="text" name="sstate" value="<?php echo $sstate?>" maxlength="60" class="form-control" required>
        </div>

		<div class="form-group col-md-6 left">
			<label>Country</label>
            <select class="form-control" name="scountryid" required>
                <?php $retval='';
                $sql="select countryid, countryname from ccd9country order by countryname"; 
                $result = $mysqli->query($sql);
                while($rescon = $result->fetch_array()){ $retsel="";
                    if($rescon['countryid']==$scountryid){
                        $retsel=" selected";
                    }
                    $retval=$retval.'<option value="'.$rescon['countryid'].'" '.$retsel.'>'.$rescon['countryname'].'</option>';
                } 
                echo $retval;
                ?>
			</select>
        </div>

        <div class="form-group col-md-6 left">
			<label>Phone</label>
			<input type="text" name="sphone" value="<?php echo $sphone?>" maxlength="20" class="form-control" required>
        </div>
		<div style="clear:both;"></div>
	

    </div>

    <div class="form-group col-md-12">
      <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
    </div>
    </form>
  </div>
</div>
<script>
function copyadd(){
    if(document.frm.addcopy.checked==true){
		document.frm.susername.value=document.frm.username.value;
		document.frm.slastname.value=document.frm.lastname.value;
		document.frm.saddress.value=document.frm.address.value;
		document.frm.saddress1.value=document.frm.address1.value;
		document.frm.scity.value=document.frm.city.value;
		document.frm.szipcode.value=document.frm.zipcode.value;
		document.frm.sstate.value=document.frm.state.value;
		document.frm.scountryid.value=document.frm.countryid.value;
		document.frm.sphone.value=document.frm.phone.value;

	}else{
		document.frm.susername.value='';
		document.frm.slastname.value='';
		document.frm.saddress.value='';
		document.frm.saddress1.value='';
		document.frm.scity.value='';
		document.frm.szipcode.value='';
		document.frm.sstate.value='';
		document.frm.scountryid.value='99';
		document.frm.sphone.value='';
	}
}
</script>
<?php include("bot.php");?>