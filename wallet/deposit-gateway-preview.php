<?php

$base = '../';
$active_page = "deposit";
include $base . "db.php";
check_user_login();
$user_id = $loggedin_user_id;



if (!isset($_GET["id"])) {
    http_response_404();
}

$gateway_id = $_GET["id"];
$query = mysqli_query($conn, "SELECT * FROM $manual_deposit_method WHERE gateway_id = '$gateway_id' ");
if (!mysqli_num_rows($query)) {
    http_response_404();
}
$row = mysqli_fetch_array($query);

$gateway_image_id = $row["gateway_image"];
$gateway_name  = $row["gateway_name"];
$processing_time = $row["processing_time"];
$status = $row["status"];
$deposit_details = $row["deposit_details"];
$gateway_image = get_image_src($gateway_image_id);

$deposit_details = unserialize($deposit_details);

$count = 0;
$detail_fields = '';
foreach ($deposit_details["label"] as $key =>  $label_text) {
    $count++;
    if ($count == '1') {
        $btn = '<button type="button" id="_add_deposit_detail_row" class="btn btn-success align-center"><i class="fa fa-plus"></i></button>';
    } else {
        $btn = '<button type="button" data-element="1" id="remove_element" class="btn btn-danger align-center"><i class="fa fa-minus"></i></button> ';
    }
    $detail_value = $deposit_details["value"][$key];
    $detail_fields .= '<div class="col-12 mb-3">
                                    <label>' . $label_text . '</label>
                                    <input value="' . $detail_value . '" disabled="" type="text" class="form-control" placeholder="' . $label_text . '" required="">
                                </div>';
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php echo web_metadata(); ?>
    <title>Deposit Money - <?php echo $web_name; ?></title>
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
                                <h3>Deposit Money</h3>
                            </div>
                            <div class="col-6">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?php echo $base_url; ?>"><i class="material-icons-outlined">home</i> </a></li>
                                    <li class="breadcrumb-item"><a>Wallet</a></li>
                                    <li class="breadcrumb-item active">Bank Transfer</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Container-fluid starts-->
                <div class="container-fluid">


                    <div class="row">
                        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6">
                            <div class="card text-center">
                                <img src="<?php echo $gateway_image; ?>" class="card-img-top" alt="image">
                                <div class="card-body">
                                    <h3 class="card-title">Transfer Money through <?php echo $gateway_name; ?></h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3>Admin Account Details</h3>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group">
                                        <?php echo $detail_fields; ?>
                                    </ul>
                                    <div class="mt-3">
                                        <button id="triggerBankModal" class="pull-right btn btn-primary" data-toggle="modal" data-target="#bankTransferModal">Deposit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>


            </div>
            <!-- Container-fluid Ends-->
        </div>


        <!-- Bank Transfer -->
        <div class="modal fade" id="bankTransferModal" tabindex="-1" role="dialog" aria-labelledby="paytmModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="paytmModal">Deposit Amount Through <?php echo $gateway_name; ?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="deposit_gateway_form" novalidate class="needs-validation">
                        <div style="max-width:500px" class="modal-body">
                            <div class="form-row">
                                <div class="col-12 mb-3">
                                    <label>Paid Amount</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><?php echo $web_currency; ?></span>
                                        </div>
                                        <input maxlength="5" name="paid_amount" type="text" data-validate="number" class="form-control" placeholder="Amount to deposit" required="">
                                        <div class="invalid-feedback">Please input a valid Amount </div>
                                    </div>
                                </div>
                                <?php echo $detail_fields; ?>
                                <div class="col-12 mb-3">
                                    <label>Payment Image</label>
                                    <label for="bank_transfer_choose_image" class="text-center btn btn-info btn-block">
                                        <div class="justify-align-center"><span class="material-icons-outlined mr-2">upload_file</span><span class="mt-1">Choose</span></div>
                                    </label>
                                    <input class="sr-only" id="bank_transfer_choose_image" name="payment_image" type="file" accept="image/*" required="">
                                    <div class="invalid-feedback">Please choose a valid Payment Image </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button class="btn btn-primary" type="submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <!--  -->

        <!-- footer start-->
        <?php echo web_footer("user"); ?>
        <!--  -->
        <script src="<?php echo $base_url; ?>/assets/js/bootstrap.min.js"></script>
        <script>
            $(document).on("change", "#bank_transfer_choose_image", function() {
                $filename = $(this).val().replace(/C:\\fakepath\\/i, '');
                $("label[for=bank_transfer_choose_image]").html($filename);
            })
        </script>
    </div>
    </div>


</body>

</html>