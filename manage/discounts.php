<?php 
session_start();
if(!isset($_SESSION['loginid'])){
	header("location:login.php");
}
include("db5conn.php");

if($_POST['revlist']!=''){
	$qdids=implode(",",$_POST['challans']);
	$revlist=inpval($_POST['revlist']);
	if($qdids=='')$qdids='0';

	$sql="update ccd9discounts set discstatus='1' where discid in (".$qdids.")";
	$mysqli->query($sql);
	//echo $sql;

	$sql="update ccd9discounts set discstatus='0' where discid not in (".$qdids.") and discid in (".$revlist.")";
	$mysqli->query($sql);
	//echo $sql;
	$msg="Discounts updated successfully!";
	header("Location:discounts.php?msg=$msg");
}

include("top.php");?>
<div class="header">
	<h1 class="page-title">Discounts</h1>
	<ul class="breadcrumb">
	<li><a href="index.php">Home</a> </li>
	<li class="active">Discounts</li>
	</ul>

</div>
<div class="main-content">
<?php echo $_GET['msg'];?>
<div class="btn-toolbar list-toolbar">
	<form method="get" name="frm" action="discounts.php">
	<input type="hidden" name="delid">
		<input type="text" name="srch" value="<?php echo $_GET['srch']?>" maxlength="250" class="form-control input-srch">
		<input type="checkbox" name="showall" value='1' class="checkbox-inline" <?php echo ($_GET['showall']=='1' ? 'checked' : '')?>> Show All
		<button type='submit' class="btn btn-primary"><i class="fa fa-search"></i> </button>
		<a class="btn btn-primary" href="discount.php"><i class="fa fa-plus"></i> Add New</a>
    </form>
  <div class="btn-group">
  </div>
</div>
<form method="post" name="frmin" action="discounts.php">
<table class="table table-striped">
  <thead>
    <tr>
      <th>#</th>
      <th>Code</th>
	  <th>Details</th>
	  <th>Value</th>
	  <th>Type</th>
	  <th>Currency</th>
	  <th>Usage</th>
	  <th>Customer</th>
	  <th>Email</th>
	  <th>From Date</th>
	  <th>Expiry Date</th>
	  <th>Order #</th>
	  <th>Status</th>
    </tr>
  </thead>
  <tbody>
	<?php
	$i=0; 
	$sql="select u.*, concat(c.username,' ', c.lastname) as customer, t.orderid from ccd9discounts u left join ccd9company c on u.compid=c.compid left join ccd9cart t on t.cartid=u.cartid where 1=1";
	if($_GET['srch']!=""){
		$srch=$_GET['srch'];
		$sql=$sql." and u.disccode like '%".trim($srch)."%' or discdescr like '%".trim($srch)."%' or c.username like '%".trim($srch)."%' or c.email='".trim($srch)."' ";
	}
	if($_GET['showall']=="1"){

	}else{
		$sql=$sql." and u.discdescr!='cart discount'";
	}
	$sql=$sql." group by u.discid order by u.discid desc";
	$result = $mysqli->query($sql);
	$num_rows = mysqli_num_rows($result);
	if ($num_rows>0){
		if($_GET['dl']!="xls"){
			$p=$_GET['p'];
			include("grojsus.php");
			$g = grojsus($num_rows,$p,25,"","p",true,"SE",10,0);
			$sql = $sql. " LIMIT $g[3],$g[5]";
		}
		$result = $mysqli->query($sql);
		$i=($p*25); $revlist='0';
		while($row=$result->fetch_array()){$i++;
		$revlist=$revlist.','.$row['discid'];
		?>
    <tr>
      <td><?php echo $i;?></td>
      <td nowrap><?=$row['disccode']?></td>
	  <td><?php echo $row['discdescr']?></td>
	  <td><?php  echo ($row['disctype']!=3) ? $row['discamt'] : "";?></td>
	  <td><?php  if($row['disctype']==1){echo "%";}else if($row['disctype']==2){echo "Amount";}else if($row['disctype']==3){echo "Free Shipping";}?></td>
	  <td><?php  echo ($row['disctype']==2) ? $row['disccur'] : "";?></td>
	  <td><?php echo ($row['discuse']==0) ? "Single" : "Multiple";?></td>
	  <td><?php echo ($row['customer']!="") ? $row['customer'] : "NA";?></td>
	  <td><?php echo ($row['discemail']!="") ? $row['discemail'] : "NA";?></td>
	  <td><?php echo ($row['discstart']!="") ? inddate($row['discstart']) : "NA";?></td>
	  <td><?php echo ($row['discexpiry']!="") ? inddate($row['discexpiry']) : "NA";?></td>
	  <td><?php echo ($row['orderid']>0) ? getordno($row['orderid']) : "NA";?></td>
      <td><input type="checkbox" class="checkbox-inline" name="challans[]" value="<?php echo $row['discid']?>" <?php echo ($row['discstatus']==1 ? 'checked' : '')?>> &nbsp; 
	  <a href="discount.php?discid=<?php echo $row['discid']?>&p=<?php echo $p?>"><i class="fa fa-pencil"></i></a> &nbsp; <a href="orders.php?discid=<?php echo $row['discid']?>" target="_blank"><i class="fa fa-search"></i></a>
	  </td>
    </tr>
	<?php } 
	?>
	<tr>
      <td colspan='10'><?php if($num_rows>$g[5]){ echo $g[1];}?></td>
	  <td colspan='2'><input type="hidden" name="revlist" value="<?php echo $revlist?>"><input type="submit" class="btn btn-primary" value="Activate"></td>
	</tr>
	<?php  }?>
  </tbody>
</table>
</form>
<script>
function checkall(x){
	var y =document.frmin.elements['challans[]'];
	if(y.length){
		for(n=0;n<y.length;n++){
			if(x.checked==true ){
				y[n].checked=true;

			}else{
				y[n].checked=false;
			}
		}
	}else{
		if(x.checked==true ){
			y.checked=true;
		}else{
			y.checked=false;
		}
	}
}
</script>
<?php include("bot.php");?>