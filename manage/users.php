<?php 
session_start();
if(!isset($_SESSION['loginid'])){
	header("location:login.php");
}
include("db5conn.php");

if($_POST['delid']!="" && checkuseraccess(0,0,6) && checkuserref()){
	$result = $mysqli->query("delete from ccd9user where loginid='".$_POST['delid']."'");
	if(mysqli_affected_rows($result)>0){
		$msg="User deleted successfully!";
	}else{
		$msg="User cannot be deleted!";
	}
	header("Location:users.php?msg=$msg");
}
include("top.php");
if($_GET['msg']!=""){
	$msg=$_GET['msg'];
}
$hqid=$_GET['hqid'];
$typeid=$_GET['typeid'];
?>
<div class="header">
	<h1 class="page-title">Users Master</h1>
	<ul class="breadcrumb">
	<li><a href="index.php">Home</a> </li>
	<li class="active">Users Master</li>
	</ul>

</div>
<div class="main-content">
<?php echo ($msg!="") ? '<div class="err">'.$msg."</div>" : "";?>
<div class="btn-toolbar list-toolbar">
	<form method="get" name="frm" action="users.php">
	<input type="hidden" name="delid">
		<input type="text" name="srch" value="<?php echo $_GET['srch']?>" maxlength="250" class="form-control input-srch">
		<button type='submit' class="btn btn-primary"><i class="fa fa-search"></i> </button>
		<a class="btn btn-primary" href="user.php"><i class="fa fa-plus"></i> Add New</a>
    </form>
  <div class="btn-group">
  </div>
</div>
<table class="table table-striped">
  <thead>
    <tr>
      <TH>Sr</TH>
      <th>Name</th>
	  <th>Login</th>
      <th style="width: 3.5em;"></th>
    </tr>
  </thead>
  <tbody>
	<?php 
	$i=0; 
	$sql="SELECT c.* from ccd9user c where c.loginid>0 ";
	if($_GET['srch']!="" && $_GET['srch']!="name"){
		$srch=$_GET['srch'];
		$sql=$sql." and ( c.name like '%".trim($srch)."%') ";
	}
	$sql=$sql." order by c.name";
	$result = $mysqli->query($sql);
	$num_rows = num_rows($result);
	if ($num_rows>0){
		if($_GET['dl']!="xls"){
			$p=$_GET['p'];
			include("grojsus.php");
			$g = grojsus($num_rows,$p,15,"","p",true,"SE",10,0);
			$sql = $sql. " LIMIT $g[3],$g[5]";
		}
		$i=($p*15);
		$result = $mysqli->query($sql);
		while($row=$result->fetch_array()){ $i++; ?>
    <tr>
      <td><?php echo $i;?></td>
      <td><?php echo $row['name']?></td>
	  <td nowrap><?php echo ($row['email']!='') ? '<a href="mailto:'.$row['email'].'" alt="'.$row['email'].'" title="'.$row['email'].'"><i class="fa fa-envelope-o"></i> '.$row['email'].'</a>' : '';?>
	  </td>
      <td>
          <a href="user.php?userid=<?php echo $row['loginid']?>&p=<?php echo $p?>"><i class="fa fa-pencil"></i></a>
          <a href="#" data-href="<?php echo $row['loginid']?>" role="button" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
      </td>
    </tr>
	<?php } 
	if($num_rows>$g[5]){?>
	<tr>
      <td colspan='10'><?php echo $g[1];?></td>
	</tr>
	<?php } }?>
  </tbody>
</table>
<?php include("bot.php");?>