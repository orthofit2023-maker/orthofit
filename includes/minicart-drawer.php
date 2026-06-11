<div class="minicart-right-drawer modal right fade" id="minicart-drawer">
	<div class="modal-dialog">
		<div class="modal-content">
			<div id="cart-drawer" class="block block-cart">
				<div class="minicart-header">
					<a href="javascript:void(0);" class="close-cart" data-bs-dismiss="modal" aria-label="Close"><i class="an an-times-r" aria-hidden="true" data-bs-toggle="tooltip" data-bs-placement="left" title="Close"></i></a>
					<h4 class="fs-6">Your cart (<?php echo $numcart?> Items)</h4>
				</div>
				<div class="minicart-content">
					<ul class="clearfix">
						<?php 
						$resultcart = $mysqli->query($sqlcartlist);
						$numcart = mysqli_num_rows($resultcart);
						if($numcart>0){ $n=0; $tot=0;
							//print_r($resultcart);
							while($row=$resultcart->fetch_array()){ $n++; 
								$url=getprodurl($row['produrl'], $row['caturl'])."&type1=".$row['prodmeas']."&type2=".$row['prodsize']."&type3=".$row['prodcolor'];
								$tot=$tot+($row['finalprice']*$row['prodqty']);
								$showcur=showcursymb($row['prodcur']);
								list($prodphoto1, $prodphoto2) = getprodphotos(trim($row['photo']), trim($row['images']));
							?>
						<li class="item d-flex justify-content-center align-items-center">
							<a class="product-image" href="<?php echo $url?>">
								<img class="blur-up lazyload" src="<?php echo $prodphoto1?>" data-src="<?php echo $prodphoto1?>" alt="image" title="">
							</a>
							<div class="product-details">
								<a class="product-title" href="<?php echo $url?>"><?php echo dbval($row['prodname'])?></a>
								<div class="variant-cart"><?php echo ($row['vprodmeas']!='NA' ? 'Fit: '.$row['vprodmeas'] : '' ).(trim($row['vprodcolor'])!='NA' ? '<br>Color: '.$row['vprodcolor'] : '' ).(trim($row['prodsize'])!='NA' ? '<br>Size: '.trim($row['prodsize']) : '');?></div>
							</div>
							<div class="qtyDetail text-center">
								<div class="wrapQtyBtn">
									<div class="priceRow">
										<div class="product-price">
											<span class="money"><?php echo $showcur.number_format($row['finalprice'])?></span>
										</div>
									</div>
								</div>
							</div>
						</li>
						<?php } }?>
					</ul>
				</div>
				<?php if($numcart>0){?>
				<div class="minicart-bottom">
					<div class="subtotal">
						<span>Total:</span>
						<span class="product-price"><?php echo $showcur.number_format($tot)?></span>
					</div>
					<a href="order" class="w-100 p-2 my-2 btn btn-outline-primary proceed-to-checkout rounded">Proceed to Checkout</a>
					<a href="shopping-cart" class="w-100 btn-primary cart-btn rounded">View Cart</a>
				</div>
				<?php }?>
			</div>
		</div>
	</div>
</div>