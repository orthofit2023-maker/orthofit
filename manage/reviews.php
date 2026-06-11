<?php 
session_start();
if(!isset($_SESSION['loginid'])){
	header("location:login.php");
}
include("db5conn.php");

if($_POST['revlist']!=''){
	$qdids=implode(",",$_POST['challans']);
	$revlist=inpval($_POST['revlist']);
	if($qdids=='')$qdids='0';

	$sql="update ccd9reviews set status='1' where revid in (".$qdids.")";
	$mysqli->query($sql);
	//echo $sql;

	$sql="update ccd9reviews set status='0' where revid not in (".$qdids.") and revid in (".$revlist.")";
	$mysqli->query($sql);
	//echo $sql;
	$sql="update ccd9products p join (
		select count(*) as cnt, sum(rating) as avgrate, prodid from ccd9reviews where status=1 group by prodid) r on p.prodid=r.prodid 
		set p.rating=(r.avgrate/r.cnt);";
	$mysqli->query($sql);

	$msg="Reviews approved successfully!";
	header("Location:reviews.php?msg=$msg");
}

include("top.php");?>
<div class="header">
	<h1 class="page-title">Reviews</h1>
	<ul class="breadcrumb">
	<li><a href="index.php">Home</a> </li>
	<li class="active">Reviews</li>
	</ul>

</div>
<div class="main-content">
<?php echo $_GET['msg'];?>
<div class="btn-toolbar list-toolbar">
	<form method="get" name="frm" action="reviews.php">
	<input type="hidden" name="delid">
		<input type="text" name="srch" value="<?php echo $_GET['srch']?>" maxlength="250" class="form-control input-srch">
		<button type='submit' class="btn btn-primary"><i class="fa fa-search"></i> </button>
		<a class="btn btn-primary" href="review.php"><i class="fa fa-plus"></i> Add New</a>
    </form>
</div>
<form method="post" name="frmin" action="reviews.php">
<table class="table table-striped">
  <thead>
    <tr>
      <th>#</th>
      <th>Product</th>
      <th>By</th>
      <th>Phone</th>
      <th>Review</th>
      <th>Status <input type="checkbox" class="checkbox-inline" name="chkall" value="1" onclick="checkall(this)"></th>
      <th>Date</th>
    </tr>
  </thead>
  <tbody>
	<?php 
	$i=0; 
	$sql="SELECT r.*, p.prodname, p.prodcode, p.produrl, DATE_FORMAT(r.revdate,'%b %d, %Y') as regdate from ccd9reviews r left join ccd9products p on p.prodid=r.prodid where r.revid>0 ";
	if($_GET['srch']!="" && $_GET['srch']!="name"){
		$srch=$_GET['srch'];
		$sql=$sql." and ( r.username like '%".trim($srch)."%' or r.email like '%".trim($srch)."%' or p.prodname like '%".trim($srch)."%' ) ";
	}
	if($_GET['status']!=""){
		$status=$_GET['status'];
		$sql=$sql." and r.status='".trim($status)."'";
	}
	
	$sql=$sql." order by r.revid desc";
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
      <td><?php echo $i; if($row['isman']==1){?><br><a href="review.php?revid=<?php echo $row['revid']?>&p=<?php echo $p?>"><i class="fa fa-pencil"></i></a><?php }?></td>
      <td><?php echo $row['prodname']?></td>
      <td><?php echo $row['username']?></td>
      <td><?php echo $row['email']?></td>
      <td><?php echo 'Rating: '.intval($row['rating']).'<br><strong>'.dbval($row['revtitle']).'</strong><br>'.dbval($row['review'])?></td>
      <td>
	  <input type="checkbox" class="checkbox-inline" name="challans[]" value="<?php echo $row['revid']?>" <?php echo ($row['status']==1 ? 'checked' : '')?>>
	  </td>
      <td><?php echo $row['regdate']?></td>
    </tr>
	<?php } 
	?>
	<tr>
      <td colspan='6'><?php if($num_rows>$g[5]){ echo $g[1];}?></td>
	  <td colspan='2'><input type="hidden" name="revlist" value="<?php echo $revlist?>"><input type="submit" class="btn btn-primary" value="Approve"></td>
	</tr>
	<?php  }?>
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