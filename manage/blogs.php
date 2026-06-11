<?php 
session_start();
if(!isset($_SESSION['loginid'])){
	header("location:login.php");
}
include("db5conn.php");
if($_POST['delid']!=""){
	$mysqli->query("delete from ccd9pages where pageid='".$_POST['delid']."'");
	$msg= "Banner updated successfully!";
	header("Location:blogs.php?msg=$msg");
}
include("top.php");
$opt=$_GET['opt'];
?>
<div class="header">
	<h1 class="page-title">PS Blog</h1>
	<ul class="breadcrumb">
	<li><a href="index.php">Home</a> </li>
	<li class="active">PS Blog</li>
	</ul>

</div>
<div class="main-content">
<?php echo $_GET['msg'];?>
<div class="btn-toolbar list-toolbar">
	<form method="get" name="frm" action="blogs.php">
		<input type="hidden" name="delid">
		<input type="text" name="srch" value="<?php echo $_GET['srch']?>" maxlength="40" class="form-control input-srch">
		<button type='submit' class="btn btn-primary"><i class="fa fa-search"></i> </button>
	<a class="btn btn-primary" href="blog.php"><i class="fa fa-plus"></i> Add New</a>
    </form>
  <div class="btn-group">
  </div>
</div>

<table class="table table-striped">
  <thead>
    <tr>
      <th>Date</th>
      <th>Photo</th>
      <th>Title</th>
	  <th>Active</th>
    </tr>
  </thead>
  <tbody>
	<?php 
	$i=0; 
	$sql="select * from ccd9pages t where iscart>0 ";
	if($_GET['srch']!="" && $_GET['srch']!="name"){
		$srch=$_GET['srch'];
		$sql=$sql." and ( title like '%".trim($srch)."%' or description like '%".trim($srch)."%' or meta_description like '%".trim($srch)."%' ) ";
	}
	$sql=$sql." order by pageid desc";
	$result = $mysqli->query($sql);
	while($rescon = $result->fetch_array()){$i++;?>
    <tr>
      <td><?php echo inddate($rescon['pagedate'])?></td>
      <td><img src="https://www.payalsinghal.com/images/<?php echo $rescon['banner']?>" height="100" /></td>
      <td><?php echo $rescon['title']?></td>
      <td><?php echo ($rescon['status']=='1' ? 'Yes' : 'No')?></td>
	  <td>
		  <a href="blog.php?pageid=<?php echo $rescon['pageid']?>&p=<?php echo $p?>"><i class="fa fa-pencil"></i></a>
          <a href="#" data-href="<?php echo $rescon['pageid']?>" role="button" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
      </td>
    </tr>
	<?php } 
	?>
  </tbody>
</table>
<?php include("bot.php");?>