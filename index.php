<?php 
//update ccd9prodphotos set photo=replace(photo, 'orthofitmart.com','orthofit.in');
session_start();
//ini_set("log_errors", 1);
//include("manage/db5conn.php");
include("includes/header.php");
?>
<!doctype html>
<html lang="en">
<head>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=AW-18051420566"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'AW-18051420566');
</script>
<!--Required Meta Tags-->
<!-- Event snippet for Click to call (3) conversion page
In your html page, add the snippet and call gtag_report_conversion when someone clicks on the chosen link or button. -->
<script>
function gtag_report_conversion(url) {
  var callback = function () {
    if (typeof(url) != 'undefined') {
      window.location = url;
    }
  };
  gtag('event', 'conversion', {
      'send_to': 'AW-18051420566/nRXzCMWDi68cEJajy59D',
      'value': 1.0,
      'currency': 'INR',
      'event_callback': callback
  });
  return false;
}
</script>
<meta charset="utf-8">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Orthofit - <?php echo ($metatitle!='' ? $metatitle : 'Orthotic Footwear for Heel Pain &amp; Knee Pain');?></title>
<meta name="description" content="<?php echo  ($metadescription!='' ? $metadescription : 'Buy specialized footwear for heel and knee pain from Orthofit. Explore orthotic, diabetic footwear and footwear accessories for optimal foot support.');?>">
<meta name="keywords" content="<?php echo ($metakeywords!='' ? $metakeywords : 'Buy specialized footwear for heel and knee pain from Orthofit. Explore orthotic, diabetic footwear and footwear accessories for optimal foot support.');?>">
<base href="<?php echo $serverurl?>" />
<link rel="canonical" href="<?php echo $serverurl.($metaurl!='' ? $metaurl : '')?>"/>
<link rel="alternate" href="<?php echo $serverurl.($metaurl!='' ? $metaurl : '')?>" hreflang="en-in" />
<!-- Favicon -->
<link rel="shortcut icon" href="wp-content/uploads/2023/02/site-icon.svg" />
<!-- Plugins CSS -->
<link rel="stylesheet" href="assets/css/plugins.css" />
<!-- Main Style CSS -->
<link rel="stylesheet" href="assets/css/style.css" />
<link rel="stylesheet" href="assets/css/responsive.css" />
</head>

    <body class="template-product shop-left-sidebar"><!-- shop-listing  -->
        <!-- Page Loader -->
        <div id="pre-loader"><img src="assets/images/loader.gif" alt="Loading..." /></div>
        <!-- End Page Loader -->

        <!--Page Wrapper-->
        <div class="page-wrapper">
            <!--Header-->
            <div id="header" class="header">
                <div class="header-main">
                    <?php include("includes/top.php");?>
                    <!--Main Navigation Desktop-->
                    <div class="menu-outer">
                        <nav class="container-fluid">
                            <div class="row">
                                <div class="col-1 col-sm-12 col-md-12 col-lg-12 align-self-center d-menu-col">
                                    <?php include("includes/desktopmenu.php");?>
                                </div>
                            </div>
                        </nav>
                    </div>
                    <!--End Main Navigation Desktop-->
                    <!--Search Popup-->
					<?php include("includes/search-popup.php");?>
                   
                </div>
            </div>
            <!--End Header-->
            <?php include("includes/mobilemenu.php");?>

            <!--Body Container-->
			 <?php 
			 //echo $opt.$pgtype;
			 if($opt=="home"){ 
				include("includes/home.php");
			}else{ 
				if($pgtype=="products"  && $opt!="recently-viewed" && $opt!="most-viewed"){ //&& $opt!="search"
					include("includes/productlist.php");
				}else if($opt=="search" || $opt=="recently-viewed" || $opt=="most-viewed"){
					include("includes/productlist.php");
				}else if($pgtype=="product" && $prodid>0){
					include("includes/product.php");
				}else if($pgtype=="blog-post" &&  $pageid>0){
					include("includes/blogpage.php");
				}else if($pgtype=="blog-post" ||  $opt=="blog-post"){
					$iscarturl="blog-post";
					$iscart=1;
					include("includes/bloglist.php");
				}else if($opt=="video-post" &&  $_GET['videoid']!=''){
					include("includes/videopage.php");
				}else if($opt=="video-post"){
					include("includes/videolist.php");
				}else if($opt=="gallery"){
					include("includes/gallery.php");
				}else if($opt=="shopping-cart"){
					include("includes/cart.php");
				}else if($opt=="order"){
					include("includes/checkout.php");
				}else if($opt=="success"){
					include("includes/success.php");
				}else if($opt=="register"){
					include("includes/register.php");
				}else if($opt=="login"){
					include("includes/login.php");
				}else if($opt=="reviews"){
					include("includes/reviews.php");
				}else if($opt=="myaccount" || $opt=="wishlist"){
					include("includes/myaccount.php");
				}else{
					include("includes/content.php");
				}
			}
			?>
            <!--End Body Container-->

            <!--Footer-->
            <div class="footer footer-1">
                <div class="footer-top clearfix">
                    <div class="container">
						<!-- 
						<div class="row">
                             <div class="col-12 col-sm-12 col-md-4 col-lg-4 text-center about-col">
                                <img src="wp-content/uploads/2023/07/edge-main-logo.png" alt="Our Clinical Footcare Brand" class="mb-3"/>
                            </div>
							 <div class="col-12 col-sm-12 col-md-4 col-lg-4 text-center about-col">
                                <img src="wp-content/uploads/2022/07/25-year-logo-by-vishal-1-1536x361.png" alt="Orthofit Healthcare Pvt Ltd" class="mb-3"/>
                            </div>
							 <div class="col-12 col-sm-12 col-md-4 col-lg-4 text-center about-col">
                                <img src="wp-content/uploads/2022/05/triplanar-logo.svg" alt="Triplanar Motion Control" class="mb-3 col-lg-9"/>
                            </div>
                        </div>
						-->
						<div class="row">
							<!-- <div class="col-12 col-sm-6 col-md-6 col-lg-6 mb-3 my-sm-3">
								<a class="d-flex clr-none" href="#">
									<img src="assets/images/triplanar-logo.png" style="width:120px;padding-right:10px;" border="0" alt="STEP 01">
									<div class="detail">
										<p class="sub-text mt-3">Our proprietary Triplanar Motion Control&trade; helps improve posture and alleviate foot pains like plantar fasciitis whilst providing support to the arch of the foot</p>
									</div>
								</a>
							</div> -->
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 text-center about-col mb-4">
								<!--  class="d-flex clr-none" --><a href="/"> 
									<img src="assets/images/logo/dredge2026new.png" style="width:200px;padding-right:10px;" border="0" alt="Dr EDGE Our Clinical Footcare Brand">
									<div class="detail">
										<h5 class="fs-6 text-uppercase mt-3">Our Clinical Footcare Brand</h5>
									</div>
								</a>
							</div>

						</div>
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12 text-center about-col mb-4">
                                <img src="assets/images/logo/website2026.png" alt="Orthofit" class="mb-3"/>
                                <p>Orthofit Healthcare Pvt Ltd, 9th Floor, Mahalaxmi Chambers, Bhulabhai Desai Road (Warden Road) Mumbai 400026</p>
                                <p class="mb-0 mb-md-3">Phone: <a href="tel:+918454920321">84549 20321  |  L : 022-23529230 / 022-23518860</a> <span class="mx-1">|</span> Email: <a href="mailto:dr.edge@orthofit.in">dr.edge@orthofit.in</a></p>
								<p><strong>Orthofit Clinic has developed a unique approach where certified therapists conduct a detailed foot, ankle and lower limb biomechanics evaluation and scrutinize static and dynamic conditions.</strong></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-sm-12 col-md-4 col-lg-3 footer-links">
                                <h4 class="h4">My Account</h4>
                                <ul>
                                    <li><a href="myaccount">My Account</a></li>
                                    <li><a href="login">Login</a></li>
                                    <li><a href="myaccount">Order History</a></li>
                                    <li><a href="myaccount?task=wishlist">Wishlist</a></li>
                                    <li><a href="shopping-cart">Shopping Cart</a></li>
                                </ul>
                            </div>
                            <div class="col-12 col-sm-12 col-md-4 col-lg-2 footer-links">
                                <h4 class="h4">Quick Shop</h4>
                                <ul>
                                    <li><a href="new-arrivals">New Arrivals</a></li>
                                    <li><a href="women">Women</a></li>
                                    <li><a href="men">Men</a></li>
                                    <li><a href="orthotic-insoles">Insoles</a></li>
                                    <li><a href="accessories">Accessories</a></li>
                                </ul>
                            </div>
                            <div class="col-12 col-sm-12 col-md-4 col-lg-3 footer-links">
                                <h4 class="h4">Customer Services</h4>
                                <ul>
                                    <li><a href="contactus">Contact Us</a></li>
                                    <li><a href="shipping-information">Shipping Information</a></li>
                                    <li><a href="privacy-policy">Privacy Policy</a></li>
                                    <li><a href="returns-exchanges">Returns and Exchanges</a></li>
                                    <li><a href="terms-conditions">Terms and Conditions</a></li>
                                </ul>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-4 newsletter-col">
                                <?php if($_GET['newsmsg']!=''){?>
								<div class="alert alert-success py-2 rounded-1 alert-dismissible fade show cart-alert" role="alert">
									<i class="align-middle icon an an-envelope-l icon-large me-2"></i><?php echo dbval($_GET['newsmsg'])?>
									<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
								</div>
								<?php }?>
								<div class="display-table pt-md-3 pt-lg-0">
                                    <div class="display-table-cell footer-newsletter">
                                        <form action="" method="get"><a name="newsemail"></a>
                                            <label class="h4">STAY CONNECTED</label>
                                            <p>Be the first to know about sales, special promotions, tips, trends and more.</p>
                                            <div class="input-group">
												<input type="hidden" name="newsemailflag" value="1">
                                                <input type="email" class="brounded-start input-group__field newsletter-input mb-0" name="newsemail" value="" placeholder="Email address" required>
                                                <span class="input-group__btn">
                                                    <button type="submit" class="btn newsletter__submit rounded-end" name="commit" id="Subscribe"><i class="an an-envelope-l"></i></button>
                                                </span>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <ul class="list-inline social-icons mt-3 pt-1">
                                    <li class="list-inline-item"><a href="https://www.facebook.com/orthofitclinic" target="_blank" data-bs-toggle="tooltip" data-bs-placement="top" title="Facebook"><i class="an an-facebook" aria-hidden="true"></i></a></li>
                                    <li class="list-inline-item"><a href="https://twitter.com/orthofitclinic"  target="_blank" data-bs-toggle="tooltip" data-bs-placement="top" title="Twitter"><i class="an an-twitter" aria-hidden="true"></i></a></li>
                                    <!-- <li class="list-inline-item"><a href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="Pinterest"><i class="an an-pinterest-p" aria-hidden="true"></i></a></li> -->
                                    <li class="list-inline-item"><a href="https://www.instagram.com/orthofitclinic/"  target="_blank" data-bs-toggle="tooltip" data-bs-placement="top" title="Instagram"><i class="an an-instagram" aria-hidden="true"></i></a></li>
                                    <!-- <li class="list-inline-item"><a href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="TikTok"><i class="an an-tiktok" aria-hidden="true"></i></a></li> -->
									<li class="list-inline-item"><a href="https://www.linkedin.com/in/orthofit-clinic-85692815b/"  target="_blank" data-bs-toggle="tooltip" data-bs-placement="top" title="LinkedIn"><i class="an an-linkedin" aria-hidden="true"></i></a></li>
									<li class="list-inline-item"><a href="https://www.youtube.com/@orthofitclinic1"  target="_blank" data-bs-toggle="tooltip" data-bs-placement="top" title="YouTube"><i class="an an-youtube-s2" aria-hidden="true"></i></li>
                                    <li class="list-inline-item"><a href="https://api.whatsapp.com/send/?phone=918454920321&text&type=phone_number&app_absent=0"  target="_blank" data-bs-toggle="tooltip" data-bs-placement="top" title="Whatsapp"><i class="an an-whatsapp" aria-hidden="true"></i></a></li>
									<li class="list-inline-item"><a href="https://g.page/r/CZIcog52j1xLEB0?entry=ttu"  target="_blank" data-bs-toggle="tooltip" data-bs-placement="top" title="Google Reviews"><i class="an an-google" aria-hidden="true"></i></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="footer-bottom clearfix">
                    <div class="container">
                        <div class="d-flex-center flex-column justify-content-md-between flex-md-row-reverse">
                            <img src="assets/images/safepayment.jpg" alt="Payments"/>
                            <div class="copytext text-uppercase">&copy; <?php echo date("Y")?> Orthofitmart Powerd by Orthofit Clinic. All Rights Reserved.</div>
                        </div>
                    </div>
                </div>
            </div>
            <!--End Footer-->

            <!--Scoll Top-->
			<div id="site-call"><a href="tel:+918454920321"><img src="assets/images/phone.png"></a></div>
			<div id="site-callw"><a target="_blank" href="https://api.whatsapp.com/send?phone=+918454920321"><img src="assets/images/whatsapp.png"></a></div>
            <span id="site-scroll"><i class="icon an an-chevron-up"></i></span>
            <!--End Scoll Top-->

            <!--MiniCart Drawer-->
           <?php include("includes/minicart-drawer.php");?>
            <!--End MiniCart Drawer-->
            <div class="modalOverly"></div>

            <!--Quickview Popup-->
            <?php //include("includes/quickview-popup.php");?>
            <!--End Quickview Popup-->

            <!--Addtocart Added Popup-->
            <?php include("includes/addtocart-popup.php");?>
            <!-- End Addtocart Added Popup-->
			<?php if($opt=="home"){ ?>
			<!--Newsletter Popup-->
           <?php include("includes/newsletter-popup.php");?>
            <!--End Newsletter Popup-->
			<?php }?>
            <!-- Including Jquery -->
            <script src="assets/js/vendor/jquery-min.js"></script>
            <script src="assets/js/vendor/js.cookie.js"></script>
            <!--Including Javascript-->
            <script src="assets/js/plugins.js"></script>
            <script src="assets/js/main.js"></script>

			<?php if($opt=="home"){ ?>
			<!--Newsletter Popup Cookies-->
            <script>
                function newsletter_popup() {
                    var cookieSignup = "cookieSignup", date = new Date();
                    if ($.cookie(cookieSignup) != 'true' && window.location.href.indexOf("challenge#newsletter-modal") <= -1) {
                        setTimeout(function () {
                            $.magnificPopup.open({
                                items: {
                                    src: '#newsletter-modal'
                                }
                                , type: 'inline', removalDelay: 300, mainClass: 'mfp-zoom-in'
                            }
                            );
                        }
                        , 5000);
                    }
                    $.magnificPopup.instance.close = function () {
                        if ($("#dontshow").prop("checked") == true) {
                            $.cookie(cookieSignup, 'true', {
                                expires: 1, path: '/'
                            }
                            );
                        }
                        $.magnificPopup.proto.close.call(this);
                    }
                }
                //newsletter_popup();
            </script>
            <!--End Newsletter Popup Cookies-->
			<?php }
			if($pgtype=="products"){ ?>
			<script>
			function price_slider() {
				$("#slider-range").slider({
					range: true,
					min: 100,
					max: 10000,
					values: [2000, 6000],
					slide: function (event, ui) {
						$("#amount").val(ui.values[0] + "-" + ui.values[1]);
					}
				});
				$("#amount").val($("#slider-range").slider("values", 0) +
						"-" + $("#slider-range").slider("values", 1));
			}
			price_slider();
			</script>
			<?php }
			if($opt=='gallery'){?>
			 <!-- Photoswipe Gallery -->
            <script src="assets/js/vendor/photoswipe.min.js"></script>
            <script>
                $(function () {
                    var $pswp = $('.pswp')[0],
                            image = [],
                            getItems = function () {
                                var items = [];
                                $('a.zoom').each(function () {
                                    var $href = $(this).attr('href'),
                                            $size = $(this).data('size').split('x'),
                                            item = {src: $href, w: $size[0], h: $size[1]};
                                    items.push(item);
                                });
                                return items;
                            };
                    var items = getItems();
                    $('.zoom').click(function (event) {
                        event.preventDefault();
                        var $index = $(this).parents(".grid-lookbook").index();
                        $index = $index;
                        var options = {
                            index: $index,
                            bgOpacity: .6,
                            showHideOpacity: true
                        };
                        var lightBox = new PhotoSwipe($pswp, PhotoSwipeUI_Default, items, options);
                        lightBox.init();
                    });
                });
            </script>
            <div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="pswp__bg"></div>
                <div class="pswp__scroll-wrap">
                    <div class="pswp__container">
                        <div class="pswp__item"></div>
                        <div class="pswp__item"></div>
                        <div class="pswp__item"></div>
                    </div>
                    <div class="pswp__ui pswp__ui--hidden">
                        <div class="pswp__top-bar">
                            <div class="pswp__counter"></div>
                            <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>
                            <button class="pswp__button pswp__button--share" title="Share"></button>
                            <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>
                            <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>
                            <div class="pswp__preloader">
                                <div class="pswp__preloader__icn">
                                    <div class="pswp__preloader__cut">
                                        <div class="pswp__preloader__donut"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                            <div class="pswp__share-tooltip"></div>
                        </div>
                        <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)"></button>
                        <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)"></button>
                        <div class="pswp__caption"><div class="pswp__caption__center"></div></div>
                    </div>
                </div>
            </div>
			<?php
			}
			if($pgtype=="product"){?>
			<!-- Photoswipe Gallery -->
            <script src="assets/js/vendor/photoswipe.min.js"></script>
            <script>
                $(function () {
                    var $pswp = $('.pswp')[0],
                            image = [],
                            getItems = function () {
                                var items = [];
                                $('.lightboximages a').each(function () {
                                    var $href = $(this).attr('href'),
                                            $size = $(this).data('size').split('x'),
                                            item = {
                                                src: $href,
                                                w: $size[0],
                                                h: $size[1]
                                            };
                                    items.push(item);
                                });
                                return items;
                            };
                    var items = getItems();

                    $.each(items, function (index, value) {
                        image[index] = new Image();
                        image[index].src = value['src'];
                    });
                    $('.prlightbox').on('click', function (event) {
                        event.preventDefault();

                        var $index = $(".active-thumb").parent().attr('data-slick-index');
                        $index++;
                        $index = $index - 1;

                        var options = {
                            index: $index,
                            bgOpacity: 0.7,
                            showHideOpacity: true
                        };
                        var lightBox = new PhotoSwipe($pswp, PhotoSwipeUI_Default, items, options);
                        lightBox.init();
                    });
                });
            </script>
            <div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="pswp__bg"></div>
                <div class="pswp__scroll-wrap">
                    <div class="pswp__container">
                        <div class="pswp__item"></div>
                        <div class="pswp__item"></div>
                        <div class="pswp__item"></div>
                    </div>
                    <div class="pswp__ui pswp__ui--hidden">
                        <div class="pswp__top-bar">
                            <div class="pswp__counter"></div>
                            <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>
                            <button class="pswp__button pswp__button--share" title="Share"></button>
                            <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>
                            <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>
                            <div class="pswp__preloader">
                                <div class="pswp__preloader__icn">
                                    <div class="pswp__preloader__cut">
                                        <div class="pswp__preloader__donut"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                            <div class="pswp__share-tooltip"></div>
                        </div>
                        <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)"></button>
                        <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)"></button>
                        <div class="pswp__caption"><div class="pswp__caption__center"></div></div>
                    </div>
                </div>
            </div>
			<?php }else if($opt=="login"){?>
			<script>
			/* Login Form */
                $('.back-to-login').on('click', function (e) {
                    $(".user-form-login").toggleClass("login-active");
                    $(".user-form-forgot").removeClass("forgot-active");
                    $(".user-form-signup,.login-inner").removeClass("signup-active");
                    $(".user-registered").removeClass("registered-active");
                    $(".use-logined").removeClass("logined-active");
                    $(".use-forgoted").removeClass("forgoted-active");
                    e.preventDefault();
                });
                $(".forgotpass-link").on('click', function (e) {
                    $(".user-form-forgot").toggleClass("forgot-active");
                    $(".user-form-login").removeClass("login-active");
                    $(".user-form-signup,.login-inner").removeClass("signup-active");
                    $(".user-registered").removeClass("registered-active");
                    $(".use-logined").removeClass("logined-active");
                    $(".use-forgoted").removeClass("forgoted-active");
                    e.preventDefault();
                });
                $(".signup-link").on('click', function (e) {
                    $(".user-form-signup,.login-inner").toggleClass("signup-active");
                    $(".user-form-login").removeClass("login-active");
                    $(".user-form-forgot").removeClass("forgot-active");
                    $(".user-registered").removeClass("registered-active");
                    $(".use-logined").removeClass("logined-active");
                    $(".use-forgoted").removeClass("forgoted-active");
                    e.preventDefault();
                });
                $(".register-link").on('click', function (e) {
                    $(".user-registered").toggleClass("registered-active");
                    $(".check").toggleClass("checked");
                    $(".use-forgoted .check").removeClass("checked");
                    $(".user-form-login").removeClass("login-active");
                    $(".user-form-forgot").removeClass("forgot-active");
                    $(".user-form-signup,.login-inner").removeClass("signup-active");
                    $(".use-logined").removeClass("logined-active");
                    $(".use-forgoted").removeClass("forgoted-active");
                    e.preventDefault();
                });
                $(".signin-link").on('click', function (e) {
                    $(".use-logined").toggleClass("logined-active");
                    $(".user-form-login").removeClass("login-active");
                    $(".user-form-forgot").removeClass("forgot-active");
                    $(".user-registered").removeClass("registered-active");
                    $(".use-forgoted").removeClass("forgoted-active");
                    e.preventDefault();
                });
                $(".forgoted-link").on('click', function (e) {
                    $(".use-forgoted").toggleClass("forgoted-active");
                    $(".check").toggleClass("checked");
                    $(".user-registered .check").removeClass("checked");
                    $(".user-form-login").removeClass("login-active");
                    $(".user-form-forgot").removeClass("forgot-active");
                    $(".user-registered").removeClass("registered-active");
                    e.preventDefault();
                });
				</script>
				<?php }
				if($pgtype=="products" || $pgtype=="product"){?>
				<script>
				function addtowishbtn(z,y,pc,pn,pp,pcol,cid,cn){
					//var z = $(this).attr("prodid");
					var u = <?php echo ($_SESSION["compid"]>0 ? $_SESSION["compid"] : '0')?>;
					var x = '<?php echo session_id()?>';
					var dataString = 'prodid='+ z + '&userid='+ u + '&sessid='+ x;
					if(z>0){
						$.ajax({
						type: "POST",
						url: "includes/wishsubmit.php",
						data: dataString,
						cache: false,
						success: function(result){
							//alert(result);
							if(result==1){
								//$("#wish"+z).addClass("wishlist added");
								$("#wish"+z).css('color', '#ff0000');
								showmsg("Wishlist updated successfully!");

								/*dataLayer.push({ ecommerce: null });  
								dataLayer.push({
								  event: "add_to_wishlist",
								  ecommerce: {
									currency: "<?php echo ($_SESSION['myCUR']=='US $' ? 'USD' : 'INR')?>",
									value: pp,
									items: [
									{
									  item_id: pc,
									  item_name: pn,
									  affiliation: "PAYALSINGHAL",
									  index: 0,
									  item_brand: "PAYALSINGHAL",
									  item_category: cn,
									  item_list_id: "products_"+cid,
									  item_list_name: cn,
									  item_variant: pcol,
									  price: pp,
									  quantity: 1
									}
									]
								  }
								});*/

							}else if(result==3){
								document.location.href='login?errmsg=Please login to add more products to your wishlist!';
							}else if(result==0){
								//$("#wish"+z).removeClass("added");
								$("#wish"+z).css('color', '#222222');
								showmsg("Wishlist updated successfully!");

								/*dataLayer.push({ ecommerce: null });  
								dataLayer.push({
								  event: "remove_from_wishlist",
								  ecommerce: {
									currency: "<?php echo ($_SESSION['myCUR']=='US $' ? 'USD' : 'INR')?>",
									value: pp,
									items: [
									{
									  item_id: pc,
									  item_name: pn,
									  affiliation: "PAYALSINGHAL",
									  index: 0,
									  item_brand: "PAYALSINGHAL",
									  item_category: cn,
									  item_list_id: "products_"+cid,
									  item_list_name: cn,
									  item_variant: pcol,
									  price: pp,
									  quantity: 1
									}
									]
								  }
								});*/
							}
						}
						});
					}else{
						showmsg('Please register/login to continue!');
						
					}
					//return false;
				}
				</script>
				<?php }?>
				<script>
				function showmsg(msg){
					 $("#errmsg").removeClass("hide");
					 $("#errmsgtxt").text(msg);
				}
				</script>
        </div>
        <!--End Page Wrapper-->
    </body>
</html>