<?php
$API_key    = 'AIzaSyAnGBcm7WUyPLyHNkDegDspjmQ13Ha8MHE'; //my API key
$company='Orthofit Healthcare Pvt Ltd';
$url = 'https://maps.googleapis.com/maps/api/place/textsearch/json?query='.$company.'&sensor=true&key='.$API_key;
$result = json_decode(file_get_contents($url));
$referenceId='ChIJQRFcbubuWhERkhyiDnaPXEs';
$placeid='ChIJQRFcbubuWhERkhyiDnaPXEs';

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
	//echo $item['author_name'];
	//echo $item['author_url'];
	//echo $item['rating'];
	//echo $item['text'];
	echo '<div class="quotes-slide">
				<blockquote class="quotes-slider__text text-center">             
					<p class="authour">'.($item['author_name']).'</p>
					<div class="product-review">';
						for($n=1;$n<=$item['rating'];$n++){
							echo '<i class="an an-star"></i>';
						}
						echo '</div>
					<div class="rte-setting"><p>'.($item['text']).'</p></div>
				</blockquote>
			</div>';
}

?>