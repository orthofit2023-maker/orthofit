<?php
//echo "select * from ccd9orders where orderid='$orderid' ".($opt=='myaccount' ? " and status>0 " : " and status=0 ")."";
$resord=query_first("select * from ccd9orders where orderid='$orderid' ".($opt=='myaccount' || $opt=='success' ? " and status>0 " : " and status=0 ")."");
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
$ordref = dbval($resord['ordref']);
list($dtyr,$mm,$dd)=explode("-",$resord['orddate']);

//$sqlcartord="select c.*, ph.photo, p.produrl, p.prodcode, ct.typeid as catid, ct.typename as catname, ct.typevalue as caturl, t1.typename as vprodmeas, t2.typename as vprodsize, t3.typename as vprodcolor  from ccd9cart c join ccd9products p on p.prodid=c.prodid left join ccd9prod2cat pt on pt.prodid=p.prodid left join ccd9types ct on pt.catid=ct.typeid and ct.opt='2' left join ccd9types t1 on c.prodmeas=t1.typeid and t1.opt='3' left  join ccd9types t2 on c.prodsize=t2.typeid and t2.opt='4'  left  join ccd9types t3 on c.prodcolor=t3.typeid and t3.opt='7' left  join ccd9prod2type3 pt3 on pt3.prodid=p.prodid and c.prodcolor=pt3.typeid left join ccd9prodphotos ph on ph.prodid=c.prodid and ph.type1=c.prodmeas and ph.type3=c.prodcolor where c.compid='".$_SESSION['compid']."' and c.orderid='$orderid' and c.status='1' group by c.cartid order by c.cartid;";

$sqlcartord="select c.*, ph.photo, p.produrl, p.prodcode, ct.typeid as catid, ct.typename as catname, ct.typevalue as caturl, t1.typename as vprodmeas, t2.typename as vprodsize, t3.typename as vprodcolor  from ccd9cart c join ccd9products p on p.prodid=c.prodid left join ccd9prod2cat pt on pt.prodid=p.prodid left join ccd9types ct on pt.catid=ct.typeid and ct.opt='2' left join ccd9types t1 on c.prodmeas=t1.typeid and t1.opt='3' left  join ccd9types t2 on c.prodsize=t2.typeid and t2.opt='4'  left  join ccd9types t3 on c.prodcolor=t3.typeid and t3.opt='7' left  join ccd9prod2type3 pt3 on pt3.prodid=p.prodid and c.prodcolor=pt3.typeid left join ccd9prodphotos ph on ph.prodid=c.prodid and ph.type1=c.prodmeas and ph.type3=c.prodcolor where c.compid='".$_SESSION['compid']."'  ".(($opt=='myaccount'  || $opt=='success') && $orderid>0 ? " and c.status>0 and c.orderid='$orderid' " : " and c.status=0 ")."  group by c.cartid order by c.cartid;";
//echo $sqlcartord;
$resultcart = $mysqli->query($sqlcartord);
//$numcart = mysqli_num_rows($resultcart);

$ordtxt='
<div class="table-responsive order-table style1"> 
	<table class="table table-bordered align-middle table-hover text-center mb-1">
		<thead>
			<tr>
				<th>Product</th>
				<th class="text-start">Name</th>
				<th>Price</th>
				<th>Qty</th>
				<th>Subtotal</th>
			</tr>
		</thead>
		<tbody>';
			
			$shippingamt=0;$tot=0;$cartjscode='';$checkoutjscode=""; $gcincart=0;
			while($row=$resultcart->fetch_array()){ $n++;
			$url=getprodurl($row['produrl'], $row['caturl'])."&type1=".$row['prodmeas']."&type2=".$row['prodsize']."&type3=".$row['prodcolor'];
			$tot=$tot+($row['finalprice']*$row['prodqty']);
			$showcur=showcursymb($row['prodcur']);
			list($prodphoto1, $prodphoto2) = getprodphotos(trim($row['photo']), trim($row['images']));
			$ordtxt=$ordtxt.'
			<tr>
				<td class="thumbImg"><a href="'.$url.'" class="thumb d-inline-block"><img class="cart__image" src="'.$prodphoto1.'" alt="'.dbval($row['prodname']).'" width="80" /></a></td>
				<td class="text-start">
					<a href="'.$url.'">'.dbval($row['prodname']).'</a>
					<div class="cart__meta-text">
						'.($row['vprodmeas']!='NA' ? '<b>Fit</b>: '.$row['vprodmeas'] : '' ).(trim($row['vprodcolor'])!='NA' ? ' <b>Color</b>: '.$row['vprodcolor'] : '' ).(trim($row['prodsize'])!='NA' ? ' <b>Size</b>: '.trim($row['prodsize']) : '').(trim($row['comments'])!='' ? '<br>'.trim($row['comments']) : '').'
					</div>
				</td>
				<td>'.$showcur.number_format($row['finalprice']).'</td>
				<td>'.$row['prodqty'].'</td>
				<td class="fw-500">'.$showcur.number_format(($row['finalprice']*$row['prodqty'])+ ($row['shipprice']*$row['prodqty'])).'</td>
			</tr>';
			}
			$shippingamt=0;

			
		$ordtxt=$ordtxt.'</tbody>
		<tfoot class="font-weight-600">
			<tr>
				<td colspan="4" class="text-end fw-bolder">Subtotal </td>
				<td class="fw-bolder">'.$showcur.number_format($tot).'</td>
			</tr>';
			if($discountamt>0){
			$ordtxt=$ordtxt.'<tr>
				<td colspan="4" class="text-end fw-bolder">Discount </td>
				<td class="fw-bolder">'.$showcur.number_format($discountamt).'</td>
			</tr>';
			} 
			if($shippingamt>0){
			$ordtxt=$ordtxt.'<tr>
				<td colspan="4" class="text-end fw-bolder">Shipping </td>
				<td class="fw-bolder">'.$showcur.number_format($shippingamt).'</td>
			</tr>';
			} 
			$ordtotal=$tot+$shippingamt-$discountamt;
			
			$ordtxt=$ordtxt.'<tr>
				<td colspan="4" class="text-end fw-bolder">Total</td>
				<td class="fw-bolder">'.$showcur.number_format($ordtotal).'</td>
			</tr>
		</tfoot>
	</table>
</div>';
?>