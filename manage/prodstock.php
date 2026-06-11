<?php 
session_start();
if(!isset($_SESSION['loginid'])){
	header("location:login.php");
}
include("db5conn.php");

if($_POST['cnt']>0){
	$catid=intval($_POST['catid']);
	$type1=intval($_POST['type1']);
	$type2=intval($_POST['type2']);
	$type3=intval($_POST['type3']);
	for($x=1;$x<=intval($_POST['cnt']);$x++){
		$stockid=intval($_POST['stockid'.$x]);
		$prodqty=intval($_POST['prodqty'.$x]);
		$query="update ccd9stocks set prodqty='$prodqty' where stockid='$stockid' ";
		//echo $query.'<br>';
		$mysqli->query($query);
	}
	//exit();
	$msg="Records updated successfully!";
	header("Location:prodstock.php?msg=$msg&catid=$catid&type1=$type1&type2=$type2&type3=$type3");
}
include("top.php");
if($_GET['msg']!=""){
	$msg=$_GET['msg'];
}
$type1=inpval($_GET['type1']);
$catid=inpval($_GET['catid']);
$type2=inpval($_GET['type2']);
$type3=inpval($_GET['type3']);
?>
<div class="header">
	<h1 class="page-title">Product Stock</h1>
	<ul class="breadcrumb">
	<li><a href="index.php">Home</a> </li>
	<li class="active">Product Stock</li>
	</ul>

</div>
<div class="main-content">
<?php echo ($msg!="") ? '<div class="err">'.$msg."</div>" : "";?>
<div class="btn-toolbar list-toolbar">
	<form method="get" name="frm" action="prodstock.php">
	<input type="hidden" name="delid">
		<input type="text" name="srch" value="<?php echo $_GET['srch']?>" maxlength="40" class="form-control input-srch">
		
		<select name="catid" class="form-control input-srch" onchange="document.frm.submit()">
		<?php echo getcatmenu(0,$catid);?>
		</select>
		<select name="type1" class="form-control input-srch" onchange="document.frm.submit()">
		<?php echo gettype1menu(0,$type1);?>
		</select>
		<select name="type2" class="form-control input-srch" onchange="document.frm.submit()">
		<?php echo gettype2menu(0,$type2);?>
		</select>
		<select name="type3" class="form-control input-srch" onchange="document.frm.submit()">
		<?php echo gettype3menu(0,$type3);?>
		</select>
		<button type='submit' class="btn btn-primary"><i class="fa fa-search"></i> </button>
    </form>
  <div class="btn-group">
  </div>
</div>
<?php if($_GET['catid']>0){?>
<form method="post" name="frmin" action="prodstock.php">
<input type="hidden" name="catid" value="<?php echo $catid?>">
<input type="hidden" name="type1" value="<?php echo $type1?>">
<input type="hidden" name="type2" value="<?php echo $type2?>">
<input type="hidden" name="type3" value="<?php echo $type3?>">
<table class="table table-striped">
  <thead>
    <tr>
      <TH>Sr</TH>
      <th>Code</th>
      <th>Name</th>
	  <th>Fit</th>
	  <th>Colour</th>
	  <th>Size</th>
  	  <th>Qty</th>

    </tr>
  </thead>
  <tbody>
	<?php 
	
	$i=0; 
	$sql="SELECT p.prodcode,s.*, p.prodname, t1.typename as type1name, t3.typename as type3name FROM ccd9stocks s join ccd9prod2cat c on s.prodid=c.prodid   join ccd9products p on s.prodid=p.prodid  join ccd9types t1 on t1.typeid=s.type1 join ccd9types t3 on t3.typeid=s.type3  where p.prodstatus=1 ";

	$sql=$sql." and ( c.catid = '".inpval($catid)."') ";
	if($type1>0){
		$sql=$sql." and ( s.type1 = '".inpval($type1)."') ";
	}
	if($type2>0){
		$sql=$sql." and ( s.type2 = '".inpval($type2)."') ";
	}
	if($type3>0){
		$sql=$sql." and ( s.type3 = '".inpval($type3)."') ";
	}
	if($_GET['srch']!=""){
		$srch=$_GET['srch'];
		$sql=$sql." and ( p.prodname like '%".trim($srch)."%' or p.prodcode like '%".trim($srch)."%' or p.proddesc like '%".trim($srch)."%' ) ";
	}

	
	$sql=$sql." group by s.stockid order by p.prodid, s.type1, s.type3, s.type2";
	$result = $mysqli->query($sql);
	$num_rows = mysqli_num_rows($result);
	if ($num_rows>0){
		while($row=$result->fetch_array()){ $i++;
		?>
    <tr>
      <td><?php echo $i;?><input type="hidden" name="stockid<?php echo $i;?>" value="<?php echo $row['stockid']?>"></td>
	  <td nowrap><?php echo $row['prodcode']?></td>
      <td><?php echo $row['prodname']?></td>
      <td><?php echo $row['type1name']?></td>
      <td><?php echo $row['type3name']?></td>
      <td><?php echo $row['type2']?></td>
	  <td><input type="text" class="form-control" name="prodqty<?php echo $i;?>" value="<?php echo $row['prodqty']?>" maxlength="3"></td>
    </tr>
	<?php }?>
	<tr>
	  <td colspan='6'></td>
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