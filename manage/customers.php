<?php 
session_start();
if(!isset($_SESSION['loginid'])){
	header("location:login.php");
}
include("db5conn.php");

if($_GET['msg']!=""){
	$msg=$_GET['msg'];
}
$countryid=inpval($_GET['countryid']);
$fdate=$_GET['fdate'];
$tdate=$_GET['tdate'];
$srch=inpval($_GET['srch']);
if($_GET['dl']!="xls"){
	include("top.php");
?>
<div class="header">
	<h1 class="page-title">Customers Master</h1>
	<ul class="breadcrumb">
	<li><a href="index.php">Home</a> </li>
	<li class="active">Customers Master</li>
	</ul>

</div>
<div class="main-content">
<?php echo ($msg!="") ? '<div class="err">'.$msg."</div>" : "";?>
<div class="btn-toolbar list-toolbar">
	<form method="get" name="frm" action="customers.php">
	<input type="hidden" name="delid">
		<input type="text" name="srch" value="<?php echo $_GET['srch']?>" maxlength="250" class="form-control input-srch">
		<select name="countryid" class="form-control input-srch">
			<option value=''>Select Country</option>
			<?php 
			$sql = "select c.* from ccd9country c join ccd9address a on c.countryid=a.countryid group by a.countryid order by c.countryname";
			$result = $mysqli->query($sql);
			while($row=$result->fetch_array()){?>
			<option value="<?php echo $row['countryid']?>" <?php echo ($row['countryid']==$countryid) ? "selected" : "";?>><?php echo $row['countryname']?></option>
			<?php }?>
        </select>
		<input type="text" name="fdate" value="<?php echo $fdate?>" maxlength="10" class="form-control-left datepicker" placeholder="from dd/mm/yyyy">
		<input type="text" name="tdate" value="<?php echo $tdate?>" maxlength="10" class="form-control-left datepicker" placeholder="to dd/mm/yyyy">
		<button type='submit' class="btn btn-primary"><i class="fa fa-search"></i> </button>
		<a href='customers.php?dl=xls&fdate=<?php echo $fdate?>&tdate=<?php echo $tdate?>&countryid=<?php echo $countryid?>&srch=<?php echo $srch?>' class="btn btn-danger"><i class="fa fa-file-excel-o"></i></a>
    </form>
  <div class="btn-group">
  </div>
</div>
<?php }else{ 

	header("Content-Type: application/vnd.ms-excel");
	header("Content-disposition:attachment;filename=customers.xls"); 
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
}?>
<table class="table table-striped" <?php echo ($_GET['dl']=="xls" ? " border='1'" : "")?>>
  <thead>
    <tr>
      <TH>Sr</TH>
	  <th>Currency</th>
	  <th>Order Value</th>
      <th>Name</th>
	  <th>Phone</th>
      <th>Email</th>
      <th>Country</th>
      <th>Reg Date</th>
      <th>Last Login</th>
	  <?php if($_GET['dl']=="xls"){?>
      <th>Address line 1</th>
      <th>Address line 2</th>
      <th>City</th>
      <th>State</th>
	  <?php }else if($_GET['dl']!="xls"){?>
      <th style="width: 100px;"></th>
	  <?php }?>
    </tr>
  </thead>
  <tbody>
	<?php 
	$i=0; 
	$sql="SELECT c.*, cm.countryname, a.city, a.state, a.address, a.address1 from ccd9company c left join ccd9address a on c.addressid=a.addressid left join ccd9country cm on a.countryid=cm.countryid where c.compid>0 ";
	if($_GET['srch']!=""){
		$srch=$_GET['srch'];
		$sql=$sql." and ( c.username like '%".trim($srch)."%' or c.lastname like '%".trim($srch)."%' or c.phone like '%".trim($srch)."%' or c.email like '%".trim($srch)."%') ";
	}
	if($_GET['countryid']!=""){
		$sql=$sql." and (a.countryid='$countryid')";
	}
	if($_GET['fdate']!="" && $_GET['tdate']!=""){
		$sql=$sql." and ( c.regdate between '".sqldate($fdate)."' and '".sqldate($tdate)."')";
	}else if ($_GET['fdate']!=""){
		$sql=$sql." and ( c.regdate>='".sqldate($fdate)."')";
	}else if ($_GET['tdate']!=""){
		$sql=$sql." and ( c.regdate<='".sqldate($tdate)."')";
	}
	$sql=$sql." group by c.compid order by c.compid desc";
	$result = $mysqli->query($sql);
	$num_rows = mysqli_num_rows($result);
	if ($num_rows>0){
		if($_GET['dl']!="xls"){
			$p=$_GET['p'];
			include("grojsus.php");
			$g = grojsus($num_rows,$p,15,"","p",true,"SE",10,0);
			$sql = $sql. " LIMIT $g[3],$g[5]";
			$result = $mysqli->query($sql);
		}
		$i=($p*15);
		while($row=$result->fetch_array()){$i++;
			$sqlin="SELECT sum(ordtotal) as ordval, ordcur from ccd9orders where email='".trim($row['email'])."' and status>0";
			$resin=query_first($sqlin);
		?>
    <tr>
	  <td><?php echo $i?></td>
	  <td><?php echo dbval($resin['ordcur'])?></td>
	  <td><?php echo dbval($resin['ordval'])?></td>
      <td><?php echo dbval($row['username']).' '.dbval($row['lastname'])?></td>
	  <td><?php echo dbval($row['phone'])?></td>
	  <td><?php echo dbval($row['email'])?></td>
	  <td><?php echo dbval($row['countryname'])?></td>
	  <td><?php echo inddate(substr($row['regdate'],0,10))?></td>
	  <td><?php echo inddate(substr($row['lastlogin'],0,10))?></td>
	  <?php if($_GET['dl']=="xls"){?>
	  <td><?php echo dbval($row['address1'])?></td>
	  <td><?php echo dbval($row['address'])?></td>
	  <td><?php echo dbval($row['city'])?></td>
	  <td><?php echo dbval($row['state'])?></td>
	  <?php }else if($_GET['dl']!="xls"){?>
      <td>
          <!-- <a href="techprint.php?compid=<?php echo $row['compid']?>" target="_blank"><i class="fa fa-print" aria-hidden="true"></i></a> -->
      </td>
	  <?php }?>
    </tr>
	<?php } 
	if($num_rows>$g[5] && $_GET['dl']!="xls"){?>
	<tr>
      <td colspan='5'><?php echo $g[1];?></td>
	</tr>
	<?php } }?>
  </tbody>
</table>
<?php 
if($_GET['dl']!="xls"){
	include("bot.php");
}
?>