<?php
include "../db.php";

if (!is_admin_exist()) {
    locate_to($admin_base_url . '/create');
}



is_admin_already_loggedin();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php echo web_metadata(); ?>
    <title>Admin Login - <?php echo $web_name; ?></title>
    <style>
        .footer {
            display: none;
        }
    </style>
    <?php include("../assets/css/css-files.php"); ?>
</head>

<body>
    <!-- Preloader End -->
    <div id="main-wrapper" class="login-register h-100 d-flex flex-column primary-bg">
        <div class="container login-container  user-login-container">
            <div style="border-radius: 27.5px;" class="w-100 card user-login-card">
                <div class="row no-gutters">
                    <div class="col-md-6">
                        <img src="<?php echo $base_url; ?>/assets/images/web/login_4.jpg" alt="login" class="login-card-img">
                    </div>
                    <div class="col-md-6">
                        <div class="brand-wrapper text-center">
                            <a href="<?php echo $seller_base_url; ?>">
                                <img src="<?php echo web_logo(); ?>" alt="logo" class="logo">
                            </a>
                        </div>
                        <hr>
                        <div class="card-body">
                            <p class="login-card-description">Welcome to <?php echo $web_name; ?> admin panel</p>
                            <form id="_login_form" class="needs-validation" novalidate>
                                <div class="form-group">
                                    <label for="user_id">User ID or Username</label>
                                    <input autofocus value="" data-validate="alpha_numeric" required type="text" name="user_id" id="user_id" class="form-control" placeholder="User ID or Username">
                                    <div class="invalid-feedback">Please provide a valid User ID or Username.</div>
                                </div>
                                <div class="form-group mt-2">
                                    <label for="password">Password</label>
                                    <input value="" required type="password" name="password" id="password" class="form-control" placeholder="***********">
                                    <div class="invalid-feedback">Please provide a valid password.</div>
                                </div>

                                <div class=" mb-4 row">
                                    <div class="col-sm">
                                        <div class="form-check custom-control custom-checkbox">
                                            <input checked="" id="rememberme" class="custom-control-input link" type="checkbox">
                                            <label class="custom-control-label" for="rememberme">Remember Me</label>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-info btn-block">Login</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <?php echo web_footer("admin"); ?>
</body>

</html>
