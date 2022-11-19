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
$user_id = $loggedin_user_id;

$case = $_POST["case"];
if (is_empty($case)) return_exit("Invalid request");
switch ($case) {

    case "get_profile_modal":
        $user_avatar_id = user_data($user_id)["user_image_id"];
        $modal = ' <div class="pop_img_upload" id="pop_img_upload">
            <div class="pop-imgupload">
                <div class="pop-imgupload-header">
                    <h5 class="m-0">Change avatar</h5>
                    <button id="close_pop_imgupload" class="close-pop-imgupload" type="button">×</button>
                </div>
                <div class="pop-imgupload-body">
                    <label class="btn bg-primary">  <i class="material-icons-outlined mr-1">upload</i> Upload<input type="file" class="sr-only" id="img_upload_input" name="image" accept="image/*"></label>
                    <button id="edit_profile_img" class="btn bg-success"> <i class="material-icons-outlined mr-1">change_circle</i> <span>Edit</span></button>
                    <button id="delete_profile_img" class="btn bg-danger"><i class="material-icons-outlined mr-1">delete</i><span>Delete</span></button>
                </div>
            </div>
        </div>';
        if (is_empty($user_avatar_id)) {
            $modal = ' <div class="pop_img_upload" id="pop_img_upload">
            <div class="pop-imgupload">
                <div class="pop-imgupload-header">
                    <h5 class="m-0">Change avatar</h5>
                    <button id="close_pop_imgupload" class="close-pop-imgupload" type="button">×</button>
                </div>
                <div class="pop-imgupload-body">
                    <label class="btn bg-primary">  <i class="material-icons-outlined mr-1">upload</i> Upload<input type="file" class="sr-only" id="img_upload_input" name="image" accept="image/*"></label>
                </div>
            </div>
        </div>';
        }
        $output = array(
            'modal_data' => $modal
        );
        echo json_encode($output);
        break;

    case "change_profile_image":

        $image_data = upload_image("avatar");
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

        $query = mysqli_query($conn, "UPDATE $users_tbl SET user_image_id = '$image_inserted_id' WHERE user_id = '$user_id' ");
        if (!$query) {
            return_exit("Error in updating profile");
        }

        $output = new stdClass();
        $output->message = "Profile updated successfully";
        $output->image_src = get_image_src($image_inserted_id);
        echo json_encode($output);
        break;

    case "preview_profile_img":
        $img_src =  user_image($user_id);
        $data = '<img id="imageprev" src="' . $img_src . '" >';
        $output = new stdClass();
        $output->data = $data;
        echo json_encode($output);
        break;

    case "delete_profile_img":
        $query = mysqli_query($conn, "UPDATE $users_tbl SET user_image_id = NULL WHERE user_id = '$user_id' ");
        if (!$query) {
            return_exit("Error in deleting profile image");
        };
        $image_src = user_image($user_id);
        $output = new stdClass;
        $output->message = "Profile image deleted successfully";
        $output->image_src = $image_src;
        echo json_encode($output);
        break;


    case "update_profile_detail";

        if (!is_isset("full_name", "user_dob", "user_gender")) {
            return_exit("Invalid request");
        }

        $full_name = sanitize_text($_POST["full_name"]);
        $user_dob = sanitize_text($_POST["user_dob"]);
        $user_gender = sanitize_text($_POST["user_gender"]);

        validate_post_input($full_name, "alpha", "Last Name", true);
        validate_post_input($user_dob, "", "User Dob", true);
        validate_post_input($user_gender, "alpha", "User Gender", true);

        if ($user_gender == "male" || $user_gender == "famale") {
        } else {
            return_exit("Invalid user gender");
        }

        $query = mysqli_query($conn, "UPDATE $users_tbl SET 
                `full_name` = '$full_name', 
                `user_dob` = '$user_dob', `user_gender` = '$user_gender'
                WHERE user_id = '$user_id'
        ");
        if (!$query) {
            return_exit("Error in updating Profile Details");
        }

        $output = new stdClass;
        $output->message = "Profile details updated successfully";
        echo json_encode($output);
        break;

    case "update_address_detail";

        if (!is_isset("address_1", "address_2", "user_country", "user_state", "user_city", "user_pincode", "user_mobile_number")) {
            return_exit("Invalid request");
        }

        $address_1 = sanitize_text($_POST["address_1"]);
        $address_2 = sanitize_text($_POST["address_2"]);
        $user_country = sanitize_text($_POST["user_country"]);
        $user_state = sanitize_text($_POST["user_state"]);
        $user_city = sanitize_text($_POST["user_city"]);
        $user_pincode = sanitize_text($_POST["user_pincode"]);
        $user_mobile_number = sanitize_text($_POST["user_mobile_number"]);

        validate_post_input($address_1, "", "Address Line 1", true);
        validate_post_input($address_2, "", "Address Line 1", true);
        validate_post_input($user_country, "alpha", "User Country", true);
        validate_post_input($user_state, "alpha", "User State", true);
        validate_post_input($user_city, "alpha_numeric", "User City", true);
        validate_post_input($user_pincode, "pincode", "PIN Code", true);
        validate_post_input($user_mobile_number, "mobile_number", "Mobile Number", true);

        $query = mysqli_query($conn, "UPDATE $users_tbl SET 
                `user_address_1` = '$address_1', `user_address_2`= '$address_2', 
                `user_country` = '$user_country', `user_state` = '$user_state',
                `user_city` = '$user_city', `user_pin_code` = '$user_pincode',`user_mobile_number` = '$user_mobile_number'
                WHERE user_id = '$user_id'
        ");
        if (!$query) {
            return_exit("Error in updating Address Details");
        }

        $output = new stdClass;
        $output->message = "Address details updated successfully";
        echo json_encode($output);
        break;

    case "kyc_img_upload_aadhar":
        $row = user_data($user_id);
        $kyc = $row["kyc"];
        if ($kyc == '3') {
            return_exit("KYC already approved");
        }

        $image_data = upload_image("card_file");
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

        $query = mysqli_query($conn, "UPDATE $users_tbl SET aadhar_image_id = '$image_inserted_id' WHERE user_id = '$user_id' ");
        if (!$query) {
            return_exit("Error in updating Aadhar Image");
        }

        $output = new stdClass();
        $output->message = "Aadhar Image updated successfully";
        $output->image_src = get_image_src($image_inserted_id);
        echo json_encode($output);
        break;

    case "kyc_img_upload_pan":
        $row = user_data($user_id);
        $kyc = $row["kyc"];
        if ($kyc == '3') {
            return_exit("KYC already approved");
        }

        $image_data = upload_image("card_file");
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

        $query = mysqli_query($conn, "UPDATE $users_tbl SET pan_image_id = '$image_inserted_id' WHERE user_id = '$user_id' ");
        if (!$query) {
            return_exit("Error in updating Aadhar Image");
        }

        $output = new stdClass();
        $output->message = "PAN Image updated successfully";
        $output->image_src = get_image_src($image_inserted_id);
        echo json_encode($output);
        break;

    case "submit_kyc":
        $kyc = user_data($user_id)["kyc"];
        if ($kyc == '3') {
            return_exit("KYC already approved");
        }

        if ($kyc == '2') {
            return_exit("KYC already in progess");
        }

        $aadhar_image = user_aadhar_image($user_id);
        $pan_image = user_pan_image($user_id);

        if (is_empty($aadhar_image)) {
            return_exit("Upload Aadhar Card Image first");
        }

        if (is_empty($pan_image)) {
            return_exit("Upload Pan Card Image first");
        }


        $query = mysqli_query($conn, "UPDATE $users_tbl SET kyc = '2', kyc_submitted = '$current_date' WHERE user_id = '$user_id' ");
        if (!$query) {
            return_exit("Error is submitting KYC");
        }

        $output = new stdClass;
        $output->message = "KYC submitted Successfully";
        echo json_encode($output);
        break;

    case "change_password":

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


    case "add_to_payment_gateway":
        if (!is_isset("gateway_id")) {
            return_exit("Invalid Request");
        }
        $gateway_id = sanitize_text($_POST["gateway_id"]);

        $query = mysqli_query($conn, "SELECT * FROM $withdraw_method_tbl WHERE gateway_id = '$gateway_id' AND status = 'enabled' ");
        if (!mysqli_fetch_array($query)) {
            return_exit("This withdraw method does not exists");
        }


        $gateway_id_array = [$gateway_id];
        $action = "added";
        $payment_gateways = user_payment_gateways($user_id);

        $output = new stdClass;
        if (is_selected_payment_gateway_id($gateway_id, $user_id)) {
            $action = "removed";
            $payment_gateways = array_diff($payment_gateways, $gateway_id_array);
            $output->message = "Withdraw method successfully removed";
        } else {
            $payment_gateways = array_merge($payment_gateways, $gateway_id_array);
            $output->message = "Withdraw method successfully added";
        }

        $payment_gateways = implode(",", $payment_gateways);
        $query = mysqli_query($conn, "UPDATE $users_tbl SET payment_gateways = '$payment_gateways' WHERE user_id = '$user_id' ");
        if (!$query) {
            return_exit("Error in updating data");
        }
        if($action == "removed"){
            delete_user_gateway_payment_data($user_id,$gateway_id);
        }
        $output->action = $action;
        echo json_encode($output);
        break;

    case "payment_gateway_image_upload":
        $image_data = upload_image("payment_gateway_file");
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

    case "payment_gateway_form":
        if (!is_isset("form_id", "payment_gateway_input")) {
            return_exit("Invalid request");
        }

        $payment_gateway_input = $_POST["payment_gateway_input"];
        if (!is_array($payment_gateway_input)) {
            return_exit("Invalid request");
        }

        $gateway_id = $_POST["form_id"];
        if (!is_payment_gateway_id($gateway_id)) {
            return_exit("Payment Gateway Doesn't Exists");
        }

        $payment_gateway_keys = array_keys($payment_gateway_input);
        $db_payment_gateway_keys = payment_gateway_keys($gateway_id);


        if ($payment_gateway_keys != $db_payment_gateway_keys) {
            return_exit("Invalid request");
        }

        
        $output = new stdClass;
        foreach ($payment_gateway_input as $requirement_id => $value) {
            $query = mysqli_query($conn, "SELECT * FROM $user_gateway_val_tbl WHERE requirement_id = '$requirement_id' AND user_id = '$user_id' ");
            if (!mysqli_num_rows($query)) {
                $sub_query = mysqli_query($conn, "INSERT INTO $user_gateway_val_tbl (`requirement_id`,`value`,`user_id`)  VALUES ('$requirement_id','$value','$user_id') ");
                if (!$sub_query) {
                    return_exit("Error in adding details");
                }
                $output->message = "Detail added successfully";
            } else {
                $sub_query = mysqli_query($conn, "UPDATE $user_gateway_val_tbl SET 
                `value` = '$value' WHERE requirement_id = '$requirement_id' AND user_id = '$user_id' ");
                if (!$sub_query) {
                    return_exit("Error in updating details");
                }
                $output->message = "Detail updated successfully";
            }
        }
        echo json_encode($output);
        break;

    default:
        http_response_404();
        break;
}
