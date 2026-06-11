
<!--Body Container-->
<div id="page-content">
	<!--Collection Banner-->
	<div class="collection-header">
		<div class="collection-hero">
			<div class="collection-hero__image"></div>
			<div class="collection-hero__title-wrapper container">
				<h1 class="collection-hero__title">Photo Gallery</h1>
				<!-- <div class="breadcrumbs text-uppercase mt-1 mt-lg-2"><a href="index.html" title="Back to the home page">Home</a><span>|</span><span class="fw-bold">Blog Masonry</span></div> -->
			</div>
		</div>
	</div>
	<!--End Collection Banner-->
	<section class="section lookbook-section d-md-block">
	<!--Lookbook Masonary Grid-->
	<div class="container-fluid">
		<div class="grid-lookbook style3 grid-mr-10">
			<div class="grid-masonary lookbook lookbook-page-grid">
				<div class="grid-sizer col-sm-6 col-md-4 col-lg-3 mw-100"></div>
				<div class="row">
					<?php 
					$directory = 'photos'; // Replace with your directory path

					//if (is_dir($directory)) {
						//$files = scandir($directory);
						// Remove '.' and '..' entries
						//$files = array_diff($files, array('.', '..'));
					//foreach ($files as $file) {
						//echo $file . "\n";
					//}
					for($n=1;$n<53;$n++){
					?>
					<div class="col-12 col-sm-6 col-md-4 col-lg-3 cl-item grid-lookbook mw-100">
						<div class="lookbook-item gallery">
							<span class="rounded-circle zoom-img zoom"><i class="icon an an-search-plus"></i></span>
							<a class="zoom" href="<?php echo $directory.'/Footwear '.$n.'.jpg'?>" data-size="1000x1000">
								<img class="blur-up lazyload" data-src="<?php echo $directory.'/Footwear '.$n.'.jpg'?>" src="<?php echo $directory.'/Footwear '.$n.'.jpg'?>" alt="Gallery" title=" " />
							</a>
						</div>
					</div>
					<?php }?>

				</div>
			</div>
		</div>
	</div>
<!--End Lookbook Masonary Grid-->
</section>

</div>
<!--End Body Container-->