<?php 
session_start();
if(!isset($_SESSION['loginid'])){
	header("location:login.php");
}
include("db5conn.php");
if($_GET['dl']=="xls" && checkuseraccess(0,0,5)){
	header("Content-Type: application/vnd.ms-excel");
	header("Content-disposition:attachment;filename=repoordcoupons.xls"); 
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
}else{
	include("top.php");
}
$fdate=$_GET['fdate'];
if($fdate=='')$fdate=date('d/m/Y', mktime(0,0,0,1,1,date("Y")));
$tdate=$_GET['tdate'];
if($tdate=='')$tdate=date('d/m/Y', mktime(0,0,0,13,0,date("Y")));
$srch=inpval($_GET['srch']);
$cur=inpval($_GET['cur']);
if($cur=='')$cur='INR';
if($_GET['dl']!="xls"){
?>
<div class="header">
	<h1 class="page-title">Sales by Coupons</h1>
	<ul class="breadcrumb">
	<li><a href="index.php">Home</a> </li>
	<li class="active">Sales by Coupons</li>
	</ul>

</div>
<div class="main-content">
<?php echo $_GET['msg'];?>
<div class="btn-toolbar list-toolbar">
	<form method="get" name="frm" action="repoordcoupons.php">
	<input type="hidden" name="delid">
		<input type="text" name="srch" value="<?php echo $_GET['srch']?>" maxlength="250" class="form-control input-srch" placeholder="coupon">
		<input type="text" name="fdate" value="<?php echo $fdate?>" maxlength="10" class="form-control-left datepicker" placeholder="from dd/mm/yyyy">
		<input type="text" name="tdate" value="<?php echo $tdate?>" maxlength="10" class="form-control-left datepicker" placeholder="to dd/mm/yyyy">
		
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
      <th>Coupon</th>
      <th>Value (<?php echo (trim($cur)!='' ? trim($cur) : "US $/INR");?>)</th>
    </tr>
  </thead>
  <tbody>
	<?php 
	$i=0; 
	$sql="SELECT sum(ordtotal) as cnt, o.disccode FROM `ccd9orders` o where o.status>0  and disccode!='' ";
	if($_GET['srch']!="" && $_GET['srch']!="name"){
		$srch=$_GET['srch'];
		$sql=$sql." and ( o.disccode like '%".trim($srch)."%') ";
	}
	if($cur!=""){
		$sql=$sql." and ( o.ordcur='".trim($cur)."')";
	}
	if($fdate!="" && $tdate!=""){
		$sql=$sql." and ( o.orddate between '".sqldate($fdate)."' and '".sqldate($tdate)."')";
	}else if ($fdate!=""){
		$sql=$sql." and ( o.orddate>='".sqldate($fdate)."')";
	}else if ($tdate!=""){
		$sql=$sql." and ( o.orddate<='".sqldate($tdate)."')";
	}
	$sql=$sql." group by o.disccode order by cnt desc";
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
      <td><?php echo dbval($row['disccode'])?></td>
      <td><?php echo number_format($row['cnt'])?></td>
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