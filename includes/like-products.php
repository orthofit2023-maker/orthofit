<?php
$likeresult=$mysqli->query("select t3.*, t3.typeid as t3typeid, t1.typeid as typefit, ph.photo, ph.video, t3c.typename, t3c.typevalue, p.*, IF(CURDATE() between p.offerfrdate and p.offertodate, '1', '0') as isoffer, IF(CURDATE() between p.discfrdate and p.disctodate, '1', '0') as isdiscount, w.wishid from ccd9products p  join ccd9prod2type3 t3 on p.prodid=t3.prodid join ccd9types t3c on t3.typeid=t3c.typeid and t3c.opt=7 left join ccd9prod2type1 t1 on t1.prodid=p.prodid left join ccd9prodphotos ph on ph.prodid=p.prodid and ph.type1=t1.typeid and ph.type3=t3.typeid and ph.photo!='' left join ccd9prod2cat c on p.prodid=c.prodid left join ccd9wishlist w on w.prodid=p.prodid and w.compid='".$_SESSION['compid']."' where  p.prodid !='$prodid' and p.prodstatus='1' and t3.status='1' and c.catid='$prodcatid' group by c.prodid, t3.typeid order by c.prodid desc limit 0,5");
$likerows = mysqli_num_rows($likeresult);
?>

<section class="section product-slider pb-0">
	<div class="container-fluid">
		<div class="row">
			<div class="section-header col-12">
				<h2 class="text-transform-none">You May Also Like <!-- RELATED PRODUCTS --></h2>
			</div>
		</div>
		<div class="productSlider grid-products">
			<?php if($likerows>0){
			while($row=$likeresult->fetch_array()){
				$produrl=getprodurl($row['produrl'],$opt).'&type3='.$row['t3typeid'];

								list($prodphoto1, $prodphoto2) = getprodphotos(trim($row['photo']), trim($row['images']));
								list($prodprice, $priceval)=getprice($row);
				?>
			<div class="item">
				<!--Start Product Image-->
				<div class="product-image">
					<!--Start Product Image-->
					<a href="<?php echo $produrl?>" class="product-img">
						<!-- image -->
						<img class="primary blur-up lazyload" data-src="<?php echo $prodphoto1?>" src="<?php echo $prodphoto1?>" alt="" title="">
						<!-- End image -->
						<!-- Hover image -->
						<img class="hover blur-up lazyload" data-src="<?php echo $prodphoto2?>" src="<?php echo $prodphoto2?>" alt="" title="">
						<!-- End hover image -->
						<!-- product label -->
						<!-- <div class="product-labels"><span class="lbl on-sale">50% Off</span></div> -->
						<!-- End product label -->
					</a>
					<!--End Product Image-->

					<!--Product Button-->
					<div class="button-set style0 d-none d-md-block">
						<ul>
							<!--Cart Button-->
							<!-- <li><a class="btn-icon btn cartIcon pro-addtocart-popup" href="<?php echo $produrl?>"><i class="icon an an-cart-l"></i> <span class="tooltip-label top">Add to Cart</span></a></li> -->
							<!--End Cart Button-->
							<!--Quick View Button-->
							<!-- <li><a class="btn-icon quick-view-popup quick-view" href="javascript:void(0)" data-toggle="modal" data-target="#content_quickview"><i class="icon an an-search-l"></i> <span class="tooltip-label top">Quick View</span></a></li> -->
							<!--End Quick View Button-->
							<!--Wishlist Button-->
							<!-- <li><a class="btn-icon wishlist add-to-wishlist" href="my-wishlist.html"><i class="icon an an-heart-l"></i> <span class="tooltip-label top">Add To Wishlist</span></a></li> -->
							<!--End Wishlist Button-->
							<!--Compare Button-->
							<!-- <li><a class="btn-icon compare add-to-compare" href="compare-style2.html"><i class="icon an an-sync-ar"></i> <span class="tooltip-label top">Add to Compare</span></a></li> -->
							<!--End Compare Button-->
						</ul>
					</div>
					<!--End Product Button-->
				</div>
				<!--End Product Image-->
				<!--Start Product Details-->
				<div class="product-details text-center">
					<!--Product Name-->
					<div class="product-name text-uppercase">
						<a href="<?php echo $produrl?>"><?php echo $row['prodname']?></a>
					</div>
					<!--End Product Name-->
					<!--Product Price-->
					<div class="product-price">
						<span class="price"><?php echo $prodprice?></span>
					</div>
					<!--End Product Price-->
					<!--Product Review-->
					<div class="product-review d-flex align-items-center justify-content-center"><?php showrating($row['rating']);?></div>
					<!--End Product Review-->
					<!--Color Variant -->
					<ul class="swatches">
						<?php echo getcolors($row['prodid'],$produrl)?>
					</ul>
					<!-- End Variant -->
				</div>
				<!--End Product Details-->
			</div>
			<?php }}?>

		</div>
	</div>
</section>