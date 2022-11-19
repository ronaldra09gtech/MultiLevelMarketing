<?php

$active_page = "package-setting";
$base = "../../";
include $base . "db.php";
check_admin_login();


?>
<!DOCTYPE html>
<html lang="en">

<head>

    <?php echo web_metadata(); ?>

    <title>Package Setting - <?php echo $web_name; ?></title>
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
            <?php include $base . 'assets/nav/admin/sidebar.php'; ?>
            <!-- Page Sidebar Ends-->
            <div class="page-body">
                <div class="container-fluid">
                    <div class="page-title">
                        <div class="row">
                            <div class="col-6">
                                <h3>Package Setting</h3>
                            </div>
                            <div class="col-6">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?php echo $base_url; ?>"><i class="material-icons-outlined">home</i> </a></li>
                                    <li class="breadcrumb-item"><a> Settings </a></li>
                                    <li class="breadcrumb-item active">Package Setting </li>
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
                                <h5>Packages</h5>
                            </div>
                            <a href="<?php echo $admin_base_url; ?>/settings/add-new-package" class="btn btn-success">Add New</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="display" id="data_tbl">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Package</th>
                                            <th>Price</th>
                                            <th>Pair Income</th>
                                            <th>Daily Pair Income <small>(max)</small></th>
                                            <th>Minimum Withdrawal</th>
                                            <th>Withdraw Charge</th>
                                            <th>Validity</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php echo _packages_tbl(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
            <!-- Container-fluid Ends-->
        </div>


        <!-- footer start-->
        <?php echo web_footer("admin"); ?>

    </div>

    <script src="<?php echo $base_url; ?>/assets/js/jquery.dataTables.min.js"></script>
    <script>
        init_datatbl();
    </script>

</body>

</html>