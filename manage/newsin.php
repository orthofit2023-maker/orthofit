<?php 
include("db5conn.php");
if($_POST['delid']!=""){
	$mysqli->query("delete from ccd9pages where pageid='".$_POST['delid']."'");
	$msg= "Record updated successfully!";
	header("Location:$retuurl?msg=$msg");
}
include("top.php");
$opt=$_GET['opt'];
?>
<div class="header">
	<h1 class="page-title"><?php echo $pgtitle?></h1>
	<ul class="breadcrumb">
	<li><a href="index.php">Home</a> </li>
	<li class="active"><?php echo $pgtitle?></li>
	</ul>

</div>
<div class="main-content">
<?php $msg = inpval($_GET['msg']);
echo ($msg!="") ? '<div class="err">'.$msg."</div>" : "";?>
<div class="btn-toolbar list-toolbar">
	<form method="get" name="frm">
		<input type="hidden" name="delid">
		<input type="text" name="srch" value="<?php echo $_GET['srch']?>" maxlength="40" class="form-control input-srch">
		<button type='submit' class="btn btn-primary"><i class="fa fa-search"></i> </button>
		<?php if($iscart!=2){?>
		<a class="btn btn-primary" href="<?php echo $pgurl?>"><i class="fa fa-plus"></i> Add New</a>
		<?php }?>
    </form>
  <div class="btn-group">
  </div>
</div>

<table class="table table-striped">
  <thead>
    <tr><th>
	<?php echo ($iscart==1 ? ' Date' : 'Sr');?>
     </th>
      <th>Title</th>
	  <th>Active</th>
    </tr>
  </thead>
  <tbody>
	<?php 
	$i=0; 
	if($iscart==2){
		$sql="select p.prodname as title,  p.prodid as pageid,  p.prodstatus as status from ccd9products p where 1=1 ";
		if($_GET['srch']!="" && $_GET['srch']!="name"){
			$srch=$_GET['srch'];
			$sql=$sql." and ( prodname like '%".trim($srch)."%' or prodkeys like '%".trim($srch)."%' or proddesc like '%".trim($srch)."%' ) ";
		}
		$sql=$sql." order by prodid desc";
	}else{
		$sql="select * from ccd9pages t where iscart='$iscart'";
		if($_GET['srch']!="" && $_GET['srch']!="name"){
			$srch=$_GET['srch'];
			$sql=$sql." and ( title like '%".trim($srch)."%' or description like '%".trim($srch)."%' or meta_description like '%".trim($srch)."%' ) ";
		}
		$sql=$sql." order by pageid desc";
	}
	$result = $mysqli->query($sql);
	while($rescon = $result->fetch_array()){$i++;?>
    <tr>
      <td><?php echo ($iscart==1 ? inddate($rescon['pagedate']) : $i)?></td>
      <td><?php echo $rescon['title']?></td>
      <td><?php echo ($rescon['status']=='1' ? 'Yes' : 'No')?></td>
	  <td>
		  <a href="<?php echo $pgurl?>?pageid=<?php echo $rescon['pageid']?>&p=<?php echo $p?>"><i class="fa fa-pencil"></i></a>
          <!-- <a href="#" data-href="<?php echo $rescon['pageid']?>" role="button" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a> -->
      </td>
    </tr>
	<?php } 
	?>
  </tbody>
</table>
<?php include("bot.php");?>