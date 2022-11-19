<?php

error_reporting(1);
ini_set("display_errors", 1);

define('ROOT', dirname(__FILE__));
define('BASE', basename(dirname(__FILE__)));


function go_to_installer()
{
    header("location:/" . BASE . "/install/");
    exit();
}


//* Don't Remove Any Comments Before Setup

// db_host_name
$mysqlHostName = "localhost";
// db_user_name
$mysqlUserName = "root";
// db_password
$mysqlPassword = "";
// db_name
$mysqlDbName = "mlm_with_capping";

$conn = mysqli_connect($mysqlHostName, $mysqlUserName, $mysqlPassword, $mysqlDbName);
if (!$conn && !$installer_page) {
    go_to_installer();
}

if ($conn) {
    $has_db_created = "true";
}

// web_base_url
$base_url = "http://localhost/pilamoko";



$tables = [
    "users",
    "images",
    "users_tree",
    "otps",
    "login_session",
    "tickets",
    "tickets_messages",
    "user_withdraws",
    "user_deposits",
    "withdraw_methods",
    "withdraw_requirements",
    "user_gateway_val",
    "manual_deposit_method",
    "user_balance",
    "packages",
    "user_referrs",
    "user_transactions",
    "paytm_payments",
    "setting",
    "user_capping"
];

$users_tbl = 'users';
$users_tree_tbl = 'users_tree';
$images_tbl = 'images';
$otp_tbl = 'otps';
$login_session_tbl = 'login_session';
$tickets_tbl = 'tickets';
$tickets_msg_tbl = 'tickets_messages';
$setting_tbl = "setting";
$paytm_tbl = "paytm_payments";
$balance_tbl = 'user_balance';
$transaction_tbl = "user_transactions";
$withdraw_tbl = "user_withdraws";
$deposit_tbl = "user_deposits";
$withdraw_method_tbl = 'withdraw_methods';
$withdraw_require_tbl = 'withdraw_requirements';
$user_gateway_val_tbl = 'user_gateway_val';
$manual_deposit_method = 'manual_deposit_method';
$packages_tbl = 'packages';
$capping_tbl = 'user_capping';
$package_history_tbl = "package_history";
$pair_income_tbl = "pair_income";

$current_date = strtotime(date("d-m-Y H:i:s")) + 11*24*3600;
date_default_timezone_set("Asia/Kolkata");

$web_name = "TLPneurship";
$support_email = "platinumr1itservices@gmail.com";
$web_prefix = "TLP";
$web_real_primary_color = "0,158,246";

$images_base_url = $base_url . '/assets/images';
$web_host = parse_url($base_url, PHP_URL_HOST);

function http_response_404()
{
    global $base_url;
    http_response_code(404);
    $file = file_get_contents($base_url . '/404.php');
    echo $file;
    exit();
}

if (!$installer_page) {
    include "assets/php/functions.php";
}
