<?php

header("Pragma: no-cache");
header("Cache-Control: no-cache");
header("Expires: 0");
// Include database
$base = "../";
include $base . "db.php";

check_user_login();
if (!isset($_GET["id"])) {
    http_response_404();
}


$transaction_id = $_GET["id"];
$user_id = $loggedin_user_id;
$query = mysqli_query($conn, "SELECT * FROM $paytm_tbl WHERE transaction_id = '$transaction_id' AND user_id = '$user_id' AND status = 'INCOMPLETE' ");
if (!mysqli_num_rows($query)) {
    http_response_404();
}
$row = mysqli_fetch_array($query);
$payable_amount = $row["payable_amount"];
if (is_empty($payable_amount)) {
    http_response_404();
}


require_once("./lib/config_paytm.php");
require_once("./lib/encdec_paytm.php");

$checkSum = "";
$paramList = array();

$ORDER_ID = $transaction_id;
$CUST_ID = rand(10000000, 99999999);
$INDUSTRY_TYPE_ID = 'Retail';
$CHANNEL_ID = 'WEB';
$TXN_AMOUNT = $payable_amount;

// Create an array having all required parameters for creating checksum.
$paramList["MID"] = PAYTM_MERCHANT_MID;
$paramList["ORDER_ID"] = $ORDER_ID;
$paramList["CUST_ID"] = $CUST_ID;
$paramList["INDUSTRY_TYPE_ID"] = $INDUSTRY_TYPE_ID;
$paramList["CHANNEL_ID"] = $CHANNEL_ID;
$paramList["TXN_AMOUNT"] = $TXN_AMOUNT;
$paramList["WEBSITE"] = PAYTM_MERCHANT_WEBSITE;
$paramList["CALLBACK_URL"] = $base_url . '/pay/callback';
$checkSum = getChecksumFromArray($paramList, PAYTM_MERCHANT_KEY);
?>



<!DOCTYPE html>
<html lang="en">

<head>
    
    <?php echo web_metadata(); ?>
 
    <title>Pay - <?php echo $web_name; ?></title>
    <?php include $base . "assets/css/css-files.php"; ?>
</head>

<body>

    <div id="main-wrapper" data-theme="light" data-layout="vertical" data-navbarbg="skin1" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed" data-boxed-layout="full" class="">
        <div id="app" class="ml-0 page-wrapper" style="display: block;">
            <div class="container-fluid justify-align-center vh-100">
                <div class="card">
                    <div class="card-body justify-align-center flex-column">
                        <p class="text-danger">
                            <strong>Warning!</strong> Please do not refresh this page...
                        </p>

                        <svg width="200px" height="200px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
                            <g>
                                <path fill="#2accfc" d="M48.3 56.8l-4.4 1.5c-0.9-1.3-1.5-2.7-1.7-4.3l-1.9 0.3c0.5 2.6 1.7 5 3.6 6.9c1.9 1.9 4.3 3.1 6.9 3.6 l0.3-1.9c-1.6-0.3-3-0.9-4.3-1.7L48.3 56.8z"></path>
                                <path fill="#2accfc" d="M57.8 47.2l4.4-1.5c0.9 1.3 1.5 2.7 1.7 4.3l1.9-0.3c-0.5-2.6-1.7-5-3.6-6.9c-1.9-1.9-4.3-3.1-6.9-3.6L55 41.1 c1.6 0.3 3 0.9 4.3 1.7L57.8 47.2z"></path>
                                <path fill="#2accfc" d="M62.2 58.2l-4.4-1.5l1.5 4.4C58 62 56.5 62.6 55 62.9l0.3 1.9c2.6-0.5 5-1.7 6.9-3.6c1.9-1.9 3.1-4.3 3.6-6.9 L64 53.9C63.7 55.5 63.1 56.9 62.2 58.2z"></path>
                                <path fill="#2accfc" d="M43.9 42.8c-1.9 1.9-3.1 4.3-3.6 6.9l1.9 0.3c0.3-1.6 0.9-3 1.7-4.3l4.4 1.5l-1.5-4.4c1.3-0.9 2.7-1.5 4.3-1.7 l-0.3-1.9C48.2 39.7 45.8 40.9 43.9 42.8z"></path>
                                <animateTransform attributeName="transform" type="rotate" repeatCount="indefinite" dur="4s" keyTimes="0;1" values="0 53.064 52;360 53.064 52"></animateTransform>
                            </g>
                            <g class="ldio-d9dqg8a5m87-st2">
                                <path fill="#2accfc" d="M36 61.9c-1.7-3-2.7-6.4-2.7-9.9c0-10.9 8.8-19.7 19.7-19.7v1c-10.3 0-18.8 8.4-18.8 18.8 c0 3.3 0.9 6.5 2.5 9.4L36 61.9z"></path>
                                <animateTransform attributeName="transform" type="rotate" repeatCount="indefinite" dur="2s" keyTimes="0;1" values="360 53.064 52;0 53.064 52"></animateTransform>
                            </g>
                            <g class="ldio-d9dqg8a5m87-st2">
                                <path fill="#2accfc" d="M57 75.3l-0.5-3c9.9-1.7 17.2-10.2 17.2-20.3c0-11.4-9.2-20.6-20.6-20.6S32.5 40.6 32.5 52 c0 1.6 0.2 3.2 0.5 4.7l-3 0.7c-0.4-1.8-0.6-3.6-0.6-5.4c0-13.1 10.6-23.7 23.7-23.7S76.7 38.9 76.7 52 C76.7 63.6 68.4 73.4 57 75.3z"></path>
                                <animateTransform attributeName="transform" type="rotate" repeatCount="indefinite" dur="1.332s" keyTimes="0;1" values="0 53.064 52;360 53.064 52"></animateTransform>
                            </g>
                            <g>
                                <path fill="#2accfc" d="M90.5 45.4c-1.5-8.8-6.2-16.8-13-22.5l0 0c-3.4-2.9-7.3-5.1-11.4-6.6s-8.5-2.3-13-2.3v2.4v1.4v2.4 c3.7 0 7.4 0.6 10.9 1.9l0.8-2.3c0 0 0 0 0 0c3.7 1.4 7.2 3.4 10.3 5.9l1.2-1.5L75 25.8c0 0 0 0 0 0l-1.5 1.8 c5.7 4.8 9.6 11.5 10.9 18.8l3.8-0.7c0 0 0 0 0 0L90.5 45.4z"></path>
                                <path fill="#2accfc" d="M29.7 22l4.7 6.1c3.5-2.8 7.5-4.6 11.9-5.6l-1.7-7.5C39.2 16.2 34.2 18.5 29.7 22z"></path>
                                <animateTransform attributeName="transform" type="rotate" repeatCount="indefinite" dur="2s" keyTimes="0;1" values="360 53.064 52;0 53.064 52"></animateTransform>
                            </g>
                            <g class="ldio-d9dqg8a5m87-st2">
                                <path fill="#2accfc" d="M53.1 92.4v-1c21.8 0 39.5-17.7 39.5-39.5c0-21.8-17.7-39.5-39.5-39.5c-15.8 0-30 9.4-36.2 23.8L15.9 36 c6.4-14.8 21-24.4 37.1-24.4c22.3 0 40.4 18.1 40.4 40.4C93.5 74.3 75.3 92.4 53.1 92.4z"></path>
                                <animateTransform attributeName="transform" type="rotate" repeatCount="indefinite" dur="1.332s" keyTimes="0;1" values="0 53.064 52;360 53.064 52"></animateTransform>
                            </g>
                            <g>
                                <path fill="#2accfc" d="M39.7 28.5l0.6 1c3.9-2.2 8.3-3.4 12.8-3.4V25C48.4 25 43.7 26.2 39.7 28.5z"></path>
                                <path fill="#2accfc" d="M28.6 60.6l-1.1 0.4C31.3 71.8 41.6 79 53.1 79v-1.2C42.1 77.9 32.3 70.9 28.6 60.6z"></path>
                                <animateTransform attributeName="transform" type="rotate" repeatCount="indefinite" dur="4s" keyTimes="0;1" values="360 53.064 52;0 53.064 52"></animateTransform>
                            </g>
                        </svg>

                    </div>
                </div>



                <ul class="d-none none ">
                    <form method="POST" action="<?php echo PAYTM_TXN_URL ?>" name="f1">
                        <?php
                        foreach ($paramList as $name => $value) {
                            echo '<input style="display:none;" readonly type="hidden" name="' . $name . '" value="' . $value . '">';
                        }
                        ?>
                        <input type="hidden" name="CHECKSUMHASH" value="<?php echo $checkSum ?>">
                        <script type="text/javascript">
                            document.f1.submit();
                        </script>
                    </form>
                </ul>
            </div>

        </div>
    </div>
</body>

</html>