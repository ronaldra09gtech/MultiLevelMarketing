<?php

$base = '../../';
$active_page = "pending-withdraws";
include $base . "db.php";
check_admin_login();

if (!isset($_GET["id"])) {
    http_response_404();
}
$transaction_id = $_GET["id"];
$query = mysqli_query($conn, "SELECT * FROM $withdraw_tbl WHERE transaction_id = '$transaction_id' ");
if (!mysqli_num_rows($query)) {
    http_response_404();
}
$row = mysqli_fetch_array($query);
$user_id = $row["user_id"];
$user_name = user_name($user_id);
$registration_date = user_registration_date($user_id);
$referred_by = user_referred_by($user_id);
$user_status = user_status_label($user_id);
$user_image_src = user_image($user_id);

$payment_requested = add_currency($row["amount"]);
$requested_date = $row["requested_date"];
$delay_time = ($current_date - $requested_date) / 86400;
$delay_time = $delay_time < 86400 ? 0 : $delay_time;
$delay_time = $delay_time . ' Days';
$total_charge = add_currency($row["total_charge"]);
$payable_amount = add_currency($row["net_amount"]);
$gateway_id = $row["gateway_id"];
$withdraw_status = $row["status"];
$user_account_details = $row["user_account_details"];
$withdraw_status = $row["status"];
$message = unsanitize_text($row["message"]);


if ($withdraw_status == "pending") {
    $withdraw_label = '<label class="ml-4 badge alert-warning">pending</label>';
}
if ($withdraw_status == "approved") {
    $withdraw_label = '<label class="ml-4 badge alert-success">approved</label>';
}
if ($withdraw_status == "rejected") {
    $withdraw_label = '<label class="ml-4 badge alert-danger">rejected</label>';
}


$query = mysqli_query($conn, "SELECT * FROM $withdraw_method_tbl WHERE gateway_id = '$gateway_id' ");
if (!mysqli_num_rows($query)) {
    http_response_404();
}
$row = mysqli_fetch_array($query);
$requirement_card_heading = $row["requirement_card_heading"];


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
  
    <title>Edit Withdraw Method - <?php echo $web_name; ?></title>
    <link rel="stylesheet" href="<?php echo $base_url; ?>/assets/css/datatables.css">
    <?php include $base . "assets/css/css-files.php"; ?>
</head>

<body>
    <!-- tap on top starts-->
    <div class="tap-top"><i data-feather="chevrons-up"></i></div>
    <!-- tap on tap ends-->
    <!-- page-wrapper Start-->
    <div class="page-wrapper compact-wrapper" id="pageWrapper">
        <!-- Page Header Start-->
        <?php include $base . "assets/nav/admin/header.php"; ?>
        <!-- Page Header Ends     -->
        <!-- Page Body Start-->
        <div class="page-body-wrapper">
            <!-- Page Sidebar Start-->
            <?php include $base . '/assets/nav/admin/sidebar.php'; ?>
            <!-- Page Sidebar Ends-->
            <div class="page-body">
                <div class="container-fluid">
                    <div class="page-title">
                        <div class="row">
                            <div class="col-6">
                                <h3>Withdraw</h3>
                            </div>
                            <div class="col-6">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?php echo $admin_base_url; ?>"><i class="material-icons-outlined">home</i> </a></li>
                                    <li class="breadcrumb-item active">Withdraw</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Container-fluid starts-->
                <div class="container-fluid">

                    <div class="row">
                        <div class="col-lg-3">
                            <div class="card myprofile-card">
                                <div class="card-header">
                                    <h3 class="card-title">Profile</h3>
                                </div>
                                <div class="card-body">
                                    <div class="profile_img_container">
                                        <div class="img_center">
                                            <img class="img-fluid" src="<?php echo $user_image_src; ?>" alt="<?php echo $user_name; ?>">
                                        </div>
                                    </div>
                                    <div class="profile-detail-container">
                                        <hr>
                                        <div class="align-justify-between">
                                            <span> User Id</span>
                                            <span><?php echo $user_id; ?></span>
                                        </div>
                                        <div class="align-justify-between">
                                            <span> User Name</span>
                                            <span><?php echo $user_name; ?></span>
                                        </div>
                                        <div class="align-justify-between">
                                            <span> Referred By</span>
                                            <span><?php echo $referred_by; ?></span>
                                        </div>
                                        <div class="align-justify-between">
                                            <span> Registration Date</span>
                                            <span><?php echo $registration_date; ?></span>
                                        </div>
                                        <div class="align-justify-between">
                                            <span> Status</span>
                                            <span><?php echo $user_status; ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-9">
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
                                    <?php
                                    if ($withdraw_status == "pending") { ?>
                                        <div class="justify-right">
                                            <button data-toggle="modal" data-target="#withdrawRejectionModal" class="btn btn-danger mr-3">Reject</button>
                                            <button data-toggle="modal" data-target="#withdrawConfirmationModal" class="btn btn-success">Paid</button>
                                        </div>
                                    <?php }
                                    ?>
                                </div>
                            </div>

                        </div>
                    </div>


                    <div class="card">
                        <div class="card-header justify-between">
                            <h5>
                                Withdraw Summary
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="display" id="data_tbl">
                                    <thead>
                                        <tr>
                                            <th>Serial No.</th>
                                            <th>Transaction Id</th>
                                            <th>Amount</th>
                                            <th>Charge</th>
                                            <th>Payable Amount</th>
                                            <th>Payment Method</th>
                                            <th>Requested Date</th>
                                            <th>Success Date</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php echo _user_withdraw_tbl($user_id); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>

        <!-- Modal Start -->
        <div class="modal fade" id="withdrawConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="paytmModal" style="display: none;" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Payment Confirmation Modal</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <form id="_approve_withdraw" novalidate="" class="needs-validation">
                        <div style="max-width:500px" class="modal-body">
                            <div class="form-row">
                                <div class="col-12 mb-3">
                                    <label>Message to user</label>
                                    <div class="input-group">
                                        <textarea maxlength="200" name="message_to_user" type="text" class="form-control" placeholder="Message to user" required=""></textarea>
                                        <div class="invalid-feedback">Please input a valid Message </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button class="btn btn-success" type="submit">Paid</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Modal End -->

        <!-- Modal Start -->
        <div class="modal fade" id="withdrawRejectionModal" tabindex="-1" role="dialog" aria-labelledby="paytmModal" style="display: none;" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Rejection Confirmation Modal</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <form id="_reject_withdraw" novalidate="" class="needs-validation">
                        <div style="max-width:500px" class="modal-body">
                            <div class="form-row">
                                <div class="col-12 mb-3">
                                    <label>Reason For Payment Reject</label>
                                    <div class="input-group">
                                        <textarea name="reject_reason" type="text" class="form-control" placeholder="Reason For Payment Reject" required=""></textarea>
                                        <div class="invalid-feedback">Please input a valid Reason </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button class="btn btn-primary" type="submit">Reject</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Modal End -->

        <!-- footer start-->
        <?php echo web_footer("admin"); ?>
        <!--  -->
        <script src="<?php echo $base_url; ?>/assets/js/jquery.dataTables.min.js"></script>
        <script>
            init_datatbl();
        </script>
    </div>
    </div>




</body>

</html>