<!--Desktop Menu-->
<nav class="grid__item" id="AccessibleNav">
	<ul id="siteNav" class="site-nav medium center hidearrow">
		<li class="lvl1 parent dropdown"><a href="aboutus">Why Dr. Edge <i class="an an-angle-down-l"></i></a>
			<ul class="dropdown">
				<li><a href="triplanar-technology" class="site-nav">Triplanar Technology</a></li>
				<li><a href="pain-syndromes" class="site-nav">Pain Syndromes</a></li>
				<li><a href="aboutus" class="site-nav">About Us</a></li>
			</ul>
		</li>
		<li class="lvl1 parent dropdown"><a href="new-arrivals">Orthotic Footwear<i class="an an-angle-down-l"></i></a>
			<ul class="dropdown">
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
		<li class="lvl1 parent dropdown"><a href="footcare-products">Accessories</a>
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
		<li class="lvl1 parent dropdown"><a href="blog-post">Blog <i class="an an-angle-down-l"></i></a>
			<ul class="dropdown">
				<li><a href="blog-post" class="site-nav">Articles</a></li>
				<li><a href="video-post" class="site-nav">Videos</a></li>
				<!-- <li><a href="podcast-post" class="site-nav">Podcast</a></li> -->
			</ul>
		</li>
		<li class="lvl1 parent dropdown"><a href="video-post">Testimony <i class="an an-angle-down-l"></i></a>
			<ul class="dropdown">
				<li><a href="video-post" class="site-nav">Video</a></li>
				<!-- <li><a href="reviews" class="site-nav">Reviews</a></li> -->
			</ul>
		</li>
		<!-- <li class="lvl1"><a href="https://orthofit.in/"  target="_blank">Clinical Practices</a></li> -->
		<li class="lvl1 parent dropdown"><a href="gallery">Product  Gallery <i class="an an-angle-down-l"></i></a>
			<ul class="dropdown">
				<li><a href="gallery" class="site-nav">Photos</a></li>
				<li><a href="video-post?view=product" class="site-nav">Videos</a></li>
			</ul>
		</li>
	</ul>
</nav>
<!--End Desktop Menu-->