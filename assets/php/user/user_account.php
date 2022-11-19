<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    if (!function_exists('http_response_404')) {
        include("../../../db.php");
    }
    http_response_404();
}

if (!isset($_POST["case"])) {
    return_exit("Invalid request");
}

$case = $_POST["case"];
if (is_empty($case)) return_exit("Invalid request");
switch ($case) {

    case "get_user_name_by_id":
        if (is_isset("user_id", "error_text")) {
            $user_id = sanitize_text($_POST["user_id"]);
            $error_text = sanitize_text($_POST["error_text"]);
            if (is_user_id($user_id)) {
                $user_name = user_name($user_id);
                $output = array(
                    'user_name' => $user_name
                );
                echo json_encode($output);
                exit();
            }
            return_exit("$error_text is invalid");
        }
        break;

    case "get_placement_type":
        if (is_isset("placement_id")) {
            $placement_id = sanitize_text($_POST['placement_id']);
            if (!is_user_id($placement_id)) {
                echo "Invalid placement id";
            } else {
                $placement_name = user_name($placement_id);
                $data = user_tree_data($placement_id);
                if (!is_empty($data)) {
                    $left_id = $data['left_id'];
                    $right_id = $data['right_id'];
                    $left_id = is_user_id($left_id) ? "false" : "true";
                    $right_id = is_user_id($right_id) ? "false" : "true";
                    $output = new stdClass();
                    $output->user_name = $placement_name;
                    $output->left_id = $left_id;
                    $output->right_id = $right_id;
                    echo json_encode($output);
                    exit();
                }
                echo "Invalid plac6ement id";
            }
        }
        break;

    case "send_registration_otp":
        if (!is_isset(
            "user_email"
        )) {
            return_exit("Invalid request");
        }
        $user_email = $_POST["user_email"];
        validate_post_input($user_email, "email", "Email", true);
        $otp = get_new_registration_otp($user_email);
        if (!$otp) {
            return_exit("Otp sending failed");
        }
        include("send_email.php");
        $send_otp = send_registration_otp($user_email, $otp);
        if (!$send_otp) {
            return_exit("Otp sending failed");
        }
        $output = array(
            "message" => "Otp sending successfull"
        );
        echo json_encode($output);
        break;


    case "register":

        if (!is_isset("placement_type")) {
            return_exit("Placement type is invalid");
        }

        if (!is_isset(
            "referral_id",
            "placement_id",
            "user_name",
            "full_name",
            "mobile_number",
            "user_email",
            "password",
            "confirm_password"
        )) {
            return_exit("Invalid request");
        }

        $referral_id = sanitize_text($_POST['referral_id']);
        $placement_id = sanitize_text($_POST['placement_id']);
        $placement_type = sanitize_text($_POST['placement_type']);
        $user_name = sanitize_text($_POST['user_name']);
        $full_name = sanitize_text($_POST['full_name']);
        $mobile_number = sanitize_text($_POST['mobile_number']);
        $user_email = sanitize_text($_POST['user_email']);
        $password = sanitize_text($_POST['password']);
        $confirm_password = sanitize_text($_POST['confirm_password']);



        validate_post_input($referral_id, "number", "Referral Id", true);
        validate_post_input($placement_id, "number", "Placement Id", true);
        validate_post_input($placement_type, "", "Placement Type", true);
        validate_post_input($user_name, "alpha", "Username", true);
        validate_post_input($full_name, "alpha", "Full Name", true);
        validate_post_input($mobile_number, "mobile_number", "Mobile Number", true);
        validate_post_input($user_email, "email", "Email", true);
        validate_post_input($password, "", "Password", true);
        validate_post_input($confirm_password, "", "Confirm Password", true);

        if ($placement_type == "left" || $placement_type == "right") {
        } else {
            return_exit("Invalid placement type");
        }

        if ($password !== $confirm_password) {
            return_exit("Passwords are not matching");
        }

        if (!is_user_id($referral_id)) {
            return_exit("Referral Id is invalid");
        }

        if (!is_user_id($placement_id)) {
            return_exit("Placement Id is invalid");
        }

        if (is_username_exist($user_name)) {
            return_exit("Username already exists");
        }

        validate_placement_type($placement_id, $placement_type);

        if (!check_combination_referral_placement($referral_id, $placement_id)) {
            return_exit("Invalid combination of referral and placement id");
        }

        $user_id = get_new_user_id();
        $password = password_hash($password, PASSWORD_BCRYPT);
        $query = mysqli_query($conn, "INSERT INTO $users_tbl (`user_id`, `user_name`,`user_email`, `user_password`, `full_name`, `user_mobile_number`,`user_registration_date`,`status`)
                                                    VALUES ('$user_id','$user_name','$user_email','$password','$full_name','$mobile_number','$current_date','active') ");
        if (!$query) {
            return_exit("Error in registration:" . mysqli_error($conn));
        }

        $basic_package_id = basic_package_id();
        $package_price = package_data($basic_package_id)["price"];
        $transaction_id = new_transaction_id();

        $query = mysqli_query($conn, "INSERT INTO $package_history_tbl (`user_id`, `package_id`, `transaction_id`, `amount`, `date`, `activated_date`,`expired_date`,`status`) VALUES 
                       ('$user_id','$basic_package_id','$transaction_id','$package_price','$current_date','$current_date','0','active')");
        if (!$query) return_exit("Error in registration:" . mysqli_error($conn));

        $query = mysqli_query($conn, "INSERT INTO $transaction_tbl (`transaction_id`, `user_id`, `amount`, `transaction_charge`, `net_amount`, `description`, `category`, `date`, `status`)
                    VALUES ('$transaction_id','$user_id','$package_price','0','$package_price','$user_package package activated','purchased package','$current_date','debit') ");
        if (!$query) return_exit("Error in registration:" . mysqli_error($conn));

        $left_count = 0;
        $right_count = 0;
        $left_id = 0;
        $right_id = 0;
        $pair_count = 0;
        $tree_query = mysqli_query($conn, "INSERT INTO $users_tree_tbl ( `user_id`, `referral_id`, `placement_id`, `placement_type`, `left_count`, `right_count`, `left_id`, `right_id`, `pair_count`)
                 VALUES ('$user_id','$referral_id','$placement_id','$placement_type','$left_count','$right_count','$left_id','$right_id', '$pair_count')");
        if (!$query) {
            return_exit("Error in registration");
        }
      
        if (!add_user_balance_row($user_id)) {
            return_exit("Error in registration");
        }

        update_user_placement_data($placement_id, $placement_type, $user_id);
        check_binary_count($user_id,$placement_id, $placement_type);

        $output = array(
            'message' => "Registration Successfull",
            'page_url' => $base_url . '/new-registration'
        );

        setcookie('registered_user_id', $user_id, time() + (1800), '/');
        echo json_encode($output);

        break;

    case "login":
        if (!is_isset("user_id", "password", "keeplogged")) {
            return_exit("Invalid request");
        }

        $user_id = sanitize_text($_POST["user_id"]);
        $password = sanitize_text($_POST["password"]);
        $keeploggedin = sanitize_text($_POST["keeplogged"]);

        validate_post_input($user_id, "alpha_numeric", "User ID or Username", true);
        validate_post_input($password, "", "Password", true);

        if (!is_user_id($user_id) && !is_user_name($user_id)) {
            return_exit("Invalid Username & User ID");
        }

        if (is_user_id($user_id)) {
            $post_text = "User ID";
        }

        if (is_user_name($user_id)) {
            $post_text = "Username";
        }

        $query = mysqli_query($conn, "SELECT * FROM $users_tbl WHERE user_name = '$user_id' || user_id = '$user_id' ");
        if (!mysqli_num_rows($query)) {
            return_exit("Invalid Username & User ID");
        }
        $row = mysqli_fetch_array($query);
        $user_id = $row["user_id"];
        $db_password = $row['user_password'];
        $pass_decode = password_verify($password, $db_password);
        if (!$pass_decode) {
            return_exit("$post_text and Password are invalid");
        }

        $status = $row["status"];
        if ($status != "active") {
            return_exit("Your account has been blocked");
        }

        $session_id = new_session_id();
        $valid_till_date = '';
        if ($keeploggedin == 1) {
            $valid_till_date = (strtotime("+30 days", ($current_date)));
            $time = time() + (86400 * 30);
        } else if ($keeploggedin == 0) {
            $valid_till_date = (strtotime("+30 minutes", ($current_date)));
            $time =  time() + (1800);
        }
        setcookie($web_name . '_usession_id', $session_id, $time, '/');
        $query = mysqli_query($conn, "INSERT INTO $login_session_tbl (`user_id`, `session_id`, `valid_till`) 
                VALUES ('$user_id','$session_id','$valid_till_date') ");
        if ($query) {
            $output = new stdClass();
            $output->message = "Login Successfull";
            $output->url = $base_url;
            echo json_encode($output);
            exit();
        }
        return_exit("Error in Login");

        break;

    case "forgot_password":

        if (!is_isset("user_id")) {
            return_exit("Invalid request");
        }

        $user_id = sanitize_text($_POST["user_id"]);

        validate_post_input($user_id, "alpha_numeric", "User ID or Username", true);

        if (!is_user_id($user_id) && !is_user_name($user_id)) {
            return_exit("Invalid Username & User ID");
        }

        if (is_user_id($user_id)) {
            $post_text = "User ID";
        }

        if (is_user_name($user_id)) {
            $post_text = "Username";
        }

        $query = mysqli_query($conn, "SELECT * FROM $users_tbl WHERE user_name = '$user_id' || user_id = '$user_id' ");
        if (!mysqli_num_rows($query)) {
            return_exit("Invalid Username & User ID");
        }

        $row = mysqli_fetch_array($query);
        $user_id = $row["user_id"];
        $user_email = $row["user_email"];
        if (is_empty($user_email)) {
            return_exit("Your Acoount Email is invalid");
        }

        $otp = get_new_forgot_pwd_otp($user_email);
        if (!$otp) {
            return_exit("Otp sending failed");
        }
        include("send_email.php");
        $send_otp = send_forgot_password_otp($user_id, $user_email, $otp);
        if (!$send_otp) {
            return_exit("Otp sending failed");
        }
        $reset_html = get_reset_password_html($user_id);

        $output = new stdClass;
        $output->message =  "Otp sending successfull";
        $output->reset_html = $reset_html;
        echo json_encode($output);

        break;

    case "reset_password":

        if (!isset($_POST['password'], $_POST['confirm_password'], $_POST['user_id'], $_POST["otp"])) {
            return_exit("Invalid request");
        }
        $password = sanitize_text($_POST["password"]);
        $confirm_password = sanitize_text($_POST["confirm_password"]);
        $user_id = sanitize_text($_POST["user_id"]);
        $otp = sanitize_text($_POST["otp"]);
        validate_post_input($user_id, "alpha_numeric", "User ID or Username", true);
        validate_post_input($password, "", "Password", true);
        validate_post_input($confirm_password, "", "Confirm Password", true);
        validate_post_input($otp, "otp", "OTP", true);

        if (!is_user_id($user_id)) {
            return_exit("Invalid Request");
        }

        if ($password !== $confirm_password) {
            return_exit("Passwords are not matching");
        }
        $user_email = user_email($user_id);
        if (!is_valid_reset_password_otp($otp, $user_email)) {
            return_exit("Invalid otp");
        }
        $password = password_hash($password, PASSWORD_BCRYPT);
        $query = mysqli_query($conn, "UPDATE $users_tbl SET user_password = '$password' WHERE user_id = '$user_id' ");
        if (!$query) {
            return_exit("Error In Updating Password");
        }
        update_otp_status($otp, $user_email, 2);
        logout_all_user($user_id);
        include("send_email.php");
        $receiver_email = user_email($user_id);
        send_reset_password_successfull_email($user_id, $receiver_email);
        $output = new stdClass;
        $output->message = "Password reset successfull";
        $output->url = $base_url . '/login';
        echo json_encode($output);
        break;

    case "resend_reset_otp";
        if (!is_isset("user_id")) {
            return_exit("Invalid request");
        }
        $user_id = sanitize_text($_POST["user_id"]);
        if (!is_user_id($user_id)) {
            return_exit("Invalid request");
        }
        $user_email = user_email($user_id);
        $otp = get_new_forgot_pwd_otp($user_email);
        if (!$otp) {
            return_exit("Otp sending failed");
        }
        include("send_email.php");
        $send_otp = send_forgot_password_otp($user_id, $user_email, $otp);
        if (!$send_otp) {
            return_exit("Otp sending failed");
        }
        $output = new stdClass;
        $output->message =  "Otp sending successfull";
        echo json_encode($output);

        break;

    default:
        http_response_404();
        break;
}
