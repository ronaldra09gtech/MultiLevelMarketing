<?php

$base = '../';
$active_page = "profile";
include $base . "db.php";
check_user_login();
$user_id = $loggedin_user_id;
$aadhar_image = user_aadhar_image($user_id);
$pan_image = user_pan_image($user_id);

$aadhar_number = user_aadhar_number($user_id);
$pan_number = user_pan_number($user_id);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php echo web_metadata(); ?>
    <title>KYC - <?php echo $web_name; ?></title>
    <link rel="stylesheet" href="<?php echo $base_url; ?>/assets/css/cropper.css">
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
                                <h3>KYC</h3>
                            </div>
                            <div class="col-6">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?php echo $base_url; ?>"><i class="material-icons-outlined">home</i> </a></li>
                                    <li class="breadcrumb-item"><a href="<?php echo $base_url; ?>/profile/">Profile </a></li>
                                    <li class="breadcrumb-item active">KYC</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Container-fluid starts-->
                <div class="container-fluid">

                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Aadhar Card</h4>
                                </div>
                                <div class="card-body">
                                    <div>
                                        <div style="max-width:350px;min-height:250px;" data-upload="kyc_img_upload_aadhar" id="img_upload_card" class="m-auto payment-img-container">
                                            <div <?php if (!is_empty($aadhar_image)) {
                                                        echo 'class="d-none"';
                                                    }  ?> id="img_upload_inner">
                                                <i class="material-icons-outlined">backup</i>
                                                <h4 class="text-center">Aadhar Card</h4>
                                            </div>
                                            <img class="img-fluid" src="<?php echo $aadhar_image; ?>" alt="">
                                        </div>
                                        <input accept="image/*" type="file" class="sr-only none" select="" id="kyc_image_chooser">
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <h5>Aadhar Number: <span><?php echo $aadhar_number; ?></span></h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Pan Card</h4>
                                </div>
                                <div class="card-body">
                                    <div>
                                        <div style="max-width:350px;min-height:250px;" data-upload="kyc_img_upload_pan" id="img_upload_card" class="m-auto payment-img-container">
                                            <div <?php if (!is_empty($pan_image)) {
                                                        echo 'class="d-none"';
                                                    }  ?> id="img_upload_inner">
                                                <i class="material-icons-outlined">backup</i>
                                                <h4 class="text-center">Pan Card</h4>
                                            </div>
                                            <img class="img-fluid" src="<?php echo $pan_image; ?>" alt="">
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <h5>Pan Number: <span><?php echo $pan_number; ?></span></h5>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>




            </div>
        </div>
    </div>


    <!-- footer start-->
    <?php echo web_footer("user"); ?>
    <!--  -->
</body>

</html>