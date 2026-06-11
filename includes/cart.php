<?php
if($_SESSION['compid']>0){
//----------------check product current status and update the cart----------------------
	$mysqli->query("delete from ccd9cart where compid='".$_SESSION['compid']."' and status='0' and prodid in (select prodid from ccd9products where prodstatus!='1') ");

	$sqlord="select prodid, cartid from ccd9cart where compid='".$_SESSION['compid']."' and status='0'";
	$resultord = $mysqli->query($sqlord);
	while($roword=$resultord->fetch_array()){
		$prodid=$roword['prodid'];
		$row= query_first("select p.*, IF(CURDATE() between p.discfrdate and p.disctodate, '1', '0') as isdiscount from ccd9products p where p.prodid='$prodid'");

		$offerprice=0; $isoffer=0;
		if($_SESSION['myCUR']=="US $"){
			$finalprice=dbval($row['usdprice']);
			$shipprice=dbval($row['shipusd']);
			$offerprice=dbval($row['offerusd']);
		}else{	
			$finalprice=dbval($row['prodprice']);
			$shipprice=dbval($row['shipprod']);
			$offerprice=dbval($row['offerprod']);
		}
		if($row['isdiscount']==1 && $row['proddisc']>0){
			$offerprice=$finalprice-round($finalprice*$row['proddisc']/100,0);
			$isoffer=1;
		}
		if($offerprice>0 || $isoffer==1)$finalprice=$offerprice;

		$sql="update ccd9cart set prodname='".$row['prodname']."', prodprice='".$row['prodprice']."', usdprice='".$row['usdprice']."', poundprice='".$row['poundprice']."', europrice='".$row['europrice']."', prodcare='".$row['prodcare']."', noship='".$row['noship']."', prodbox='".$row['prodbox']."', shiptime='".$row['shiptime']."', prodwgt='".$row['prodwgt']."', prodpack='".$row['prodpack']."', shipprod='".$row['shipprod']."', shipusd='".$row['shipusd']."', freeship='".$row['freeship']."', proddisc='".$row['proddisc']."', discfrdate='".$row['discfrdate']."', disctodate='".$row['disctodate']."', offerprod='".$row['offerprod']."', offerusd='".$row['offerusd']."', offerfrdate='".$row['offerfrdate']."', offertodate='".$row['offertodate']."', prodcur='".($_SESSION['myCUR']!='US $' ? 'INR' : $_SESSION['myCUR'])."', finalprice='$finalprice', shipprice='$shipprice' where cartid='".$roword['cartid']."'";
		$mysqli->query($sql);
	}
}
//----------------end check product current status and update the cart----------------------

$freediscship=0;
if($_SESSION['compid']>0){
	$row= query_first("select orderid, discid, disccode, discountamt, ordtax, comments from ccd9orders where status='0' and compid='".$_SESSION['compid']."'");
	if ($row['orderid']>0){
		$orderid = $row['orderid'];
		$discount = $row['discountamt'];
		$disccode = $row['disccode'];
		$ordcomments = dbval($row['comments']);

		if($row['discid']>0){
			$row= query_first("select disctype from ccd9discounts where discid='".$row['discid']."' ");
			if($row['disctype']==3){
				$freediscship=1;
			}

		}
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
				<h1 class="collection-hero__title">Shopping Cart</h1>
				<div class="breadcrumbs text-uppercase mt-1 mt-lg-2"><a href="/" title="Back to the home page">Home</a><span>|</span><span class="fw-bold">Shopping Cart</span></div>
			</div>
		</div>
	</div>
	<!--End Collection Banner-->

	<!--Main Content-->
	<div class="container-fluid">
		<?php if($numcart==0){?>
		<!--Category Empty-->
			<div class="row">
				<div class="col-12 col-sm-12 col-md-12 col-lg-12 text-center pt-5 pb-5">
					<p><img src="assets/images/sad-icon.png" alt="" /></p>
					<h2 class="mt-4"><strong>SORRY,</strong> Your shopping cart is empty!</h2>
					<p class="mb-3 pb-1">You have no items in your shopping cart.</p>
					<p><a href="/" class="btn btn-outline-primary rounded mb-2 me-2">GO Back</a><a href="search" class="btn rounded mb-2 text-capitalize">Continue shopping</a></p>
				</div>
			</div>
			<!--End Category Empty-->
		<?php }else{?>
		<!--Cart Page-->
		<form action="shopping-cart" name="frmcart" method="post" class="cart style2">
		<div class="row">
			<div class="col-12 col-sm-12 col-md-12 col-lg-8 main-col">
				<?php if($_GET['errmsg']!=''){?>
				<div class="alert alert-success py-2 rounded-1 alert-dismissible fade show cart-alert" role="alert">
					<i class="align-middle icon an an-sq-bag icon-large me-2"></i><?php echo dbval($_GET['errmsg'])?>
					<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
				</div>
				<?php }?>
				
				
					<input type="hidden" name="retuto" value='order'>
					<table class="align-middle">
						<thead class="cart__row cart__header small--hide">
							<tr>
								<th class="action">&nbsp;</th>
								<th colspan="2" class="text-start">Product</th>
								<th class="text-center">Price</th>
								<th class="text-center">Quantity</th>
								<th class="text-center">Total</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$shiptot=0;$tot=0;$cartjscode='';$checkoutjscode=""; $gcincart=0;

							//$sqlcartlist="select c.*, ph.photo, p.produrl, p.prodcode, ct.typeid as catid, ct.typename as catname, ct.typevalue as caturl, t1.typename as vprodmeas, t2.typename as vprodsize, t3.typename as vprodcolor  from ccd9cart c join ccd9products p on p.prodid=c.prodid left join ccd9prod2cat pt on pt.prodid=p.prodid left join ccd9types ct on pt.catid=ct.typeid and ct.opt='2' left join ccd9types t1 on c.prodmeas=t1.typeid and t1.opt='3' left  join ccd9types t2 on c.prodsize=t2.typeid and t2.opt='4'  left  join ccd9types t3 on c.prodcolor=t3.typeid and t3.opt='7' left  join ccd9prod2type3 pt3 on pt3.prodid=p.prodid and c.prodcolor=pt3.typeid left join ccd9prodphotos ph on ph.prodid=c.prodid and ph.type1=c.prodmeas and ph.type3=c.prodcolor where ".($_SESSION['compid']>0 ? " compid='".$_SESSION['compid']."'" : " c.sessionid='".session_id()."' and c.compid='0'")." and c.status='0'  group by c.cartid order by c.cartid;";
							//echo $sqllist;
							//$resultcart = $mysqli->query($sqlcartlist);
							//$numcart = mysqli_num_rows($resultcart);

							//------sql code from header -- '$sqlcartlist'-------------------------
							
							while($row=$resultcart->fetch_array()){ $n++;
								$url=getprodurl($row['produrl'], $row['caturl'])."&type1=".$row['prodmeas']."&type2=".$row['prodsize']."&type3=".$row['prodcolor'];
								$tot=$tot+($row['finalprice']*$row['prodqty']);
								$showcur=showcursymb($row['prodcur']);
								//$shiptot=$shiptot+($row['shipprice']*$row['prodqty']);
								//$heelreq=trim($row['heelreq']);
								//$height=dbval($row['height']);
								//$heelheight=dbval($row['heelheight']);
								$discemail=dbval($row['discemail']);
								//if($height!=''){
								//	list($heightft,$heightin)=explode('.',$height);
								//}

								list($prodphoto1, $prodphoto2) = getprodphotos(trim($row['photo']), trim($row['images']));
								

								/*$webpimg = trim($row['produrl']).'-'.trim($row['prodcolor']).'-'.strtolower(chr(65)).'.webp';
								
								$prodphoto=trim($arrphoto[0]);
								$photoext=trim(substr($prodphoto,strrpos($prodphoto,".")+1));
								$prodimg=trim($row['produrl']).'.'.$photoext;
								if(!file_exists($imgpath.$prodimg)){
									$photocontent = file_get_contents($prodphoto);
									file_put_contents($imgpath.$prodimg, $photocontent);
								}
								$webpimg=trim($row['produrl']).'.webp';

								if(!file_exists($imgpath.$webpimg)){
									$image = imagecreatefromstring(file_get_contents($imgpath.$prodimg));
									ob_start();
									imagejpeg($image,NULL,100);
									$cont = ob_get_contents();
									ob_end_clean();
									imagedestroy($image);
									$content = imagecreatefromstring($cont);
									$output = $imgpath.$webpimg;
									imagewebp($content,$output);
									imagedestroy($content);
								}*/
							?>
							<input type="hidden" name="cartid<?php echo $n;?>" value="<?php echo $row['cartid'];?>">
							<tr class="cart__row border-bottom line1 cart-flex border-top">
								<td class="cart-delete text-center small--hide"><a href="Javascript:delprod('<?php echo $row['cartid']?>', '<?php echo $row["prodcode"]?>', '<?php echo dbval(str_replace("'","",$row['prodname']))?>', '<?php echo $row["finalprice"]?>', '<?php echo dbval($row["prodcolor"])?>', '<?php echo $row["catid"]?>', '<?php echo inpval($row["catname"])?>')" class="btn btn--secondary cart__remove remove-icon position-static" data-bs-toggle="tooltip" data-bs-placement="top" title="Remove item"><i class="icon an an-times-r"></i></a></td>
								<td class="cart__image-wrapper cart-flex-item">
									<a href="<?php echo $url?>"><img class="cart__image blur-up lazyload" data-src="<?php echo $prodphoto1?>" src="<?php echo $prodphoto1?>" alt="<?php echo dbval($row['prodname'])?>" width="80" /></a>
								</td>
								<td class="cart__meta small--text-left cart-flex-item">
									<div class="list-view-item__title">
										<a href="<?php echo $url?>"><?php echo dbval($row['prodname'])?></a>
									</div>
									<div class="cart__meta-text">
										<?php echo ($row['vprodmeas']!='NA' ? '<b>Fit</b>: '.$row['vprodmeas'] : '' ).(trim($row['vprodcolor'])!='NA' ? ' <b>Color</b>: '.$row['vprodcolor'] : '' ).(trim($row['prodsize'])!='NA' ? ' <b>Size</b>: '.trim($row['prodsize']) : '');
										
										$comments= getsizebox($row['prodmeas'],$row['prodsize'],$row['catid']);
										echo $comments;
										?>
										<input type="hidden" name="prodmeas<?php echo $n;?>" value="<?php echo $row['prodmeas']?>">
										<input type="hidden" name="prodsize<?php echo $n;?>" value="<?php echo $row['prodsize']?>">
										<input type="hidden" name="catid<?php echo $n;?>" value="<?php echo $row['catid']?>">
									</div>
									<div class="cart-price d-md-none">
										<span class="money fw-500"><?php echo $showcur.number_format($row['finalprice'])?></span>
									</div>
								</td>
								<td class="cart__price-wrapper cart-flex-item text-center small--hide">
									<span class="money"><?php echo $showcur.number_format($row['finalprice'])?></span>
								</td>
								<td class="cart__update-wrapper cart-flex-item text-end text-md-center">
									<div class="cart__qty d-flex justify-content-end justify-content-md-center">
										<div class="qtyField">
											<a class="qtyBtn minus" href="javascript:void(0);"><i class="icon an an-minus-r"></i></a>
											<input class="cart__qty-input qty" type="text" name="qty<?php echo $n;?>" value="<?php echo $row['prodqty']?>" pattern="[0-9]*" />
											<a class="qtyBtn plus" href="javascript:void(0);"><i class="icon an an-plus-r"></i></a>
										</div>
									</div>
									<a href="Javascript:delprod('<?php echo $row['cartid']?>', '<?php echo $row["prodcode"]?>', '<?php echo dbval(str_replace("'","",$row['prodname']))?>', '<?php echo $row["finalprice"]?>', '<?php echo dbval($row["prodcolor"])?>', '<?php echo $row["catid"]?>', '<?php echo inpval($row["catname"])?>')" title="Remove" class="removeMb d-md-none d-inline-block text-decoration-underline mt-2 me-3">Remove</a>
								</td>
								<td class="cart-price cart-flex-item text-center small--hide">
									<span class="money fw-500"><?php echo $showcur.number_format(($row['finalprice']*$row['prodqty'])+ ($row['shipprice']*$row['prodqty']))?></span>
								</td>
							</tr>
							<?php } 
							$shiptot=0;
							?>
							
						</tbody>
						<tfoot>
							<tr>
								<td colspan="3" class="text-start pt-3"><a href="/search" class="btn btn--link d-inline-flex align-items-center btn--small p-0 cart-continue"><i class="me-1 icon an an-angle-left-l"></i><span class="text-decoration-underline">Continue shopping</span></a></td>
								<td colspan="3" class="text-end pt-3">
									<!-- <button type="submit" name="clear" class="btn btn--link d-inline-flex align-items-center btn--small small--hide"><i class="me-1 icon an an-times-r"></i><span class="ms-1 text-decoration-underline">Clear Shoping Cart</span></button> -->
									<input type="hidden" value="<?php echo $n;?>" name="totn">
									<button type="button" name="update"  onclick="validatecart('shopping-cart')" class="btn btn--small d-inline-flex align-items-center rounded cart-continue ml-2"><i class="me-1 icon an an-sync-ar d-none"></i>Update Cart</button>
								</td>
							</tr>
						</tfoot>
					</table> 
				
				<!-- <div class="currencymsg">We processes all orders in USD. While the content of your cart is currently displayed in USD, the checkout will use USD at the most current exchange rate.</div> -->

				<!-- <div class="row my-3">
					<div class="col-12 col-sm-12 col-md-12 col-lg-12 mb-12 cart-col">
						<h5>Discount Codes</h5>
						<form action="#" method="post">
							<div class="form-group">
								<label for="address_zip">Enter your coupon code if you have one.</label>
								<div class="input-group flex-nowrap">
									<input class="input-group__field" type="text" name="coupon" />
									<span class="input-group__btn">
										<input type="button" class="btn rounded-end text-nowrap" value="Apply Coupon" />
									</span>
								</div>
							</div>
						</form>
					</div>
				</div> -->
			</div>

			<div class="col-12 col-sm-12 col-md-12 col-lg-4 cart__footer">
				<div class="cart_info">
					<div id="shipping-calculator" class="mb-4 cart-col">
							<h5>DISCOUNT CODES</h5>
							<div class="form-group">
								<label for="address_zip">Enter your coupon code if you have one.</label>
								<div class="input-group flex-nowrap">
									<input class="input-group__field" type="text" name="disccode" value="<?php echo $disccode?>"/>
									<input type="hidden" name="olddisccode" value="<?php echo $disccode?>">
									<span class="input-group__btn">
										<input type="button" class="btn rounded-end text-nowrap" value="Apply Coupon" onclick="validatecart('shopping-cart')"/>
									</span>
								</div>
							</div>
						
					</div>
					<div class="cart-order_detail cart-col">
						<?php if($tot>0){?>
						<div class="row">
							<span class="col-6 col-sm-6 cart__subtotal-title"><strong>Subtotal</strong></span>
							<span class="col-6 col-sm-6 cart__subtotal-title cart__subtotal text-end"><span class="money"><?php echo $showcur.number_format($tot)?></span></span>
						</div>
						<?php }
						if($discount>0){?>
						<div class="row">
							<span class="col-6 col-sm-6 cart__subtotal-title"><strong>Discount</strong></span>
							<span class="col-6 col-sm-6 cart__subtotal-title cart__subtotal text-end"><span class="money"><?php echo $showcur.number_format($discount)?></span></span>
						</div>
						<?php } 
						if($shiptot>0){?>
						<div class="row">
							<span class="col-6 col-sm-6 cart__subtotal-title"><strong>Shipping</strong></span>
							<span class="col-6 col-sm-6 cart__subtotal-title cart__subtotal text-end"><span class="money"><?php echo $showcur.number_format($shiptot)?></span></span>
						</div>
						<?php }?>
						<div class="row">
							<span class="col-6 col-sm-6 cart__subtotal-title"><strong>Total</strong></span>
							<span class="col-6 col-sm-6 cart__subtotal-title cart__subtotal text-end"><span class="money"><?php echo $showcur.number_format($tot+$shiptot-$discount)?></span></span>
						</div>
						<!-- <p class="cart__shipping m-0">Shipping &amp; taxes calculated at checkout</p>
						<p class="cart__shipping pt-0 m-0 fst-normal freeShipclaim"><i class="me-1 align-middle icon an an-truck-l"></i><b>FREE SHIPPING</b> ELIGIBLE</p>
						<div class="customCheckbox cart_tearm">
							<input type="checkbox" value="allen-vela" id="cart_tearm">
							<label for="cart_tearm">I agree with the terms and conditions</label>
						</div> -->
						<div class="card mt-2">
							<div class="card-body">
								<h5 class="fs-6">Order Comment</h5>
								<label class="text-uppercase d-none">Write a comment here:</label> 
								<textarea class="form-control textarea--height-200" name="comments" rows="5" placeholder="Write a comment here"><?php echo $ordcomments?></textarea>
							</div>
						</div>
						<button type="submit" id="cartCheckout" class="btn btn--small-wide rounded my-4 checkout">Proceed To Checkout</button>
						<div class="paymnet-img text-center"><img src="assets/images/safepayment.jpg" alt="Payment" /></div>
					</div>
				</div>
			</div>
		</div>
		</form>
		<!--End Cart Page-->
		<?php }?>
	</div>
	<!--End Main Content-->
</div>
<!--End Body Container-->

<script>
function validatecart(z){
	document.frmcart.retuto.value=z;
	document.frmcart.submit();
}

function delprod(x, xid, xname, xprice, xcolor, xcatid, xcat){
	var y = confirm("Do you wish to remove this product?");
	if(y){
	/*
		ga('ec:addProduct', {
			'id': xid,
			'name': xname,
			'price': xprice
		});
		ga('ec:setAction', 'remove');
		ga('send', 'event', 'UX', 'click', 'add to cart');  



		dataLayer.push({ ecommerce: null });  
		dataLayer.push({
		  event: "remove_from_cart",
		  ecommerce: {
			currency: "<?php echo ($_SESSION['myCUR']=='US $' ? 'USD' : 'INR')?>",
			value: xprice,
			items: [
			{
			  item_id: xid,
			  item_name: xname,
			  affiliation: "PAYALSINGHAL",
			  index: 0,
			  item_brand: "PAYALSINGHAL",
			  item_category: xcat,
			  item_list_id: "products_"+xcatid,
			  item_list_name: xcat,
			  item_variant: xcolor,
			  price: xprice,
			  quantity: 1
			}
			]
		  }
		});
		*/
		document.location.href="shopping-cart?remprod="+x;
	}
}
</script>