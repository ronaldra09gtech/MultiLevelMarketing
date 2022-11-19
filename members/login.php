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
                            <p class="login-card-description">Welcome to <?php echo $web_name; ?></p>
                            <div class="msg"></div>
                            <form id="login_form" class="needs-validation" novalidate>
                                <div class="form-floating mb-3">
                                    <label for="user_id">Username or User ID</label>
                                    <input autofocus value="" data-validate="alpha_numeric" required type="text" name="user_id" id="user_id" class="form-control" placeholder="10060**">
                                    <div class="invalid-feedback">Please provide a valid username / userid.</div>
                                </div>
                                <div class="form-floating mb-3">
                                    <label for="password">Password</label>
                                    <input value="" required type="password" name="password" id="password" class="form-control" placeholder="***********">
                                    <div class="invalid-feedback">Please provide a valid password.</div>
                                </div>

                                <div class=" mb-3 row">
                                    <div class="col-sm">
                                        <div class="form-check custom-control custom-checkbox">
                                            <input checked="" id="rememberme" class="custom-control-input" type="checkbox">
                                            <label class="custom-control-label" for="rememberme">Remember Me</label>
                                        </div>
                                    </div>
                                    <div class="col-sm text-right"><a class="link" href="<?php echo $base_url; ?>/forgot-password">Forgot Password?</a></div>
                                </div>
                                <button type="submit" class="m-0 btn btn-block btn-primary mb-4">Login</button>
                            </form>
                            <p class="mt-2 text-center text-muted">Don't have an account? <a href="<?php echo $base_url; ?>/register" class="link"> Register</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php echo web_footer("user"); ?>


</body>

</html>
