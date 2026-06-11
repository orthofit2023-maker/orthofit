<?php
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


$ordpg='
<div style="width:95%;clear:both;">';
	$shiptot=0;$tot=0;
	$sqllist="select c.*, p.produrl, p.prodcode from ccd9cart c join ccd9products p on p.prodid=c.prodid where c.orderid='$orderid' order by c.cartid";
	$result = $mysqli->query($sqllist);
	$num_rows = mysqli_num_rows($result);
	if($num_rows>0){ $n=0;
	
	$ordpg=$ordpg.'<div style="width:100%;">';
			

				
					while($row=$result->fetch_array()){$n++;
					$tot=$tot+($row['finalprice']*$row['prodqty']);
					$shiptot=$shiptot+($row['shipprice']*$row['prodqty']);
					$url=getprodurl($row['produrl']);
					$showcur=showcursymb($row['prodcur']);
				
					$ordpg=$ordpg.'<div style="clear:both;padding-top:10px;">
						<div style="width:10%; float:left">
							<a href="'.$url.'"><img src="'.getprodimg($row['produrl']).'"></a>
						</div>
						<div style="width:65%; float:left; display:inline;padding: 0 10px 0 10px">
							<a href="'.$url.'">'.$row['prodname'].'</a>
							<BR><small>Model: '.$row['prodcode'].' | Color: '.$row['prodcolor'].' | Qty: '.$row['prodqty'].'</small>
							'.($row['measlist']!='' ? '<BR><small>'.getmeas($row['measlist']).'</small>' : '').'
						</div>
						<div style="width:25%; float:right">
						'.$showcur.(($row['finalprice']*$row['prodqty'])+ ($row['shipprice']*$row['prodqty'])).'
						</div>
					</div>';
				}
			
	}
		$ordpg=$ordpg.'<div style="clear:both;padding-bottom:20px;"></div>
		
	</div>
	<div style="width:100%;">
		<div style="width:60%; float:left">&nbsp;</div>
		<div style="width:40%; float:right">
			<h4 style="clear:both;padding-bottom:20px;">Order Summary</h4>';
			if($num_rows>0){
				if($discount>0 || $shiptot>0){
			$ordpg=$ordpg.'<div style="width:50%; float:left">Sub Total</div>
			<div style="width:50%; float:right">'.$showcur.$tot.'</div>';
				}
			if($discount>0){
			$ordpg=$ordpg.'<div style="width:50%; float:left">Discount</div>
			<div style="width:50%; float:right">'.$showcur.$discount.'</div>';
			} 
			if($shiptot>0){
			$ordpg=$ordpg.'<div style="width:50%; float:left">Shipping</div>
			<div style="width:50%; float:right">'.$showcur.$shiptot.'</div>';
			}
			$ordpg=$ordpg.'<div style="width:50%; float:left">Total Payable</div>
			<div style="width:50%; float:right">'.$showcur.($tot+$shiptot-$discount).'</div>';

			}
		$ordpg=$ordpg.'</div>
	</div>
</div>';


?>