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
				<h1 class="collection-hero__title">Videos</h1>
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
						<form action="/video-post" method="get" class="input-group flex-nowrap search-header search" role="search">
							<input class="search-header__input search__input input-group__field" type="search" name="q" placeholder="Video Search..." aria-label="Search" autocomplete="off">
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
							$sqlin='';
							if(count($arrq)>0){ 
								for($z=0;$z<count($arrq);$z++){
									if($arrq[$z]!='' && !in_array(trim($arrq[$z]), $andorarr)){
										$sqlin=$sqlin." and ( p.title like '%".inpval($arrq[$z])."%' or p.description like '%".inpval($arrq[$z])."%') ";
									}
								}
							}
							if($_GET['view']=='product'){
								$sqlin=$sqlin." and isclient='0'";
							}else{
								$sqlin=$sqlin." and isclient='1'";
							}

							$sqllist="select p.*, date_format(videodate,'%M %d, %Y') as pgdate from ccd9videos p where 1=1 $sqlin order by videodate desc, id desc";
							//echo $sqllist;
							$result = $mysqli->query($sqllist);
							$num_rows = mysqli_num_rows($result);
							if($num_rows>0){
							while($row=$result->fetch_array()){ 
								$blogphoto=trim($row['photo']);
								$blogtitle=trim($row['title']);
								//$username=dbval($row['username']);
								$blogcontent=trim($row['description']);
								$blogdate=trim($row['pgdate']);
								$videoid=trim($row['videoid']);
								$pagedescr=substr(strip_tags($blogcontent),0,strpos(strip_tags($blogcontent),' ',120));
							?>
							<div class="col-12 col-sm-12 col-md-6 col-lg-4 collection-page-item cl-item">
								<div class="article mb-0"> 
									<div class="row">
										<!-- Article Image --> 
										<div class="post-img">
											<a class="article_featured-image zoom-scal" href="video-post?videoid=<?php echo $videoid?>"><img class="blur-up lazyload" data-src="<?php echo $blogphoto?>" src="<?php echo $blogphoto?>" alt="<?php echo $blogtitle?>"></a> 
										</div>
										<!-- Article Content -->
										<div class="post-content">
											<h2 class="h3 text-transform-none"><a href="video-post?videoid=<?php echo $videoid?>"><?php echo $blogtitle?></a></h2>
											<ul class="publish-detail d-flex-wrap">                      
												<li><i class="icon an an-clock-r"></i><time datetime="<?php echo $row['pagedate']?>"><?php echo $blogdate?></time></li>
												<!-- <li><i class="icon an an-user-al"></i><span class="clr-555 me-1">Posted by:</span><?php echo $username?></li>
												<li><i class="icon an an-comments-l"></i><a href="video-post?videoid=<?php echo $videoid?>">5</a></li> -->
											</ul>
											<!-- <div class="rte"> 
												<p><?php //echo $pagedescr?>...</p>
											</div> -->
											<p><a href="video-post?videoid=<?php echo $videoid?>" class="btn btn--small rounded">Read more</a></p>
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