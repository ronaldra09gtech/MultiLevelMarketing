<?php

include("../../db.php");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_404();
}

check_web_validation();

if (!isset($_POST['action'])) {
    http_response_404();
}

$action = $_POST['action'];
if (is_empty($action)) {
    http_response_404();
}


sleep(1);

switch ($action) {



    // users ---

    case 'user_account':
        include "user/user_account.php";
        break;
        
    case 'profile_action':
        include "user/profile_action.php";
        break;

    case "support_action":
        include "user/support_action.php";
        break;

    case "balance_action":
        include "user/balance_action.php";
        break;
    
        // Admin ---

    case "_admin_account":
        include "admin/_admin_account.php";
        break;

    case "_support_action":
        include "admin/_support_action.php";
        break;

    case "_setting":
        include "admin/_setting.php";
        break;

    case "_user_action":
        include "admin/_user_action.php";
        break;

    case "_withdraw_action":
        include "admin/_withdraw_action.php";
        break;

    case "_ads_action":
        include "admin/_ads_action.php";
        break;

    default:
        http_response_404();
        break;
}
