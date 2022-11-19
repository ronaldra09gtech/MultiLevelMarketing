<?php


// 

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    if (!function_exists('http_response_404')) {
        include("../../../db.php");
    }
    http_response_404();
}

if (!is_user_loggedin()) {
    return_exit("Login Required");
}

if (!isset($_POST["case"])) {
    http_response_404();
}

$user_id = $loggedin_user_id;
$case = $_POST["case"];
if (is_empty($case)) http_response_404();
switch ($case) {

    case "deposit_manual_money";

        if (!is_isset("paid_amount", "gateway_id")) {
            return_exit("Invalid request");
        }

        $paid_amount = sanitize_text($_POST["paid_amount"]);
        $gateway_id = sanitize_text($_POST["gateway_id"]);
        validate_post_input($paid_amount, "number", "Paid Amount", true);
        validate_post_input($gateway_id, "number", "Gateway Id", true);

        $query = mysqli_query($conn, "SELECT * FROM $manual_deposit_method WHERE gateway_id = '$gateway_id'  ");
        if (!mysqli_num_rows($query)) {
            return_exit("Invalid request");
        }
        $row = mysqli_fetch_array($query);
        $gateway_name = $row["gateway_name"];
        $image_data = upload_image("payment_image");
        $data_type = $image_data["type"];
        if ($data_type == "error") {
            $error = $image_data["error"];
            return_exit($error);
        } else if ($data_type == "success") {
            $image_inserted_id = $image_data["image_id"];
            if (is_empty($image_inserted_id)) {
                return_exit("Error in uploading image");
            }
        }
        $transaction_id = new_transaction_id();
        $query = mysqli_query($conn, "INSERT INTO $transaction_tbl 
            ( `transaction_id`, `user_id`, `amount`, `transaction_charge`, `net_amount`,`description`,`payment_image`, `category`, `date`, `status`) 
        VALUES ('$transaction_id','$user_id','$paid_amount','0','$paid_amount','credit through bank transfer','$image_inserted_id','deposit','$current_date','review') ");
        if (!$query) {
            return_exit("Error in depositing money");
        }
        $query = mysqli_query($conn, "INSERT INTO $deposit_tbl (`transaction_id`, `user_id`, `amount`,`gateway`,`payment_image`, `date`, `status`) 
        VALUES ('$transaction_id','$user_id','$paid_amount','$gateway_name','$image_inserted_id','$current_date','review') ");
        if (!$query) {
            return_exit("Error in depositing money");
        }

        $query = mysqli_query($conn, "UPDATE $balance_tbl SET deposit_review = deposit_review + '$paid_amount' WHERE user_id = '$user_id' ");
        if (!$query) {
            return_exit("Error in depositing money");
        }

        $output = new stdClass;
        $output->message = "Deposit in review";
        $output->url = $base_url . '/wallet/deposit-history';
        echo json_encode($output);
        break;


    case "fetch_withdraw_gateway_charge_details";

        $output = new stdClass;

        $withdraw_charge = '';
        $final_amount = '';

        if (is_isset("amount")) {
            $amount = sanitize_text($_POST["amount"]);
            if ($amount > 0) {
                validate_post_input($amount, "number", "Amount", true);
                $user_withdraw_charge = user_withdraw_charge($user_id);
                $withdraw_charge = ($amount * $user_withdraw_charge) / 100;
                $final_amount = $amount - $withdraw_charge;
            }
        }

        $output->withdraw_charge = $withdraw_charge;
        $output->final_amount = $final_amount;
        echo json_encode($output);

        break;


    case "withdraw_payment":

        if (!is_isset("amount", "gateway_id")) {
            return_exit("Invalid request");
        }

        $amount = sanitize_text($_POST["amount"]);
        $gateway_id = sanitize_text($_POST["gateway_id"]);
        validate_post_input($amount, "number", "Amount", true);



        $user_withdraw_charge = user_withdraw_charge($user_id);
        $withdraw_charge = ($amount * $user_withdraw_charge) / 100;
        $final_amount = $amount - $withdraw_charge;
        $user_minimum_withdraw = user_minimum_withdraw($user_id);


        $user_wallet = user_wallet($user_id);

        if ($amount > $user_wallet) {
            return_exit("You have insufficient amount to withdraw");
        }

        if ($amount < $user_minimum_withdraw) {
            return_exit("Minimum withdraw amount is " . $user_minimum_withdraw);
        }

        $user_withdraw_details_log = user_withdraw_log_details($user_id, $gateway_id);

        $query = mysqli_query($conn, "SELECT * FROM $withdraw_method_tbl WHERE gateway_id = '$gateway_id' ");
        $row = mysqli_fetch_array($query);
        $gateway_name = $row["gateway_name"];


        $transaction_id = new_transaction_id();
        $query = mysqli_query($conn, "INSERT INTO $transaction_tbl 
            ( `transaction_id`, `user_id`, `amount`, `transaction_charge`, `net_amount`,`description`, `category`, `date`, `status`) 
        VALUES ('$transaction_id','$user_id','$amount','$withdraw_charge','$final_amount','withdraw through $gateway_name','withdraw','$current_date','pending') ");
        if (!$query) {
            return_exit("Error in withdraw");
        }

        $query = mysqli_query($conn, "INSERT INTO $withdraw_tbl 
                (`transaction_id`,`user_id`, `gateway_id`,`amount`, `total_charge`, `net_amount`,`user_account_details`, `payment_method`,`requested_date`, `status`) 
                    VALUES ('$transaction_id','$user_id','$gateway_id','$amount','$withdraw_charge','$final_amount','$user_withdraw_details_log','$gateway_name','$current_date','pending') ");
        if (!$query) {
            return_exit("Error in withdraw");
        }

        $query = mysqli_query($conn, "UPDATE $balance_tbl SET wallet = wallet - '$amount',  pending = pending + '$amount' WHERE user_id = '$user_id' ");
        if (!$query) {
            return_exit("Error in withdraw");
        }

        $output = new stdClass;
        $output->message = "Withdraw in pending";
        $output->url = $base_url . '/wallet/withdraw-history';
        echo json_encode($output);

        break;


    case "buy_package":
        if (!is_isset("package_id")) {
            return_exit("Invalid Request");
        }

        $package_id = sanitize_text($_POST["package_id"]);
        $query = mysqli_query($conn, "SELECT * FROM $packages_tbl WHERE package_id = '$package_id' AND status = 'enable' ");
        if (!mysqli_num_rows($query)) {
            return_exit("Package doesn't exist");
        }
        $row = mysqli_fetch_array($query);
        $package_price = $row["price"];
        $package_name = $row["package"];
        $user_wallet = user_wallet($user_id);

        if ($package_price > $user_wallet) {
            return_exit("You have insufficient money to buy the package");
        }

        $user_package_id = user_package_id($user_id);
        if ($package_id == $user_package_id && $user_package_id == basic_package_id()) return_exit("Package already activated");


        // if (is_admin($user_id)) {
        //     return_exit("Action not allowed for admin");
        // }

        $query = mysqli_query($conn, "UPDATE $balance_tbl SET wallet = wallet - '$package_price' WHERE user_id = '$user_id' ");
        if (!$query) {
            return_exit("Error in puchasing package");
        }

        $user_packgae = user_package($user_id);
        $transaction_id = new_transaction_id();

        $data = package_data($package_id);
        $validity = $data["validity"] * 86400;

        if ($package_id == $user_package_id) {
            $query = mysqli_query($conn, "SELECT expired_date FROM $package_history_tbl WHERE user_id = '$user_id' AND ( status = 'active' OR status = 'pending' ) AND package_id = '$package_id' ORDER BY serial_id DESC ");
            $activated_date = mysqli_fetch_array($query)["expired_date"];
            $expired_date = $activated_date + $validity;

            $query = mysqli_query($conn, "INSERT INTO $package_history_tbl (`user_id`, `package_id`, `transaction_id`, `amount`, `date`,`activated_date`,`expired_date`,`status`) VALUES 
                       ('$user_id','$package_id','$transaction_id','$package_price','$current_date','$activated_date','$expired_date','pending')");

            if (!$query) return_exit("Error in puchasing package" . mysqli_error($conn));
        } else {
            $query = mysqli_query($conn, "UPDATE $package_history_tbl SET status = 'expired', expired_date = '$current_date' WHERE user_id = '$user_id' AND ( status = 'active' OR status = 'pending' )  ");
            if (!$query) return_exit("Error in puchasing package" . mysqli_error($conn));

            $expired_date = $validity == 0 ? 0 : $current_date + $validity;
            $query = mysqli_query($conn, "INSERT INTO $package_history_tbl (`user_id`, `package_id`, `transaction_id`, `amount`, `date`, `activated_date`,`expired_date`,`status`) VALUES 
                       ('$user_id','$package_id','$transaction_id','$package_price','$current_date','$current_date','$expired_date','active')");
            if (!$query) return_exit("Error in puchasing package" . mysqli_error($conn));
        }


        $query = mysqli_query($conn, "INSERT INTO $transaction_tbl (`transaction_id`, `user_id`, `amount`, `transaction_charge`, `net_amount`, `description`, `category`, `date`, `status`)
                    VALUES ('$transaction_id','$user_id','$package_price','0','$package_price','$package_name package activated','purchased package','$current_date','debit') ");
        if (!$query) {
            return_exit("Error in puchasing package");
        }

        if ($package_id != basic_package_id())  check_pending_pair_income($user_id);

        $output = new stdClass;
        $output->message = "Package purchased successfully";
        echo json_encode($output);
        break;

    default:
        http_response_404();
        break;
}
