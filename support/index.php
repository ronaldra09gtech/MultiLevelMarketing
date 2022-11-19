<?php

$base = '../';
$active_page = "support";
include $base . "db.php";
check_user_login();
$user_id = $loggedin_user_id;

?>
<!DOCTYPE html>
<html lang="en">

<head>
  
    <?php echo web_metadata(); ?>
   
    <title>Profile - <?php echo $web_name; ?></title>
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
                                <h3>Support</h3>
                            </div>
                            <div class="col-6">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?php echo $base_url; ?>"><i class="material-icons-outlined">home</i> </a></li>
                                    <li class="breadcrumb-item active">Support</li>
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
                                <h5>Support Ticket List</h5>
                                <span>List of ticket opend by you</span>
                            </div>
                            <a href="<?php echo $base_url; ?>/support/add-ticket" class="btn btn-success">Add A Ticket</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="display" id="data_tbl">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Ticket Id</th>
                                            <th>Subject</th>
                                            <th>Status</th>
                                            <th>Date Added</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php echo support_tbl($user_id); ?>
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