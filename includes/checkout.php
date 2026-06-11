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

$rsdata= query_first("select * from ccd9orders where compid='".$_SESSION['compid']."' and status='0'");
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

	$cartcur=dbval($rsdata['ordcur']);

	$billing_email = $rsdata['email'];
	$billing_city=dbval($rsdata['billing_city']);
	$billing_state=dbval($rsdata['billing_state']);
	$billing_zipcode=dbval($rsdata['billing_zipcode']);
	$billing_address_1=dbval($rsdata['billing_address']);
	$billing_address_2=dbval($rsdata['billing_address1']);
	$billing_username=dbval($rsdata['billing_username']);
	$billing_lastname=dbval($rsdata['billing_lastname']);
	$billing_phone=dbval($rsdata['phone']);
	$billing_country=dbval($rsdata['billing_country']);

	$shipping_email = $rsdata['email'];
	$shipping_city=dbval($rsdata['city']);
	$shipping_state=dbval($rsdata['state']);
	$shipping_zipcode=dbval($rsdata['zipcode']);
	$shipping_address_1=dbval($rsdata['address']);
	$shipping_address_2=dbval($rsdata['address1']);
	$shipping_username=dbval($rsdata['username']);
	$shipping_lastname=dbval($rsdata['lastname']);
	$shipping_phone=dbval($rsdata['shipping_phone']);
	$shipping_country=dbval($rsdata['country']);
	$trackcode=dbval($rsdata['trackcode']);
	$sent2fb=dbval($rsdata['sent2fb']);
	list($dtyr,$mm,$dd)=explode("-",$rsdata['orddate']);

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
		$listadd = $listadd." <label for='oldaddress".$x."'><input type='radio' class='rdb-oldaddress' id='oldaddress".$x."' name='addressid' value='".$res['addressid']."' ".$addchk.">&nbsp;".$res['uname'].", ".$res['address'].($res['address1']!='' ? ", ".$res['address1'].", " : ", ").$res['city'].", ".$res['zipcode'].", ".$res['state'].", ".getcountry($res['countryid'])." [ <a href='myaccount?task=manage-address&id=".$res['addressid']."'>Edit address</a> ]</label><br>";

	//}else 
		
	if($res['addressid']==$baddressid){
		$listbadd = $listbadd." <label for='oldaddress'><input type='radio' class='rdb-oldaddress' id='oldbaddress' name='baddressid' value='".$res['addressid']."' checked>&nbsp;".$res['uname'].", ".$res['address'].($res['address1']!='' ? ", ".$res['address1'].", " : ", ").$res['city'].", ".$res['zipcode'].", ".$res['state'].", ".getcountry($res['countryid'])." [ <a href='myaccount?task=manage-address&id=".$res['addressid']."'>Edit address</a> ]</label><br>";
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
	<div class="container-fluid">
		
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
										<select name="billing_state" id="billing_state" data-default="">
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
					<form method="post" name="redirect" name="frmpay"> 
					<input type="hidden" name="orderid" value="<?php echo $orderid?>">
					<input type="hidden" name="addressid" value="<?php echo $addressid?>">
					<input type="hidden" name="baddressid" value="<?php echo $baddressid?>">
					<input type="hidden" name="paymentid" value="1">
					<h2 class="title fs-6">ORDER SUMMARY</h2>
					<?php 
					include("ordcart.php");
					echo $ordtxt;

					if($orderid>0 && $addressid>0){

								$orderip = $_SERVER['REMOTE_ADDR'];
								$ordref = getordno($orderid).'-'.mktime(); //uniqid('orthofit', true);
								$mysqli->query("update ccd9orders set orderip='$orderip', ordtotal='$ordtotal', discountamt='$discountamt', shippingamt='$shippingamt', ordref='$ordref' where orderid='$orderid' and compid='".$_SESSION["compid"]."'");

								$mysqli->query("update ccd9cart set ordref='$ordref'  where sessionid='".session_id()."' and compid='".$_SESSION["compid"]."' and status='0'");
					

									$amount =  ($ordtotal); //////4; //your script should substitute the amount in the quotes provided here
									//$order_id = getordno($orderid) ;//your script should substitute the order description in the quotes provided here
									//echo $order_id;

									if(($orderid>0)&&($orderstatus==0) && ($tot>0)){

										$returnURL = $serverurl."smartResponse.php";

										require("manage/smartconfig.php");

										$headerValue =  base64_encode($apikey);
										$header = array();
										$header[] = 'Content-type: application/json';
										$header[] = 'Authorization: Basic '.$headerValue;		
										$header[] = 'x-merchantid: '.$merchantid;
										$header[] = 'x-customerid: '.$_SESSION["compid"];		


										$postValues =  array();
										$postValues['order_id'] = $ordref;
										$postValues['amount'] = strval($amount);
										$postValues['customer_id'] = strval($_SESSION['compid']);
										$postValues['customer_email'] = $billing_email;
										$postValues['customer_phone'] = $billing_phone;
										$postValues['payment_page_client_id'] = "hdfcmaster"; //($apistatus=='LIVE' ? $merchantid : "hdfcmaster");
										$postValues['action'] = "paymentPage";
										$postValues['currency'] = "INR";
										$postValues['return_url'] = $returnURL;
										$postValues['description'] = "Complete your payment";
										$postValues['first_name'] = $billing_username;
										$postValues['last_name'] = $billing_lastname;

										//print_r($postValues);

										$postJson = json_encode($postValues);

										$curl = curl_init();
										curl_setopt($curl, CURLOPT_URL, $sessionURL);
										curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
										curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
										curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
										curl_setopt($curl, CURLOPT_SSLVERSION, 6);
										curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
										curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
										curl_setopt($curl, CURLOPT_ENCODING, '');		
										curl_setopt($curl, CURLOPT_TIMEOUT, 60);
										curl_setopt($curl, CURLOPT_POST, 1);
										curl_setopt($curl, CURLOPT_POSTFIELDS, $postJson);
										$response = curl_exec($curl);
										$curlerr = curl_error($curl);

										if ($curlerr != ''){
											// -------------------- return to order page with error msg --------------------
											echo '<h4 style="color:red">Error:'.$curlerr.'</h4>';
											//return false;
										}else {
											$res = json_decode($response);
											//print_r($res);
											//echo '<br><br>--------------------------------';
											//echo $res->status; 
											if($res->status=='NEW'){
												//echo $res->id;
												$payment_link=$res->payment_links->web;
												//echo $payment_link;
												//echo $res->clientAuthToken;
												//echo $res;
												//session_start();

												//$_SESSION['myorderid']=$orderid;

												//echo '<br><br>'.$_SESSION['myorderid'];
											}
										}
									}

															
					?>
					<div class="card card--grey mt-2">
						<div class="card-body">
							<h2 class="fs-6">Order Comment</h2>
							<label for="ordterms"><?php echo $ordcomments?></label>
						</div>
					</div>
					<!-- <div class="alert alert-success py-2 rounded-1 alert-dismissible fade show cart-alert" role="alert">
						<label for="ordterms ">Online payment system is temporarily unavailable.  Kindly contact our office directly on  84549 20321 for further assistance. Our team will be happy to help you complete the process smoothly. We apologize for the inconvenience and appreciate your understanding.</label>
					</div> -->
					<div class="customCheckbox ordterms">
						<input type="checkbox" value="1" id="ordterms" name="ordterms" checked required>
						<label for="ordterms">By clicking on this check box you have agreed that you have read, understood and will abide by the above mentioned ordering policies that are explicit and non negotiable.</label>
					</div>
					<div class="order-button-payment mt-2 clearfix"><button type="button" class="cartCheckout fs-6 btn btn-lg rounded w-100 fw-600 text-white" onclick="document.location.href='<?php echo $payment_link?>'">Cotinue	To Payment</button></div>
					<div class="paymnet-img text-center"><img src="assets/images/payment-img.jpg" alt="Payment"></div>
					</form>
					<?php }?>
				</div>
			</div>
	</div>
	<!--End Main Content-->
</div>
<!--End Body Container-->