<?php 
session_start();
if(!isset($_SESSION['loginid'])){
	header("location:login.php");
}
include("db5conn.php");
if($_GET['dl']=="xls" && checkuseraccess(0,0,5)){
	header("Content-Type: application/vnd.ms-excel");
	header("Content-disposition:attachment;filename=repobestseller.xls"); 
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
	<h1 class="page-title">Best Sellers</h1>
	<ul class="breadcrumb">
	<li><a href="index.php">Home</a> </li>
	<li class="active">Best Sellers</li>
	</ul>

</div>
<div class="main-content">
<?php echo $_GET['msg'];?>
<div class="btn-toolbar list-toolbar">
	<form method="get" name="frm" action="repobestseller.php">
	<input type="hidden" name="delid">
		<input type="text" name="srch" value="<?php echo $_GET['srch']?>" maxlength="250" class="form-control input-srch">
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
      <th>#</th>
      <th>Code</th>
      <th>Product</th>
	  <th>Category</th>
	  <th>Type</th>
	  <th>INR</th>
      <th>Count</th>
    </tr>
  </thead>
  <tbody>
	<?php  // left  left join ccd9prod2type1 v1 on v1.prodid=p.prodid left join ccd9types t on v1.typeid=t.typeid left join ccd9prod2cat c1 on c1.prodid=p.prodid left join ccd9types t1 on c1.catid=t1.typeid
	$i=0; 
	$sql="SELECT count(*) as cnt, p.prodid, p.prodprice, p.usdprice, p.prodname, p.prodcode, p.produrl, c.prodid from ccd9cart c join ccd9orders o on c.orderid=o.orderid join ccd9products p on p.prodid=c.prodid where o.status>0 and p.prodcode!='' ";
	if($_GET['srch']!="" && $_GET['srch']!="name"){
		$srch=$_GET['srch'];
		$sql=$sql." and ( p.prodname like '%".trim($srch)."%' or p.prodcode like '%".trim($srch)."%') ";
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
	$sql=$sql." group by c.prodid";
	$sql=$sql." order by cnt desc";
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
		$revlist=$revlist.','.$row['revid'];
		$res=query_first("select typename from ccd9prod2cat c join ccd9types t on c.catid=t.typeid where c.prodid='".$row['prodid']."'");
		$prodcat=$res['typename'];
		$res=query_first("select typename from ccd9prod2type1 c join ccd9types t on c.typeid=t.typeid where c.prodid='".$row['prodid']."'");
		$prodtype=$res['typename'];
		?>
    <tr>
      <td><?php echo $i; //.'-'.$row['prodid'];?></td>
      <td><?php echo $row['prodcode']?></td>
      <td><?php echo $row['prodname']?></td>
	  <td><?php echo $prodcat?></td>
	  <td><?php echo $prodtype?></td>
	  <td nowrap><?php echo 'INR '.$row['prodprice']?></td>
      <td><?php echo $row['cnt']?></td>
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