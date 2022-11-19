<?php

$base = '../../';
$active_page = "manual-methods";
include $base . "db.php";
check_admin_login();

$gateway_id = $_GET["id"];
$query = mysqli_query($conn, "SELECT * FROM $manual_deposit_method WHERE gateway_id = '$gateway_id'  ");
if (!mysqli_num_rows($query)) {
    http_response_404();
}
$row = mysqli_fetch_array($query);
$gateway_image_id = $row["gateway_image"];
$gateway_name  = $row["gateway_name"];
$processing_time = $row["processing_time"];
$status = $row["status"];
$deposit_details = $row["deposit_details"];
$gateway_image = get_image_src($gateway_image_id);

$deposit_details = unserialize($deposit_details);

$count = 0;
$detail_fields = '';
foreach ($deposit_details["label"] as $key =>  $label_text) {
    $count++;
    if ($count == '1') {
        $btn = '<button type="button" id="_add_deposit_detail_row" class="btn btn-success align-center"><i class="fa fa-plus"></i></button>';
    } else {
        $btn = '<button type="button" data-element="1" id="remove_element" class="btn btn-danger align-center"><i class="fa fa-minus"></i></button> ';
    }
    $detail_value = $deposit_details["value"][$key];
    $detail_fields .= '  <div class="col-lg-5 mb-3">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="material-icons-outlined">attach_money</i></span>
                                                    </div>
                                                    <input value="' . $label_text . '" data-validate="alpha" required="" name="detail_label[]" type="text" class="form-control" placeholder="Add label" autocomplete="off">
                                                    <div class="invalid-feedback">Please provide a valid Label</div>
                                                </div>
                                            </div>
                                         <div class="col-lg-5  mb-3">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="material-icons-outlined">attach_money</i></span>
                                                    </div>
                                                    <input value="' . $detail_value . '" required="" name="detail_value[]" type="text" class="form-control" placeholder="Value" autocomplete="off">
                                                    <div class="invalid-feedback">Please provide a valid Value</div>
                                                </div>
                                            </div><div class="col-lg-2 mb-3">' . $btn . '
  </div>';
}

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <?php echo web_metadata(); ?>

    <title>Edit Deposit Methods - <?php echo $web_name; ?></title>
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
                                <h3>Edit Deposit Gateway</h3>
                            </div>
                            <div class="col-6">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?php echo $admin_base_url; ?>"><i class="material-icons-outlined">home</i> </a></li>
                                    <li class="breadcrumb-item active">Edit Deposi Gateway</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Container-fluid starts-->
                <div class="container-fluid">
                    <form data-action="edit" class="needs-validation pb-5" novalidate id="_create_deposit_method_form">
                        <div class=" row">
                            <div class="col-lg-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title"> Edit Deposit Method </h4>
                                    </div>
                                    <div class="card-body">

                                        <div class="row">
                                            <div class="col-lg-4 justify-align-center">
                                                <div class="gateway-image">
                                                    <img id="gateway_img_preview" class="img-fluid" src="<?php echo $gateway_image; ?>" alt="">
                                                    <label for="gateway_img">
                                                        <i class="text-white material-icons-outlined" style="font-size: 100px;">file_upload</i>
                                                        <input class="sr-only" type="file" id="gateway_img" accept="image/*">
                                                        <input class="sr-only" id="gateway_file_name" name="gateway_img" value="<?php echo $gateway_image_id; ?>" type="text">
                                                        <input class="d-none" name="gateway_id" value="<?php echo $gateway_id; ?>" type="text">
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
                                                            <input value="<?php echo $gateway_name; ?>" data-validate="alpha" required="" name="gateway_name" type="text" class="form-control" placeholder="Gateway Name" autocomplete="off">
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
                                                            <input value="<?php echo $processing_time; ?>" data-validate="alpha_numeric" required="" name="processing_time" type="text" class="form-control" placeholder="Processing Time" autocomplete="off">
                                                            <div class="invalid-feedback">Please provide a valid Processing Time</div>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-6  mb-3">
                                                        <label>Status</label>
                                                        <select required name="status" class="form-control" autocomplete="off">
                                                            <option value="">Select</option>
                                                            <option <?php if ($status == "active") {
                                                                        echo "selected";
                                                                    } ?> value="active">Active</option>
                                                            <option <?php if ($status == "inactive") {
                                                                        echo "selected";
                                                                    } ?> value="inactive">Inactive</option>
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
                                            <?php echo $detail_fields; ?>


                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--  -->


                        </div>
                        <div class="justify-right py-2">
                            <button id="_delete_deposit_gateway" type="button" class="btn btn-danger">Delete</button>
                            <button type="submit" class="btn btn-success ml-3">Submit</button>
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