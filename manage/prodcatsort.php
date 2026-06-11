<?php 
session_start();
if(!isset($_SESSION['loginid'])){
	header("location:login.php");
}
include("db5conn.php");

if($_POST['cnt']>0){
	$catid=intval($_POST['catid']);
	for($x=1;$x<=intval($_POST['cnt']);$x++){
		$sortby=intval($_POST['sortby'.$x]);
		$prodid=intval($_POST['prodid'.$x]);
		$query="update `ccd9prod2cat` set sortby='$sortby' where prodid='$prodid' and catid='$catid' ";
		//echo $query.'<br>';
		$mysqli->query($query);
	}
	//exit();
	$msg="Product sorting updated successfully!";
	header("Location:prodcatsort.php?msg=$msg&catid=$catid");
}
include("top.php");
if($_GET['msg']!=""){
	$msg=$_GET['msg'];
}
$catid=inpval($_GET['catid']);
?>
<div class="header">
	<h1 class="page-title">Product Sorting</h1>
	<ul class="breadcrumb">
	<li><a href="index.php">Home</a> </li>
	<li class="active">Product Sorting</li>
	</ul>

</div>
<div class="main-content">
<?php echo ($msg!="") ? '<div class="err">'.$msg."</div>" : "";?>
<div class="btn-toolbar list-toolbar">
	<form method="get" name="frm" action="prodcatsort.php">
	<input type="hidden" name="delid">
		<select name="catid" class="form-control input-srch" onchange="document.frm.submit()">
		<?php echo getcatmenu(0,$catid);?>
		</select>
		<input type="checkbox" name="sortreset" value='1' class="checkbox-inline" <?php echo ($_GET['sortreset']=='1' ? 'checked' : '')?>> Reset Seq
		<button type='submit' class="btn btn-primary"><i class="fa fa-search"></i> </button>
    </form>
  <div class="btn-group">
  </div>
</div>
<?php if($_GET['catid']>0){?>
<form method="post" name="frmin" action="prodcatsort.php">
<input type="hidden" name="catid" value="<?php echo $catid?>">
<table class="table table-striped">
  <thead>
    <tr>
      <TH>Sr</TH>
      <th>Code</th>
      <th>Name</th>
	  <th>Sort No.</th>
    </tr>
  </thead>
  <tbody>
	<?php 
	
	$i=0; 
	$sql="SELECT p.prodcode, p.entrydate, c.*, p.prodname, p.produrl FROM `ccd9prod2cat` c join ccd9products p on c.prodid=p.prodid where p.prodstatus=1 ";

	$sql=$sql." and ( c.catid = '".inpval($catid)."') ";

	
	$sql=$sql." order by c.sortby, p.sortby, p.entrydate desc, p.prodid";
	$result = $mysqli->query($sql);
	$num_rows = mysqli_num_rows($result);
	if ($num_rows>0){
		while($row=$result->fetch_array()){ $i++;
		?>
    <tr>
      <td><?php echo $i;?><input type="hidden" name="prodid<?php echo $i;?>" value="<?php echo $row['prodid']?>"></td>
	  <td nowrap><?php echo $row['prodcode']?></td>
      <td><?php echo $row['prodname']?></td>
	  <td><input type="text" class="form-control col-md-1" name="sortby<?php echo $i;?>" value="<?php echo ($_GET['sortreset']=='1' ? $i : $row['sortby'])?>"></td>
    </tr>
	<?php }?>
	<tr>
      <td colspan='2'><input type="text" name="chgord" class="form-control col-md-1 left" value="" onblur="chgsort()" placeholder="sr no"></td>
	  <td></td>
	  <td><button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button> <input type="hidden" name="cnt" value="<?php echo $i?>"></td>
	</tr>
	<?php } ?>
  </tbody>
</table>
<script>
function chgsort(){
	var x = parseInt(document.frmin.chgord.value);
	var n = parseInt(document.frmin.cnt.value);
	for(var v=x;v<=n;v++){
		var z = eval("document.frmin.sortby"+v);
		z.value=parseInt(z.value)+1;
	}
}
</script>
<?php } 
include("bot.php");?>