<?php


// 

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    if (!function_exists('http_response_404')) {
        include("../../../db.php");
    }
    http_response_404();
}

if (!is_admin_loggedin()) {
    return_exit("Login Required");
}

if (!isset($_POST["case"])) {
    return_exit("Invalid request");
}

$case = $_POST["case"];

switch ($case) {
    case "upload_gateway_thumbnail":

        $image_data = upload_image("gateway_thumbnail");
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
        $image_url = get_image_src($image_inserted_id);
        $output = new stdClass;
        $output->image_url = $image_url;
        $output->image_id = $image_inserted_id;
        echo json_encode($output);
        break;

    case "create_withdraw_method":
        if (!is_isset(
            "requirement_card_heading",
            "requirements",
            "gateway_id",
            "action_type",
            "gateway_img",
            "gateway_name",
            "processing_time",
            "status"
        )) {
            return_exit("Invalid request");
        }
        $gateway_img = sanitize_text($_POST["gateway_img"]);
        $gateway_name = sanitize_text($_POST["gateway_name"]);
        $processing_time = sanitize_text($_POST["processing_time"]);
        $action_type = sanitize_text($_POST["action_type"]);
        $gateway_id = sanitize_text($_POST["gateway_id"]);
        $requirement_card_heading = sanitize_text($_POST["requirement_card_heading"]);
        $status = sanitize_text($_POST["status"]);

        validate_post_input($gateway_img, "number", "Image", true);
        validate_post_input($gateway_name, "alpha", "Gateway Name", true);
        validate_post_input($processing_time, "alpha_numeric", "Processing Time", true);
        validate_post_input($requirement_card_heading, "alpha", "Card Heading", true);

        if ($action_type == "edit" || $action_type == "create") {
        } else {
            return_exit("Invalid request");
        }

        $requirements = $_POST["requirements"];
        $requirements = json_decode($requirements, TRUE);

        if (count($requirements) > 10) {
            return_exit("Maximum 10 rows are allowed in Withdraw Requirements");
        }

        if ($status == "active" || $status == "inactive") {
        } else {
            return_exit("Invalid status");
        }

        $status =  $status == "active" ? "enabled" : "disabled";

        $output = new stdClass;
        if ($action_type == "edit") {
            $query = mysqli_query($conn, "SELECT * FROM $withdraw_method_tbl WHERE gateway_id = '$gateway_id'  ");
            if (!mysqli_num_rows($query)) {
                return_exit("Withdraw method does not exists");
            }
            $query = mysqli_query($conn, "UPDATE $withdraw_method_tbl SET 
            gateway_image = '$gateway_img',
            gateway_name ='$gateway_name',
            processing_time = '$processing_time',
            requirement_card_heading ='$requirement_card_heading',
            status = '$status'
            WHERE gateway_id = '$gateway_id'
            ");
            if (!$query) {
                return_exit("Error in updating withdraw method");
            }
            $output->message = "Withdraw method updated successfully";
        } else {
            $query = mysqli_query($conn, "INSERT INTO $withdraw_method_tbl
        (`gateway_image`, `gateway_name`, `processing_time`,`requirement_card_heading`, `date_added`,`status`)
         VALUES ('$gateway_img','$gateway_name','$processing_time','$requirement_card_heading','$current_date','$status')");
            if (!$query) {
                return_exit("Error in adding withdraw method");
            }
            $gateway_id = mysqli_insert_id($conn);
            $output->message = "Withdraw method added successfully";
        }

        if (is_isset("withdraw_image_chooser")) {
            $withdraw_image_chooser = $_POST["withdraw_image_chooser"];
            $query = mysqli_query($conn, "SELECT * FROM $withdraw_require_tbl WHERE is_image_chooser = '1' AND  gateway_id = '$gateway_id'");
            if (mysqli_num_rows($query)) {
                $query = mysqli_query($conn, "UPDATE $withdraw_require_tbl SET label_text = '$withdraw_image_chooser' WHERE gateway_id = '$gateway_id' AND is_image_chooser = '1'  ");
                if (!$query) {
                    return_exit("Error in updating withdraw method");
                }
            } else {
                $query = mysqli_query($conn, "INSERT INTO $withdraw_require_tbl (`gateway_id`,`label_text`,`is_image_chooser`) VALUES ('$gateway_id','$withdraw_image_chooser','1') ");
                if (!$query) {
                    return_exit("Error in adding withdraw method");
                }
            }
        } else {
            $query = mysqli_query($conn, "DELETE FROM $withdraw_require_tbl WHERE is_image_chooser = '1' ");
        }

        if (!is_empty($requirements)) {
            $requirement_post_ids = array();
            foreach ($requirements as $data) {
                $id = $data[0];
                if ($id == '0') {
                } else {
                    $requirement_post_ids[] = $id;
                }
            }
            $query = mysqli_query($conn, "SELECT * FROM $withdraw_require_tbl WHERE gateway_id = '$gateway_id' AND is_image_chooser = '0' ");
            while ($row = mysqli_fetch_array($query)) {
                $requirement_id = $row["requirement_id"];
                if (!in_array($requirement_id, $requirement_post_ids)) {
                    mysqli_query($conn, "DELETE FROM $withdraw_require_tbl WHERE gateway_id = '$gateway_id' AND requirement_id = '$requirement_id' ");
                }
            }
            foreach ($requirements as $data) {
                $id = $data[0];
                $val = $data[1];
                if ($id == '0') {
                    $query = mysqli_query($conn, "INSERT INTO $withdraw_require_tbl (`gateway_id`,`label_text`) VALUES ('$gateway_id','$val') ");
                    if (!$query) {
                        return_exit("Error in adding withdraw method");
                    }
                } else {
                    $requirement_post_ids[] = $id;
                    $query = mysqli_query($conn, "SELECT * FROM $withdraw_require_tbl WHERE requirement_id = '$id' AND gateway_id = '$gateway_id' ");
                    if (!mysqli_num_rows($query)) {
                        return_exit("Invalid request");
                    }
                    $query = mysqli_query($conn, "UPDATE $withdraw_require_tbl SET label_text = '$val' WHERE gateway_id = '$gateway_id' AND requirement_id= '$id' ");
                    if (!$query) {
                        return_exit("Error in updating details");
                    }
                }
            }
        }

        $output->url = $admin_base_url . '/withdraw/withdraw-methods';
        echo json_encode($output);
        break;

    case "withdraw_gateway_status":
        if (!is_isset("gateway_id")) {
            return_exit("Invalid request");
        }
        $gateway_id = sanitize_text($_POST["gateway_id"]);
        $query = mysqli_query($conn, "SELECT * FROM $withdraw_method_tbl WHERE gateway_id = '$gateway_id' ");
        if (!mysqli_num_rows($query)) {
            return_exit("Withdraw Method don't exist");
        }
        $row = mysqli_fetch_array($query);
        $output = new stdClass;
        $status = $row["status"];
        if ($status == "enabled") {
            $query = mysqli_query($conn, "UPDATE $withdraw_method_tbl set status = 'disabled' WHERE status = 'enabled' AND gateway_id = '$gateway_id' ");
            if (!$query) {
                return_exit("Error in updating Status");
            }
            $output->status = "disabled";
        } else {
            $query = mysqli_query($conn, "UPDATE $withdraw_method_tbl set status = 'enabled' WHERE status = 'disabled' AND gateway_id = '$gateway_id' ");
            if (!$query) {
                return_exit("Error in updating Status");
            }
            $output->status = "enabled";
        }
        $output->message = "Status updated successfully";
        echo json_encode($output);
        break;

    case "delete_withdraw_gateway":
        if (!is_isset("gateway_id")) {
            return_exit("Invalid request");
        }
        $gateway_id = sanitize_text($_POST["gateway_id"]);
        $query = mysqli_query($conn, "SELECT * FROM $withdraw_method_tbl WHERE gateway_id = '$gateway_id' ");
        if (!mysqli_num_rows($query)) {
            return_exit("Withdraw Method don't exist");
        }

        _delete_gateway_all_data($gateway_id);
        $output = new stdClass;
        $output->message = "Withdraw method deleted successfully";
        $output->url = $admin_base_url . '/withdraw/withdraw-methods';
        echo json_encode($output);
        break;

    case "approve_user_withdraw":
        if (!is_isset("transaction_id", "message_to_user")) {
            return_exit("Invalid Request");
        }
        $message_to_user = sanitize_text($_POST["message_to_user"]);
        $transaction_id = $_POST["transaction_id"];
        if (!is_transaction_id($transaction_id)) {
            return_exit("Invalid Request");
        }
        validate_post_input($message_to_user, "", "Message", true);
        if (strlen($message_to_user) > 200) {
            return_exit("Maximum 200 characters are allowed in Message");
        }
        $query = mysqli_query($conn, "SELECT * FROM $withdraw_tbl WHERE transaction_id = '$transaction_id' ");
        if (!mysqli_num_rows($query)) {
            return_exit("Invalid Request");
        }
        $row = mysqli_fetch_array($query);
        $status = $row["status"];
        $total_amount = $row["amount"];
        $user_id = $row["user_id"];
        if ($status !== "pending") {
            return_exit("Invalid Request");
        }

        $query = mysqli_query($conn, "UPDATE deposit_tickets SET status = '2',approve_date = '$current_date' WHERE transaction_id = '$transaction_id' ");
        if (!$query) {
            return_exit("Error in approving deposit");
        }
        $query = mysqli_query($conn, "UPDATE $withdraw_tbl SET message = '$message_to_user',status = 'approved',success_date = '$current_date' WHERE transaction_id = '$transaction_id' ");
        if (!$query) {
            return_exit("Error in approving withdraw");
        }
        $query = mysqli_query($conn, "UPDATE $transaction_tbl SET status = 'debit' WHERE transaction_id = '$transaction_id' ");
        if (!$query) {
            return_exit("Error in approving deposit");
        }

        $query = mysqli_query($conn, "UPDATE $balance_tbl SET wallet = wallet - '$total_amount'  = total_withdrawl + '$total_amount', total_withdrawl = total_withdrawl + '$total_amount',last_withdrawl = '$total_amount', pending = pending - '$total_amount' WHERE user_id = '$user_id' ");
        if (!$query) {
            return_exit("Error in approving deposit");
        }

        $output = new stdClass;
        $output->message = "Withdraw approved successfully";
        $output->url = $admin_base_url . '/withdraw/pending-withdraws';
        echo json_encode($output);
        break;

    case "reject_user_withdraw":
        if (!is_isset("transaction_id", "reject_reason")) {
            return_exit("Invalid Request");
        }
        $reject_reason = sanitize_text($_POST["reject_reason"]);
        $transaction_id = $_POST["transaction_id"];
        if (!is_transaction_id($transaction_id)) {
            return_exit("Invalid Request");
        }
        validate_post_input($reject_reason, "", "Message", true);
        if (strlen($reject_reason) > 200) {
            return_exit("Maximum 200 characters are allowed in Message");
        }
        $query = mysqli_query($conn, "SELECT * FROM $withdraw_tbl WHERE transaction_id = '$transaction_id' ");
        if (!mysqli_num_rows($query)) {
            return_exit("Invalid Request");
        }
        $row = mysqli_fetch_array($query);
        $status = $row["status"];
        $total_amount = $row["amount"];
        $user_id = $row["user_id"];
        if ($status !== "pending") {
            return_exit("Invalid Request");
        }
        $query = mysqli_query($conn, "UPDATE $withdraw_tbl SET message = '$reject_reason',status = 'rejected',success_date = '$current_date' WHERE transaction_id = '$transaction_id' ");
        if (!$query) {
            return_exit("Error in rejecting withdraw");
        }
        $query = mysqli_query($conn, "UPDATE $transaction_tbl SET status = 'rejected' WHERE transaction_id = '$transaction_id' ");
        if (!$query) {
            return_exit("Error in approving withdraw");
        }

        $query = mysqli_query($conn, "UPDATE deposit_tickets SET status = '3' WHERE transaction_id = '$transaction_id' ");
        if (!$query) {
            return_exit("Error in approving withdraw");
        }

        $output = new stdClass;
        $output->message = "Withdraw rejected successfully";
        $output->url = $admin_base_url . '/withdraw/pending-withdraws';
        echo json_encode($output);
        break;

    case "reject_user_deposit":
        if (!is_isset("transaction_id", "reject_reason")) {
            return_exit("Invalid Request");
        }
        $transaction_id = sanitize_text($_POST["transaction_id"]);
        $reject_reason = sanitize_text($_POST["reject_reason"]);
        validate_post_input($reject_reason, "", "Reject Reason", true);
        if (strlen($reject_reason) > 200) {
            return_exit("Maximum 200 characters are allowed in Message");
        }

        $query = mysqli_query($conn, "SELECT * FROM $deposit_tbl WHERE transaction_id = '$transaction_id' ");
        if (!mysqli_num_rows($query)) {
            return_exit("Invalid Request");
        }
        $row = mysqli_fetch_array($query);
        $status = $row["status"];
        $total_amount = $row["amount"];
        $user_id = $row["user_id"];
        if ($status !== "review") {
            return_exit("Invalid Request");
        }

        $query = mysqli_query($conn, "UPDATE $deposit_tbl SET message = '$reject_reason', status =  'rejected',success_date = '$current_date' WHERE transaction_id = '$transaction_id' ");
        if (!$query) {
            return_exit("Error in rejecting deposit");
        }

        $query = mysqli_query($conn, "UPDATE deposit_tickets SET status = '3' WHERE transaction_id = '$transaction_id' ");
        if (!$query) {
            return_exit("Error in approving deposit");
        }

	$query = mysqli_query($conn, "UPDATE user_transactions SET status = 'rejected' WHERE transaction_id = '$transaction_id' ");
        if (!$query) {
            return_exit("Error in approving deposit");
        }

        $query = mysqli_query($conn, "UPDATE $balance_tbl SET deposit_review = deposit_review - '$total_amount' WHERE user_id = '$user_id' ");
        if (!$query) {
            return_exit("Error in approving deposit");
        }

        $output = new stdClass;
        $output->message = "Deposit successfully rejected";
        $output->url = $admin_base_url . '/deposit/pending-deposits';
        echo json_encode($output);
        break;

    case "approve_user_deposit":
        if (!is_isset("transaction_id")) {
            return_exit("Invalid Request4");
        }
        $message_to_user = sanitize_text($_POST["message_to_user"]);
        $transaction_id = $_POST["transaction_id"];
        if (!is_transaction_id($transaction_id)) {
            return_exit("Invalid Request");
        }
        if (strlen($message_to_user) > 200) {
            return_exit("Maximum 200 characters are allowed in Message");
        }
        $query = mysqli_query($conn, "SELECT * FROM deposit_tickets WHERE transaction_id = '$transaction_id' ");
        if (!mysqli_num_rows($query)) {
            return_exit("Invalid Request");
        }
        $row = mysqli_fetch_array($query);
        $status = $row["status"];
        $total_amount = $row["amount"];
        $user_id = $row["ticket_creator"];
        if ($status !== "1") {
            return_exit("Invalid Request");
        }
        $query = mysqli_query($conn, "UPDATE deposit_tickets SET status = '2',approve_date = '$current_date' WHERE transaction_id = '$transaction_id' ");
        if (!$query) {
            return_exit("Error in approving deposit");
        }

        $query = mysqli_query($conn, "UPDATE $transaction_tbl SET status = 'credit' WHERE transaction_id = '$transaction_id' ");
        if (!$query) {
            return_exit("Error in approving deposit");
        }

        $query = mysqli_query($conn, "UPDATE $balance_tbl SET wallet = wallet + '$total_amount', last_added_money =  '$total_amount', total_added_money = total_added_money + '$total_amount', deposit_review = deposit_review - '$total_amount' WHERE user_id = '$user_id' ");
        if (!$query) {
            return_exit("Error in approving deposit");
        }

        $query = mysqli_query($conn, "UPDATE $deposit_tbl SET status = 'approved' WHERE transaction_id = '$transaction_id' ");
        if (!$query) {
                return_exit("Error in depositing money");
        }

        $output = new stdClass;
        $output->message = "Deposit approved successfully";
        $output->url = $admin_base_url . '/deposit/pending-deposits';
        echo json_encode($output);
        break;

    case "create_new_deposit_method":

        if (!is_isset("gateway_id", "gateway_name", "action_type", "gateway_img", "processing_time", "status", "detail_label", 'detail_value')) {
            return_exit("Invalid request");
        }

        $gateway_name = sanitize_text($_POST["gateway_name"]);
        $processing_time = sanitize_text($_POST["processing_time"]);
        $status = sanitize_text($_POST["status"]);
        $detail_label = $_POST["detail_label"];
        $gateway_id = sanitize_text($_POST["gateway_id"]);
        $detail_value = $_POST["detail_value"];
        $gateway_img = sanitize_text($_POST["gateway_img"]);
        $action_type = sanitize_text($_POST["action_type"]);

        validate_post_input($gateway_img, "number", "Image", true);
        validate_post_input($gateway_name, "alpha", "Gateway Name", true);
        validate_post_input($processing_time, "", "Processing Time", true);

        if ($action_type == "create" || $action_type == "edit") {
        } else {
            return_exit("Invalid Request");
        }

        if ($status == "active" || $status == "inactive") {
        } else {
            return_exit("Invalid Status");
        }

        if (!is_array($detail_label)) {
            return_exit("Details are invalid");
        }
        if (!is_array($detail_value)) {
            return_exit("Details are invalid");
        }

        if (count($detail_label) == count($detail_value)) {
        } else {
            return_exit("Invalid details");
        }

        if (count($detail_label) > 10) {
            return_exit("Maximum 10 rows are allowed in details");
        }

        $deposit_details = array();
        $deposit_details["label"] = $detail_label;
        $deposit_details["value"] = $detail_value;

        $deposit_details = serialize($deposit_details);

        $output = new stdClass;
        if ($action_type == "create") {
            $query = mysqli_query($conn, "INSERT INTO $manual_deposit_method (`gateway_image`,`gateway_name`, `processing_time`, `deposit_details`, `status`,`date_created`) 
            VALUES ('$gateway_img','$gateway_name','$processing_time','$deposit_details','$status','$current_date') ");
            if (!$query) {
                return_exit("Error in creating deposit method");
            }
            $output->message = "Deposit method created successfully";
        } else {
            $query = mysqli_query($conn, "SELECT * FROM $manual_deposit_method WHERE gateway_id = '$gateway_id' ");
            if (!mysqli_num_rows($query)) {
                return_exit("Deposit method doesn't exists");
            }
            $query = mysqli_query($conn, "UPDATE $manual_deposit_method SET 
                gateway_image = '$gateway_img',
                gateway_name = '$gateway_name',
                processing_time = '$processing_time',
                deposit_details = '$deposit_details',
                status = '$status'
                WHERE gateway_id = '$gateway_id'
            ");
            if (!$query) {
                return_exit("Error in updating deposit method");
            }
            $output->message = "Deposit method updated successfully";
        }
        $output->url = $admin_base_url . '/deposit/manual-methods';
        echo json_encode($output);

        break;

    case "delete_deposit_method":
        if (!is_isset("gateway_id")) {
            return_exit("Invalid request");
        }
        $gateway_id = sanitize_text($_POST["gateway_id"]);
        $query = mysqli_query($conn, "SELECT * FROM $manual_deposit_method WHERE gateway_id = '$gateway_id' ");
        if (!mysqli_num_rows($query)) {
            return_exit("Deposit method doesn't exists");
        }
        $query = mysqli_query($conn, "DELETE FROM $manual_deposit_method WHERE gateway_id = '$gateway_id' ");
        if (!$query) {
            return_exit("Error in deleting deposit gateway");
        }
        $output = new stdClass;
        $output->message = "Deposit Gateway Deleted Successfully";
        $output->url = $admin_base_url . '/deposit/manual-methods';
        echo json_encode($output);
        break;
}
