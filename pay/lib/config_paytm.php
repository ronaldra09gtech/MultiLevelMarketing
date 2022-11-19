<?php

// define('PAYTM_ENVIRONMENT', 'PROD'); // PROD
// define('PAYTM_MERCHANT_KEY', 'Lm&Vv7oQcHiNMJjc'); //Change this constant's value with Merchant key received from Paytm.
// define('PAYTM_MERCHANT_MID', 'rLkvVr08997940557362'); //Change this constant's value with MID (Merchant ID) received from Paytm.
// define('PAYTM_MERCHANT_WEBSITE', 'localhost'); //Change this constant's value with Website name received from Paytm.


define('PAYTM_ENVIRONMENT', 'TEST'); // PROD
define('PAYTM_MERCHANT_KEY', '&xMexbD%DUYhqGhX');
define('PAYTM_MERCHANT_MID', 'WgbocG04046901552998'); 
define('PAYTM_MERCHANT_WEBSITE', 'WEBSTAGING');

$PAYTM_STATUS_QUERY_NEW_URL='https://securegw-stage.paytm.in/merchant-status/getTxnStatus';
$PAYTM_TXN_URL='https://securegw-stage.paytm.in/theia/processTransaction';
if (PAYTM_ENVIRONMENT == 'PROD') {
	$PAYTM_STATUS_QUERY_NEW_URL='https://securegw.paytm.in/merchant-status/getTxnStatus';
	$PAYTM_TXN_URL='https://securegw.paytm.in/theia/processTransaction';
}

define('PAYTM_REFUND_URL', '');
define('PAYTM_STATUS_QUERY_URL', $PAYTM_STATUS_QUERY_NEW_URL);
define('PAYTM_STATUS_QUERY_NEW_URL', $PAYTM_STATUS_QUERY_NEW_URL);
define('PAYTM_TXN_URL', $PAYTM_TXN_URL);
?>