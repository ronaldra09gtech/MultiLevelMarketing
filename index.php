<?php
$active_page = "profile";
include "db.php";

is_user_already_loggedin();

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <?php echo web_metadata(); ?>
    <title>Home - <?php echo $web_name; ?></title>
    <?php include "assets/css/css-files.php"; ?>
    <link rel="stylesheet" href="<?php echo $base_url . '/assets/css/home.css'; ?>">
</head>

<body>

    <header>
        <div class="header-bottom">
            <div class="container">
                <div class="header-area">
                    <div class="logo">
                        <a href="<?php echo $base_url; ?>"><img src="<?php echo web_logo(); ?>" alt="logo"></a>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- ============Header Section Ends Here============ -->

    <section class="banner-slider">
        <div class="swiper-wrapper">
            <div class="swiper-slide">
                <div class="banner-container bg-overlay bg_img" style='background-image: url("<?php echo $images_base_url; ?>/web/swiper_1_home.jpg")'>
                    <div class="container">
                        <div class="banner-content">
                            <h3 class="sub-title">PilaMoko</h3>
                            <h1 class="title">TLPneurship Program</h1>
                            <div class="button-area">
                                <p>To make apportunity for some people start to be their business</p>
                                <div class="button-group">
                                    <a href="register" class="custom-button active">Register</a>
                                    <a href="login" class="custom-button">Login</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="swiper-slide">
                <div class="banner-container bg-overlay bg_img" style='background-image: url("<?php echo $images_base_url; ?>/web/swiper_2_home.jpg")'>
                    <div class="container">
                        <div class="banner-content">
                            <h3 class="sub-title">PilaMoko</h3>
                            <h1 class="title">TLPneurship Program</h1>
                            <div class="button-area">
                                <p>To help and develop your Entrepreneurship skill.</p>
                                <div class="button-group">
                                    <a href="register" class="custom-button active">Register</a>
                                    <a href="login" class="custom-button">Login</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </section>


    <!-- ============Footer Section Starts Here============ -->

    <footer>
        <div class="footer-top">
            <div class="container">
                <div class="logo">
                    <a href="<?php echo $base_url; ?>"><img src="<?php echo web_logo(); ?>" alt="logo"></a>
                </div>
                <p>We are a worldwide trusted company. We provide secured website with a user-friendly interface and anytime support .
                    Be a part of our community today by investing your money and getting a good profit form us.
                </p>
                <ul class="social-icons">


                    <ul class="social-icons">
                        <li><a href="https://api.whatsapp.com/send?phone=+919771701893" target="_blank" title="telegram"><i class="fa fa-whatsapp"></i></a></li>
                        <li><a href="https://jamsrworld.com/" target="_blank" title="youtube"><i class="fa fa-instagram"></i></a></li>
                        <li><a href="https://www.youtube.com/watch?v=OxfCD4oTH8Q" target="_blank" title="youtube"><i class="fa fa-youtube"></i></a></li>
                    </ul>

                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>Copyright © 2021 - <a class="link" href="">Platinum R1-IT Solutions Services Ltd. Pvt.</a> All Rights Reserved.</p>
        </div>
    </footer>
    <!-- ============Footer Section Ends Here============ -->

    <script src="<?php echo $base_url; ?>/assets/js/jquery.min.js"></script>
    <script src="<?php echo $base_url; ?>/assets/js/bootstrap.min.js"></script>
    <script src="<?php echo $base_url; ?>/assets/js/swiper.js"></script>
    <script>
        var swiper = new Swiper(' .banner-slider', {
            loop: true,
            slidesPerView: 1,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            autoHeight: true,
            navigation: {
                nextEl: '.banner-next',
                prevEl: '.banner-prev',
            },
            // pagination: true,
            speed: 1000,
        });
    </script>


</body>

</html>