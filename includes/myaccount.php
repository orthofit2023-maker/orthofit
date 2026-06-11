<?php


$task = inpval($_GET['task']);
?>
<!--Body Container-->
<div id="page-content">
	<!--Collection Banner-->
	<div class="collection-header">
		<div class="collection-hero">
			<div class="collection-hero__image"></div>
			<div class="collection-hero__title-wrapper container">
				<h1 class="collection-hero__title">My Account</h1>
				<div class="breadcrumbs text-uppercase mt-1 mt-lg-2"><a href="/" title="Back to the home page">Home</a><span>|</span><span class="fw-bold">My Account</span></div>
			</div>
		</div>
	</div>
	<!--End Collection Banner-->

	<!--Container-->
	<div class="container-fluid pt-2">
		<!--Main Content-->
		<div class="dashboard-upper-info">
			<div class="row align-items-center g-0">
				<div class="col-xl-3 col-lg-3 col-sm-6">
					<div class="d-single-info">
						<p class="user-name">Hello <span class="fw-600"><?php echo $_SESSION["name"]?></span></p>
						<p>(not <?php echo $_SESSION["name"]?> <a class="link-underline fw-600" href="logout">Log Out</a>)</p>
					</div>
				</div>
				<div class="col-xl-4 col-lg-4 col-sm-6">
					<div class="d-single-info">
						<p>Need Assistance? Customer service at.</p>
						<p><a href="mailto:support@orthofit.in">support@orthofit.in</a></p>
					</div>
				</div>
				<div class="col-xl-3 col-lg-3 col-sm-6">
					<div class="d-single-info">
						<p>E-mail them at </p>
						<p><a href="mailto:edge@orthofit.in">edge@orthofit.in</a></p>
					</div>
				</div>
				<div class="col-xl-2 col-lg-2 col-sm-6">
					<div class="d-single-info text-lg-center">
						<a class="link-underline fw-600 view-cart" href="shopping-cart"><i class="icon an an-sq-bag me-2"></i>View Cart</a>
					</div>
				</div>
			</div>
		</div>

		<div class="row mb-4 mb-lg-5 pb-lg-5">
			<div class="col-xl-3 col-lg-2 col-md-12 mb-4 mb-lg-0">
				<!-- Nav tabs -->
				<ul class="nav flex-column bg-light h-100 dashboard-list" role="tablist">
					<!-- <li><a class="nav-link" data-bs-toggle="tab" href="#dashboard">Dashboard</a></li> -->
					<li><a class="nav-link <?php echo ($task=='' || $task=='' ? 'active' : '')?>" data-bs-toggle="tab" href="#orders">Orders</a></li>
					<!-- <li><a class="nav-link" data-bs-toggle="tab" href="#orderstracking">Orders tracking</a></li> -->
					<li><a class="nav-link <?php echo ($task=='manage-address' ? 'active' : '')?>" data-bs-toggle="tab" href="#address">Addresses</a></li>
					<li><a class="nav-link <?php echo ($task=='edit-information' ? 'active' : '')?>" data-bs-toggle="tab" href="#account-details">Account details</a></li>
					<li><a class="nav-link <?php echo ($task=='password' ? 'active' : '')?>" data-bs-toggle="tab" href="#account-password">Change password</a></li>
					<li><a class="nav-link <?php echo ($task=='wishlist' ? 'active' : '')?>" data-bs-toggle="tab" href="#wishlist">Wishlist</a></li>
					<li><a class="nav-link" href="logout">logout</a></li>
				</ul>
				<!-- End Nav tabs -->
			</div>

			<div class="col-xl-9 col-lg-10 col-md-12">
				<?php if($_GET['errmsg']!=''){?>
				<div class="alert alert-success py-2 rounded-1 alert-dismissible fade show cart-alert" role="alert">
					<i class="align-middle icon an an-user-expand icon-large me-2"></i><?php echo dbval($_GET['errmsg'])?>
					<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
				</div>
				<?php }?>
				<!-- Tab panes -->
				<div class="tab-content dashboard-content">

					<!-- Orders -->
					<div id="orders" class="product-order tab-pane <?php echo ($task=='' || $task=='' ? 'active' : 'fade')?>">
						<h3>Orders</h3>
						<?php $i=0;
								$result = $mysqli->query("select o.orderid, o.orddate, o.ordtotal, o.ordcur, s.statusname, DATE_FORMAT(o.orddate,'%d/%b/%Y') as regdate from ccd9orders o join ccd9orderstatus s on s.statusid=o.status where o.compid='".$_SESSION['compid']."' and o.status>0 order by o.orderid desc");
								$num_rows = mysqli_num_rows($result);
								if($num_rows>0){
						?>
						<div class="table-responsive order-table">
							<table class="table table-bordered table-hover align-middle mb-0">
								<thead class="alt-font">
									<tr>
										<th>Order</th>
										<th>Date</th>
										<th>Status</th>
										<th>Total</th>
										<th>Actions</th>
									</tr>
								</thead>
								<tbody>
									<?php
										while($row=$result->fetch_array()){ $i++;
										?>
									<tr>
										<td><?php echo getordno($row['orderid'], $row['orddate'])?>&nbsp;<a href="myaccount?ordid=<?php echo $row['orderid']?>"></td>
										<td><?php echo $row['regdate']?></td>
										<td class="text-success"><?php echo $row['statusname']?></td>
										<td><?php echo showcursymb($row['ordcur']).number_format($row['ordtotal'])?></td>
										<td><a class="link-underline view" href="myaccount?ordid=<?php echo $row['orderid']?>">View</a></td>
									</tr>
									<?php 
											if($_GET['ordid']==$row['orderid']){
												$orderid=intval($_GET['ordid']);
												echo '<tr><td colspan="5">';
												include("ordcart.php");
												echo $ordtxt;
												echo '</td></tr>';

												//echo '<tr><td colspan="5"><h3>Comments</h3>'.$comments.'</td></tr>';
												echo '<tr><td colspan="5"><h3>Order Status</h3>'; 
													$sqlstatus="SELECT s.statusname, u.comments, DATE_FORMAT(u.datemodified,'%d/%b/%Y') as regdate, u.sendemail from ccd9orderhistory u left join ccd9orderstatus s on u.statusid=s.statusid where u.orderid='$orderid'  and u.statusid>1";
													$sqlstatus=$sqlstatus." order by u.datemodified desc";
													$resultstatus = $mysqli->query($sqlstatus);
													$num_status = mysqli_num_rows($resultstatus);
													if ($num_status>0){
														while($rowstatus=$resultstatus->fetch_array()){
																echo '<b>'.$rowstatus['regdate'].' | '.$rowstatus['statusname'].'</b>';
																if($rowstatus['sendemail']==1){
																	echo '<br/>'.str_replace("\n","<br/>",$rowstatus['comments']);
																}
																echo '<br/>';
														}
													}
												echo '</td></tr>';

												echo '<tr><td colspan="3" valign="top"><h3>Shipping Address</h3>
												'.trim($shipping_username).' '.trim($shipping_lastname).'<br/>'.
								trim($shipping_address_1).' '.trim($shipping_address_2).'<br/>'.trim($shipping_city).', '.trim($shipping_zipcode).'<br/> '.trim($shipping_state).', '.getcountry($shipping_country).'
								<br/><b>Phone:</b> '.trim($shipping_phone).'<br/><b>eMail</b>: '.trim($shipping_email).'
												
												</td>';

												echo '<td colspan="3" valign="top"><h3>Billing Address</h3>'.trim($billing_username).' '.trim($billing_lastname).'<br/>'.
								trim($billing_address_1).' '.trim($billing_address_2).'<br/>'.trim($billing_city).', '.trim($billing_zipcode).'<br/> '.trim($billing_state).', '.getcountry($billing_country).'<br/><b>Phone:</b> '.trim($billing_phone).'
												
												</td></tr>';
												

											}
										
										} ?>
										
								</tbody>
							</table>
						</div>
						<?php }else{?>
							<div class="row">
								<div class="col-12 col-sm-12 col-md-12 col-lg-12 text-center pt-5 pb-5">
									<p><img src="assets/images/sad-icon.png" alt=""></p>
									<h2 class="mt-4"><strong>SORRY,</strong> Order list is empty!</h2>
									<p><a href="/" class="btn btn-outline-primary rounded mb-2 me-2">GO Back</a><a href="search" class="btn rounded mb-2 text-capitalize">Continue shopping</a></p>
								</div>
							</div>
						<?php }?>
					</div>
					<!-- End Orders -->

					<!-- Address -->
					<div id="address" class="address tab-pane <?php echo ($task=='manage-address' ? 'active' : 'fade')?>">
						<h3>Addresses</h3>
						<p class="xs-fon-13 margin-10px-bottom">The following addresses will be used on the checkout page by default.</p>
						<div class="row">
							<div class="col-12 col-sm-12">
								<?php
								$listadd=""; $listbadd=""; $x=0; $addressid = inpval($_GET['id']);
								$result = $mysqli->query("select concat(a.username,' ',a.lastname) as uname, a.username, a.lastname, a.addressid, a.address, a.address1, a.zipcode, a.city, c.addressid as caddressid, c.phone, a.state, a.countryid from ccd9address a left join ccd9company c on a.addressid=c.addressid where a.compid='".$_SESSION['compid']."' and a.countryid>0 order by a.addressid desc");
								$num_rows = mysqli_num_rows($result);
								while($res=$result->fetch_array()){ $x++;
									if($res['phone']!='')$phone = $res['phone'];

										$listadd = $listadd."<p class='mt-3'><label for='oldaddress".$x."'> [ <a href='myaccount?task=manage-address&id=".$res['addressid']."' class='link-underline view'>Edit</a> ] &nbsp;".$res['uname'].", ".$res['address'].($res['address1']!='' ? ", ".$res['address1'].", " : ", ").$res['city'].", ".$res['zipcode'].", ".$res['state'].", ".getcountry($res['countryid'])."</label></p>";

										if($addressid>0 && $res['addressid']==$addressid){
												$city=dbval($res['city']);
												$state=dbval($res['state']);
												$zipcode=dbval($res['zipcode']);
												$address=dbval($res['address']);
												$address1=dbval($res['address1']);
												$country=dbval($res['countryid']);
												$username=dbval($res['username']);
												$lastname=dbval($res['lastname']);
												$phone=dbval($res['phone']);
										}
								}
								echo $listadd;
								?>
								
							</div>
							<div class="row <?php echo ($addressid>0 ? '' : 'd-none')?>">
								<div id="billing">
                                                    <form class="address-from mt-3" method="post" action="manage-address">
													<input type="hidden" name="addressid" value="<?php echo $addressid?>">
                                                        <fieldset>
                                                            <h2 class="login-title mb-3"><?php echo ($addressid>0 ? 'Edit' : 'Add')?> Address</h2>
                                                            <div class="row">
                                                                <div class="form-group col-md-6 col-lg-6 col-xl-6">
                                                                    <label for="username" class="d-none">First Name <span class="required-f">*</span></label>
                                                                    <input name="username" id="username" placeholder="First Name" value="<?php echo $username?>" type="text" required>
                                                                </div>
                                                                <div class="form-group col-md-6 col-lg-6 col-xl-6">
                                                                    <label for="lastname" class="d-none">Last Name <span class="required-f">*</span></label>
                                                                    <input name="lastname" id="lastname" placeholder="Last Name" value="<?php echo $lastname?>" required type="text">
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="form-group col-md-6 col-lg-6 col-xl-6">
                                                                    <label for="address" class="d-none">Address <span class="required-f">*</span></label>
                                                                    <input name="address" placeholder="Address" value="<?php echo $address?>" id="address" type="text" required>
                                                                </div>
                                                                <div class="form-group col-md-6 col-lg-6 col-xl-6">
                                                                    <label for="address1" class="d-none">Address </label>
                                                                    <input name="address1" placeholder="Address" value="<?php echo $address1?>" id="address1" type="text">
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="form-group col-md-6 col-lg-6 col-xl-6">
                                                                    <label for="city" class="d-none">City <span class="required-f">*</span></label>
                                                                    <input name="city" placeholder="City" value="<?php echo $city?>" id="city" type="text" required>
                                                                </div>
                                                                <div class="form-group col-md-6 col-lg-6 col-xl-6">
                                                                    <label for="input-address-22" class="d-none">Post Code <span class="required-f">*</span></label>
                                                                    <input type="text" name="zipcode" id="zipcode" value="<?php echo $zipcode?>" placeholder="Zip/postal code" class="form-control" maxlength="6" required>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="form-group col-md-6 col-lg-6 col-xl-6">
                                                                    <label for="state" class="d-none">State <span class="required-f">*</span></label>
                                                                    <select name="state" id="state" required>
																		<?php echo getstatemenu($state)?>
																	</select>
                                                                </div>
                                                                <div class="form-group col-md-6 col-lg-6 col-xl-6">
                                                                    <label for="country" class="d-none">Country <span class="required-f">*</span></label>
                                                                    <select name="country" id="country" data-default="99">
																		<?php echo getcountrymenu($country)?>
																	</select>
                                                                </div>
                                                            </div>
                                                            <button type="submit" class="btn rounded mt-1"><span>Submit</span></button>
                                                        </fieldset>
                                                    </form>
                                                </div>
							</div>
						</div>
					</div>
					<!-- End Address -->

					<!-- Account Details -->
<?php
$row= query_first("select u.phone, u.email, u.username,u.lastname, a.address, a.address1, a.city, a.state, a.zipcode, newsletter from ccd9company u join ccd9address a on a.addressid=u.addressid where u.compid='".$_SESSION['compid']."'");
$newsemail = $row['newsletter'];
$username = $row['username'];
$lastname = $row['lastname'];
$useremail = $row['email'];
$userphone = $row['phone'];

?>
					<div id="account-details" class="tab-pane <?php echo ($task=='edit-information' ? 'active' : 'fade')?>">
						<h3>Account details </h3>
						<div class="account-login-form bg-light-gray padding-20px-all">
							<form action="edit-information" method="post">
								<fieldset>
									<div class="row">
										<div class="form-group col-md-6 col-lg-6 col-xl-6">
											<label for="input-firstname" class="d-none">First Name <span class="required-f">*</span></label>
											<input name="username" placeholder="First Name" value="<?php echo $username?>" id="input-firstname" class="form-control" maxlength="20" type="text" required>
										</div>
										<div class="form-group col-md-6 col-lg-6 col-xl-6">
											<label for="input-lastname" class="d-none">Last Name <span class="required-f">*</span></label>
											<input name="lastname" placeholder="Last Name" value="<?php echo $lastname?>" id="input-lastname" class="form-control" maxlength="20" type="text" required>
										</div>
									</div>
									<div class="row">
										<div class="form-group col-md-6 col-lg-6 col-xl-6">
											<label for="input-email" class="d-none">Email <span class="required-f">*</span></label>
											<input name="email" placeholder="Email" value="<?php echo $useremail?>" id="input-email" class="form-control" type="email" maxlength="120" required>
										</div>
										<div class="form-group col-md-6 col-lg-6 col-xl-6">
											<label for="input-telephone" class="d-none">Telephone <span class="required-f">*</span></label>
											<input name="phone" placeholder="Telephone" value="<?php echo $userphone?>" id="input-telephone" class="form-control" maxlength="10" type="tel" required>
										</div>
									</div>
									<div class="row mb-4">
										<div class="col-md-12 col-lg-12 col-xl-12">
											<!-- <div class="customCheckbox clearfix mb-2">
												<input id="offers" name="offers" type="checkbox" />
												<label for="offers">Receive offers from our partners</label>
											</div> -->
											<div class="customCheckbox clearfix">
												<input id="newsletter" name="newsletter" type="checkbox" value="1"/>
												<label for="newsletter">Sign up for our newsletter</label>
											</div>
										</div>
									</div>
								</fieldset>
								<button type="submit" class="btn btn-primary rounded">Save</button>
							</form>
						</div>
					</div>
					<!-- End Account Details -->

					<div id="account-password" class="tab-pane <?php echo ($task=='password' ? 'active' : 'fade')?>">
						<h3>Change password </h3>
						<div class="account-login-form bg-light-gray padding-20px-all">
							<form action="password" method="post">
								<fieldset>
									<div class="row">
										<div class="form-group col-md-6 col-lg-6 col-xl-6">
											<label for="input-password" class="d-none">Existing Password <span class="required-f">*</span></label>
											<input name="passwd" placeholder="Existing Password" value="" id="input-password" class="form-control" type="password" required>
										</div>
										<div class="form-group col-md-6 col-lg-6 col-xl-6">
											<label for="input-password" class="d-none">New Password <span class="required-f">*</span></label>
											<input name="npasswd" placeholder="New Password" value="" id="input-password" class="form-control" type="password" required>
										</div>
										<div class="form-group col-md-6 col-lg-6 col-xl-6">
											<label for="input-password" class="d-none">Confirm Password <span class="required-f">*</span></label>
											<input name="cpasswd" placeholder="Confirm Password" value="" id="input-password" class="form-control" type="password" required>
										</div>
									</div>
								</fieldset>
								<button type="submit" class="btn btn-primary rounded">Save</button>
							</form>
						</div>
					</div>
					<!-- End Password -->

					<!-- Wishlist -->
					<div id="wishlist" class="product-wishlist tab-pane <?php echo ($task=='wishlist' ? 'active' : 'fade')?>">
						<h3>My Wishlist</h3>
						<!-- Grid Product -->
						<div class="grid-products grid--view-items wishlist-grid mt-4">
							<div class="row">
								<?php
									$sqllist="select c.wishid, c.measlist, p.*, ph.photo from ccd9wishlist c join ccd9products p on p.prodid=c.prodid join ccd9prod2type3 t3 on p.prodid=t3.prodid left join ccd9prod2type1 v1 on v1.prodid=c.prodid left join ccd9prodphotos ph on ph.prodid=c.prodid and ph.type1=v1.typeid and ph.type3=t3.typeid and ph.photo!=''where c.compid='".$_SESSION['compid']."' group by c.prodid";
									//echo $sqllist;
									$result = $mysqli->query($sqllist);
									$num_rows = mysqli_num_rows($result);
									if($num_rows>0){ $n=0;
									while($row=$result->fetch_array()){$n++;
										$url=getprodurl($row['produrl']);
										list($prodphoto1, $prodphoto2) = getprodphotos(trim($row['photo']), trim($row['images']));
										list($prodprice, $priceval)=getprice($row);
									?>
								
								<div class="col-6 col-sm-6 col-md-3 col-lg-3 item position-relative">
									<a href="wishlist?remprod=<?php echo $row['prodid'];?>" class="btn remove-icon close-btn" data-bs-toggle="tooltip" data-bs-placement="top" title="Remove"><i class="icon an an-times-r"></i></a>
									<!-- Product Image -->
									<div class="product-image">
										<!-- Product Image -->
										<a href="<?php echo $url?>" class="product-img">
											<!-- image -->
											<img class="primary blur-up lazyload" data-src="<?php echo $prodphoto1?>" src="<?php echo $prodphoto1?>" alt="product" title="product" />
											<!-- End image -->
											<!-- Hover image -->
											<img class="hover blur-up lazyload" data-src="<?php echo $prodphoto2?>" src="<?php echo $prodphoto2?>" alt="product" title="product" />
											<!-- End hover image -->
											<!-- product label -->
											<!-- <div class="product-labels rectangular"><span class="lbl pr-label3">Low in stock</span></div> -->
											<!-- End product label -->
										</a>
										<!-- End Product Image -->
									</div>
									<!-- End Product Image -->

									<!-- Product Details -->
									<div class="product-details text-center">
										<!-- Product Name -->
										<div class="product-name">
											<a href="<?php echo $url?>"><?php echo $row['prodname']?></a>
										</div>
										<!-- End Product Name -->
										<!-- Product Price -->
										<div class="product-price">
											<!-- <span class="old-price">$199.00</span> -->
											<span class="price"><?php echo $prodprice?></span>
										</div>
										<!-- End Product Price -->
										<!-- Product Review -->
										<!-- <div class="product-review d-flex align-items-center justify-content-center">
											<i class="an an-star"></i><i class="an an-star"></i><i class="an an-star"></i><i class="an an-star"></i> <i class="an an-star-o"></i>
											<span class="caption hidden ms-2">9 reviews</span>
										</div> -->
										<!-- End Product Review -->
										<!-- Product Button -->
										<!-- <form method="post" action="/cart/add" class="cart-form mt-3" enctype="multipart/form-data">
											<a href="cart-style1.html" class="btn btn--small rounded product-form__cart-submit"><span>Add to cart</span></a>
										</form> -->
										<!-- End Product Button -->
									</div>
									<!-- End Product Details -->
								</div>
								<?php }}else{?>
										<div class="col-12 col-sm-12 col-md-12 col-lg-12 text-center pt-5 pb-5">
											<p><img src="assets/images/sad-icon.png" alt=""></p>
											<h2 class="mt-4"><strong>SORRY,</strong> Wishlist is empty!</h2>
											<p><a href="/" class="btn btn-outline-primary rounded mb-2 me-2">GO Back</a><a href="search" class="btn rounded mb-2 text-capitalize">Continue shopping</a></p>
										</div>
								<?php }?>
								</div>
							</div>
						</div>
						<!-- End Grid Product-->
					</div>
					<!-- End Wishlist -->
				</div>
				<!-- End Tab panes -->
			</div>
		</div>
		<!--End Main Content-->
	</div>
	<!--End Container-->
</div>
<!--End Body Container-->
