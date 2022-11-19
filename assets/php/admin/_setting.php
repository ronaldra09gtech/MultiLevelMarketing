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

if (!is_admin_loggedin()) {
    return_exit("Login Required");
}


$case = $_POST["case"];
if (is_empty($case)) return_exit("Invalid request");
switch ($case) {

    case "email_setting":

        if (!is_isset("mail_encryption", "mail_host", "mail_port", "mail_username", "mail_password")) {
            return_exit("Invalid request");
        }
        
        $mail_encryption = sanitize_text($_POST["mail_encryption"]);
        $mail_host = sanitize_text($_POST["mail_host"]);
        $mail_port = sanitize_text($_POST["mail_port"]);
        $mail_username = sanitize_text($_POST["mail_username"]);
        $mail_password = sanitize_text($_POST["mail_password"]);

        validate_post_input($mail_encryption, "alpha", "Encryption", true);
        validate_post_input($mail_host, "", "Host", true);
        validate_post_input($mail_port, "number", "Mail Port", true);
        validate_post_input($mail_username, "email", "Username", true);
        validate_post_input($mail_password, "", "Password", true);

        if ($mail_encryption == "ssl" || $mail_encryption == "tls") {
        } else {
            return_exit("Invalid Encryption");
        }

        $query = mysqli_query($conn,"SELECT * FROM $setting_tbl");
        if(!mysqli_num_rows($query)){
            $query = mysqli_query($conn,"INSERT INTO $setting_tbl ( `mail_encryption`,`mail_host`, `mail_port`, `mail_username`,`mail_password`) 
            VALUES ('$mail_encryption','$mail_host','$mail_port','$mail_username','$mail_password') ");
        }

        $query = mysqli_query($conn, "UPDATE $setting_tbl SET 
            `mail_encryption` = '$mail_encryption',
             `mail_host` = '$mail_host',
              `mail_port` = '$mail_port',
               `mail_username` = '$mail_username',
                `mail_password` = '$mail_password'
            ");
        if (!$query) {
            return_exit("Error in updating email setting");
        }

        $output = new stdClass;
        $output->message = "Email setting updated successfully";
        echo json_encode($output);



        break;

    case "test_email":

        if (!is_isset("test_email")) {
            return_exit("Invalid request");
        }

        $test_email = sanitize_text($_POST["test_email"]);
        validate_post_input($test_email, "email", "Email", true);

        include("send_email.php");
        $send_otp = send_test_email($test_email);
        if (!$send_otp) {
            return_exit("Email sending failed");
        }
        $output = new stdClass;
        $output->message =  "Email sending successfull";
        echo json_encode($output);
        break;

    case "add_package":

        if (!is_isset("package_id", "package", "price", "minimum_withdraw", "withdraw_charge", "pair_income", "maximum_pair_income", "validity","status","action_type")) {
            return_exit("Invalid request");
        }
        $package_id = sanitize_text($_POST["package_id"]);
        $package = sanitize_text($_POST["package"]);
        $price = sanitize_text($_POST["price"]);
        $minimum_withdraw = sanitize_text($_POST["minimum_withdraw"]);
        $withdraw_charge = sanitize_text($_POST["withdraw_charge"]);
        $pair_income = sanitize_text($_POST["pair_income"]);
        $maximum_pair_income = sanitize_text($_POST["maximum_pair_income"]);
        $status = sanitize_text($_POST["status"]);
        $action_type = sanitize_text($_POST["action_type"]);
        $validity = sanitize_text($_POST["validity"]);

        validate_post_input($package, "alpha_numeric", "Package", true);
        validate_post_input($price, "number", "Price", true);
        validate_post_input($minimum_withdraw, "number", " Minimum Withdrawal ", true);
        validate_post_input($pair_income, "number", " Pair Income ", true);
        validate_post_input($pair_income, "number", "Maximum Pair Income", true);
        validate_post_input($withdraw_charge, "decimal_numeric", " Withdraw Charge ", true);
        validate_post_input($validity, "number", " Validity ", true);

        if ($action_type == "add" || $action_type == "edit") {
        } else {
            return_exit("Invalid Request");
        }
        
        if ($status == "enable" || $status == "disable") {
        } else {
            return_exit("Invalid Request");
        }

     
        $output = new stdClass;
        if ($action_type == "add") {
            $query = mysqli_query($conn, "INSERT INTO $packages_tbl (`package`, `price`,`pair_income`,`maximum_pair_income`, `minimum_withdraw`, `withdraw_charge`,`validity`, `status`) 
                                                                VALUES ('$package','$price','$pair_income','$maximum_pair_income','$minimum_withdraw','$withdraw_charge','$validity','$status')");
            if (!$query) {
                return_exit("Error in creating package");
            }
            $output->message = "Package created successfully";
        } else {
            if (!is_package_id($package_id)) {
                return_exit("Package doesn't exists");
            }
            $query = mysqli_query($conn, "UPDATE $packages_tbl SET package = '$package', price = '$price', pair_income = '$pair_income',maximum_pair_income = '$maximum_pair_income',
            minimum_withdraw = '$minimum_withdraw', withdraw_charge = '$withdraw_charge',validity = '$validity', status = '$status' WHERE package_id = '$package_id' ");
            if (!$query) {
                return_exit("Error in updating package");
            }
            $output->message = "Package edited successfully";
        }

        $output->url = $admin_base_url . '/settings/package-setting';
        echo json_encode($output);
        break;

    case "delete_package":
        if (!is_isset("package_id")) {
            return_exit("Invalid request");
        }
        $package_id = sanitize_text($_POST["package_id"]);
        $query = mysqli_query($conn, "SELECT * FROM $packages_tbl WHERE package_id = '$package_id' ");
        if (!mysqli_num_rows($query)) {
            return_exit("Package doesn't exists");
        }

        $query = mysqli_query($conn, "DELETE FROM $packages_tbl WHERE package_id = '$package_id' ");
        if (!$query) {
            return_exit("Error in deleting package");
        }
        $output = new stdClass;
        $output->message = "Package deleted successfully";
        $output->url = $admin_base_url . '/settings/package-setting';
        echo json_encode($output);
        break;

    case "notice_setting":
        if(!is_isset("notice")){
            return_exit("Invalid Request");
        }
        $notice = sanitize_text($_POST["notice"]);

        $query = mysqli_query($conn, "SELECT * FROM $setting_tbl");
        if (!mysqli_num_rows($query)) {
            $query = mysqli_query($conn, "INSERT INTO $setting_tbl ( `notice`) 
            VALUES ('$notice') ");
        }

        $query = mysqli_query($conn,"UPDATE $setting_tbl SET notice = '$notice' ");
        if(!$query){
            return_exit("Error in updating notice");
        }
        $notice = unsanitize_text($notice);
        $output = new stdClass;
        $output->notice_text = $notice;
        $output->message = "Notice updated successfully";
        echo json_encode($output);

        break;
    case "basic_setting":

        if (!is_isset("currency", "currency_position", "primary_color")) {
            return_exit("Invalid request");
        }

        $currency = sanitize_text($_POST["currency"]);
        $currency_position = sanitize_text($_POST["currency_position"]);
        $primary_color = sanitize_text($_POST["primary_color"]);

        validate_post_input($currency, "", "Currency", true);
        validate_post_input($currency_position, "", "Currency Position", true);
        validate_post_input($primary_color, "", "Primary Colour", true);

        if ($currency_position == "prefix" || $currency_position == "suffix") {
        } else {
            return_exit("Invalid Currency Position");
        }

        $primary_color = convert_hex_to_rgb_color($primary_color);



        $query = mysqli_query($conn, "SELECT * FROM $setting_tbl");
        if (!mysqli_num_rows($query)) {
            $query = mysqli_query($conn, "INSERT INTO $setting_tbl ( `web_currency`,`web_currency_position`,`web_primary_color`) 
            VALUES ('$currency','$currency_position','$primary_color') ");
        }


        $query = mysqli_query($conn, "UPDATE $setting_tbl SET web_currency = '$currency', web_currency_position = '$currency_position', web_primary_color = '$primary_color' ");
        if (!$query) {
            return_exit("Error in updating details");
        }
        $output = new stdClass;
        $output->message = "Details updated successfully";
        echo json_encode($output);
        break;


    case "change_logo":
        if (!is_isset("event")) return_exit("Invalid request");
        $event = sanitize_text($_POST["event"]);
        if (!in_array($event, ["logo", "logo_icon", "favicon"])) return_exit("Invalid request");

        $image_data = upload_image("image");
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

        $output = new stdClass();
        $output->image_id = $image_inserted_id;
        $output->image_src = get_image_src($image_inserted_id);
        echo json_encode($output);

        break;

    case "save_logo":
        if (!is_isset("logo", "logo_icon", "favicon")) return_exit("Invalid request");
        $logo = sanitize_text($_POST["logo"]);
        $logo_icon = sanitize_text($_POST["logo_icon"]);
        $favicon = sanitize_text($_POST["favicon"]);

        validate_post_input($logo, "number", "Logo", true);
        validate_post_input($logo_icon, "number", "Logo Icon", true);
        validate_post_input($favicon, "number", "Favicon", true);

        $query = mysqli_query($conn, "SELECT * FROM $setting_tbl");
        if (!mysqli_num_rows($query)) {
            $query = mysqli_query($conn, "INSERT INTO $setting_tbl ( `logo_id`,`logo_icon_id`,`favicon_id`) 
            VALUES ('$logo','$logo_icon','$favicon') ");
        } else {
            $query = mysqli_query($conn, "UPDATE $setting_tbl SET logo_id = '$logo',logo_icon_id = '$logo_icon',favicon_id = '$favicon' ");
            if (!$query) {
                return_exit("Error in updating logos");
            }
        }
        $output = new stdClass;
        $output->message = "Logos updated successfully";
        echo json_encode($output);

        break;

    default:
        http_response_404();
        break;
}
