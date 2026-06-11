<?php 
session_start();
if(!isset($_SESSION['loginid'])){
	header("location:login.php");
}
include("db5conn.php");
if($_POST['delid']!=""){
	$mysqli->query("delete from ccd9types where typeid='".$_POST['delid']."'");
	$msg= "Banner updated successfully!";
	header("Location:herobanners.php?msg=$msg");
}
include("top.php");
$opt=$_GET['opt'];
$status=intval($_GET['status']);
?>
<div class="header">
	<h1 class="page-title">Banners</h1>
	<ul class="breadcrumb">
	<li><a href="index.php">Home</a> </li>
	<li class="active">Banners</li>
	</ul>

</div>
<div class="main-content">
<?php echo $_GET['msg'];?>
<div class="btn-toolbar list-toolbar">
	<form method="get" name="frm" action="herobanners.php">
		<input type="hidden" name="delid">
		<select name="opt" class="form-control input-srch" onchange="document.frm.submit()">
			<option value="0" <?php echo ($opt==0) ? " selected" : "";?>>Banner Position</option>
			<option value="101" <?php echo ($opt=='101' ? 'selected' : '')?>>Desktop</option>
			<option value="102" <?php echo ($opt=='102' ? 'selected' : '')?>>Mobile</option>
			<option value="103" <?php echo ($opt=='103' ? 'selected' : '')?>>Other</option>
		</select>
		<select name="status" class="form-control input-srch" onchange="document.frm.submit()">
			<option value="0" <?php echo ($status=='0' ? 'selected' : '')?>>Active Status</option>
			<option value="1" <?php echo ($status=='1' ? 'selected' : '')?>>Yes</option>
			<option value="2" <?php echo ($status=='2' ? 'selected' : '')?>>No</option>
		</select>
		<button type='submit' class="btn btn-primary"><i class="fa fa-search"></i> </button>
	<a class="btn btn-primary" href="herobanner.php"><i class="fa fa-plus"></i> Add New</a>
    </form>
  <div class="btn-group">
  </div>
</div>

<table class="table table-striped">
  <thead>
    <tr>
      <th>Seq. No.</th>
      <th>Photo</th>
      <th>URL</th>
      <th>INR/USD</th>
      <th>Type</th>
	  <th>Active</th>
    </tr>
  </thead>
  <tbody>
	<?php 
	$i=0; 
	$sql="select * from ccd9types t where t.opt in ('101','102','103') ".($opt>0 ? " and t.opt='$opt'" : "" )."".($status>0 ? " and t.typevalue2='$status'" : "" )." order by t.opt, CAST(t.typevalue1 AS UNSIGNED )";
	$result = $mysqli->query($sql);
	while($rescon = $result->fetch_array()){$i++;
		list($banner,$url)=explode(",",dbval($rescon['typename']));
		?>
    <tr>
      <td><?php echo $rescon['typevalue1']?></td>
      <td><img src="https://www.payalsinghal.com/images/<?php echo $banner?>" height="100" /></td>
      <td><?php echo $url?></td>
      <td><?php echo $rescon['typevalue']?></td>
      <td><?php echo ($rescon['opt']=='101' ? 'Desktop' : ($rescon['opt']=='102' ? 'Mobile' : 'Other')).($rescon['opt']=='103' ?  $rescon['typevalue1'] : '')?></td>
      <td><?php echo ($rescon['typevalue2']=='1' ? 'Yes' : 'No')?></td>
	  <td>
		  <a href="herobanner.php?typeid=<?php echo $rescon['typeid']?>&p=<?php echo $p?>"><i class="fa fa-pencil"></i></a>
          <a href="#" data-href="<?php echo $rescon['typeid']?>" role="button" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
      </td>
    </tr>
	<?php } 
	?>
  </tbody>
</table>
<?php include("bot.php");?>