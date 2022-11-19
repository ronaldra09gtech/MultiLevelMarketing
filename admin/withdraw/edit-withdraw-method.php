<?php

$base = '../../';
$active_page = "withdraw-methods";
include $base . "db.php";
check_admin_login();

$gateway_id = $_GET["id"];
$query = mysqli_query($conn, "SELECT * FROM $withdraw_method_tbl WHERE gateway_id = '$gateway_id'  ");
if (!mysqli_num_rows($query)) {
    http_response_404();
}
$row = mysqli_fetch_array($query);
$gateway_image_id = $row["gateway_image"];
$gateway_name  = $row["gateway_name"];
$processing_time = $row["processing_time"];
$status = $row["status"];

$requirement_card_heading = $row["requirement_card_heading"];
$gateway_image = get_image_src($gateway_image_id);
$withdraw_requirement_heading = is_empty($requirement_card_heading) ? "Withdraw requirements" : $requirement_card_heading;
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <?php echo web_metadata(); ?>

    <title>Edit Withdraw Method - <?php echo $web_name; ?></title>
    <link rel="stylesheet" href="<?php echo $base_url; ?>/assets/css/datatables.css">
    <?php include $base . "assets/css/css-files.php"; ?>
    <style>
        .wrapper .table {
            background: #fff;
            padding: 30px 30px;
            position: relative;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            margin: 0 0 20px 0px;
            border-radius: 20px;
        }

        .table .price-section {
            display: flex;
            justify-content: center;
        }

        .table .price-area {
            height: 120px;
            width: 120px;
            border-radius: 50%;
            padding: 2px;
        }

        .price-area .inner-area {
            height: 100%;
            width: 100%;
            border-radius: 50%;
            line-height: 117px;
            text-align: center;
            color: #fff;
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        .price-area .inner-area .text {
            font-size: 25px;
            font-weight: 400;
            position: absolute;
            top: -34px;
            left: 50px
        }

        .price-area .inner-area .price {
            font-size: 30px;
            font-weight: 500;
            margin-top: 20px;
        }

        .table .package-name {
            width: 100%;
            height: 2px;
            margin: 35px 0;
            position: relative;
        }

        .table .package-name span {
            position: absolute;
            top: 50%;
            left: 50%;
            font-size: 25px;
            font-weight: 500;
            background: #fff;
            padding: 0 15px;
            transform: translate(-50%, -50%);
        }

        .table .features li {
            margin-bottom: 15px;
            list-style: none;
            display: flex;
            justify-content: space-between;
        }

        .features li .list-name {
            font-size: 17px;
            font-weight: 400;
        }

        .features li .icon {
            font-size: 15px;
        }

        .features li .icon.check {
            color: #2db94d;
        }

        .features li .icon.cross {
            color: #cd3241;
        }

        .basic ::selection,
        .basic .price-area,
        .basic .inner-area {
            background: #43ef8b;
        }

        .basic .package-name {
            background: #baf8d4;
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
                                <h3>Edit Withdraw Method</h3>
                            </div>
                            <div class="col-6">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?php echo $admin_base_url; ?>"><i class="material-icons-outlined">home</i> </a></li>
                                    <li class="breadcrumb-item active">Withdraw Methods</li>
                                    <li class="breadcrumb-item active">Withdraw Methods</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Container-fluid starts-->
                <div class="container-fluid">
                    <form data-action="edit" class="needs-validation pb-5" novalidate id="create_withdraw_method_form">
                        <div class=" row">
                            <div class="col-lg-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title"> Edit Withdraw Method </h4>
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
                                                            <option <?php if ($status == "enabled") {
                                                                        echo "selected";
                                                                    } ?> value="active">Active</option>
                                                            <option <?php if ($status == "disabled") {
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
                            <div class="col-lg-4">
                                <div class="wrapper">
                                    <div class="table basic">
                                        <div class="price-section">
                                            <div class="price-area">
                                                <div class="inner-area">
                                                    <span style="font-size: 100px;" class="material-icons-outlined">paid</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="package-name"><span>Charge</span></div>
                                        <ul class="features">
                                            <?php echo _all_packages_charge(); ?>

                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!--  -->
                            <div class="col-lg-8 col-xl-4">
                                <div id="withdraw_requirements_card" class="card">
                                    <div class="card-header justify-between">
                                        <h3><?php echo $withdraw_requirement_heading; ?></h3>
                                        <div class="d-flex">
                                            <button id="_add_withdraw_req_row" type="button" class="btn btn-success mr-3"><i class="fa fa-plus"></i></button>
                                            <button id="_add_gateway_image_chooser" type="button" class="btn btn-info">Add Image Chooser</button>
                                        </div>
                                    </div>
                                    <div class="card-body">

                                        <div class="col-lg-12 mb-3">
                                            <label>Card Heading </label> <input value="<?php echo $requirement_card_heading; ?>" data-validate="alpha" required="" name="requirement_card_heading" type="text" class="form-control" placeholder="Card Heading" autocomplete="off">
                                        </div>

                                        <?php echo _withdraw_requirements($gateway_id); ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <button id="_delete_withdraw_method" type="button" class="btn btn-primary">Delete</button>
                            <button type="submit" class="btn btn-success pull-right">Update</button>
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