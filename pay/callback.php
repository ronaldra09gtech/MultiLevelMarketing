<?php
$message = '';
$base = "../";
header("Pragma: no-cache");
header("Cache-Control: no-cache");
header("Expires: 0");
require_once("./lib/config_paytm.php");
require_once("./lib/encdec_paytm.php");
$paytmChecksum = "";
$paramList = array();
$isValidChecksum = "FALSE";
$paramList = $_POST;
$paytmChecksum = isset($_POST["CHECKSUMHASH"]) ? $_POST["CHECKSUMHASH"] : "";
$isValidChecksum = verifychecksum_e($paramList, PAYTM_MERCHANT_KEY, $paytmChecksum);
include($base . "db.php");

if (!is_user_loggedin()) {
    http_response_404();
}

check_user_login();
$user_id = $loggedin_user_id;

if ($isValidChecksum === "TRUE") {
    if ($_POST["STATUS"] === "TXN_SUCCESS") {
        $transaction_id = $_POST['ORDERID'];
        $paid_amount = $_POST['TXNAMOUNT'];
        $paytm_status = $_POST['STATUS'];
        $paytm_txn_date = $_POST['TXNDATE'];

        $query = mysqli_query($conn, "SELECT * FROM $paytm_tbl WHERE transaction_id = '$transaction_id' AND user_id = '$user_id' ");
        if (!mysqli_num_rows($query)) {
            return_exit("Payment details not found");
        }

        $row = mysqli_fetch_array($query);
        $payable_amount = $row["payable_amount"];
        $paid_amount = (int) $paid_amount;
        if ($payable_amount != $paid_amount) {
            return_exit("Paid amount is insufficient");
        }

        $paytm_txn_date = strtotime($paytm_txn_date);
        $db_status = $row["status"];

        if ($db_status == "INCOMPLETE") {
            $query = mysqli_query($conn, "UPDATE $paytm_tbl SET paid_amount = '$paid_amount', paid_date = '$paytm_txn_date', status = '$paytm_status' WHERE user_id = '$user_id' AND transaction_id = '$transaction_id' ");
            if (!$query) {
                return_exit("Error in payment");
            }
            $query = mysqli_query($conn, "UPDATE $balance_tbl SET wallet = wallet + $paid_amount, last_added_money = '$paid_amount', total_added_money = total_added_money + '$paid_amount'  WHERE user_id = '$user_id' ");
            if (!$query) {
                return_exit("Error in payment");
            }

            $query = mysqli_query($conn, "UPDATE $transaction_tbl SET status = 'credit' WHERE transaction_id = '$transaction_id' AND status = 'pending' ");
            if (!$query) {
                return_exit("Error in payment");
            }

            $query = mysqli_query($conn, "INSERT INTO $deposit_tbl (`transaction_id`, `user_id`, `amount`, `gateway`, `date`,`payment_type`,`success_date`, `status`) 
                                            VALUES ('$transaction_id','$user_id','$paid_amount','PAYTM','$current_date','automatic','$current_date','approved') ");
            if (!$query) {
                return_exit("Error in depositing money");
            }
        }


        $text = '';
        $i = 0;
        if (isset($_POST) && count($_POST) > 0) {
            foreach ($_POST as $paramName => $paramValue) {
                $i++;
                if ($i == 1 || $i == 4 || $i == 6 || $i == 8 || $i == 7 || $i == 9 || $i == 11 || $i == 13) {
                    $text .= '<div><b>' . $paramName . '</b> <p class="ml-2">' . $paramValue . '</p></div>';
                    continue;
                }
            }
        }


        $message = success_paytm_payment_card($text);
    } else {
        $transaction_id = $_POST['ORDERID'];
        $paytm_status = $_POST['STATUS'];
        $query = mysqli_query($conn, "SELECT * FROM $paytm_tbl WHERE transaction_id = '$transaction_id' ");
        if (!mysqli_num_rows($query)) {
            return_exit("Payment details not found");
        }
        $query =  mysqli_query($conn, "UPDATE $paytm_tbl SET `status` = '$paytm_status' WHERE transaction_id = '$transaction_id' ");
        $text = '';
        if (isset($_POST) && count($_POST) > 0) {
            $order_id = $_POST['ORDERID'];
            $text .=  '<div><b>TRANSACTION ID</b> <p class="ml-2" >' . $_POST['ORDERID'] . '</p></div>';
            $text .=  '<div><b>RESPONSE CODE</b> <p class="ml-2" >' . $_POST['RESPCODE'] . '</p></div>';
            $text .=  '<div><b>RESPONSE MESSAGE</b> <p class="ml-2" >' . $_POST['RESPMSG'] . '</p></div>';
            $text .=  '<div><b>STATUS</b> <p class="ml-2" >' . $_POST['STATUS'] . '</p></div>';
            $text .=  '<div><b>TXNAMOUNT</b> <p class="ml-2" >' . $_POST['TXNAMOUNT'] . '</p></div>';
            $text .=  '<div><b>CURRENCY</b> <p class="ml-2" >' . $_POST['CURRENCY'] . '</p></div>';
        }

        $message = error_paytm_payment_card($text);
    }
} else {
    $message  = paytm_checksum_mismatched_card();
}






function success_paytm_payment_card($text)
{
    global $base_url;
    global $support_email;
    global $web_name;
    $messagae = '<i class="text-success material-icons-outlined">check_circle</i>
                <h4 class="my-2 message text-danger" >Payment Successfull</h4>
                <p class="mb-2" >Your transation has been successfully processed.If you have any problem feel free to contact us at <a class="link" href="mailto:' . $support_email . '">' . $support_email . '</a></p>
                <p>Regards,<br>' . $web_name . '</p>';

    $messagae .= $text;
    $messagae .= '<a href ="' . $base_url . '"><button class="btn btn-info btn-block m-0" >Home</button></a>';

    return $messagae;
}


function error_paytm_payment_card($text)
{
    global $base_url;
    global $support_email;
    global $web_name;
    $messagae = '<i class="text-warning material-icons-outlined">report_problem</i>
                <h4 class="my-2 message text-danger" >Transaction Failed</h4>
                <p class="mb-2" >Your transation has been failed.If you have any problem feel free to contact us at <a class="link" href="mailto:' . $support_email . '">' . $support_email . '</a></p>
               <p>Regards,<br>' . $web_name . '</p>';

    $messagae .= $text;
    $messagae .= '<a href ="' . $base_url . '"><button class="btn btn-info btn-block m-0" >Home</button></a>';

    return $messagae;
}


function paytm_checksum_mismatched_card()
{
    global $web_name;
    global $base_url;
    global $support_email;
    $messagae = '<i class="text-danger material-icons-outlined">dangerous</i>
                <h4 class="my-2 message text-danger" >Checksum mismatched</h4>
                <p class="mb-2" >Process transaction is suspicious. Someone altered the transaction details.</p>
                <p>If you have any problem feel free to contact us at <a class="link" href="mailto:' . $support_email . '">' . $support_email . '</a></p>
<p>Regards,<br>' . $web_name . '</p>
<a href ="' . $base_url . '"><button class="btn btn-info btn-block m-0" >Home</button></a>
                ';

    return $messagae;
}

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

                <div style="max-width:400px;" class="card">
                    <div class="card-body payment-card">
                        <?php echo $message; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>