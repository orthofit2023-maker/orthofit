<?php 
session_start();
if(!isset($_SESSION['loginid'])){
	header("location:login.php");
}
include("db5conn.php");
$arrres = array();
$arr=array( 'prodcode', 'prodkeys', 'prodalt',  'prodcolor', 'prodname', 'proddesc', 'prodsize', 'prodprice', 'offerprod', 'prodstatus', 'produrl');
//'prodcats', 'type1', 'type2', 'type3', 'prodrelated', 'crosssell', 'accessories', 'forkids', 'combocodes', 'shippingtype', 'whomtype', 'occasion1', 'occasion2', 'sortby'


if(inpval($_POST['prodname'])!="" && checkuseraccess(0,0)){
	//exit();

	$prodid =  intval($_POST['prodid']);
	$prodcats=$_POST['prodcats'];
	$type1=$_POST['type1'];
	$type2=$_POST['type2'];
	$type3=$_POST['type3'];
	//$prodrelated=$_POST['prodrelated'];
	//$crosssell=$_POST['crosssell'];
	//$accessories=$_POST['accessories'];
	//$forkids=$_POST['forkids'];
	//$combocodes=$_POST['combocodes'];
	//$shippingtype=$_POST['shippingtype'];
	//$whomtype=$_POST['whomtype'];
	//$occasion1=$_POST['occasion1'];
	//$occasion2=$_POST['occasion2'];
	
	for($n=0;$n<count($arr);$n++){
		$arrres[$arr[$n]]=inpval($_POST[$arr[$n]]);
	}
	/*
	if($arrres['discfrdate']!='')$arrres['discfrdate']=sqldate($arrres['discfrdate']);
	if($arrres['disctodate']!='')$arrres['disctodate']=sqldate($arrres['disctodate']);
	if($arrres['offerfrdate']!='')$arrres['offerfrdate']=sqldate($arrres['offerfrdate']);
	if($arrres['offertodate']!='')$arrres['offertodate']=sqldate($arrres['offertodate']);
	if($arrres['prodfrdate']!='')$arrres['prodfrdate']=sqldate($arrres['prodfrdate']);
	*/
	if($arrres['produrl']==''){
		$arrres['produrl']=geturl(trim($arrres['prodname']),trim($arrres['prodcode']));
	}

		if($prodid>0 && checkuseraccess(0,0,2)){
			$query="update ccd9products set ";
			
			for($n=0;$n<count($arr);$n++){
				$query =$query.$arr[$n]."='".$arrres[$arr[$n]]."', ";
			}
			$query =$query." loginid='".$_SESSION["loginid"]."', produrl='".$arrres['produrl']."' where prodid='$prodid'";

			$mysqli->query($query);


		}else if($prodid=="0" && checkuseraccess(0,0,1)){
			
			$query = "insert into ccd9products (";
			for($n=0;$n<count($arr);$n++){
				$query =$query.$arr[$n].", ";
			}
			$query =$query."entrydate, loginid) values(";
			for($n=0;$n<count($arr);$n++){
				$query =$query ."'".$arrres[$arr[$n]]."', ";
			}
			$query =$query."CURDATE(), '".$_SESSION['loginid']."')";
			$mysqli->query($query);
			$prodid=mysqli_insert_id($mysqli);//echo $query.'<BR>';
			//exit();
		}

		//exit($query);
		//;
		if($prodid>0){
			//--------------product categories----------------------------
			storearray($prodcats, $prodid, 'ccd9prod2cat','2');

			//--------------product type 1----------------------------
			storearray($type1, $prodid, 'ccd9prod2type1','3');

			//--------------product type 2----------------------------
			storearray($type2, $prodid, 'ccd9prod2type2','4');

			//--------------product type 3----------------------------
			storearray($type3, $prodid, 'ccd9prod2type3','7');

			//--------------product related----------------------------
			//storearray($prodrelated, $prodid, 'ccd9prodrelated','1');

			//--------------product cross sell----------------------------
			//storearray($crosssell, $prodid, 'ccd9prodxsell','1');

			//--------------product accessories----------------------------
			//storearray($accessories, $prodid, 'ccd9prodacces','1');

			//--------------product kids----------------------------
			//storearray($forkids, $prodid, 'ccd9prod4kids','1');

			//--------------product combo----------------------------
			//storearray($combocodes, $prodid, 'ccd9prodcombo','1');

			//--------------product type 9 shippingtype---------------------
			//storearray($shippingtype, $prodid, 'ccd9prod2type9', '1');

			//--------------product type 8 whomtype-------------------------
			//storearray($whomtype, $prodid, 'ccd9prod2type8', '1');

			//--------------product type 10 occasion1-----------------------
			//storearray($occasion1, $prodid, 'ccd9prod2type10', '1');

			//--------------product type 11 occasion2-----------------------
			//storearray($occasion2, $prodid, 'ccd9prod2type11', '1');

			//if(){
				$sqllist="SELECT c.prodid, c.prodcode, if(t1c.typevalue1!='', concat(c.prodcode,t1c.typevalue1, t3c.typevalue1),if(t3c.typevalue1!='', concat(c.prodcode, t3c.typevalue1),c.prodcode )) as sku, c.prodname, c.prodsize, t1c.typeid as fitid, t1c.typevalue1 as typefit, t3c.typeid as colid, t3c.typename, t3c.typevalue1 from ccd9products c join ccd9prod2type1 t1 on c.prodid=t1.prodid join ccd9types t1c on t1.typeid=t1c.typeid join ccd9prod2type3 t3 on c.prodid=t3.prodid join ccd9types t3c on t3.typeid=t3c.typeid and t3c.opt=7  left join ccd9prod2cat t on t.prodid=c.prodid  where c.prodcode!='' and c.prodid='$prodid' group by c.prodid, t3.typeid, t1.typeid order by c.prodid;"; //,t1c.typevalue1,t3c.typevalue1 //c.prodid not in (SELECT prodid FROM `ccd9stocks` ) 
				$result = $mysqli->query($sqllist);
				$num_rows = mysqli_num_rows($result);
				if ($num_rows>0){ $i=0; 
						while($row=$result->fetch_array()){  $i++;
							$prodid = $row['prodid'];  
							//echo $i.'='.$row['prodname'].' ('.$row['typefit'].' - '.$row['typename'].')<br> ';
							$prodsize=$row['prodsize'];
							$arrsize=array();
							if(strstr($prodsize,',')){
								$arrsize=explode(',', dbval($prodsize));
							}else{
								if($prodsize!='')$arrsize[0]=dbval($prodsize);
							}
							if($prodsize!=''){
								for($n=0;$n<count($arrsize);$n++){
									if(trim($arrsize[$n])!='' && trim($arrsize[$n])!='NA'){
									$prodcode = trim($row['sku']).trim($arrsize[$n]);
									//echo $prodcode.'<br>';
										if(trim($row['prodcode'])=='')$prodcode='';

										$res=query_first("select stockid from ccd9stocks where prodid='$prodid' and type1='".$row['fitid']."' and type2='".trim($arrsize[$n])."' and type2='".$row['colid']."'");
										if($res['stockid']>0){}else{
											$mysqli->query("insert into ccd9stocks (prodid, type1, type2, type3, prodsku) values ('".$row['prodid']."', '".$row['fitid']."', '".trim($arrsize[$n])."', '".$row['colid']."', '$prodcode')");
										}
									}

								}
							}else{
									$mysqli->query("insert into ccd9stocks (prodid, type1, type2, type3, prodsku) values ('".$row['prodid']."', '".$row['fitid']."', '', '".$row['colid']."', '$prodcode')");
							}
							

						}
							//echo '<br>--------------------------end------------------------<br>';
				}


			//}
		}

	$p=inpval($_POST['p']);
	//exit();
	header("Location:products.php?p=$p");

}else if($_GET['prodid']!=""){
	$prodid=$_GET['prodid'];

	$res=query_first("select * from ccd9products where prodid='$prodid'");
	for($n=0;$n<count($arr);$n++){
		//$$arr[$n]=dbval($res[$arr[$n]]);

		$arrres[$arr[$n]]=dbval($res[$arr[$n]]);
		
	}
	/*
	if($discfrdate!='')$discfrdate=inddate($discfrdate);
	if($disctodate!='')$disctodate=inddate($disctodate);
	if($offerfrdate!='')$offerfrdate=inddate($offerfrdate);
	if($offertodate!='')$offertodate=inddate($offertodate);
	if($prodfrdate!='')$prodfrdate=inddate($prodfrdate);
	*/
	
}
$p=$_GET['p'];
include("top.php");?>
<div class="header">
	<h1 class="page-title">Product Master</h1>
	<ul class="breadcrumb">
	<li><a href="index.php">Home</a> </li>
	<li class="active">Product Master</li>
	</ul>

</div>
<div class="main-content">
<?php echo ($msg!="") ? '<div class="err">'.$msg."</div>" : "";?>
<ul class="nav nav-tabs">
  <li class="active"><a href="#home" data-toggle="tab">Main</a></li>
  <li><a href="#tab2" data-toggle="tab">Description</a></li>
</ul>

<div class="row">
  <div class="col-md-6">
    <form name="frm" method="post" action='product.php' onsubmit="return validate()">
    <div id="myTabContent" class="tab-content">
	  <input type="hidden" name="prodid" value="<?php echo $prodid?>">
	  <input type="hidden" name="p" value="<?php echo $p?>">
	  <div class="tab-pane active in" id="home">
	  <br>
		<div class="form-group col-md-6 left">
			<label>Product Name</label>
			<input type="text" name="prodname" value="<?php echo $arrres['prodname']?>" maxlength="120" class="form-control">
        </div>
		<div class="form-group col-md-6">
			<label>Product URL</label>
			<input type="text" name="produrl" value="<?php echo $arrres['produrl']?>" maxlength="120" class="form-control">
        </div>

		<div class="form-group col-md-4 left">
			<label>Product Code</label>
			<input type="text" name="prodcode" value="<?php echo $arrres['prodcode']?>" maxlength="20" class="form-control">
        </div>
		<div class="form-group col-md-4">
			<label>INR Price</label>
			<input type="text" name="prodprice" value="<?php echo $arrres['prodprice']?>" maxlength="10" class="form-control">
        </div>
		<div class="form-group col-md-4">
			<label>Offer Price</label>
			<input type="text" name="offerprod" value="<?php echo $arrres['offerprod']?>" maxlength="5" class="form-control">
        </div>
		
		<div style="clear:both;"></div>
		<div class="form-group col-md-6 left">
			<label>Main Category (Menu)</label>
			<select name="prodcats[]" multiple class="chosen-select form-control">
				<?php echo getcatmenu($prodid);?>
			</select>
        </div>
		<div class="form-group col-md-6">
			<label>Type 1 (fit)</label>
			<select name="type1[]" multiple class="chosen-select form-control">
				<?php echo gettype1menu($prodid);?>
			</select>
        </div>
		<div style="clear:both;"></div>
		<div class="form-group col-md-6 left">
			<label>Type 2 (sizes)</label>
			<select name="type2[]" multiple class="chosen-select form-control">
				<?php echo gettype2menu($prodid);?>
			</select>
        </div>
		<div class="form-group col-md-6">
			<label>Type 3 (colors)</label>
			<select name="type3[]" multiple class="chosen-select form-control">
				<?php echo gettype3menu($prodid);?>
			</select>
        </div>

		

		<div style="clear:both;"></div>
		<div class="form-group col-md-4 left">
			<label>Sizes</label>
			<input type="text" name="prodsize" value="<?php echo $arrres['prodsize']?>" maxlength="120" class="form-control">
        </div>
		<div class="form-group col-md-4">
			<label>Colors</label>
			<input type="text" name="prodcolor" value="<?php echo $arrres['prodcolor']?>" maxlength="120" class="form-control">
        </div>
		
		<div class="form-group col-md-4">
			<label>Active</label>
			<select name="prodstatus" class="form-control">
			<option value="1" <?php echo ($arrres['prodstatus']==1) ? " selected" : "";?>>Yes</option>
			<option value="0" <?php echo ($arrres['prodstatus']==0) ? " selected" : "";?>>No</option>
            </select>
        </div>

		<div style="clear:both;"></div>
	</div>
    <div class="tab-pane fade" id="tab2">
		<BR>
		<div class="form-group col-md-12 left">
			<label>Product ALT</label>
			<input type="text" name="prodalt" value="<?php echo $arrres['prodalt']?>" maxlength="160" class="form-control">
        </div>
		<div class="form-group col-md-12 left">
			<label>Product Details</label>
			<textarea type="text" id="editor1" name="proddesc" class="form-control"><?php echo $arrres['proddesc']?></textarea>
        </div>
		<div class="form-group col-md-12 left">
			<label>Product Keywords</label>
			<textarea type="text" name="prodkeys" class="form-control" style="height:80px;"><?php echo $arrres['prodkeys']?></textarea>
        </div>
		
		
		
		<div style="clear:both;"></div>
	</div>



    </div>

    <div class="form-group col-md-12">
      <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
    </div>
    </form>
  </div>
</div>

<SCRIPT LANGUAGE="JavaScript">
<!--
function validate(){
	if(document.frm.prodname.value==""){
		alert("Please enter Product Name");
		document.frm.prodname.focus();
		return false;
	}else if(document.frm.prodcode.value==""){
		alert("Please enter Product Code");
		document.frm.prodcode.focus();
		return false;
	}else{
		
		return true;
	}
}
//-->
</SCRIPT>
<script type="text/javascript" src="js/nicEdit.js"></script>
<script type="text/javascript">
bkLib.onDomLoaded(function() {
	if(document.getElementById('proddesc>')){
		new nicEditor({buttonList : ['bold','italic','underline','strikeThrough','subscript','superscript']}).panelInstance('ques<?php echo $arrtxtarea[$x]?>');
	}
});
</script>
<?php include("bot.php");?>