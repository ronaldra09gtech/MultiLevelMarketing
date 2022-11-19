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
                    VALUES ('$ticket_id','$loggedin_admin_id','$message','$current_date','$images') ");
        if (!$query) {
            return_exit("Error in adding reply");
        }

        $query = mysqli_query($conn,"UPDATE $tickets_tbl SET last_reply_date = '$current_date', status = '2' WHERE ticket_id = '$ticket_id' ");

        $query = mysqli_query($conn,"SELECT * FROM $tickets_tbl WHERE ticket_id = '$ticket_id' ");
        $row = mysqli_fetch_array($query);
        $activated_on = $row["activated_on"];
        if(is_empty($activated_on)){
            $query = mysqli_query($conn, "UPDATE $tickets_tbl SET activated_on = '$current_date' WHERE ticket_id = '$ticket_id' ");
        }

        $output = new stdClass;
        $output->message = "Reply added successfully";
        $output->url = $admin_base_url . '/support/view-ticket?ticket=' . $ticket_id;
        echo json_encode($output);
        break;

    case "close_ticket":

        if (!is_isset("ticket_id")) {
            return_exit("Invalid request");
        }

        $ticket_id = sanitize_text($_POST["ticket_id"]);
        $query = mysqli_query($conn, "SELECT * FROM $tickets_tbl WHERE ticket_id = '$ticket_id' ");
        if (!mysqli_num_rows($query)) {
            return_exit("Ticket doesn't exist");
        }

        $row = mysqli_fetch_array($query);
        $status = $row["status"];
        if ($status == '3') {
            return_exit("Ticket is already closed");
        }

        $query = mysqli_query($conn, "UPDATE $tickets_tbl SET status = '3', closed_on = '$current_date' WHERE ticket_id = '$ticket_id' ");
        if (!$query) {
            return_exit("Error in closing ticket");
        }

        $output = new stdClass;
        $output->message = "Ticket closed successfully";
        $output->url = $admin_base_url.'/support/';
        echo json_encode($output);

        break;
}
