<?php
$REQUEST_URI = explode("/", $_SERVER["REQUEST_URI"]);
$web_name = $REQUEST_URI[1];
$web_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/$web_name";

$installer_page = true;
include "../../db.php";
if ($has_db_created == "true") {
    http_response_404();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/font-awesome.css">
    <link rel="stylesheet" href="../../assets/css/bootstrap.css">
    <link rel="stylesheet" href="../install.css">
    <link rel="stylesheet" href="../../assets/css/sweetalert.css">
</head>

<body>
    <div class="page-wrapper compact-wrapper" id="pageWrapper">
        <div class="page-body-wrapper">
            <div class="container-fluid">
                <div class="row wizard-setup">
                    <div class="col-sm-12 col-lg-5 col-xl-3">
                        <div class="card">
                            <div class="card-header">
                                <h1 class="text-center">Jamsmlm</h1>
                                <h5 class="text-center">Welcome to the Installer</h5>
                            </div>
                            <div class="card-body">
                                <div class="f1" method="post">
                                    <div class="f1-steps">
                                        <div class="f1-progress">
                                            <div class="f1-progress-line" data-now-value="16.66" data-number-of-steps="3"></div>
                                        </div>
                                        <div class="f1-step active">
                                            <div class="f1-step-icon"><i class="fa fa-code"></i></div>
                                            <p>Start</p>
                                        </div>
                                        <div class="f1-step">
                                            <div class="f1-step-icon"><i class="fa fa-database"></i></div>
                                            <p>Database</p>
                                        </div>
                                        <div class="f1-step">
                                            <div class="f1-step-icon">
                                                <i class="fa fa-user"></i>
                                            </div>
                                            <p>Admin</p>
                                        </div>
                                    </div>

                                    <!--  -->
                                    <fieldset style="display: block;">
                                        <form id="setup_from" method="post">
                                            <input value="licence_code" name="action" type="hidden">
                                            <div class="mb-4">
                                                <label for="f1-first-name">License Code</label>
                                                <input placeholder="License Code" name="license_code" required class="form-control">
                                                <div class="invalid-feedback">Please provide a valid License Code</div>
                                            </div>
                                            <div class="justify-right">
                                                <button type="submit" class="btn btn-primary">Next</button>
                                            </div>
                                        </form>
                                        <div class="f1-buttons">
                                            <button class="d-none btn btn-primary btn-next" type="button">Next</button>
                                        </div>
                                    </fieldset>
                                    <!--  -->

                                    <!--  -->
                                    <fieldset>

                                        <form id="setup_from" method="post">
                                            <input value="database" name="action" type="hidden">
                                            <div class="form-group">
                                                <label for="host">Host</label>
                                                <input id="host" type="text" class="form-control form-input" name="db_host" placeholder="Host" value="localhost" required="">
                                                <div class="invalid-feedback">Please provide a valid Host</div>
                                            </div>
                                            <div class="form-group">
                                                <label for="database_name">Database Name</label>
                                                <input id="database_name" type="text" class="form-control form-input" name="db_name" placeholder="Database Name" value="" required="">
                                                <div class="invalid-feedback">Please provide a valid Database Name</div>

                                            </div>
                                            <div class="form-group">
                                                <label for="user_name">Database Username</label>
                                                <input id="user_name" type="text" class="form-control form-input" name="db_user" placeholder="Database Username" value="root" required="">
                                                <div class="invalid-feedback">Please provide a valid Database Username</div>

                                            </div>
                                            <div class="form-group">
                                                <label for="password">Database Password</label>
                                                <input id="password" type="password" class="form-control form-input" name="db_password" placeholder="Database Password" value="">
                                                <div class="invalid-feedback">Please provide a valid Database Password</div>
                                            </div>
                                            <input value="<?php echo $web_url; ?>" type="hidden" name="base_url">
                                            <div class="justify-right mt-4">
                                                <button class="btn btn-primary btn-previous" type="button">Previous</button>
                                                <button type="submit" class="ml-3 btn btn-primary">Next</button>
                                            </div>
                                        </form>
                                        <div class="f1-buttons">
                                            <button class="d-none btn btn-primary btn-next" type="submit">Next</button>
                                        </div>
                                    </fieldset>
                                    <!--  -->

                                    <!--  -->
                                    <fieldset>
                                        <form id="setup_from" method="post">
                                            <input value="admin_data" name="action" type="hidden">
                                            <div class="form-group">
                                                <label for="host">Username</label>
                                                <input type="text" class="form-control form-input" name="user_name" placeholder="Username" value="" required="">
                                                <div class="invalid-feedback">Please provide a valid Username</div>
                                            </div>
                                            <div class="form-group">
                                                <label for="database_name">Email</label>
                                                <input id="database_name" type="text" class="form-control form-input" name="email" placeholder="Email" value="" required="">
                                                <div class="invalid-feedback">Please provide a valid Email</div>

                                            </div>
                                            <div class="form-group">
                                                <label for="user_name">Password</label>
                                                <input required id="user_name" type="password" class="form-control form-input" name="password" placeholder="Password" value="">
                                                <div class="invalid-feedback">Please provide a valid Password</div>

                                            </div>
                                            <div class="form-group">
                                                <label for="password">Confirm Password</label>
                                                <input required id="password" type="password" class="form-control form-input" name="confirm_password" placeholder="Confirm Password" value="">
                                                <div class="invalid-feedback">Please provide a valid Confirm Password</div>
                                            </div>
                                            <div class="f1-buttons">
                                                <button class="btn btn-primary btn-previous" type="button">Previous</button>
                                                <button class="btn btn-primary btn-submit" type="submit">Submit</button>
                                            </div>
                                        </form>
                                    </fieldset>
                                    <!--  -->


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</body>

<script src="../../assets/js/jquery.min.js"></script>
<script src="../../assets/js/sweetalert.js"></script>
<script src="../../assets/js/functions.js"></script>
<script src="../../assets/js/bootstrap.min.js"></script>
<script src="../install.js"></script>

</html>