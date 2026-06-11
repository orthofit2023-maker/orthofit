<?php 
session_start();
if(!isset($_SESSION['loginid'])){
	header("location:login.php");
}
include("db5conn.php");

if(inpval($_POST['orderid'])!=""){
	$orderid = inpval($_POST['orderid']);
	$statusid = inpval($_POST['statusid']);
	$oldstatusid = inpval($_POST['oldstatusid']);
	$comments = inpval($_POST['comments']);
	//$comments = preg_replace('/[\x00-\x1F\x7F\xA0]/u/','', $comments);
	$sendemail = inpval($_POST['sendemail']);
	$p=$_POST['p'];

	$res=query_first("select * from ccd9orders c where c.orderid='$orderid'");
	$oldcomments=dbval($res['comments']);
	if($res['status']==1){
		$sql="insert into ccd9orderhistory (orderid, comments, statusid, loginid) values ('$orderid', '$oldcomments', '$oldstatusid', '".$_SESSION['loginid']."')";
		$mysqli->query($sql);
	}

	$sql="update ccd9orders set status='$statusid', comments='$comments' where orderid='$orderid'";
	$mysqli->query($sql);
	$msg="Order updated successfully!";

	$sql="insert into ccd9orderhistory (orderid, comments, statusid, loginid, sendemail) values ('$orderid', '$comments', '$statusid', '".$_SESSION['loginid']."', '$sendemail')";
	$mysqli->query($sql);

	if($statusid>0 && $sendemail==1){
		//if($oldstatusid!=$statusid){
			$row=query_first("select concat(username,' ',lastname) as name, email, orddate from ccd9orders where orderid='$orderid'");
			$customername=$row['name'];
			$customeremail=$row['email'];
			$invoiceno = getordno($orderid, $row['orddate']);

			$row=query_first("select * from ccd9orderstatus where statusid='$statusid'");
			//$subject="Your $adminuser Order ".$invoiceno;

			$subject=str_replace("%order_id%",$invoiceno,trim($row['subject']));

			$email=str_replace("%customername%",$customername,trim($row['email']));
			$email=str_replace("%comments%",stripslashes($comments),$email);
			$email=str_replace("%order_id%",$invoiceno,$email);
			$email=str_replace("%orderstatus%",trim($row['statusname']),$email);
			$email=str_replace("\n","<br>",$email);

			$email='<p><img src="https://www.orthofitmart.com/assets/images/email-logo.jpg" style="height:90px;"></p>'.$email;

			//$email=''.$email;
			//$email=$email.$adminuser;
			//$to=$customeremail; //$customeremail

			//$technicalemail='samir.sudrik@gmail.com';
			//exit();
			sendsmtpmail($customeremail,$subject,$email,$technicalemail);
		//}
	}
	header("Location:orders.php?msg=$msg&p=$p");
}else if($_GET['orderid']!="" && $_GET['resend']=="1"){
	$orderid=trim($_GET['orderid']);
	include("orderpg.php"); 

	sendordemail($orderid);

}else if($_GET['orderid']!=""){
	$orderid=trim($_GET['orderid']);
	$res=query_first("select * from ccd9orders c where c.orderid='$orderid'");
	//$comments=dbval($res['comments']);
	$statusid=dbval($res['status']);
}


include("top.php");?>
<div class="header hidden-print">
	<h1 class="page-title">Orders</h1>
	<ul class="breadcrumb">
	<li><a href="index.php">Home</a> </li>
	<li class="active">Orders</li>
	</ul>

</div>
<div class="main-content">
<div class="row">
  <div class="col-md-8">
  <?php include("orderpg.php"); 
  echo '<div class="pull-left"><h4>Order No: '.getordno($orderid).'</h4></div>';
  if(checkuseraccess('', '60','4')){
  echo '<div class="pull-right">
      <a class="btn btn-primary" href="order.php?orderid='.$orderid.'&resend=1"><i class="fa fa-envelope"></i> Resend Email</a>
    </div>';
  }
  echo $ordpg;

  ?>
  </div>
</div>

<div class="row hidden-print">

  <div class="col-md-4">
    <form name="frm" method="post" action='order.php'>
    <div id="myTabContent" class="tab-content">
	  <input type="hidden" name="orderid" value="<?php echo $orderid?>">
	  <input type="hidden" name="oldstatusid" value="<?php echo $oldstatusid?>">
	  <input type="hidden" name="p" value="<?php echo $p?>">
        <div class="form-group">
          <label>Status</label>
          <select name="statusid" class="form-control">
			<?php 
			$sql = "select * from ccd9orderstatus order by sortby";
			$result = $mysqli->query($sql);
			while($row=$result->fetch_array()){?>
			<option value="<?php echo $row['statusid']?>" <?php echo ($row['statusid']==$statusid) ? "selected" : "";?>><?php echo $row['statusname']?></option>
			<?php }?>
          </select>
        </div>
		<div class="form-group">
          <label>Comments</label> 
          <textarea rows="3" name="comments" class="form-control"><?php echo $comments?></textarea>
        </div>
		<div class="form-group">
          <label><input type="checkbox" name="sendemail" value="1" class="checkbox-inline"> Send email to customer</label>  
		</div>
    </div>

    <div class="btn-toolbar list-toolbar">
      <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Update Order</button>
    </div>
    </form>
  </div>
</div>
<div class="row hidden-print">
  <div class="col-md-8">
		<table class="table">
		  <thead>
			<tr>
			  <th>#</th>
			  <th>Date</th>
			  <th>Status</th>
			  <th>Comments</th>
			  <th>Customer Updated</th>
			  <th>Updated By</th>
			</tr>
		  </thead>
		  <tbody>
			<?php 
			$i=0; 
			$sql="SELECT s.statusname, u.sendemail, u.orderid, u.comments, DATE_FORMAT(u.datemodified,'%d/%b/%Y') as regdate, n.name as loggedin from ccd9orderhistory u left join ccd9orderstatus s on u.statusid=s.statusid left join ccd9user n on u.loginid=n.loginid where u.orderid='$orderid' ";
			$sql=$sql." order by u.datemodified desc";
			$result = $mysqli->query($sql);
			$num_rows = mysqli_num_rows($result);
			if ($num_rows>0){
				while($row=$result->fetch_array()){$i++;
				?>
			<tr>
			  <td><?php echo $i;?></td>
			  <td><?php echo $row['regdate']?></td>
			  <td><?php echo $row['statusname']?></td>
			  <td><?php echo str_replace("\n","<BR>",$row['comments'])?></td>
			  <td><?php echo $row['sendemail']==1 ? 'Yes' : 'No';?></td>
			  <td><?php echo $row['loggedin']?></td>
			</tr>
			<?php } }?>
		  </tbody>
		</table> 
  </div>
</div>
<?php include("bot.php");?>