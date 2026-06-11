<?php 
$colspan=3;
$ordpg='
<table width="650" border="1" cellpadding="5" cellspacing="0" class="table order-table">
	<thead>';
		if($filename=="order.php"){
			$ordpg=$ordpg.'<th class="hidden-print">&nbsp;</th>';
		}
		$ordpg=$ordpg.'<th>Product Name</th>
		<th>Size</th>
		<th>Quantity</th>
		<th>Price</th>
		<th>Total</th>
	</thead>
	<tbody>
		';
		$resord=query_first("select * from ccd9orders where orderid='$orderid'");
		$ordtotal = dbval($resord['ordtotal']);
		//$taxamt = dbval($resord['ordtax']);
		$shippingamt = dbval($resord['shippingamt']);
		$discountamt = dbval($resord['discountamt']);
		$cartcur=dbval($resord['ordcur']);
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
		$shipping_phone=dbval($resord['phone']);
		$shipping_country=dbval($resord['country']);
		$trackcode=dbval($resord['trackcode']);
		list($dtyr,$mm,$dd)=explode("-",$resord['orddate']);

		$result = $mysqli->query("select c.*, p.prodcode from ccd9cart c join ccd9products p on p.prodid=c.prodid where c.orderid='$orderid' order by c.cartid");
		$num_rows = mysqli_num_rows($result);
		$n=0; $tot=0;
		while($rescart=$result->fetch_array()){
			$n++; $tot=$tot+round($rescart['finalprice']*$rescart['prodqty'],0);
		
		 $ordpg=$ordpg.'<tr>';
		 if($filename=="order.php"){
			 $ordpg=$ordpg.'<td class="hidden-print"><a href="orderchart.php?orderid='.$orderid.'&prodid='.$rescart['prodid'].'&q='.$n.'&t='.$num_rows.'" target="_blank"><i class="fa fa-pencil"></i></a><BR>
			 <a href="orderchart.php?orderid='.$orderid.'&prodid='.$rescart['prodid'].'&q='.$n.'&t='.$num_rows.'&dl=xls" target="_blank"><i class="fa fa-file-excel-o"></i></a>
			 </td>';
		 }
		 $ordpg=$ordpg.'<td>'.$rescart['prodname'].'
		 <BR><small>Model: '.$rescart['prodcode'].' | Color: '.$rescart['prodcolor'].'</small>'.
		 ($rescart['measlist']!='' ? '<BR><small>'.getmeas($rescart['measlist']).'</small>' : '').'
		 </td> 
			<td>'.$rescart['prodsize'].'</td>
			<td>'.$rescart['prodqty'].'</td>
			<td>'.$cartcur.' '.$rescart['finalprice'].'</td>
			<td>'.$cartcur.' '.round($rescart['finalprice']* $rescart['prodqty'],0).'</td>
		</tr>';
		}
		if($discountamt>0 || $shippingamt>0 || $taxamt>0){
		$ordpg=$ordpg.'
		<tr>';
			if($filename=="order.php"){
				$ordpg=$ordpg.'<td class="hidden-print"></td>';
			}
			$ordpg=$ordpg.'<td colspan="'.$colspan.'"></td>                                                                
			<td><strong>Sub-Total</strong></td>
			<td>'.$cartcur.' '.$tot.'</td>
		</tr>';
		}
		if($taxamt>0){
		$ordpg=$ordpg.'<tr>';
			if($filename=="order.php"){
				$ordpg=$ordpg.'<td class="hidden-print"></td>';
			}
			$ordpg=$ordpg.'
			<td colspan="'.$colspan.'"></td>                                                                
			<td><strong>GST 15%</strong></td>
			<td>'.$cartcur.' '.$taxamt.'</td>
		</tr>';
		}
		if($discountamt>0){
		$ordpg=$ordpg.'<tr>';
			if($filename=="order.php"){
				$ordpg=$ordpg.'<td class="hidden-print"></td>';
			}
			$ordpg=$ordpg.'
			<td colspan="'.$colspan.'"></td>                                                                
			<td><strong>Discount</strong></td>
			<td>'.$cartcur.' '.$discountamt.'</td>
		</tr>';
		}
		if($shippingamt>0){
		$ordpg=$ordpg.'<tr>';
			if($filename=="order.php"){
				$ordpg=$ordpg.'<td class="hidden-print"></td>';
			}
			$ordpg=$ordpg.'
			<td colspan="'.$colspan.'"></td>                                                                
			<td><strong>Shipping</strong></td>
			<td>'.$cartcur.' '.$shippingamt.'</td>
		</tr>';
		}
		$ordpg=$ordpg.'<tr>';
			if($filename=="order.php"){
				$ordpg=$ordpg.'<td class="hidden-print"></td>';
			}
			$ordpg=$ordpg.'
			<td colspan="'.$colspan.'"></td>                                                                
			<td><strong>Total</strong></td>
			<td>'.$cartcur.' '.$ordtotal.'</td>
		</tr>';
		$ordpg=$ordpg.'<tr>
			<td colspan="'.$colspan.'">
			<div style="float:left;padding-right:150px;">
			<b>Billing Address:</b><br/>
			'.dbval($billing_username).' '.dbval($billing_lastname).'<br/>'.
			dbval($billing_address_1).' '.dbval($billing_address_2).'<br/>'.dbval($billing_city).', '.dbval($billing_zipcode).'<br/> '.dbval($billing_state).', '.getcountry($billing_country).'
			</div>
			<div style="display:inline">
			<b>Shipping Address:</b><br/>
			'.dbval($shipping_username).' '.dbval($shipping_lastname).'<br/>'.
			dbval($shipping_address_1).' '.dbval($shipping_address_2).'<br/>'.dbval($shipping_city).', '.dbval($shipping_zipcode).'<br/> '.dbval($shipping_state).', '.getcountry($shipping_country).'
			<br/><b>Phone:</b> '.dbval($shipping_phone).'<br/><b>eMail</b>: '.dbval($shipping_email).'
			</div>
			</td>
		</tr>';
		$ordpg=$ordpg.'</tbody>
</table>';
?>