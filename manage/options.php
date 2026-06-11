<?php 
if($_GET['dl']=="xls"){
	header("Content-Type: application/vnd.ms-excel");
	header("Content-disposition:attachment;filename=download.xls"); 
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
}else{
	include("top.php");
}
if($_GET['msg']!="") $msg=$_GET['msg'];
if($_GET['dl']!="xls"){
?>
<div class="header">
	<h1 class="page-title"><?php echo $filetitle?></h1>
	<ul class="breadcrumb">
	<li><a href="index.php">Home</a> </li>
	<li class="active"><?php echo $filetitle?></li>
	</ul>

</div>
<div class="main-content">
<?php echo ($msg!="") ? '<div class="err">'.$msg."</div>" : "";?>
<div class="btn-toolbar list-toolbar">
	<form method="get" name="frm">
	<input type="hidden" name="delid">
		<input type="text" name="srch" value="<?php echo $_GET['srch']?>" maxlength="40" class="form-control input-srch">
		<button type='submit' class="btn btn-primary"><i class="fa fa-search"></i> </button>
		<a class="btn btn-primary" href="<?php echo $newfile?>"><i class="fa fa-plus"></i> Add New</a>
		<?php //if(checkuseraccess(0,0,5)){?>
		<a href="?dl=xls" class="btn btn-danger"><i class="fa fa-file-excel-o"></i></a>
		<?php //}?>
    </form>
</div>
<?php }?>
<table class="table table-striped">
  <thead>
    <tr>
      <TH>Sr</TH>
      <th><?php echo $filetitle?></th>
	  <?php if($filename=='cats.php'){?>
	  <TH>URL</TH>
	  <TH>Active</TH>
	  <TH>Sort No</TH>
	  <?php }?>
      <th style="width: 3.5em;"></th>
    </tr>
  </thead>
  <tbody>
	<?php 
	$i=0; 
	$sql="SELECT c.* from ccd9types c where c.opt='$opt' ";
	if($_GET['srch']!=""){
		$srch=$_GET['srch'];
		$sql=$sql." and ( c.typename like '%".trim($srch)."%') ";
	}
	$sql=$sql." order by CAST(c.typevalue1 AS UNSIGNED), c.typename";
	//echo $sql;
	$query = $mysqli->query($sql);
	$num_rows = mysqli_num_rows($query);
	if ($num_rows>0){
		if($_GET['dl']!="xls"){
			$p=$_GET['p'];
			include("grojsus.php");
			$g = grojsus($num_rows,$p,20,"","p",true,"SE",10,0);
			$sql = $sql. " LIMIT $g[3],$g[5]";
		}
		$i=($p*20);
		$result = $mysqli->query($sql) ;
		while($row=$result->fetch_array()){ $i++;
		?>
    <tr>
      <td><?php echo $i;?></td>
      <td><?php echo dbval($row['typename']);?></td>
	  <?php if($filename=='cats.php'){?>
      <td><?php echo $row['typevalue']?></td>
	  <td><?php echo ($row['typevalue2']=='1' ? 'Yes' : 'No');?></td>
      <td><?php echo $row['typevalue1']?></td>
	  <?php }?>
      <td>
          <a href="<?php echo $newfile?>?typeid=<?php echo $row['typeid']?>&p=<?php echo $p?>"><i class="fa fa-pencil"></i></a>
          <!-- <a href="#" data-href="<?php echo $row['typeid']?>" role="button" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a> -->
      </td>
    </tr>
	<?php }
	if($_GET['dl']!="xls"){
	?>
	<tr>
      <td colspan='10'><?php if($num_rows>$g[5]){ echo $g[1];}?></td>
	</tr>
	<?php } }?>
  </tbody>
</table>
<?php 
if($_GET['dl']!="xls"){
	include("bot.php");
}
?>