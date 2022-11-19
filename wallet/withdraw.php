<?php

$base = '../';
$active_page = "withdraw";
include $base . "db.php";
check_user_login();
$user_id = $loggedin_user_id;

?>
<!DOCTYPE html>
<html lang="en">

<head>
  
    <?php echo web_metadata(); ?>
  
    <title>Withdraw - <?php echo $web_name; ?></title>
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
                                <h3>Deposit Money</h3>
                            </div>
                            <div class="col-6">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?php echo $base_url; ?>"><i class="material-icons-outlined">home</i> </a></li>
                                    <li class="breadcrumb-item"><a>Wallet</a></li>
                                    <li class="breadcrumb-item active">Withdraw</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Container-fluid starts-->
                <div class="container-fluid">



                <div class="card">
                        <div class="card-header justify-between">
                            <div>
                                <h5>Withdraw amount List</h5>
                                <span>List of Withdraw Request opend by you</span>
                            </div>
                            <a href="<?php echo $base_url; ?>/wallet/add-withdraw-ticket.php" class="btn btn-success">Create Request</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="display" id="data_tbl">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Ticket Id</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Date Added</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php echo withdraw_ticket_tbl($user_id); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                </div>
                <!-- Container-fluid Ends-->
            </div>


            <!-- Paytm -->
            <div class="modal fade" id="paytmModal" tabindex="-1" role="dialog" aria-labelledby="paytmModal" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="paytmModal">Deposit amount by paytm</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="deposit_paytm_form" novalidate class="needs-validation">
                            <div style="max-width:500px" class="modal-body">
                                <div class="form-row">
                                    <div class="col-12 mb-3">
                                        <label>Enter Amount</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><?php echo $web_currency; ?></span>
                                            </div>
                                            <input maxlength="5" name="paytm_amount" type="text" data-validate="number" class="form-control" placeholder="Amount to deposit" required="">
                                            <div class="invalid-feedback">Please input a valid Amount </div>
                                        </div>
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
        </div>
    </div>

    <script src="<?php echo $base_url; ?>/assets/js/jquery.dataTables.min.js"></script>
            <script>
                init_datatbl();
            </script>


</body>

</html>
