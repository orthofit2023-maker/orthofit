<?php 
session_start();
if(!isset($_SESSION['loginid'])){
	header("location:login.php");
}
include("db5conn.php");
if($_GET['dl']=="xls" && checkuseraccess(0,0,5)){
	header("Content-Type: application/vnd.ms-excel");
	header("Content-disposition:attachment;filename=repoordabandcart.xls"); 
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
}else{
	include("top.php");
}
$fdate=$_GET['fdate'];
if($fdate=='')$fdate=date('d/m/Y', mktime(0,0,0,1,1,date("Y")));
$tdate=$_GET['tdate'];
if($tdate=='')$tdate=date('d/m/Y', mktime(0,0,0,13,0,date("Y")));
$srch=inpval($_GET['srch']);
$cur=inpval($_GET['cur']);
$status=inpval($_GET['status']);
$typeid=inpval($_GET['typeid']);
if($_GET['dl']!="xls"){
?>
<div class="header">
	<h1 class="page-title">Report Orders</h1>
	<ul class="breadcrumb">
	<li><a href="index.php">Home</a> </li>
	<li class="active">Report Orders</li>
	</ul>

</div>
<div class="main-content">
<?php echo $_GET['msg'];?>
<div class="btn-toolbar list-toolbar">
	<form method="get" name="frm" action="repoordcart.php">
	<input type="hidden" name="delid">
		<input type="text" name="srch" value="<?php echo $_GET['srch']?>" maxlength="250" class="form-control input-srch"  placeholder="name / email / code">
		<input type="text" name="fdate" value="<?php echo $fdate?>" maxlength="10" class="form-control input-srch datepicker" placeholder="from dd/mm/yyyy">
		<input type="text" name="tdate" value="<?php echo $tdate?>" maxlength="10" class="form-control input-srch datepicker" placeholder="to dd/mm/yyyy">
		
		<select name="status" class="form-control input-srch">
			<option value=''>Select Status</option>
			<?php 
			$sql = "select * from ccd9orderstatus order by statusid";
			$result = $mysqli->query($sql);
			while($row=$result->fetch_array()){?>
			<option value="<?php echo $row['statusid']?>" <?php echo ($row['statusid']==$status) ? "selected" : "";?>><?php echo $row['statusname']?></option>
			<?php }?>
          </select>
		<select name="cur" class="form-control input-srch" onchange="document.frm.submit()">
			<?php echo getcurmenu($cur);?>
		</select>
		<select name="typeid" class="form-control input-srch">
			<option value=''>Select Type</option>
			<?php $retval='';
					$sql="select t.typeid, t.typename from ccd9prod2type1 pt join ccd9types t on t.typeid=pt.typeid where 1=1  group by t.typeid order by t.typename";
					$result = $mysqli->query($sql);
					while($rescon = $result->fetch_array()){$retsel="";
						if($rescon['typeid']==$typeid){
							$retsel=" selected";
						}
						$retval=$retval.'<option value="'.$rescon['typeid'].'" '.$retsel.'>'.$rescon['typename'].'</option>';
					} 
					echo $retval;
				?>
          </select>
		<button type='submit' class="btn btn-primary"><i class="fa fa-search"></i> </button>
		<?php if(checkuseraccess(0,0,5)){?>
		<a href='?dl=xls&fdate=<?php echo $fdate?>&tdate=<?php echo $tdate?>&srch=<?php echo $srch?>&cur=<?php echo $cur?>&status=<?php echo $status?>&typeid=<?php echo $typeid?>' class="btn btn-danger"><i class="fa fa-file-excel-o"></i></a>
		<?php }?>
    </form>
</div>
<?php }?>
<table class="table table-striped" <?php echo ($_GET['dl']=="xls" ? 'border="1"' : '')?>>
  <thead>
    <tr>
      <th>Order #</th>
      <th>Date</th>
      <th>Customer</th>
	  <?php if(checkuseraccess('', '60','4')){ ?>
      <th>Email</th>
	  <?php }?>
	  <th>Code</th>
      <th>Product</th>
	  <th>Product Type</th>
      <th>Currency</th>
      <th>Value</th>
	  <th>Status</th>
	  <th>Transaction#</th>
	  <?php if(checkuseraccess('', '60','4')){ ?>
	  <th>Address 1</th>
	  <th>Address 2</th>
	  <th>City</th>
      <th>State</th>
	  <th>Zipcode</th>
      <th>Country</th>
	  <?php }?>
    </tr>
  </thead>
  <tbody>
	<?php 
	$i=0; 
	$sql="SELECT c.prodname, p.prodcode, c.finalprice, o.tx, o.orderid, t.typename as prodtype, date_format(c.datemodified,'%d/%b/%y') as cdt, date_format(o.orddate,'%d/%b/%y') as dt, o.orddate, o.ordtotal, c.prodcur, concat(m.username,' ',m.lastname) as user, concat(o.username,' ',o.lastname) as ouser, o.email, o.billing_address1, o.billing_address, o.billing_city, o.billing_state, o.billing_zipcode, cm.countryname, s.statusname FROM ccd9cart c join `ccd9orders` o on c.compid=o.compid and c.orderid=o.orderid and c.compid>0 and c.status='1' join ccd9products p on p.prodid=c.prodid join ccd9company m on m.compid=o.compid join ccd9country cm on o.billing_country=cm.countryid join ccd9orderstatus s on o.status=s.statusid left join ccd9prod2type1 pt1 on c.prodid=pt1.prodid left join ccd9types t on t.typeid=pt1.typeid where o.status>0  ";
	if($_GET['srch']!="" && $_GET['srch']!="name"){
		$srch=$_GET['srch'];
		$sql=$sql." and ( o.username like '%".trim($srch)."%' or o.lastname like '%".trim($srch)."%' or o.email like '%".trim($srch)."%' or o.orderid like '%".trim($srch)."%' or p.prodcode like '%".trim($srch)."%') ";
	}
	if($_GET['status']!=""){
		$sql=$sql." and o.status='".trim($status)."'";
	}
	if($_GET['cur']!=""){
		$sql=$sql." and ( o.ordcur='".trim($cur)."')";
	}
	if($_GET['typeid']>0){
		$sql=$sql." and ( pt1.typeid='".trim($typeid)."')";
	}
	if($fdate!="" && $tdate!=""){
		$sql=$sql." and ( o.orddate between '".sqldate($fdate)."' and '".sqldate($tdate)." 23:59:59')";
	}else if ($fdate!=""){
		$sql=$sql." and ( o.orddate>='".sqldate($fdate)."')";
	}else if ($tdate!=""){
		$sql=$sql." and ( o.orddate<='".sqldate($tdate)." 23:59:59')";
	}
	$sql=$sql." group by c.prodid, o.orderid order by o.orddate desc, o.orderid, c.prodid";
	//echo $sql;
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
		?>
    <tr>
      <td><?php echo getordno($row['orderid'], $row['orddate'])?></td>
      <td><?php echo ($row['dt']!='' ? $row['dt'] : $row['cdt'])?></td>
      <td><?php echo (trim($row['user'])!='' ? $row['user'] : $row['ouser'])?></td>
	  <?php if(checkuseraccess('', '60','4')){ ?>
      <td><?php echo $row['email']?></td>
      <?php }?>
	  <td><?php echo $row['prodcode']?></td>
      <td><?php echo $row['prodname']?></td>
	  <td><?php echo $row['prodtype']?></td>
      <td><?php echo $row['prodcur']?></td>
	  <td><?php echo number_format($row['finalprice'])?></td>
	  <td><?php echo $row['statusname']?></td>
      <td><?php echo $row['tx']?></td>
	  <?php if(checkuseraccess('', '60','4')){ ?>
      <td><?php echo $row['billing_address']?></td>
      <td><?php echo $row['billing_address1']?></td>
      <td><?php echo $row['billing_city']?></td>
	  <td><?php echo $row['billing_state']?></td>
	  <td><?php echo $row['billing_zipcode']?></td>
	  <td><?php echo $row['countryname']?></td>
      <?php }?>

	</tr>
	<?php } 
	if($_GET['dl']!="xls"){
	?>
	<tr>
      <td colspan='4'><?php if($num_rows>$g[5]){ echo $g[1];}?></td>
	</tr>
	<?php } }?>
  </tbody>
</table>

<?php 
if($_GET['dl']!="xls"){
	include("bot.php");
}?>