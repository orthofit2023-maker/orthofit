<?php 
session_start();
if(!isset($_SESSION['loginid'])){
	header("location:login.php");
}
include("db5conn.php");

if($_POST['cccatid']!="" && intval($_POST['catopt'])>0 && checkuseraccess(71,0,4)){
	$cccatid=intval($_POST['cccatid']);
	$catopt=intval($_POST['catopt']);
	$arrstd=$_POST['challans'];
	if($catopt==1){
		$mysqli->query("delete from ccd9prod2cat where prodid in (".implode(',', $arrstd).") and catid='$cccatid'");
	}else if($catopt==3){
		$mysqli->query("insert into ccd9prod2cat (prodid, catid) select prodid, '$cccatid' from ccd9products where prodid in (".implode(',', $arrstd).")");
	}
	//exit();
	$msg="Products updated successfully!";
	header("Location:products.php?msg=$msg&catid=$cccatid");

}else if($_POST['delid']!="" && checkuseraccess(0,0,6)){
	//$mysqli->query("delete from ccd9products where prodid='".$_POST['delid']."' and prodid not in(select prodid from ccd9billdata where prodid='".$_POST['delid']."') and prodid not in(select prodid from ccd9invdata where prodid='".$_POST['delid']."')");
	$msg="Product deleted successfully!";
	header("Location:products.php?msg=$msg");
}
include("top.php");
if($_GET['msg']!=""){
	$msg=$_GET['msg'];
}
$type1=inpval($_GET['type1']);
$catid=inpval($_GET['catid']);
$type2=inpval($_GET['type2']);
$type3=inpval($_GET['type3']);
$prodopt=inpval($_GET['prodopt']);
?>
<div class="header">
	<h1 class="page-title">Product Master</h1>
	<ul class="breadcrumb">
	<li><a href="index.php">Home</a> </li>
	<li class="active">Product Master</li>
	</ul>

</div>
<div class="main-content">
<?php echo ($msg!="") ? '<div class="err">'.$msg."</div>" : "";?>
<div class="btn-toolbar list-toolbar">
	<form method="get" name="frm" action="products.php">
	<input type="hidden" name="delid">
		<input type="text" name="srch" value="<?php echo $_GET['srch']?>" maxlength="40" class="form-control input-srch">
		<select name="catid" class="form-control input-srch" onchange="document.frm.submit()">
		<?php echo getcatmenu(0,$catid);?>
		</select>
		<select name="type1" class="form-control input-srch" onchange="document.frm.submit()">
		<?php echo gettype1menu(0,$type1);?>
		</select>
		<!--<select name="type2" class="form-control input-srch" onchange="document.frm.submit()">
		<?php //echo gettype2menu(0,$type2);?>
		</select>-->
		<select name="type3" class="form-control input-srch" onchange="document.frm.submit()">
		<?php echo gettype3menu(0,$type3);?>
		</select>
		
		<button type='submit' class="btn btn-primary"><i class="fa fa-search"></i> </button>
		<a class="btn btn-primary" href="product.php"><i class="fa fa-plus"></i> Add New</a>
    </form>
</div>
<form method="post" name="frmin" action="products.php">
<table class="table table-striped">
  <thead>
    <tr>
      <Th></Th>
      <th>Code</th>
      <th>Name</th>
      <th>Color</th>
      <th>Sizes</th>
	  <th>Price</th>
	  <th>Offer (Rs)</th>
      <!-- <th style="width: 3.5em;"></th> -->
    </tr>
  </thead>
  <tbody>
	<?php 
	$i=0; 
	$sql="SELECT c.*, m.typevalue as measurement from ccd9products c left join ccd9prod2cat t on t.prodid=c.prodid left join ccd9prod2type1 v1 on v1.prodid=c.prodid left join ccd9prod2type2 v2 on v2.prodid=c.prodid left join ccd9prod2type3 v3 on v3.prodid=c.prodid left join ccd9types m on m.typeid=c.prodmeas where c.prodid>0 ";
	if($_GET['srch']!="" && $_GET['srch']!="name"){
		$srch=$_GET['srch'];
		$sql=$sql." and ( c.prodname like '%".trim($srch)."%' or c.prodcode like '%".trim($srch)."%' or c.proddesc like '%".trim($srch)."%' ) ";
	}
	if($_GET['type1']>0){
		$sql=$sql." and ( v1.typeid = '".inpval($type1)."') ";
	}
	if($_GET['type2']>0){
		$sql=$sql." and ( v2.typeid = '".inpval($type2)."') ";
	}
	if($_GET['type3']>0){
		$sql=$sql." and ( v3.typeid = '".inpval($type3)."') ";
	}
	if($_GET['catid']>0){
		$sql=$sql." and ( t.catid = '".inpval($catid)."') ";
	}
	if($_GET['prodopt']>0){
		if($prodopt==1){
			$sql=$sql." and ( c.proddisc>0 and CURDATE() between c.discfrdate and c.disctodate) ";
		}else if($prodopt==2){
			$sql=$sql." and ( c.offerprod>0 and CURDATE() between c.offerfrdate and c.offertodate) ";
		}else if($prodopt==3){
			$sql=$sql." and ( c.prodstatus='0' or c.prodfrdate>CURDATE())";
		}
	}
	
	$sql=$sql." group by c.prodid order by c.prodid desc"; //, c.prodcode, c.prodname
	$result = $mysqli->query($sql);
	$num_rows = mysqli_num_rows($result);
	if ($num_rows>0){
		if($_GET['dl']!="xls"){
			$p=$_GET['p'];
			include("grojsus.php");
			$g = grojsus($num_rows,$p,25,"","p",true,"SE",10,0);
			$sql = $sql. " LIMIT $g[3],$g[5]";
		}
		$i=($p*15);
		$result = $mysqli->query($sql);
		while($row=$result->fetch_array()){ $i++;
		?>
    <tr>
      <td><?php echo $i;?><BR><a href="product.php?prodid=<?php echo $row['prodid']?>&p=<?php echo $p?>&billtype=<?php echo $billtype?>"><i class="fa fa-pencil"></i></a>
	  </td>
	  <td nowrap><?php echo $row['prodcode']?></td>
      <td><?php echo $row['prodname']?></td>
      <td><?php echo $row['prodcolor']?></td>
      <td><?php echo $row['prodsize']?></td>
	  <td align="right"><?php echo number_format($row['prodprice'])?></td>
	  <td align="right"><?php echo number_format($row['offerprod'])?></td>
    </tr>
	<?php }?>
	<tr>
      <td colspan='7'><?php echo ($num_rows>$g[5] ? $g[1] : '');?></td>
	</tr>
	<?php }?>
  </tbody>
</table>
</form>
<script>
function checkall(x){
	var y =document.frmin.elements['challans[]'];
	if(y.length){
		for(n=0;n<y.length;n++){
			if(x.checked==true ){
				y[n].checked=true;

			}else{
				y[n].checked=false;
			}
		}
	}else{
		if(x.checked==true ){
			y.checked=true;
		}else{
			y.checked=false;
		}
	}
}
</script>
<?php include("bot.php");?>