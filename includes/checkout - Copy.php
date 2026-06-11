<?php
if($_GET['do']==""){
$sqllist="select c.finalprice, p.prodcode from ccd9cart c join ccd9products p on c.prodid=p.prodid where c.sessionid='".session_id()."' and c.status='0'";
$result = $mysqli->query($sqllist);
$num_rows = mysqli_num_rows($result); $cnt=0; $tot=0; $ids=''; $contentidscode='';
if($num_rows>0){
	while($row=$result->fetch_array()){ $cnt++;
		$tot=$tot+$row["finalprice"];
		$ids=($ids!='' ? $ids.", '".$row["prodcode"]."'" : "'".$row["prodcode"]."'"); 
		$contentidscode=($contentidscode!="" ? $contentidscode.",".'"'.$row['prodcode'].'"' : '"'.$row['prodcode'].'"');
	}
}

}//------------------------------------------

$rsdata= query_first("select orderid, addressid, billing_addressid, paymentid, shippingid, ordterms, discountamt, disccode, shippingamt, ordtotal, billing_country, comments from ccd9orders where compid='".$_SESSION['compid']."' and status='0'");
if ($rsdata['orderid']>0){
	$orderid = $rsdata['orderid'];
	$addressid = $rsdata['addressid'];
	$baddressid = $rsdata['billing_addressid'];
	$paymentid = $rsdata['paymentid'];
	$shippingid = $rsdata['shippingid'];
	$discountamt = $rsdata['discountamt'];
	$shippingamt = $rsdata['shippingamt'];
	$disccode = $rsdata['disccode'];
	$ordterms = $rsdata['ordterms'];
	$ordtotal = $rsdata['ordtotal'];
	$shippingcountry = $rsdata['country'];
	$ordcomments = dbval($rsdata['comments']);
}
//echo $orderid.'-'.$addressid;
$listadd=""; $listbadd=""; $x=0;
$result = $mysqli->query("select concat(a.username,' ',a.lastname) as uname, a.addressid, a.address, a.zipcode, a.city, c.addressid as caddressid, c.phone, a.state, a.countryid from ccd9address a left join ccd9company c on a.addressid=c.addressid where a.compid='".$_SESSION['compid']."' and a.countryid>0 order by a.addressid desc");
$num_rows = mysqli_num_rows($result);
while($res=$result->fetch_array()){ $x++;
	if($res['phone']!='')$phone = $res['phone'];
	$addchk =""; $addbchk = "";
	if( $addressid>0){
		if($res['addressid']==$addressid && $addressid>0){
			$addchk = " checked";
		}
	}else if($x==1){
		$addchk = " checked";
	}
	if($res['addressid']==$baddressid && $baddressid>0){
		//$addbchk = " checked";
	}
	//if($res['addressid']!=$baddressid){
		$listadd = $listadd." <label for='oldaddress".$x."'><input type='radio' class='rdb-oldaddress' id='oldaddress".$x."' name='addressid' value='".$res['addressid']."' ".$addchk.">&nbsp;".$res['uname'].", ".$res['address'].($res['address1']!='' ? ", ".$res['address1'].", " : ", ").$res['city'].", ".$res['zipcode'].", ".$res['state'].", ".getcountry($res['countryid'])." [ <a href=''>Edit address</a> ]</label><br>";

	//}else 
		
	if($res['addressid']==$baddressid){
		$listbadd = $listbadd." <label for='oldaddress'><input type='radio' class='rdb-oldaddress' id='oldbaddress' name='baddressid' value='".$res['addressid']."' checked>&nbsp;".$res['uname'].", ".$res['address'].($res['address1']!='' ? ", ".$res['address1'].", " : ", ").$res['city'].", ".$res['zipcode'].", ".$res['state'].", ".getcountry($res['countryid'])." [ <a href=''>Edit address</a> ]</label><br>";
	}
}
?>
<!--Body Container-->
<div id="page-content">   
	<!--Collection Banner-->
	<div class="collection-header">
		<div class="collection-hero">
			<div class="collection-hero__image"></div>
			<div class="collection-hero__title-wrapper container">
				<h1 class="collection-hero__title">Checkout</h1>
				<div class="breadcrumbs text-uppercase mt-1 mt-lg-2"><a href="/" title="Back to the home page">Home</a><span>|</span><span class="fw-bold">Checkout</span></div>
			</div>
		</div>
	</div>
	<!--End Collection Banner-->

	<!--Main Content-->
	<div class="container">
		
			<div class="row">
				<div class="col-md-12 col-lg-6">
					<?php if($_GET['errmsg']!=''){?>
					<div class="alert alert-success py-2 rounded-1 alert-dismissible fade show cart-alert" role="alert">
						<i class="align-middle icon an an-user-expand icon-large me-2"></i><?php echo dbval($_GET['errmsg'])?>
						<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
					</div>
					<?php }?>
					<form class="checkout-form" method="post" action="order?do=billing">
					<input type="hidden" name="orderid" value="<?php echo $orderid?>">
					<h2 class="fs-6">DELIVERY ADDRESS</h2>
					<div class="card card--grey">
						<div class="card-body customCheckbox clearfix">
						<?php echo $listadd?>
						</div>
					</div>
					<div class="card card--grey">
						<div class="card-body customCheckbox clearfix">
							<!-- <input id="formcheckoutCheckbox2" name="checkbox2" type="checkbox">  -->
							<input type='radio' name='addressid' id="btn-newaddress" value='new'>
							<label for='btn-newaddress'>&nbsp;I want to enter a new Delivery address</label>
						</div>
						<div class="card-body d-none"  id="div-newaddress">
							<!-- <h2 class="fs-6">SHIPPING ADDRESS</h2>
							<p><a class="text-decoration-underline" href="login.html">Login</a> or <a class="text-decoration-underline" href="register.html">Register</a> for faster payment.</p> -->
							<div class="row mt-2">
								<div class="col-sm-6"><label class="text-uppercase d-none">First Name:</label>
									<div class="form-group"><input type="text" name="username" id="username" placeholder="First Name" class="form-control" maxlength="60"></div>
								</div>
								<div class="col-sm-6"><label class="text-uppercase d-none">Last Name:</label>
									<div class="form-group"><input type="text" name="lastname" id="lastname" placeholder="Last Name" class="form-control" maxlength="60"></div>
								</div>
							</div>
							<label class="text-uppercase d-none">Address 1:</label>
							<div class="form-group"><input type="text" name="address" id="address" placeholder="Address 1" class="form-control" maxlength="60"></div>
							<label class="text-uppercase d-none">Address 2:</label>
							<div class="form-group"><input type="text" name="address1" id="address1" placeholder="Address 2" class="form-control" maxlength="60"></div>
							<div class="row">
								<div class="col-sm-6"><label class="text-uppercase d-none">City:</label>
									<div class="form-group"><input type="text" name="city" id="city" placeholder="City" class="form-control" maxlength="40"></div>
								</div>
								<div class="col-sm-6"><label class="text-uppercase d-none">Zip/postal code:</label>
									<div class="form-group"><input type="text" name="zipcode" id="zipcode" placeholder="Zip/postal code" class="form-control" maxlength="6"></div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-6"><label class="text-uppercase d-none">State:</label>
									<div class="form-group select-wrapper">
										<select name="state" id="state">
											<?php echo getstatemenu()?>
										</select>
									</div>
								</div>
								<div class="col-sm-6"><label class="text-uppercase d-none">Country:</label>
									<div class="form-group">
										<select name="country" id="country" data-default="99">
											<?php echo getcountrymenu($countryid)?>
										</select>
									</div>
								</div>
							</div>
							<!-- <div class="order-button-payment mt-2 clearfix"><button type="submit" class="cartCheckout fs-6 btn btn-lg rounded w-100 fw-200 text-white">Submit</button></div> -->
						</div>
					</div>
					<h2 class="fs-6">BILLING ADDRESS</h2>
					<?php if($baddressid>0 && $baddressid!=$addressid){?>
					<div class="card card--grey">
						<div class="card-body customCheckbox clearfix">
						<?php echo $listbadd?>
						</div>
					</div>
					<?php }else{?>
					<div class="card card--grey">
						<div class="card-body customCheckbox clearfix">
						<input name='baddressid' class="rdb-newaddress" value="same" type="radio" <?php echo ( ($baddressid>0 && $baddressid==$addressid) || $baddressid==0 ? 'checked' : '' )?>>&nbsp;Billing address is same as Delivery address
						</div>
					</div>
					<?php }?>
					<div class="card card--grey">
							<div class="card-body customCheckbox clearfix">
							<input name='baddressid' id="btn-newbilladdress" value="new" type="radio">
							<label for='btn-newbilladdress'>&nbsp;I want to enter a new Billing address</label>
							</div>
						<div class="card-body d-none"  id="div-newbilladdress">
							<div class="row mt-2">
								<div class="col-sm-6"><label class="text-uppercase d-none">First Name:</label>
									<div class="form-group"><input type="text" name="billing_username" id="billing_username" placeholder="First Name" class="form-control" maxlength="60"></div>
								</div>
								<div class="col-sm-6"><label class="text-uppercase d-none">Last Name:</label>
									<div class="form-group"><input type="text" name="billing_lastname" id="billing_lastname" placeholder="Last Name" class="form-control" maxlength="60"></div>
								</div>
							</div>
							<label class="text-uppercase d-none">Address 1:</label>
							<div class="form-group"><input type="text" name="billing_address" id="billing_address" placeholder="Address 1" class="form-control" maxlength="60"></div>
							<label class="text-uppercase d-none">Address 2:</label>
							<div class="form-group"><input type="text" name="billing_address1" placeholder="Address 2" class="form-control" maxlength="60"></div>
							<div class="row">
								<div class="col-sm-6"><label class="text-uppercase d-none">City:</label>
									<div class="form-group"><input type="text" name="billing_city" id="billing_city" placeholder="City" class="form-control" maxlength="40"></div>
								</div>
								<div class="col-sm-6"><label class="text-uppercase d-none">Zip/postal code:</label>
									<div class="form-group"><input type="text" name="billing_zipcode" id="billing_zipcode" placeholder="Zip/postal code" class="form-control" maxlength="6"></div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-6"><label class="text-uppercase d-none">State:</label>
									<div class="form-group select-wrapper">
										<selec name="billing_state" id="billing_state" data-default="">
											<?php echo getstatemenu()?>
										</select>
									</div>
								</div>
								<div class="col-sm-6"><label class="text-uppercase d-none">Country:</label>
									<div class="form-group">
										<select name="billing_country" id="billing_country" data-default="99">
											<?php echo getcountrymenu($countryid)?>
										</select>
									</div>
								</div>
							</div>

						</div>
					</div>
					<div class=" col-md-12 col-lg-12 mt-2"></div>
					<div class=" col-md-12 col-lg-12 mt-2  ">
					<a href="shopping-cart" class="btn btn--small d-inline-flex align-items-center rounded cart-continue ml-2"><i class="me-1 icon an an-sync-ar d-none"></i>Return to Cart</a>&nbsp;
					<button type="submit" name="update"  onclick="validatecart('shopping-cart')" class="btn btn--small d-inline-flex align-items-center rounded cart-continue ml-2"><i class="me-1 icon an an-sync-ar d-none"></i>Use this Address</button></div>
					</form>

				</div>

				<div class="col-md-12 col-lg-6 mt-2 mt-lg-0">
					<!-- <form class="checkout-form" method="post" action="order?do=payment"> -->
					<form method="post" name="redirect" action="https://secure.ccavenue.com/transaction/transaction.do?command=initiateTransaction"> 
					<input type="hidden" name="orderid" value="<?php echo $orderid?>">
					<input type="hidden" name="addressid" value="<?php echo $addressid?>">
					<input type="hidden" name="baddressid" value="<?php echo $baddressid?>">
					<input type="hidden" name="paymentid" value="1">
					<h2 class="title fs-6">ORDER SUMMARY</h2>
					<div class="table-responsive order-table style1"> 
						<table class="table table-bordered align-middle table-hover text-center mb-1">
							<thead>
								<tr>
									<th>Product</th>
									<th class="text-start">Name</th>
									<th>Price</th>
									<th>Qty</th>
									<th>Subtotal</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$shippingamt=0;$tot=0;$cartjscode='';$checkoutjscode=""; $gcincart=0;
								while($row=$resultcart->fetch_array()){ $n++;
								$url=getprodurl($row['produrl'], $row['caturl'])."&type1=".$row['prodmeas']."&type2=".$row['prodsize']."&type3=".$row['prodcolor'];
								$tot=$tot+($row['finalprice']*$row['prodqty']);
								$showcur=showcursymb($row['prodcur']);
								list($prodphoto1, $prodphoto2) = getprodphotos(trim($row['photo']), trim($row['images']));
								?>
								<tr>
									<td class="thumbImg"><a href="<?php echo $url?>" class="thumb d-inline-block"><img class="cart__image" src="<?php echo $prodphoto1?>" alt="<?php echo dbval($row['prodname'])?>" width="80" /></a></td>
									<td class="text-start">
										<a href="<?php echo $url?>"><?php echo dbval($row['prodname'])?></a>
										<div class="cart__meta-text">
											<?php echo ($row['vprodmeas']!='NA' ? '<b>Fit</b>: '.$row['vprodmeas'] : '' ).(trim($row['vprodcolor'])!='NA' ? ' <b>Color</b>: '.$row['vprodcolor'] : '' ).(trim($row['prodsize'])!='NA' ? ' <b>Size</b>: '.trim($row['prodsize']) : '').(trim($row['comments'])!='' ? '<br>'.trim($row['comments']) : '');?>
										</div>
									</td>
									<td><?php echo $showcur.number_format($row['finalprice'])?></td>
									<td><?php echo $row['prodqty']?></td>
									<td class="fw-500"><?php echo $showcur.number_format(($row['finalprice']*$row['prodqty'])+ ($row['shipprice']*$row['prodqty']))?></td>
								</tr>
								<?php }
								
								/*
								if(($tot-$discountamt)>=5000 && $_SESSION['myCUR']!="US $")$shippingamt=0;
								if(($tot-$discountamt)<5000 && $_SESSION['myCUR']!="US $")$shippingamt=300;
								if($gcincart==1 && $n==1)$shippingamt=0;

								if($freediscship==1 && $disccode!='FREESHIP') {
									$shippingamt=0;
								}else if($freediscship==1 && $disccode=='FREESHIP') {
									if($tot>500 && $_SESSION['myCUR']=="US $"){
										$shippingamt=0;
									}
								}*/
								$shippingamt=0;

								?>
							</tbody>
							<tfoot class="font-weight-600">
								<tr>
									<td colspan="4" class="text-end fw-bolder">Subtotal </td>
									<td class="fw-bolder"><?php echo $showcur.number_format($tot)?></td>
								</tr>
								<?php if($discountamt>0){?>
								<tr>
									<td colspan="4" class="text-end fw-bolder">Discount </td>
									<td class="fw-bolder"><?php echo $showcur.number_format($discountamt)?></td>
								</tr>
								<?php } 
								if($shippingamt>0){?>
								<tr>
									<td colspan="4" class="text-end fw-bolder">Shipping </td>
									<td class="fw-bolder"><?php echo $showcur.number_format($shippingamt)?></td>
								</tr>
								<?php } 
								$ordtotal=$tot+$shippingamt-$discountamt;
								?>
								<tr>
									<td colspan="4" class="text-end fw-bolder">Total</td>
									<td class="fw-bolder"><?php echo $showcur.number_format($ordtotal)?></td>
								</tr>
							</tfoot>
						</table>
					</div>

					<?php if($orderid>0 && $addressid>0){
					

									$amount =  ($ordtotal); //1;////4; //your script should substitute the amount in the quotes provided here
									$order_id = getordno($orderid) ;//your script should substitute the order description in the quotes provided here

									if(($orderid>0)&&($orderstatus==0) && ($tot>0)){

									$merchant_id = "2784771" ;//This id(also User Id)  available at "Generate Working Key" of "Settings & Options" 
									$Redirect_Url = $serverurl."ccavResponse.php" ;

									$tot=$ordtotal;//50; 
									require("Crypto.php");
									$merchant_data='';
									$working_key='2D381A5515E2A5885980361E70EE5AB2';//Shared by CCAVENUES
									$access_code='AVEL02KH18BW98LEWB';//Shared by CCAVENUES

									$merchant_data.='tid='.time().'&';
									$merchant_data.='merchant_id='.$merchant_id.'&';
									$merchant_data.='order_id='.$order_id.'&';
									$merchant_data.='amount='.$amount.'&';//
									$merchant_data.='currency=INR'.'&';
									$merchant_data.='redirect_url='.$Redirect_Url.'&';
									$merchant_data.='cancel_url='.$Redirect_Url.'&';
									$merchant_data.='language=EN'.'&';
									$merchant_data.='billing_name='.addslashes($billing_username.' '.$billing_lastname).'&';
									$merchant_data.='billing_address='.addslashes($billing_address_1.' '.$billing_address_2).'&';
									$merchant_data.='billing_city='.addslashes($billing_city).'&';
									$merchant_data.='billing_state='.addslashes($billing_state).'&';
									$merchant_data.='billing_zip='.addslashes($billing_zipcode).'&';
									$merchant_data.='billing_country=India'.'&';
									$merchant_data.='billing_tel='.addslashes($billing_phone).'&';
									$merchant_data.='billing_email='.$billing_email.'&';
									$merchant_data.='delivery_name='.addslashes($shipping_username.' '.$shipping_lastname).'&';
									$merchant_data.='delivery_address='.addslashes($shipping_address_1.' '.$shipping_address_2).'&';
									$merchant_data.='delivery_city='.addslashes($shipping_city).'&';
									$merchant_data.='delivery_state='.addslashes($shipping_state).'&';
									$merchant_data.='delivery_zip='.addslashes($shipping_zipcode).'&';
									$merchant_data.='delivery_country=India'.'&';
									$merchant_data.='delivery_tel='.addslashes($shipping_phone).'&';
									$merchant_data.='merchant_param1='.$orderid.'&';
									$merchant_data.='merchant_param2='.$_SESSION['compid'].'&';
									$merchant_data.='merchant_param3='.session_id();

									$encrypted_data=encrypt($merchant_data,$working_key);
									echo "<input type=hidden name=encRequest value=$encrypted_data>";
									echo "<input type=hidden name=access_code value=$access_code>";
									}

															
					?>
					<!-- <div class="card card--grey mt-2">
						<div class="card-body">
							<h2 class="fs-6">Order Comment</h2>
							<label class="text-uppercase d-none">Write a comment here:</label> 
							<textarea class="form-control textarea--height-200" name="comments" rows="5" placeholder="Write a comment here"><?php echo $ordcomments?></textarea>
						</div>
					</div> -->
					<div class="customCheckbox ordterms">
						<input type="checkbox" value="1" id="ordterms" name="ordterms" checked required>
						<label for="ordterms">By clicking on this check box you have agreed that you have read, understood and will abide by the above mentioned ordering policies that are explicit and non negotiable.</label>
					</div>
					<div class="order-button-payment mt-2 clearfix"><button type="submit" class="cartCheckout fs-6 btn btn-lg rounded w-100 fw-600 text-white">Cotinue	To Payment</button></div>
					<div class="paymnet-img text-center"><img src="assets/images/payment-img.jpg" alt="Payment"></div>
					</form>
					<?php }?>
				</div>
			</div>
		</form>
	</div>
	<!--End Main Content-->
</div>
<!--End Body Container-->