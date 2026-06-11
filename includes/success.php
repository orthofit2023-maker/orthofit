<?php 
$orderid=trim($_GET['id']);
include("ordcart.php");
?>
<!--Main Content-->
<div class="container-fluid">
	<div class="checkout-success-content py-2">
		<!--Order Card-->
		<div class="row">
			<div class="col-12 col-sm-12 col-md-12 col-lg-12">
				<div class="checkout-scard card border-0 rounded">
					<div class="card-body text-center">
						<p class="card-icon"><i class="icon an an-shield-check fs-1"></i></p>
						<h4 class="card-title">Thank you for your order!</h4>
						<p class="card-text mb-1">You will receive an order confirmation email with details of your order and a link to track its progress.</p>
						<p class="card-text mb-1">All necessary information about the delivery, we sent to your email</p>
						<p class="card-text text-order badge bg-success my-3">Your order # is: <b><?php echo $ordref; //getordno($_GET['id']);?></b></p>
					</div>
				</div>
			</div>
		</div>
		<!--End Order Card-->
		<!--Order Summary-->
		<div class="row">
			<div class="col-12 col-sm-12 col-md-12 col-lg-6">
				<div class="checkout-item-ordered">
					<h2>Order Summary</h2>
					<?php 
					$orderid=trim($_GET['id']);
					include("ordcart.php");
					echo $ordtxt;			
					?>
					
				</div>
			</div>
			<div class="col-12 col-sm-12 col-md-12 col-lg-6">
				<h2>&nbsp;</h2>
				<div class="ship-info-details shipping-method-details">
					<div class="row g-0">
						<div class="col-12 col-sm-6 col-md-6 col-lg-6">
							<div class="shipping-details mb-4 mb-sm-0 clearfix">
								<h3>Shipping Address</h3>
								<p><?php echo trim($shipping_username).' '.trim($shipping_lastname).'<br/>'.
								trim($shipping_address_1).' '.trim($shipping_address_2).'<br/>'.trim($shipping_city).', '.trim($shipping_zipcode).'<br/> '.trim($shipping_state).', '.getcountry($shipping_country).'
								<br/><b>Phone:</b> '.trim($shipping_phone).'<br/><b>eMail</b>: '.trim($shipping_email)?></p>
							</div>
						</div>
						<div class="col-12 col-sm-6 col-md-6 col-lg-6">
							<div class="billing-details clearfix">
								<h3>Billing Address</h3>
								<p><?php echo trim($billing_username).' '.trim($billing_lastname).'<br/>'.
								trim($billing_address_1).' '.trim($billing_address_2).'<br/>'.trim($billing_city).', '.trim($billing_zipcode).'<br/> '.trim($billing_state).', '.getcountry($billing_country).'<br/><b>Phone:</b> '.trim($billing_phone)?></p>
							</div>
						</div>
					</div>
				</div>

				<div class="d-flex-wrap w-100 mt-4 text-center">
					<a href="index.html" class="d-inline-flex align-items-center btn btn-outline-primary rounded me-2 mb-2 me-sm-3"><i class="me-2 icon an an-angle-left-r"></i>Continue Shopping</a>
				</div>
			</div>
		</div>
		<!--End Order Summary-->
	</div>
</div>
<!--End Main Content-->
</div>
<!--End Body Container-->
