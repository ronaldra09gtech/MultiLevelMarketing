<?php

$base = '../../';
$active_page = "support";
include $base . "db.php";
check_admin_login();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php echo web_metadata(); ?>
    <title>Support - <?php echo $web_name; ?></title>
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
                                <h3>Support</h3>
                            </div>
                            <div class="col-6">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?php echo $admin_base_url; ?>"><i class="material-icons-outlined">home</i> </a></li>
                                    <li class="breadcrumb-item active">Support</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Container-fluid starts-->
                <div class="container-fluid">

                    <div class="card">
                        <div class="card-header">
                            <h5>Support Ticket List</h5>
                            <span>List of ticket opened by customers</span>
                        </div>
                        <div class="card-body">

                            <ul class="nav nav-tabs border-tab nav-secondary nav-left" id="danger-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link align-center active" id="danger-home-tab" data-bs-toggle="tab" href="#danger-total"><i class="material-icons-outlined">message</i><span>Total</span></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link align-center" id="profile-danger-tab" data-bs-toggle="tab" href="#danger-pending"><i class="material-icons-outlined">mark_chat_unread</i><span>Pending</span></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link align-center" id="contact-danger-tab" data-bs-toggle="tab" href="#danger-open"><i class="material-icons-outlined">quickreply</i><span>Open</span></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link align-center" id="contact-danger-tab" data-bs-toggle="tab" href="#danger-closed"><i class="material-icons-outlined">speaker_notes_off</i><span>Closed</span></a>
                                </li>
                            </ul>

                            <div class="tab-content" id="danger-tabContent">
                                <!--  -->
                                <div class="tab-pane fade active show" id="danger-total" role="tabpanel" aria-labelledby="danger-home-tab">
                                    <div class="table-responsive">
                                        <table class="display" id="data_tbl">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Ticket Id</th>
                                                    <th>Subject</th>
                                                    <th>Status</th>
                                                    <th>User ID</th>
                                                    <th>Username</th>
                                                    <th>Last Reply On</th>
                                                    <th>Date Added</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php echo _support_tbl("total"); ?>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                                <!--  -->
                                <div class="tab-pane fade" id="danger-pending" role="tabpanel" aria-labelledby="profile-danger-tab">
                                    <div class="table-responsive">
                                        <table class="display" id="data_tbl">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Ticket Id</th>
                                                    <th>Subject</th>
                                                    <th>Status</th>
                                                    <th>User ID</th>
                                                    <th>Username</th>
                                                    <th>Last Reply On</th>
                                                    <th>Date Added</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php echo _support_tbl("pending"); ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!--  -->
                                <div class="tab-pane fade" id="danger-open" role="tabpanel" aria-labelledby="contact-danger-tab">
                                    <div class="table-responsive">
                                        <table class="display" id="data_tbl">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Ticket Id</th>
                                                    <th>Subject</th>
                                                    <th>Status</th>
                                                    <th>User ID</th>
                                                    <th>Username</th>
                                                    <th>Last Reply On</th>
                                                    <th>Date Added</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php echo _support_tbl("open"); ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!--  -->
                                <div class="tab-pane fade" id="danger-closed" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="display" id="data_tbl">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Ticket Id</th>
                                                    <th>Subject</th>
                                                    <th>Status</th>
                                                    <th>User ID</th>
                                                    <th>Username</th>
                                                    <th>Last Reply On</th>
                                                    <th>Date Added</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php echo _support_tbl("closed"); ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!--  -->
                            </div>


                        </div>
                    </div>

                </div>
                <!-- Container-fluid Ends-->
            </div>


            <!-- footer start-->
            <?php echo web_footer("admin"); ?>
            <!--  -->
            <script src="<?php echo $base_url; ?>/assets/js/jquery.dataTables.min.js"></script>
            <script src="<?php echo $base_url; ?>/assets/js/bootstrap.bundle.min.js"></script>
            <script>
                init_datatbl();
            </script>
        </div>
    </div>




</body>

</html>