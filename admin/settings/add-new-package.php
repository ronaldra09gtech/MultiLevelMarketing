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

    <title>Add New Package - <?php echo $web_name; ?></title>
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
                                <h3>Add New Package</h3>
                            </div>
                            <div class="col-6">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?php echo $base_url; ?>"><i class="material-icons-outlined">home</i> </a></li>
                                    <li class="breadcrumb-item"><a> Settings </a></li>
                                    <li class="breadcrumb-item active">Add New Package </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Container-fluid starts-->
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-header justify-between">
                            <h5>Add New Package </h5>
                        </div>
                        <div class="card-body">

                            <form data-action="add" id="_add_new_package" novalidate class="row needs-validation">
                                <input type="hidden" name="package_id" value="0">
                                <!--  -->
                                <div class="col-lg-6 mb-3">
                                    <label>Package</label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="material-icons-outlined">credit_card</i></span>
                                        </div>
                                        <input data-validate="alpha_numeric" required="" name="package" type="text" class="form-control" placeholder="Package">
                                        <div class="invalid-feedback">Please provide a valid Package</div>
                                    </div>
                                </div>
                                <!--  -->
                                <!--  -->
                                <div class="col-lg-6  mb-3">
                                    <label>Price</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><?php echo $web_currency; ?></span>
                                        </div>
                                        <input data-validate="number" required="" name="price" type="text" class="form-control" placeholder="Price">
                                        <div class="invalid-feedback">Please provide a valid Price</div>
                                    </div>
                                </div>
                                <!--  -->
                                <div class="col-lg-6  mb-3">
                                    <label>Minimum Withdrawal</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><?php echo $web_currency; ?></span>
                                        </div>
                                        <input data-validate="number" required="" name="minimum_withdraw" type="text" class="form-control" placeholder="Minimum Withdrawal">
                                        <div class="invalid-feedback">Please provide a valid Minimum Withdrawal</div>
                                    </div>
                                </div>
                                <!--  -->
                                <div class="col-lg-6  mb-3">
                                    <label>Withdrawal Charge</label>
                                    <div class="input-group">
                                        <input data-validate="decimal_numeric" required="" name="withdraw_charge" type="text" class="form-control" placeholder="Withdraw Charge">
                                        <div class="input-group-append">
                                            <span class="input-group-text">%</span>
                                        </div>
                                        <div class="invalid-feedback">Please provide a valid Withdraw Charge</div>
                                    </div>
                                </div>
                                <!--  -->
                                <div class="col-lg-6  mb-3">
                                    <label>Pair Income</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><?php echo $web_currency; ?></span>
                                        </div>
                                        <input data-validate="number" required="" name="pair_income" type="text" class="form-control" placeholder="Pair Income">
                                        <div class="invalid-feedback">Please provide a valid Pair Income</div>
                                    </div>
                                </div>
                                <!--  -->
                                <!--  -->
                                <div class="col-lg-6  mb-3">
                                    <label>Maximum Pair Income Per Day</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><?php echo $web_currency; ?></span>
                                        </div>
                                        <input data-validate="number" required="" name="maximum_pair_income" type="text" class="form-control" placeholder="Maximum Pair Income Per Day">
                                        <div class="invalid-feedback">Please provide a valid Maximum Pair Income</div>
                                    </div>
                                </div>
                                <!--  -->

                                <div class="col-lg-6  mb-3">
                                    <label> Validity <small>(in days)</small> </label>
                                    <div class="input-group">
                                        <input data-validate="number" required="" name="validity" type="text" class="form-control" placeholder="Validity 0 = Unlimited">
                                        <div class="input-group-append">
                                            <span class="input-group-text">Days</span>
                                        </div>
                                        <div class="invalid-feedback">Please provide a valid Validity</div>
                                    </div>
                                </div>
                                <!--  -->
                                <div class="col-lg-6  mb-3">
                                    <label>Status</label>
                                    <select required class="form-control" name="status" id="">
                                        <option selected disabled value="">Select</option>
                                        <option value="enable">Enable</option>
                                        <option value="disable">Disable</option>
                                    </select>
                                    <div class="invalid-feedback">Please provide a valid Status</div>
                                </div>
                                <!--  -->
                                <div class="col-lg-12">
                                    <div class="justify-right">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>


            </div>
            <!-- Container-fluid Ends-->
        </div>


        <!-- footer start-->
        <?php echo web_footer("admin"); ?>

    </div>



</body>

</html>