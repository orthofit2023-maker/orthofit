<!--Body Container-->
<div id="page-content">
	<!--Collection Banner-->
	<div class="collection-header">
		<div class="collection-hero m-0">
			<div class="collection-hero__image"></div>
			<div class="collection-hero__title-wrapper container">
				<h1 class="collection-hero__title"><?php echo $pagetitle?></h1>
			</div>
		</div>
	</div>
	<!--End Collection Banner-->

	<!--Main Content-->
	<!--Content Info-->
<style>
#pagecontent{font-size:17px;}
#pagecontent ul{
	 list-style-position: outside !important; 
	 padding-left:20px;
}
#pagecontent ul li{
	margin-bottom:15px;
}
</style>
<?php if($pgtype=="pain-conditions"){
list($overview, $symptoms, $causes, $treatment, $solution) = explode("####", $pagecontent);

?>
<!--Main Content-->
                <div class="container  mt-2 mt-md-5">
                                   <div class="row">
                                        <div class="col-12 col-sm-12 col-md-5 col-lg-5 mb-4 mb-md-0">
                    
												<div class="col-12 col-sm-12 mb-4 text-center">
													<img data-src="<?php echo $pagephoto?>" src="<?php echo $pagephoto?>" alt="image" />
												</div>
										</div>

                                        <div class="col-12 col-sm-12 col-md-7 col-lg-7 mb-4 mb-md-0">

                    <!--Product Tabs-->
                     <div class="tabs-listing">
                        <ul class="product-tabs list-unstyled d-flex-wrap border-bottom m-0 d-none d-md-flex">
                            <li rel="overview" class="active"><a class="tablink">Overview</a></li>
                            <li rel="symptoms"><a class="tablink">Symptoms</a></li>
                            <li rel="causes"><a class="tablink">Causes</a></li>
                            <li rel="treatment"><a class="tablink">Treatment</a></li>
                            <li rel="solution-product"><a class="tablink">Solution</a></li>
                        </ul>
                        <div class="tab-container">
                            <h3 class="tabs-ac-style d-md-none active" rel="overview">Overview</h3>
                            <div id="overview" class="tab-content">
                                <div class="product-description">
                                    <div class="row">
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 mb-4 mb-md-0">
												 <?php echo $overview?>
										</div>
                                </div>
                            </div>
                            </div>

                            <h3 class="tabs-ac-style d-md-none" rel="symptoms">Symptoms</h3>
                            <div id="symptoms" class="tab-content">

                                <div class="product-description">
                                    <div class="row">
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 mb-4 mb-md-0">
										 <?php echo $symptoms?>
										  </div>
									</div>
                                </div>

                            </div>

                            <h3 class="tabs-ac-style d-md-none" rel="causes">Causes</h3>
                            <div id="causes" class="tab-content">
                                <div class="product-description">
                                    <div class="row">
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 mb-4 mb-md-0">
												 <?php echo $causes?>
										</div>
                                    </div>
                                </div>


							</div>

                            <h3 class="tabs-ac-style d-md-none" rel="treatment">Treatment</h3>
                            <div id="treatment" class="tab-content">
                                <div class="product-description">
                                    <div class="row">
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 mb-4 mb-md-0">
												 <?php echo $treatment?>
										</div>
                                    </div>
                                </div>




							</div>

							<h3 class="tabs-ac-style d-md-none" rel="solution-product">Solution Product</h3>
                            <div id="solution-product" class="tab-content">
                                <div class="product-description">
                                    <div class="row">
                                        <div class="col-12 col-sm-12 col-md-12 col-lg-12 mb-4 mb-md-0">
											<?php echo $solution?>
										</div>
									</div>
								</div>
							</div>


						</div>
					</div>
                    <!--End Product Tabs-->
                </div>
                </div>


                <!--End Container-->
            </div>
            <!--End Body Container-->


<?php }else{?>

	<div class="container-fluid section">
		<div class="row about-info1">
			<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-9 mx-auto" id="pagecontent">
				<?php echo $pagecontent;?>                     
			</div>
		</div>
	</div>
	<?php include("includes/socialshare.php");?>
<?php }?>
	<!--End Content Info-->


	<!--End Main Content-->
</div>
<!--End Body Container-->