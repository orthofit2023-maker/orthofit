<?php 

$cartpg='
<table border="1" cellpadding="5" cellspacing="0" class="table order-table" style="width:600px;" width="600">
	<thead>';
		$cartpg=$cartpg.'<th colspan="2">Product</th><th>Price</th>
	</thead>
	<tbody>
		';

		$result = $mysqli->query("select c.*, p.prodcode, p.produrl, p.prodprice, p.usdprice from ccd9cart c join ccd9products p on p.prodid=c.prodid where c.compid='$userid' and c.status='0' ".($_GET['m2u']=='USD' ? " and c.prodid not in (SELECT prodid FROM ccd9prod2cat where catid='153') " : "")." order by c.cartid"); //
		$cartrows = mysqli_num_rows($result);
		$n=0; $tot=0; $ordhasgc=0; 
		while($rescart=$result->fetch_array()){ $n++;
			//$tot=$tot+round($rescart['finalprice']*$rescart['prodqty'],0);
			$showcur=dbval($rescart['prodcur']);
			$prodimg=$stackurl.getprodimg($rescart['produrl'],'a','3');
		
		 $cartpg=$cartpg.'<tr>';

		 $cartpg=$cartpg.'<td style="width:90px;" width="90"><a href="'.getprodurl($rescart['produrl']).'" target="_blank"><img width="90" style="width:90px;" src="'.$prodimg.'"></a></td>';
		 $cartpg=$cartpg.'<td valign="top">'.$rescart['prodname'].'
			<BR><small>Style Code: '.$rescart['prodcode'].' | Color: '.(trim($rescart['prodcolor'])!='' ? $rescart['prodcolor'] : 'NA' ).' | Qty: '.$rescart['prodqty'].''.(!checkgiftcat($roword['prodid']) && $rescart['measlist']!='' ? '<br>'.getmeas($rescart['measlist']) : ( (!getnoguidecat($rescart['prodid'])) ? ' | Size: '.$rescart['prodsize'].showhgthlhgt($rescart['height'],$rescart['heelheight']) : '')).(trim($rescart['comments']) ? '<small><br>Special Instructions: '.$rescart['comments'].'</small>' : '').'</small></td>';
			$cartpg=$cartpg.'<td nowrap valign="top">'.$showcur.' '.number_format(round($rescart['finalprice']* $rescart['prodqty'],0)).'</td>';
		$cartpg=$cartpg.'</tr>';
		}

		$cartpg=$cartpg.'</tbody>
</table>';


?>