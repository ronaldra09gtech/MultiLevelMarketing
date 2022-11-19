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
                        <div class="card-body">
                            <p class="login-card-description">Enter the otp</p>
                            <div class="msg"></div>
                            <form id="otp_form" class="needs-validation" novalidate>
                                <div class="otp-form-group form-row">
                                    <div class="col form-group">
                                        <input autofocus name="1" data-name="chars[1]" type="text" class="form-control text-center py-2" maxlength="1" required="" autocomplete="off">
                                    </div>
                                    <div class="col form-group">
                                        <input name="2" data-name="chars[2]" type="text" class="form-control text-center py-2" maxlength="1" required="" autocomplete="off">
                                    </div>
                                    <div class="col form-group">
                                        <input name="3" data-name="chars[3]" type="text" class="form-control text-center py-2" maxlength="1" required="" autocomplete="off">
                                    </div>
                                    <div class="col form-group">
                                        <input name="4" data-name="chars[4]" type="text" class="form-control text-center py-2" maxlength="1" required="" autocomplete="off">
                                    </div>
                                </div>
                                <button type="submit" class="m-0 btn btn-block btn-primary mb-4">Validate</button>
                            </form>
                            <p class="mt-2 text-center text-muted">Didn't get the code? <a id="resend_register_otp" class="link"> Resend it</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php echo web_footer("user"); ?>



</body>

</html>