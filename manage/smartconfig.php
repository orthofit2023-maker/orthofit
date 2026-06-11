<?php
//https://smartgateway.hdfcbank.com/docs/smartgateway-payment-links/docs/payment-links-via-api/session-api--payment-links
$apistatus='LIVE';  //'TEST'; //// 

if($apistatus=='LIVE'){
	$merchantid="66461";
	$apikey='5B11582A67841CF8D8F903519C5674'; //live
	$sessionURL="https://smartgateway.hdfc.bank.in/session"; //live
	$statusURL="https://smartgateway.hdfc.bank.in/orders/"; //live

}else{
	$merchantid="SG4550";
	$apikey='7046A1DEB42477286C9C4D0CABD37C'; //test
	$sessionURL="https://smartgateway.hdfcuat.bank.in/session"; //test
	$statusURL="https://smartgateway.hdfcuat.bank.in/orders/";
}
?>