<!--Mobile Menu-->
<div class="mobile-nav-wrapper" role="navigation">
	<div class="closemobileMenu"><i class="icon an an-times-l pull-right"></i> Close Menu</div>
	<ul id="MobileNav" class="mobile-nav">
		<li class="lvl1 parent megamenu"><a href="aboutus">Why Dr. Edge <i class="an an-plus-l"></i></a>
			<ul>
				<li><a href="pain-syndromes" class="site-nav">Pain Syndromes</a></li>
				<li><a href="triplanar-technology" class="site-nav">Triplanar Technology</a></li>
				<li><a href="aboutus" class="site-nav">About Us</a></li>
			</ul>
		</li>
		<li class="lvl1 parent megamenu"><a href="orthotic-footwear-introduction">Orthotic Footwear<i class="an an-plus-l"></i></a>
			<ul>
				<li><a href="orthotic-footwear-introduction" class="site-nav">Introduction</a></li>
				<?php
				$sql="select typeid, typename, typevalue from ccd9types where opt='2' and typevalue1='1' order by typevalue2"; // order by sortby
				$result = $mysqli->query($sql);
				while($rowtype = $result->fetch_array()){
					echo '<li><a href="'.$rowtype['typevalue'].'" class="site-nav">'.$rowtype['typename'].'</a></li>';
				}
				?>
			</ul>
		</li>
		<li class="lvl1 parent megamenu"><a href="orthotic-insoles">Orthotic Insoles<i class="an an-plus-l"></i></a>
			<ul class="dropdown">
					<li><a href="orthotic-insoles-introduction" class="site-nav">Introduction</a></li>
					<?php
					$sql="select typeid, typename, typevalue from ccd9types where opt='2' and typevalue1='32' order by typevalue2"; // order by sortby
					$result = $mysqli->query($sql);
					while($rowtype = $result->fetch_array()){
						echo '<li><a href="'.$rowtype['typevalue'].'" class="site-nav">'.$rowtype['typename'].'</a></li>';
					}
					?>
			</ul>
		</li>
		<li class="lvl1 parent megamenu"><a href="diabetic-medical-pedicure">Treatment<i class="an an-plus-l"></i></a>
			<ul class="dropdown">
					<li><a href="diabetic-medical-pedicure" class="site-nav">Diabetic Medical Pedicure</a></li>
					<li><a href="medical-pedicure" class="site-nav">Medical Pedicure</a></li>
					<li><a href="laser-treatment" class="site-nav">Laser Treatment</a></li>
			</ul>
		</li>
		
		<li class="lvl1 parent megamenu"><a href="footcare-products">Accessories<i class="an an-plus-l"></i></a>
			<ul class="dropdown">
					<?php
					$sql="select typeid, typename, typevalue from ccd9types where opt='2' and typevalue1='29' order by typevalue2"; // order by sortby
					$result = $mysqli->query($sql);
					while($rowtype = $result->fetch_array()){
						echo '<li><a href="'.$rowtype['typevalue'].'" class="site-nav">'.$rowtype['typename'].'</a></li>';
					}
					?>
					<li><a href="therapy-equipments" class="site-nav">Devices</a></li>
			</ul>
		</li>
		<li class="lvl1 parent megamenu"><a href="custom-orthotic-theory">Resources<i class="an an-plus-l"></i></a>
			<ul class="dropdown">
			<li><a href="custom-orthotic-theory" class="site-nav">Custom Orthotic Theory</a></li>
			<li><a href="how-to-dispense-custom-orthotics" class="site-nav">How to dispense Custom Orthotics</a></li>
			<li><a href="limb-exercises" class="site-nav">Lower Limb Exercises</a></li>
			<li><a href="training-ppt" class="site-nav">Training PPT</a></li>
			<li><a href="training-video" class="site-nav">Training Video</a></li>
			</ul>
		</li>
		<li class="lvl1 parent megamenu"><a href="blog-post">Blog <i class="an an-plus-l"></i></a>
			<ul class="dropdown">
				<li><a href="blog-post" class="site-nav">Article's</a></li>
				<li><a href="video-post" class="site-nav">Video's</a></li>
				<!-- <li><a href="podcast-post" class="site-nav">Podcast</a></li> -->
			</ul>
		</li>
		<li class="lvl1 parent megamenu"><a href="gallery">Gallery <i class="an an-plus-l"></i></a>
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