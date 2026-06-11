<?php 
session_start();
if(!isset($_SESSION['loginid'])){
	header("location:login.php");
}
include("db5conn.php");
$status=inpval($_GET['status']);
$fdate=$_GET['fdate'];
$tdate=$_GET['tdate'];

if($_GET['dl']!="xls"){
include("top.php");
?>
<style>
.tdblu{ color:#6600FF !important;}
.tdred{ color:#FF0000 !important;}
.tdgreen{color:#66CC00 !important;}
.tdorg{color:#FF9900 !important;}
.tdgry{color:#828282 !important;}
.tdwht{color:#000 !important;}
</style>
<div class="header">
	<h1 class="page-title">Orders</h1>
	<ul class="breadcrumb">
	<li><a href="index.php">Home</a> </li>
	<li class="active">Orders</li>
	</ul>

</div>
<div class="main-content">
<?php echo $_GET['msg'];?>
<div class="btn-toolbar list-toolbar">
	<form method="get" name="frm" action="orderspending.php">
	<input type="hidden" name="delid">
		<input type="text" name="srch" value="<?php echo $_GET['srch']?>" maxlength="250" class="form-control input-srch"  placeholder="name / phone / transaction #">
		<input type="text" name="fdate" value="<?php echo $fdate?>" maxlength="10" class="form-control-left datepicker" placeholder="from dd/mm/yyyy">
		<input type="text" name="tdate" value="<?php echo $tdate?>" maxlength="10" class="form-control-left datepicker" placeholder="to dd/mm/yyyy">
		<button type='submit' class="btn btn-primary"><i class="fa fa-search"></i> </button>
		<?php if(checkuseraccess('', '','5')){?>
		<a href='orderspending.php?dl=xls&fdate=<?php echo $fdate?>&tdate=<?php echo $tdate?>&status=<?php echo $status?>&srch=<?php echo $srch?>' class="btn btn-danger"><i class="fa fa-file-excel-o"></i></a>
		<?php }?>
    </form>
  <div class="btn-group">
  </div>
</div>
<?php }else if(checkuseraccess('', '','5')){ 

	header("Content-Type: application/vnd.ms-excel");
	header("Content-disposition:attachment;filename=orderspending.xls"); 
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
}?>
<table class="table table-striped" <?php echo ($_GET['dl']=="xls" ? "border='1'" : "")?>>
  <thead>
    <tr>
      <th>#</th>
      <th>Order#</th>
      <th>Date</th>
      <th>Name</th>
      <th>Phone</th>
      <th>Total</th>
      <th>Email</th>
      <th>City</th>
      <th>State</th>
      <th>Country</th>
	  <?php if($_GET['dl']!="xls"){?>
      <th style="width:100px;"></th>
	  <?php }?>
    </tr>
  </thead>
  <tbody>
	<?php 

	// SELECT  u.status, DATE_FORMAT(u.orddate,'%Y') as yr, DATE_FORMAT(u.orddate,'%d/%b/%Y') as regdate, u.orderid, u.ordcur, u.ordtotal, s.statusname,  concat(u.billing_username,' ',u.billing_lastname) as name, u.email, u.phone, u.billing_city, u.billing_state, u.billing_zipcode, cm.countryname, u.tx, u.invoiceno from ccd9orders u join ccd9orderstatus s on u.status=s.statusid join ccd9country cm on u.billing_country=cm.countryid where u.status>0 order by u.orddate desc

	$i=0; 
	$sql="SELECT  u.status, DATE_FORMAT(u.orddate,'%Y') as yr, DATE_FORMAT(u.orddate,'%d/%b/%Y') as regdate, u.orderid, u.ordcur, u.ordtotal, concat(u.billing_username,' ',u.billing_lastname) as name, u.email, u.phone, u.billing_city, u.billing_state, u.billing_zipcode, cm.countryname, u.tx, u.invoiceno from ccd9orders u join ccd9country cm on u.billing_country=cm.countryid where u.status=0 ";

	if($_GET['srch']!="" && $_GET['srch']!="name"){
		$srch=$_GET['srch'];
		$sql=$sql." and ( u.username like '%".trim($srch)."%' or u.lastname like '%".trim($srch)."%' or u.phone like '%".trim($srch)."%' or u.tx like '%".trim($srch)."%' or u.orderid like '%".trim($srch)."%') ";
	}

	if($_SESSION['loginid']==9 && $_GET['dl']=="xls"){ //9 = Merch.team

		$sql=$sql." and ( u.orddate> (curdate()- interval 15 DAY) )";

	}else{

		if($_GET['fdate']!="" && $_GET['tdate']!=""){
			$sql=$sql." and ( u.orddate between '".sqldate($fdate)."' and '".sqldate($tdate)."')";
		}else if ($_GET['fdate']!=""){
			$sql=$sql." and ( u.orddate>='".sqldate($fdate)."')";
		}else if ($_GET['tdate']!=""){
			$sql=$sql." and ( u.orddate<='".sqldate($tdate)."')";
		}
	}
	$sql=$sql." order by u.orderid desc";
	//echo $sql;
	$result = $mysqli->query($sql);
	$num_rows = mysqli_num_rows($result);
	//echo $num_rows.' - num_rows<BR>';
	if ($num_rows>0){
		if($_GET['dl']!="xls"){
			$p=$_GET['p'];
			include("grojsus.php");
			$g = grojsus($num_rows,$p,25,"","p",true,"SE",10,0);
			$sql = $sql. " LIMIT $g[3],$g[5]";
			$result = $mysqli->query($sql);
		}
		$i=($p*25);
		while($row=$result->fetch_array()){$i++;
		?>
    <tr>
      <td><?php echo $i;?></td>
      <td><?php echo getordno($row['orderid'], $row['orddate'])?></td>
      <td><?php echo $row['regdate']?></td>
      <td><?php echo $row['name']?></td>
      <td><?php echo $row['phone']?></td>
      <td><?php echo $row['ordcur'].' '.number_format($row['ordtotal'])?></td>
	  <td><?php echo $row['email']?></td>
      <td><?php echo $row['billing_city']?></td>
	  <td><?php echo $row['billing_state']?></td>
	  <td><?php echo $row['countryname']?></td>
	  <?php if($_GET['dl']!="xls"){?>
      <td>
          <a href="orderpending.php?orderid=<?php echo $row['orderid']?>&p=<?php echo $p?>"><i class="fa fa-pencil"></i></a>
      </td>
	  <?php }?>
    </tr>
	<?php }
	if($_GET['dl']!="xls"){
	if($num_rows>$g[5]){?>
	<tr>
      <td colspan='9'><?php echo $g[1];?></td>
	</tr>
	<?php } } }?>
  </tbody>
</table> 


<?php 
if($_GET['dl']!="xls"){
	include("bot.php");
}
?>