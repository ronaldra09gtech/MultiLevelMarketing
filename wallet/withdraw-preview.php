<?php

$base = '../';
$active_page = "withdraw-history";
include $base . "db.php";
check_user_login();
$user_id = $loggedin_user_id;


if (!isset($_GET["id"])) {
    http_response_404();
}
$transaction_id = $_GET["id"];
$query = mysqli_query($conn, "SELECT * FROM $withdraw_tbl WHERE transaction_id = '$transaction_id' ");
if (!mysqli_num_rows($query)) {
    http_response_404();
}
$row = mysqli_fetch_array($query);
$payment_requested = add_currency($row["amount"]);
$withdraw_status = $row["status"];
$requested_date = $row["requested_date"];
$success_date = $row["success_date"];
$delay_time = ($current_date - $requested_date) / 86400;
if ($withdraw_status !== "pending") {
    $delay_time = ($success_date - $requested_date) / 86400;
}
$delay_time = $delay_time < 86400 ? 0 : $delay_time;
$delay_time = $delay_time . ' Days';
$total_charge = add_currency($row["total_charge"]);
$payable_amount = add_currency($row["net_amount"]);
$gateway_id = $row["gateway_id"];
$user_account_details = $row["user_account_details"];
$withdraw_status = $row["status"];
$message = unsanitize_text($row["message"]);


$query = mysqli_query($conn, "SELECT * FROM $withdraw_method_tbl WHERE gateway_id = '$gateway_id' ");
if (!mysqli_num_rows($query)) {
    http_response_404();
}
$row = mysqli_fetch_array($query);
$requirement_card_heading = $row["requirement_card_heading"];

if ($withdraw_status == "pending") {
    $withdraw_label = '<label class="ml-4 badge alert-warning">pending</label>';
}
if ($withdraw_status == "approved") {
    $withdraw_label = '<label class="ml-4 badge alert-success">approved</label>';
}
if ($withdraw_status == "rejected") {
    $withdraw_label = '<label class="ml-4 badge alert-danger">rejected</label>';
}


$user_details_inputs = '';
$user_account_details = unserialize($user_account_details);
foreach ($user_account_details as $user_detail) {
    $label_text = $user_detail["label_text"];
    $user_filled_value = $user_detail["user_filled_value"];
    $is_image_chooser = $user_detail["is_image_chooser"];
    if ($is_image_chooser == '1') {
        $image_src = get_image_src($user_filled_value);
        $user_details_inputs .= '<div class="col-lg-12 col-md-12 col-sm-12 mb-3">
                                        <label>' . $label_text . '</label>
                                        <label  class="p-2 bg-light payment-img-container">
                                            <img style="max-height: 600px !important;" class="img-fluid" src="' . $image_src . '" alt="">
                                        </label>
                                    </div>';
    } else {
        $user_details_inputs .= '<div class="col-md-12 col-lg-12 mb-3">
                                                    <label>' . $label_text . '</label>
                                                    <input value="' . $user_filled_value . '" readonly="" type="text" class="form-control" placeholder="' . $label_text . '" required="" autocomplete="off">
                                                </div>';
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <?php echo web_metadata(); ?>
    <title>Withdraw Preview - <?php echo $web_name; ?></title>
    <?php include $base . "assets/css/css-files.php"; ?>
</head>

<body>
    <!-- tap on top starts-->
    <div class="tap-top"><i data-feather="chevrons-up"></i></div>
    <!-- tap on tap ends-->
    <!-- page-wrapper Start-->
    <div class="page-wrapper compact-wrapper" id="pageWrapper">
        <!-- Page Header Start-->
        <?php include '../assets/nav/user/header.php'; ?>
        <!-- Page Header Ends     -->
        <!-- Page Body Start-->
        <div class="page-body-wrapper">
            <!-- Page Sidebar Start-->
            <?php include $base . '/assets/nav/user/sidebar.php'; ?>
            <!-- Page Sidebar Ends-->
            <div class="page-body">
                <div class="container-fluid">
                    <div class="page-title">
                        <div class="row">
                            <div class="col-6">
                                <h3>Withdraw Preview</h3>
                            </div>
                            <div class="col-6">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?php echo $base_url; ?>"><i class="material-icons-outlined">home</i> </a></li>
                                    <li class="breadcrumb-item active">Withdraw Preview</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Container-fluid starts-->
                <div class="container-fluid">

                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="align-center">
                                    <h3>Withdraw Details</h3> <?php echo $withdraw_label; ?>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-row">
                                            <div class="col-md-12 col-lg-12 mb-3">
                                                <label>Currency</label>
                                                <input value="<?php echo $web_currency; ?>" readonly="" type="text" class="form-control" placeholder="Currency" required="" autocomplete="off">
                                            </div>
                                            <div class="col-md-12 col-lg-12 mb-3">
                                                <label>Delay</label>
                                                <input value="<?php echo $delay_time; ?>" readonly="" type="text" class="form-control" placeholder="Delay" required="" autocomplete="off">
                                            </div>
                                            <div class="col-md-12 col-lg-12 mb-3">
                                                <label>Payment Requested</label>
                                                <input value="<?php echo $payment_requested; ?>" readonly="" type="text" class="form-control" placeholder="Payment Requested" required="" autocomplete="off">
                                            </div>
                                            <div class="col-md-12 col-lg-12 mb-3">
                                                <label>Charge</label>
                                                <input value="<?php echo $total_charge; ?>" readonly="" type="text" class="form-control" placeholder="Charge" required="" autocomplete="off">
                                            </div>
                                            <div class="col-md-12 col-lg-12 mb-3">
                                                <label>Payable Amount</label>
                                                <input value="<?php echo $payable_amount; ?>" readonly="" type="text" class="form-control" placeholder="Payable Amount" required="" autocomplete="off">
                                            </div>
                                            <div class="col-md-12 col-lg-12 mb-3">
                                                <label><?php if ($withdraw_status == "approved") {
                                                            echo "Message";
                                                        } else {
                                                            echo "Reason";
                                                        } ?></label>
                                                <textarea readonly="" type="text" class="form-control" placeholder="Message" required="" autocomplete="off"><?php echo $message; ?></textarea>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4 class="card-heading "><?php echo $requirement_card_heading; ?></h4>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-row">
                                                    <?php echo $user_details_inputs; ?>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
                <!-- Container-fluid Ends-->
            </div>


            <!-- footer start-->
            <?php echo web_footer("user"); ?>
            <!--  -->

        </div>
    </div>




</body>

</html>