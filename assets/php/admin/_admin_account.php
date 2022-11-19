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

    case "create_admin":

        if (!is_isset("user_name", "email", "password", "confirm_password")) {
            return_exit("Invalid request");
        }

        $user_name = sanitize_text($_POST["user_name"]);
        $email = sanitize_text($_POST["email"]);
        $password = sanitize_text($_POST["password"]);
        $confirm_password = sanitize_text($_POST["confirm_password"]);
        $admin_id = 1006090;

        validate_post_input($email, "email", "Email", true);
        validate_post_input($user_name, "alpha_numeric", "Username", true);
        validate_post_input($password, "", "Password", true);
        validate_post_input($confirm_password, "", "Confirm Password", true);
        if ($password !== $confirm_password) {
            return_exit("Passwords are not matching");
        }
        $password = password_hash($password, PASSWORD_BCRYPT);
        
        $query = mysqli_query($conn, "INSERT INTO $users_tbl (`user_id`, `user_name`,`user_email`, `user_password`, `user_registration_date`, `status`,`user_role`)
                                                    VALUES ('$admin_id','$user_name','$email','$password','$current_date','active','2') ");
        if (!$query) {
            return_exit("Error in creating Admin account");
        }


        $basic_package_id = basic_package_id();
        $package_price = package_data($basic_package_id)["price"];
        $transaction_id = new_transaction_id();
        $package_name = package_data($basic_package_id)["package"];

        $query = mysqli_query($conn, "INSERT INTO $package_history_tbl (`user_id`, `package_id`, `transaction_id`, `amount`, `date`, `activated_date`,`expired_date`,`status`) VALUES 
                       ('$admin_id','$basic_package_id','$transaction_id','$package_price','$current_date','$current_date','0','active')");
        if (!$query) return_exit("Error in registration:" . mysqli_error($conn));

        $query = mysqli_query($conn, "INSERT INTO $transaction_tbl (`transaction_id`, `user_id`, `amount`, `transaction_charge`, `net_amount`, `description`, `category`, `date`, `status`)
                    VALUES ('$transaction_id','$admin_id','$package_price','0','$package_price','$package_name package activated','purchased package','$current_date','debit') ");
        if (!$query) return_exit("Error in registration:" . mysqli_error($conn));


        $left_count = 0;
        $right_count = 0;
        $left_id = 0;
        $right_id = 0;
        $pair_count = 0;
        $tree_query = mysqli_query($conn, "INSERT INTO $users_tree_tbl ( `user_id`, `referral_id`, `placement_id`, `placement_type`, `left_count`, `right_count`, `left_id`, `right_id`, `pair_count`)
                 VALUES ('$admin_id','0','0','left','$left_count','$right_count','$left_id','$right_id', '$pair_count')");
        if (!$query) {
            return_exit("Error in registration");
        }

        if (!add_user_balance_row($admin_id)) {
            return_exit("Error in creating Admin account");
        }

        $output = new stdClass;
        $output->message = "Account created successfully";
        $output->url = $admin_base_url . '/login';
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

        $query = mysqli_query($conn,"SELECT * FROM $users_tbl WHERE ( user_name = '$user_id' || user_id = '$user_id' ) AND user_role = '2'");
        if(!mysqli_num_rows($query)){
            return_exit("Invalid Username & User ID");
        }

        $row = mysqli_fetch_array($query);
        $db_password = $row['user_password'];
        $pass_decode = password_verify($password, $db_password);
        if (!$pass_decode) {
            return_exit("$post_text and Password are invalid");
        }
        $user_id = $row["user_id"];

        $session_id = new_session_id();
        $valid_till_date = '';
        if ($keeploggedin == 1) {
            $valid_till_date = (strtotime("+30 days", ($current_date)));
            $time = time() + (86400 * 30);
        } else if ($keeploggedin == 0) {
            $valid_till_date = (strtotime("+30 minutes", ($current_date)));
            $time =  time() + (1800);
        }
        setcookie($web_name . '_asession_id', $session_id, $time, '/');
        $query = mysqli_query($conn, "INSERT INTO $login_session_tbl (`user_id`, `session_id`, `valid_till`) 
                VALUES ('$user_id','$session_id','$valid_till_date') ");
        if ($query) {
            $output = new stdClass();
            $output->message = "Login Successfull";
            $output->url = $admin_base_url;
            echo json_encode($output);
            exit();
        }
        return_exit("Error in Login");

        break;


    case "change_password":

        if(!is_admin_loggedin()){
            return_exit("Login Required");
        }

        if (!is_isset("current_password", "new_password", "confirm_password")) {
            return_exit("Invalid request");
        }

        $current_password = sanitize_text($_POST["current_password"]);
        $new_password = sanitize_text($_POST["new_password"]);
        $confirm_password = sanitize_text($_POST["confirm_password"]);

        validate_post_input($current_password, "", "Current Password", true);
        validate_post_input($new_password, "", "New Password", true);
        validate_post_input($confirm_password, "", "Confirm Password", true);

        if ($new_password !== $confirm_password) {
            return_exit("New Passwords are not matching");
        }

        if ($new_password == $current_password) {
            return_exit("New password can't be used as current password");
        }

        $user_id = $loggedin_admin_id;

        $data = user_data($user_id);
        $db_password = $data['user_password'];
        $pass_decode = password_verify($current_password, $db_password);
        if (!$pass_decode) {
            return_exit("Current password is incorrect");
        }

        $new_password = password_hash($new_password, PASSWORD_BCRYPT);
        $query = mysqli_query($conn, "UPDATE $users_tbl SET user_password = '$new_password' WHERE user_id = '$user_id' ");
        if (!$query) {
            return_exit("Error in updating password");
        }

        include("send_email.php");
        send_change_password_successfull_email($loggedin_user_id);
        logout_with_excep($user_id);

        $output = new stdClass;
        $output->message = "Password updated successfully";
        echo json_encode($output);

        break;
}