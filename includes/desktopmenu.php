<!--Desktop Menu-->
<nav class="grid__item" id="AccessibleNav">
	<ul id="siteNav" class="site-nav medium center hidearrow">
		<li class="lvl1 parent dropdown"><a href="aboutus">Why Dr. Edge <i class="an an-angle-down-l"></i></a>
			<ul class="dropdown">
				<li><a href="pain-syndromes" class="site-nav">Pain Syndromes</a></li>
				<li><a href="triplanar-technology" class="site-nav">Triplanar Technology</a></li>
				<li><a href="aboutus" class="site-nav">About Us</a></li>
			</ul>
		</li>
		<li class="lvl1 parent dropdown"><a href="orthotic-footwear-introduction">Orthotic Footwear<i class="an an-angle-down-l"></i></a>
			<ul class="dropdown">
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
		<li class="lvl1 parent dropdown"><a href="orthotic-insoles-introduction">Orthotic Insoles<i class="an an-angle-down-l"></i></a>
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
		<li class="lvl1 parent dropdown"><a href="diabetic-medical-pedicure">Treatment</a>
			<ul class="dropdown">
				<li><a href="diabetic-medical-pedicure" class="site-nav">Diabetic Medical Pedicure</a></li>
				<li><a href="medical-pedicure" class="site-nav">Medical Pedicure</a></li>
				<li><a href="laser-treatment" class="site-nav">Laser Treatment</a></li>
			</ul>
		</li>
		<li class="lvl1 parent dropdown"><a href="footcare-products">Accessories</a>
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
		<li class="lvl1 parent dropdown"><a href="custom-orthotic-theory">Resources<i class="an an-plus-l"></i></a>
			<ul class="dropdown">
			<li><a href="custom-orthotic-theory" class="site-nav">Custom Orthotic Theory</a></li>
			<li><a href="how-to-dispense-custom-orthotics" class="site-nav">How to dispense Custom Orthotics</a></li>
			<li><a href="limb-exercises" class="site-nav">Lower Limb Exercises</a></li>
			<li><a href="training-ppt" class="site-nav">Training PPT</a></li>
			<li><a href="training-video" class="site-nav">Training Video</a></li>
			</ul>
		</li>
		<li class="lvl1 parent dropdown"><a href="blog-post">Blog <i class="an an-angle-down-l"></i></a>
			<ul class="dropdown">
				<li><a href="blog-post" class="site-nav">Articles</a></li>
				<li><a href="video-post" class="site-nav">Videos</a></li>
				<!-- <li><a href="podcast-post" class="site-nav">Podcast</a></li> -->
			</ul>
		</li>
		<li class="lvl1 parent dropdown"><a href="gallery">Gallery <i class="an an-angle-down-l"></i></a>
			<ul class="dropdown">
				<li><a href="gallery" class="site-nav">Photos</a></li>
				<li><a href="video-post?view=product" class="site-nav">Videos</a></li>
			</ul>
		</li>
	</ul>
</nav>
<!--End Desktop Menu-->