<?php

$base = '../';
$active_page = "withdraw";
include $base . "db.php";
check_user_login();
$user_id = $loggedin_user_id;


if (!isset($_GET["id"])) {
    http_response_404();
}

$gateway_id = $_GET["id"];
if (!is_payment_gateway_id($gateway_id)) {
    http_response_404();
}

$query = mysqli_query($conn, "SELECT * FROM $withdraw_method_tbl WHERE gateway_id = '$gateway_id' ");
$row = mysqli_fetch_array($query);
$user_withdraw_charge = user_withdraw_charge($user_id);
$withdraw_charge_input = '<div class="col-lg-12 col-md-12 col-sm-12 mb-3">
                                            <label>Withdraw Charge<small> (' . $user_withdraw_charge . '%) </small></label>
                                            <div class="input-group">
                                             <div class="input-group-prepend">
                                                    <span class="input-group-text">'. $web_currency.'</span>
                                                </div>
                                                <input id="withdraw_charge" disabled type="text" value="" class="form-control" placeholder="Withdraw Charge" autocomplete="off">                                       
                                            </div>
                                        </div>';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php echo web_metadata(); ?>
    <title> Withdraw Details - <?php echo $web_name; ?></title>
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
                                <h3>Withdraw Details</h3>
                            </div>
                            <div class="col-6">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?php echo $base_url; ?>"><i class="material-icons-outlined">home</i> </a></li>
                                    <li class="breadcrumb-item"><a>Withdraw</a></li>
                                    <li class="breadcrumb-item active">Withdraw Details</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Container-fluid starts-->
                <div class="container-fluid">
                    <div class="row">
                        <!--  -->
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-heading">Withdraw Details</h4>
                                </div>
                                <div class="card-body">
                                    <form id="withdraw_payment_form" data-id="6" class="needs-validation" novalidate="" disabled="disabled">
                                        <!--  -->
                                        <div class="col-lg-12 col-md-12 col-sm-12 mb-3">
                                            <label>Amount</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><?php echo $web_currency; ?></span>
                                                </div>
                                                <input id="withdraw_amt_input" data-validate="number" required="" name="amount" type="text" value="" class="form-control" placeholder="Amount" autocomplete="off">
                                                <div class="invalid-feedback">Please provide a valid Amount</div>
                                            </div>
                                        </div>
                                        <!--  -->

                                        <div id="charges_input_container">
                                            <?php echo $withdraw_charge_input; ?>
                                        </div>

                                        <!--  -->
                                        <div class="col-lg-12 col-md-12 col-sm-12 mb-3">
                                            <label>Final Amount</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><?php echo $web_currency; ?></span>
                                                </div>
                                                <input id="final_withdraw_amount" readonly type="text" value="" class="form-control" placeholder="Final Amount" autocomplete="off">
                                            </div>
                                        </div>
                                        <!--  -->
                                        <div>
                                            <button type="submit" class="pull-right btn btn-primary">Withdraw</button>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                        <!--  -->

                        <?php echo user_gateway_detail_card($user_id, $gateway_id) ?>
                    </div>
                </div>
                <!-- Container-fluid Ends-->
            </div>




            <!-- footer start-->
            <?php echo web_footer("user"); ?>
            <!--  -->
            <script src="<?php echo $base_url; ?>/assets/js/bootstrap.min.js"></script>
        </div>
    </div>




</body>

</html>