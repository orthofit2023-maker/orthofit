<?php 
session_start();
if(!isset($_SESSION['loginid'])){
	header("location:login.php");
}
include("db5conn.php");
if($_POST['delid']!=""){
	header("Location:discount.php?userid=$userid");
}

if($_POST['disccode']!=""){
	$disccode=strtoupper(inpval($_POST['disccode']));
	$discamt=inpval($_POST['discamt']);
	$disctype=inpval($_POST['disctype']);
	$discstatus=inpval($_POST['discstatus']);
	$discuse=inpval($_POST['discuse']);
	$userid=inpval($_POST['userid']);
	$discid=inpval($_POST['discid']);
	$discdescr = inpval($_POST['discdescr']);
	$discexpiry = sqldate($_POST['discexpiry']);
	$discemail = inpval($_POST['discemail']);
	$disccur = inpval($_POST['disccur']);
	if(trim($_POST['customer'])==""){
		$userid=0;
	}
	$exclcats = $_POST['exclcats'];
	$incltype = $_POST['incltype'];
	$inclcats = $_POST['inclcats'];
	//echo $exclcats;

	if($discid>0){
		$sql="update ccd9discounts set disccode='$disccode', discamt='$discamt', disctype='$disctype', discstatus='$discstatus', discuse='$discuse', compid='$userid', discdescr='$discdescr', discexpiry='$discexpiry', discemail='$discemail', disccur='$disccur', exclcats='".implode(",",$exclcats)."', inclcats='".implode(",",$inclcats)."', incltype='".implode(",",$incltype)."' where discid='$discid'";
		//echo $sql;
		//exit();
		$mysqli->query($sql);
		header("Location:discounts.php?msg=Discount code updated successfully!");
	}else{
		$sql = "insert into ccd9discounts (disccode, discamt, disctype, discstatus, discuse, compid, discdescr, discexpiry, discemail, disccur, exclcats, incltype, inclcats) values('".$disccode."', '".$discamt."', '".$disctype."', '".$discstatus."', '".$discuse."', '".$userid."', '".$discdescr."', '".$discexpiry."', '".$discemail."', '".$disccur."', '".implode(",",$exclcats)."', '".implode(",",$incltype)."', '".implode(",",$inclcats)."' )";
		$mysqli->query($sql);
		header("Location:discounts.php?msg=Discount code added successfully!");
	}
}else if($_GET['discid']!=""){
	$discid = $_GET['discid'];
	$row=query_first("select * from ccd9discounts where discid='$discid'");
	if($row['discid']>0){
		$discid=dbval($row['discid']);
		$disccode=dbval($row['disccode']);
		$discamt=dbval($row['discamt']);
		$disctype=dbval($row['disctype']);
		$discuse=dbval($row['discuse']);
		$userid=dbval($row['compid']);
		$discstatus=dbval($row['discstatus']);
		$discdescr = dbval($row['discdescr']);
		$discexpiry = inddate($row['discexpiry']);
		$discemail = dbval($row['discemail']);
		$disccur = dbval($row['disccur']);
		$exclcats = explode(",",$row['exclcats']);
		$incltype = explode(",",$row['incltype']);
		$inclcats = explode(",",$row['inclcats']);
		if($userid>0){
			$row=query_first("select concat(username,' ', lastname) as customer from ccd9company where compid='$userid'");
			$customer = $row['customer'];
		}
	}
}
$p=$_GET['p'];

include("top.php");?>
<div class="header">
	<h1 class="page-title">Discounts</h1>
	<ul class="breadcrumb">
	<li><a href="index.php">Home</a> </li>
	<li class="active">Discounts</li>
	</ul>

</div>
<div class="main-content">
<?php echo ($msg!="") ? '<div class="err">'.$msg."</div>" : "";?>
<script type='text/javascript' src='js/jquery.autocomplete.js'></script>
<link rel="stylesheet" type="text/css" href="js/jquery.autocomplete.css" />
<script>
$(document).ready(function() {
	$("#customer").autocomplete("listsearch.php?getCustomer=1", {
		width: 420,
		matchContains: true,
		minChars: 0,
		cacheLength: 0,
		selectFirst: false
	});
	$("#customer").result(function(event, data, formatted) {
		$("#userid").val(data[1]);
	});
});

</script>
<div class="row">
  <div class="col-md-8">
    <form name="frm" method="post" action='discount.php' autocomplete="off">
    <div id="myTabContent" class="tab-content">
	  <input type="hidden" name="discid" value="<?php echo $discid?>">
	  <input type="hidden" name="p" value="<?php echo $p?>">
	  <input type="hidden" name="delid">


		<div class="form-group col-md-6 left">
        <label>Discount Code</label>
        <input type="text" name="disccode" value="<?php echo $disccode?>" maxlength="20" class="form-control">
        </div>
		<div class="form-group  col-md-6">
        <label>Details</label>
		<input class="form-control" type="text" name="discdescr" value="<?php echo $discdescr?>" maxlength="120">
        </div>
		<div class="form-group col-md-4 left">
			<label>Discount</label>
			<input type="text" name="discamt" value="<?php echo $discamt?>" maxlength="6" class="form-control">
        </div>
		<div class="form-group col-md-4">
			<label>Discount Type</label>
			<select name="disctype" class="form-control">
				<option value="1" <?php if($disctype==1){echo " selected";}?>>%</option>
				<option value="2" <?php if($disctype==2){echo " selected";}?>>Amount</option>
				<option value="3" <?php if($disctype==3){echo " selected";}?>>Free Shipping</option>
			</select>
        </div>
		<div class="form-group col-md-4">
			<label>Discount Currency</label>
			<select name="disccur" class="form-control">
				<option value="" <?php if($disccur==""){echo " selected";}?>>Select Currency</option>
				<option value="INR" <?php if($disccur=="INR"){echo " selected";}?>>INR</option>
				<option value="US $" <?php if($disccur=="US $"){echo " selected";}?>>USD</option>
			</select>
        </div>
		<div class="form-group col-md-6 left">
			<label>Discount Usage</label>
			<select name="discuse" class="form-control">
				<option value="0" <?php if($discuse==0){echo " selected";}?>>Single</option>
				<option value="1" <?php if($discuse==1){echo " selected";}?>>Multiple</option>
			</select>
        </div>
		<div class="form-group col-md-6">
			<label>For Customer</label>
			<input name="customer" id="customer" type="text" value="<?php echo $customer?>" maxlength="120" class="form-control"/>
			<input type="hidden" id="userid" name="userid" value="<?php echo $userid;?>">
        </div>
		<div class="form-group col-md-6 left">
			<label>Email</label>
			<input type="text" name="discemail" value="<?php echo $discemail?>" maxlength="200" class="form-control">
        </div>
		<div class="form-group col-md-6">
			<label>Expiry Date</label>
			<input type="text" name="discexpiry" value="<?php echo $discexpiry?>" maxlength="10" class="form-control datepicker">
        </div>
		<div class="form-group col-md-6 left">
			<label>Active</label>
			<select name="discstatus" class="form-control col-md-6">
			<option value="1" <?php echo ($discstatus==1) ? " selected" : "";?>>Yes</option>
			<option value="0" <?php echo ($discstatus==0) ? " selected" : "";?>>No</option>
            </select>
        </div>

		<div class="form-group col-md-6 left">
			<label>Exclude Category</label>
			<select name="exclcats[]" multiple class="chosen-select form-control">
				<?php 
				$retval=$retval.'<option value="0">Select Category</option>';

				$sql="select t.typeid, t.typename from ccd9types t where t.opt='2' order by t.typeid";
				$result = $mysqli->query($sql);
				while($rescon = $result->fetch_array()){$retsel="";
					if(in_array($rescon['typeid'],$exclcats)){
						$retsel=" selected";
					}
					$retval=$retval.'<option value="'.$rescon['typeid'].'" '.$retsel.'>'.$rescon['typename'].'</option>';
				} 
				echo $retval;
				?>
			</select>
        </div>

		<div class="form-group col-md-6 left">
			<label>Include Product Type</label>
			<select name="incltype[]" multiple class="chosen-select form-control">
				<?php 
				$retval='<option value="0">Select Product Type</option>';

				$sql="select t.typeid, t.typename from ccd9types t where t.opt='3' order by t.typeid";
				$result = $mysqli->query($sql);
				while($rescon = $result->fetch_array()){$retsel="";
					if(in_array($rescon['typeid'],$incltype)){
						$retsel=" selected";
					}
					$retval=$retval.'<option value="'.$rescon['typeid'].'" '.$retsel.'>'.$rescon['typename'].'</option>';
				} 
				echo $retval;
				?>
			</select>
        </div>

		<div class="form-group col-md-6 left">
			<label>Include Category</label>
			<select name="inclcats[]" multiple class="chosen-select form-control">
				<?php 
				$retval='<option value="0">Select Category</option>';

				$sql="select t.typeid, t.typename from ccd9types t where t.opt='2' order by t.typeid";
				$result = $mysqli->query($sql);
				while($rescon = $result->fetch_array()){$retsel="";
					if(in_array($rescon['typeid'],$inclcats)){
						$retsel=" selected";
					}
					$retval=$retval.'<option value="'.$rescon['typeid'].'" '.$retsel.'>'.$rescon['typename'].'</option>';
				} 
				echo $retval;
				?>
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
<?php include("bot.php");?>