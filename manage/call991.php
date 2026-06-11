<?php
session_start();
ini_set('max_execution_time', 10000);
include("db5conn.php");

$mysqli->query("delete from ccd9prodphotos");

$sqllist="SELECT t1c.typeid as fitid, t3c.typeid as colid,t1c.typename as typefit, t3.*, t3c.typename, t3c.typevalue,  c.*, IF(CURDATE() between c.offerfrdate and c.offertodate, '1', '0') as isoffer, IF(CURDATE() between c.discfrdate and c.disctodate, '1', '0') as isdiscount from ccd9products c join ccd9prod2type1 t1 on c.prodid=t1.prodid join ccd9types t1c on t1.typeid=t1c.typeid join ccd9prod2type3 t3 on c.prodid=t3.prodid join ccd9types t3c on t3.typeid=t3c.typeid and t3c.opt=7  left join ccd9prod2cat t on t.prodid=c.prodid  where 1=1 and c.prodstatus='1'  group by c.prodid, t3.typeid, t1.typeid order by c.prodid desc;";
$result = $mysqli->query($sqllist);
$num_rows = mysqli_num_rows($result);
if ($num_rows>0){ $i=0; 
		while($row=$result->fetch_array()){  $i++;
			$prodid = $row['prodid'];  $prodcode = trim($row['prodcode']);
			echo $i.'='.$row['prodname'].' ('.$row['typefit'].' - '.$row['typename'].')<br> ';
			$photo='';
			$sqlin="select images, galleryimages from `wc_products` where (parent like '%$prodid' ".($prodcode!='' ? " or parent like '%$prodcode%' or sku like '%".$row['prodcode']."%'" : "")." )  and (attribute1values='".trim($row['typefit'])."' or attribute2values='".trim($row['typefit'])."' or attribute3values='".trim($row['typefit'])."')  and (attribute1values='".trim($row['typename'])."' or attribute2values='".trim($row['typename'])."' or attribute3values='".trim($row['typename'])."')   and (images!='' or galleryimages!='') order by position";
			$resultin = $mysqli->query($sqlin);
			if($res = $resultin->fetch_array()){
				if(trim($res['images'])!=''){
					$photo=trim($res['images']); 
				}
				if(trim($res['galleryimages'])!=''){
					$photo=$photo.','.trim($res['galleryimages']);
				}
			}
			if($photo==''){
				$sqlin="select images, galleryimages from `wc_products` where (parent like '%$prodid' ".($prodcode!='' ? " or parent like '%$prodcode%' or sku like '%".$row['prodcode']."%'" : "")." )  and  (attribute1values='".trim($row['typename'])."' or attribute2values='".trim($row['typename'])."' or attribute3values='".trim($row['typename'])."')   and (images!='' or galleryimages!='') order by position";
				$resultin = $mysqli->query($sqlin);
				if($res = $resultin->fetch_array()){
					echo 'found checking product color';
					if(trim($res['images'])!=''){
						$photo=trim($res['images']); 
					}
					if(trim($res['galleryimages'])!=''){
						$photo=$photo.','.trim($res['galleryimages']);
					}
				}
			}
			if($photo==''){
				echo 'not found checking main product';
				
				$sqlin="select images, galleryimages from `wc_products` where id='$prodid' and (images!='' or galleryimages!='') order by position";
				$resultin = $mysqli->query($sqlin);
				if($res = $resultin->fetch_array()){
					if(trim($res['images'])!=''){
						$photo=trim($res['images']); 
					}
					if(trim($res['galleryimages'])!=''){
						$photo=$photo.','.trim($res['galleryimages']);
					}
				}
			}

			echo $photo.'<br>--------------------------------------------------<br>';
			$mysqli->query("insert into ccd9prodphotos (prodid, type1, type3, photo) values ('".$row['prodid']."', '".$row['fitid']."', '".$row['colid']."', '$photo')");
		}
}
?>