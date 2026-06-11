<?php 
session_start();
ini_set('max_execution_time', 300);
if(!isset($_SESSION['loginid'])){
	header("location:login.php");
}
include("db5conn.php");
if($_POST['prodcode']!='' && $_POST['prodnew']!=''){
	$prodcode=inpval($_POST['prodcode']);
	$prodnew=inpval($_POST['prodnew']);
	$prodcats=$_POST['prodcats'];

	$sql="select * from ccd9products where prodcode='$prodcode'";
	$result = $mysqli->query($sql);
	if($row = $result->fetch_array()){

		$produrl=geturl(trim($row['prodname']), trim($prodnew));

		//echo $produrl.'<br>';

		for($n=1;$n<20;$n++){	
			$urlfile =$imgcodepath.dbval($row['produrl']).'-'.strtolower(chr($n+64)).'0.jpg';
			$file =$imgcodepath.dbval($prodcode).strtolower(chr($n+64)).'0.jpg';
			$newfile =$imgcodepath.dbval($prodnew).strtolower(chr($n+64)).'0.jpg';
					//echo $file.'<BR>';
					//echo $newfile.'<BR>';

			if(file_exists($file)){
				if(copy($file, $newfile)){
					//echo $file.' xx<BR>';
					//echo $newfile.' xx<BR>';
				}
			}else if(file_exists($urlfile)){
				if(copy($urlfile, $newfile)){
					//echo $urlfile.' xx<BR>';
					//echo $newfile.' xx<BR>';
				}
			}
		}

		//exit();
		$mysqli->query("insert into ccd9products (`prodcode`, `prodkeys`, `prodalt`, `prodcats`, `type1`, `type2`, `type3`, `subcats`, `prodname`, `proddesc`, `prodsize`, `prodcolor`, `prodmeas`, `prodprice`, `usdprice`, `poundprice`, `europrice`, `prodcare`, `prodstatus`, `catid`, `subcatid`, `produrl`, `entrydate`, `noship`, `prodbox`, `shiptime`, `prodwgt`, `prodpack`, `shipprod`, `shipusd`, `freeship`, `proddisc`, `discfrdate`, `disctodate`, `offerprod`, `offerusd`, `offerfrdate`, `offertodate`, `prodfrdate`, `sortby`, `prodrelated`, `crosssell`, `accessories`, `forkids`, `combocodes`, `loginid`, `newentry`, `zone`, `disctext`, `sizeguide`, `discperc`, `toppick`, `readyship`, `nobadge`, `video1`, `shippingtype`, `whomtype`, `occasion1`, `occasion2`, `mfctby`, `packby`, `coo`) select '$prodnew', `prodkeys`, `prodalt`, `prodcats`, `type1`, `type2`, `type3`, `subcats`, `prodname`, `proddesc`, `prodsize`, `prodcolor`, `prodmeas`, `prodprice`, `usdprice`, `poundprice`, `europrice`, `prodcare`, '0', `catid`, `subcatid`, '$produrl', `entrydate`, `noship`, `prodbox`, `shiptime`, `prodwgt`, `prodpack`, `shipprod`, `shipusd`, `freeship`, `proddisc`, `discfrdate`, `disctodate`, `offerprod`, `offerusd`, `offerfrdate`, `offertodate`, `prodfrdate`, `sortby`, `prodrelated`, `crosssell`, `accessories`, `forkids`, `combocodes`, `loginid`, `newentry`, `zone`, `disctext`, `sizeguide`, `discperc`, `toppick`, `readyship`, `nobadge`, `video1`, `shippingtype`, `whomtype`, `occasion1`, `occasion2`, `mfctby`, `packby`, `coo` from ccd9products where prodid='".$row['prodid']."'");

		$prodid=mysqli_insert_id($mysqli);

		if($prodid>0){
			//--------------product categories----------------------------
			storearray(trim($prodcats), $prodid, 'ccd9prod2cat','2');

			//--------------product type 1----------------------------
			storearray(trim($row['type1']), $prodid, 'ccd9prod2type1','3');

			//--------------product type 2----------------------------
			storearray(trim($row['type2']), $prodid, 'ccd9prod2type2','4');

			//--------------product type 3----------------------------
			storearray(trim($row['type3']), $prodid, 'ccd9prod2type3');

			//--------------product related----------------------------
			storearray(trim($row['prodrelated']), $prodid, 'ccd9prodrelated');

			//--------------product cross sell----------------------------
			storearray(trim($row['crosssell']), $prodid, 'ccd9prodxsell');

			//--------------product accessories----------------------------
			storearray(trim($row['accessories']), $prodid, 'ccd9prodacces');

			//--------------product kids----------------------------
			storearray(trim($row['forkids']), $prodid, 'ccd9prod4kids');

			//--------------product combo----------------------------
			storearray(trim($row['combocodes']), $prodid, 'ccd9prodcombo');
		
			//--------------product type 9 shippingtype---------------------
			storearray(trim($row['shippingtype']), $prodid, 'ccd9prod2type9', '9');

			//--------------product type 8 whomtype-------------------------
			storearray(trim($row['whomtype']), $prodid, 'ccd9prod2type8', '8');

			//--------------product type 10 occasion1-----------------------
			storearray(trim($row['occasion1']), $prodid, 'ccd9prod2type10', '10');

			//--------------product type 11 occasion2-----------------------
			storearray(trim($row['occasion2']), $prodid, 'ccd9prod2type11', '11');

		}

		//--------------product categories----------------------------
		//storearray($prodcats, $prodid, 'ccd9prod2cat','2');

		//--------------product type 1----------------------------
		//storearray(trim($row['type1']), $prodid, 'ccd9prod2type1','3');

		//--------------product type 2----------------------------
		//storearray(trim($row['type2']), $prodid, 'ccd9prod2type2','4');


		//--------------product type 3----------------------------
		//storearray(trim($row['type3']), $prodid, 'ccd9prod2type3');

		
	}

	$msg= "Product added successfully!";

	//echo $msg;
	//exit();
	header("Location:product.php?prodid=$prodid?msg=$msg&p=$p");

}

if($_GET['msg']!="")$msg=$_GET['msg'];
$p=$_GET['p'];
include("top.php");?>
<div class="header">
	<h1 class="page-title">Copy Product</h1>
	<ul class="breadcrumb">
	<li><a href="index.php">Home</a> </li>
	<li class="active">Copy Product</li>
	</ul>

</div>
<div class="main-content">
<?php echo ($msg!="") ? '<div class="err">'.$msg."</div>" : "";?>
<div class="row">
  <div class="col-md-6">
    <form name="frm" method="post" action='prodcopy.php'>
    <div id="myTabContent" class="tab-content">
		<div class="form-group col-md-4 left">
        <label>Product Code (copy from)</label>
        <input type="text" name="prodcode" class="form-control">
        </div>
		<div class="form-group col-md-4">
        <label>Product Code (new)</label>
        <input type="text" name="prodnew" class="form-control">
        </div>
		<div class="form-group col-md-4">
		<label>Category</label>
		<select name="prodcats[]" multiple class="chosen-select form-control">
			<?php echo getcatmenu($prodid);?>
		</select>
        </div>
    </div>

    <div class="form-group">
      <button type="submit" class="btn btn-primary" name="btnsubmit"><i class="fa fa-save"></i> Save</button>
    </div>
    </form>
  </div>
</div>
<?php include("bot.php");?>