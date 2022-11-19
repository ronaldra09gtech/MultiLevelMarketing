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
    return_exit("Invalid request");
}

$case = $_POST["case"];
$user_id = $loggedin_user_id;

switch ($case) {

    case "upload_images":
        $output = '';
        $image_id = [];


        foreach ($_FILES["file"]["name"] as $key => $file) {
            $file = $_FILES["file"];
            $filename = $file['name'][$key];
            $filepath = $file['tmp_name'][$key];
            $file_extension =  strtolower(pathinfo($file['name'][$key], PATHINFO_EXTENSION));
            $fileinfo = getimagesize($file['tmp_name'][$key]);
            $fileerror = $file['error'][$key];
            $allowed_image_extension = array("png", "jpg", "jpeg");
            $ext_err_text = "Upload a valid image. Only PNG, JPG  and JPEG are allowed.";
            if ($fileerror != 0) {
                $output = "Error while uploading image";
                return_exit($output);
            }
            if (!in_array($file_extension, $allowed_image_extension)) {
                $output = $ext_err_text;
                return_exit($output);
            }

            if (
                $fileinfo['mime'] == 'image/jpeg' ||
                $fileinfo['mime'] == 'image/png'
            ) {
            } else {
                $output =  $ext_err_text;
                return_exit($output);
            }

            if (($file['size'][$key] > $web_max_img_size)) {
                $output = "Image size exceeds $web_max_img_size_text MB ";
                return_exit($output);
            }

            $filename = get_new_image_name($file_extension);
            $destfile = '../images/users/' . $filename;
            compressImage($filepath, $destfile, 60);
            $query = mysqli_query($conn, "INSERT INTO $images_tbl (`image_src`) VALUES ('$filename')  ");
            if (!$query) {
                $output = "Error while uploading image3";
                return_exit($output);
            }

            $image_inserted_id = mysqli_insert_id($conn);
            $image_id[] = $image_inserted_id;
        }

        if (is_empty($output)) {
            $output = new stdClass;
            $output->images = $image_id;
            $output->message = "Images uploaded successfully";
            echo json_encode($output);
            exit();
        }

        return_exit($output);
        break;
    
    case "add_ticket":

        if (!is_isset("subject", "message", "images")) {
            return_exit("Invalid request");
        }

        $subject = sanitize_text($_POST["subject"]);
        $message = sanitize_text($_POST["message"]);
        $images = $_POST["images"];

        if (!is_empty($images)) {
            $images = json_decode($images);
            if (!is_array($images)) {
                return_exit("Invalid request");
            }
            $images = implode(",", $images);
        }
        validate_post_input($subject, "", "Subject", true);
        validate_post_input($message, "", "Message", true);

        if (strlen($message) > 2000) {
            return_exit("Maximum 2000 characters are allowed in message");
        }

        $query = mysqli_query($conn, "INSERT INTO $tickets_tbl (`ticket_creator`,`ticket_subject`,`created_at`,`status`)
                                                    VALUES ('$user_id','$subject','$current_date','1')  ");
        if (!$query) {
            return_exit("Error in adding ticket");
        }
        $ticket_id = mysqli_insert_id($conn);

        $query = mysqli_query($conn, "INSERT INTO $tickets_msg_tbl (`ticket_id`, `replier`, `message`, `date`, `files`) 
                    VALUES ('$ticket_id','$user_id','$message','$current_date','$images') ");
        if (!$query) {
            return_exit("Error in adding ticket");
        }

        $output = new stdClass;
        $output->message = "Ticket added successfully";
        $output->url = $base_url . '/support/';
        echo json_encode($output);

        break;

    case "add_deposit_ticket":

        if (!is_isset("amount","payment_method", "message", "images")) {
            return_exit("Invalid request");
        }

        $amount = sanitize_text($_POST["amount"]);
	$payment_method = sanitize_text($_POST["payment_method"]);
        $message = sanitize_text($_POST["message"]);
        $images = $_POST["images"];

            $images = json_decode($images);
            if(!is_array($images)) return_exit("Upload Payment Image or Slip");
             if(count($images) !== 1) return_exit("Upload 1 Payment Image or Slip");
            $images = implode(",", $images);

        validate_post_input($amount, "number", "amount", true);
        validate_post_input($message, "", "Message", true);

        if (strlen($message) > 2000) {
            return_exit("Maximum 2000 characters are allowed in message");
        }
        $transaction_id = new_transaction_id();
        $query = mysqli_query($conn, "INSERT INTO deposit_tickets (`transaction_id`,`ticket_creator`,`amount`,`created_at`,`status`,`ticket_purpose`)
                                                    VALUES ('$transaction_id','$user_id','$amount','$current_date','1','1')  ");
        if (!$query) {
            return_exit("Error in adding ticket");
        }

        $ticket_id = mysqli_insert_id($conn);

        $query = mysqli_query($conn, "INSERT INTO deposit_ticket_messages (`ticket_id`, `replier`, `message`, `date`, `files`) 
                    VALUES ('$transaction_id','$user_id','$message','$current_date','$images') ");
        if (!$query) {
            return_exit("Error in adding ticket");
        }

        
        $query = mysqli_query($conn, "INSERT INTO $transaction_tbl 
            ( `transaction_id`, `user_id`, `amount`, `net_amount`, `transaction_charge`,`description`,`payment_image`, `category`, `date`, `status`) 
        VALUES ('$transaction_id','$user_id','$amount','$amount','0','credit through bank transfer','$images','deposit','$current_date','review') ");

        if (!$query) {
            return_exit("Error in sending req");
        }
        $query = mysqli_query($conn, "INSERT INTO $deposit_tbl (`transaction_id`, `user_id`,`gateway`, `amount`,`payment_image`, `date`, `status`) 
        VALUES ('$transaction_id','$user_id','$paymenth_method','$amount','$images','$current_date','review') ");
        if (!$query) {
            return_exit("Error in depositing money");
        }

        $query = mysqli_query($conn, "UPDATE $balance_tbl SET deposit_review = deposit_review + '$amount' WHERE user_id = '$user_id' ");
        if (!$query) {
            return_exit("Error in depositing money");
        }


        $output = new stdClass;
        $output->message = "Ticket added successfully";
        $output->url = $base_url . '/wallet/deposit.php';
        echo json_encode($output);

        break;
    
    case "add_withdraw":

        if (!is_isset("bank_name","bank_acc_num","amount", "message", "images")) {
            return_exit("Invalid request");
        }

        $amount = sanitize_text($_POST["amount"]);
        $message = sanitize_text($_POST["message"]);
        $bank_name = sanitize_text($_POST["bank_name"]);
        $bank_acc_num = sanitize_text($_POST["bank_acc_num"]);
        $images = $_POST["images"];

        if (!is_empty($images)) {
            $images = json_decode($images);
            if (!is_array($images)) {
                return_exit("Invalid request");
            }
            $images = implode(",", $images);
        }
        validate_post_input($amount, "", "amount", true);
        validate_post_input($message, "", "Message", true);

        if ($amount < 2000) {
            return_exit("Minumum of 2000 is allowed to Withdraw");
        }

        if (strlen($message) > 2000) {
            return_exit("Maximum 2000 characters are allowed in message");
        }
        $transaction_id = new_transaction_id();
        $query = mysqli_query($conn, "INSERT INTO deposit_tickets (`transaction_id`,`ticket_creator`,`bank_name`,`bank_acc_num`,`amount`,`created_at`,`status`,`ticket_purpose`)
                                                    VALUES ('$transaction_id','$user_id','$bank_name','$bank_acc_num','$amount','$current_date','1','2')  ");
        if (!$query) {
            return_exit("Error in adding ticket");
        }

        $ticket_id = mysqli_insert_id($conn);

        $query = mysqli_query($conn, "INSERT INTO deposit_ticket_messages (`ticket_id`, `replier`, `message`, `date`, `files`) 
                    VALUES ('$transaction_id','$user_id','$message','$current_date','$images') ");
        if (!$query) {
            return_exit("Error in adding ticket");
        }

        $charge = $amount * 0.05;
        $net_income = $amount - $charge;
        $query = mysqli_query($conn, "INSERT INTO $transaction_tbl 
            ( `transaction_id`, `user_id`, `amount`, `net_amount`, `transaction_charge`,`description`,`payment_image`, `category`, `date`, `status`) 
        VALUES ('$transaction_id','$user_id','$amount','$net_income','$charge','credit through bank transfer','$images','withdraw','$current_date','review') ");

        if (!$query) {
            return_exit("Error in sending dito");
        }
        
        $query = mysqli_query($conn, "INSERT INTO user_withdraws (`transaction_id`, `user_id`, `amount`,`total_charge`,`net_amount`,`bank_name`,`bank_account_details`,`payment_img`, `requested_date`, `status`) 
        VALUES ('$transaction_id','$user_id','$amount','$charge','$net_income','$bank_name','$bank_acc_num','$images','$current_date','pending') ");
        if (!$query) {
            return_exit("Error in sending dito2");
        }

        $query = mysqli_query($conn, "UPDATE $balance_tbl SET pending = pending + '$amount' WHERE user_id = '$user_id' ");
        if (!$query) {
            return_exit("Error in depositing money");
        }


        $output = new stdClass;
        $output->message = "Ticket added successfully";
        $output->url = $base_url . '/wallet/withdraw.php';
        echo json_encode($output);

        break;

    case "reply_ticket";

        if (!is_isset("message", "images", "ticket_id")) {
            return_exit("Invalid request");
        }

        $message = sanitize_text($_POST["message"]);
        $ticket_id = sanitize_text($_POST["ticket_id"]);
        $images = $_POST["images"];

        if (!is_empty($images)) {
            $images = json_decode($images);
            if (!is_array($images)) {
                return_exit("Invalid request");
            }
            $images = implode(",", $images);
        }

        validate_post_input($message, "", "Message", true);


        if (strlen($message) > 2000) {
            return_exit("Maximum 2000 characters are allowed in message");
        }

        if (!is_ticket_id($ticket_id)) {
            return_exit("Invalid Ticket");
        }

        $query = mysqli_query($conn, "SELECT * FROM $tickets_tbl WHERE ticket_id = '$ticket_id' ");
        if (!mysqli_num_rows($query)) {
            return_exit("Ticket doesn't exist");
        }

        $row = mysqli_fetch_array($query);
        $status = $row["status"];
        if ($status == '3') {
            return_exit("Ticket is closed");
        }
        
        $query = mysqli_query($conn, "INSERT INTO $tickets_msg_tbl (`ticket_id`, `replier`, `message`, `date`, `files`) 
                    VALUES ('$ticket_id','$user_id','$message','$current_date','$images') ");
        if (!$query) {
            return_exit("Error in adding reply");
        }

        $output = new stdClass;
        $output->message = "Reply added successfully";
        $output->url = $base_url . '/support/view-ticket?ticket=' . $ticket_id;
        echo json_encode($output);
        break;
}
