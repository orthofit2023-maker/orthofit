<?php
session_start();

if(!(strstr($_SERVER['SCRIPT_NAME'], 'index.php'))){
	include("db5conn.php");
	if(!(strstr($_SERVER['SERVER_NAME'], $GLOBALS['serverdomain']))){
		exit();
	}
	if($_GET['opt']!='')$opt=inpval($_GET['opt']);
	if($opt!=''){
		$sql="select c.typevalue, c.typeid, c.typename from ccd9types c where c.typevalue='".trim($opt)."'";
		$query=$mysqli->query($sql);
		if ($resin=$query->fetch_array()){
			$catid=$resin['typeid'];
		}
	}

}else if(!(strstr($_SERVER['SERVER_NAME'], $GLOBALS['serverdomain']))){
	exit();
}
if($_GET['catid']!='')$catid=inpval($_GET['catid']);
$type1=inpval($_GET['type1']);
//$catid=inpval($_GET['catid']);
$type2=inpval($_GET['type2']);
$type3=inpval($_GET['type3']);
//echo 'serverurl'.$serverurl.'<br>';



$arrq=array();
if(trim($_GET['q'])!=''){
	$maintitle="Search Result for ".$_GET['q'];
	$mysqli->query("insert into ccd9prodsrch (compid, srchkeys, sessionid) values ('".$_SESSION['compid']."', '".inpval($_GET['q'])."', '".session_id()."')");

	$q=trim($_GET['q']);

	if(strstr($q,' ')){
		$arrq=explode(' ',$q);
	}else{
		$arrq[0]=$q;
	}
}
$sqllist="SELECT c.*, IF(CURDATE() between c.offerfrdate and c.offertodate, '1', '0') as isoffer, IF(CURDATE() between c.discfrdate and c.disctodate, '1', '0') as isdiscount ".($_SESSION['compid']>0 ? ", w.wishid " : "").($opt=='sale' ? ", ct.status as salestatus" :"")." from ccd9products c left join ccd9prod2cat t on t.prodid=c.prodid ".($type1>0 ? " left join ccd9prod2type1 v1 on v1.prodid=c.prodid " : "" ).($type2>0 ? " left join ccd9prod2type2 v2 on v2.prodid=c.prodid " : "").($type3>0 ? " left join ccd9prod2type3 v3 on v3.prodid=c.prodid " : "").($_SESSION['compid']>0 ? " left join ccd9wishlist w on w.prodid=c.prodid and w.compid='".$_SESSION['compid']."'" : "").($opt=='sale' ? " left join ccd9cart ct on ct.prodid=c.prodid " : "" )." where 1=1 ".($opt=='sale' ? " and (c.prodstatus='1' or c.prodstatus='0') " : " and c.prodstatus='1' ").($_SESSION['myCUR']=="US $" ? " and (c.zone='USD' or c.zone='ALL')" : " and (c.zone='INR' or c.zone='ALL')")." and c.prodid not in (191,192,193,131,132,133,135,136,134,137,164,165,1113,1114,1115)"; //,481,482,483,484,476,477,478,479,480

if($_GET['type1']>0){
	$sqllist=$sqllist." and ( v1.typeid = '".inpval($type1)."') ";
}
if($_GET['type2']>0){
	$sqllist=$sqllist." and ( v2.typeid = '".inpval($type2)."') ";
}
if($_GET['type3']>0){
	$sqllist=$sqllist." and ( v3.typeid = '".inpval($type3)."') ";
}
if($catid>0){
	$sqllist=$sqllist." and ( t.catid = '".inpval($catid)."') ";
}
$andorarr = array('in', 'or', 'with', 'and', '&', '+', '-');
if(count($arrq)>0){
	for($z=0;$z<count($arrq);$z++){
		if($arrq[$z]!='' && !in_array(trim($arrq[$z]), $andorarr)){
			$sqllist=$sqllist." and ( c.prodcode like '%".inpval($arrq[$z])."%' or c.prodname like '%".inpval($arrq[$z])."%' or c.proddesc like '%".inpval($arrq[$z])."%') ";
		}
	}
}

$sqllist=$sqllist." group by c.prodid ";
if($sortby=="" || $sortby=="new"){
	$sqllist=$sqllist." order by c.entrydate desc, c.prodid"; // 
}else if($sortby=="price"){
	$sqllist=$sqllist." order by c.prodprice"; // 
}else if($sortby=="priceh"){
	$sqllist=$sqllist." order by c.prodprice desc"; // 
}
//$sqllist=$sqllist." limit 0, 12";
//echo $sqllist;
//exit();
$result = $mysqli->query($sqllist);
$num_rows = mysqli_num_rows($result);

$perPage = 16;
$page = 1;
if(!empty($_GET["page"])) {
$page = $_GET["page"];
}

$start = ($page-1)*$perPage;
if($start < 0) $start = 0;

$sqllist =  $sqllist. " limit " . $start . "," . $perPage; 
//echo $sqllist.'- from : '.$start.' to '.$num_rows.' page '.$page.' pages'.$pages.'<br>';
if(empty($_GET["rowcount"])) {
$_GET["rowcount"] = $num_rows;
}
$pages  = ceil($_GET["rowcount"]/$perPage);

$result = $mysqli->query($sqllist);

$output = '';
$output .= '<input type="hidden" class="pagenum" value="' . $page . '" /><input type="hidden" id="rowcount" class="total-page" value="' . $pages . '" />';
if ($num_rows>0){ $i=0;
//echo 'from : '.$start.' to '.$num_rows.' page '.$page.' pages'.$pages.'<br>';;
$output .= '<div class="row ">';
while($row=$result->fetch_array()){ 
		if(in_array(dbval($row['prodcode']), $acccodes)){
			$dimy=229;
		}else{
			$dimy=512;
		}
		
		generateimages($imgpath.dbval($row['produrl']), $imgcodepath.dbval($row['prodcode']), $dimy);
		list($prodprice, $priceval)=getprice($row);
		$prodimg=$serverurl.getprodimg($row['produrl'],'a','3');
	
		$output .= '<div class="col-sm-3 col-xs-12">
			<div class="thumbnail">
				<div class="image-thumb">
					<a href="'.($row['prodstatus']==1 ? getprodurl($row['produrl'],$opt) : 'Javascript:return false;').'" class="block"><img src="'.$prodimg.'" alt="'.$row['prodalt'].'" title="'.$row['prodalt'].'" /></a>
				</div>
				<div class="caption">
					<h1 class="product-name">'.$row['prodname'].'</h1>
					<h2 class="product-price">'.$prodprice;
					if($row['prodstatus']==0){
					$output .= '<button class="btn btn-default sold-btn">SOLD OUT!</button>';
					}
					$output .= '</h2>';
					if(getnoguidecat($row['prodid'])){
					$output .= '<div class="addtocart"><a href="Javascript:addtocartbtn('.$row['prodid'].')"><img src="images/shoppingbag-icon.png" border="0" alt="" width="14"></a></div>';
					}
					$output .= '<div class="wishlist'.($row['wishid'] ? ' added' : '').'" id="wish'.$row['prodid'].'"><a href="Javascript:addtowishbtn('.$row['prodid'].')"><i class="fa fa-heart-o"></i></a></div>
				</div>
			</div>
		</div>';
	$i++;  if($i%4==0) $output .= '</div><div class="row">'; }
	$output .= '</div>';
}else{
	$output .= '<div align="center">Sorry, no products available in this category for now. Please check after few days.</div>';
}
$output .= '</div>';
print $output;

?>
