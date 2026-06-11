<?php
$type3=intval($_GET['type3']);
if($type3==0){
	$type3=$resin['typeid'];
}
$type1=intval($_GET['type1']);
if($type1==0){
	$type1=$resin['typefit'];
}
$type2=trim($_GET['type2']);
$proddesc=str_replace('\n','<br>',trim($resin['proddesc']));
$prodvideo=$resin['video'];
$prodcatid=$resin['prodcatid'];
$prodsize=$resin['prodsize'];
$prodrating=$resin['rating'];
$arrsize=array();
if(strstr($prodsize,',')){
	$arrsize=explode(',', dbval($prodsize));
}else{
	if($prodsize!='')$arrsize[0]=dbval($prodsize);
}
$arrtype2=array();
if(strstr($resin['type2'],',')){
	$arrtype2=explode(',', dbval($resin['type2']));
}else{
	if($resin['type2']!='')$arrtype2[0]=dbval($resin['type2']);
}

$prodsize=$resin['prodsize']; 
//$prodprice=$resin['prodprice'];
list($prodprice, $priceval)=getprice($resin);
$prodgallery=trim($resin['photo']);
if(trim($resin['images'])!='' && $prodgallery==''){
	$prodgallery=trim($resin['images']);
}

if(strstr($prodgallery,',')){
	$arrgallery=explode(',', trim($prodgallery));
}else{
	$arrgallery=array();
	$arrgallery[0]=trim($prodgallery);
}
$pageurl=urlencode($serverurl.$_SERVER['REQUEST_URI']);
//echo $prodcatid;
?>
<style>
img.rts-icon {
    width: 7px;
    height: 9px;
    margin-left: 2px;
    margin-top: 2px;
}
</style>
<!--Body Container-->
            <div id="page-content">   
                <!--Breadcrumbs-->
                <div class="breadcrumbs-wrapper text-uppercase">
                    <div class="container">
                        <div class="breadcrumbs"><a href="index.html" title="Back to the home page">Home</a><span>|</span><span class="fw-bold"><?php echo $_SESSION['optreturn'];?></span></div>
                    </div>
                </div>
                <!--End Breadcrumbs-->

                <!--Main Content-->
                <div class="container-fluid">
                    <!--Product Content-->
                    <div class="product-single">
						
                        <div class="row">
                            <div class="col-lg-7 col-md-6 col-sm-12 col-12">
                                <?php if($_GET['errmsg']!=''){?>
								<div class="alert alert-success py-2 rounded-1 alert-dismissible fade show cart-alert" role="alert">
									<i class="align-middle icon an an-check-cil icon-large me-2"></i><?php echo dbval($_GET['errmsg'])?>
									<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
								</div>
								<?php }?>
								<div class="product-details-img thumb-left clearfix d-flex-wrap mb-3 mb-md-0">
                                    <div class="product-thumb">
                                        <div id="gallery" class="product-dec-slider-2 product-tab-left">
											<?php for($x=0;$x<count($arrgallery);$x++){
												$prodphoto = trim($arrgallery[$x]);
												if($x==0)$defaultphoto=$prodphoto;

												/*
												$photoext= trim(substr($prodphoto,strrpos($prodphoto,".")+1));
												$photoname = trim($resin['produrl']).'-'.$type3.'-'.strtolower(chr($x+65));
												$prodimg = $photoname.'.'.$photoext;
												$webpimg = $photoname.'.webp';
												
												if(!file_exists($imgpath.$prodimg)){
													$photocontent = file_get_contents($prodphoto);
													file_put_contents($imgpath.$prodimg, $photocontent);
												}
												

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
												}
												if($x==0)$defaultphoto=$webpimg;
												if(file_exists($imgpath.$webpimg)){*/
													//$arrgallery[$x]
													$photolist.='<a data-image="'.trim($prodphoto).'" data-zoom-image="'.trim($prodphoto).'" class="slick-slide slick-cloned active">
														<img class="blur-up lazyload" data-src="'.trim($prodphoto).'" src="'.trim($prodphoto).'" alt="product" />
													</a>'."\n";
													$lightboximages.='<a href="'.trim($prodphoto).'" data-size="1000x1280"></a>'."\n";
												//}
											}
											echo $photolist;

											?>
                                            
                                        </div>
                                    </div>
                                    <div class="zoompro-wrap product-zoom-right">
                                        <div class="zoompro-span"><img id="zoompro" class="zoompro" src="<?php echo trim($defaultphoto)?>" data-zoom-image="<?php echo trim($defaultphoto)?>" alt="product" /></div>
                                        <!-- <div class="product-labels"><span class="lbl pr-label1">new</span><span class="lbl on-sale">Best seller</span></div> -->
                                        <div class="product-wish"><a class="wishIcon wishlist rounded m-0" href="Javascript:addtowishbtn('<?php echo $resin['prodid']?>','<?php echo $opt?>','<?php echo dbval($resin['prodcode'])?>','<?php echo dbval(str_replace("'","",$resin['prodname']))?>','<?php echo $priceval?>','<?php echo dbval($resin['prodcolor'])?>','<?php echo $catid?>','<?php echo dbval($catname)?>')"><i class="icon an an-heart-l" id="wish<?php echo $resin['prodid'];?>" <?php echo ($resin['wishid'] ? ' style="color:#f00000"' : '')?>></i><?php echo ($resin['wishid'] ? '<span class="tooltip-label left">Available in Wishlist</span>' : '');?></a></div>
                                        <div class="product-buttons">
											<?php if($prodvideo!=''){?>
                                            <a href="https://www.youtube.com/watch?v=<?php echo $prodvideo?>" class="mfpbox mfp-with-anim btn rounded popup-video"><i class="icon an an-video"></i><span class="tooltip-label">Watch Video</span></a>
											<?php }?>
                                            <!-- <a href="#" class="btn rounded prlightbox"><i class="icon an an-expand-l-arrows"></i><span class="tooltip-label">Zoom Image</span></a> -->
                                        </div>
                                    </div>
                                    <div class="lightboximages">
                                        <?php echo $lightboximages?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-5 col-md-6 col-sm-12 col-12">
								
                                <!-- Product Info -->
                                <div class="product-single__meta">
                                    <h1 class="product-single__title"><?php echo $resin['prodname']?></h1>
                                    <!-- <div class="product-single__subtitle">From Italy</div> -->
                                    <!-- Product Reviews -->
                                    <div class="product-review mb-2"><a class="reviewLink d-flex-center" href="<?php echo $_SERVER['REQUEST_URI']?>#reviews">
									<?php
									$revresult=$mysqli->query("select r.*, date_format(revdate,'%b %d, %Y') as revdt from ccd9reviews  r where prodid='$prodid' and status='1'");
									$numreviews = mysqli_num_rows($revresult);
									showrating($prodrating);
									?>
									<span class="spr-badge-caption ms-2"><?php echo $numreviews?> Reviews</span></a></div>
                                    <!-- End Product Reviews -->
                                    <!-- Product Info -->
                                    <div class="product-info">
                                        <!-- <p class="product-type">Vendor: <span>Bohemian France</span></p>   -->
                                        <p class="product-sku">SKU: <span class="variant-sku"><?php echo $resin['prodcode']?></span></p>
                                    </div>
                                    <!-- End Product Info -->
                                    <!-- Product Price -->
                                    <div class="product-single__price pb-1">
                                        <span class="visually-hidden">Regular price</span>
                                        <span class="product-price__sale--single">
                                            <span class="product-price__price"><?php echo $prodprice?></span> <!-- product-price-old-price -->
											<!-- <span class="product-price__price product-price__sale">$225.00</span>   
                                            <span class="discount-badge"><span class="devider me-2">|</span><span>Save: </span><span class="product-single__save-amount"><span class="money">$99.00</span></span><span class="off ms-1">(<span>25</span>%)</span></span>  -->
                                        </span>
                                        <!-- <div class="product__policies fw-normal mt-1">Tax included.</div> -->
                                    </div>
                                    <!-- End Product Price -->
                                    <!-- Countdown -->
                                    <!-- <div class="countdown-text d-flex-wrap mb-3 pb-1">
                                        <label class="mb-2 mb-lg-0">Limited-Time Offer :</label>
                                        <div class="prcountdown d-flex" data-countdown="2024/10/01"></div>
                                    </div> -->
                                    <!-- End Countdown -->
                                    <!-- Product Sold -->
                                    <!-- <div class="orderMsg d-flex-center" data-user="23" data-time="24">
                                        <img src="assets/images/order-icon.jpg" alt="order" />
                                        <p class="m-0"><strong class="items">8</strong> Sold in last <strong class="time">14</strong> hours</p>
                                        <p id="quantity_message" class="ms-2 ps-2 border-start">Hurry! Only  <span class="items fw-bold">4</span>  left in stock.</p>
                                    </div> -->
                                    <!-- End Product Sold -->
                                </div>
                                <!-- End Product Info -->
                                <!-- Product Form -->
                                <form name="frmprod" method="post" action="addtocart" class="product-form hidedropdown" onsubmit='return doaddcart()'>
									<input type="hidden" name="retnto" value="">
									<input type="hidden" name="carteventid" value="<?php //echo $carteventid?>">
									<input type="hidden" name="prodid" value="<?php echo dbval($resin['prodid'])?>">
									<input type="hidden" name="measlist" value="<?php //echo $type1?>">
									
                                    <!-- Swatches Size -->
									<?php
									$sqlcol="select t3c.typeid, t3c.typename, t3c.typevalue from ccd9prod2type1 t3 join ccd9types t3c on t3.typeid=t3c.typeid and t3c.opt=3  where t3.prodid='$prodid' and  t3c.typename!='NA' and t3.status='1' order by t3c.typeid";
									$result = $mysqli->query($sqlcol); 
									$num_rows = mysqli_num_rows($result);
									if ($num_rows>0){ $retval='';
									?>
                                    <div class="swatch clearfix swatch-1 option3" data-option-index="0">
                                        <div class="product-form__item">
                                            <label class="label d-flex">Fit: <span class="required">*</span><!--  d-none<span class="slVariant ms-1 fw-bold">S</span> --></label>
                                            <ul class="swatches-fit d-flex-center list-unstyled clearfix">
												<?php
												
												while($rescon = $result->fetch_array()){$retsel="";
													$retval=$retval.' <li data-value="'.$rescon['typeid'].'" class="swatch-element '.$rescon['typename'].' available '.($type1==$rescon['typeid'] ? 'active' : '').'"> <!-- active -->
														<a href="'.$_SESSION['optreturn'].'/'.$opt.'&type1='.$rescon['typeid'].'&type3='.$type3.'"><label class="swatchLbl rounded large" title="'.$rescon['typename'].'">'.$rescon['typename'].'</label></a><span class="tooltip-label">'.$rescon['typename'].'</span>
													</li>'; //  onclick="gettype(\'1\',\''.$rescon['typeid'].'\')"
												} 
												echo $retval;
												
												?>
                                            </ul>
                                        </div>
                                    </div>
                                    <!-- End Swatches Color -->
									<?php }else{$type1=44;}

									$sqlcol="select t3c.typeid, t3c.typename, t3c.typevalue from ccd9prod2type3 t3 join ccd9types t3c on t3.typeid=t3c.typeid and t3c.opt=7  where t3.prodid='$prodid' and  t3c.typename!='NA' and t3.status='1' order by t3c.typeid";
									$result = $mysqli->query($sqlcol); 
									$num_rows = mysqli_num_rows($result);
									if ($num_rows>0){ $retval='';
									?>
									<!-- Swatches Color -->
                                    <div class="swatches-image swatch clearfix swatch-0 option1" data-option-index="1">
                                        <div class="product-form__item">
                                            <label class="label d-flex">Color: <span class="required">*</span><!-- <span class="slVariant ms-1 fw-bold">Red</span> --></label>
                                            <ul class="swatches d-flex-wrap list-unstyled clearfix">
												<?php
												while($rescon = $result->fetch_array()){$retsel="";
													$retval=$retval.'<li data-value="'.$rescon['typevalue'].'" class="swatch-element color available '.($type3==$rescon['typeid'] ? 'active' : '').'">
														<a href="'.$_SESSION['optreturn'].'/'.$opt.'&type1='.$type1.'&type3='.$rescon['typeid'].'"><label class="swatchLbl rounded color xlarge" title="'.$rescon['typename'].'" style="background-color:'.$rescon['typevalue'].'"></label></a>
														<span class="tooltip-label top">'.$rescon['typename'].'</span>
													</li>';
												} 
												echo $retval;
												
												?>
                                            </ul>
                                        </div>
                                    </div>
                                    <!-- End Swatches Color -->
									<?php } else{$type3=46;}
									
									if(count($arrsize)>0){ //echo $type2;
									?>
                                    <!-- Swatches Size -->
                                    <div class="swatch clearfix swatch-1 option2" data-option-index="2">
                                        <div class="product-form__item">
                                            <label class="label d-flex">Size: <span class="required">*</span><!--  d-none<span class="slVariant ms-1 fw-bold">S</span> --></label>
                                            <ul class="swatches-size d-flex-center list-unstyled clearfix">
												<?php $retval='';
												for($n=0;$n<count($arrsize);$n++){
													$sqlstock = "select prodqty as qty from ccd9stocks where ".($type1==44 ? "(type1='0' or type1='44')" :  "type1='$type1'")."  and  ".($type3==46 ? "(type3='0' or type3='46')" :  "type3='$type3'")." and type2='".trim($arrtype2[$n])."' and prodid='$prodid'";
													$resdata=query_first($sqlstock);


													$retval=$retval.' <li data-value="'.trim($arrsize[$n]).'" class="swatch-element '.trim($arrsize[$n]).' available '.($type2==trim($arrsize[$n]) && $type2!='0' ? 'active' : '').'">
														<label class="swatchLbl rounded medium" title="'.trim($arrsize[$n]).'" onclick="gettype(\'2\',\''.trim($arrsize[$n]).'\')">'.trim($arrsize[$n]).($resdata['qty']>0 ? '<img class="rts-icon" src="assets/images/rts-icon.svg">' : '').'</label><span class="tooltip-label">'.trim($arrsize[$n]).($resdata['qty']>0 ? ' - Available In Stock' : '').'</span></li>';
												}
												echo $retval;
												?>
                                               
                                                
                                                <li class="ms-1"><a href="#sizechart" class="sizelink link-underline text-uppercase"><i class="icon an an-ruler d-none"></i> Size Guide</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <!-- End Swatches Size -->
									<?php }else{$type2='NA';}?>
									<input type="hidden" name="prodmeas" id="prodmeas" value="<?php echo $type1?>">
									<input type="hidden" name="prodcolor" id="prodcolor" value="<?php echo $type3?>">
									<input type="hidden" name="prodsize" id="prodsize" value="<?php echo $type2?>">
									<?php 
										//echo $_SESSION['optreturn'].'<br>';
										//echo $prodcatid.'<br>';
										$sqlstock = "select * from ccd9stocks where type1='".($type1==44 ? 0 : $type1)."'  and type3='".($type3==46 ? 0 : $type3)."' and prodid='$prodid'";
										//echo $sqlstock; //and type2='".($type2==45 ? 0 : $type2)."'
										$resstock = $mysqli->query($sqlstock); 
										$num_rows = mysqli_num_rows($resstock);
										if ($num_rows>0){
											while($rescon = $resstock->fetch_array()){ 
												echo '<input type="hidden" id="qty'.trim($rescon['type2']).'" name="qty'.trim($rescon['type2']).'"  value="'.trim($rescon['prodqty']).'">';
											}
										}
									?>
                                    <!-- Product Action -->
                                    <div class="product-action w-100 clearfix">
                                        <p class="infolinks d-flex-center mt-2 mb-3">
										<?php 
										//echo $_SESSION['optreturn'].'<br>';
										//echo $prodcatid.'<br>';
										$sqlsize = "select * from ccd9sizes where (type1='".($type1==44 ? 0 : $type1)."' or  type1='4')  and catid='$prodcatid'";
										//echo $sqlsize;
										$ressize = $mysqli->query($sqlsize); 
										$num_rows = mysqli_num_rows($ressize);
										if ($num_rows>0){
											echo '<table class="table table-bordered align-middle table-hover text-center mb-1"><tr class="d-none" id="sizemain"><th>India</th><th>UK</th><th>Length(MM)</th><th>Width(MM)</th></tr>';
											while($rescon = $ressize->fetch_array()){ 
												echo '<tr class="d-none" id="size'.trim($rescon['indiasize']).'" ><td>'.trim($rescon['indiasize']).'</td><td>'.trim($rescon['uksize']).'</td><td>'.trim($rescon['length']).'</td><td>'.trim($rescon['width']).'</td></tr>';
											}
											echo '</table>';
										}

										?>
										</p>
										
										<!-- <div class="product-form__item--quantity d-flex-center mb-3">
                                            <div class="qtyField">
                                                <a class="qtyBtn minus" href="javascript:void(0);"><i class="icon an an-minus-r" aria-hidden="true"></i></a>
                                                <input type="text" name="quantity" value="1" class="product-form__input qty">
                                                <a class="qtyBtn plus" href="javascript:void(0);"><i class="icon an an-plus-r" aria-hidden="true"></i></a>
                                            </div>
                                            <div class="pro-stockLbl ms-3">
                                                <span class="d-flex-center stockLbl instock"><i class="icon an an-check-cil"></i><span> In stock</span></span>
                                                <span class="d-flex-center stockLbl preorder d-none"><i class="icon an an-clock-r"></i><span> Pre-order Now</span></span>
                                                <span class="d-flex-center stockLbl outstock d-none"><i class="icon an an-times-cil"></i> <span>Sold out</span></span>
                                                <span class="d-flex-center stockLbl lowstock d-none" data-qty="15"><i class="icon an an-exclamation-cir"></i><span> Order now, Only  <span class="items">10</span>  left!</span></span>
                                            </div>
                                        </div> -->
                                        <div class="product-form__item--submit">
                                            <button type="submit" name="add" class="btn rounded product-form__cart-submit"><span>Add to cart</span></button>
                                            <button type="button" name="add" class="btn rounded product-form__sold-out d-none" disabled="disabled">Sold out</button>
                                        </div>
                                        <!-- <div class="product-form__item--buyit clearfix">
                                            <button type="button" class="btn rounded btn-outline-primary proceed-to-checkout">Buy it now</button>
                                        </div>
                                        <div class="agree-check customCheckbox clearfix d-none">
                                            <input id="prTearm" name="tearm" type="checkbox" value="term" />
                                            <label for="prTearm">I agree with the terms and conditions</label>
                                        </div> -->
                                    </div>
                                    <!-- End Product Action -->
                                    <!-- Product Info link -->
                                    <p class="infolinks d-flex-center mt-2 mb-3">
                                        <a class="btn add-to-wishlist d-none" href="my-wishlist.html"><i class="icon an an-heart-l me-1" aria-hidden="true"></i> <span>Add to Wishlist</span></a>
                                        <!-- <a class="btn add-to-wishlist" href="compare-style1.html"><i class="icon an an-sync-ar me-1" aria-hidden="true"></i> <span>Add to Compare</span></a> -->
                                        <a class="btn shippingInfo" href="#ShippingInfo"><i class="icon an an-paper-l-plane me-1"></i> Delivery &amp; Returns</a>
                                        <a class="btn emaillink me-0" href="#productInquiry"> <i class="icon an an-question-cil me-1"></i> Ask A Question</a>
                                    </p>
                                    <!-- End Product Info link -->
                                </form>
                                <!-- End Product Form -->
                                <!-- Social Sharing -->
                                <div class="social-sharing d-flex-center mb-3">
                                    <span class="sharing-lbl me-2">Share :</span>
                                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $pageurl?>" target="_blank" class="d-flex-center btn btn-link btn--share share-facebook" data-bs-toggle="tooltip" data-bs-placement="top" title="Share on Facebook"><i class="icon an an-facebook mx-1"></i><span class="share-title d-none">Facebook</span></a>
                                    <a href="https://twitter.com/intent/tweet?text=<?php echo $pageurl?>" target="_blank" class="d-flex-center btn btn-link btn--share share-twitter" data-bs-toggle="tooltip" data-bs-placement="top" title="Tweet on Twitter"><i class="icon an an-twitter mx-1"></i><span class="share-title d-none">Tweet</span></a>
                                    <a href="https://api.whatsapp.com/send?text=<?php echo dbval($resin['prodname']).$pageurl?>" target="_blank" class="d-flex-center btn btn-link btn--share share-pinterest" data-bs-toggle="tooltip" data-bs-placement="top" title="Share on WhatsApp"><i class="icon an an-whatsapp mx-1"></i> <span class="share-title d-none">WhatsApp</span></a>
                                    <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo $pageurl?>" target="_blank" class="d-flex-center btn btn-link btn--share share-linkedin" data-bs-toggle="tooltip" data-bs-placement="top" title="Share on Linkedin"><i class="icon an an-linkedin mx-1"></i><span class="share-title d-none">Linkedin</span></a>
                                    <a href="mailto:?subject=<?php echo dbval($resin['prodname'])?>&body=<?php echo $pageurl?>" class="d-flex-center btn btn-link btn--share share-email" target="_blank" data-bs-toggle="tooltip" data-bs-placement="top" title="Share by Email"><i class="icon an an-envelope-l mx-1"></i><span class="share-title d-none">Email</span></a>
                                </div>
                                <!-- End Social Sharing -->
                                <!-- Product Info -->
                                <!-- <div class="freeShipMsg" data-price="199"><i class="icon an an-truck" aria-hidden="true"></i>SPENT <b class="freeShip"><span class="money" data-currency-usd="$199.00" data-currency="USD">$199.00</span></b> MORE FOR FREE SHIPPING</div> -->
								<?php
								//$stockstatus=0;
								//if($stockstatus==1){
									$deliverydate1=date('D, M d', strtotime(date("Y-m-d"). ' + 3 days'));
									$deliverydate2=date('D, M d', strtotime(date("Y-m-d"). ' + 7 days'));
									//$deliverydate1=date('D, M d', strtotime(date("Y-m-d"). ' + 7 days'));
									//$deliverydate2=date('D, M d', strtotime(date("Y-m-d"). ' + 13 days'));
								//}else{
									$deliverydate3=date('D, M d', strtotime(date("Y-m-d"). ' + 7 days'));
									$deliverydate4=date('D, M d', strtotime(date("Y-m-d"). ' + 11 days'));
									//$deliverydate1=date('D, M d', strtotime(date("Y-m-d"). ' + 14 days'));
									//$deliverydate2=date('D, M d', strtotime(date("Y-m-d"). ' + 20 days'));
								//}
								?>
                                <div class="shippingMsg"><i class="icon an an-clock-r" aria-hidden="true"></i>Estimated Delivery Between 
								<span id="del7" class=""><b id="fromDate"><?php echo $deliverydate1?></b> and <b id="toDate"><?php echo $deliverydate2?></b></span>
								<span id="del14" class="d-none"><b id="fromDate"><?php echo $deliverydate3?></b> and <b id="toDate"><?php echo $deliverydate4?></b></span>
								
								.</div>
                                <!-- <div class="userViewMsg" data-user="20" data-time="11000"><i class="icon an an-eye-r" aria-hidden="true"></i><strong class="uersView">21</strong> People are Looking for this Product</div> -->
                                <div class="trustseal-img mt-4"><img src="assets/images/powerby-cards.jpg" alt="powerby cards" /></div>
                                <!-- End Product Info -->
                            </div>
                        </div>
                    </div>
                    <!--Product Content-->

                    <!--Product Nav-->
                    <!-- <a href="product-layout7.html" class="product-nav prev-pro d-flex-center justify-content-between" title="Previous Product">
                        <span class="details">
                            <span class="name">Mini Sleev Top</span>
                            <span class="price">$199.00</span>
                        </span>
                        <span class="img"><img src="assets/images/products/product-7.jpg" alt="product" /></span>
                    </a>
                    <a href="product-layout2.html" class="product-nav next-pro d-flex-center justify-content-between" title="Next Product">
                        <span class="img"><img src="assets/images/products/product-2.jpg" alt="product"></span>
                        <span class="details">
                            <span class="name">Ditsy Floral Dress</span>
                            <span class="price">$99</span>
                        </span>
                    </a> -->
                    <!--End Product Nav-->

                    <!--Product Tabs-->
                     <div class="tabs-listing mt-2 mt-md-5">
                        <ul class="product-tabs list-unstyled d-flex-wrap border-bottom m-0 d-none d-md-flex">
                            <li rel="description" class="active"><a class="tablink">Description</a></li>
                            <li rel="size-chart"><a class="tablink">Size Chart</a></li>
                            <li rel="shipping-return"><a class="tablink">Shipping &amp; Return</a></li>
                            <!-- <li rel="reviews"><a class="tablink">Reviews</a></li> -->
                            <!-- <li rel="addtional-tabs"><a class="tablink">Addtional Tabs</a></li> -->
                        </ul>
                        <div class="tab-container">
                            <h3 class="tabs-ac-style d-md-none active" rel="description">Description</h3>
                            <div id="description" class="tab-content">
                                <div class="product-description">
                                    <div class="row">
                                        <div class="col-12 col-sm-12 col-md-8 col-lg-8 mb-4 mb-md-0">
                                            <?php echo $proddesc?>
                                        </div>
                                        <!-- <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                                            <img data-src="assets/images/about/about-info-s3.jpg" src="assets/images/about/about-info-s3.jpg" alt="image" />
                                        </div> -->
                                    </div>
                                </div>
                            </div>

                            <h3 class="tabs-ac-style d-md-none" rel="size-chart">Size Chart</h3>
                            <div id="size-chart" class="tab-content">
                                <h4 class="fw-bold text-center">Size Guide</h4>
                                <?php include("includes/sizechart.php");?>
                            </div>

                            <h3 class="tabs-ac-style d-md-none" rel="shipping-return">Shipping &amp; Return</h3>
                            <div id="shipping-return" class="tab-content">
                                <h4 class="pt-2 text-uppercase"><br></h4>
                                <?php 
									$resdata=query_first("select * from ccd9pages where pageid='18315'");
									$deliverydata =  trim($resdata['description']);

									$resdata=query_first("select * from ccd9pages where pageid='18316'");
									$deliverydata =  $deliverydata . trim($resdata['description']);

									echo $deliverydata;
								?>
                            </div>

                            <!-- <h3 class="tabs-ac-style d-md-none" rel="reviews">Review</h3>
                            <?php //include("includes/product-reviews.php");?> -->

                            <!-- <h3 class="tabs-ac-style d-md-none" rel="addtional-tabs">Addtional Tabs</h3>
                            <div id="addtional-tabs" class="tab-content">
                                <p>You can set different tabs for each products.</p>
                                <ul>
                                    <li>Comodous in tempor ullamcorper miaculis.</li>
                                    <li>Pellentesque vitae neque mollis urna mattis laoreet.</li>
                                    <li>Divamus sit amet purus justo.</li>
                                    <li>Proin molestie egestas orci ac suscipit risus posuere loremous.</li>
                                </ul>
                            </div> -->
                        </div>
                    </div>
                    <!--End Product Tabs-->
                </div>
                <!--End Container-->
				<!--You May Also Like Products-->
                <?php include("includes/reviews-products.php");?>
                <!--End You May Also Like Products-->
                <!--You May Also Like Products-->
                <?php include("includes/like-products.php");?>
                <!--End You May Also Like Products-->

                <!--Recently Viewed Products-->
                <?php //include("includes/viewed-products.php");?>
                <!--End Recently Viewed Products-->
            </div>
            <!--End Body Container-->

            

            

            <!-- Shipping Popup-->
            <div id="ShippingInfo" class="mfpbox mfp-with-anim mfp-hide">
                <?php 
				echo $deliverydata;
				?>
            </div>
            <!-- End Shipping Popup-->

            <!--Product Enuiry Popup-->
            <?php include("includes/product-enuiry-popup.php");?>
            <!--End Product Enuiry Popup-->

            <!--Size Chart-->
            <div id="sizechart" class="mfpbox mfp-with-anim mfp-hide">
                <h4 class="fw-bold">Size Guide</h4>
                <?php include("includes/sizechart.php");?>
                </div>
                <button title="Close (Esc)" type="button" class="mfp-close">X</button>
            </div>
            <!--End Size Chart-->


<script>
function doaddcart(){
	var t1 = $("#prodmeas").val();
	var t2 = $("#prodsize").val();
	var t3 = $("#prodcolor").val();

	if(t1=='' || t2=='' || t3==''){
		alert('Please select Fit, Size & Color');
		return false;
	}
	//alert('x');
	//addfbCart(parseInt(document.frmprod.prodqty.value));
}

function hidesizes(){
	$("#sizemain").addClass("d-none");
	<?php for($n=0;$n<count($arrsize);$n++){?>
			$("#size<?php echo trim($arrsize[$n])?>").addClass("d-none");
	<?php }?>
}

function gettype(t,v){
	if(t==1){
		$("#prodmeas").val(v);
	}else if(t==2){
		$("#prodsize").val(v);
		var q = $("#qty"+v).val();
		if(parseInt(q)>0){
			$("#del7").removeClass("d-none");
			$("#del14").addClass("d-none");
		}else{
			$("#del14").removeClass("d-none");
			$("#del7").addClass("d-none");
		}
		hidesizes();
		$("#sizemain").removeClass("d-none");
		$("#size"+v).removeClass("d-none");
	}else if(t==3){
		//$("#prodcolor").value(v);
	}
}
</script>