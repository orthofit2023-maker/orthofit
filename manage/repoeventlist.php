<?php 
session_start();
if(!isset($_SESSION['loginid'])){
	header("location:login.php");
}
include("db5conn.php");
if($_GET['dl']=="xls" && checkuseraccess(0,0,5)){
	header("Content-Type: application/vnd.ms-excel");
	header("Content-disposition:attachment;filename=repoeventlist.xls"); 
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
	<h1 class="page-title">Landing Page Listing</h1>
	<ul class="breadcrumb">
	<li><a href="index.php">Home</a> </li>
	<li class="active">Landing Page Listing</li>
	</ul>

</div>
<div class="main-content">
<?php echo $_GET['msg'];?>
<div class="btn-toolbar list-toolbar">
	<form method="get" name="frm" action="repoeventlist.php">
	<input type="hidden" name="delid">
		<input type="text" name="srch" value="<?php echo $_GET['srch']?>" maxlength="250" class="form-control input-srch">
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
      <th>Landing Page</th>
      <th>Name</th>
      <th>Email</th>
      <th>Mobile</th>
      <th>City</th>
	  <th>Date/Time</th>
    </tr>
  </thead>
  <tbody>
	<?php 
	$i=0; 
	$sql="SELECT t.*, date_format(createdon, '%d/%b/%y %H:%s:%i') as dt from ccd9trunkshow t where 1=1 "; //createdon>='2022-06-01'
	if($_GET['srch']!="" && $_GET['srch']!="name"){
		$srch=$_GET['srch'];
		$sql=$sql." and ( email like '%".trim($srch)."%' or username like '%".trim($srch)."%' or mobile like '%".trim($srch)."%' or city like '%".trim($srch)."%') ";
	}

	$sql=$sql." group by email, city";
	$sql=$sql." order by tid desc";
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
      <td><?php echo $i;?></td>
      <td><?php echo dbval($row['event'])?></td>
      <td><?php echo dbval($row['username'])?></td>
      <td><?php echo dbval($row['email'])?></td>
      <td><?php echo dbval($row['mobile'])?></td>
      <td><?php echo dbval($row['city'])?></td>
	  <td><?php echo trim($row['dt'])?></td>
    </tr>
	<?php } 
	if($_GET['dl']!="xls"){
	?>
	<tr>
      <td colspan='6'><?php if($num_rows>$g[5]){ echo $g[1];}?></td>
	</tr>
	<?php } }?>
  </tbody>
</table>

<?php 
if($_GET['dl']!="xls"){
	include("bot.php");
}?>