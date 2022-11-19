<?php

$base = '../';
$active_page = "change-password";
include $base . "db.php";
check_user_login();
$user_id = $loggedin_user_id;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php echo web_metadata(); ?>
    <title>Change Password - <?php echo $web_name; ?></title>
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
                                <h3>Change Password</h3>
                            </div>
                            <div class="col-6">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?php echo $base_url; ?>"><i class="material-icons-outlined">home</i> </a></li>
                                    <li class="breadcrumb-item active">Change Password</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Container-fluid starts-->
                <div class="container-fluid">

                    <div class="card">
                        <div class="card-header">
                            <h5>Change Your Password</h5>
                        </div>
                        <div class="card-body">
                            <form id="change_password_form" class="needs-validation" novalidate="">

                                <div class="row form-row mb-3">
                                    <label class="col-sm-2 col-form-label" for="validationCustom1">Current
                                        Password</label>
                                    <div class="col-sm-9">
                                        <input type="password" class="form-control" id="validationCustom1" name="current_password" placeholder="Current Password" value="" required="" autocomplete="off">
                                        <div class="invalid-feedback">
                                            Please provide a valid current password.
                                        </div>
                                    </div>
                                </div>

                                <div class="row form-row mb-3">
                                    <label class="col-sm-2 col-form-label" for="validationCustom2">New Password</label>
                                    <div class="col-sm-9">
                                        <input type="password" class="form-control" id="validationCustom2" name="new_password" placeholder="New Password" value="" required="" autocomplete="off">
                                        <div class="invalid-feedback">
                                            Please provide a valid new password.
                                        </div>
                                    </div>
                                </div>

                                <div class="row form-row mb-3">
                                    <label class="col-sm-2 col-form-label" for="validationCustom3">Confirm New Password</label>
                                    <div class="col-sm-9">
                                        <input type="password" class="form-control" id="validationCustom3" name="confirm_password" placeholder="Confirm New Password" required="" autocomplete="off" auto>
                                        <div class="invalid-feedback">
                                            Please provide a valid confirm new password.
                                        </div>
                                    </div>
                                </div>

                                <div class="text-right float-right p-0 m-0 row">
                                    <button class="btn btn-primary" type="submit">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
                <!-- Container-fluid Ends-->
            </div>


            <!-- footer start-->
            <?php echo web_footer("user"); ?>
            <!--  -->

        </div>
    </div>




</body>

</html>