<?php 

if(trim($_POST['typename'])!=""){

	$typeid =  $_POST['typeid'];
	$p =  $_POST['p'];
	$typename=inpval($_POST['typename']);
	$parentid=inpval($_POST['parentid']);
	$typevalue1=inpval($_POST['typevalue1']);
	$typevalue2=inpval($_POST['typevalue2']);
	$typevalue=inpval($_POST['typevalue']);

	if($typeid>0 ){
		$sql="update ccd9types set typename='$typename', typevalue='$typevalue', typevalue1='$typevalue1', typevalue2='$typevalue2' where typeid='$typeid'";
		$query = $mysqli->query($sql) ;
		$msg="$filetitle updated successfully!";

	}else if($typename!="" ){
		$row=query_first("select typeid from ccd9types where typename='$typename' and opt='$opt'");
		if($row['typeid']>0){
			$msg= "$filetitle already added with this name";
			$err=0;
		}else{
			$sql="insert into ccd9types (typename, opt, typevalue, typevalue1, typevalue2) values ('$typename', '$opt', '$typevalue', '$typevalue1', '$typevalue2')";
			$query = $mysqli->query($sql) ;
			$typeid=mysqli_insert_id();
			if($typeid>0){
				$msg="$filetitle added successfully!";
			}
		}
	}
	//echo $sql;
	//exit();
	header("location:$retufile?typeid=$typeid&msg=$msg");

}else if($_GET['typeid']!=""){
	$typeid=$_GET['typeid'];
	$p=$_GET['p'];

	$res=query_first("select * from ccd9types where typeid='$typeid'");
	$typename=dbval($res['typename']);
	//$sortby=dbval($res['sortby']);
	//$parentid=dbval($res['parentid']);
	$typevalue1=dbval($res['typevalue1']);
	$typevalue2=dbval($res['typevalue2']);
	$typevalue=dbval($res['typevalue']);
} 
$p=$_GET['p'];

include("top.php");?>
<div class="header">
	<h1 class="page-title"><?php echo $filetitle?></h1>
	<ul class="breadcrumb">
	<li><a href="index.php">Home</a> </li>
	<li class="active"><?php echo $filetitle?></li>
	</ul>

</div>
<div class="main-content">
<?php echo ($msg!="") ? '<div class="err">'.$msg."</div>" : "";?>

<div class="row">
  <div class="col-md-4">
    <form name="frm" method="post" autocomplete="off" onsubmit="return validate()">
    <div id="myTabContent" class="tab-content">
	  <input type="hidden" name="typeid" value="<?php echo $typeid?>">
	  <input type="hidden" name="p" value="<?php echo $p?>">
	  <input type="hidden" name="delid">


		<div class="form-group">
        <label><?php echo $filetitle?></label>
        <input type="text" name="typename" value="<?php echo $typename?>" maxlength="120" class="form-control">
        </div>
		<?php if($filename=='cat.php'){?>
		<div class="form-group">
        <label>URL</label>
		<input type="text" name="typevalue" value="<?php echo $typevalue?>" maxlength="150" class="form-control">
        </div>
		<div class="form-group col-md-6 left">
        <label>Active</label>
		<select name="typevalue2" class="form-control">
			<option value="1" <?php echo ($typevalue2==1) ? " selected" : "";?>>Yes</option>
			<option value="0" <?php echo ($typevalue2==0) ? " selected" : "";?>>No</option>
            </select>
        </div>
		<div class="form-group col-md-6">
        <label>Sort Order</label>
        <input type="text" name="typevalue1" value="<?php echo $typevalue1?>" maxlength="3" class="form-control">
        </div>
		<?php }?>
    </div>

    <div class="btn-toolbar list-toolbar">
      <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
    </div>
    </form>
  </div>
</div>

<script>
function validate(){
	if(document.frm.typename.value==""){
		alert("please enter <?php echo $filetitle?>");
		document.frm.typename.focus();
		return false;
	}
}
</script>
<?php include("bot.php");?>