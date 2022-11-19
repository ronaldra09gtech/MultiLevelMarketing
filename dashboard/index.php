<?php

$active_page = "dashboard";
include "../db.php";
check_user_login();

$user_id = $loggedin_user_id;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php echo web_metadata(); ?>
    <title>Dashboard - <?php echo $web_name; ?></title>
    <?php include "../assets/css/css-files.php"; ?>
    <style>
        .card.border-bottom {
            border-radius: 0;
        }

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
            <?php include '../assets/nav/user/sidebar.php'; ?>
            <!-- Page Sidebar Ends-->
            <div class="page-body">
                <div class="container-fluid">
                    <div class="page-title">
                        <div class="row">
                            <div class="col-6">
                                <h3>Dashboard</h3>
                            </div>
                            <div class="col-6">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?php echo $base_url; ?>"><i class="material-icons-outlined">home</i> </a></li>
                                    <li class="breadcrumb-item active">Dashboard</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Container-fluid starts-->
                <div class="container-fluid">
                    <!--  -->
                    <div class="row">


                        <a href="<?php echo $base_url; ?>/package/history" class="col-lg-3 col-md-6">
                            <div class="rect-card card border-bottom border-success">
                                <div class="card-body">
                                    <div class="d-flex no-block align-items-center">
                                        <div>
                                            <h2><?php echo user_package_validity_remaining($user_id); ?></h2>
                                            <h6 class="text-success">Package Validity</h6>
                                            <i class="fas fa-less-than"></i>
                                        </div>
                                        <div class="ml-auto">
                                            <div class="text-success"><i class="material-icons-outlined">donut_large</i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>


                        <div class="col-lg-3 col-md-6">
                            <div class="rect-card card border-bottom border-success">
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
                            <div class="rect-card card border-bottom border-success">
                                <div class="card-body">
                                    <div class="d-flex no-block align-items-center">
                                        <div>
                                            <h2><?php echo add_currency(user_income($user_id)); ?></h2>
                                            <h6 class="text-success">Income</h6>
                                        </div>
                                        <div class="ml-auto">
                                            <div class="text-success"><i class="material-icons-outlined">attach_money</i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="rect-card card border-bottom border-cyan">
                                <div class="card-body">
                                    <div class="d-flex no-block align-items-center">
                                        <div>
                                            <h2><?php echo add_currency(user_total_income($user_id)); ?></h2>
                                            <h6 class="text-cyan">Total Income</h6>
                                        </div>
                                        <div class="ml-auto">
                                            <div class="text-cyan"><i class="material-icons-outlined">attach_money</i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="rect-card card border-bottom border-warning">
                                <div class="card-body">
                                    <div class="d-flex no-block align-items-center">
                                        <div>
                                            <h2><?php echo add_currency(user_pending_amt($user_id)); ?></h2>
                                            <h6 class="text-warning">Withdrawl Pending</h6>
                                        </div>
                                        <div class="ml-auto">
                                            <div class="text-warning"><i class="material-icons-outlined">schedule</i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="rect-card card border-bottom border-cyan">
                                <div class="card-body">
                                    <div class="d-flex no-block align-items-center">
                                        <div>
                                            <h2><?php echo add_currency(user_last_added_money($user_id)); ?></h2>
                                            <h6 class="text-cyan">Last added Money</h6>
                                        </div>
                                        <div class="ml-auto">
                                            <div class="text-cyan"><i class="material-icons-outlined">attach_money</i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <div class="col-lg-3 col-md-6">
                            <div class="rect-card card border-bottom border-cyan">
                                <div class="card-body">
                                    <div class="d-flex no-block align-items-center">
                                        <div>
                                            <h2><?php echo add_currency(user_last_withdraw_money($user_id)); ?></h2>
                                            <h6 class="text-cyan">Last Withdrawl Money</h6>
                                        </div>
                                        <div class="ml-auto">
                                            <div class="text-cyan"><i class="material-icons-outlined">attach_money</i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-lg-3 col-md-6">
                            <div class="rect-card card border-bottom border-cyan">
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
                            <div class="rect-card card border-bottom border-warning">
                                <div class="card-body">
                                    <div class="d-flex no-block align-items-center">
                                        <div>
                                            <h2><?php echo add_currency(user_deposit_review($user_id)); ?></h2>
                                            <h6 class="text-warning">Deposite in review</h6>
                                        </div>
                                        <div class="ml-auto">
                                            <div class="text-warning"><i class="material-icons-outlined">schedule</i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <div class="col-lg-3 col-md-6">
                            <div class="rect-card card border-bottom border-success">
                                <div class="card-body">
                                    <div class="d-flex no-block align-items-center">
                                        <div>
                                            <h2><?php echo user_left_count($user_id); ?></h2>
                                            <h6 class="text-success">Left Count</h6>
                                        </div>
                                        <div class="ml-auto">
                                            <div class="text-success"><i class="material-icons-outlined">people</i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="rect-card card border-bottom border-success">
                                <div class="card-body">
                                    <div class="d-flex no-block align-items-center">
                                        <div>
                                            <h2><?php echo user_right_count($user_id); ?></h2>
                                            <h6 class="text-success">Right Count</h6>
                                        </div>
                                        <div class="ml-auto">
                                            <div class="text-success"><i class="material-icons-outlined">people</i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="rect-card card border-bottom border-cyan">
                                <div class="card-body">
                                    <div class="d-flex no-block align-items-center">
                                        <div>
                                            <h2><?php echo user_pair_count($user_id); ?></h2>
                                            <h6 class="text-cyan">Pair Count</h6>
                                        </div>
                                        <div class="ml-auto">
                                            <div class="text-cyan"><i class="material-icons-outlined">people</i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-lg-3 col-md-6">
                            <div class="rect-card card border-bottom border-cyan">
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
                            <div class="rect-card card border-bottom border-cyan">
                                <div class="card-body">
                                    <div class="d-flex no-block align-items-center">
                                        <div>
                                            <h2><?php echo user_total_downlines($user_id); ?></h2>
                                            <h6 class="text-cyan">Total downlines</h6>
                                        </div>
                                        <div class="ml-auto">
                                            <div class="text-cyan"><i class="material-icons-outlined">mobiledata_off</i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="rect-card card border-bottom border-cyan">
                                <div class="card-body">
                                    <div class="d-flex no-block align-items-center">
                                        <div>
                                            <h2><?php echo user_direct_referrals_count($user_id); ?></h2>
                                            <h6 class="text-cyan">Direct Referral</h6>
                                        </div>
                                        <div class="ml-auto">
                                            <div class="text-cyan"><i class="material-icons-outlined">person_add</i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="rect-card card border-bottom border-success">
                                <div class="card-body">
                                    <div class="d-flex no-block align-items-center">
                                        <div>
                                            <h2><?php echo user_referred_by($user_id); ?></h2>
                                            <h6 class="text-success">Referral Id</h6>
                                        </div>
                                        <div class="ml-auto">
                                            <div class="text-success"><i class="material-icons-outlined">person_outline</i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--  -->

                    <div class="mt-4 row">
                        <div class="col-12">
                            <div class="card  card-hover">
                                <div class="card-header bg-danger">
                                    <h4 class="mb-0 text-white">Notice</h4>
                                </div>
                                <div class="card-body">
                                    <p class="pre-wrap card-text"><?php echo web_notice(); ?></p>
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

    </div>

</body>

</html>