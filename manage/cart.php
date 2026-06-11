<?php 
/*

$res= query_first("select height, heelheight, refsize from ccd9company where compid='".$_SESSION['compid']."'");
$height=$res['height']; $refsize=$res['refsize'];
$heelheight=$res['heelheight'];
$dimlist = array('',$refsize,'Inches',$height,$heelheight);

$sql = "select * from ccd9userdim where compid='".$_SESSION['compid']."'";
$query=$mysqli->query($sql);
$num_rows = mysqli_num_rows($query);
if($num_rows>0) {
    while($res=$query->fetch_array()){
        $dimlist[$res['typeid']]=intval($res['dimval']);
    }
}
*/
//----------------------------------------------------------------
session_start();
if(!isset($_SESSION['loginid'])){
	header("location:login.php");
}
include("db5conn.php");
if($_POST['delid']!=""){
    $prodid=inpval($_POST['delid']);
	$userid=inpval($_POST['userid']);
	header("Location:cart.php?userid=$userid");
}

if($_POST['prodid']>0 && $_POST['userid']>0){
	$prodid=inpval($_POST['prodid']);
	$userid=inpval($_POST['userid']);
    

    
}else if($_GET['srch']!=""){
	$prodcode = inpval($_GET['srch']);
    
	$row=query_first("select * from ccd9products where prodcode='$prodcode' and prodstatus='1'");
	if($row['prodid']>0){
		$prodname=dbval($row['prodname']);
        $prodid=dbval($row['prodid']);
        $typeid=dbval($row['prodmeas']);
        $prodsize=$row['prodsize'];
		$produrl=dbval($row['produrl']);
        if($typeid>0){
			$row=query_first("select typename from ccd9types where typevalue='$typeid'");
			$measlist=str_replace(",","','",$row['typename']);
        }

        
	}
}
if($_GET['userid']>0){
    $userid=inpval($_GET['userid']);
}

if($_GET['cartid']>0){
    $cartid=inpval($_GET['cartid']);
    $row=query_first("select c.cartid, c.prodqty, c.prodsize, c.prodcur, c.finalprice, p.prodcode, p.prodname, p.prodid, c.height, c.heelheight, p.proddesc, p.produrl from ccd9cart c join ccd9products p on p.prodid=c.prodid where c.compid='$userid' and c.status='0' and p.prodstatus='1' and c.cartid='$cartid'");
	if($row['prodid']>0){
		$prodname=dbval($row['prodname']);
        $prodcode=dbval($row['prodcode']);
		$proddesc=dbval($row['proddesc']);
		$produrl=dbval($row['produrl']);
        $prodid=dbval($row['prodid']);
        $typeid=dbval($row['prodmeas']);
        $prodsize=$row['prodsize'];
        $height=$row['height'];
        $heelheight=$row['heelheight'];
        if($typeid>0){
            $row=query_first("select typename from ccd9types where typevalue='$typeid'");
            $measlist=str_replace(",","','",$row['typename']);
        }
        if($height!=''){
            list($heightft,$heightin)=explode('.',$height);
        }

        
	}

}

$p=$_GET['p'];

include("top.php");?>
<script type='text/javascript' src='js/jquery.autocomplete.js'></script>
<link rel="stylesheet" type="text/css" href="js/jquery.autocomplete.css" />
<script>
$(document).ready(function() {
	$("#customer").autocomplete("listsearch.php?getCustomer=1", {
		width: 420,
		matchContains: true,
		minChars: 0,
		cacheLength: 0,
		selectFirst: false
	});
	$("#customer").result(function(event, data, formatted) {
		$("#userid").val(data[1]);
	});
});

</script>
<div class="header">
	<h1 class="page-title">Shopping Cart</h1>
	<ul class="breadcrumb">
	<li><a href="index.php">Home</a> </li>
	<li class="active">Shopping Cart</li>
	</ul>

</div>
<div class="main-content">
<?php echo ($msg!="") ? '<div class="err">'.$msg."</div>" : "";?>
<div class="col-md-12 left">
	<form method="get" name="frmin" action="cart.php">
	    <input type="hidden" name="delid">
        <div style="clear:both;"><b>Select Customer</b></div>
		<input type="text" name="customer" id="customer" value="<?php echo $_GET['customer']?>" maxlength="20" class="form-control input-srch"  placeholder="Customer">
        <input type="hidden" id="userid" name="userid" value="<?php echo $userid;?>">

		<select class="form-control input-srch" name="cur">
			<option value="INR">INR</option>
			<option value="USD">USD</option>
		</select>
		
		<button type='submit' class="btn btn-primary"><i class="fa fa-search"></i> </button>
        <div style="clear:both;"></div>
        <?php if($userid>0){?>
		<select class="form-control input-srch" name="billingaddress">
			<option value="0">Billing Address</option>
		</select>
		<select class="form-control input-srch" name="shippingaddress">
			<option value="0">Shipping Address</option>
		</select>
		<div style="clear:both;"><br><b>Add Products</b></div>
		
		<input type="text" name="srch" value="<?php echo $prodcode?>" maxlength="20" class="form-control input-srch"  placeholder="Product Code">
		
		<button type='submit' class="btn btn-primary"><i class="fa fa-search"></i> </button>
        <?php }?>
    </form>
</div>

<?php if($prodid>0){
$file =$imgpath.$produrl.'-'.strtolower(chr(65)).'3.jpg';
//echo $file;
if(file_exists($file)){
	$prodimg = '<img src="'.$stackurl.getprodimg($produrl,strtolower(chr(65)),'3').'" style="width:90px;">';
}
?>
<div class="row">
  <div class="col-md-8">
    <form name="frm" method="post" action='cart.php' autocomplete="off">
    <div id="myTabContent" class="tab-content">
	  
	  <input type="hidden" name="prodid" value="<?php echo $prodid?>">
      <input type="hidden" name="userid" value="<?php echo $userid?>">
	  <input type="hidden" name="p" value="<?php echo $p?>">
	  <input type="hidden" name="delid">
		<div class="form-group col-md-2 left">
			<?php echo $prodimg?>
		</div>
		<div class="form-group col-md-10">
			<div class="form-group col-md-12">
			<label><?php echo $prodcode." ".$prodname?></label>
			<?php echo $proddesc?>
			</div>
			<div style="clear:both;"></div>
			<div class="form-group col-md-2 left">
				<label>Size</label>
				<?php 
				if(strstr($prodsize,'KIDS'))$prodsize=str_replace('KIDS:','',$prodsize);
				if(strstr($prodsize,',')){
					$arrsize=explode(',', dbval($prodsize));
				}else{$arrsize[0]=dbval($prodsize);}?>

				<select class="form-control" name="prodsize">
				<?php 
				$retval=''; 
				for($n=0;$n<count($arrsize);$n++){
					$retval=$retval.'<option value="'.$arrsize[$n].'"'.($arrsize[$n]==$cartsize ? ' selected' : '').'>'.$arrsize[$n].'</option>';
				}
				echo $retval;
				?>
				</select>

			</div>
			<div class="form-group col-md-2">
				<label>Qty</label>
				<select class="form-control" name="prodqty">
				<?php 
				 $retval=''; 
				for($n=1;$n<11;$n++){
					$retval=$retval.'<option value="'.$n.'">'.$n.'</option>';
				}
				echo $retval;
				?>
				</select>
			</div>

			<div class="form-group col-md-2">
				<label>Body (ft)</label>
				<input type="number" class="form-control" name="heightft" value="<?php echo $heightft?>" required>
			</div>
			<div class="form-group col-md-2">
				<label>(In)</label>
				<input type="number" class="form-control" name="heightin" value="<?php echo $heightin?>" required>
			</div>
			<div class="form-group col-md-2">
				<label>Heels (In)</label>
				<input type="number" class="form-control" name="heelheight" value="<?php echo $heelheight?>" required>
			</div>
			<div style="clear:both;"></div>
			<?php if($measlist!=''){ ?>
			<div class="form-group col-md-12 left">
			<label><input type="checkbox" class="checkbox-inline" name="custsize" value="1" onclick="showmeas(this)"> Customise Measurement</label>        
			</div>
        </div>
        <div style="clear:both;"></div>
        <div style="display:none" id="measdiv" class="col-md-10 left">
        <?php  $i=0;
            $sql="select * from ccd9types where typeid in ('".$measlist."')";
            $result = $mysqli->query($sql);
            while($rescon = $result->fetch_array()){ $i++;
                echo '
                <div class="form-group col-xs-3 col-md-4 left">
                    <label>'.$rescon['typename'].'</label>
                    <input type="text" class="form-control" name="dimval'.$i.'" value="'.($dimlist[$rescon['typeid']]).'" >
                    <input type="hidden" value="'.$rescon['typeid'].'" name="typeid'.$i.'">
                </div>';

            }


        }?>
        </div>
		
	
        <div style="clear:both;"></div>

    </div>
    
    <div class="form-group col-md-12">
      <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Add To Cart</button>
    </div>
    </form>
    <?php } 
    if($userid>0){?>
    <div style="clear:both;"></div>
        <?php $i=0;
        $sql="select c.compid, c.cartid, c.prodqty, c.prodsize, c.prodcur, c.finalprice, p.prodcode, p.prodname, p.produrl from ccd9cart c join ccd9products p on p.prodid=c.prodid where c.compid='$userid' and c.status='0' and p.prodstatus='1'";
        $result = $mysqli->query($sql);
        while($rescon = $result->fetch_array()){ $i++;
			$file =$imgpath.dbval($rescon['produrl']).'-'.strtolower(chr(65)).'3.jpg';
			//echo $file;
			if(file_exists($file)){
				echo '<div class="form-group col-md-2 left"><img src="'.$stackurl.getprodimg(dbval($rescon['produrl']),strtolower(chr(65)),'3').'" style="width:90px;"></div>';
			}
            echo '
            <div class="form-group col-md-6">
                <label>'.$rescon['prodcode'].'</label> '.$rescon['prodname'].'
                <input type="hidden" value="'.$rescon['cartid'].'" name="cartid'.$i.'">
				<label>'.$rescon['prodsize'].'</label>
            </div>
            <div class="form-group col-md-1">
                <label>'.$rescon['finalprice'].'</label>
            </div>
            <div class="form-group col-md-1">
                <label>'.$rescon['prodqty'].'</label>
            </div>
            <div class="form-group col-md-1">
                <a href="cart.php?cartid='.$rescon["cartid"].'&userid='.$rescon["compid"].'"><i class="fa fa-pencil"></i></a>
				<a href="#" data-href="'.$rescon["cartid"].'" role="button" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
            </div>
            <div style="clear:both;"></div>
            ';

        }

			echo '
            <div class="form-group col-md-8"></div>
            <div class="form-group col-md-1">
                <label>1,49,500</label>
            </div>
            <div class="form-group col-md-1">&nbsp;</div>
            <div class="form-group col-md-1">&nbsp;</div>
            <div style="clear:both;"></div>
            ';
        
        ?>
	<div style="clear:both;"></div>
	<div class="form-group col-md-12">
      <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Submit Order</button>
    </div>

	<div style="clear:both;"></div>
    <?php }?>


  </div>
</div>
<script>
function showmeas(x){
    if(x.checked==true){
        document.getElementById("measdiv").style.display='block';
    }else{
        document.getElementById("measdiv").style.display='none';
    }

}

</script>

<?php include("bot.php");?>