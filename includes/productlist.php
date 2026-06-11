<?php
$type1=intval($_GET['type1']);
$type2=intval($_GET['type2']);
$type3=intval($_GET['type3']);
//$sortby=inpval($_GET['sortprice']);
$sortsize=inpval($_GET['sortsize']);
$price=inpval($_GET['price']);
$q=inpval($_GET['q']);
$sortby=inpval($_GET['sortby']);

$arrq=array();
if(trim($_GET['q'])!=''){
	$maintitle="Search Result for ".$_GET['q'];
	//$mysqli->query("insert into ccd9prodsrch (compid, srchkeys, sessionid) values ('".$_SESSION['compid']."', '".inpval($_GET['q'])."', '".session_id()."')");

	$q=trim($_GET['q']);

	if(strstr($q,' ')){
		$arrq=explode(' ',$q);
	}else{
		$arrq[0]=$q;
	}
}

$sqlvar = "t3.*, t3.typeid as t3typeid, v1.typeid, ph.photo, t3c.typename, t3c.typevalue,  c.*, IF(CURDATE() between c.offerfrdate and c.offertodate, '1', '0') as isoffer, IF(CURDATE() between c.discfrdate and c.disctodate, '1', '0') as isdiscount ".($_SESSION['compid']>0 ? ", w.wishid " : "").($opt=='sale' ? ", ct.status as salestatus" :"") ;

$sqllist="SELECT ### from ccd9products c join ccd9prod2type3 t3 on c.prodid=t3.prodid join ccd9types t3c on t3.typeid=t3c.typeid and t3c.opt=7  left join ccd9prod2cat t on t.prodid=c.prodid left join ccd9prod2type1 v1 on v1.prodid=c.prodid left join ccd9prodphotos ph on ph.prodid=c.prodid and ph.type1=v1.typeid and ph.type3=t3.typeid and ph.photo!='' ".($type2>0 ? " left join ccd9prod2type2 v2 on v2.prodid=c.prodid " : "").($_SESSION['compid']>0 ? " left join ccd9wishlist w on w.prodid=c.prodid and w.compid='".$_SESSION['compid']."'" : "").($opt=='sale' ? " left join ccd9cart ct on ct.prodid=c.prodid " : "" )." where 1=1 and c.prodstatus='1' and t3.status='1' ".($_SESSION['myCUR']=="US $" ? " and (c.zone='USD' or c.zone='ALL')" : " and (c.zone='INR' or c.zone='ALL')");
//.($type3>0 ? " left join ccd9prod2type3 v3 on v3.prodid=c.prodid " : "")
if($_GET['type1']>0){
	$sqllist=$sqllist." and ( v1.typeid = '".inpval($type1)."') ";
}
if($_GET['type2']>0){
	$sqllist=$sqllist." and ( v2.typeid = '".inpval($type2)."') ";
}
if($_GET['type3']>0){
	$sqllist=$sqllist." and ( t3.typeid = '".inpval($type3)."') ";
}
if($catid>0){
	$sqllist=$sqllist." and ( t.catid = '".inpval($catid)."') ";
}
if($price!=''){
	$sqllist=$sqllist." and ( c.prodprice between '".str_replace('-','\' and \'',$price)."') ";
}
$andorarr = array('in', 'or', 'with', 'and', '&', '+', '-');
if(count($arrq)>0){
	for($z=0;$z<count($arrq);$z++){
		if($arrq[$z]!='' && !in_array(trim($arrq[$z]), $andorarr)){
			$sqllist=$sqllist." and ( c.prodcode like '%".inpval($arrq[$z])."%' or c.prodname like '%".inpval($arrq[$z])."%' or c.proddesc like '%".inpval($arrq[$z])."%') ";
		}
	}
}

$sqllist=$sqllist." group by c.prodid, t3.typeid ";
if($sortby=="" || $sortby=="featured"){
	$sqllist=$sqllist." order by c.prodid desc"; // c.sortby, c.entrydate desc, 
}else if($sortby=="price-ascending"){
	$sqllist=$sqllist." order by c.prodprice"; // 
}else if($sortby=="price-descending"){
	$sqllist=$sqllist." order by c.prodprice desc"; // 
}else if($sortby=="title-ascending"){
	$sqllist=$sqllist." order by c.prodname"; // 
}else if($sortby=="title-descending"){
	$sqllist=$sqllist." order by c.prodname desc"; // 
}
//$sqllist=$sqllist." limit 0, 12";
//echo $sqllist;

if(trim($_GET['q'])!=''){}else{
	//$sqllist="CALL prodList('".$_SESSION['myCUR']."','$catid','$type1','$type2','$type3','$sort')";
}

//echo $catid;
?>
<div id="page-content">  
	<!--Collection Banner-->
	<div class="collection-header">
		<div class="collection-hero">
			<div class="collection-hero__image"></div>
			<div class="collection-hero__title-wrapper container">
				<h2 class="collection-hero__title"><?php echo $metatitle?></h2>
				<!-- <div class="breadcrumbs text-uppercase mt-1 mt-lg-2"><a href="index.html" title="Back to the home page">Home</a><span>|</span><span class="fw-bold">Shop Left Sidebar</span></div> -->
			</div>
		</div>
	</div>
	<!--End Collection Banner-->

	<div class="container-fluid">
		<div class="row">
			<?php include("includes/sidebar.php");
			
			
			$sqllist=str_replace('###',$sqlvar, $sqllist);
			//echo $sqllist;
			?>

			<!--Main Content-->
			<div class="col-12 col-sm-12 col-md-12 col-lg-9 main-col">
				<!-- <div class="page-title"><h1>Womens</h1></div> -->
				<div id="errmsg" class="hide">
				<div class="alert alert-success py-2 rounded-1 alert-dismissible fade show cart-alert" role="alert">
					<span id="errmsgtxt"></span>
					<!-- <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button> -->
				</div></div>
				<!--Collection Description-->
				<div class="collection-description">
					<p><?php echo $catdescription?></p>
				</div>
				<!--End Collection Description-->
				<!--Active Filters-->
				<!-- <ul class="active-filters d-flex flex-wrap align-items-center m-0 list-unstyled">
					<li><a href="#">Clear all</a></li>
					<li><a href="#">Men <i class="an an-times-l"></i></a></li>
					<li><a href="#">Accessories <i class="an an-times-l"></i></a></li>
				</ul> -->
				<!--End Active Filters-->
				<!--Toolbar-->
				<?php
				$result = $mysqli->query($sqllist);
				$num_rows = mysqli_num_rows($result);
				?>
				<div class="toolbar">
					<div class="filters-toolbar-wrapper">
						<ul class="list-unstyled d-flex align-items-center">
							<li class="product-count d-flex align-items-center">
								<button type="button" class="btn btn-filter an an-slider-3 d-inline-flex d-lg-none me-2 me-sm-3"><span class="hidden">Filter</span></button>
								<div class="filters-toolbar__item">
									<span class="filters-toolbar__product-count d-none d-sm-block">Showing: <?php echo $num_rows?> products</span>
								</div>
							</li>
							<li class="collection-view ms-sm-auto">
								<div class="filters-toolbar__item collection-view-as d-flex align-items-center me-3">
									<!-- <a href="javascript:void(0)" class="change-view prd-grid change-view--active"><i class="icon an an-th" aria-hidden="true"></i><span class="tooltip-label">Grid View</span></a>
									<a href="javascript:void(0)" class="change-view prd-list"><i class="icon an an-th-list" aria-hidden="true"></i><span class="tooltip-label">List View</span></a> -->
								</div>
							</li>
							<li class="filters-sort ms-auto ms-sm-0">
								<div class="filters-toolbar__item">
									<label for="sortby" class="hidden">Sort by:</label>
									<select name="sortby" id="sortby" class="filters-toolbar__input filters-toolbar__input--sort" onchange="callfilter('sortby', this)">
										<option value="featured" <?php echo ($sortby=='featured' || $sortby=='' ? 'selected="selected"' : '')?>>Featured</option>
										<!-- <option value="best-selling">Best selling</option> -->
										<option value="title-ascending" <?php echo ($sortby=='title-ascending' ? 'selected="selected"' : '')?>>Alphabetically, A-Z</option>
										<option value="title-descending" <?php echo ($sortby=='title-descending' ? 'selected="selected"' : '')?>>Alphabetically, Z-A</option>
										<option value="price-ascending" <?php echo ($sortby=='price-ascending' ? 'selected="selected"' : '')?>>Price, low to high</option>
										<option value="price-descending" <?php echo ($sortby=='price-descending' ? 'selected="selected"' : '')?>>Price, high to low</option>
										<!-- <option value="created-ascending">Date, old to new</option>
										<option value="created-descending">Date, new to old</option> -->
									</select>
								</div>
							</li>
						</ul>
					</div>
				</div>
				<!--End Toolbar-->

				<!--Product Grid-->
				<div class="grid-products grid--view-items prd-grid">
					<form class="addtocart" action="#" method="post">
					<div class="row">
						<?php
						if ($num_rows>0){ $i=0; 
							while($row=$result->fetch_array()){ 
								$produrl=getprodurl($row['produrl'],$opt).'&type3='.$row['t3typeid'];

								list($prodphoto1, $prodphoto2) = getprodphotos(trim($row['photo']), trim($row['images']));
								list($prodprice, $priceval)=getprice($row);
								

						?>
						<div class="col-6 col-sm-6 col-md-4 col-lg-4 item">
							<!--Start Product Image-->
							<div class="product-image">
								<!--Start Product Image-->
								<a href="<?php echo $produrl?>" class="product-img">
									<!-- image -->
									<img class="primary blur-up lazyload" data-src="<?php echo $prodphoto1?>" src="<?php echo $prodphoto1?>" alt="image" title="">
									<!-- End image -->
									<!-- Hover image -->
									<img class="hover blur-up lazyload" data-src="<?php echo $prodphoto2?>" src="<?php echo $prodphoto2?>" alt="image" title="">
									<!-- End hover image -->
									<!-- product label -->
									<?php if(strstr($prodprice,'OFF')){?>
									<div class="product-labels"><span class="lbl on-sale rounded">Sale</span></div>
									<?php }?>
									<!-- End product label -->
								</a>
								<!--End Product Image-->

								<!--Product Button-->
								<div class="button-set style0 d-none d-md-block">
									<ul>
										<!--Cart Button-->
										<li><a class="btn-icon btn cartIcon pro-addtocart-popup" href="<?php echo $produrl?>"><i class="icon an an-cart-l"></i> <span class="tooltip-label top">Add to Cart</span></a></li>
										<!--End Cart Button-->
										<!--Quick View Button-->
										<!-- <li><a class="btn-icon quick-view-popup quick-view" href="javascript:void(0)" data-toggle="modal" data-target="#content_quickview"><i class="icon an an-search-l"></i> <span class="tooltip-label top">Quick View</span></a></li> -->
										<!--End Quick View Button-->
										<!--Wishlist Button-->
										<li><a class="btn-icon wishlist add-to-wishlist" href="Javascript:addtowishbtn('<?php echo $row['prodid']?>','<?php echo $opt?>','<?php echo dbval($row['prodcode'])?>','<?php echo dbval(str_replace("'","",$row['prodname']))?>','<?php echo$priceval?>','<?php echo dbval($row['prodcolor'])?>','<?php echo $catid?>','<?php echo dbval($catname)?>')"><i class="icon an an-heart-l" id="wish<?php echo $row['prodid']?>" <?php echo ($row['wishid'] ? ' style="color:#f00000"' : '')?>></i> <span class="tooltip-label top">Add To Wishlist</span></a></li>
										<!--End Wishlist Button-->
										<!--Compare Button-->
										<!-- <li><a class="btn-icon compare add-to-compare" href="compare-style2.html"><i class="icon an an-sync-ar"></i> <span class="tooltip-label top">Add to Compare</span></a></li> -->
										<!--End Compare Button-->
									</ul>
								</div>
								<!--End Product Button-->  
							</div>
							<!--End Product Image-->
							<!--Start Product Details-->
							<div class="product-details text-center">
								<!--Product Name-->
								<div class="product-name text-uppercase">
									<a href="<?php echo $produrl?>"><?php echo $row['prodname']?></a>
								</div>
								<!--End Product Name-->
								<!--Product Price-->
								<div class="product-price">
									<span class="price"><?php echo $prodprice?></span>
								</div>
								<!--End Product Price-->
								<!--Product Review-->
								<div class="product-review d-flex align-items-center justify-content-center">
									<?php showrating($row['rating']);
									//echo '<span class="caption hidden ms-2">'.$row['rating'].' reviews</span>';
									?>
									
								</div>
								<!--End Product Review-->
								<!--Sort Description-->
								<!-- <p class="hidden sort-desc"><?php //echo str_replace('\n','<br>',trim($row['proddesc']))?></p> -->
								<!--End Sort Description-->
								<!--Color Variant-->
								<ul class="swatches">
									<?php echo getcolors($row['prodid'],$produrl)?>
									<!-- <li class="swatch medium radius black"><span class="tooltip-label">black</span></li>
									<li class="swatch medium radius maroon"><span class="tooltip-label">maroon</span></li> -->
								</ul>
								<!--End Color Variant-->
								<!-- Product Button -->
								<div class="button-action d-flex">
									<div class="addtocart-btn">
											<a class="btn pro-addtocart-popup" href="#pro-addtocart-popup"><i class="icon hidden an an-cart-l"></i>Add To Cart</a>
									</div>
									<div class="quickview-btn">
										<a class="btn btn-icon quick-view quick-view-popup" href="javascript:void(0)" data-toggle="modal" data-target="#content_quickview"><i class="icon an an-search-l"></i> <span class="tooltip-label top">Quick View</span></a>
									</div>
									<div class="wishlist-btn">
										<a class="btn btn-icon wishlist add-to-wishlist" href="my-wishlist.html"><i class="icon an an-heart-l"></i> <span class="tooltip-label top">Add To Wishlist</span></a>
									</div>
								</div>
								<!-- End Product Button -->
							</div>
							<!--End Product Details-->
						</div>
						<?php } }?>
					</div>
					</form>
				</div>
				<!--End Product Grid-->

				<!--Pagination Classic-->
				<!-- <hr class="clear">
				<div class="pagination">
					<ul>
						<li class="prev"><a href="#"><i class="icon align-middle an an-caret-left" aria-hidden="true"></i></a></li>
						<li class="active"><a href="#">1</a></li>
						<li><a href="#">2</a></li>
						<li><a href="#">...</a></li>
						<li><a href="#">5</a></li>
						<li class="next"><a href="#"><i class="icon align-middle an an-caret-right" aria-hidden="true"></i></a></li>
					</ul>
				</div> -->
				<!--End Pagination Classic-->

				
			</div>
			<!--End Main Content-->
		</div>
	</div>
</div>