<?php

$base = '../';
$active_page = "deposit-history";
include $base . "db.php";
check_user_login();

if (!isset($_GET["id"])) {
    http_response_404();
}

$transaction_id = $_GET["id"];
$query = mysqli_query($conn, "SELECT * FROM $deposit_tbl WHERE transaction_id = '$transaction_id' ");
if (!mysqli_num_rows($query)) {
    http_response_404();
}
$row = mysqli_fetch_array($query);
$user_id = $row["user_id"];


$amount_deposited = $row["amount"];
$requested_date = $row["payment_type"];
$deposit_status = $row["status"];
$message = unsanitize_text($row["message"]);
$payment_gateway = $row["gateway"];
if (is_empty($message)) {
    $message = "NA";
}

if ($deposit_status == "review") {
    $deposit_label = '<label class="ml-4 badge alert-warning">review</label>';
}
if ($deposit_status == "approved") {
    $deposit_label = '<label class="ml-4 badge alert-success">credit</label>';
}
if ($deposit_status == "rejected") {
    $deposit_label = '<label class="ml-4 badge alert-danger">rejected</label>';
}

$payment_image = get_image_src($row["payment_image"]);


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
                                <h3>Deposit Preview</h3>
                            </div>
                            <div class="col-6">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?php echo $base_url; ?>"><i class="material-icons-outlined">home</i> </a></li>
                                    <li class="breadcrumb-item active">Deposit Preview</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Container-fluid starts-->
                <div class="container-fluid">

                    <div class="row">

                        <div class="col-lg-12">
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
                                                <div class="col-md-12 col-lg-12 mb-3">
                                                    <label>Payment Gateway</label>
                                                    <input value="<?php echo $payment_gateway; ?>" readonly="" type="text" class="form-control" placeholder="Payment Gateway" required="" autocomplete="off">
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
                                </div>
                            </div>

                        </div>
                    </div>


                </div>


            </div>
        </div>

        <!-- footer start-->
        <?php echo web_footer("admin"); ?>
        <!--  -->
        <script src="<?php echo $base_url; ?>/assets/js/jquery.dataTables.min.js"></script>
        <script>
            init_datatbl();
        </script>
    </div>




</body>

</html>