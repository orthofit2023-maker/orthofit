<?php
$resord=query_first("select * from ccd9orders where orderid='$orderid' ");//and status>0
$ordtotal = dbval($resord['ordtotal']);
//$taxamt = dbval($resord['ordtax']);
$shippingamt = dbval($resord['shippingamt']);
$discountamt = dbval($resord['discountamt']);
$cartcur=dbval($resord['ordcur']);
$disccode = dbval($resord['disccode']);
//$addressid = dbval($resord['addressid']);
//$baddressid = dbval($resord['billing_addressid']);

$billing_email = $resord['email'];
$billing_city=dbval($resord['billing_city']);
$billing_state=dbval($resord['billing_state']);
$billing_zipcode=dbval($resord['billing_zipcode']);
$billing_address_1=dbval($resord['billing_address']);
$billing_address_2=dbval($resord['billing_address1']);
$billing_username=dbval($resord['billing_username']);
$billing_lastname=dbval($resord['billing_lastname']);
$billing_phone=dbval($resord['phone']);
$billing_country=dbval($resord['billing_country']);

$shipping_email = $resord['email'];
$shipping_city=dbval($resord['city']);
$shipping_state=dbval($resord['state']);
$shipping_zipcode=dbval($resord['zipcode']);
$shipping_address_1=dbval($resord['address']);
$shipping_address_2=dbval($resord['address1']);
$shipping_username=dbval($resord['username']);
$shipping_lastname=dbval($resord['lastname']);
$shipping_phone=dbval($resord['shipping_phone']);
$shipping_country=dbval($resord['country']);
$trackcode=dbval($resord['trackcode']);
$sent2fb=dbval($resord['sent2fb']);
list($dtyr,$mm,$dd)=explode("-",$resord['orddate']);


$ordpg='
<div class="row">';
	$shiptot=0;$tot=0; $cartjscode=''; $checkoutjscode=""; $contentidscode=''; $uetqjscode=''; $cartga4code='';
	$sqllist="select c.*, p.produrl, p.prodcode, p.prodcolor, ct.typeid as catid, ct.typename as catname from ccd9cart c join ccd9products p on p.prodid=c.prodid join ccd9prod2cat pt on pt.prodid=p.prodid join ccd9types ct on pt.catid=ct.typeid and ct.opt='2' and ct.typevalue2='1' where c.orderid='$orderid' and c.status='1' group by c.prodid order by c.cartid";
	$resultord = $mysqli->query($sqllist);
	$num_rows = mysqli_num_rows($resultord);
	if($num_rows>0){ $n=0;
	
	$ordpg=$ordpg.'<div class="col-md-9">';
			

				
					while($roword=$resultord->fetch_array()){$n++;
					$tot=$tot+($roword['finalprice']*$roword['prodqty']);
					//$shiptot=$shiptot+($roword['shipprice']*$roword['prodqty']);
					$url=getprodurl($roword['produrl']);
					$showcur=showcursymb($roword['prodcur']);
$cartjscode=$cartjscode."
ga('ec:addProduct', {
  'id': '".$roword['prodcode']."',
  'name': '".$roword['prodname']."',
  'brand': 'PAYALSINGHAL',
  'price': '".$roword['finalprice']."',
  'quantity': '".$roword['prodqty']."'
});

";		

if($n>1)$contentidscode=$contentidscode.",";
$contentidscode=$contentidscode.'"'.$roword['prodcode'].'"';

if($n==1)$uetqprodcode=$roword['prodcode'];
if($n>1)$uetqjscode=$uetqjscode.",";
$uetqjscode=$uetqjscode."{'id': '".$roword['prodcode']."', 'quantity': '".$roword['prodqty']."', 'price': '".$roword['finalprice']."' }";


if($n>1)$checkoutjscode=$checkoutjscode.",";
$checkoutjscode=$checkoutjscode."				
{
  'name': '".$roword['prodname']."',
  'id': '".$roword['prodcode']."',
  'price': '".$roword['finalprice']."',
  'brand': 'PAYALSINGHAL',
  'quantity': '".$roword['prodqty']."'
}	
";

if($n>1)$cartga4code=$cartga4code.",";
$cartga4code=$cartga4code.'
{
  item_id: "'.$roword['prodcode'].'",
  item_name: "'.dbval($roword['prodname']).'",
  affiliation: "PAYALSINGHAL",
  //coupon: "SUMMER_FUN",
  //discount: 2.22,
  index: '.$n.',
  item_brand: "PAYALSINGHAL",
  item_category: "'.dbval($row['catname']).'",
  //item_category2: "Adult",
  //item_category3: "Shirts",
  //item_category4: "Crew",
  //item_category5: "Short sleeve",
  item_list_id: "products_'.$roword['catid'].'",
  item_list_name: "'.dbval($roword['catname']).'",
  item_variant: "'.dbval($roword['prodcolor']).'",
  //location_id: "ChIJIQBpAG2ahYAR_6128GcTUEo",
  price: "'.$roword['finalprice'].'",
  //promotion_id: "P_12345",
  //promotion_name: "Summer Sale",
  quantity: "'.$roword['prodqty'].'"
}';


					$ordpg=$ordpg.'<div class="row pb20 pt20">
						<div class="col-md-2 col-xs-3">
							<a href="'.$url.'"><img src="'.$serverurl.getprodimg($roword['produrl'],'a','3').'"></a>
						</div>
						<div class="col-md-8 col-xs-6">
							<a href="'.$url.'">'.$roword['prodname'].'</a>
							<br><small>Style Code: '.$roword['prodcode'].' | Color: '.(trim($roword['prodcolor'])!='' ? $roword['prodcolor'] : 'NA' ).' | Qty: '.$roword['prodqty'].'</small>
							'.(!checkgiftcat($roword['prodid']) && $roword['measlist']!='' ? '<BR><small>'.getmeas($roword['measlist']).'</small>' : '').($roword['measlist']=='' && (!getnoguidecat($roword['prodid'])) ? '<BR><small>'.showhgthlhgt($roword['height'],$roword['heelheight']).'</small>' : '').(checkgiftcat($roword['prodid']) && $roword['discemail']!='' ? '<br><small>Recipient\'s email address: '.$roword['discemail'].'</small>' : '').'
						</div>
						<div class="col-md-2 col-xs-3 pt5">
						'.$showcur.number_format(($roword['finalprice']*$roword['prodqty'])+ ($roword['shipprice']*$roword['prodqty'])).'
						</div>
					</div>';
				}
			
	}
		$ordpg=$ordpg.'<div class="clear"></div>
		
	</div>
	<div class="col-md-3">
		<div class="row pb20">
			<div class="col-md-12 col-xs-12 pb20">
			<h4 class="box-heading">Order Summary</h4></div>';
			if($num_rows>0){
				if($discountamt>0 || $shippingamt>0){
			$ordpg=$ordpg.'<div class="col-md-6 col-xs-6">Sub Total</div>
			<div class="col-md-6 col-xs-6 pull-right">'.$showcur.number_format($tot).'</div>';
				}
			if($discountamt>0){
			$ordpg=$ordpg.'<div class="col-md-6 col-xs-6">Discount</div>
			<div class="col-md-6 col-xs-6 pull-right">'.$showcur.number_format($discountamt).'</div>';
			} 
			if($shippingamt>0){
			$ordpg=$ordpg.'<div class="col-md-6 col-xs-6">Shipping</div>
			<div class="col-md-6 col-xs-6 pull-right">'.$showcur.number_format($shippingamt).'</div>';
			}
			$ordpg=$ordpg.'<div class="col-md-6 col-xs-6">Total Payable</div>
			<div class="col-md-6 col-xs-6 pull-right">'.$showcur.number_format($tot+$shippingamt-$discountamt).'</div>';

			}
		$ordpg=$ordpg.'</div>
	</div>
</div>
<div class="row" style="padding-top:20px">
	<div class="col-md-6 col-xs-12">
	<h5>Billing Address:</h5><br/>
	'.dbval($billing_username).' '.dbval($billing_lastname).'<br/>'.
	dbval($billing_address_1).' '.dbval($billing_address_2).'<br/>'.dbval($billing_city).', '.dbval($billing_zipcode).'<br/> '.dbval($billing_state).', '.getcountry($billing_country).'<br/><b>Phone:</b> '.trim($billing_phone).'
	</div>
	<div class="col-md-6 col-xs-12 pull-right">
	<h5>Shipping Address:</h5><br/>
	'.dbval($shipping_username).' '.dbval($shipping_lastname).'<br/>'.
	dbval($shipping_address_1).' '.dbval($shipping_address_2).'<br/>'.dbval($shipping_city).', '.dbval($shipping_zipcode).'<br/> '.dbval($shipping_state).', '.getcountry($shipping_country).'
	<br/><b>Phone:</b> '.dbval($shipping_phone).'<br/><b>eMail</b>: '.dbval($shipping_email).'
	</div>
</div>
';


?>