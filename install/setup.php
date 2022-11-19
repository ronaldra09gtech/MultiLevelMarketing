<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    exit();
}




if (!isset($_POST["action"])) {
    return_exit("Invalid request");
}

$action = $_POST["action"];

if ($action !== "admin_data") {
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

    function return_exit($text)
    {
        echo $text;
        exit();
    }
}

switch ($action) {




    case "licence_code":
        if (!is_isset("license_code")) {
            return_exit("Invalid request");
        }
        $license_code = $_POST["license_code"];
        if ($license_code !== "Jamsrworld2") {
            return_exit("Invalid License Code");
        }
        $output = new stdClass;
        $output->message = "Licence Code Matched";
        echo json_encode($output);
        break;

    case "database":

        error_reporting(0);
        if (!is_isset("db_host", "db_name", "db_password", "db_user", "base_url")) {
            return_exit("Invalid request");
        }

        $db_host = $_POST["db_host"];
        $db_password = $_POST["db_password"];
        $db_user = $_POST["db_user"];
        $db_name = $_POST["db_name"];
        $base_url = $_POST["base_url"];



        if (!mysqli_connect($db_host, $db_user, $db_password)) {
            return_exit("Provide a valid database credentials");
        }

        $conn = mysqli_connect($db_host, $db_user, $db_password);

        $sql = "CREATE DATABASE $db_name";
        if (!mysqli_query($conn, $sql)) {
            return_exit(mysqli_error($conn));
        }

        $conn = mysqli_connect($db_host, $db_user, $db_password, $db_name);
        $tempLine = '';
        // Read in the full file
        $lines = file("sql.sql");
        // Loop through each line
        foreach ($lines as $line) {

            // Skip it if it's a comment
            if (substr($line, 0, 2) == '--' || $line == '')
                continue;

            // Add this line to the current segment
            $tempLine .= $line;
            // If its semicolon at the end, so that is the end of one query
            if (substr(trim($line), -1, 1) == ';') {
                // Perform the query
                $query = mysqli_query($conn, $tempLine);
                if (!$query) {
                    mysqli_query($conn, "DROP DATABASE databasename");
                    return_exit("Error in " . $tempLine . ":" . mysqli_error($conn));
                }
                // Reset temp variable to empty
                $tempLine = '';
            }
        }

        $txt_file = file_get_contents("../db.php");
        $rows = explode("\n", $txt_file);
        foreach ($rows as $row => &$data) {
            if (strstr($data, 'db_host_name') !== FALSE) {
                $rows[$row + 1] = '$mysqlHostName = "' . $db_host . '";';
            }
            if (strstr($data, 'db_user_name') !== FALSE) {
                $rows[$row + 1] = '$mysqlUserName = "' . $db_user . '";';
            }
            if (strstr($data, 'db_password') !== FALSE) {
                $rows[$row + 1] = '$mysqlPassword = "' . $db_password . '";';
            }
            if (strstr($data, 'db_name') !== FALSE) {
                $rows[$row + 1] = '$mysqlDbName = "' . $db_name . '";';
            }

            if (strstr($data, 'web_base_url') !== FALSE) {
                $rows[$row + 1] = '$base_url = "' . $base_url . '";';
            }
            $data = $data . "\n";
        }

        file_put_contents('../db.php', $rows);
        $output = new stdClass;
        $output->message = "Database created successfully";
        echo json_encode($output);

        break;

    case "admin_data":

        include("../db.php");

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



        $package_selected = basic_package_id();
        $query = mysqli_query($conn, "INSERT INTO $users_tbl (`user_id`, `user_name`,`user_email`, `user_password`, `full_name`,`user_registration_date`, `status`,`user_role`)
                                                    VALUES ('$admin_id','$user_name','$email','$password','$user_name','$current_date','active','2') ");
        if (!$query) {
            return_exit("Error in creating Admin account" . mysqli_error($conn));
        }

        $basic_package_id = basic_package_id();
        $package_price = package_data($basic_package_id)["price"];
        $transaction_id = new_transaction_id();
        $package_name = package_data($basic_package_id)["package"];

        $query = mysqli_query($conn, "INSERT INTO $package_history_tbl (`user_id`, `package_id`, `transaction_id`, `amount`, `date`, `activated_date`,`expired_date`,`status`) VALUES 
                       ('$admin_id','$basic_package_id','$transaction_id','$package_price','$current_date','$current_date','0','active')");
        if (!$query)  return_exit("Error in creating Admin account" . mysqli_error($conn));


        $query = mysqli_query($conn, "INSERT INTO $transaction_tbl (`transaction_id`, `user_id`, `amount`, `transaction_charge`, `net_amount`, `description`, `category`, `date`, `status`)
                    VALUES ('$transaction_id','$admin_id','$package_price','0','$package_price','$package_name package activated','purchased package','$current_date','debit') ");
        if (!$query) return_exit("Error in creating Admin account" . mysqli_error($conn));

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
            return_exit("Error in creating Admin account" . mysqli_error($conn));
        }

        $output = new stdClass;
        $output->message = "Account created successfully";
        $output->url = $base_url;
        echo json_encode($output);

        break;
}
