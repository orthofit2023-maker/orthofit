<header class="header-wrap container d-flex align-items-center">
	<div class="row g-0 align-items-center w-100">
		<!--Social Icons-->
		<div class="col-4 col-sm-4 col-md-4 col-lg-4 d-none d-lg-block">
			<ul class="social-icons list-inline">
				<li class="list-inline-item"><a href="https://www.facebook.com/orthofitclinic" target="_blank"><i class="an an-facebook" aria-hidden="true"></i><span class="tooltip-label">Facebook</span></a></li>
				<li class="list-inline-item"><a href="https://twitter.com/orthofitclinic"  target="_blank"><i class="an an-twitter" aria-hidden="true"></i><span class="tooltip-label">Twitter</span></a></li>
				<!-- <li class="list-inline-item"><a href="#"><i class="an an-pinterest-p" aria-hidden="true"></i><span class="tooltip-label">Pinterest</span></a></li> -->
				<li class="list-inline-item"><a href="https://www.instagram.com/orthofitclinic/"  target="_blank"><i class="an an-instagram" aria-hidden="true"></i><span class="tooltip-label">Instagram</span></a></li>
				<li class="list-inline-item"><a href="https://www.linkedin.com/in/orthofit-clinic-85692815b/"  target="_blank"><i class="an an-linkedin" aria-hidden="true"></i><span class="tooltip-label">LinkedIn</span></a></li>
				<li class="list-inline-item"><a href="https://www.youtube.com/@orthofitclinic1"  target="_blank"><i class="an an-youtube-s2" aria-hidden="true"></i><span class="tooltip-label">YouTube</span></a></li>
				<li class="list-inline-item"><a href="https://api.whatsapp.com/send/?phone=918454920321&text&type=phone_number&app_absent=0"  target="_blank"><i class="an an-whatsapp" aria-hidden="true"></i><span class="tooltip-label">Whatsapp</span></a></li>
				<li class="list-inline-item"><a href="https://g.page/r/CZIcog52j1xLEB0?entry=ttu"  target="_blank"><i class="an an-google" aria-hidden="true"></i><span class="tooltip-label">Google Reviews</span></a></li>
			</ul>
		</div>
		<!--End Social Icons-->
		<!--Logo / Menu Toggle-->
		<div class="col-6 col-sm-6 col-md-6 col-lg-4 d-flex">
			<!--Mobile Toggle-->
			<button type="button" class="btn--link site-header__menu js-mobile-nav-toggle mobile-nav--open me-3 d-lg-none"><i class="icon an an-times-l"></i><i class="icon an an-bars-l"></i></button>
			<!--End Mobile Toggle-->
			<!--Logo-->
			<!-- <div class="logo mx-lg-auto"><a href="/"><img class="logo-img" src="assets/images/logo/website2026.png" alt="OrthoFitMart - Orthotic Footwear for Heel Pain &amp; Knee Pain" title="OrthoFitMart - Orthotic Footwear for Heel Pain &amp; Knee Pain" /></a>
			</div> -->
			<div class="logo mx-lg-auto"><a href="/"><img class="logo-img" src="assets/images/logo/dredge2026new.png" alt="Dr Edge - Our Clinical Footcare Brand" title="Dr Edge - Our Clinical Footcare Brand" /></a></div>
			<!--End Logo-->
		</div>
		<!-- <div class="col-1 col-sm-1 col-md-1 col-lg-2 d-flex">
			
		</div> -->
		<!--End Logo / Menu Toggle-->
		<!--Right Action-->
		<div class="col-6 col-sm-6 col-md-6 col-lg-4 icons-col text-right d-flex justify-content-end">
			<!--Search-->
			<div class="site-search iconset"><i class="icon an an-search-l"></i><span class="tooltip-label">Search</span></div>
			<!--End Search-->
			<!--Wishlist-->
			<div class="wishlist-link iconset">
				<a href="myaccount?task=wishlist"><i class="icon an an-heart-l"></i><span class="wishlist-count counter d-flex-center justify-content-center position-absolute translate-middle rounded-circle"><?php echo $wishcnt?></span><span class="tooltip-label">Wishlist</span></a>
			</div>
			<!--End Wishlist-->
			<!--Setting Dropdown-->
			<div class="user-link iconset"><i class="icon an an-user-expand"></i><span class="tooltip-label">My Account</span></div>
			<div id="userLinks">
				<ul class="user-links">
					<?php if($_SESSION['compid']>0){?>
					<li><a href="myaccount">My Account</a></li>
					<li><a href="myaccount?task=wishlist">Wishlist</a></li>
					<?php }else{ ?>
					<li><a href="login">Login</a></li>
					<li><a href="register">Sign Up</a></li>
					<li><a href="myaccount">Wishlist</a></li>
					<?php }?>
				</ul>
			</div>
			<!--End Setting Dropdown-->
			<!--Minicart Drawer-->
			<div class="header-cart iconset">
				<a href="" class="site-header__cart btn-minicart" data-bs-toggle="modal" data-bs-target="#minicart-drawer">
					<i class="icon an an-sq-bag"></i><span class="site-cart-count counter d-flex-center justify-content-center position-absolute translate-middle rounded-circle"><?php echo $numcart?></span><span class="tooltip-label">Cart</span>
				</a>
			</div>
			<!--End Minicart Drawer-->
			<!--Setting Dropdown-->
			<!-- <div class="setting-link iconset pe-0"><i class="icon an an-right-bar-s"></i><span class="tooltip-label">Settings</span></div>
			<div id="settingsBox">
				<div class="currency-picker">
					<span class="ttl">Select Currency</span>
					<ul id="currencies" class="cnrLangList">
						<li class="selected"><a href="#;" class="active">INR</a></li><li><a href="#;">GBP</a></li><li><a href="#;">CAD</a></li><li><a href="#;">USD</a></li><li><a href="#;">AUD</a></li><li><a href="#;">EUR</a></li><li><a href="#;">JPY</a></li>
					</ul>
				</div>
				<div class="language-picker">
					<span class="ttl">SELECT LANGUAGE</span>
					<ul id="language" class="cnrLangList">
						<li><a href="#" class="active">English</a></li><li><a href="#">French</a></li><li><a href="#">German</a></li>
					</ul>
				</div>
			</div> -->
			<!--End Setting Dropdown-->
		</div>
		<!--End Right Action-->
	</div>
</header>