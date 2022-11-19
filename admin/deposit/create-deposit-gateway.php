<?php

$base = '../../';
$active_page = "manual-methods";
include $base . "db.php";
check_admin_login();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php echo web_metadata(); ?>
    <title>Create Deposit Gateway - <?php echo $web_name; ?></title>
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
               <?php include $base."assets/nav/admin/header.php"; ?>
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
                                <h3>Create New Deposit Gateway</h3>
                            </div>
                            <div class="col-6">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?php echo $admin_base_url; ?>"><i class="material-icons-outlined">home</i> </a></li>
                                    <li class="breadcrumb-item active">Gateway</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Container-fluid starts-->
                <div class="container-fluid">
                    <form data-action="create" class="needs-validation pb-5" novalidate id="_create_deposit_method_form">
                        <div class=" row">
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title"> New Deposit Method </h4>
                                </div>
                                <div class="card-body">

                                    <div class="row">
                                        <div class="col-lg-4 justify-align-center">
                                            <div class="gateway-image">
                                                <img id="gateway_img_preview" class="img-fluid" src="" alt="">
                                                <label for="gateway_img">
                                                    <i class="text-white material-icons-outlined" style="font-size: 100px;">file_upload</i>
                                                    <input class="sr-only" type="file" id="gateway_img" accept="image/*">
                                                    <input class="sr-only" id="gateway_file_name" name="gateway_img" value="" type="text">
                                                    <input class="d-none" name="gateway_id" value="0" type="text">
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-lg-8">
                                            <div class="row">
                                                <!--  -->
                                                <div class="col-lg-6 mb-3">
                                                    <label>Gateway Name</label>

                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="material-icons-outlined">credit_card</i></span>
                                                        </div>
                                                        <input data-validate="alpha" required="" name="gateway_name" type="text" class="form-control" placeholder="Gateway Name" autocomplete="off">
                                                        <div class="invalid-feedback">Please provide a valid Gateway Name</div>
                                                    </div>
                                                </div>
                                                <!--  -->

                                                <div class="col-lg-6  mb-3">
                                                    <label>Processing Time</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><i class="material-icons-outlined">schedule</i></span>
                                                        </div>
                                                        <input data-validate="alpha_numeric" required="" name="processing_time" type="text" class="form-control" placeholder="Processing Time" autocomplete="off">
                                                        <div class="invalid-feedback">Please provide a valid Processing Time</div>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6  mb-3">
                                                    <label>Status</label>
                                                    <select required name="status" class="form-control" autocomplete="off">
                                                        <option value="">Select</option>
                                                        <option value="active">Active</option>
                                                        <option value="inactive">Inactive</option>
                                                    </select>
                                                    <div class="invalid-feedback">Please provide a valid Status</div>
                                                </div>


                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                        <!--  -->
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Deposit Details</h4>
                                </div>
                                <div id="_deposit_detail_input_container" class="card-body">
                                    <div class="row">
                                        <div class="col-5"><label>Label</label></div>
                                        <div class="col-5"><label>Value</label></div>
                                        <div class="col-2"><label>Action</label></div>
                                    </div>
                                    <div class="row">

                                        <!--  -->
                                        <div class="col-lg-5 mb-3">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="material-icons-outlined">attach_money</i></span>
                                                </div>
                                                <input data-validate="alpha" required="" name="detail_label[]" type="text" class="form-control" placeholder="Add label" autocomplete="off">
                                                <div class="invalid-feedback">Please provide a valid Label</div>
                                            </div>
                                        </div>
                                        <!--  -->
                                        <div class="col-lg-5  mb-3">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="material-icons-outlined">attach_money</i></span>
                                                </div>
                                                <input required="" name="detail_value[]" type="text" class="form-control" placeholder="Value" autocomplete="off">
                                                <div class="invalid-feedback">Please provide a valid Value</div>
                                            </div>
                                        </div>
                                        <!--  -->
                                        <div class="col-lg-2 mb-3">
                                            <button type="button" id="_add_deposit_detail_row" class="btn btn-success align-center"><i class="fa fa-plus"></i></button>
                                        </div>
                                        <!--  -->

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--  -->


                </div>
                <div>
                    <button type="submit" class="btn btn-primary pull-right">Submit</button>
                </div>

                </form>
            </div>
            <!-- Container-fluid Ends-->
        </div>


        <!-- footer start-->
        <?php echo web_footer("admin"); ?>
        <!--  -->
        <script src="<?php echo $base_url; ?>/assets/js/jquery.dataTables.min.js"></script>
        <script>
            init_datatbl();
        </script>
    </div>
    </div>




</body>

</html>