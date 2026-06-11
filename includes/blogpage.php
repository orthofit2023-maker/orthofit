<?php
$res=query_first("select *, date_format(pagedate, '%b %d, %Y') as dt, u.typename as username from ccd9pages  p join ccd9types u on u.typeid=p.pageby and u.opt='8'where pageid='$pageid'");
$title=dbval($res['title']);
$meta_title=dbval($res['meta_title']);
$description=trim($res['description']);
$pageurl = dbval($res['pageurl']);
$status = dbval($res['status']);
$meta_description=dbval($res['meta_description']);
$meta_keywords=dbval($res['meta_keywords']);
$banner=dbval($res['banner']);
$dt=trim($res['dt']);
$pageurl=$serverurl.$iscarturl."/".$res['pageurl'];
$cnt=trim($res['cnt']);
$blogbanner=trim($res['banners']);
$username=trim($res['username']);
?>

<!--Body Container-->
<div id="page-content">
	<!--Breadcrumbs-->
	<div class="breadcrumbs-wrapper text-uppercase">
		<div class="container">
			<div class="breadcrumbs"><a href="index.html" title="Back to the home page">Home</a><span>|</span><a href="blog-post" title="Back to the Blog page">Blogs</a><span>|</span><span class="fw-bold"><?php echo $title?></span></div>
		</div>
	</div>
	<!--End Breadcrumbs-->

	<!--Container-->
	<div class="container-fluid">
		<div class="row">
			<!--Sidebar-->
			<div class="col-12 col-sm-12 col-md-12 col-lg-3 blog-sidebar sidebar sidebar-noborder">
			<?php include("includes/blogsidebar.php");?>
			<!--End Sidebar-->
			</div>
			
			<!--Main Content-->
			<div class="col-12 col-sm-12 col-md-12 col-lg-9 main-col">
				<div class="article"> 
					<h1 class="h3"><?php echo $title?></h1>
					<ul class="publish-detail d-flex-wrap mb-4 pt-1">                      
						<li><i class="icon an an-clock-r"></i><time datetime="<?php echo $dt?>"><?php echo $dt?></time></li>
						<li><i class="icon an an-user-al"></i><span class="clr-555 me-1">Posted by:</span><?php echo $username?></li>
						<!-- <li><i class="icon an an-comments-l"></i>5 comments</li> -->
					</ul>
					<!-- Article Image --> 
					<div class="article_featured-image"><img class="blur-up ls-is-cached lazyloaded" data-src="<?php echo $blogbanner?>" src="<?php echo $blogbanner?>" alt="<?php echo $title?>" /></div> 
					<!-- Article Content --> 
					<div class="rte">
						<?php echo $pagecontent?>
					</div>
					<!-- Article Tags --> 
					<!-- <ul class="publish-detail d-flex-wrap">                      
						<li><i class="icon an an-user-al d-none"></i><span class="clr-555 me-1">Posted in</span><a class="link-underline" href="#">Beauty</a>,<a class="link-underline ms-1" href="#">Fashion</a>,<a class="link-underline ms-1" href="#">Spring</a></li>
					</ul> -->
					
					<!-- Article Social -->
					<?php include("includes/socialshare.php");?>
					<!-- Article Nav -->
					<!-- <div class="blog-nav d-flex-center mt-4 clearfix">
						<div class="nav-prev me-auto"><a href="#"><i class="align-middle me-2 icon an an-long-arrow-alt-left"></i><span>Previous post</span></a></div>
						<div class="nav-next ms-auto"><a href="#"><span>Next post</span><i class="align-middle ms-2 icon an an-long-arrow-alt-right"></i></a></div>
					</div> -->
					
					<?php //include("includes/blogcomments.php");?>

					<!--Return to Posts-->
					<div class="text-center return-link-wrapper mt-4">
						<a href="blog-post" class="btn btn-lg rounded btn--has-icon-before return-link"><i class="icon an an-long-arrow-alt-left align-middle me-2"></i><span class="align-middle">Back to Blogs</span></a>
					</div>
					<!--End Return to Posts-->
				</div>
			</div>
			<!--End Main Content-->
		</div>
	</div>
	<!--End Container-->
</div>
<!--End Body Container-->
