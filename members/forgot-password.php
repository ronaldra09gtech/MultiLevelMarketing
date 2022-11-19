<?php
include "../db.php";
is_user_already_loggedin();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php echo web_metadata(); ?>
    <title>Login - <?php echo $web_name; ?></title>
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
            <div class="w-100 card user-login-card">
                <div class="row">
                    <div class="col-md-6">
                        <section class="swiper-container login-swiper">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide" style="background-image:url(<?php echo $base_url; ?>/assets/images/web/login_1.jpg);"></div>
                                <div class="swiper-slide" style="background-image:url(<?php echo $base_url; ?>/assets/images/web/login_2.jpg);"></div>
                                <div class="swiper-slide" style="background-image:url(<?php echo $base_url; ?>/assets/images/web/login_3.jpg);"></div>
                            </div>
                            <div class="swiper-pagination"></div>
                        </section>
                    </div>
                    <div class="col-md-6">
                        <div class="brand-wrapper text-center">
                            <a href="<?php echo $base_url; ?>">
                                <img src="<?php echo web_logo(); ?>" alt="logo" class="logo">
                            </a>
                        </div>
                        <hr>
                        <div id="card_container" class="card-body">
                            <p class="login-card-description">Reset Password</p>
                            <p class="lead text-muted text-center mb-4">Enter the User ID or Username associated with your account.</p>
                            <form id="forgot_password_form" class="needs-validation" novalidate>
                                <div class="form-floating mb-3">
                                    <label for="user_id">User ID or Username</label>
                                    <input data-validate="alpha_numeric" required type="user_id" name="user_id" id="user_id" class="form-control" placeholder="User ID or Username">
                                    <div class="invalid-feedback">Please provide a valid User ID or Username.</div>
                                </div>
                                <button type="submit" class="m-0 btn btn-block btn-primary mb-4">Continue</button>
                            </form>
                            <p class="mt-2 text-center text-muted">Remembered password? <a href="<?php echo $base_url; ?>/login" class="link"> Login</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php echo web_footer("user"); ?>


</body>

</html>