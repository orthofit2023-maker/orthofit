<?php 
session_start();
if(!isset($_SESSION['loginid'])){
	header("location:login.php");
}
include("connection.php");

if($_POST['delid']!="" && checkuseraccess($_SESSION['loginid'], 7, 6)){
	$DB_site->query_delete("delete from ccd9pages where pgid='".$_POST['delid']."'");
	header("Location:postpages.php?msg=Page deleted successfully!");
}
include("top.php");
if($_GET['msg']!=""){
	$msg=$_GET['msg'];
}
?>
<div class="header">
	<h1 class="page-title">Page Content</h1>
	<ul class="breadcrumb">
	<li><a href="index.php">Home</a> </li>
	<li class="active">Page Content</li>
	</ul>

</div>
<div class="main-content">
<?php echo ($msg!="") ? '<div class="err">'.$msg."</div>" : "";?>
<div class="btn-toolbar list-toolbar">
	<form method="get" name="frm" action="postpages.php">
	<input type="hidden" name="delid">
		<input type="text" name="srch" value="<?php echo $_GET['srch']?>" maxlength="250" class="form-control input-srch">
		<button type='submit' class="btn btn-primary"><i class="fa fa-search"></i> </button>
		<a class="btn btn-primary" href="postpage.php"><i class="fa fa-plus"></i> New</a>
    </form>
  <div class="btn-group">
  </div>
</div>
<table class="table">
  <thead>
    <tr>
      <TH>Sr</TH>
	  <TH>Page Name</TH>
	  <TH>Menu Link</TH>
      <th style="width: 60px;"></th>
    </tr>
  </thead>
  <tbody>
	<?php 
	$i=0; 
	$sql="select u.*, c.catname, p.catname as parentcat, s.catname as maincat from ccd9pages u left join ccd9category c on u.catid=c.catid left join ccd9category p on c.parentid=p.catid left join ccd9category s on p.parentid=s.catid where u.pgid>'0' ";
	if($_GET['srch']!=""){
		$srch=$_GET['srch'];
		$sql=$sql." and (u.pagename like '%".trim($srch)."%' or u.pgcontent like '%".trim($srch)."%') ";
	}

	$sql=$sql."  group by u.pgid order by s.catname, p.catname, c.catname";
	$query = mysql_query($sql) or die(mysql_error());
	$num_rows = mysql_num_rows($query);
	if ($num_rows>0){
		$p=$_GET['p'];
		include("grojsus.php");
		$g = grojsus($num_rows,$p,15,"","p",true,"SE",10,0);
		$sql = $sql. " LIMIT $g[3],$g[5]";
		$query = mysql_query($sql) or die(mysql_error());
		$i=($p*15);
		$query = mysql_query($sql) or die(mysql_error());
		while($row=mysql_fetch_array($query)){
		$i++;
		?>
    <tr>
      <td><?php echo $i;?></td>
	  <td><?php echo ($row['maincat']!='' ? $row['maincat'].' -> ': '').($row['parentcat']!='' ? $row['parentcat'].' -> ': '').$row['catname']?></td>
      <td><?php echo $row['pagename']?></td>
      <td>
		  <A HREF="postpage.php?pgid=<?php echo $row['pgid']?>&p=<?php echo $p?>"><i class="fa fa-pencil"></i></a>
          <a href="#" data-href="<?php echo $row['pgid']?>" role="button" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
      </td>
    </tr>
	<?php } 
	if($num_rows>$g[5]){?>
	<tr>
      <td colspan='<?php echo (12+count($arrfields));?>'><?php echo $g[1];?></td>
	</tr>
	<?php } }?>
  </tbody>
</table>
<?php include("bot.php");?>