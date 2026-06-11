<?php 
$colspan=1;
$ordpg='
<table border="1" cellpadding="5" cellspacing="0" class="table order-table">
	<tr><thead>';

		$ordpg=$ordpg.'<th style="width:90px;">&nbsp;</th><th style="width:400px;">Product</th><th style="width:90px;">Price</th></tr></thead><tbody>';
		$resord=query_first("select * from ccd9orders where orderid='$orderid' and status>0");
		$ordtotal = dbval($resord['ordtotal']);
		//$taxamt = dbval($resord['ordtax']);
		$shippingamt = dbval($resord['shippingamt']);
		$discountamt = dbval($resord['discountamt']);
		$cartcur=dbval($resord['ordcur']);
		$paymentid = dbval($resord['paymentid']);
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
		$ordstatus=dbval($resord['status']);
		$comments = dbval($resord['comments']);
		list($dtyr,$mm,$dd)=explode("-",$resord['orddate']);

		$sqlcartlist="select c.*, ph.photo, p.produrl, p.prodcode, ct.typeid as catid, ct.typename as catname, ct.typevalue as caturl, t1.typename as vprodmeas, t2.typename as vprodsize, t3.typename as vprodcolor  from ccd9cart c join ccd9products p on p.prodid=c.prodid left join ccd9prod2cat pt on pt.prodid=p.prodid left join ccd9types ct on pt.catid=ct.typeid and ct.opt='2' left join ccd9types t1 on c.prodmeas=t1.typeid and t1.opt='3' left  join ccd9types t2 on c.prodsize=t2.typeid and t2.opt='4'  left  join ccd9types t3 on c.prodcolor=t3.typeid and t3.opt='7' left  join ccd9prod2type3 pt3 on pt3.prodid=p.prodid and c.prodcolor=pt3.typeid left join ccd9prodphotos ph on ph.prodid=c.prodid and ph.type1=c.prodmeas and ph.type3=c.prodcolor where c.orderid='$orderid' and c.status='1' group by c.cartid order by c.cartid";
		$result = $mysqli->query($sqlcartlist);
		$num_rows = mysqli_num_rows($result);
		$cartrows=$num_rows; $discemail=array();
		$n=0; $tot=0; $ordhasgc=0; $tstype=0; $shiptime='';
		while($rescart=$result->fetch_array()){ $n++;
			$tot=$tot+round($rescart['finalprice']*$rescart['prodqty'],0);
			if($filename=="order.php"){
				$showcur=showcursymb($rescart['prodcur']);
			}else{
				$showcur=$cartcur;
			}

			//if($rescart['shipid']>$tstype){
			//	$tstype=$rescart['shipid'];
			//	$shiptime=$rescart['shiptime'];
			//}

			if(checkgiftcat($rescart['prodid']) ){ //text for gift card
				//$ordpgend="<p>The Gift Card you have ordered will be emailed to the Recipient's email address (".$rescart['discemail'].") that you have provided within 24-48 working hours and you will receive a confirmation email once the Gift Card has been emailed to the Recipient.</p>";
				array_push($discemail,$rescart['discemail']);
				$ordhasgc++; 
			}
		list($prodphoto1, $prodphoto2) = getprodphotos(trim($rescart['photo']), trim($rescart['images']));
		 $ordpg=$ordpg.'<tr>';
		 $ordpg=$ordpg.'<td style="width:90px;"><a href="'.getprodurl($rescart['produrl']).'" target="_blank"><img style="width:90px;" src="'.$prodphoto1.'"></a></td>';
		 $ordpg=$ordpg.'<td valign="top">'.dbval($rescart['prodname']);
		 //$ordpg=$ordpg.'<br><small>Style Code: '.$rescart['prodcode'].' | Color: '.(trim($rescart['prodcolor'])!='' ? $rescart['prodcolor'] : 'NA' ).' | Qty: '.$rescart['prodqty'].' | Size: '.dbval($rescart['prodsize']);
		 $ordpg=$ordpg.'<br><small>'.($rescart['vprodmeas']!='NA' ? '<b>Fit</b>: '.$rescart['vprodmeas'] : '' ).(trim($rescart['vprodcolor'])!='NA' ? ' <b>Color</b>: '.$rescart['vprodcolor'] : '' ).(trim($rescart['prodsize'])!='NA' ? ' <b>Size</b>: '.trim($rescart['prodsize']) : '').' <b>Qty</b>: '.$rescart['prodqty'];
			 
		 //$ordpg=$ordpg.(!checkgiftcat($roword['prodid']) && $rescart['measlist']!='' ? '<br>'.getmeas($rescart['measlist']) : ( (!getnoguidecat($rescart['prodid'])) ? showhgthlhgt($rescart['height'],$rescart['heelheight']) : ''));
		 //$ordpg=$ordpg.(checkgiftcat($rescart['prodid']) ? '<br>Recipient\'s email address: '.$rescart['discemail'].'' : '');
		 $ordpg=$ordpg.(trim($rescart['comments']) ? '<br>'.(str_replace("<table ","<table border='1' cellpadding='5' cellspacing='0' width='100%' ",$rescart['comments'])) : '');
		 $ordpg=$ordpg.'</small></td><td nowrap valign="top" style="width:90px;">'.$showcur.' '.number_format(round($rescart['finalprice']* $rescart['prodqty'],0)).'</td>';
		 $ordpg=$ordpg.'</tr>';
		}
		if($discountamt>0 || $shippingamt>0 || $taxamt>0){
		$ordpg=$ordpg.'
		<tr>';
			$ordpg=$ordpg.'<td align="right" colspan="2"><strong>Sub-Total</strong></td>                                                                
			<td>'.$showcur.' '.number_format($tot).'</td>
		</tr>';
		}
		if($taxamt>0){
		$ordpg=$ordpg.'<tr>';
			$ordpg=$ordpg.'
			<td align="right" colspan="2"><strong>GST 15%</strong></td>                                                                
			<td>'.$showcur.' '.number_format($taxamt).'</td>
		</tr>';
		}
		if($discountamt>0){
		$ordpg=$ordpg.'<tr>';
			$ordpg=$ordpg.'
			<td align="right" colspan="2"><strong>Discount</strong></td>                                                                
			<td>'.$showcur.' '.number_format($discountamt).'</td>
		</tr>';
		}
		if($shippingamt>0){
		$ordpg=$ordpg.'<tr>';
			$ordpg=$ordpg.'
			<td align="right" colspan="2"><strong>Shipping</strong></td>                                                                
			<td>'.$showcur.' '.number_format($shippingamt).'</td>
		</tr>';
		}
		$ordpg=$ordpg.'<tr>';
			$ordpg=$ordpg.'
			<td align="right" colspan="2"><strong>Total</strong></td>                                                                
			<td>'.$showcur.' '.number_format($ordtotal).'</td>
		</tr>';
		if($comments!=''){
		$ordpg=$ordpg.'<tr>';
		$ordpg=$ordpg.'<td colspan="3"><b>Comments:</b><br/>
			'.$comments.' </td></tr>';
		}
		$ordpg=$ordpg.'<tr>';
			$ordpg=$ordpg.'
			<td colspan="3">
			<div style="float:left;width:50%">
			<b>Billing Address:</b><br/>
			'.trim($billing_username).' '.trim($billing_lastname).'<br/>'.
			trim($billing_address_1).' '.trim($billing_address_2).'<br/>'.trim($billing_city).', '.trim($billing_zipcode).'<br/> '.trim($billing_state).', '.getcountry($billing_country).'<br/><b>Phone:</b> '.trim($billing_phone).'
			</div>
			<div style="display:inline">
			<b>Shipping Address:</b><br/>
			'.trim($shipping_username).' '.trim($shipping_lastname).'<br/>'.
			trim($shipping_address_1).' '.trim($shipping_address_2).'<br/>'.trim($shipping_city).', '.trim($shipping_zipcode).'<br/> '.trim($shipping_state).', '.getcountry($shipping_country).'
			<br/><b>Phone:</b> '.trim($shipping_phone).'<br/><b>eMail</b>: '.trim($shipping_email).'
			</div>
			</td>
		</tr>';
		$ordpg=$ordpg.'
</tbody></table>';

?>