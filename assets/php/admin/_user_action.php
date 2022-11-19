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
    case "change_user_status":
        if (!is_isset("user_id", "status")) {
            return_exit("Invalid request");
        }
        $user_id = sanitize_text($_POST["user_id"]);
        $status = sanitize_text($_POST["status"]);
        if ($status == "unblock" || $status == "block") {
        } else {
            return_exit("Invalid request");
        }

        if (!is_user_id($user_id)) {
            return_exit("User don't exists");
        }

        $user_status = user_status($user_id);

        $output = new stdClass;

        if ($status == "block") {
            if ($user_status == "blocked") {
                return_exit("User is already blocked");
            }
            $query = mysqli_query($conn, "UPDATE $users_tbl SET status = 'blocked' WHERE user_id = '$user_id' ");
            if (!$query) {
                return_exit("Error in blocking user");
            }
            $output->message = "User blocked successfully";
        }

        if ($status == "unblock") {
            if ($user_status == "active") {
                return_exit("User is already unblocked");
            }
            $query = mysqli_query($conn, "UPDATE $users_tbl SET status = 'active' WHERE user_id = '$user_id' ");
            if (!$query) {
                return_exit("Error in unblocking user");
            }
            $output->message = "User unblocked successfully";
        }
        echo json_encode($output);
        break;
}
