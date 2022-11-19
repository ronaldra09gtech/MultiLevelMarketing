<?php

$active_page = "package";
include "../db.php";
check_user_login();
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <?php echo web_metadata(); ?>
    <title>Package - <?php echo $web_name; ?></title>
    <?php include "../assets/css/css-files.php"; ?>
    <link rel="stylesheet" href="style.css">

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
                                <h3 class="text-white">Package</h3>
                            </div>
                            <div class="col-6">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?php echo $base_url; ?>"><i class="text-white material-icons-outlined">home</i> </a></li>
                                    <li class="breadcrumb-item text-white active">Package</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Container-fluid starts-->
                <div class="container-fluid package-container">

                    <div class="wrapper">
                        <!--  -->
                        <?php echo plans_card($loggedin_user_id); ?>


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