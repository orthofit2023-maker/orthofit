<?php
include("db5conn.php");

$url = 'http://api.apnabazarapp.in/WebAPI/V2/get_paramwise_stock_summary.php';

$mysqli->query("delete from ccd9stocks");
echo '<br>------------------------------------------------------------------------<br>';

$post = [
    'Username' => '9754428053',
    'Password' => '12345678',
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
$response = curl_exec($ch);
curl_close($ch);

// do anything you want with your response
//var_dump($response);

$arr = json_decode($response);
print_r($arr->statuscode); 
echo '<br>------------------------------------------------------------------------<br>';
//echo print_r($arr->result);
$arrstock=$arr->result;
for($x=0;$x<count($arrstock);$x++){
	//echo print_r($arrstock[$x]);
	echo $arrstock[$x]->ItemName;
	echo '<br>'.$arrstock[$x]->MCName;
	echo '<br>'.$arrstock[$x]->MainQty;
	echo '<br>'.$arrstock[$x]->Parameter1;
	echo '<br>'.$arrstock[$x]->Amount;
	echo '<br>------------------------------------------------------------------------<br>';
	$prodcode = str_replace('/','-',inpval($arrstock[$x]->ItemName));
	$prodsize = inpval($arrstock[$x]->Parameter1);
	$storename =  inpval($arrstock[$x]->MCName);
	$prodqty = inpval($arrstock[$x]->MainQty);

	$sqlin="select typeid, typevalue from ccd9types where typename='".$prodsize."'  and opt='107'";
	$row=query_first($sqlin);
	if($row['typeid']>0){
		$prodsize = trim($row['typevalue']);
	}

	$sqlin="select stockid, prodqty from ccd9stocks where prodcode='".$prodcode."' and prodsize='".$prodsize."'  and storename='".$storename."'";
	$row=query_first($sqlin);
	if($row['stockid']>0){
			$mysqli->query("update ccd9stocks set prodqty=prodqty+'$prodqty' where stockid='".$row['stockid']."'");
	}else{
			$mysqli->query("insert into ccd9stocks (prodcode, prodsize, storename, prodqty ) values ('$prodcode', '$prodsize', '$storename','$prodqty')");
	}
}
?>