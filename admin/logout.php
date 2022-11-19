<?php
include("../db.php");
if (!is_admin_loggedin()) {
    locate_to($admin_base_url);
}
$session_id = sanitize_text($_COOKIE[$web_name . '_asession_id']);
$query = mysqli_query($conn, "DELETE FROM $login_session_tbl WHERE session_id = '$session_id' ");
if ($query) {
    setcookie($web_name . '_asession_id', '', time() - (86400 * 30), '/');
    locate_to($admin_base_url);
} else {
    return_exit("Error in logout");
}
