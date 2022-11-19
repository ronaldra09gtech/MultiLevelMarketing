<?php

include("../db.php");

if (!isset($_COOKIE['registered_user_id'])) {
    header("location:$base_url");
    exit();
}

$user_id = $_COOKIE['registered_user_id'];
if (!is_user_id($user_id)) {
    echo "Registration failed";
    exit();
}
$user_name = user_name($user_id);
$name = user_full_name($user_id);
$email = user_email($user_id);
$date = user_registration_date($user_id);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <?php echo web_metadata(); ?>
    <link rel="icon" href="<?php echo $images_base_url; ?>/web/favicon.png" type="image/x-icon">
    <link rel="shortcut icon" href="<?php echo $images_base_url; ?>/web/favicon.png" type="image/x-icon">
    <title>New Registration - <?php echo $web_name; ?></title>
    <?php include  "../assets/css/css-files.php"; ?>
</head>

<body>

    <div id="main-wrapper" data-theme="light" data-layout="vertical" data-navbarbg="skin1" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed" data-boxed-layout="full" class="">
        <div id="app" class="ml-0 page-wrapper" style="display: block;">
            <div class="container-fluid justify-align-center vh-100">

                <div style="max-width:400px;" class="card">
                    <div class="card-body payment-card">
                        <i class="text-success material-icons-outlined">check_circle</i>
                        <h4 class="my-2 message text-danger">Registration Successfull</h4>
                        <div><b>User Id: </b>
                            <p><?php echo $user_id; ?></p>
                        </div>
                        <div><b>User Name: </b>
                            <p><?php echo $user_name; ?></p>
                        </div>
                        <div><b>Name: </b>
                            <p><?php echo $name; ?></p>
                        </div>
                        <div><b>Email: </b>
                            <p><?php echo $email; ?></p>
                        </div>
                        <div><b>Date: </b>
                            <p><?php echo $date; ?>3</p>
                        </div>
                        <p>Thanks for becoming a member of binary mlm</p>
                        <a href="<?php echo $base_url; ?>">
                            <button class="btn btn-block btn-info">Home</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>