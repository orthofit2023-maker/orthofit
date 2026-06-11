<?php
ini_set("log_errors", 1);
error_reporting(E_ALL);
//VISA 4012 0000 0000 1097  Any future date Any CVV 000000

$apikey='7046A1DEB42477286C9C4D0CABD37C';
$headerValue =  base64_encode($apikey);

	
//NzA0NkExREVCNDI0NzcyODZDOUM0RDBDQUJEMzdD


$header = array();
$header[] = 'Content-type: application/json';
$header[] = 'Authorization: Basic '.$headerValue;		
$header[] = 'x-merchantid: SG4550';
$header[] = 'x-customerid: SG4550';		
/*

Content-Type application/json
Authorization Basic NzA0NkExREVCNDI0NzcyODZDOUM0RDBDQUJEMzdD
x-merchantid SG4550
x-customerid  7896

{
    "order_id": " 1233",
    "amount": "5.0",
    "customer_id": "111",
    "customer_email": "samir.sudrik@gmail.com",
    "customer_phone": "9820594920",
    "payment_page_client_id": "hdfcmaster",
    "action": "paymentPage",
    "currency": "INR",
    "return_url": "https://www.orthofit.in/manage/checkpay.php",
    "description": "Complete your payment",
    "first_name": "Samir",
    "last_name": "Sudrik"
}
*/
$orderid="1245";
$amount="4.0";
$customer_id="789";
$customer_email='samir.sudrik@gmail.com';
$customer_phone="9820594920";
$fname="Samir"; $lname="Sudrik";

$postValues =  array();
$postValues['order_id'] = $orderid;
$postValues['amount'] = $amount;
$postValues['customer_id'] = $customer_id;
$postValues['customer_email'] = $customer_email;
$postValues['customer_phone'] = $customer_phone;
$postValues['payment_page_client_id'] = "hdfcmaster";
$postValues['action'] = "paymentPage";
$postValues['currency'] = "INR";
$postValues['return_url'] = "https://www.orthofit.in/manage/checkpay.php";
$postValues['description'] = "Complete your payment";
$postValues['first_name'] = $fname;
$postValues['last_name'] = $lname;

$postJson = json_encode($postValues);


$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, "https://smartgateway.hdfcuat.bank.in/session");
curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_SSLVERSION, 6);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($curl, CURLOPT_ENCODING, '');		
curl_setopt($curl, CURLOPT_TIMEOUT, 60);
curl_setopt($curl, CURLOPT_POST, 1);
curl_setopt($curl, CURLOPT_POSTFIELDS, $postJson);
$response = curl_exec($curl);
$curlerr = curl_error($curl);

if ($curlerr != ''){
	// -------------------- return to order page with error msg --------------------
	echo '<h4 style="color:red">Error:'.$curlerr.'</h4>';
	//return false;
}else {
	$res = json_decode($response);
	print_r($res);
	echo '<br><br>--------------------------------';
	echo $res->status; echo $res->id;
	echo $res->payment_links->web;
	echo $res->clientAuthToken;
	//echo $res;
}


exit();

/*

{
    "status": "NEW",
    "id": "ordeh_84dbc68264ed4f0aa1e069acb33840dc",
    "order_id": "1233",
    "payment_links": {
        "web": "https://smartgateway.hdfcuat.bank.in/payment-page/order/ordeh_84dbc68264ed4f0aa1e069acb33840dc",
        "expiry": "2026-02-22T10:34:34Z"
    },
    "sdk_payload": {
        "requestId": "9ee6800f744c47ff8f242312b7fb5d4a",
        "service": "in.juspay.hyperpay",
        "payload": {
            "firstName": "Samir",
            "clientId": "hdfcmaster",
            "customerId": "111",
            "displayBusinessAs": "Orthofit Healthcare Pvt Ltd",
            "orderId": "1233",
            "returnUrl": "https://www.orthofit.in/manage/checkpay.php",
            "currency": "INR",
            "customerEmail": "samir.sudrik@gmail.com",
            "customerPhone": "9820594920",
            "service": "in.juspay.hyperpay",
            "description": "Complete your payment",
            "environment": "sandbox",
            "lastName": "Sudrik",
            "merchantId": "SG4550",
            "amount": "5.0",
            "clientAuthTokenExpiry": "2026-02-22T10:34:34Z",
            "clientAuthToken": "tkn_7d27aae5c22b4e9c8a3a7dfb74d7daea",
            "action": "paymentPage",
            "collectAvsInfo": false
        },
        "expiry": "2026-02-22T10:34:34Z",
        "currTime": "2026-02-22T10:19:34Z"
    },
    "order_expiry": "2026-02-22T10:34:34Z"
}
*/




/*
https://smartgateway.hdfcuat.bank.in/orders/$orderid

Content-Type application/x-www-form-urlencoded
Authorization Basic NzA0NkExREVCNDI0NzcyODZDOUM0RDBDQUJEMzdD
x-merchantid SG4550
x-customerid  7896
version 2023-06-30



{
    "customer_email": "samir.sudrik@gmail.com",
    "customer_phone": "9820594920",
    "customer_id": "111",
    "customer_phone_country_code": null,
    "status_id": 21,
    "status": "CHARGED",
    "id": "ordeh_84dbc68264ed4f0aa1e069acb33840dc",
    "merchant_id": "SG4550",
    "amount": 5,
    "currency": "INR",
    "order_id": "1233",
    "date_created": "2026-02-22T10:19:34Z",
    "last_updated": "2026-02-22T10:20:33Z",
    "return_url": "https://www.orthofit.in/manage/checkpay.php",
    "product_id": "",
    "payment_links": {
        "mobile": "https://smartgateway.hdfcuat.bank.in/payment-page/order/ordeh_84dbc68264ed4f0aa1e069acb33840dc",
        "web": "https://smartgateway.hdfcuat.bank.in/payment-page/order/ordeh_84dbc68264ed4f0aa1e069acb33840dc",
        "iframe": "https://smartgateway.hdfcuat.bank.in/payment-page/order/ordeh_84dbc68264ed4f0aa1e069acb33840dc"
    },
    "udf1": "",
    "udf2": "",
    "udf3": "",
    "udf4": "",
    "udf5": "",
    "udf6": "",
    "udf7": "",
    "udf8": "",
    "udf9": "",
    "udf10": "",
    "txn_id": "SG4550-1233-1",
    "payment_method_type": "CARD",
    "auth_type": "THREE_DS",
    "card": {
        "expiry_year": "2029",
        "card_reference": "",
        "saved_to_locker": false,
        "expiry_month": "01",
        "name_on_card": "samir",
        "card_issuer": "HDFC Bank",
        "last_four_digits": "1097",
        "using_saved_card": false,
        "card_fingerprint": "851xwwrms2aftruerpg79u8ev",
        "card_isin": "401200",
        "card_type": "CREDIT",
        "card_brand": "VISA",
        "using_token": false,
        "tokens": [],
        "token_type": "CARD",
        "card_issuer_country": "INDIA",
        "juspay_bank_code": "JP_HDFC",
        "extended_card_type": "CREDIT",
        "payment_account_reference": ""
    },
    "payment_method": "VISA",
    "refunded": false,
    "amount_refunded": 0,
    "effective_amount": 5,
    "resp_code": null,
    "resp_message": null,
    "bank_error_code": "",
    "bank_error_message": "",
    "txn_uuid": "mozhppvo9YuwDem43k9",
    "txn_detail": {
        "txn_id": "SG4550-1233-1",
        "order_id": "1233",
        "status": "CHARGED",
        "error_code": null,
        "net_amount": 5,
        "surcharge_amount": null,
        "tax_amount": null,
        "txn_amount": 5,
        "offer_deduction_amount": null,
        "gateway_id": 100,
        "currency": "INR",
        "metadata": {
            "microapp": "hyperpay",
            "payment_channel": "WEB"
        },
        "express_checkout": false,
        "redirect": true,
        "txn_uuid": "mozhppvo9YuwDem43k9",
        "gateway": "DUMMY",
        "error_message": "",
        "created": "2026-02-22T10:20:12Z",
        "last_updated": "2026-02-22T10:20:33Z",
        "txn_flow_type": "CARD_TRANSACTION",
        "is_cvv_less_txn": false,
        "txn_amount_breakup": [
            {
                "name": "BASE",
                "amount": 5,
                "sno": 1,
                "method": "ADD"
            }
        ]
    },
    "payment_gateway_response": {
        "resp_code": null,
        "rrn": "768072",
        "created": "2026-02-22T10:20:32Z",
        "epg_txn_id": "DUMMY-SG4550-1233-1",
        "resp_message": null,
        "auth_id_code": "768335",
        "txn_id": null,
        "network_error_message": null,
        "network_error_code": null,
        "arn": null,
        "gateway_merchant_id": null,
        "eci": null,
        "auth_ref_num": null,
        "umrn": null,
        "current_blocked_amount": null,
        "payer_ifsc": null,
        "xid": null,
        "cvv_check": null,
        "avs_response": null
    },
    "gateway_id": 100,
    "emi_details": {
        "bank": null,
        "monthly_payment": null,
        "interest": null,
        "subvention_amount": null,
        "conversion_details": null,
        "principal_amount": null,
        "additional_processing_fee_info": null,
        "tenure": null,
        "subvention_info": [],
        "emi_type": null,
        "processed_by": null
    },
    "metadata": {
        "order_expiry": "2026-02-22T10:34:34Z",
        "payment_page_client_id": "hdfcmaster",
        "payment_links": {
            "mobile": "https://smartgateway.hdfcuat.bank.in/payment-page/order/ordeh_84dbc68264ed4f0aa1e069acb33840dc",
            "web": "https://smartgateway.hdfcuat.bank.in/payment-page/order/ordeh_84dbc68264ed4f0aa1e069acb33840dc",
            "iframe": "https://smartgateway.hdfcuat.bank.in/payment-page/order/ordeh_84dbc68264ed4f0aa1e069acb33840dc"
        },
        "merchant_payload": "{\"displayBusinessAs\":\"Orthofit Healthcare Pvt Ltd\",\"customerEmail\":\"samir.sudrik@gmail.com\",\"customerPhone\":\"9820594920\"}",
        "payment_page_sdk_payload": "{\"firstName\":\"Samir\",\"displayBusinessAs\":\"Orthofit Healthcare Pvt Ltd\",\"currency\":\"INR\",\"customerEmail\":\"samir.sudrik@gmail.com\",\"customerPhone\":\"9820594920\",\"service\":\"in.juspay.hyperpay\",\"description\":\"Complete your payment\",\"lastName\":\"Sudrik\",\"amount\":\"5.0\",\"action\":\"paymentPage\",\"collectAvsInfo\":false}"
    },
    "gateway_reference_id": null,
    "offers": [],
    "maximum_eligible_refund_amount": 5,
    "order_expiry": "2026-02-22T10:34:34Z",
    "resp_category": null,
    "next_action": [
        "REFUND"
    ]
}

*/

 echo 'done';

?>