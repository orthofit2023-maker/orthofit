<?php 
session_start();
if(!isset($_SESSION['loginid'])){
	header("location:login.php");
}
include("connection.php");

$arrperms = array();
$arrperms[1]="Add";
$arrperms[2]="Edit";
//$arrperms[3]="Approve";
$arrperms[4]="View";
$arrperms[5]="Download";
$arrperms[6]="Delete";

if($_GET['msg']!=""){
	$msg=$_GET['msg'];
}
$sort=$_GET['sort'];
$logtype=$_GET['logtype'];
$id=$_GET['id'];
if($_GET['dl']=="xls"){
	header("Content-Type: application/vnd.ms-excel");
	header("Content-disposition:attachment;filename=adminaccesslog.xls"); 
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
}else{
	include("top.php");
?>
<div class="header">
	<h1 class="page-title">Admin Access Log</h1>
	<ul class="breadcrumb">
	<li><a href="index.php">Home</a> </li>
	<li class="active">Admin Access Log</li>
	</ul>

</div>
<div class="main-content">
<?php echo ($msg!="") ? '<div class="err">'.$msg."</div>" : "";?>
<div class="btn-toolbar list-toolbar">
	<form method="get" name="frmin" action="adminaccess.php">
	<input class="form-control input-srch datepicker" type="text" name="srch" maxlength="10" value="<?php echo $_GET['srch']?>" placeholder='dd/mm/yyyy'>
	<input class="form-control input-srch datepicker" type="text" name="srch1" maxlength="10" value="<?php echo $_GET['srch1']?>" placeholder='dd/mm/yyyy'>
	<select name="id" class="form-control-left">
	  <option value="">Select Admin User</option>
	  <?php 
		$sql="select loginid, user_name from au_admin order by user_name"; 
		$query = mysql_query($sql) or die(mysql_error());
		while($row=mysql_fetch_array($query)){
			?>
		  <option value="<?php echo $row['loginid']?>" <?php if($id==$row['loginid']){echo " selected"; $adminname=$row['user_name'];
			}?>> 
		  <?php echo $row['user_name'];?>
		  </option>
	  <?php }?>
	</select>&nbsp;
	<select name="logtype" class="form-control-left">
	  <option value="">Select Type</option>
		<?php for($n=1;$n<7;$n++){
			if($arrperms[$n]!=''){
			?>
		  <option value="<?php echo $n?>" <?php if($logtype==$n){echo " selected"; $admintype=$arrperms[$n];
			}?>> 
		  <?php echo $arrperms[$n];?>
		  </option>
	  <?php } }?>
	</select>&nbsp;
	<button type='submit' class="btn btn-primary"><i class="fa fa-search"></i> </button>
</div>
<?php 
}		
if($_GET['id']!="" && $_GET['srch']!=""){
	mysql_query("insert into au_adminlog (loginid, logtable, logtype, logip, logdescr) values ('".$_SESSION['loginid']."', '34', '4', '".$_SERVER['REMOTE_ADDR']."', '".$_GET['id']."')");
	?>
<table class="table">
  <thead>
	<tr> 
	  <TH colspan="6">
	    <div class='pull-left'><?php echo "Access Log for ".$adminname;?> <?php ($admintype!='') ? ' for '.$admintype : '';?></div>
		<?php if(checkuseraccess($_SESSION['loginid'], 34, 5)){?>
		<div class='pull-right'><a href='adminaccess.php?srch=<?php echo $_GET['srch'].'&srch1='.$_GET['srch1'].'&id='.$_GET['id'].'&logtype='.$_GET['logtype']?>&dl=xls' class="btn btn-danger"><i class="fa fa-file-excel-o"></i></a></div>
		<?php }?>
	  </TH>
	</tr>
	<tr> 
	  <TH>Sr</TH>
	  <th>Log Date</th>
	  <TH>User IP</th>
	  <th>Process</th>
	  <TH>Type</th>
	  <th>Details</th>
	</tr>
	</thead>
	 <tbody>
	<?php 
	$i=0;
	$sql="SELECT l.*, a.user_name, f.field, f.fieldtable, f.fieldname, f.filetitle, f.menutitle FROM `au_adminlog` l join au_admin a on l.loginid=a.loginid join au_accessfiles f on f.fileid=l.logtable where f.fieldname!='' and f.field!='' ";
	if($_GET['srch']!="" && $_GET['srch1']!="" && $_GET['srch']!=$_GET['srch1']){//
		$srch=sqldate($_GET['srch']); $srch1=sqldate($_GET['srch1']);
		$sql=$sql." and l.logdate between '".trim($srch)."' and '".trim($srch1)." 23:59:59'";
	}else if($_GET['srch']==$_GET['srch1']){//
		$srch=sqldate($_GET['srch']); $srch1=sqldate($_GET['srch1']);
		$sql=$sql." and l.logdate between '".trim($srch)."' and '".trim($srch)." 23:59:59'";
	}else if($_GET['srch']!=""){
		$srch=sqldate($_GET['srch']);
		$sql=$sql." and l.logdate>='".trim($srch)."'";
	}else if($_GET['srch1']!=""){
		$srch1=sqldate($_GET['srch1']);
		$sql=$sql." and l.logdate<='".trim($srch1)."  23:59:59'";
	}
	if($_GET['logtype']!=""){
		$sql=$sql." and l.logtype='".trim($logtype)."'";
	}
	if($_GET['id']!=""){
		$sql=$sql." and l.loginid='".trim($_GET['id'])."'";
	}
	$sql=$sql." order by l.logdate";

	//echo $sql;
	$query = mysql_query($sql) or die(mysql_error());
	$num_rows = mysql_num_rows($query);

	if ($num_rows>0){
		if($_GET['dl']!="xls"){
			$p=$_GET['p'];
			include("grojsus.php");
			$g = grojsus($num_rows,$p,15,"","p",true,"SE",10,0);
			$sql = $sql. " LIMIT $g[3],$g[5]";
			$query = mysql_query($sql) or die(mysql_error());
			$i=($p*15);
		}
		while($row=mysql_fetch_array($query)){
			$i++;
		?>
		<tr> 
		  <td valign='top'><?php echo $i;?></td>
		  <td><?php echo inddate($row['logdate'])?></td>
		  <td><?php echo $row['logip']?></td>
		  <td><?php echo $row['menutitle'].': '.$row['filetitle'];?></td>
		  <td><?php echo $arrperms[$row['logtype']];?> </td>
		  <td><?php 
			if($row['logdescr']!="New" && $row['logdescr']!="" && !strstr($row['field'],",")){	
				//echo $row['field'].','.$row['logdescr'].','.$row['fieldname'];
				echo getfieldvalue($row['logdescr'], $row['fieldname'], $row['field'], $row['fieldtable']); 
			}else if(strstr($row['field'],",")){
				//echo $row['field'].','.$row['logdescr'].','.$row['fieldname'].','.$row['fieldtable'].'<BR>';
				$arrfieldids = explode(',',$row['field']);
				$arrvalues = explode(',',$row['logdescr']);
				$arrfields = explode(',',$row['fieldname']);
				$arrtables = explode(',',$row['fieldtable']);
				for($x=0;$x<count($arrfieldids);$x++){
					echo getfieldvalue($arrvalues[$x], $arrfields[$x], $arrfieldids[$x], $arrtables[$x]).', '; 
				}
			}
		?></td>
		</tr>
		<?php } 
		if($num_rows>$g[5] && $_GET['dl']!="xls"){?>
		<tr>
		  <td colspan='5'>
		  
		  <?php echo $g[1];?></td>
		</tr>
		<?php } }?>
  </tbody>
</table>
</form>
<?php 
if($_GET['dl']=="xls"){
	$DB_site->query("insert into au_adminlog (loginid, logtable, logtype, logip, logdescr) values ('".$_SESSION['loginid']."', '34', '5', '".$_SERVER['REMOTE_ADDR']."', '".$id."')");	
}else{
	$DB_site->query("insert into au_adminlog (loginid, logtable, logtype, logip, logdescr) values ('".$_SESSION['loginid']."', '34', '4', '".$_SERVER['REMOTE_ADDR']."', '".$id."')");		
}
} 
if($_GET['dl']!="xls"){
	include("bot.php");
} ?>