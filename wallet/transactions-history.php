<?php

$base = '../';
$active_page = "transactions-history";
include $base . "db.php";
check_user_login();
$user_id = $loggedin_user_id;
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <?php echo web_metadata(); ?>
    <title> Wallet - <?php echo $web_name; ?></title>
    <link rel="stylesheet" href="<?php echo $base_url; ?>/assets/css/datatables.css">
    <?php include $base . "assets/css/css-files.php"; ?>
    <style>
        .card .material-icons-outlined {
            font-size: 40px !important;
        }
    </style>
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
                                <h3>Transactions History</h3>
                            </div>
                            <div class="col-6">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?php echo $base_url; ?>"><i class="material-icons-outlined">home</i> </a></li>
                                    <li class="breadcrumb-item"><a>Wallet</a></li>
                                    <li class="breadcrumb-item active">Transactions History</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Container-fluid starts-->
                <div class="container-fluid">


                    <div class="row m-0">


                        <div class="col-lg-3 col-md-6">
                            <div class="card border-bottom border-success">
                                <div class="card-body">
                                    <div class="d-flex no-block align-items-center">
                                        <div>
                                            <h2><?php echo add_currency(user_wallet($user_id)); ?></h2>
                                            <h6 class="text-success">Wallet</h6>
                                        </div>
                                        <div class="ml-auto">
                                            <div class="text-success"><i class="material-icons-outlined">account_balance_wallet</i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <div class="col-lg-3 col-md-6">
                            <div class="card border-bottom border-cyan">
                                <div class="card-body">
                                    <div class="d-flex no-block align-items-center">
                                        <div>
                                            <h2><?php echo add_currency(user_pair_income($user_id)); ?></h2>
                                            <h6 class="text-cyan">Pair Income</h6>
                                        </div>
                                        <div class="ml-auto">
                                            <div class="text-cyan"><i class="material-icons-outlined">attach_money</i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="card border-bottom border-cyan">
                                <div class="card-body">
                                    <div class="d-flex no-block align-items-center">
                                        <div>
                                            <h2><?php echo add_currency(user_total_added_money($user_id)); ?></h2>
                                            <h6 class="text-cyan">Total Added Money</h6>
                                        </div>
                                        <div class="ml-auto">
                                            <div class="text-cyan"><i class="material-icons-outlined">attach_money</i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="card border-bottom border-cyan">
                                <div class="card-body">
                                    <div class="d-flex no-block align-items-center">
                                        <div>
                                            <h2><?php echo add_currency(user_total_withdrawl($user_id)); ?></h2>
                                            <h6 class="text-cyan">Total Withdrawl</h6>
                                        </div>
                                        <div class="ml-auto">
                                            <div class="text-cyan"><i class="material-icons-outlined">attach_money</i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="card border-bottom border-warning">
                                <div class="card-body">
                                    <div class="d-flex no-block align-items-center">
                                        <div>
                                            <h2><?php echo add_currency(user_deposit_review($user_id)); ?></h2>
                                            <h6 class="text-warning">Deposit in review</h6>
                                        </div>
                                        <div class="ml-auto">
                                            <div class="text-warning"><i class="material-icons-outlined">schedule</i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="card border-bottom border-warning">
                                <div class="card-body">
                                    <div class="d-flex no-block align-items-center">
                                        <div>
                                            <h2><?php echo add_currency(user_pending_amt($user_id)); ?></h2>
                                            <h6 class="text-warning">withdrawal in pending</h6>
                                        </div>
                                        <div class="ml-auto">
                                            <div class="text-warning"><i class="material-icons-outlined">schedule</i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>



                    </div>




                    <div class="card">
                        <div class="card-header justify-between">
                            <h5>Transactions</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="display" id="data_tbl">
                                    <thead>
                                        <tr>
                                            <th>Serial No.</th>
                                            <th>Transaction Id</th>
                                            <th>Amount</th>
                                            <th>Txn Charge</th>
                                            <th>Net Amount</th>
                                            <th>Date</th>
                                            <th>Description</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php echo transaction_tbl($user_id); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- Container-fluid Ends-->
            </div>


            <!-- footer start-->
            <?php echo web_footer("user"); ?>
            <!--  -->
            <script src="<?php echo $base_url; ?>/assets/js/jquery.dataTables.min.js"></script>
            <script>
                init_datatbl();
            </script>
        </div>
    </div>




</body>

</html>