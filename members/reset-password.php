<?php
include "../db.php";
is_user_already_loggedin();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php echo web_metadata(); ?>
    <title>Reset Password - <?php echo $web_name; ?></title>
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
                        <div class="card-body">
                            <p class="login-card-description">Set a new password</p>
                            <form id="reset_password_form" class="needs-validation" novalidate>
                                <div class="form-floating mb-3">
                                    <label for="user_id">User ID</label>
                                    <input disabled required type="text" name="user_id" id="user_id" class="form-control" placeholder="User ID">
                                    <div class="invalid-feedback">Please provide a valid User ID.</div>
                                </div>
                                <div class="form-floating mb-3">
                                    <label for="password">New Password</label>
                                    <input data-validate="password" required type="password" name="password" id="password" class="form-control" placeholder="***********">
                                    <div class="invalid-feedback">Please provide a valid password.</div>
                                </div>
                                <div class="form-floating mb-3">
                                    <label for="confirm_password">Confirm New Password</label>
                                    <input data-validate="confirm_password" required type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="***********">
                                    <div class="invalid-feedback">Please provide a valid password.</div>
                                </div>
                                <div class="form-floating mb-3">
                                    <label for="otp">Enter OTP</label>
                                    <input maxlength="6" data-validate="otp" required type="text" name="otp" id="otp" class="form-control" placeholder="123456">
                                    <div class="invalid-feedback">Please provide a valid otp.</div>
                                </div>
                                <button type="submit" class="m-0 btn btn-block btn-primary mb-4">Update Password</button>
                                <p class="mt-2 text-center text-muted">Remembered password? <a href="<?php echo $base_url; ?>/login" class="link"> Login</a></p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php echo web_footer("user"); ?>


</body>

</html>