<?php

session_start();
ini_set('max_execution_time', 10000);
include("db5conn.php");

//https://www.google.com/maps/place/Orthofit+Healthcare+Pvt+Ltd/@18.9763714,72.8078434,872m/data=!3m1!1e3!4m17!1m8!3m7!1s0x115aeee66e5c1141:0x4b5c8f760ea21c92!2sOrthofit+Healthcare+Pvt+Ltd!8m2!3d18.9765426!4d72.8078188!10e1!16s%2Fg%2F11b5qhkt_n!3m7!1s0x115aeee66e5c1141:0x4b5c8f760ea21c92!8m2!3d18.9765426!4d72.8078188!9m1!1b1!16s%2Fg%2F11b5qhkt_n?entry=ttu&g_ep=EgoyMDI1MDkxNi4wIKXMDSoASAFQAw%3D%3D

$API_key    = 'AIzaSyAnGBcm7WUyPLyHNkDegDspjmQ13Ha8MHE'; //my API key
//$channelID  = 'UCpuq1d8RXpVyTgX9Er5YX9g'; //my channel ID
//$maxResults = 100;

$company='Orthofit Healthcare Pvt Ltd';

//https://mybusiness.googleapis.com/v4/accounts/{accountId}/locations/{locationId}/reviews

$url = 'https://maps.googleapis.com/maps/api/place/textsearch/json?query='.$company.'&sensor=true&key='.$API_key;
//echo $url;
$result = json_decode(file_get_contents($url));

//echo($result);

$referenceId='ChIJQRFcbubuWhERkhyiDnaPXEs';
$placeid='ChIJQRFcbubuWhERkhyiDnaPXEs';


    // Example using HTTP GET request
//$url = "https://mybusiness.googleapis.com/v4/accounts/orthofitmart/locations/ChIJQRFcbubuWhERkhyiDnaPXEs/reviews";


//exit();

$url = 'https://maps.googleapis.com/maps/api/place/details/json?reference='.$referenceId.'&key='.$API_key;
//$url = "https://maps.googleapis.com/maps/api/place/details/json?key=$API_key&placeid=$placeid";
$ch = curl_init();
curl_setopt ($ch, CURLOPT_URL, $url);
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec ($ch);
$res        = json_decode($result,true);
$reviews    = $res['result']['reviews'];
//print_r($reviews);

//exit();

$url = "https://maps.googleapis.com/maps/api/place/details/json?key=$API_key&placeid=$placeid";
$ch = curl_init();
curl_setopt ($ch, CURLOPT_URL, $url);
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec ($ch);
$res        = json_decode($result,true);
$reviews    = $res['result']['reviews'];
//print_r($reviews);



foreach ($reviews as $item) {
	//print_r($item);
	echo $item['author_name'];
	echo '<br>';
	echo $item['author_url'];
	echo '<br>';
	echo $item['rating'];
	echo '<br>';
	echo $item['text'];
	echo '<br>';
	//profile_photo_url
}

exit();

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
    //Embed video
    if (isset($item->id->videoId)) {
		$sqlin="select id from ccd9videos where videoid='".inpval($item->id->videoId)."'";
		$res=query_first($sqlin);
		if($res['id']>0){
			$mysqli->query("update ccd9videos set videoid='".inpval($item->id->videoId)."', videodate='".inpval($item->snippet->publishedAt)."', title='".inpval($item->snippet->title)."', description='".inpval($item->snippet->description)."', photo='".inpval($item->snippet->thumbnails->high->url)."' where id='".$res['id']."'");
		}else{

			$mysqli->query("insert into ccd9videos (videoid, videodate, title, description, photo) values ('".inpval($item->id->videoId)."', '".inpval($item->snippet->publishedAt)."', '".inpval($item->snippet->title)."', '".inpval($item->snippet->description)."', '".inpval($item->snippet->thumbnails->high->url)."')");
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

?>