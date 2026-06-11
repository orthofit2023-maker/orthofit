<?php 
session_start();
if(!isset($_SESSION['loginid'])){
	header("location:login.php");
}
include("db5conn.php");

include("top.php");
if($_POST['cartid']>0){
	$orderid=inpval($_POST['orderid']);
	$prodid=inpval($_POST['prodid']);
	$cartid=inpval($_POST['cartid']);
	$othnote=inpval($_POST['othnote']);
	$shipping=inpval($_POST['shipping']);
	$delnote=inpval($_POST['delnote']);
	$dispdate=sqldate($_POST['dispdate']);
	$reqfrom=inpval($_POST['reqfrom']);
	$reqto=inpval($_POST['reqto']);
	$reqdate=sqldate($_POST['reqdate']);
	$prodmeas=inpval($_POST['prodmeas']);

	$heightft=intval($_POST['heightft']);
	$heightin=intval($_POST['heightin']);
	if($heightin=='')$heightin='0';
	$height=$heightft.'.'.$heightin;
	$heelheight=intval($_POST['heelheight']);
	if($heelheight=='' || $heelheight=='0')$heelheight='NA';
	$dimunit="Inches";
	$dimlist[0]=$prodid;
	$dimlist[1]=$prodmeas;
	$dimlist[2]=$dimunit;
	$dimlist[3]=$height;
	$dimlist[4]=$heelheight;

	for($n=1;$n<=$_POST['cnt'];$n++){
		$typeid=intval($_POST['typeid'.$n]);
		$dimval=intval($_POST['dimval'.$n]);
		array_push($dimlist, $typeid.'='.intval($dimval));

	}
	$measlist=implode(',', $dimlist);
	if($cartid>0){
		$sql="update ccd9cart set measlist='$measlist', othnote='$othnote', shipping='$shipping', delnote='$delnote', dispdate='$dispdate', reqfrom='$reqfrom', reqto='$reqto', delnote='$delnote', reqdate='$reqdate' where cartid='$cartid'";
		//echo $sql;
		//exit();
		$mysqli->query($sql);
	}
	header("location:".$serverurl."orderchart.php?orderid=1&prodid=468");

}else{

	$orderid=$_GET['orderid'];
	$prodid=$_GET['prodid'];
	$res=query_first("select * from ccd9orders c where c.orderid='$orderid'");
	$row = query_first("select c.*, p.prodcode, p.produrl, p.prodmeas as pmeas from ccd9cart c join ccd9products p on p.prodid=c.prodid where c.orderid='$orderid' and c.prodid='$prodid' order by c.cartid");
	$resin=query_first("select typename from ccd9types where typevalue='".($row['prodmeas']>0 ? $row['prodmeas'] : $row['pmeas']) ."' and opt='6'");
	$typelist=str_replace(",","','",$resin['typename']);
	//echo $typelist;
	$cartid=dbval($row['cartid']);
	$prodmeas=dbval($row['prodmeas']);
	$othnote=dbval($row['othnote']);
	$shipping=dbval($row['shipping']);
	$delnote=dbval($row['delnote']);
	$dispdate=inddate($row['dispdate']);
	//$heightft=dbval($row['cartid']);
	//$heightin=dbval($row['cartid']);
	//$heelheight=dbval($row['cartid']);
	$reqfrom=dbval($row['reqfrom']);
	$reqto=dbval($row['reqto']);
	$reqdate=inddate($row['reqdate']);
	if($reqfrom=='')$reqfrom='Nirvaan K. (PS Inc.)';
	if($reqto=='')$reqto='Komal (Production Team)';
	if($reqdate=='')$reqdate=date('d/m/Y');
	$dimlist = array();
	if($row['measlist']!=''){
		//echo $row['measlist'];
		$dimlist=explode(",",$row['measlist']);
		$prodmeas=$dimlist[1];
		$dimunit=$dimlist[2];
		$height=$dimlist[3];
		if($height!=''){
			list($heightft,$heightin)=explode('.',$height);
		}
		$heelheight=$dimlist[4];
		for($n=5;$n<count($dimlist);$n++){
			list($typeval,$dimval)=explode('=',$dimlist[$n]);
			$dimlist[$typeval]=$dimval;
		}
	}
}

?>
<div class="row">
  <div class="col-md-8">
  <form method="post">
  <input type="hidden" name="cartid" value="<?php echo $cartid;?>">
  <input type="hidden" name="prodid" value="<?php echo $prodid;?>">
  <input type="hidden" name="orderid" value="<?php echo $orderid;?>">
  <input type="hidden" name="prodmeas" value="<?php echo $prodmeas;?>">
  <table class="table">
  <tr>
    <td>From:</td>
    <td><input type="text" class="form-control" name="reqfrom" value="<?php echo $reqfrom?>"></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>To:</td>
    <td><input type="text" class="form-control" name="reqto" value="<?php echo $reqto?>"></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Submission Date:</td>
    <td><input type="text" class="form-control datepicker" name="reqdate" value="<?php echo $reqdate?>"></td>
    <td align="center"><B>1 of 5 items</B></td>
  </tr>
  <tr>
    <td>Company / Website:</td>
    <td>payalsinghal.com</td>
    <td rowspan="19" align="center" valign="top">
	<div class="col-md-4"><img src="../<?php echo getprodimg($row['produrl'])?>" width="350"/></div>
	</td>
  </tr>
  <tr>
    <td>Order#:</td>
    <td><?php echo getordno($orderid);?></td>
  </tr>
  <tr>
    <td>Client Name:</td>
    <td><?php echo dbval($res['username']).' '.dbval($res['lastname'])?></td>
  </tr>
  <tr>
    <td>Apt #</td>
    <td><?php echo dbval($res['address'])?></td>
  </tr>
  <tr>
    <td>Street</td>
    <td><?php echo dbval($res['address1'])?></td>
  </tr>
  <tr>
    <td>Apt #</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>City</td>
    <td><?php echo dbval($res['city'])?></td>
  </tr>
  <tr>
    <td>State</td>
    <td><?php echo dbval($res['state'])?></td>
  </tr>
  <tr>
    <td>Zip</td>
    <td><?php echo dbval($res['zipcode'])?></td>
  </tr>
  <tr>
    <td>Country</td>
    <td><?php echo dbval(getcountry($res['country']))?></td>
  </tr>
  <tr>
    <td>Client Phone:</td>
    <td><?php echo dbval($res['phone'])?></td>
  </tr>
  <tr>
    <td>Client email:</td>
    <td><?php echo dbval($res['email'])?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>Item #</td>
    <td><?php echo dbval($row['prodcode'])?></td>
  </tr>
  <tr>
    <td>Name</td>
    <td><?php echo dbval($row['prodname'])?></td>
  </tr>
  <tr>
    <td>Colour:</td>
    <td><?php echo dbval($row['prodcolor'])?></td>
  </tr>
  <tr>
    <td>Size</td>
    <td><?php echo dbval($row['prodsize'])?></td>
  </tr>
  <tr>
    <td>Dispatch Date:</td>
    <td><input type="text" class="form-control datepicker" name="dispdate" value="<?php echo $dispdate?>"></td>
  </tr>
  <tr>
    <td>Delivery Notes:</td>
    <td colspan="2"><input type="text" class="form-control" name="delnote" value="<?php echo $delnote?>" maxlength="120"></td>
  </tr>
  <tr>
    <td>Shipping:</td>
    <td colspan="2"><input type="text" class="form-control" name="shipping" value="<?php echo $shipping?>" maxlength="200"></td>
  </tr>
  <tr>
    <td colspan="3">Custom measurements:</td>
  </tr>
  <tr>
    <td colspan="3"><table class="table">
      <tr>
        <td>Customers Height</td>
        <td>Feet</td>
		<td>&nbsp;</td>
        <td>Inches </td>
		<td>Heel Height</td>
      </tr>
	  <tr>
        <td>&nbsp;</td>
        <td><input type="text" class="form-control" name="heightft" value="<?php echo $heightft?>"></td>
		<td>&nbsp;</td>
        <td><input type="text" class="form-control" name="heightin" value="<?php echo $heightin?>"></td>
		<td><input type="text" class="form-control" name="heelheight" value="<?php echo $heelheight?>"></td>
      </tr>
	  <tr>
        <td>Custom measurements:</td>
        <td>Inches </td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>Inches </td>
		<td>&nbsp;</td>
      </tr>
	  <tr>
	  <?php $i=0;
	  $sql="select * from ccd9types where typeid in ('".$typelist."')";
	  $result = $mysqli->query($sql);
	  while($rescon = $result->fetch_array()){ $i++;
	  ?>
        <td><?php echo $rescon['typename'];?></td>
        <td><input type="text" class="form-control" name="dimval" value="<?php echo $dimlist[$rescon['typeid']]?>">
		<input type="hidden" value="<?php echo $rescon['typeid']?>" name="typeid<?php echo $i?>">
		</td>
		<td>&nbsp;</td>
	  <?php 
		if($i%2==0) echo '</tr><tr>';
	  }?>
      </tr>
	  <input type="hidden" value="<?php echo $i;?>" name="cnt">
    </table></td>
  </tr>
  <tr>
    <td>Notes:</td>
    <td colspan="2"><textarea class="form-control" name="othnote" rows="3" maxlength="200"><?php echo $othnote?></textarea></td>
  </tr>
  <tr>
    <td></td>
    <td colspan="2"><button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> SAVE</button></td>
  </tr>
</table>
</form>
  </div>
</div>
<?php include("bot.php");?>