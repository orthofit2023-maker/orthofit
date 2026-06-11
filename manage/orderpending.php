<?php 
session_start();
if(!isset($_SESSION['loginid'])){
	header("location:login.php");
}
include("db5conn.php");

if(intval($_POST['orderid'])>0){
	$orderid = intval($_POST['orderid']);
	$tx = inpval($_POST['tx']);
	$sendemail = intval($_POST['sendemail']);
	$orddate = sqldate($_POST['orddate']);
	$ordsession = trim($_POST['ordsession']);


	$mysqli->query("update ccd9orders set orddate='$orddate', status='1', tx='$tx', bankref='NA' where orderid='$orderid'");

	$mysqli->query("update ccd9cart set orderid='$orderid', status='1' where sessionid='$ordsession' and prodqty>0 and status='0'");
	
	

	sendordemail($orderid);

	//processgiftcard($orderid);
	
	//redimdiscountemail($orderid);

	//remsaleprod($orderid);
	header("Location:orders.php?msg=$msg");

}else if($_GET['orderid']!=""){
	$orderid=trim($_GET['orderid']);
	$res=query_first("select * from ccd9orders c where c.orderid='$orderid'");
	//$comments=dbval($res['comments']);
	$statusid=dbval($res['status']);
}


include("top.php");?>
<div class="header hidden-print">
	<h1 class="page-title">Abandoned Order</h1>
	<ul class="breadcrumb">
	<li><a href="index.php">Home</a> </li>
	<li class="active">Abandoned Order</li>
	</ul>

</div>
<div class="main-content">
<div class="row">
  <div class="col-md-8">
  <?php include("orderpgpending.php"); 
  echo '<div class="pull-left"><h4>Order No: '.getordno($orderid).'</h4></div>';
  echo $ordpg;

  ?>
  </div>
</div>

<div class="row hidden-print">

  <div class="col-md-4">
    <form name="frm" method="post" action='orderpending.php'>
    <div id="myTabContent" class="tab-content">
	  <input type="hidden" name="orderid" value="<?php echo $orderid?>">
	  <input type="hidden" name="ordsession" value="<?php echo $ordsession?>">
		<div class="form-group">
          <label>Transaction Details</label> 
			<input type="text" name="tx" class="form-control">
        </div>
		<div class="form-group">
          <label>Transaction Date</label> 
			<input type="text" name="orddate" class="form-control datepicker">
        </div>
		<!-- <div class="form-group">
          <label><input type="checkbox" name="sendemail" value="1" class="checkbox-inline"> Send email</label>  
		</div> -->
    </div>

    <div class="btn-toolbar list-toolbar">
      <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Update Order</button>
    </div>
    </form>
  </div>
</div>
<?php include("bot.php");?>