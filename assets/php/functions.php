<?php


// **** ------- Common Functions Start ------- ****
check_table_exist($tables);


$web_max_img_size_text = 4; // In MB
$web_max_img_size = $web_max_img_size_text * 1000000;
$web_currency = web_currency();
$web_currency_position = web_currency_position();

if (is_user_loggedin()) {
    global $conn;
    global $login_session_tbl;
    $session_id = $_COOKIE[$web_name . '_usession_id'];
    $query = mysqli_query($conn, "SELECT * FROM $login_session_tbl WHERE session_id = '$session_id' ");
    $data = mysqli_fetch_array($query);
    $user_id = $data['user_id'];
    $loggedin_user_id =  $user_id;
    check_package_validity($loggedin_user_id);
}

function sanitize_text($string)
{
    global $conn;
    $string = mysqli_real_escape_string($conn, $string);
    $string = trim(addslashes(htmlentities(htmlspecialchars($string))));
    return $string;
}

function unsanitize_text($string)
{
    $string = htmlspecialchars_decode($string);
    $string = str_replace('\r\n', "\r\n", $string);
    $string = str_replace('\r', "\r\n", $string);
    $string = str_replace('\n', "\r\n", $string);
    $string = stripslashes($string);
    return $string;
}

function is_empty()
{
    $strings = func_get_args();
    $output = false;
    foreach ($strings as $string) {
        if (is_array($string)) {
            if (empty($string)) {
                $output =  true;
            }
        } else {
            $string = sanitize_text($string);
            if (($string != '') && ($string != "undefined") && ($string != null) && (!empty($string))) {
            } elseif ($string == '0') {
            } else {
                $output =  true;
            }
        }
    }
    return $output;
}


function return_exit($text)
{
    echo $text;
    exit();
}

function get_image_src($image_id)
{
    global $conn;
    global $images_tbl;
    global $images_base_url;
    $query = mysqli_query($conn, "SELECT * FROM $images_tbl WHERE image_id = '$image_id' ");
    if (!mysqli_num_rows($query)) {
        return;
    }
    $row = mysqli_fetch_array($query);
    $image_src = $row["image_src"];
    $is_web_img = $row["is_web_img"];
    if ($is_web_img == '1') {
        $image_src = $images_base_url . '/users/' . $image_src;
    } else {
        $image_src = $images_base_url . '/web/' . $image_src;
    }

    return $image_src;
}


function is_url_exists($url)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($code == 200) {
        $status = true;
    } else {
        $status = false;
    }
    curl_close($ch);
    return $status;
}

function get_db_name()
{
    global $conn;
    $r = mysqli_query($conn, "SELECT DATABASE()");
    $db_name =  mysqli_fetch_array($r)[0];
    return $db_name;
}

function new_session_id()
{
    return sprintf(
        '%04x%04x%04x%04x%04x%04x%04x%04x',
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff)
    );
}

function date_time($date)
{
    if (is_empty($date)) return;
    return date("d F Y H:i:s", $date);
}

function to_date($date)
{
    if (is_empty($date)) return;
    return date("d F Y", $date);
}

function short_date($date)
{
    if (is_empty($date)) return;
    return date("D, j M y", $date);
}

function is_isset()
{
    $requests =  func_get_args();
    $output = true;
    foreach ($requests as $request) {
        if (!isset($_POST[$request])) {
            $output = false;
        }
    }
    return $output;
}

function new_registration_otp()
{
    global $conn;
    global $otp_tbl;
    $otp = rand(1000, 9999);
    $query = mysqli_query($conn, "SELECT * FROM $otp_tbl WHERE otp = '$otp' AND status = '0' ");
    if (mysqli_num_rows($query)) {
        mysqli_query($conn, "DELETE FROM $otp_tbl WHERE otp = '$otp' AND status = '0' ");
        new_registration_otp();
    } else {
        return $otp;
    }
}

function is_login_token_valid($token)
{
    global $conn;
    global $tokens_tbl;
    global $current_date;

    $query = mysqli_query($conn, "SELECT * FROM $tokens_tbl WHERE token = '$token' AND token_status = '0' ");
    if (!mysqli_num_rows($query)) {
        return false;
    }
    $data =  mysqli_fetch_array($query);
    $valid_till =  $data['valid_till'];
    if ($valid_till < $current_date) {
        return false;
    }
    return true;
}

function locate_to($url)
{
    header("location:$url");
    exit();
}

function get_uuid()
{
    return sprintf(
        '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff)
    );
}

// Get a unique id
function unique_id()
{
    return rand(1111111, 9999999);
}


function get_new_image_name($file_extension)
{
    global $current_date;
    $rand = get_uuid();
    $unique_id = unique_id();
    return $unique_id . $current_date . $rand . '.' . $file_extension;
}

function check_table_exist($tables)
{
    global $conn;
    $db_name = get_db_name();
    if (!is_array($tables)) return_exit("Database error");
    global $conn;
    foreach ($tables as $table) {
        $exists = mysqli_query($conn, "SELECT 1 FROM $table");
        if ($exists === FALSE) {
            return_exit("$table table doesn't exist in database '$db_name' ");
        }
    }
}

function is_page_active($page_name)
{
    global $active_page;
    if ($active_page == $page_name) {
        return "active";
    }
}

function web_footer($type)
{
    global $base_url;
    $output = file_get_contents($base_url . '/assets/nav/user/footer.php');
    if ($type == "admin") {
        $output = file_get_contents($base_url . '/assets/nav/admin/footer.php');
    }
    return $output;
}


function web_metadata()
{
    global $base_url;
    $output = file_get_contents($base_url . '/assets/nav/metadata.php');
    return $output;
}


function check_web_validation()
{
    global $web_host;
    $headers = apache_request_headers();
    $host = $headers["Host"];
    if ($host !== $web_host) {
        http_response_404();
    }
    if (isset($_SERVER['HTTP_REFERER'])) {
        $refer_url = $headers["Referer"];
        $refer_host = parse_url($refer_url, PHP_URL_HOST);
        if ($refer_host !== $web_host) {
            http_response_404();
        }
    }
}

function validate_post_input($input, $type, $input_name, $is_required)
{
    $web_max_mobile_length = 10;
    switch ($type) {

        case 'number':
            if (!preg_match("/^[0-9]*$/", $input)) {
                return_exit("$input_name has invalid characters");
            }
            break;

        case 'otp':
            if (!preg_match("/^[0-9]*$/", $input)) {
                return_exit("$input_name has invalid characters");
            }
            if (strlen($input) != 6) {
                return_exit("Invalid Otp length");
            }
            break;

        case 'alpha':
            if (!preg_match("/^[a-zA-Z ]*$/", $input)) {
                return_exit("$input_name has invalid characters");
            }
            break;

        case 'alpha_numeric':
            if (!preg_match("/^[a-zA-Z0-9 ]*$/", $input)) {
                return_exit("$input_name has invalid characters");
            }
            break;

        case 'decimal_numeric':
            if (!preg_match("/^[0-9. ]*$/", $input)) {
                return_exit("$input_name has invalid characters");
            }
            break;

        case 'mobile_number':
            if (!preg_match("/^[0-9]*$/", $input)) {
                return_exit("$input_name has invalid characters");
            }
            if (strlen($input) > $web_max_mobile_length) {
                return_exit("Invalid mobile number");
            }
            break;

        case 'pincode':
            if (!preg_match("/^[0-9]*$/", $input)) {
                return_exit("$input_name has invalid characters");
            }
            if (strlen($input) != 6) {
                return_exit("Invalid pincode length");
            }
            break;

        case 'email':
            if (!filter_var($input, FILTER_VALIDATE_EMAIL)) {
                return_exit("$input_name format is invalid");
            }
            break;

        default:
            break;
    }

    if ($is_required && is_empty($input)) {
        return_exit("$input_name is required");
    }
}



function upload_image($img_name)
{
    $output = array();
    $output["type"] = "error";

    if (!isset($_FILES[$img_name])) {
        $output["error"] = "Something went wrong";
        return $output;
    }

    global $conn;
    global $images_tbl;
    global $web_max_img_size_text;
    global $web_max_img_size;


    $file = $_FILES[$img_name];
    $file = preg_replace("/\s+/", "_", $file);
    $filename = $file['name'];
    $filepath = $file['tmp_name'];
    $file_extension =  strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $fileinfo = getimagesize($file['tmp_name']);
    $fileerror = $file['error'];
    $allowed_image_extension = array("png", "jpg", "jpeg");
    $ext_err_text = "Upload a valid image. Only PNG, JPG  and JPEG are allowed.";
    if ($fileerror != 0) {
        $output["error"] = "Error while uploading image";
        return $output;
    }
    if (!in_array($file_extension, $allowed_image_extension)) {
        $output["error"] = $ext_err_text;
        return $output;
    }

    if (
        $fileinfo['mime'] == 'image/jpeg' ||
        $fileinfo['mime'] == 'image/png'
    ) {
    } else {
        $output["error"] =  $ext_err_text;
        return $output;
    }

    if (($file['size'] > $web_max_img_size)) {
        $output["error"] = "Image size exceeds $web_max_img_size_text MB ";
        return $output;
    }

    $filename = get_new_image_name($file_extension);
    $destfile = '../images/users/' . $filename;
    compressImage($filepath, $destfile, 60);
    $query = mysqli_query($conn, "INSERT INTO $images_tbl (`image_src`) VALUES ('$filename')  ");
    if (!$query) {
        $output["error"] = "Error while uploading image3";
        return $output;
    }
    $image_inserted_id = mysqli_insert_id($conn);
    $output["type"] = "success";
    $output["image_id"] = $image_inserted_id;
    return $output;
}

function compressImage($source, $destination, $quality)
{
    $info = getimagesize($source);
    if ($info['mime'] == 'image/jpeg') {
        $image = imagecreatefromjpeg($source);
    } elseif ($info['mime'] == 'image/png') {
        $image = imagecreatefrompng($source);
    } else {
        return_exit("Upload a valid image. Only PNG, JPG and JPEG are allowed.");
    }
    imagejpeg($image, $destination, $quality);
}



// **** ------- Common Functions End ------- ****


// **** ------- Users Functions Start ------- ****

function is_user_id($user_id)
{
    global $conn;
    global $users_tbl;
    $query = mysqli_query($conn, "SELECT * FROM $users_tbl WHERE user_id = '$user_id' ");
    if (mysqli_num_rows($query)) {
        return true;
    }
    return false;
}

function is_user_name($user_name)
{
    global $conn;
    global $users_tbl;
    $query = mysqli_query($conn, "SELECT * FROM $users_tbl WHERE user_name = '$user_name' ");
    if (mysqli_num_rows($query)) {
        return true;
    }
    return false;
}

function is_admin($user_id)
{
    global $conn;
    global $users_tbl;
    $query = mysqli_query($conn, "SELECT * FROM $users_tbl WHERE user_id = '$user_id' AND user_role = '2' ");
    if (mysqli_num_rows($query)) {
        return true;
    }
    return false;
}

function check_user_id($user_id)
{
    if ($user_id == "root") return;
    if (!is_user_id($user_id)) {
        return_exit("Invalid user id: $user_id");
    }
}

function user_data($user_id)
{
    check_user_id($user_id);
    global $conn;
    global $users_tbl;
    $query = mysqli_query($conn, "SELECT * FROM $users_tbl WHERE user_id = '$user_id'");
    $data =  mysqli_fetch_array($query);
    return $data;
}

function is_user_loggedin()
{
    global $conn;
    global $web_name;
    global $login_session_tbl;
    global $current_date;

    if (!isset($_COOKIE[$web_name . '_usession_id'])) {
        return false;
    }

    $session_id = $_COOKIE[$web_name . '_usession_id'];
    if (is_empty($session_id)) {
        return false;
    }

    $query = mysqli_query($conn, "SELECT * FROM $login_session_tbl WHERE session_id = '$session_id' ");
    if (!mysqli_num_rows($query)) {
        return false;
    }

    $data = mysqli_fetch_array($query);
    $valid_till = $data['valid_till'];
    if ($valid_till < $current_date) {
        return false;
    }

    $user_id = $data['user_id'];
    if (is_user_id($user_id)) {
        return true;
    }
    return false;
}

function is_user_already_loggedin()
{
    global $base_url;
    if (is_user_loggedin()) {
        locate_to($base_url . '/dashboard/');
    }
}

function check_user_login()
{
    global $base_url;
    if (!is_user_loggedin()) {
        locate_to("$base_url/login");
    }
}


function user_name($user_id)
{
    $data = user_data($user_id);
    if ($user_id == "root") return "root";
    return unsanitize_text($data['user_name']);
}


function user_full_name($user_id)
{
    $data = user_data($user_id);
    $output = $data['full_name'];
    return unsanitize_text($output);
}

function user_image($user_id)
{
    global $images_base_url;
    $data = user_data($user_id);
    $image_id = $data['user_image_id'];
    if (is_empty($image_id)) {
        return $images_base_url . '/web/avatar.jpg';
    }
    $image_src = get_image_src($image_id);
    return $image_src;
}
function user_status($user_id)
{
    $data = user_data($user_id);
    $status = $data["status"];
    return $status;
}
function user_status_label($user_id)
{
    $data = user_data($user_id);
    $status = $data["status"];
    if ($status == "active") {
        $output = '<label class="badge alert-success">active</label>';
    } else {
        $output = '<label class="badge alert-danger">blocked</label>';
    }
    return $output;
}
function user_email($user_id)
{
    $data = user_data($user_id);
    return $data["user_email"];
}

function user_pan_image($user_id)
{
    $data = user_data($user_id);
    $image_id = $data['pan_image_id'];
    $image_src = get_image_src($image_id);
    return $image_src;
}
function user_aadhar_image($user_id)
{
    $data = user_data($user_id);
    $image_id = $data['aadhar_image_id'];
    $image_src = get_image_src($image_id);
    return $image_src;
}

function user_gender($user_id)
{
    $data = user_data($user_id);
    return $data['user_gender'];
}

function user_registration_date($user_id)
{
    $data = user_data($user_id);
    $user_registration_date =  $data['user_registration_date'];
    return date_time($user_registration_date);
}

function user_dob($user_id)
{
    $data = user_data($user_id);
    return $data['user_dob'];
}

function user_pan_number($user_id)
{
    $data = user_data($user_id);
    return unsanitize_text($data['user_pan_number']);
}

function user_aadhar_number($user_id)
{
    $data = user_data($user_id);
    return unsanitize_text($data['user_aadhar_number']);
}

function user_address_1($user_id)
{
    $data = user_data($user_id);
    return unsanitize_text($data['user_address_1']);
}

function user_address_2($user_id)
{
    $data = user_data($user_id);
    return unsanitize_text($data['user_address_2']);
}

function user_country($user_id)
{
    $data = user_data($user_id);
    return unsanitize_text($data['user_country']);
}

function user_state($user_id)
{
    $data = user_data($user_id);
    return unsanitize_text($data['user_state']);
}

function user_city($user_id)
{
    $data = user_data($user_id);
    return unsanitize_text($data['user_city']);
}

function user_pin_code($user_id)
{
    $data = user_data($user_id);
    return unsanitize_text($data['user_pin_code']);
}

function user_mobile_number($user_id)
{
    $data = user_data($user_id);
    return unsanitize_text($data['user_mobile_number']);
}


function user_payment_gateways($user_id)
{
    $data = user_data($user_id);
    $payment_gateways =  $data['payment_gateways'];
    $payment_gateways = explode(",", $payment_gateways);
    if (!is_array($payment_gateways)) {
        return array();
    }
    return $payment_gateways;
}

function is_selected_payment_gateway_id($gateway_id, $user_id)
{
    $user_payment_gateways = user_payment_gateways($user_id);
    if (in_array($gateway_id, $user_payment_gateways)) {
        return true;
    }
    return false;
}

// Logout all users except current user
function logout_with_excep($user_id)
{
    global $conn;
    global $web_name;
    global $login_session_tbl;
    $session_id = $_COOKIE[$web_name . '_usession_id'];
    $query = mysqli_query($conn, "DELETE FROM $login_session_tbl WHERE user_id = '$user_id' AND session_id != '$session_id' ");
}

function get_new_forgot_pwd_otp($user_email)
{
    global $conn;
    global $current_date;
    global $otp_tbl;

    $query = mysqli_query($conn, "SELECT * FROM $otp_tbl WHERE otp_sender = '$user_email' AND otp_status = '1' AND otp_purpose = '2' ");
    if (mysqli_num_rows($query)) {
        $row = mysqli_fetch_array($query);
        $otp = $row["otp"];
        if (is_valid_reset_password_otp($otp, $user_email)) {
            return $otp;
        } else {
            $otp = rand(111111, 999999);
            $query = mysqli_query($conn, "SELECT * FROM $otp_tbl WHERE otp = '$otp' AND otp_status = '1'  AND otp_purpose = '2' ");
            if (mysqli_num_rows($query)) {
                $otp = get_new_registration_otp($user_email);
            }
            $valid_till = strtotime("+15 minutes", ($current_date));
            $query = mysqli_query($conn, "INSERT INTO $otp_tbl (`otp`, `otp_sender`, `valid_till`, `otp_status`, `otp_purpose`)
                 VALUES ('$otp','$user_email','$valid_till','1','2') ");
            if (!$query) {
                return false;
            }
            return $otp;
        }
    } else {
        $otp = rand(111111, 999999);
        $query = mysqli_query($conn, "SELECT * FROM $otp_tbl WHERE otp = '$otp' AND otp_status = '1' AND otp_purpose = '2' ");
        if (mysqli_num_rows($query)) {
            $otp = get_new_registration_otp($user_email);
        }
        $valid_till = strtotime("+15 minutes", ($current_date));
        $query = mysqli_query($conn, "INSERT INTO $otp_tbl (`otp`, `otp_sender`, `valid_till`, `otp_status`,`otp_purpose`)
                 VALUES ('$otp','$user_email','$valid_till','1','2') ");
        if (!$query) {
            return false;
        }
        return $otp;
    }
}

function get_reset_password_html($user_id)
{
    $output = '    <p class="login-card-description">Set a new password</p>
                            <form id="reset_password_form" class="needs-validation" novalidate>
                                <div class="form-floating mb-3">
                                    <label for="user_id">User ID</label>
                                    <input readonly value=' . $user_id . ' required type="text" name="user_id" id="user_id" class="form-control" placeholder="User ID">
                                    <div class="invalid-feedback">Please provide a valid User ID.</div>
                                </div>
                                <div class="form-floating mb-3">
                                    <label for="password">New Password</label>
                                    <input data-validate="password" required type="password" name="password" id="password" class="form-control" placeholder="***********">
                                    <div class="invalid-feedback">Please provide a valid password.</div>
                                </div>
                                <div class="form-floating mb-3">
                                    <label for="confirm_password">Confirm New Password</label>
                                    <input data-validate="confirm_password" required type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="***********">
                                    <div class="invalid-feedback">Please provide a valid password.</div>
                                </div>
                                <div class="form-floating mb-3">
                                    <label for="otp">Enter OTP</label>
                                    <input data-validate="number" maxlength="6" data-validate="otp" required type="text" name="otp" id="otp" class="form-control" placeholder="123456">
                                    <div class="invalid-feedback">Please provide a valid otp.</div>
                                </div>
                                <button type="submit" class="m-0 btn btn-block btn-primary mb-4">Update Password</button>
                                <p class="mt-2 text-center text-muted"> Didn\'t receive otp? <a data-user="' . $user_id . '" id="resend_reset_pwd_otp" class="link" >Resend</a></p>
                            </form>';
    return $output;
}

function is_valid_reset_password_otp($otp, $user_email)
{
    global $conn;
    global $otp_tbl;
    global $current_date;
    $query = mysqli_query($conn, "SELECT * FROM $otp_tbl WHERE otp = '$otp' AND otp_sender = '$user_email' AND otp_status = '1'  AND otp_purpose = '2' ");
    if (!mysqli_num_rows($query)) {
        return false;
    }
    $data = mysqli_fetch_array($query);
    $otp_valid_date = ($data['valid_till']);
    if ($otp_valid_date < $current_date) {
        return false;
    } else {
        return true;
    }
}

function update_otp_status($otp, $user_email, $otp_purpose)
{
    global $conn;
    global $otp_tbl;
    $query = mysqli_query($conn, "UPDATE $otp_tbl SET otp_status = '2' WHERE otp_purpose = '$otp_purpose' AND otp_sender = '$user_email' AND otp = '$otp' ");
}


// Logout all users after changing password
function logout_all_user($user_id)
{
    global $conn;
    global $login_session_tbl;
    mysqli_query($conn, "DELETE FROM $login_session_tbl WHERE user_id = '$user_id' ");
}


// **** ------- Users Functions End ------- ****

// **** ----- Users Balance Functions Start ------ **
function user_balance_data($user_id)
{
    check_user_id($user_id);
    global $conn;
    global $balance_tbl;
    $query = mysqli_query($conn, "SELECT * FROM $balance_tbl WHERE user_id = '$user_id' ");
    $data = mysqli_fetch_array($query);
    return $data;
}

function user_wallet($user_id)
{
    $data = user_balance_data($user_id);
    return $data['wallet'];
}

function user_income($user_id)
{
    $data = user_balance_data($user_id);
    return $data['income'];
}

function user_total_income($user_id)
{
    $data = user_balance_data($user_id);
    return $data['total_income'];
}

function user_total_withdrawl($user_id)
{
    $data = user_balance_data($user_id);
    return $data['total_withdrawl'];
}

function user_deposit_review($user_id)
{
    $data = user_balance_data($user_id);
    return $data['deposit_review'];
}

function user_expenditure($user_id)
{
    $data = user_balance_data($user_id);
    return $data['expenditure'];
}

function user_pending_amt($user_id)
{
    $data = user_balance_data($user_id);
    return $data['pending'];
}

function last_added_money($user_id)
{
    $data = user_balance_data($user_id);
    return $data['last_added_money'];
}

function last_withdraw($user_id)
{
    $data = user_balance_data($user_id);
    return $data['last_withdrawl'];
}

//


function user_pair_income($user_id)
{
    $data = user_balance_data($user_id);
    return $data['pair_income'];
}

function user_total_added_money($user_id)
{
    $data = user_balance_data($user_id);
    return $data['total_added_money'];
}

function user_last_added_money($user_id)
{
    $data = user_balance_data($user_id);
    return $data['last_added_money'];
}

function user_last_withdraw_money($user_id)
{
    $data = user_balance_data($user_id);
    return $data['last_withdrawl'];
}

function user_pair_count($user_id)
{
    $data = user_tree_data($user_id);
    return $data['pair_count'];
}

function user_total_downlines($user_id)
{
    $data = user_tree_data($user_id);
    $left_count = $data["left_count"];
    $right_count = $data["right_count"];
    return $left_count + $right_count;
}

function user_direct_referrals_count($user_id)
{
    global $conn;
    global $users_tree_tbl;
    $query = mysqli_query($conn, "SELECT COUNT(*) FROM $users_tree_tbl WHERE referral_id = '$user_id' ");
    return mysqli_fetch_array($query)[0];
}

// **** ----- Users Balance Functions End ------ **


// **** ----- Setting Functions Start ------ **
function setting_data()
{
    global $conn;
    global $setting_tbl;
    $query = mysqli_query($conn, "SELECT * FROM $setting_tbl ");
    if (!mysqli_num_rows($query)) return;
    $row = mysqli_fetch_array($query);
    return $row;
}

function web_notice()
{
    $data = setting_data();
    $notice =  $data["notice"];
    $notice = unsanitize_text($notice);
    return $notice;
}

function web_currency()
{
    $data = setting_data();
    return $data["web_currency"];
}

function web_currency_position()
{
    $data = setting_data();
    return $data["web_currency_position"];
}



// **** ----- Setting Functions End ------ **



// **** ---- Registration Functions Start ----- *** 



function get_new_registration_otp($user_email)
{
    global $conn;
    global $current_date;
    global $otp_tbl;

    $query = mysqli_query($conn, "SELECT * FROM $otp_tbl WHERE otp_sender = '$user_email' AND otp_status = '1' AND otp_purpose = '1' ");
    if (mysqli_num_rows($query)) {
        $row = mysqli_fetch_array($query);
        $otp = $row["otp"];
        if (is_valid_registration_otp($otp, $user_email)) {
            return $otp;
        } else {
            $otp = rand(111111, 999999);
            $query = mysqli_query($conn, "SELECT * FROM $otp_tbl WHERE otp = '$otp' AND otp_status = '1'  AND otp_purpose = '1' ");
            if (mysqli_num_rows($query)) {
                $otp = get_new_registration_otp($user_email);
            }
            $valid_till = strtotime("+15 minutes", ($current_date));
            $query = mysqli_query($conn, "INSERT INTO $otp_tbl (`otp`, `otp_sender`, `valid_till`, `otp_status`, `otp_purpose`)
                 VALUES ('$otp','$user_email','$valid_till','1','1') ");
            if (!$query) {
                return false;
            }
            return $otp;
        }
    } else {
        $otp = rand(111111, 999999);
        $query = mysqli_query($conn, "SELECT * FROM $otp_tbl WHERE otp = '$otp' AND otp_status = '1' AND otp_purpose = '1' ");
        if (mysqli_num_rows($query)) {
            $otp = get_new_registration_otp($user_email);
        }
        $valid_till = strtotime("+15 minutes", ($current_date));
        $query = mysqli_query($conn, "INSERT INTO $otp_tbl (`otp`, `otp_sender`, `valid_till`, `otp_status`,`otp_purpose`)
                 VALUES ('$otp','$user_email','$valid_till','1','1') ");
        if (!$query) {
            return false;
        }
        return $otp;
    }
}


function is_valid_registration_otp($otp, $user_email)
{
    global $conn;
    global $otp_tbl;
    global $current_date;

    $query = mysqli_query($conn, "SELECT * FROM $otp_tbl WHERE otp = '$otp' AND otp_sender = '$user_email' AND otp_status = '1'  AND otp_purpose = '1' ");
    if (!mysqli_num_rows($query)) {
        return false;
    }

    $data = mysqli_fetch_array($query);
    $otp_valid_date = ($data['valid_till']);
    if ($otp_valid_date < $current_date) {
        return false;
    } else {
        return true;
    }
}

function is_valid_referral_pin($referral_pin)
{
    global $conn;
    global $pins_tbl;
    $query = mysqli_query($conn, "SELECT * FROM $pins_tbl WHERE pin = '$referral_pin' AND status = 'inactive'");
    if (mysqli_num_rows($query)) {
        return true;
    }
    return false;
}

function is_username_exist($user_name)
{
    global $conn;
    global $users_tbl;
    $query = mysqli_query($conn, "SELECT * FROM $users_tbl WHERE user_name = '$user_name' ");
    if (mysqli_num_rows($query)) {
        return true;
    }
    return false;
}


function validate_placement_type($placement_id, $placement_type)
{
    $data = user_tree_data($placement_id);
    $placement_type_id = $placement_type . "_id";
    $id = $data[$placement_type_id];
    if ($id != 0) {
        return_exit("Placement id $placement_id with $placement_type is already in use");
    }
}

function check_combination_referral_placement($referral_id, $placement_id)
{
    return true;
}

function get_new_user_id()
{
    $user_id = 1006090;
    while (is_user_id($user_id)) {
        $user_id += 1;
    }
    if (!is_user_id($user_id)) {
        return $user_id;
    }
}

function add_user_balance_row($user_id)
{
    global $conn;
    global $balance_tbl;
    $query = mysqli_query($conn, "INSERT INTO $balance_tbl (`user_id`) VALUES('$user_id') ");
    if ($query) {
        return true;
    }
    return false;
}
function get_level($new_user_id, $user_id)
{
    $level = 1;
    $placement_id = user_placement_id($new_user_id);
    while ($placement_id != $user_id) {
        $level++;
        $placement_id = user_placement_id($placement_id);
    }
    return $level;
}


function is_user_left_right($user_id, $campare_id)
{
    global $conn;
    global $users_tree_tbl;
    $query = mysqli_query($conn, "SELECT * FROM $users_tree_tbl WHERE user_id = '$user_id' ");
    $row = mysqli_fetch_array($query);
    $placement_id = $row["placement_id"];
    while ($placement_id != $campare_id) {
        $sub_query = mysqli_query($conn, "SELECT * FROM $users_tree_tbl WHERE user_id = '$placement_id' ");
        $row = mysqli_fetch_array($sub_query);
        $placement_id = $row["placement_id"];
    }

    if ($placement_id == $campare_id) {
        $placement_type = $row["placement_type"];
    }

    return $placement_type;
}


function check_pending_pair_income($user_id)
{
    global $conn;
    global $balance_tbl;
    global $users_tree_tbl;


    $user_balance_data = user_balance_data($user_id);
    $has_purchased_premium = $user_balance_data["has_purchased_premium"];

    $placement_id = user_placement_id($user_id);
    while ($placement_id != '0') {
        $level = get_level($user_id, $placement_id);
        if ($has_purchased_premium == "no" || $level <= 12) {
            $placement = is_user_left_right($user_id, $placement_id);
            $premium_count = "premium_" . $placement . "_count";
            mysqli_query($conn, "UPDATE $users_tree_tbl SET $premium_count = $premium_count + 1 WHERE user_id = '$placement_id' ");
            $com_pair = ($placement == "left") ? "premium_right_count" : "premium_left_count";
            $user_data = user_tree_data($placement_id);
            if (($user_data[$premium_count] <= $user_data[$com_pair]) && (($user_data['premium_left_count'] !== 0) && ($user_data['premium_right_count'] !== 0))) {
                add_user_pair_income($user_id, $placement_id);
            }
        }
        $placement_id = user_placement_id($placement_id);
    }
   mysqli_query($conn, "UPDATE $balance_tbl SET has_purchased_premium = 'yes' WHERE user_id = '$user_id' ");

}

function add_user_pair_income($new_user_id, $user_id)
{
    global $conn;
    global $transaction_tbl;
    global $current_date;
    global $capping_tbl;
    global $pair_income_tbl;
    global $balance_tbl;

    $level = get_level($new_user_id, $user_id);

    $pair_income = get_pair_commission($user_id);

    $maximum_pair_income = maximum_pair_income($user_id);

    $user_today_pair_income = user_today_pair_income($user_id);

    $valid_pair_income = $maximum_pair_income - $user_today_pair_income;

    if ($valid_pair_income > $pair_income) {
        $valid_pair_income  = $pair_income;
    }

    $status = "credit";
    $description = "pair income";

    if ($valid_pair_income == $pair_income) {
    } else {
        $capped_income = $pair_income - $valid_pair_income;
        $capped_income = add_currency($capped_income);
        $description = "pair income (capped $capped_income) ";
        $status = "capping";
    }

    $query = mysqli_query($conn, "UPDATE $balance_tbl SET
     pair_income = pair_income + '$valid_pair_income',
     wallet = wallet + '$valid_pair_income',
     income = income + '$valid_pair_income',
     total_income = total_income + '$valid_pair_income'
      WHERE user_id = '$user_id' ");

    $transaction_id = new_transaction_id();
    $query = mysqli_query($conn, "INSERT INTO $transaction_tbl 
            ( `transaction_id`, `user_id`, `amount`, `transaction_charge`, `net_amount`,`description`, `category`, `date`, `status`) 
        VALUES ('$transaction_id','$user_id','$valid_pair_income','0','$valid_pair_income','$description','pair income','$current_date','$status') ");
    if (!$query) {
        return_exit("Error in updating pir income");
    }

    $query = mysqli_query($conn, "INSERT INTO $pair_income_tbl ( `user_id`, `new_user_id`, `pair_income`, `level`, `date`, `status`) 
    VALUES ('$user_id','$new_user_id','$pair_income','$level','$current_date','$status') ");
    if (!$query) return_exit("Error in updating pair income");

    $current_time = strtotime(date("d-m-Y"));
    $query = mysqli_query($conn, "SELECT * FROM $capping_tbl WHERE user_id = '$user_id' AND date = '$current_time' ");
    if (mysqli_num_rows($query)) {
        $query = mysqli_query($conn, "UPDATE $capping_tbl SET pair_income = pair_income + '$valid_pair_income' WHERE user_id = '$user_id' AND date = '$current_time' ");
        if (!$query) {
            return_exit("Error in updating pair income");
        }
    } else {
        $query = mysqli_query($conn, "INSERT INTO $capping_tbl  (`user_id`,`pair_income`,`date`) VALUES ('$user_id','$valid_pair_income','$current_time') ");
        if (!$query) {
            return_exit("Error in updating pair income");
        }
    }
}

function user_today_pair_income($user_id)
{
    global $conn;
    global $capping_tbl;
    $current_time = strtotime(date("d-m-Y"));
    $pair_income = 0;
    $query = mysqli_query($conn, "SELECT * FROM $capping_tbl WHERE user_id = '$user_id' AND date = '$current_time' ");
    if (mysqli_num_rows($query)) {
        $row = mysqli_fetch_array($query);
        $pair_income = $row["pair_income"];
    }
    return $pair_income;
}

function update_user_placement_data($placement_id, $placement_type, $user_id)
{
    global $conn;
    global $users_tree_tbl;
    $placement_type_id = $placement_type . "_id";
    mysqli_query($conn, "UPDATE $users_tree_tbl SET $placement_type_id = '$user_id' WHERE `user_id` = '$placement_id' ");
}

function check_binary_count($user_id, $placement_id, $placement_type)
{
    global $conn;
    global $users_tree_tbl;
    while ($placement_id != 0) {
        $placement_type_count = $placement_type . "_count";
        mysqli_query($conn, "UPDATE $users_tree_tbl SET $placement_type_count = $placement_type_count+1 WHERE user_id = '$placement_id' ");
        check_is_pair_generated($user_id, $placement_id, $placement_type_count);
        $placement_type = placement_type($placement_id);
        $placement_id = user_placement_id($placement_id);
    }
}

function check_is_pair_generated($last_user_id, $placement_id, $placement_type_count)
{
    global $conn;
    global $users_tree_tbl;
    $com_pair = ($placement_type_count == "left_count") ? "right_count" : "left_count";
    $user_data = user_tree_data($placement_id);
    if (($user_data[$placement_type_count] <= $user_data[$com_pair]) && (($user_data['left_count'] !== 0) && ($user_data['right_count'] !== 0))) {
        mysqli_query($conn, "UPDATE $users_tree_tbl SET pair_count = pair_count + 1 WHERE user_id ='$placement_id' ");
    }
}


// **** ---- Registration Functions End ----- *** 


// ****** -- User Tree Data Start---- ***

function user_tree_data($user_id)
{
    check_user_id($user_id);
    global $conn;
    global $users_tree_tbl;
    $query = mysqli_query($conn, "SELECT * FROM $users_tree_tbl WHERE user_id = '$user_id' ");
    return mysqli_fetch_array($query);
}

function user_left_count($user_id)
{
    $data = user_tree_data($user_id);
    return $data['left_count'];
}

function user_right_count($user_id)
{
    $data = user_tree_data($user_id);
    return $data['right_count'];
}

function user_referred_by($user_id)
{
    $data = user_tree_data($user_id);
    $output = ($data['referral_id'] == 0) ? "root" : $data['referral_id'];
    return $output;
}


function check_package_validity($user_id)
{
    global $current_date;
    global $conn;
    global $package_history_tbl;
    global $transaction_tbl;

    $query = mysqli_query($conn, "SELECT * FROM $package_history_tbl WHERE user_id = '$user_id' AND status = 'active' ");
    $row = mysqli_fetch_array($query);
    $user_package_id = $row["package_id"];
    $expired_date = $row["expired_date"];

    if ($expired_date > $current_date || empty($expired_date)) return false;

    $query = mysqli_query($conn, "UPDATE $package_history_tbl SET status = 'expired' WHERE user_id = '$user_id' AND status = 'active' ");
    if (!$query) return_exit("Error in updating package data" . mysqli_error($conn));

    $query = mysqli_query($conn, "SELECT * FROM $package_history_tbl WHERE user_id = '$user_id' AND package_id = '$user_package_id' AND status = 'pending' ");
    if (mysqli_num_rows($query)) {
        $query = mysqli_query($conn, "UPDATE $package_history_tbl SET status = 'active' WHERE user_id = '$user_id' AND package_id = '$user_package_id' AND status = 'pending' LIMIT 1   ");
        if (!$query) return_exit("Error in updating package data" . mysqli_error($conn));
    } else {
        $basic_package_id = basic_package_id();
        $basic_package_data = package_data($basic_package_id);
        $basic_package_price = $basic_package_data["price"];
        $basic_package_name = $basic_package_data["package"];

        $transaction_id = new_transaction_id();
        $query = mysqli_query($conn, "INSERT INTO $transaction_tbl (`transaction_id`, `user_id`, `amount`, `transaction_charge`, `net_amount`, `description`, `category`, `date`, `status`)
                    VALUES ('$transaction_id','$user_id','$basic_package_price','0','$basic_package_price','$basic_package_name package activated','purchased package','$current_date','debit') ");
        if (!$query) return_exit("Error in updating package data" . mysqli_error($conn));

        $query = mysqli_query($conn, "INSERT INTO $package_history_tbl (`user_id`, `package_id`, `transaction_id`, `amount`, `date`,`activated_date`, `expired_date`,`status`) VALUES 
                       ('$user_id','$basic_package_id','$transaction_id','$basic_package_price','$current_date','$current_date','0','active')");
        if (!$query) return_exit("Error in updating package data" . mysqli_error($conn));
    }
}

function user_package_data($user_id)
{
    global $conn;
    global $package_history_tbl;
    $query = mysqli_query($conn, "SELECT * FROM $package_history_tbl WHERE user_id = '$user_id' AND status = 'active' ");
    $row = mysqli_fetch_array($query);
    return $row;
}


function user_package_id($user_id)
{

    check_package_validity($user_id);
    $data = user_package_data($user_id);
    $package_id = $data['package_id'];
    return $package_id;
}

function user_package($user_id)
{

    check_package_validity($user_id);
    global $conn;
    global $packages_tbl;

    $data = user_package_data($user_id);
    $package_id = $data['package_id'];

    $query = mysqli_query($conn, "SELECT * FROM $packages_tbl WHERE package_id = '$package_id' ");
    if (!mysqli_num_rows($query)) return;
    $row = mysqli_fetch_array($query);
    $package = $row["package"];
    return $package;
}



function user_package_validity_remaining($user_id)
{
    global $conn;
    global $package_history_tbl;
    global $current_date;

    $query =  mysqli_query($conn, "SELECT expired_date FROM $package_history_tbl WHERE user_id = '$user_id' AND status = 'active' ");
    $expired_date = mysqli_fetch_array($query)["expired_date"];
    if ($expired_date == '0') return "Unlimited";
    $remaing_time =  $expired_date - $current_date;

    $query =  mysqli_query($conn, "SELECT  activated_date,expired_date FROM $package_history_tbl WHERE user_id = '$user_id' AND status = 'pending' ");
    while ($row = mysqli_fetch_array($query)) {
        $remaing_time += $row["expired_date"] - $row["activated_date"];
    }

    $remaing_time =  secondsToTime($remaing_time);
    return  $remaing_time;
}


function secondsToTime($seconds)
{
    $dtF = new \DateTime('@0');
    $dtT = new \DateTime("@$seconds");
    $days =  $dtF->diff($dtT)->format('%a');
    $hours =  $dtF->diff($dtT)->format('%h');
    $minutes =  $dtF->diff($dtT)->format('%i');
    $seconds =  $dtF->diff($dtT)->format('%s');
    if ($days > 0) return $days . ' Days';
    if ($hours > 0) return $hours . ' Hours';
    if ($minutes > 0) return $minutes . ' Minutes';
    if ($seconds > 0) return $seconds . ' Seconds';
}

function user_placement_id($user_id)
{
    $data = user_tree_data($user_id);
    return $data['placement_id'];
}

function placement_type($user_id)
{
    $data = user_tree_data($user_id);
    return $data['placement_type'];
}


function pair_count($user_id)
{
    $data = user_tree_data($user_id);
    return $data['pair_count'];
}


// ****** -- User Tree Data End---- ***


// **** -- Support Table Start ---- ***

function support_tbl($user_id)
{
    check_user_id($user_id);
    global $conn;
    global $tickets_tbl;
    global $base_url;
    $query = mysqli_query($conn, "SELECT * FROM deposit_tickets WHERE ticket_creator = '$user_id' ORDER BY ticket_id DESC ");
    if (!mysqli_num_rows($query)) return;
    $output = '';
    $count = 0;
    while ($row = mysqli_fetch_array($query)) {
        $ticket_id = $row['ticket_id'];
        $count++;
        $subject = $row["ticket_subject"];
        $subject = mb_strimwidth($subject, 0, 30, '...');
        $status = $row["status"];
        $date = date_time($row["created_at"]);
        $view_ticket_url = $base_url . '/support/view-ticket?ticket=' . $ticket_id;

        if ($status == "1") {
            $status = '<label class="badge alert-warning" >pending</label>';
        } else
        if ($status == "2") {
            $status = '<label class="badge alert-success" >active</label>';
        } else 
        if ($status == "3") {
            $status = '<label class="badge alert-danger" >closed</label>';
        }

        $output .= '  <tr>
            <td>' . $count . '</td>
            <td>' . $ticket_id . '</td>
            <td>' . $subject . '</td>
            <td>' . $status . '</td>
            <td>' . $date . '</td>
            <td><a href="' . $view_ticket_url . '" class="justify-align-center"><i class="material-icons">visibility</i></a></td>
        </tr>';
    }
    return $output;
}

function deposit_tbl($user_id)
{
    check_user_id($user_id);
    global $conn;
    global $tickets_tbl;
    global $base_url;
    $query = mysqli_query($conn, "SELECT * FROM deposit_tickets WHERE ticket_creator = '$user_id' and ticket_purpose='1' ORDER BY ticket_id DESC ");
    if (!mysqli_num_rows($query)) return;
    $output = '';
    $count = 0;
    while ($row = mysqli_fetch_array($query)) {
        $ticket_id = $row['ticket_id'];
        $count++;
        $amount = $row["amount"];
        $status = $row["status"];
        $date = date_time($row["created_at"]);
        $view_ticket_url = $base_url . '/wallet/view-ticket?ticket=' . $ticket_id;

        if ($status == "1") {
            $status = '<label class="badge alert-warning" >pending</label>';
        } else
        if ($status == "2") {
            $status = '<label class="badge alert-success" >active</label>';
        } else 
        if ($status == "3") {
            $status = '<label class="badge alert-danger" >closed</label>';
        }

        $output .= '  <tr>
            <td>' . $count . '</td>
            <td>' . $ticket_id . '</td>
            <td>' . $amount . '</td>
            <td>' . $status . '</td>
            <td>' . $date . '</td>
            <td><a href="' . $view_ticket_url . '" class="justify-align-center"><i class="material-icons">visibility</i></a></td>
        </tr>';
    }
    return $output;
}

function withdraw_ticket_tbl($user_id)
{
    check_user_id($user_id);
    global $conn;
    global $tickets_tbl;
    global $base_url;
    $query = mysqli_query($conn, "SELECT * FROM deposit_tickets WHERE ticket_creator = '$user_id' and ticket_purpose='2' ORDER BY ticket_id DESC ");
    if (!mysqli_num_rows($query)) return;
    $output = '';
    $count = 0;
    while ($row = mysqli_fetch_array($query)) {
        $ticket_id = $row['ticket_id'];
        $count++; 
        $amount = $row["amount"];
        $status = $row["status"];
        $date = date_time($row["created_at"]);
        $view_ticket_url = $base_url . '/wallet/view-ticket?ticket=' . $ticket_id;

        if ($status == "1") {
            $status = '<label class="badge alert-warning" >pending</label>';
        } else
        if ($status == "2") {
            $status = '<label class="badge alert-success" >active</label>';
        } else 
        if ($status == "3") {
            $status = '<label class="badge alert-danger" >closed</label>';
        }

        $output .= '  <tr>
            <td>' . $count . '</td>
            <td>' . $ticket_id . '</td>
            <td>' . $amount . '</td>
            <td>' . $status . '</td>
            <td>' . $date . '</td>
            <td><a href="' . $view_ticket_url . '" class="justify-align-center"><i class="material-icons">visibility</i></a></td>
        </tr>';
    }
    return $output;
}


function is_ticket_id($ticket_id)
{
    global $conn;
    global $tickets_tbl;
    $query = mysqli_query($conn, "SELECT * FROM $tickets_tbl WHERE ticket_id = '$ticket_id' ");
    if (mysqli_num_rows($query)) {
        return true;
    }
    return false;
}

function ticket_message($ticket_id)
{
    if (!is_ticket_id($ticket_id)) return;
    global $conn;
    global $tickets_msg_tbl;
    $output = '';
    $query = mysqli_query($conn, "SELECT * FROM $tickets_msg_tbl WHERE ticket_id = '$ticket_id' ORDER BY id DESC");
    while ($row = mysqli_fetch_array($query)) {
        $replier = $row["replier"];
        $message = unsanitize_text($row["message"]);
        $date = date_time($row["date"]);
        $files = $row["files"];
        $replier_name = user_name($replier);
        $user_logo = user_image($replier);
        $files = show_support_files($files);
        $class = is_admin($replier) ? "card-left" : "";
        $output .= '<div class="card ' . $class . ' card-body" >
                        <div class="media"><img class="align-self-center" src="' . $user_logo . '" alt="">
                          <div class="media-body">
                            <div class="row">
                              <div class="col-md-4">
                                <div class="align-center">
                                    <h6 class="m-0">' . $replier_name . '</h6> <i style="font-size: 6px;" class="mx-2 fa fa-circle" ></i> <small class="text-muted">' . $date . '</small>
                                </div>
                              </div>
                            </div>
                            <p>' . $message . '</p>
                            <p>' . $files . '</p>
                          </div>
                        </div>
                      </div>';
    }
    return $output;
}

function show_support_files($files)
{
    $output = '';
    $count = 0;
    $files = explode(",", $files);
    foreach ($files as $image_id) {
        if (!is_empty($image_id)) {
            $count++;
            $image_src = get_image_src($image_id);
            $output .= '<a target="_blank" class="link" href="' . $image_src . '" > Attachment ' . $count . '</a>';
        }
    }
    return $output;
}

// **** -- Support Table End ---- ***

function my_referral_tbl($user_id)
{
    check_user_id($user_id);
    global $conn;
    global $users_tree_tbl;
    $count = 0;
    $output = '';
    $query = mysqli_query($conn, "SELECT * FROM $users_tree_tbl WHERE referral_id = '$user_id' ORDER BY tree_id DESC ");
    if (!mysqli_num_rows($query)) return;
    while ($row = mysqli_fetch_array($query)) {
        $count++;
        $referred_id = $row["user_id"];
        $reffered_user_name = user_name($referred_id);
        $date = user_registration_date($referred_id);
        $user_package = user_package($referred_id);
        $output .= ' <tr>
            <td>' . $count . '</td>
           <td>' . $referred_id . '</td>
            <td>' . $reffered_user_name . '</td>
            <td>' . $user_package . '</td>
            <td>' . $date . '</td>
        </tr>';
    }
    return $output;
}

function transaction_tbl($user_id)
{
    global $conn;
    global $transaction_tbl;
    $query = mysqli_query($conn, "SELECT * FROM $transaction_tbl WHERE user_id = '$user_id' AND status != 'pending' ORDER BY id DESC ");
    $count = 0;
    $output = '';
    while ($data = mysqli_fetch_array($query)) {
        $count = $count + 1;
        $trx_id = $data["transaction_id"];
        $amount = add_currency($data['amount']);
        $transaction_charge = add_currency($data['transaction_charge']);
        $net_amount =  add_currency($data['net_amount']);
        $date = date_time($data['date']);
        $description = $data['description'];
        $status = $data['status'];
        if ($status == "credit") {
            $status = '<label class="badge alert-success">' . $status . '</label>';
            $net_amount = '<span class="text-success">+'.$net_amount.'</span>';
        } elseif ($status == "debit") {
            $status = '<label class="badge alert-danger">' . $status . '</label>';
            $net_amount = '<span class="text-danger">-' . $net_amount . '</span>';
        } elseif ($status == "review") {
            $status = '<label class="badge alert-info">' . $status . '</label>';
            $net_amount = '<span class="text-warning">' . $net_amount . '</span>';
        } elseif ($status == "rejected") {
            $status = '<label class="badge alert-primary">' . $status . '</label>';
            $net_amount = '<span class="text-primary">' . $net_amount . '</span>';
        } elseif ($status == "capping") {
            $status = '<label class="badge alert-warning">' . $status . '</label>';
            $net_amount = '<span class="text-success">+' . $net_amount . '</span>';
        }

        $output .= '<tr>
            <td>' . $count . '</td> <td>' . $trx_id . '</td>
            <td>' . $amount . '</td>
            <td>' . $transaction_charge . '</td>
            <td>' . $net_amount . '</td>
            <td>' . $date . '</td>
            <td>' . $description . '</td>
            <td>' . $status . '</td>
        </tr>';
    }
    return $output;
}


function deposit_history_tbl($user_id)
{
    global $conn;
    global $deposit_tbl;
    global $base_url;
    $query = mysqli_query($conn, "SELECT * FROM $deposit_tbl WHERE user_id = '$user_id' ORDER BY deposit_id DESC ");
    $count = 0;
    $output = '';
    while ($data = mysqli_fetch_array($query)) {
        $count = $count + 1;
        $trx_id = $data["transaction_id"];
        $amount =  add_currency($data['amount']);
        $date = date_time($data['date']);
        $gateway = $data["gateway"];
        $status = $data['status'];
        $payment_type = $data["payment_type"];

        if ($status == "approved") {
            $status = '<label class="badge alert-success">credit</label>';
        } elseif ($status == "rejected") {
            $status = '<label class="badge alert-danger">' . $status . '</label>';
        } elseif ($status == "review") {
            $status = '<label class="badge alert-warning">' . $status . '</label>';
        }

        if ($payment_type == "verification") {
            $gateway = '<a class="link"  href="' . $base_url . '/wallet/deposit-preview?id=' . $trx_id . '" >' . $gateway . '</a>';
        }

        $output .= '<tr>
            <td>' . $count . '</td>
            <td>' . $trx_id . '</td>
            <td>' . $amount . '</td>
            <td>' . $date . '</td>
            <td>' . $gateway . '</td>
            <td>' . $status . '</td>
        </tr>';
    }
    return $output;
}

function new_transaction_id()
{
    global $conn;
    global $paytm_tbl;
    global $web_prefix;
    $transaction_id = rand(100000, 999999);
    $transaction_id = $web_prefix . $transaction_id;
    $query = mysqli_query($conn, "SELECT * FROM $paytm_tbl WHERE transaction_id = '$transaction_id' ");
    if (!mysqli_num_rows($query)) {
        return $transaction_id;
    }
    $transaction_id = new_transaction_id();
    return $transaction_id;
}


function withdraw_tbl($user_id)
{
    check_user_id($user_id);
    global $conn;
    global $base_url;
    global $withdraw_tbl;
    $count = 0;
    $output = '';
    $query = mysqli_query($conn, "SELECT * FROM $withdraw_tbl WHERE user_id = '$user_id' ORDER BY withdraw_id DESC ");
    if (!mysqli_num_rows($query)) return;
    while ($data = mysqli_fetch_array($query)) {
        $count = $count + 1;
        $trx_id = $data["transaction_id"];
        $amount =  add_currency($data['amount']);
        $net_amount = add_currency($data["net_amount"]);
        $charge = add_currency($data["total_charge"]);
        $requested_date = date_time($data['requested_date']);
        $success_date = date_time($data['success_date']);
        $success_date = is_empty($success_date) ? "NA" : $success_date;
        $status = $data['status'];
        if ($status == "approved") {
            $status = '<label class="badge alert-success">' . $status . '</label>';
        } elseif ($status == "pending") {
            $status = '<label class="badge alert-warning">' . $status . '</label>';
        } elseif ($status == "rejected") {
            $status = '<label class="badge alert-danger">' . $status . '</label>';
        }
        $url = $base_url . '/wallet/withdraw-preview?id=' . $trx_id;
        $payment_method = $data["payment_method"];
        $action = '<a href="' . $url . '" class="ml-2 btn btn-success"><i class="fa fa-eye-slash"></i></a>';
        $output .= '<tr>
            <td>' . $count . '</td>
            <td>' . $trx_id . '</td>
            <td>' . $amount . '</td>
            <td>' . $charge . '</td>
            <td>' . $net_amount . '</td>
            <td>' . $payment_method . '</td>
            <td>' . $requested_date . '</td>
            <td>' . $success_date . '</td>
            <td>' . $status . '</td>
            <td>' . $action . '</td>
        </tr>';
    }
    return $output;
}



// function payment_gateway_name($gateway_id){
//     global $conn;
//     global $withdraw_method_tbl;
//     $query = mysqli_query($conn, "SELECT * FROM $withdraw_method_tbl WHERE gateway_id = '$gateway_id' ");
//     if (!mysqli_num_rows($query)) return;
//     $row = mysqli_fetch_array($query);
//     return $row["gateway_name"];
// }


// -- Tree Start


function my_team_users($user_id)
{
    global $conn;
    global $users_tree_tbl;
    $users = [];
    $query = mysqli_query($conn, "SELECT * FROM $users_tree_tbl WHERE user_id = '$user_id' ");
}


$tree = array();
function users_under_user_tree($user_id)
{
    global $tree;
    $tree[] = $user_id;
    $left_right_id = user_left_right_id($user_id);
    update_tree_data($user_id, $left_right_id);
    return $tree;
}

// Update data to show tree
function update_tree_data($user_id, $left_right_id)
{
    global $tree;
    foreach ($left_right_id as $id) {
        if ($id != 0) {
            $tree[] = $id;
            $left_right_id = user_left_right_id($id);
            update_tree_data($id, $left_right_id);
        }
    }
}

function user_left_right_id($user_id)
{
    $data = user_tree_data($user_id);
    $output = array();
    $output[] = $data['left_id'];
    $output[] = $data['right_id'];
    return $output;
}


function user_tree_nodes($user_id)
{
    global $images_base_url;
    $output = array();
    $users_under_user_tree = users_under_user_tree($user_id);
    foreach ($users_under_user_tree as $user_id) {
        $pid = user_placement_id($user_id);
        $name = user_name($user_id);
        $email = user_email($user_id);
        $img = user_image($user_id);
        $p_t = placement_type($user_id);
        $p_t = ($p_t == "left") ? "0" : "1";
        $left_right_id = user_left_right_id($user_id);
        $left_id  = $left_right_id[0];
        $right_id  = $left_right_id[1];

        if (!is_user_id($left_id)) {
            $rand_id = rand(100000, 99999999);
            $output[] = array(
                'id' => $rand_id,
                'pid' => $user_id,
                'name' => 'Join member',
                'title' => 'Click here',
                'img' =>  $images_base_url . '/web/add-icon.png',
                'p_t' => "0"
            );
        }

        if (!is_user_id($right_id)) {
            $rand_id = rand(100000, 99999999);
            $output[] = array(
                'id' => $rand_id,
                'pid' => $user_id,
                'name' => 'Join member',
                'title' => 'Click here',
                'img' =>  $images_base_url . '/web/add-icon.png',
                'p_t' => "1"
            );
        }
        $output[] = array(
            'id' => $user_id,
            'pid' => $pid,
            'name' => $name,
            'title' => $user_id,
            'email' => $email,
            'img' => $img,
            'p_t' => $p_t
        );
    }

    $tree_p_t = array_column($output, 'p_t');
    array_multisort($tree_p_t, SORT_ASC, $output);
    echo json_encode($output, JSON_PRETTY_PRINT);
}



// -- Tree End

// Package Start

function package_data($package_id)
{
    global $conn;
    global $packages_tbl;
    $query = mysqli_query($conn, "SELECT * FROM $packages_tbl WHERE package_id = '$package_id' ");
    $row = mysqli_fetch_array($query);
    return $row;
}

function is_package_id($package_id)
{
    global $conn;
    global $packages_tbl;
    $query = mysqli_query($conn, "SELECT * FROM $packages_tbl WHERE package_id = '$package_id' ");
    if (mysqli_num_rows($query)) return true;
    return false;
}

function user_withdraw_charge($user_id)
{
    $package_id = user_package_id($user_id);
    $package_data = package_data($package_id);
    return $package_data["withdraw_charge"];
}

function user_minimum_withdraw($user_id)
{
    $package_id = user_package_id($user_id);
    $package_data = package_data($package_id);
    return $package_data["minimum_withdraw"];
}
// Package End


function withdraw_methods($user_id)
{
    global $conn;
    global $base_url;
    global $withdraw_method_tbl;

    $output = '';
    $query = mysqli_query($conn, "SELECT * FROM $withdraw_method_tbl WHERE status = 'enabled' ");
    if (!mysqli_num_rows($query)) {
        return ("<div class='empty-container' ><h1>Currently no withdraw method is available</h1></div>");
    }
    while ($row = mysqli_fetch_array($query)) {
        $gateway_name = $row["gateway_name"];
        $gateway_id = $row["gateway_id"];
        $gateway_image = $row["gateway_image"];
        $gateway_image = get_image_src($gateway_image);
        $processing_time = $row["processing_time"];
        $minimum_withdraw = add_currency(user_minimum_withdraw($user_id));
        $withdraw_charge = user_withdraw_charge($user_id) . '%';
        $class = has_user_filled_gateway_details($user_id, $gateway_id) ? "success" : "danger";
        $btn_text = has_user_filled_gateway_details($user_id, $gateway_id) ? "Withdraw Now" : "Add Now";
        $invalid_text = has_user_filled_gateway_details($user_id, $gateway_id) ? "" : "Withdraw details have not been added.";
        $btn_link = has_user_filled_gateway_details($user_id, $gateway_id) ? $base_url . '/wallet/withdraw-details?id=' . $gateway_id : $base_url . '/profile/';

        $output .= '<div class="col-lg-4 col-sm-6">
                            <div class="card text-center">
                                <img style="max-height:540px" src="' . $gateway_image . '" class="card-img-top" alt="image">
                                <div class="card-body p-4">
                                    <h4 class="card-title">Withdraw Via ' . $gateway_name . '</h4>
                                    <ul style="font-size: 15px;" class="list-group text-center py-2">
                                        <li class="list-group-item">Minimum Withdraw - ' . $minimum_withdraw . '</li>
                                        <li class="list-group-item">Charge - ' . $withdraw_charge . '</li>
                                        <li class="list-group-item">Processing Time - ' . $processing_time . '</li>
                                    </ul>
                                    <hr>
                                   <p class="text-danger"> ' . $invalid_text . '</p>
                                    <a href="' . $btn_link . '" class="btn btn-' . $class . '" >  ' . $btn_text . '</a>
                                </div>
                            </div>
                        </div>';
    }
    return $output;
}


function has_user_filled_gateway_details($user_id, $gateway_id)
{
    global $conn;
    global $withdraw_require_tbl;
    global $user_gateway_val_tbl;
    $query = mysqli_query($conn, "SELECT * FROM $withdraw_require_tbl WHERE gateway_id = '$gateway_id' ");
    while ($row = mysqli_fetch_array($query)) {
        $requirement_id = $row["requirement_id"];
        $sub_query = mysqli_query($conn, "SELECT * FROM $user_gateway_val_tbl WHERE user_id = '$user_id' AND requirement_id = '$requirement_id' ");
        if (mysqli_num_rows($sub_query)) {
            return true;
        }
    }
    return false;
}




function payments_gateway($user_id)
{
    global $conn;
    global $withdraw_method_tbl;
    $output = '';
    $query = mysqli_query($conn, "SELECT * FROM $withdraw_method_tbl WHERE status = 'enabled' ");
    if (!mysqli_num_rows($query)) {
        return ("<div class='empty-container' ><h1>Currently no withdraw method is available</h1></div>");
    }
    while ($row = mysqli_fetch_array($query)) {
        $gateway_id = $row["gateway_id"];
        $gateway_image = $row["gateway_image"];
        $gateway_name = $row["gateway_name"];
        $gateway_image = get_image_src($gateway_image);
        $processing_time = $row["processing_time"];
        $minimum_withdraw = add_currency(user_minimum_withdraw($user_id));
        $withdraw_charge = user_withdraw_charge($user_id) . '%';
        $btn_text = is_selected_payment_gateway_id($gateway_id, $user_id) ? "Remove" : "Add";
        $btn_color = is_selected_payment_gateway_id($gateway_id, $user_id) ? "danger" : "success";
        $output .= '<div class="col-xl-3 col-lg-4 col-md-4 col-sm-6">
                            <div class="card text-center">
                                <img style="max-height:540px" src="' . $gateway_image . '" class="card-img-top" alt="image">
                                <div class="card-body p-4">
                                    <h4 class="card-title">Withdraw Via ' . $gateway_name . ' </h4>
                                    <ul style="font-size: 15px;" class="list-group text-center py-2">
                                         <li class="list-group-item">Minimum Withdraw - ' . $minimum_withdraw . '</li>
                                        <li class="list-group-item">Charge - ' . $withdraw_charge . '</li>
                                        <li class="list-group-item">Processing Time - ' . $processing_time . '</li>
                                    </ul>
                                    <hr>
                                    <button data-id="' . $gateway_id . '" id="add_to_payment_gateway" class="btn btn-' . $btn_color . '" >' . $btn_text . '</button>
                                </div>
                            </div>
                        </div>';
    }
    return $output;
}

function user_payment_gateways_card($user_id)
{
    global $conn;
    global $withdraw_method_tbl;
    $payment_gateways = user_payment_gateways($user_id);
    $output = '';
    foreach ($payment_gateways as $gateway_id) {
        $form_id = "payment_gateway_form_$gateway_id";
        $query = mysqli_query($conn, "SELECT * FROM $withdraw_method_tbl WHERE gateway_id = '$gateway_id' ");
        if (mysqli_num_rows($query)) {
            $row = mysqli_fetch_array($query);
            $card_heading = $row["requirement_card_heading"];
            $inputs = get_withdraw_requirement_inputs($user_id, $gateway_id);
            $output .= ' <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header justify-between">
                                <h4 class="card-heading ">' . $card_heading . '</h4>
                                <span data-form="#' . $form_id . '" id="enable_form" class="c-pointer"><i class="material-icons-outlined">edit</i></span>
                            </div>
                            <div class="card-body">
                               ' . $inputs . '
                            </div>
                        </div>
                    </div>';
        }
    }
    return $output;
}

function get_withdraw_requirement_inputs($user_id, $gateway_id)
{
    global $conn;
    global $withdraw_require_tbl;
    $query = mysqli_query($conn, "SELECT * FROM $withdraw_require_tbl WHERE gateway_id = '$gateway_id' ");
    if (!mysqli_num_rows($query)) return;
    $output = '';
    while ($row = mysqli_fetch_array($query)) {
        $requirement_id = $row["requirement_id"];
        $label_text = $row["label_text"];
        $is_image_chooser = $row["is_image_chooser"];
        $input_name = "payment_gateway_input[$requirement_id]";
        $user_filled_value = witdraw_requirement_user_val($user_id, $requirement_id);
        if ($is_image_chooser == '1') {
            $image_chooser_name = "gateway_img_chooser_$requirement_id";
            $image_src = get_image_src($user_filled_value);
            $inner_div_class = is_empty($user_filled_value) ? "" : "d-none";
            $output .= '<div class="col-lg-12 col-md-12 col-sm-12 mb-3">
                                        <label>' . $label_text . '</label>
                                        <label for="' . $image_chooser_name . '" class="p-2 bg-light payment-img-container">
                                            <div class="' . $inner_div_class . ' inner-div" ><i class="material-icons-outlined">backup</i><h4 class="text-center">' . $label_text . '</h4></div>   
                                            <img style="max-height: 600px !important;" class="img-fluid" src="' . $image_src . '" alt="">
                                        </label>
                                          <input value="' . $user_filled_value . '" data-id="' . $image_chooser_name . '" required="" name="' . $input_name . '"  type="text" disabled="" class="sr-only form-control" autocomplete="off">
                                          <input value="' . $user_filled_value . '" id="' . $image_chooser_name . '" accept="image/*" type="file" disabled=""  class="sr-only form-control" autocomplete="off">
                                            <div class="invalid-feedback">Please provide a valid ' . $label_text . '</div>
                                    </div>';
        } else {
            $output .= '<div class="col-lg-12 col-md-12 col-sm-12 mb-3">
                                        <label>' . $label_text . '</label>
                                          <input required="" value="' . $user_filled_value . '" name="' . $input_name . '" type="text" disabled="" value="" class="form-control" placeholder="' . $label_text . '" autocomplete="off">
                                            <div class="invalid-feedback">Please provide a valid ' . $label_text . '</div>
                                    </div>';
        }
    }

    $form_id = "payment_gateway_form_$gateway_id";

    $output = '<form id="' . $form_id . '" data-id="' . $gateway_id . '" class="needs-validation payment_gateway_form" novalidate disabled="disabled" >
                            ' . $output . '
                            <div class="col-lg-12 form-footer row justify-right">
                                        <button type="button" class="btn btn-secondary ml-1" data-form="#' . $form_id . '" id="enable_form">Close</button>
                                        <button class="btn btn-success ml-1">Save</button>
                                    </div>
              </form>';
    return $output;
}

function witdraw_requirement_user_val($user_id, $requirement_id)
{
    global $conn;
    global $user_gateway_val_tbl;
    $query = mysqli_query($conn, "SELECT * FROM $user_gateway_val_tbl WHERE user_id = '$user_id' AND requirement_id = '$requirement_id' ");
    if (!mysqli_num_rows($query)) return;
    $row = mysqli_fetch_array($query);
    return $row["value"];
}

function is_payment_gateway_id($gateway_id)
{
    global $conn;
    global $withdraw_method_tbl;
    $query = mysqli_query($conn, "SELECT * FROM $withdraw_method_tbl WHERE gateway_id = '$gateway_id' AND status = 'enabled' ");
    if (mysqli_num_rows($query)) {
        return true;
    }
    return false;
}


function payment_gateway_keys($gateway_id)
{
    global $conn;
    global $withdraw_require_tbl;
    $output = [];
    $query = mysqli_query($conn, "SELECT * FROM $withdraw_require_tbl WHERE gateway_id  ='$gateway_id' ");
    if (!mysqli_num_rows($query)) return;
    while ($row = mysqli_fetch_array($query)) {
        $requirement_id = $row["requirement_id"];
        $output[] = $requirement_id;
    }
    return $output;
}

function delete_user_gateway_payment_data($user_id, $gateway_id)
{
    global $conn;
    global $withdraw_require_tbl;
    global $user_gateway_val_tbl;
    $query = mysqli_query($conn, "SELECT * FROM $withdraw_require_tbl WHERE gateway_id = '$gateway_id' ");
    while ($row = mysqli_fetch_array($query)) {
        $requirement_id = $row["requirement_id"];
        $sub_query = mysqli_query($conn, "DELETE FROM $user_gateway_val_tbl WHERE user_id = '$user_id' AND requirement_id = '$requirement_id' ");
    }
}

function user_gateway_detail_card($user_id, $gateway_id)
{
    global $conn;
    global $withdraw_method_tbl;
    $output = '';
    $form_id = "payment_gateway_form_$gateway_id";
    $query = mysqli_query($conn, "SELECT * FROM $withdraw_method_tbl WHERE gateway_id = '$gateway_id' ");
    if (mysqli_num_rows($query)) {
        $row = mysqli_fetch_array($query);
        $card_heading = $row["requirement_card_heading"];
        $inputs = get_withdraw_requirement_inputs($user_id, $gateway_id);
        $output .= ' <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-heading ">' . $card_heading . '</h4>
                            </div>
                            <div class="card-body">
                               ' . $inputs . '
                            </div>
                        </div>
                    </div>';
    }
    return $output;
}



function is_transaction_id($transaction_id)
{
    global $conn;
    global $transaction_tbl;
    $query = mysqli_query($conn, "SELECT * FROM $transaction_tbl WHERE transaction_id = '$transaction_id' ");
    if (mysqli_num_rows($query)) return true;
    return false;
}


function user_withdraw_log_details($user_id, $gateway_id)
{
    $log = array();
    global $conn;
    global $withdraw_require_tbl;
    $query = mysqli_query($conn, "SELECT * FROM $withdraw_require_tbl WHERE gateway_id = '$gateway_id' ");
    if (!mysqli_num_rows($query)) return;
    $output = '';
    while ($row = mysqli_fetch_array($query)) {
        $requirement_id = $row["requirement_id"];
        $label_text = $row["label_text"];
        $is_image_chooser = $row["is_image_chooser"];
        $user_filled_value = witdraw_requirement_user_val($user_id, $requirement_id);
        $log[] = array(
            "label_text" => $label_text,
            "user_filled_value" => $user_filled_value,
            "is_image_chooser" => $is_image_chooser
        );
    }
    $log = serialize($log);
    return $log;
}



function deposit_gateways()
{
    global $conn;
    global $base_url;
    global $manual_deposit_method;
    $query = mysqli_query($conn, "SELECT * FROM $manual_deposit_method WHERE status = 'active' ");
    if (!mysqli_num_rows($query)) return;
    $output = '';
    while ($row = mysqli_fetch_array($query)) {
        $gateway_id = $row["gateway_id"];
        $gateway_name = $row["gateway_name"];
        $gateway_image = get_image_src($row["gateway_image"]);
        $output .= '<div class="col-xl-3 col-lg-4 col-md-4 col-sm-6">
                            <div class="card text-center">
                                <img src="' . $gateway_image . '" class="card-img-top" alt="image">
                                <div class="card-body">
                                    <h5 class="card-title">' . $gateway_name . '</h5>
                                    <hr>
                                    <a href="' . $base_url . '/wallet/deposit-gateway-preview?id=' . $gateway_id . '" class="btn btn-primary deposit"> Deposit Now</a>
                                </div>
                            </div>
                        </div>';
    }
    return $output;
}

function is_admin_exist()
{
    global $conn;
    global $users_tbl;
    $query = mysqli_query($conn, "SELECT * FROM $users_tbl WHERE user_role = '2' ");
    if (mysqli_num_rows($query)) return true;
    return false;
}


function binary_commission_tbl($user_id)
{
    check_user_id($user_id);
    global $conn;
    global $pair_income_tbl;
    $output = '';
    $count = 0;
    $query = mysqli_query($conn, "SELECT * FROM $pair_income_tbl WHERE user_id = '$user_id' ORDER BY date DESC ");
    while ($row = mysqli_fetch_array($query)) {
        $count++;
        $date = date_time($row["date"]);
        $pair_bonus = add_currency($row["pair_income"]);
        $level = $row["level"];
        $status = $row["status"];

        if ($status == "credit") {
            $status = '<label class="badge alert-success">' . $status . '</label>';
        } elseif ($status == "pending") {
            $status = '<label class="badge alert-info">' . $status . '</label>';
        } elseif ($status == "capping") {
            $status = '<label class="badge alert-warning">' . $status . '</label>';
        }

        $output .= '<tr>
        <td>' . $count . '</td>
        <td>' . $pair_bonus . '</td>
        <td>' . $date . '</td>
        <td>' . $level . '</td>
        <td>' . $status . '</td>
        </tr>';
    }
    return $output;
}


function plans_card($user_id)
{
    global $conn;
    global $packages_tbl;
    $count = 0;
    $output = '';
    $query = mysqli_query($conn, "SELECT * FROM $packages_tbl WHERE status = 'enable' ");
    $color = ["basic", "bronze", "silver", "gold", "platinum"];
    while ($row = mysqli_fetch_array($query)) {
        $package_id = $row["package_id"];
        $package = $row["package"];
        $price = $row["price"];

        $pair_income = add_currency($row["pair_income"]);
        $maximum_pair_income = add_currency($row["maximum_pair_income"]);
        $withdraw_charge = $row["withdraw_charge"];
        $validity = $row["validity"];
        $minimum_withdraw = add_currency($row["minimum_withdraw"]);
        $color_choice = $color[$count];
        $user_package_id = user_package_id($user_id);
        $ribbon = ($user_package_id == 1 & $count == 1) ? '<div class="ribbon"><span>Recommended</span></div>' : "";
        $btn_text = ($user_package_id == $package_id) ? "Activated" : "Purchase";

        if ($user_package_id == $package_id) {
            $ribbon = '<div class="active ribbon"><span>Activated</span></div>';
        }
        $validity = $validity == '0' ? "Unlimited" : $validity;

        $count++;
        $output .= '
         <div class="table ' . $color_choice . '">
           ' . $ribbon . '
                            <div class="price-section">
                                <div class="price-area">
                                    <div class="inner-area">
                                        <span class="text">$</span>
                                        <span class="price">' . $price . '</span>
                                    </div>
                                </div>
                            </div>
                            <div class="package-name"><span>' . $package . '</span></div>
                            <ul class="features">
                                <li>
                                    <span class="list-name">Pair Income</span>
                                    <span class="icon check">' . $pair_income . '</span>
                                </li>
                                <li>
                                    <span class="list-name">Maximum Pair Income <small>(daily)</small> </span>
                                    <span class="icon check">' . $maximum_pair_income . '</span>
                                </li>
                                <li>
                                    <span class="list-name">Minimum Withdrawal</span>
                                    <span class="icon check">' . $minimum_withdraw . '</span>
                                </li>
                                <li>
                                    <span class="list-name">Withdraw Charge</span>
                                    <span class="icon check">' . $withdraw_charge . '%</span>
                                </li>
                                <li>
                                    <span class="list-name">Validity</span>
                                    <span class="icon check">' . $validity . ' Days</span>
                                </li>
                            </ul>
                            <div class="btn"><button id="purchase_package" data-id="' . $package_id . '"  >' . $btn_text . '</button></div>
                        </div>
        ';
    }
    return $output;
}



function advertisements_lists($user_id)
{

    global $conn;
    global $admin_base_url;
    global $ads_tbl;
    $count = 0;
    $output = '';
    $query = mysqli_query($conn, "SELECT * FROM $ads_tbl");
    while ($row = mysqli_fetch_array($query)) {
        $adver_id = $row["adver_id"];
        $title = $row["title"];
        $count++;
        $link = $row["link"];
        $price = $row["price"];
        $quantity = $row["quantity"];
        $output .= '
        <tr>
            <td>' . $count . '</td>
            <td>' . $title . '</td>
            <td>' . $price . '</td>
            <td><a class="link">Click Here</a></td>
        </tr>
        ';
    }
    return $output;
}

function add_currency($text)
{
    global $web_currency;
    global $web_currency_position;
    if ($web_currency_position == "suffix") {
        return $text . ' ' . $web_currency;
    }
    return $web_currency . $text;
}

function total_team_tbl($user_id)
{
    $team_users = users_under_user_tree($user_id);
    $output = '';
    $count = '';
    foreach ($team_users as $user_id) {
        $count++;
        $user_name = user_name($user_id);
        $package = user_package($user_id);
        $date_registerred = user_registration_date($user_id);
        $output .= '
            <tr>
                <td>' . $count . '</td>
                <td>' . $user_id . '</td>
                <td>' . $user_name . '</td>
                <td>' . $package . '</td>
                <td>' . $date_registerred . '</td>
            </tr>
        ';
    }
    return $output;
}

function get_referral_commission($user_id)
{
    $package_id = user_package_id($user_id);
    $package_data = package_data($package_id);
    $referral_income = $package_data["referral_income"];
    return $referral_income;
}

function get_pair_commission($user_id)
{
    $package_id = user_package_id($user_id);
    $package_data = package_data($package_id);
    $pair_income = $package_data["pair_income"];
    return $pair_income;
}

function maximum_pair_income($user_id)
{
    $package_id = user_package_id($user_id);
    $package_data = package_data($package_id);
    $maximum_pair_income = $package_data["maximum_pair_income"];
    return $maximum_pair_income;
}



function basic_package_id()
{
    global $conn;
    global $packages_tbl;
    $query = mysqli_query($conn, "SELECT * FROM $packages_tbl WHERE price = '0' ");
    if (!mysqli_num_rows($query)) {
        $query = mysqli_query($conn, "INSERT INTO $packages_tbl (`package`, `price`, `pair_income`,`maximum_pair_income`, `minimum_withdraw`, `withdraw_charge`,`validity`, `status`) VALUES
     ('Basic', '0', '0','0', '300', '10','0','enable')");
        if (!$query) {
            return_exit("Error in basic package");
        }
        $package_selected = mysqli_insert_id($conn);
    } else {
        $row = mysqli_fetch_array($query);
        $package_selected = $row["package_id"];
    }

    return $package_selected;
}



function convert_rgb_to_hex_color($rgb)
{
    $rgb = explode(",", $rgb);
    $color = sprintf("#%02x%02x%02x", $rgb[0], $rgb[1], $rgb[2]);
    return $color;
}

function convert_hex_to_rgb_color($hex)
{
    list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");
    return "$r,$g,$b";
}

function user_package_purchased_count($user_id)
{
    global $conn;
    global $package_history_tbl;
    $query = mysqli_query($conn, "SELECT COUNT(*) FROM $package_history_tbl WHERE user_id = '$user_id' AND ( status = 'active' OR status = 'pending' )");
    return mysqli_fetch_array($query)[0];
}

function package_history_tbl($user_id)
{
    global $conn;
    global $package_history_tbl;
    $query = mysqli_query($conn, "SELECT * FROM $package_history_tbl WHERE user_id = '$user_id' ORDER BY date DESC ");
    $count = 0;
    $output = '';
    while ($row = mysqli_fetch_array($query)) {
        $count++;
        $transaction_id = $row["transaction_id"];
        $amount = add_currency($row["amount"]);
        $date = $row["date"];
        $package_id = $row["package_id"];
        $package_data = package_data($package_id);
        $package = $package_data["package"];
        $status = $row["status"];
        $activated_date = $row["activated_date"];
        $expired_date = $row["expired_date"];
        if ($expired_date == '0') $expired_date =  "Unlimited";
        else $expired_date = date_time($expired_date);

        if ($status == "pending") $status = '<label class="badge alert-warning">' . $status . '</label>';
        if ($status == "active") $status = '<label class="badge alert-success">' . $status . '</label>';
        if ($status == "expired") $status = '<label class="badge alert-danger">' . $status . '</label>';
        $date = date_time($date);
        $activated_date = date_time($activated_date);
        $output .= '<tr>
            <td>' . $count . '</td>
            <td>' . $transaction_id . '</td>
            <td>' . $amount . '</td>
            <td>' . $package . '</td>
            <td>' . $date . '</td>
            <td>' . $activated_date . '</td>
            <td>' . $expired_date . '</td>
            <td>' . $status . '</td>
        </tr>';
    }
    return $output;
}

//Logo

function web_logo_id()
{
    $row = setting_data();
    $logo_id = $row["logo_id"] ?? 0;
    return $logo_id;
}
function web_logo_icon_id()
{
    $row = setting_data();
    $logo_id = $row["logo_icon_id"] ?? 0;
    return $logo_id;
}
function web_favicon_id()
{
    $row = setting_data();
    $logo_id = $row["favicon_id"] ?? 0;
    return $logo_id;
}


function web_logo()
{
    global $images_base_url;
    $logo_id = web_logo_id();
    if ($logo_id == 0) return $images_base_url . '/web/logo.png';
    $image_src = get_image_src($logo_id);
    return $image_src;
}
function web_logo_icon()
{
    global $images_base_url;
    $logo_id = web_logo_icon_id();
    if ($logo_id == 0)  return $images_base_url . '/web/logo-icon.png';
    $image_src = get_image_src($logo_id);
    return $image_src;
}
function web_favicon()
{
    global $images_base_url;
    $logo_id = web_favicon_id();
    if ($logo_id == 0) return $images_base_url . '/web/favicon.png';
    $image_src = get_image_src($logo_id);
    return $image_src;
}


function web_primary_color()
{
    global $web_real_primary_color;
    $data = setting_data();
    return $data["web_primary_color"] ?? $web_real_primary_color;
}

include "admin_functions.php";
