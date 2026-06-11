
	<!--Search-->
	<div class="sidebar_widget clearfix">
		<div class="widget-title"><h2>Search</h2></div>
		<div class="custom-search mt-3 mb-2 my-lg-0">
			<form action="<?php echo ($opt=='video-post' ? $opt : 'blog-post')?>" method="get" class="input-group flex-nowrap search-header search" role="search">
				<input class="search-header__input search__input input-group__field" type="search" name="q" placeholder="Search..." aria-label="Search" autocomplete="off">
				<span class="input-group__btn"><button class="btn rounded-end px-3 btnSearch" type="submit"> <i class="icon an an-search-l"></i> </button></span>
			</form>
		</div>
	</div>
	<!--End Search-->
	<!--Sidebar tags-->
	<div class="sidebar_tags clearfix">
		<!--Recent Posts-->
		<div class="sidebar_widget clearfix">
			<div class="widget-title"><h2>Recent Posts</h2></div>
			<div class="widget-content">
				<div class="list list-sidebar-products">
					<div class="row">
						<?php //start loop
							if($opt=='video-post'){
								$sqllist="select p.*, date_format(videodate,'%M %d, %Y') as pgdate from ccd9videos p where 1=1 and videoid!='$videoid'order by videodate desc, id desc limit 1,5";
							}else{
								$sqllist="select p.*, date_format(pagedate,'%M %d, %Y') as pgdate, u.typename as username from ccd9pages p join ccd9types u on u.typeid=p.pageby and u.opt='8' where iscart='1' and p.pageid!='$pageid' order by pagedate desc, pageid desc limit 1,5";
							}
							
							$result = $mysqli->query($sqllist);
							$num_rows = mysqli_num_rows($result);
							if($num_rows>0){
							while($row=$result->fetch_array()){ 
								$blogcontent=trim($row['description']);
								$blogdate=trim($row['pgdate']);
								if($opt=='video-post'){
									$blogurl='video-post?videoid='.$row['videoid'];
									$blogphoto=trim($row['photo']);
									$blogtitle=trim($row['title']);
								}else{
									$blogurl='blog-post/'.trim($row['pageurl']);
									$blogphoto=trim($row['banners']);
									$blogtitle=dbval($row['meta_title']);
								}
								$pagedescr=substr(strip_tags($blogcontent),0,strpos(strip_tags($blogcontent),' ',120));

						?>
						
						<div class="col-12">
							<div class="mini-list-item d-flex mb-10 clearfix">
								<div class="mini-view_image"><a class="grid-view-item__link" href="<?php echo $blogurl?>"><img class="grid-view-item__image blur-up lazyload" data-src="<?php echo $blogphoto?>" src="<?php echo $blogphoto?>" alt="<?php echo $blogtitle?>" /></a></div>
								<div class="ms-3 details">
									<a class="grid-view-item__title" href="<?php echo $blogurl?>"><?php echo $blogtitle?></a>
									<div class="grid-view-item__meta clr-555"><time datetime="<?php echo $row['pagedate']?>"><?php echo $blogdate?></time></div>
								</div>
							</div>
						</div>
						<?php } }?>
					</div>
				</div>
			</div>
		</div>
		<!--End Recent Posts-->
		<!--Recent Comments
		<div class="sidebar_widget clearfix">
			<div class="widget-title"><h2>Recent Comments</h2></div>
			<div class="widget-content">
				<div class="list list-sidebar-products">
					<div class="row">
						<div class="col-12">
							<div class="mini-list-item d-flex mb-10 clearfix">
								<div class="mini-view_image"><a class="grid-view-item__link" href="blog-single-post.html"><img class="grid-view-item__image blur-up lazyload" data-src="assets/images/blog/recent-commnet.jpg" src="assets/images/blog/recent-commnet.jpg" alt="Image" /></a></div>
								<div class="ms-3 details">
									<div class="grid-view-item__meta"><strong>Belle</strong> On <a href="blog-single-post.html">Lorem Ipsum</a></div>
									<a class="grid-view-item__title" href="blog-single-post.html">Lorem Ipsum is simply dummy text of the printing</a>
								</div>
							</div>
						</div>
						<div class="col-12">
							<div class="mini-list-item d-flex mb-10 clearfix">
								<div class="mini-view_image"><a class="grid-view-item__link" href="blog-single-post.html"><img class="grid-view-item__image blur-up lazyload" data-src="assets/images/blog/recent-commnet.jpg" src="assets/images/blog/recent-commnet.jpg" alt="Image" /></a></div>
								<div class="ms-3 details">
									<div class="grid-view-item__meta"><strong>Avone</strong> On <a href="blog-single-post.html">Lorem Ipsum</a></div>
									<a class="grid-view-item__title" href="blog-single-post.html">Lorem Ipsum is simply dummy text of the printing</a>
								</div>
							</div>
						</div>
						<div class="col-12">
							<div class="mini-list-item d-flex clearfix">
								<div class="mini-view_image"><a class="grid-view-item__link" href="blog-single-post.html"><img class="grid-view-item__image blur-up lazyload" data-src="assets/images/blog/recent-commnet.jpg" src="assets/images/blog/recent-commnet.jpg" alt="Image" /></a></div>
								<div class="ms-3 details">
									<div class="grid-view-item__meta"><strong>Diva</strong> On <a href="blog-single-post.html">Lorem Ipsum</a></div>
									<a class="grid-view-item__title" href="blog-single-post.html">Lorem Ipsum is simply dummy text of the printing</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>-->
		<!--End Recent Comments-->
		<!--Popular Tags
		<div class="sidebar_widget clearfix tags-clouds">
			<div class="widget-title"><h2>Popular Tags</h2></div>
			<div class="widget-content">
				<ul class="d-flex-wrap m-0">
					<li><a href="#">Fashion</a></li>
					<li><a href="#">Clothes</a></li>
					<li><a href="#">Shoes</a></li>
					<li><a href="#">Jeans</a></li>
					<li><a href="#">Furniture</a></li>
					<li><a href="#">Sun Glasses</a></li>
					<li><a href="#">Winter</a></li>
					<li><a href="#">Beauty</a></li>
				</ul>
			</div>
		</div>-->
		<!--End Popular Tags-->
		<!--Trending Now
		<div class="sidebar_widget clearfix">
			<div class="widget-title"><h2>Trending Now</h2></div>
			<div class="widget-content">
				<div class="list list-sidebar-products">
					<div class="row">
						<div class="col-12">
							<div class="mini-list-item d-flex mb-10 clearfix">
								<div class="mini-view_image"><a class="grid-view-item__link" href="product-layout1.html"><img class="primary blur-up lazyload" data-src="assets/images/products/product-1.jpg" src="assets/images/products/product-1.jpg" alt="image" title="product" /></a></div>
								<div class="ms-3 details">
									<a class="grid-view-item__title" href="product-layout1.html">Floral Crop Top</a>
									<div class="grid-view-item__meta"><div class="product-price"><span class="old-price">$199.00</span><span class="price">$219.00</span></div></div>
									<div class="product-review d-flex align-items-center justify-content-start">
										<i class="an an-star"></i><i class="an an-star"></i><i class="an an-star"></i><i class="an an-star-o"></i><i class="an an-star-o"></i>
										<span class="caption hidden ms-2">3 reviews</span>
									</div>
								</div>
							</div>
						</div>
						<div class="col-12">
							<div class="mini-list-item d-flex mb-10 clearfix">
								<div class="mini-view_image"><a class="grid-view-item__link" href="product-layout1.html"><img class="primary blur-up lazyload" data-src="assets/images/products/product-8-1.jpg" src="assets/images/products/product-8-1.jpg" alt="image" title="product" /></a></div>
								<div class="ms-3 details">
									<a class="grid-view-item__title" href="product-layout1.html">Martha Knit Top</a>
									<div class="grid-view-item__meta"><div class="product-price"><span class="price">$149.00</span></div></div>
									<div class="product-review d-flex align-items-center justify-content-start">
										<i class="an an-star"></i><i class="an an-star"></i><i class="an an-star"></i><i class="an an-star"></i><i class="an an-star"></i>
										<span class="caption hidden ms-2">9 reviews</span>
									</div>
								</div>
							</div>
						</div>
						<div class="col-12">
							<div class="mini-list-item d-flex clearfix">
								<div class="mini-view_image"><a class="grid-view-item__link" href="product-layout1.html"><img class="primary blur-up lazyload" data-src="assets/images/products/product-16-1.jpg" src="assets/images/products/product-16-1.jpg" alt="image" title="product" /></a></div>
								<div class="ms-3 details">
									<a class="grid-view-item__title" href="product-layout1.html">Cool Short Sleev T-Shirt</a>
									<div class="grid-view-item__meta"><div class="product-price"><span class="price">$59.00</span></div></div>
									<div class="product-review d-flex align-items-center justify-content-start">
										<i class="an an-star"></i><i class="an an-star"></i><i class="an an-star"></i><i class="an an-star"></i><i class="an an-star-o"></i>
										<span class="caption hidden ms-2">30 reviews</span>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>-->
		<!--End Trending Now-->
		<!--Banner
		<div class="sidebar_widget clearfix static-banner d-none d-lg-block">
			<a href="women"><img src="https://orthofitmart.com/wp-content/uploads/2023/02/home-women-collection-image.png" alt="image">
			<b class="title fs-3 mb-1">Women's Collection</b>
			</a>
		</div>-->
		<!--End Banner-->
		<!--About Blog
		<div class="sidebar_widget clearfix">
			<div class="widget-title"><h2>About Blog</h2></div>
			<div class="widget-content">
				<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed tincidunt, erat in malesuada aliquam, est erat faucibus purus, eget viverra nulla sem vitae neque. Quisque id sodales libero.</p>
			</div>
		</div>-->
		<!--End About Blog-->
	</div>
	<!--End Sidebar tags-->
