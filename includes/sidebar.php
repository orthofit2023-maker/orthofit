<!--Sidebar-->
<div class="col-12 col-sm-12 col-md-12 col-lg-3 sidebar filterbar">
	<div class="closeFilter d-block d-lg-none"><i class="icon icon an an-times-r"></i></div>
	<div class="sidebar_tags">
		<?php if($opt!='footcare-products' && $opt!='orthotic-insoles'){?>

		<!--Categories-->
		<div class="sidebar_widget categories filterBox filter-widget">
			<div class="widget-title"><h2 class="mb-0">Categories</h2></div>
			<div class="widget-content filterDD">
				<ul class="clearfix sidebar_categories mb-0">
					<li class="lvl-1 sub-level"><a href="Javascript:return false;" class="site-nav">Orthotic Footwear</a>
						<ul class="sublinks">
							<?php
							$sql="select typeid, typename, typevalue from ccd9types where opt='2' and typevalue1='1' order by typevalue2"; // order by sortby
							$result = $mysqli->query($sql);
							while($rowtype = $result->fetch_array()){
								echo '<li class="level2"><a href="'.$rowtype['typevalue'].'" class="site-nav">'.$rowtype['typename'].'</a></li>';
							}
							?>
						</ul>
					</li>
				</ul>
			</div>
		</div>
		<!--Categories-->
		<!--Price Filter-->
		<div class="sidebar_widget filterBox filter-widget">
			<div class="widget-title"><h2 class="mb-0">Price</h2></div>
			<form action="#" method="post" class="price-filter filterDD">
				<div id="slider-range" class="mt-2"></div>
				<div class="row">
					<div class="col-6">
						<p class="no-margin"><input id="amount" type="text" readonly></p>
					</div>
					<div class="col-6 text-right margin-25px-top">
						<button  type="button" class="btn btn--small rounded" onclick="callfilter('price', '<?php echo $GET['amount']?>')">filter</button>
					</div>
				</div>
			</form>
		</div>
		<!--End Price Filter-->
		<!--Color Swatches-->
		<div class="sidebar_widget filterBox filter-widget">
			<div class="widget-title"><h2 class="mb-0">Color</h2></div>
			<div class="filter-color swacth-list filterDD clearfix">
				<ul class="clearfix">
					<?php
					//$sql="select typeid, typename, typevalue from ccd9types where opt='7' and typeid!=46 and typeid in (".(str_replace('###', 't3.typeid', $sqllist)).")"; // order by sortby
					$sql="select typeid, typename, typevalue from ccd9types where opt='7' and typeid!=46 "; // order by sortby
					$result = $mysqli->query($sql);
					while($rowtype = $result->fetch_array()){
						$selected='';
						if($rowtype['typeid']==intval($_GET['type3'])) $selected=' checked';
						echo '<li><a href="Javascript:callfilter(\'type3\', \''.$rowtype['typeid'].'\',  \''.$selected.'\')"><span id="type'.$rowtype['typeid'].'" class="swacth-btn medium radius '.$selected.'" style="background-color: '.$rowtype['typevalue'].'"></span><span class="tooltip-label">'.$rowtype['typename'].'</span></a></li>';
					}
					?>
				</ul>
			</div>
		</div>
		<!--End Color Swatches-->
		<!--Size Swatches-->
		<div class="sidebar_widget filterBox filter-widget size-swacthes">
			<div class="widget-title"><h2 class="mb-0">Size</h2></div>
			<div class="filterDD">
				<ul class="clearfix">
					<?php
					$sql="select typeid, typename from ccd9types where opt='4' and typename!='NA'"; // order by sortby
					$result = $mysqli->query($sql);
					while($rowtype = $result->fetch_array()){
						$selected='';
						if($rowtype['typeid']==intval($_GET['type2'])) $selected=' checked';
						echo '<li><input type="checkbox" value="'.$rowtype['typeid'].'" id="type'.$rowtype['typeid'].'" onclick="callfilter(\'type2\', this)" '.$selected.'><label for="type'.$rowtype['typeid'].'"><span></span>'.$rowtype['typename'].'</label></li>';
					}
					?>
				</ul>
			</div>
		</div>
		<!--End Size Swatches-->
		<!--Product type-->
		<div class="sidebar_widget filterBox filter-widget size-swacthes product-type">
			<div class="widget-title"><h2 class="mb-0">Fit</h2></div>
			<div class="filterDD">
				<ul class="clearfix">
					<?php
					//$sql="select typeid, typename from ccd9types where opt='3' and typename!='NA' and typeid in (".(str_replace('###', 'v1.typeid', $sqllist)).")"; // order by sortby
					$sql="select typeid, typename from ccd9types where opt='3' and typename!='NA'"; // order by sortby
					$result = $mysqli->query($sql);
					while($rowtype = $result->fetch_array()){
						//echo '<li><input type="checkbox" value="'.$rowtype['typename'].'" id="'.$rowtype['typename'].'"><label for="s"><span></span>'.$rowtype['typename'].'</label></li>';
						$selected='';
						if($rowtype['typeid']==intval($_GET['type1'])) $selected=' checked';
						echo '<li><input type="checkbox" value="'.$rowtype['typeid'].'" id="type'.$rowtype['typeid'].'" onclick="callfilter(\'type1\', this)" '.$selected.'><label for="type'.$rowtype['typeid'].'"><span></span>'.$rowtype['typename'].'</label></li>';
					}
					?>
				</ul>
			</div>
		</div>
		<!--End Product type-->
		<?php }?>
	</div>
</div>
<!--End Sidebar-->
<script>
function callfilter(ftype, fvalue, fchecked=''){
	if(ftype=='type1'){
		if(fvalue.checked == true){
			var t1 =  fvalue.value;
		}else{
			var t1 =  '';
		}
	}else{
		var t1 = "<?php echo $_GET['type1']?>";
	}
	
	if(ftype=='type2'){
		var t2 =  fvalue.value;
		if(fvalue.checked == true){
			var t2 =  fvalue.value;
		}else{
			var t2 =  '';
		}
	}else{
		var t2 = "<?php echo $_GET['type2']?>";
	}

	if(ftype=='sortby'){
		var t5 =  fvalue.value;
	}else{
		var t5 = "<?php echo $_GET['sortby']?>";
	}

	if(ftype=='type3'){
		if(fchecked!=''){
			document.getElementById('type'+fvalue).className = "swacth-btn medium radius";
			var t3 =  '';
		}else{
			var t3 =  fvalue;
		}
	}else{
		var t3 = "<?php echo $_GET['type3']?>";
	}

	if(ftype=='price'){
		var t4 = document.getElementById('amount').value;
	}else{
		var t4 = "<?php echo $_GET['price']?>";
	}
	document.location.href="<?php echo $opt?>&type1="+t1+"&type2="+t2+"&type3="+t3+"&price="+t4+"&sortby="+t5;
}


</script>