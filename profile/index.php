<?php

$base = '../';
$active_page = "profile";
include $base . "db.php";
check_user_login();
$user_id = $loggedin_user_id;
$user_image = user_image($user_id);
$kyc = user_data($user_id)["kyc"];
if ($kyc == '2') {
    $kyc_label = ' <label class="badge alert-warning"><b>Pending</b></label>';
} else 
if ($kyc == '3') {
    $kyc_label = ' <label class="badge alert-success"><b>Approved</b></label>';
} else
if ($kyc == '4') {
    $kyc_label = ' <label class="badge alert-danger"><b>Rejected</b></label>';
} else {
    $kyc_label = ' <label class="badge alert-danger"><b>Not verified</b></label>';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
 
    <?php echo web_metadata(); ?>
   
    <title>Profile - <?php echo $web_name; ?></title>
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
                                <h3>Profile</h3>
                            </div>
                            <div class="col-6">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?php echo $base_url; ?>"><i class="material-icons-outlined">home</i> </a></li>
                                    <li class="breadcrumb-item active">Profile</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Container-fluid starts-->
                <div class="container-fluid">


                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="card card-body">
                                <div class="row">
                                    <div class="myprofile-card col-lg-4 border-right border-width-3">
                                        <div class="profile_img_container">
                                            <div id="profile_img_upload" class="img_center">
                                                <img style="width:100%" id="avatarimage" src="<?php echo $user_image; ?>"" alt="">
                                                <div class=" upload_container">
                                                <div class="img_layer"> </div>
                                                <div class="hover_icon"><i class="text-white material-icons-outlined" style="font-size: 100px;">file_upload</i></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="my-3 text-center">
                                        <h3><?php echo user_name($user_id); ?></h3>
                                        <h5><?php echo $user_id; ?></h5>
                                        <p>Registered On: <b><?php echo user_registration_date($user_id); ?></b></p>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="row">
                                        <!--  -->
                                        <div class="col-sm-6">
                                            <div class="card bg-success">
                                                <div class="card-body">
                                                    <div class="d-flex no-block align-items-center">
                                                        <div>
                                                            <h2 class="text-white"><?php echo user_referred_by($user_id); ?></h2>
                                                            <h6 class="text-white">Referral Id</h6>
                                                        </div>
                                                        <div class="ml-auto">
                                                            <span class="text-white"><i class="display-3 material-icons-outlined">person</i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--  -->
                                        <!--  -->
                                        <div class="col-sm-6">
                                            <div class="card bg-info">
                                                <div class="card-body">
                                                    <div class="d-flex no-block align-items-center">
                                                        <div>
                                                            <h2 class="text-white"><?php echo user_name(user_referred_by($user_id)); ?></h2>
                                                            <h6 class="text-white">Referral Username</h6>
                                                        </div>
                                                        <div class="ml-auto">
                                                            <span class="text-white"><i class="display-3 material-icons-outlined">person</i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--  -->
                                        <!--  -->
                                        <div class="col-sm-6">
                                            <div class="card bg-warning">
                                                <div class="card-body">
                                                    <div class="d-flex no-block align-items-center">
                                                        <div>
                                                            <h2 class="text-white"><?php echo user_left_count($user_id); ?></h2>
                                                            <h6 class="text-white">Left Count</h6>
                                                        </div>
                                                        <div class="ml-auto">
                                                            <span class="text-white"><i class=" display-3 material-icons-outlined">people</i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--  -->
                                        <!--  -->
                                        <div class="col-sm-6">
                                            <div class="card bg-orange">
                                                <div class="card-body">
                                                    <div class="d-flex no-block align-items-center">
                                                        <div>
                                                            <h2 class="text-white"><?php echo user_right_count($user_id); ?></h2>
                                                            <h6 class="text-white">Right Count</h6>
                                                        </div>
                                                        <div class="ml-auto">
                                                            <span class="text-white"><i class=" display-3 material-icons-outlined">people</i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--  -->
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h4 class="card-heading">Personal Details</h4>
                                <span data-form="#user_detail_form" id="enable_form" class="c-pointer"><i class="material-icons-outlined">edit</i></span>
                            </div>
                            <div class="card-body">
                                <form class="needs-validation" novalidate disabled="disabled" id="user_detail_form">

                                    <!--  -->
                                    <div class="col-lg-12 col-md-12 col-sm-12 mb-3">
                                        <label>Full Name </label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="material-icons-outlined">account_circle</i></span>
                                            </div>
                                            <input data-validate="alpha" required="" name="full_name" type="text" disabled="" value="<?php echo user_full_name($user_id); ?>" class="form-control" placeholder="Full Name" autocomplete="off">
                                            <div class="invalid-feedback">Please provide a valid Full Name</div>
                                        </div>
                                    </div>
                                    <!--  -->
                                    <div class="col-lg-12 col-md-12 col-sm-12 mb-3">
                                        <label>Gender</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="material-icons-outlined">account_circle</i></span>
                                            </div>
                                            <select required disabled="" class="form-control" name="user_gender" id="">
                                                <option value="">Select</option>
                                                <option <?php if (user_gender($user_id) == "male") {
                                                            echo "selected";
                                                        } ?> value="male">Male</option>
                                                <option <?php if (user_gender($user_id) == "female") {
                                                            echo "selected";
                                                        } ?> value="female">Female</option>
                                            </select>
                                            <div class="invalid-feedback">Please provide a valid Gender</div>
                                        </div>
                                    </div>
                                    <!--  -->
                                    <div class="col-lg-12 col-md-12 col-sm-12 mb-3">
                                        <label>Date Of Birth</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="material-icons-outlined">account_circle</i></span>
                                            </div>
                                            <input required="" name="user_dob" type="date" disabled="" value="<?php echo user_dob($user_id); ?>" class="form-control" placeholder="Date Of Birth" autocomplete="off">
                                            <div class="invalid-feedback">Please provide a valid Date Of Birth</div>
                                        </div>
                                    </div>
                                    <!--  -->

                                    <div class="col-lg-12 form-footer row justify-right">
                                        <button type="button" class="btn btn-secondary ml-1" data-form="#user_detail_form" id="enable_form">Close</button>
                                        <button class="btn btn-success ml-1">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>


                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h4 class="card-heading ">Contact Details</h4>
                                <span data-form="#user_adress_form" id="enable_form" class="c-pointer"><i class="material-icons-outlined">edit</i></span>
                            </div>
                            <div class="card-body">
                                <form class="needs-validation" novalidate disabled="disabled" id="user_adress_form">
                                    <!--  -->
                                    <div class="col-lg-12 col-md-12 col-sm-12 mb-3">
                                        <label>Address Line 1 </label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="material-icons-outlined">location_on</i></span>
                                            </div>
                                            <input required="" name="address_1" type="text" disabled="" value="<?php echo user_address_1($user_id); ?>" class="form-control" placeholder="Address 1" autocomplete="off">
                                            <div class="invalid-feedback">Please provide a valid Address</div>
                                        </div>
                                    </div>
                                    <!--  -->
                                    <!--  -->
                                    <div class="col-lg-12 col-md-12 col-sm-12 mb-3">
                                        <label>Address Line 2 </label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="material-icons-outlined">location_on</i></span>
                                            </div>
                                            <input required="" name="address_2" type="text" disabled="" value="<?php echo user_address_2($user_id); ?>" class="form-control" placeholder="Address 2" autocomplete="off">
                                            <div class="invalid-feedback">Please provide a valid Address</div>
                                        </div>
                                    </div>
                                    <!--  -->
                                    <!--  -->
                                    <div class="col-lg-12 col-md-12 col-sm-12 mb-3">
                                        <label>Country</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="material-icons-outlined">location_on</i></span>
                                            </div>
                                            <input data-validate="alpha" required="" name="user_country" type="text" disabled="" value="<?php echo user_country($user_id); ?>" class="form-control" placeholder="Country" autocomplete="off">
                                            <div class="invalid-feedback">Please provide a valid Country</div>
                                        </div>
                                    </div>
                                    <!--  -->
                                    <div class="col-lg-12 col-md-12 col-sm-12 mb-3">
                                        <label>State</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="material-icons-outlined">location_on</i></span>
                                            </div>
                                            <input data-validate="alpha" required="" name="user_state" type="text" disabled="" value="<?php echo user_state($user_id); ?>" class="form-control" placeholder="State" autocomplete="off">
                                            <div class="invalid-feedback">Please provide a valid State</div>
                                        </div>
                                    </div>
                                    <!--  -->
                                    <div class="col-lg-12 col-md-12 col-sm-12 mb-3">
                                        <label>City</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="material-icons-outlined">location_on</i></span>
                                            </div>
                                            <input data-validate="" required="" name="user_city" type="text" disabled="" value="<?php echo user_city($user_id); ?>" class="form-control" placeholder="City" autocomplete="off">
                                            <div class="invalid-feedback">Please provide a valid City</div>
                                        </div>
                                    </div>
                                    <!--  -->
                                    <div class="col-lg-12 col-md-12 col-sm-12 mb-3">
                                        <label>ZIP Code</label>
                                        <div class="input-group">
                                            <div class="input-group-prealpha_numericpend">
                                                <span class="input-group-text"><i class="material-icons-outlined">location_on</i></span>
                                            </div>
                                            <input maxlength="6" data-validate="number" required="" name="user_pincode" type="text" disabled="" value="<?php echo user_pin_code($user_id); ?>" class="form-control" placeholder="PIN Code" autocomplete="off">
                                            <div class="invalid-feedback">Please provide a valid PIN Code</div>
                                        </div>
                                    </div>
                                    <!--  -->
                                    <div class="col-lg-12 col-md-12 col-sm-12 mb-3">
                                        <label>Mobile Number</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="material-icons-outlined">phone</i></span>
                                            </div>
                                            <input data-validate="mobile_number" required="" name="user_mobile_number" type="text" disabled="" value="<?php echo user_mobile_number($user_id); ?>" class="form-control" placeholder="Mobile Number" autocomplete="off">
                                            <div class="invalid-feedback">Please provide a valid Mobile Number</div>
                                        </div>
                                    </div>
                                    <!--  -->
                                    <div class="col-lg-12 form-footer row justify-right">
                                        <button type="button" class="btn btn-secondary ml-1" data-form="#user_adress_form" id="enable_form">Close</button>
                                        <button class="btn btn-success ml-1">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!--  -->
                    <?php echo user_payment_gateways_card($user_id); ?>
                    <!--  -->

                </div>





            </div>
            <!-- Container-fluid Ends-->
        </div>


        <!-- footer start-->
        <?php echo web_footer("user"); ?>
        <!--  -->
        <script src="<?php echo $base_url; ?>/assets/js/bootstrap.min.js"></script>
        <script src="<?php echo $base_url; ?>/assets/js/cropper.js"></script>
    </div>
    </div>




    <!-- The Make Selection Modal -->
    <div class="modal" id="profileModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Make a selection</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <div id="cropimage">
                        <img id="imageprev" src="" />
                    </div>

                    <div style="display:none" class="progress">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                    </div>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <div class="col-12 row justify-right">
                        <div class="d-flex">
                            <button type="button" class="btn btn-secondary ml-1" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-success ml-1" id="saveAvatar">Save</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</body>

</html>
