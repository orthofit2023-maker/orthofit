<?php 
session_start();
if(!isset($_SESSION['loginid'])){
	header("location:login.php");
}
include("db5conn.php");
if($_GET['dl']=="xls" && checkuseraccess(0,0,5)){
	header("Content-Type: application/vnd.ms-excel");
	header("Content-disposition:attachment;filename=repoprodincart.xls"); 
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
	<h1 class="page-title">Products in Cart</h1>
	<ul class="breadcrumb">
	<li><a href="index.php">Home</a> </li>
	<li class="active">Products in Cart</li>
	</ul>

</div>
<div class="main-content">
<?php echo $_GET['msg'];?>
<div class="btn-toolbar list-toolbar">
	<form method="get" name="frm" action="repoprodincart.php">
	<input type="hidden" name="delid">
		<input type="text" name="srch" value="<?php echo $_GET['srch']?>" maxlength="250" class="form-control input-srch">
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
      <th>#</th>
      <th>Code</th>
      <th>Product</th>
      <th>Count</th>
    </tr>
  </thead>
  <tbody>
	<?php 
	$i=0; 
	/*
SELECT c.prodname, p.prodcode, c.finalprice, m.city, m.state, cm.countryname, concat(m.username,' ',m.lastname) as ouser, m.email, date_format(c.datemodified,'%d/%b/%y') as cdt FROM `ccd9cart`  c

join ccd9products p on p.prodid=c.prodid 
left join ccd9address m on m.compid=c.compid join ccd9country cm on m.countryid=cm.countryid

where c.orderid=0 
group by c.compid, c.prodid order by c.datemodified desc, c.compid, c.prodid


SELECT  c.prodname, p.prodcode, c.finalprice, o.orderid, date_format(c.datemodified,'%d/%b/%y') as cdt, date_format(o.orddate,'%d/%b/%y') as dt, o.orddate, o.ordtotal, c.prodcur, concat(m.username,' ',m.lastname) as user, concat(o.username,' ',o.lastname) as ouser, o.email, o.billing_city, o.billing_state, o.billing_zipcode, cm.countryname FROM `ccd9orders` o join ccd9cart c on c.compid=o.compid and c.orderid=o.orderid and c.compid>0 and c.status='0' join ccd9products p on p.prodid=c.prodid join ccd9company m on m.compid=o.compid join ccd9country cm on o.billing_country=cm.countryid where o.status=0 and o.ordtotal>0 and o.compid>0 and o.compid not in (2,26,3798,7,11,3,93,96,97,98,99,100,106,536, 3760,3630,1395,1311,700,845,50,90,164,566)  and o.email!='' group by c.prodid, o.orderid order by o.orderid desc


*/
	$sql="SELECT count(*) as cnt, p.prodname, p.prodcode, p.produrl, c.prodid from ccd9cart c join ccd9products p on p.prodid=c.prodid where c.orderid=0 ";
	if($_GET['srch']!="" && $_GET['srch']!="name"){
		$srch=$_GET['srch'];
		$sql=$sql." and ( p.prodname like '%".trim($srch)."%' or p.prodcode like '%".trim($srch)."%') ";
	}
	if($_GET['cur']!=""){
		$sql=$sql." and ( c.prodcur='".trim($cur)."')";
	}
	if($_GET['fdate']!="" && $_GET['tdate']!=""){
		$sql=$sql." and ( c.datemodified between '".sqldate($fdate)."' and '".sqldate($tdate)."')";
	}else if ($_GET['fdate']!=""){
		$sql=$sql." and ( c.datemodified>='".sqldate($fdate)."')";
	}else if ($_GET['tdate']!=""){
		$sql=$sql." and ( c.datemodified<='".sqldate($tdate)."')";
	}
	$sql=$sql." group by c.prodid";
	$sql=$sql." order by cnt desc";
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
		?>
    <tr>
      <td><?php echo $i; //.'-'.$row['prodid'];?></td>
      <td><?php echo $row['prodcode']?></td>
      <td><?php echo $row['prodname']?></td>
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