<?php

$active_page = "email-setting";
$base = "../../";
include $base . "db.php";
check_admin_login();

$query = mysqli_query($conn, "SELECT * FROM $setting_tbl");
$row = mysqli_fetch_array($query);
$mail_encryption = $row["mail_encryption"];
$mail_host = $row["mail_host"];
$mail_username = $row["mail_username"];
$mail_port = $row["mail_port"];
$mail_password = $row["mail_password"];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php echo web_metadata(); ?>
    <link rel="icon" href="<?php echo $images_base_url; ?>/web/favicon.png" type="image/x-icon">
    <link rel="shortcut icon" href="<?php echo $images_base_url; ?>/web/favicon.png" type="image/x-icon">
    <title>Dashboard - <?php echo $web_name; ?></title>
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
                                <h3>Email Setting</h3>
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
                        <div class="col-lg-6 col-md-12" style="min-height: 600px;">
                            <div class="card">
                                <div class="card-header justify-between">
                                    <h3>Email Settings</h3>
                                    <span data-form="#_email_setting_form" id="enable_form" class="c-pointer"><i class="material-icons-outlined">edit</i></span>
                                </div>
                                <h3 class="box-title"></h3>
                                <div class="card-body">
                                    <form disabled="disabled" id="_email_setting_form" class="needs-validation" novalidate>

                                        <div class="form-group">
                                            <label class="control-label">Encryption</label>
                                            <select disabled required name="mail_encryption" class="form-control">
                                                <option value="">Select</option>
                                                <option <?php if ($mail_encryption == "tls") {
                                                            echo "selected";
                                                        } ?> value="tls">TLS</option>
                                                <option <option <?php if ($mail_encryption == "ssl") {
                                                                    echo "selected";
                                                                } ?> value="ssl">SSL</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label">Mail Host</label>
                                            <input disabled required type="text" class="form-control" name="mail_host" placeholder="Mail Host" value="<?php echo $mail_host; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Mail Port</label>
                                            <input disabled data-validate="number" required type="text" class="form-control" name="mail_port" placeholder="Mail Port" value="<?php echo $mail_port; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Mail Username</label>
                                            <input disabled data-validate="email" required type="text" class="form-control" name="mail_username" placeholder="Mail Username" value="<?php echo $mail_username; ?>">
                                            <div class="invalid-feedback">Please provide a valid Mail Username</div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label">Mail Password</label>
                                            <input disabled required type="password" class="form-control" name="mail_password" placeholder="Mail Password" value="<?php echo $mail_password; ?>">
                                        </div>

                                        <div class="box-footer justify-right form-footer">
                                            <button type="button" class="btn btn-secondary mr-1" data-form="#_email_setting_form" id="enable_form">Close</button>
                                            <button type="submit" class="btn btn-primary">Save Changes</button>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- SEnd test Email -->
                        <div class="col-lg-6 col-md-12" style="min-height: 600px;">
                            <div class="card">
                                <div class="card-header">
                                    <h3>Send Test Email</h3>
                                    <span class="card-inner-text text-danger">You can send a test mail to check if your mail server is working.</span>
                                </div>
                                <h3 class="box-title"></h3>
                                <div class="card-body">
                                    <form id="_test_email_form" class="needs-validation" novalidate>
                                        <div class="form-group">
                                            <label class="control-label">Email Address</label>
                                            <input data-validate="email" required type="text" class="form-control" name="test_email" placeholder="Email Address">
                                            <div class="invalid-feedback">Please provide a valid Email Address</div>
                                        </div>
                                        <div class="box-footer justify-right">
                                            <button type="submit" class="btn btn-primary">Send Email</button>
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

    </div>

</body>

</html>