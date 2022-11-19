<?php
// 
$active_page = "basic-setting";
$base = "../../";
include $base . "db.php";
check_admin_login();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php echo web_metadata(); ?>
    <title>Basic Settings - <?php echo $web_name; ?></title>
    <?php include $base . "assets/css/css-files.php"; ?>
    <style>
        .logo-container {
            border: 1px solid #ddd;
            padding: 2px;
            margin: 12px 0;
            position: relative;
            width: max-content;
            background: #eee;
            max-width: 100%;
        }

        .preloader {
            height: 4px;
            background: rgb(var(--theme-default));
            width: 0;
            z-index: 1;
            position: absolute;
            left: 0;
            bottom: 0;
        }

        .logo-container img {
            max-height: 250px;
            max-width: 100%;
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
            <?php include $base . 'assets/nav/admin/sidebar.php'; ?>
            <!-- Page Sidebar Ends-->
            <div class="page-body">
                <div class="container-fluid">
                    <div class="page-title">
                        <div class="row">
                            <div class="col-6">
                                <h3>Basic Setting</h3>
                            </div>
                            <div class="col-6">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?php echo $base_url; ?>"><i class="material-icons-outlined">home</i> </a></li>
                                    <li class="breadcrumb-item"><a> Settings </a></li>
                                    <li class="breadcrumb-item active">Email Setting</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Container-fluid starts-->
                <div class="container-fluid">


                    <div class="row">
                        <div class="col-lg-6" style="min-height: 600px;">
                            <div class="card">
                                <div class="card-header justify-between">
                                    <h3>Basic Settings</h3>
                                    <span data-form="#_basic_setting_form" id="enable_form" class="c-pointer"><i class="material-icons-outlined">edit</i></span>
                                </div>
                                <h3 class="box-title"></h3>
                                <div class="card-body">
                                    <form disabled="disabled" id="_basic_setting_form" class="needs-validation" novalidate>

                                        <div class="form-group">
                                            <label class="control-label">Currency</label>
                                            <input disabled required type="text" class="form-control" name="currency" placeholder="Currency" value="<?php echo $web_currency; ?>">
                                            <div class="invalid-feedback">Please provide a valid Currency </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label">Currency Position</label>
                                            <select disabled required name="currency_position" class="form-control">
                                                <option value="">Select</option>
                                                <option <?php if ($web_currency_position == "prefix") {
                                                            echo "selected";
                                                        } ?> value="prefix">Prefix</option>
                                                <option <?php if ($web_currency_position == "suffix") {
                                                            echo "selected";
                                                        } ?> value="suffix">Suffix</option>
                                            </select>
                                            <div class="invalid-feedback">Please provide a valid Currency Position </div>
                                        </div>


                                        <div class="form-group">
                                            <label class="control-label">Primary Colour</label>
                                            <div class="input-group">
                                                <input disabled required type="color" class="form-control" name="primary_color" placeholder="Primary Colour" value="<?php echo convert_rgb_to_hex_color(web_primary_color()); ?>">
                                                <div class="input-group-append">
                                                    <input id="resetColor" data-color="<?php echo convert_rgb_to_hex_color($web_real_primary_color); ?>" type="button" value="Reset" class="btn btn-danger text-white input-group-text">
                                                </div>
                                                <div class="invalid-feedback">Please provide a valid Color </div>
                                            </div>

                                        </div>


                                        <div class="box-footer justify-right form-footer">
                                            <button type="button" class="btn btn-secondary mr-1" data-form="#_basic_setting_form" id="enable_form">Close</button>
                                            <button type="submit" class="btn btn-primary">Save Changes</button>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-heading">Logo Setting</h3>
                                </div>
                                <div class="card-body">

                                    <form id="changeLogoForm">
                                        <input accept="image/*" id="changeLogo" class="sr-only" type="file">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <b>Logo</b>
                                                <div class="logo-container">
                                                    <img src="<?php echo web_logo(); ?>" alt="">
                                                    <div class="preloader"></div>
                                                </div>
                                                <input value="<?php echo web_logo_id(); ?>" type="hidden" name="logo">
                                                <label data-id="logo" class="mb-4 py-2 btn btn-info">Change</label>
                                            </div>
                                            <div class="col-lg-6">
                                                <b>Logo Icon</b>
                                                <div class="logo-container">
                                                    <img src="<?php echo web_logo_icon(); ?>" alt="">
                                                    <div class="preloader"></div>
                                                </div> <input value="<?php echo web_logo_icon_id(); ?>" type="hidden" name="logo_icon">
                                                <label data-id="logo_icon" class="mb-4 py-2 btn btn-info">Change</label>
                                            </div>
                                            <div class="col-lg-6">
                                                <b>Favicon</b>
                                                <div class="logo-container">
                                                    <img src="<?php echo web_favicon(); ?>" alt="">
                                                    <div class="preloader"></div>
                                                </div> <input value="<?php echo web_favicon_id(); ?>" type="hidden" name="favicon">
                                                <label data-id="favicon" class="mb-4 py-2 btn btn-info">Change</label>
                                            </div>
                                        </div>
                                        <div class="justify-right">
                                            <button type="submit" class="btn btn-success">Save Changes</button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>


            </div>
            <!-- Container-fluid Ends-->
        </div>


        <!-- footer start-->
        <?php echo web_footer("admin"); ?>
        <script>
            $("#resetColor").on("click", function() {
                let $primary_color = $(this).data("color");
                $("input[name=primary_color]").val($primary_color);
            })

            ChangeLogo.init();
        </script>
    </div>

</body>

</html>