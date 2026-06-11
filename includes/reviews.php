<?php

$arrq=array();
if(trim($_GET['q'])!=''){
	//$maintitle="Search Result for ".$_GET['q'];
	//$mysqli->query("insert into ccd9prodsrch (compid, srchkeys, sessionid) values ('".$_SESSION['compid']."', '".inpval($_GET['q'])."', '".session_id()."')");

	$q=trim($_GET['q']);

	if(strstr($q,' ')){
		$arrq=explode(' ',$q);
	}else{
		$arrq[0]=$q;
	}
}
?>
<!--Body Container-->
<div id="page-content">
	<!--Collection Banner-->
	<div class="collection-header">
		<div class="collection-hero">
			<div class="collection-hero__image"></div>
			<div class="collection-hero__title-wrapper container">
				<h1 class="collection-hero__title">Reviews</h1>
				<!-- <div class="breadcrumbs text-uppercase mt-1 mt-lg-2"><a href="index.html" title="Back to the home page">Home</a><span>|</span><span class="fw-bold">Blog Masonry</span></div> -->
			</div>
		</div>
	</div>
	<!--End Collection Banner-->

	<!--Container-->
	<div class="container-fluid">
		<div class="row flex-md-row-reverse">
			<!-- Main Content -->
			<div class="col-12 col-sm-12 col-md-12 col-lg-12 main-col">
				<div class="d-flex">
					<!-- Blog Sidebar Btn -->
					<!-- <button type="button" class="btn btn-filter d-inline-flex align-items-center icon an an-slider-3 text-nowrap">Blog Sidebar</button> -->
					<!-- End Blog Sidebar Btn -->
					<!-- Blog Search -->
					<div class="custom-search w-100 ms-3">
						<form action="/reviews" method="get" class="input-group flex-nowrap search-header search" role="search">
							<input class="search-header__input search__input input-group__field" type="search" name="q" placeholder="Search..." aria-label="Search" autocomplete="off">
							<span class="input-group__btn"><button class="btn rounded-end px-3 btnSearch" type="submit"> <i class="icon an an-search-l"></i></button></span>
						</form>
					</div>
					<!-- End Blog Search -->
				</div>
				<div class="py-1 py-lg-3"><hr class="mb-4" /></div>

				<!--Product Masonary Grid-->
				<div class="grid-mr-10">
					<div class="grid-masonary collection-page-grid blog--list-view blog-grid-view no-border">
						<div class="grid-sizer col-12 col-sm-12 col-md-4 col-lg-4"></div>
						<div class="row">
							<?php //start loop 
							$andorarr = array('in', 'or', 'with', 'and', '&', '+', '-');
							//$sqlin=" and pageid not in (18346) ";
							if(count($arrq)>0){ 
								for($z=0;$z<count($arrq);$z++){
									if($arrq[$z]!='' && !in_array(trim($arrq[$z]), $andorarr)){
										$sqlin=$sqlin." and ( m.prodname like '%".inpval($arrq[$z])."%' or p.username like '%".inpval($arrq[$z])."%' or p.review like '%".inpval($arrq[$z])."%' or p.revtitle like '%".inpval($arrq[$z])."%') ";
									}
								}
							}
							$sqllist="select p.*, date_format(p.revdate,'%M %d, %Y') as pgdate, m.prodname,  m.produrl, ph.photo from ccd9reviews p join ccd9products m on p.prodid=m.prodid join ccd9prodphotos ph on p.prodid=ph.prodid where p.isman='1' and p.status='1' $sqlin group by p.revid order by revdate desc";
							$result = $mysqli->query($sqllist);
							$num_rows = mysqli_num_rows($result);
							if($num_rows>0){
							while($row=$result->fetch_array()){ 
								$blogtitle=dbval($row['revtitle']);
								$prodname=dbval($row['prodname']);
								$username=dbval($row['username']);
								$blogcontent=trim($row['review']);
								$blogdate=trim($row['pgdate']);
								$rating=trim($row['rating']);
								$pagedescr=strip_tags($blogcontent);
								list($prodphoto1, $prodphoto2) = getprodphotos(trim($row['photo']),'');
								$produrl=getprodurl($row['produrl'],'new-arrivals');
							?>
							<div class="col-12 col-sm-12 col-md-6 col-lg-4 collection-page-item cl-item">
								<div class="article mb-0"> 
									<div class="row">
										<div class="post-img">
											<a class="article_featured-image zoom-scal" href="<?php echo $produrl?>"><img class="blur-up lazyload" data-src="<?php echo $prodphoto1?>" src="<?php echo $prodphoto1?>" alt="<?php echo $blogtitle?>"></a> 
										</div>
										
										<!-- Article Content -->
										<div class="post-content">
											<h4 class="h4 text-transform-none"><?php echo $prodname?></h4>
											<ul class="publish-detail d-flex-wrap">                      
												<li><i class="icon an an-clock-r"></i><time datetime="<?php echo $row['blogdate']?>"><?php echo $blogdate?></time></li>
												<li><i class="icon an an-user-al"></i><span class="clr-555 me-1">Review by:</span><?php echo $username?></li>
											</ul>
											<ul class="product-review"> 
											<?php for($n=1;$n<=$row['rating'];$n++){
												echo '<i class="an an-star"></i>';
											}
											?></ul>

											<h2 class="h3 text-transform-none"><?php echo $blogtitle?></h2>
											<div class="rte"> 
												<p><?php echo $pagedescr?></p>
											</div>
										</div>
									</div>
								</div>
							</div>
							<?php }}//end loop?>

						</div>
					</div>
				</div>
				<!--End Product Masonary Grid-->

			</div>
			<!-- End Main Content -->

		</div>
	</div>
	<!--End Container-->
</div>
<!--End Body Container-->