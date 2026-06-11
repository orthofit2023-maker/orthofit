<?php
//https://developers.google.com/youtube/v3/getting-started
//https://youtube.com/@orthofitclinic3748?si=U6qSjR9nwdWcgl4v
session_start();
ini_set('max_execution_time', 10000);
include("db5conn.php");

$API_key    = 'AIzaSyBBt8m8sbK-FXS_eR-E8AjmkwO4KsC74wo'; //my API key
$channelID  = 'UCpuq1d8RXpVyTgX9Er5YX9g'; //my channel ID
$maxResults = 100;

$video_list = json_decode(file_get_contents('https://www.googleapis.com/youtube/v3/search?order=date&part=snippet&channelId='.$channelID.'&maxResults='.$maxResults.'&key='.$API_key.''));

foreach ($video_list->items as $item) {
	//print_r($item);
	echo $item->id->videoId;
	echo '<br>';
	echo $item->snippet->publishedAt;
	echo '<br>';
	echo $item->snippet->title;
	echo '<br>';
	echo $item->snippet->description;
	echo '<br>';
	//echo $item->snippet->thumbnails;
	//echo '<br>';
	echo $item->snippet->thumbnails->high->url;
	echo '<br>';
	//echo $item->id->videoId.'<br>';
	echo '<br>-------------------------------------<br>';
	$isclient=1;
	if(strstr($item->snippet->title,"Footwear") || strstr($item->snippet->title,"footwear") ){
		$isclient=0;
	}
    //Embed video
    if (isset($item->id->videoId)) {
		$sqlin="select id from ccd9videos where videoid='".inpval($item->id->videoId)."'";
		$res=query_first($sqlin);
		if($res['id']>0){
			$mysqli->query("update ccd9videos set videoid='".inpval($item->id->videoId)."', videodate='".inpval($item->snippet->publishedAt)."', title='".inpval($item->snippet->title)."', description='".inpval($item->snippet->description)."', photo='".inpval($item->snippet->thumbnails->high->url)."', isclient='$isclient' where id='".$res['id']."'");
		}else{

			$mysqli->query("insert into ccd9videos (videoid, videodate, title, description, photo, isclient) values ('".inpval($item->id->videoId)."', '".inpval($item->snippet->publishedAt)."', '".inpval($item->snippet->title)."', '".inpval($item->snippet->description)."', '".inpval($item->snippet->thumbnails->high->url)."', '$isclient')");
		}
        echo '<div class="">
                <img width="280" height="150" src="'.$item->snippet->thumbnails->high->url.'"/>
                <h2>'. $item->snippet->title .'</h2>
            </div>';
		/*echo '<div class="">
                <iframe width="280" height="150" src="https://www.youtube.com/embed/'.$item->id->videoId.'" frameborder="0" allowfullscreen></iframe>
                <h2>'. $item->snippet->title .'</h2>
            </div>';*/
    } 

}


exit();

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