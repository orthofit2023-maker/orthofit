<?php 
session_start();
if(!isset($_SESSION['loginid'])){
	header("location:login.php");
}
include("db5conn.php");
if($_GET['dl']=="xls" && checkuseraccess(0,0,5)){
	header("Content-Type: application/vnd.ms-excel");
	header("Content-disposition:attachment;filename=repoordabandcart.xls"); 
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
}else{
	include("top.php");
}
$fdate=$_GET['fdate'];
$tdate=$_GET['tdate'];
$srch=inpval($_GET['srch']);
$cur=inpval($_GET['cur']);
if($_GET['dl']!="xls"){
?>
<div class="header">
	<h1 class="page-title">Abandoned Orders</h1>
	<ul class="breadcrumb">
	<li><a href="index.php">Home</a> </li>
	<li class="active">Abandoned Orders</li>
	</ul>

</div>
<div class="main-content">
<?php echo $_GET['msg'];?>
<div class="btn-toolbar list-toolbar">
	<form method="get" name="frm" action="repoordabandcart.php">
	<input type="hidden" name="delid">
		<input type="text" name="srch" value="<?php echo $_GET['srch']?>" maxlength="250" class="form-control input-srch"  placeholder="name or email">
		<input type="text" name="fdate" value="<?php echo $fdate?>" maxlength="10" class="form-control-left datepicker" placeholder="from dd/mm/yyyy">
		<input type="text" name="tdate" value="<?php echo $tdate?>" maxlength="10" class="form-control-left datepicker" placeholder="to dd/mm/yyyy">
		<select name="cur" class="form-control-left" onchange="document.frm.submit()">
			<?php echo getcurmenu($cur);?>
		</select>
		<button type='submit' class="btn btn-primary"><i class="fa fa-search"></i> </button>
		<?php if(checkuseraccess(0,0,5)){?>
		<a href='?dl=xls&fdate=<?php echo $fdate?>&tdate=<?php echo $tdate?>&srch=<?php echo $srch?>&cur=<?php echo $cur?>' class="btn btn-danger"><i class="fa fa-file-excel-o"></i></a>
		<?php }?>
    </form>
</div>
<?php }?>
<table class="table table-striped" <?php echo ($_GET['dl']=="xls" ? 'border="1"' : '')?>>
  <thead>
    <tr>
      <th>Order #</th>
      <th>Date</th>
      <th>Customer</th>
      <th>Email</th>
      <th>Currency</th>
      <th>Order Value</th>
	  <th>Code</th>
      <th>Product</th>
      <th>Value</th>
    </tr>
  </thead>
  <tbody>
	<?php 
	$i=0; 
	$sql="SELECT c.prodname, p.prodcode, c.finalprice, o.orderid, date_format(o.orddate,'%d/%b/%y') as dt, o.orddate, o.ordtotal, o.ordcur, concat(m.username,' ',m.lastname) as user, concat(o.username,' ',o.lastname) as ouser, o.email FROM ccd9cart c join `ccd9orders` o on c.orderid=o.orderid join ccd9products p on p.prodid=c.prodid join ccd9company m on m.email=o.email where o.status='0'  ";
	if($_GET['srch']!="" && $_GET['srch']!="name"){
		$srch=$_GET['srch'];
		$sql=$sql." and ( o.username like '%".trim($srch)."%' or o.lastname like '%".trim($srch)."%' or o.email like '%".trim($srch)."%' or o.orderid like '%".trim($srch)."%') ";
	}
	if($_GET['cur']!=""){
		$sql=$sql." and ( o.ordcur='".trim($cur)."')";
	}
	if($_GET['fdate']!="" && $_GET['tdate']!=""){
		$sql=$sql." and ( o.orddate between '".sqldate($fdate)."' and '".sqldate($tdate)."')";
	}else if ($_GET['fdate']!=""){
		$sql=$sql." and ( o.orddate>='".sqldate($fdate)."')";
	}else if ($_GET['tdate']!=""){
		$sql=$sql." and ( o.orddate<='".sqldate($tdate)."')";
	}
	$sql=$sql." group by c.prodid, o.orderid order by o.orderid desc";
	//echo $sql;
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
		?>
    <tr>
      <td><?php echo getordno($row['orderid'], $row['orddate'])?></td>
      <td><?php echo dbval($row['dt'])?></td>
      <td><?php echo (trim($row['user'])!='' ? $row['user'] : $row['ouser'])?></td>
      <td><?php echo $row['email']?></td>
      <td><?php echo $row['ordcur']?></td>
      <td><?php echo number_format($row['ordtotal'])?></td>
	  <td><?php echo $row['prodcode']?></td>
      <td><?php echo $row['prodname']?></td>
	  <td><?php echo number_format($row['finalprice'])?></td>
    </tr>
	<?php } 
	if($_GET['dl']!="xls"){
	?>
	<tr>
      <td colspan='4'><?php if($num_rows>$g[5]){ echo $g[1];}?></td>
	</tr>
	<?php } }?>
  </tbody>
</table>

<?php 
if($_GET['dl']!="xls"){
	include("bot.php");
}?>