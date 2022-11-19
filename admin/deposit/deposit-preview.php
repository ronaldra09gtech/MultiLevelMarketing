<?php

$base = '../../';
$active_page = "pending-deposits";
include $base . "db.php";
check_admin_login();

if (!isset($_GET["id"])) {
    http_response_404();
}

$transaction_id = $_GET["id"];
$query = mysqli_query($conn, "SELECT * FROM deposit_tickets WHERE transaction_id = '$transaction_id' ");
if (!mysqli_num_rows($query)) {
    http_response_404();
}
$row = mysqli_fetch_array($query);
$user_id = $row["ticket_creator"];
$user_name = user_name($user_id);
$registration_date = user_registration_date($user_id);
$referred_by = user_referred_by($user_id);
$user_status = user_status_label($user_id);
$user_image_src = user_image($user_id);

$amount_deposited = $row["amount"];
$deposit_status = $row["status"];

$query2 = mysqli_query($conn, "SELECT * FROM deposit_ticket_messages WHERE ticket_id = '$transaction_id' ");
if (!mysqli_num_rows($query2)) {
    http_response_404();
}
$row2 = mysqli_fetch_array($query2);
$message = unsanitize_text($row2["message"]);
if (is_empty($message)) {
    $message = "NA";
}

if ($deposit_status == "1") {
    $deposit_label = '<label class="ml-4 badge alert-warning">review</label>';
}
if ($deposit_status == "2") {
    $deposit_label = '<label class="ml-4 badge alert-success">approve</label>';
}
if ($deposit_status == "3") {
    $deposit_label = '<label class="ml-4 badge alert-danger">rejected</label>';
}


$payment_image = get_image_src($row2["files"]);


?>
<!DOCTYPE html>
<html lang="en">

<head>
   
    <?php echo web_metadata(); ?>
 
    <title> Deposit Preview - <?php echo $web_name; ?></title>
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
               <?php include $base."assets/nav/admin/header.php"; ?>
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
                                <h3>Deposit Preview</h3>
                            </div>
                            <div class="col-6">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?php echo $admin_base_url; ?>"><i class="material-icons-outlined">home</i> </a></li>
                                    <li class="breadcrumb-item active">Deposit Preview</li>
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
                                        <h3>Deposit Details</h3> <?php echo $deposit_label; ?>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-row">
                                                <div class="col-md-12 col-lg-12 mb-3">
                                                    <label> Currency</label>
                                                    <input value="<?php echo $web_currency; ?>" readonly="" type="text" class="form-control" placeholder="Currency" required="" autocomplete="off">
                                                </div>
                                                <div class="col-md-12 col-lg-12 mb-3">
                                                    <label>Amount Deposited</label>
                                                    <input value="<?php echo $amount_deposited; ?>" readonly="" type="text" class="form-control" placeholder="Amount Deposited" required="" autocomplete="off">
                                                </div>
                                                <?php if (!is_empty($payment_image)) { ?><div class="col-md-12 col-lg-12 mb-3">
                                                        <label> Payment Image </label>
                                                        <label class="p-2 bg-light payment-img-container">
                                                            <img target="_blank" style="max-height: 600px !important;" class="img-fluid" src="<?php echo $payment_image; ?>" alt="">
                                                        </label>
                                                        <!--  -->
                                                    </div>
                                                <?php

                                                }
                                                ?>
                                                <div class="col-md-12 col-lg-12 mb-3">
                                                    <label><?php if ($deposit_status == "rejected") {
                                                                echo "Reason";
                                                            } else {
                                                                echo "Message";
                                                            } ?></label>
                                                    <textarea readonly="" type="text" class="form-control" placeholder="Message" required="" autocomplete="off"><?php echo $message; ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                    if ($deposit_status == "1") { ?>
                                        <div class="justify-right">
                                            <button data-toggle="modal" data-target="#depositRejectionModal" class="btn btn-danger mr-3">Reject</button>
                                            <button data-toggle="modal" data-target="#depositConfirmationModal" class="btn btn-success">Approve</button>
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
                                Deposit Summary
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
                                            <th>Requested Date</th>
                                            <th>Success Date</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php echo _user_deposit_tbl($user_id); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>

        <!-- Modal Start -->
        <div class="modal fade" id="depositConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="paytmModal" style="display: none;" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Deposit Confirmation Modal</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <form id="_approve_deposit" novalidate="" class="needs-validation">
                        <div style="max-width:500px" class="modal-body">
                            <div class="form-row">
                                <div class="col-12 mb-3">
                                    <label>Message to user</label>
                                    <div class="input-group">
                                        <textarea maxlength="200" name="message_to_user" type="text" class="form-control" placeholder="Message to user (optional)"></textarea>
                                        <input type="hidden" name="transaction_id" value="<?php echo $transaction_id ?>">
                                        <div class="invalid-feedback">Please input a valid Message </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button class="btn btn-success" type="submit">Approve</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Modal End -->

        <!-- Modal Start -->
        <div class="modal fade" id="depositRejectionModal" tabindex="-1" role="dialog" aria-labelledby="paytmModal" style="display: none;" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Rejection Confirmation Modal</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <form id="_reject_deposit" novalidate="" class="needs-validation">
                        <div style="max-width:500px" class="modal-body">
                            <div class="form-row">
                                <div class="col-12 mb-3">
                                    <label>Reason For Deposit Rejection</label>
                                    <div class="input-group">
                                        <textarea name="reject_reason" type="text" class="form-control" placeholder="Reason For Deposit Rejection" required=""></textarea>
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