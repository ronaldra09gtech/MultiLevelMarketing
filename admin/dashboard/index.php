<?php

$active_page = "dashboard";
include "../../db.php";
check_admin_login();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php echo web_metadata(); ?>
    <title>Dashboard - <?php echo $web_name; ?></title>
    <?php include "../../assets/css/css-files.php"; ?>
    <style>
        .card.border-bottom {
            border-radius: 0;
        }

        .card .ml-auto .material-icons-outlined {
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
        <?php include "../../assets/nav/admin/header.php"; ?>
        <!-- Page Header Ends     -->
        <!-- Page Body Start-->
        <div class="page-body-wrapper">
            <!-- Page Sidebar Start-->
            <?php include '../../assets/nav/admin/sidebar.php'; ?>
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
                                    <li class="breadcrumb-item"><a href="<?php echo $admin_base_url; ?>"><i class="material-icons-outlined">home</i> </a></li>
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
                        <!--  -->
                        <div class="col-lg-3 col-md-6">
                            <div class="rect-card card border-bottom border-info">
                                <div class="card-body">
                                    <div class="d-flex no-block align-items-center">
                                        <div>
                                            <h2><?php echo _left_joining_count(); ?></h2>
                                            <h6 class="text-info">Left Joining</h6>
                                        </div>
                                        <div class="ml-auto">
                                            <div class="text-info"><i class="material-icons-outlined">attach_money</i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="rect-card card border-bottom border-info">
                                <div class="card-body">
                                    <div class="d-flex no-block align-items-center">
                                        <div>
                                            <h2><?php echo _right_joining_count(); ?></h2>
                                            <h6 class="text-info">Right Joining</h6>
                                        </div>
                                        <div class="ml-auto">
                                            <div class="text-info"><i class="material-icons-outlined">attach_money</i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="rect-card card border-bottom border-info">
                                <div class="card-body">
                                    <div class="d-flex no-block align-items-center">
                                        <div>
                                            <h2><?php echo _active_members(); ?></h2>
                                            <h6 class="text-info">Active Members</h6>
                                        </div>
                                        <div class="ml-auto">
                                            <div class="text-info"><i class="material-icons-outlined">schedule</i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="rect-card card border-bottom border-info">
                                <div class="card-body">
                                    <div class="d-flex no-block align-items-center">
                                        <div>
                                            <h2><?php echo _today_users_joining(); ?></h2>
                                            <h6 class="text-info">Today Joining</h6>
                                        </div>
                                        <div class="ml-auto">
                                            <div class="text-info"><i class="material-icons-outlined">people</i></div>
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
                                            <h2><?php echo _withdraw_requests_count(); ?></h2>
                                            <h6 class="text-success">Withdraw Requests </h6>
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
                                            <h2><?php echo add_currency(_withdraw_in_pending()); ?></h2>
                                            <h6 class="text-success">Withdraw Request </h6>
                                        </div>
                                        <div class="ml-auto">
                                            <div class="text-success"><i class="material-icons-outlined">attach_money</i></div>
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
                                            <h2><?php echo _deposit_requests_count(); ?></h2>
                                            <h6 class="text-success">Deposit Requests </h6>
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
                                            <h2><?php echo add_currency(_deposit_in_pending()); ?></h2>
                                            <h6 class="text-success">Deposit Request </h6>
                                        </div>
                                        <div class="ml-auto">
                                            <div class="text-success"><i class="material-icons-outlined">attach_money</i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--  -->
                        <div class="col-lg-3 col-md-6">
                            <div class="rect-card card border-bottom border-warning">
                                <div class="card-body">
                                    <div class="d-flex no-block align-items-center">
                                        <div>
                                            <h2><?php echo _total_tickets(); ?></h2>
                                            <h6 class="text-warning">Total Tickets</h6>
                                        </div>
                                        <div class="ml-auto">
                                            <div class="text-warning"><i class="material-icons-outlined">message</i></div>
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
                                            <h2><?php echo _pending_tickets(); ?></h2>
                                            <h6 class="text-warning">Pending Tickets</h6>
                                        </div>
                                        <div class="ml-auto">
                                            <div class="text-warning"><i class="material-icons-outlined">mark_chat_unread</i></div>
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
                                            <h2><?php echo _open_tickets(); ?></h2>
                                            <h6 class="text-warning">Open Tickets</h6>
                                        </div>
                                        <div class="ml-auto">
                                            <div class="text-warning"><i class="material-icons-outlined">quickreply</i></div>
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
                                            <h2><?php echo _closed_tickets(); ?></h2>
                                            <h6 class="text-warning">Closed Tickets</h6>
                                        </div>
                                        <div class="ml-auto">
                                            <div class="text-warning"><i class="material-icons-outlined">speaker_notes_off</i></div>
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
                                <div class="card-header justify-between bg-danger">
                                    <h4 class="mb-0 text-white">Notice</h4>
                                    <i data-toggle="modal" data-target="#_NoticeModal" class="material-icons-outlined">edit</i>
                                </div>
                                <div class="card-body">
                                    <p id="notice_text" class="pre-wrap card-text"><?php echo web_notice(); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>


            </div>
            <!-- Container-fluid Ends-->
        </div>


        <div class="modal fade" id="_NoticeModal" tabindex="-1" role="dialog" style="display: none;" aria-hidden="true">
            <div style="max-width:900px" class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Change Plan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <form id="_notice_form" class="py-2 needs-validation resetonload" no-validate="">
                        <div class="modal-body">
                            <div class="form-row">
                                <div class="col-12 mb-3">
                                    <label>Notice</label>
                                    <textarea rows="3" name="notice" type="text" class="form-control" placeholder="Notice" required="" autocomplete="off"><?php echo web_notice(); ?></textarea>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button class="btn btn-primary" type="submit">Submit</button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
            <!--  -->
        </div>


        <!-- footer start-->
        <?php echo web_footer("admin"); ?>

    </div>

</body>

</html>