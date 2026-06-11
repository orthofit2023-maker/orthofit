<!--Mobile Menu-->
<div class="mobile-nav-wrapper" role="navigation">
	<div class="closemobileMenu"><i class="icon an an-times-l pull-right"></i> Close Menu</div>
	<ul id="MobileNav" class="mobile-nav">
		<li class="lvl1 parent megamenu"><a href="aboutus">Why Dr. Edge <i class="an an-plus-l"></i></a>
			<ul>
				<li><a href="triplanar-technology" class="site-nav">Triplanar Technology</a></li>
				<li><a href="pain-syndromes" class="site-nav">Pain Syndromes</a></li>
				<li><a href="aboutus" class="site-nav">About Us</a></li>
			</ul>
		</li>
		<li class="lvl1 parent megamenu"><a href="new-arrivals">Orthotic Footwear<i class="an an-plus-l"></i></a>
			<ul>
				<?php
				$sql="select typeid, typename, typevalue from ccd9types where opt='2' and typevalue1='1' order by typevalue2"; // order by sortby
				$result = $mysqli->query($sql);
				while($rowtype = $result->fetch_array()){
					echo '<li><a href="'.$rowtype['typevalue'].'" class="site-nav">'.$rowtype['typename'].'</a></li>';
				}
				?>
			</ul>
		</li>
		<li class="lvl1"><a href="orthotic-insoles">Orthotic Insoles</a></li>
		<li class="lvl1 parent megamenu"><a href="footcare-products">Accessories<i class="an an-plus-l"></i></a>
			<ul class="dropdown">
					<?php
					$sql="select typeid, typename, typevalue from ccd9types where opt='2' and typevalue1='29' order by typevalue2"; // order by sortby
					$result = $mysqli->query($sql);
					while($rowtype = $result->fetch_array()){
						echo '<li><a href="'.$rowtype['typevalue'].'" class="site-nav">'.$rowtype['typename'].'</a></li>';
					}
					?>
			</ul>
		</li>
		<li class="lvl1 parent megamenu"><a href="blog-post">Blog <i class="an an-plus-l"></i></a>
			<ul class="dropdown">
				<li><a href="blog-post" class="site-nav">Article's</a></li>
				<li><a href="video-post" class="site-nav">Video's</a></li>
				<!-- <li><a href="podcast-post" class="site-nav">Podcast</a></li> -->
			</ul>
		</li>
		<li class="lvl1 parent megamenu"><a href="video-post">Testimony <i class="an an-angle-down-l"></i></a>
			<ul class="dropdown">
				<li><a href="video-post" class="site-nav">Video</a></li>
				<!-- <li><a href="reviews" class="site-nav">Reviews</a></li> -->
			</ul>
		</li>
		<!-- <li class="lvl1"><a href="https://orthofit.in/"  target="_blank">Clinical Practices</a></li> -->
		<li class="lvl1 parent megamenu"><a href="gallery">Photo Gallery <i class="an an-plus-l"></i></a>
			<ul class="dropdown">
				<li><a href="gallery" class="site-nav">Photos</a></li>
				<li><a href="video-post?view=product" class="site-nav">Video's</a></li>
			</ul>
		</li>
		<li class="acLink"></li>
		<?php if($_SESSION['compid']>0){?>
		<li class="lvl1 bottom-link"><a href="myaccount">My Account</a></li>
		<?php }else{ ?>
		<li class="lvl1 bottom-link"><a href="login">Login</a></li>
		<li class="lvl1 bottom-link"><a href="login">Signup</a></li>
		<li class="lvl1 bottom-link"><a href="myaccount?task=wishlist">Wishlist</a></li>
		<?php }?>
		<li class="help bottom-link"><b>NEED HELP?</b><br>Call: 84549 20321 </li>
	</ul>
</div>
<!--End Mobile Menu-->