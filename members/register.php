<?php
include "../db.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php echo web_metadata(); ?>
    <title>Register - <?php echo $web_name; ?></title>
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
                <div class="brand-wrapper text-center">
                    <a href="<?php echo $base_url; ?>">
                        <img src="<?php echo web_logo(); ?>" alt="logo" class="logo">
                    </a>
                </div>
                <hr>
                <span class="login-card-description">Register to <?php echo $web_name; ?></span>
                <div class="card-body">
                    <form id="registration_form" class="needs-validation" novalidate>
                        <div class="row">
                            <!--  -->
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <label for="referral_id">Referral Id</label>
                                    <input data-validate="number" required type="text" name="referral_id" id="referral_id" class="form-control" placeholder="Referral Id">
                                    <div class="invalid-feedback">Please provide a valid Referral Id.</div>
                                    <div class="valid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <label for="referral_name">Referral Name</label>
                                    <input disabled data-validate="alpha" required type="text" name="referral_name" id="referral_name" class="form-control" placeholder="Referral Name">
                                    <div class="invalid-feedback">Please provide a valid Referral Name.</div>
                                    <div class="valid-feedback"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <label for="placement_id">Placement Id</label>
                                    <input data-validate="number" required type="text" name="placement_id" id="placement_id" class="form-control" placeholder="Placement Id">
                                    <div class="invalid-feedback">Please provide a valid Placement Id.</div>
                                    <div class="valid-feedback"></div>

                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <label for="placement_name"> Placement User Name </label>
                                    <input disabled data-validate="alpha" required type="text" name="placement_name" id="placement_name" class="form-control" placeholder="Placement User Name">
                                    <div class="invalid-feedback">Please provide a valid Placement User Name.</div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-floating">
                                    <label for="placement_type">Placement Type</label>
                                    <select readonly placeholder="Select" name="placement_type" id="placement_type" class="form-control" required>
                                        <option selected value="">Select Placement Type</option>
                                        <option value="left">Left</option>
                                        <option value="right">Right</option>
                                    </select>
                                    <div class="invalid-feedback">Please provide a valid Placement Type.</div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <label for="user_name">Username</label>
                                    <input data-validate="alpha" required type="text" name="user_name" id="user_name" class="form-control" placeholder="Username">
                                    <div class="invalid-feedback">Please provide a valid Username.</div>
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <label for="full_name">Full Name</label>
                                    <input data-validate="alpha" required type="text" name="full_name" id="full_name" class="form-control" placeholder="Full Name">
                                    <div class="invalid-feedback">Please provide a valid Full Name.</div>
                                </div>
                            </div> <!--  -->


                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <label for="mobile_number">Mobile Number</label>
                                    <input data-validate="number" required type="text" maxlength="10" name="mobile_number" id="mobile_number" class="form-control" placeholder="9123456789">
                                    <div class="invalid-feedback">Please provide a valid Mobile Number.</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <label for="user_email">Email</label>
                                    <input data-validate="email" required type="email" name="user_email" id="user_email" class="form-control" placeholder="xyz@gmail.com">
                                    <div class="invalid-feedback">Please provide a valid Email.</div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3 floating-btn-container">
                                <div class="w-100 form-floating">
                                    <label for="otp">Enter Otp</label>
                                    <input data-validate="number" required type="text" maxlength="6" name="otp" id="otp" class="form-control" placeholder="Enter Otp">
                                    <div class="invalid-feedback">Please provide a valid Otp.</div>
                                </div>
                                <button id="send_registration_otp" class="floating-btn btn btn-info" type="button">Send Otp</button>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <label for="password">Password</label>
                                    <input data-validate="password" required type="password" name="password" id="password" class="form-control" placeholder="Password">
                                    <div class="invalid-feedback">Please provide a valid Password.</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <label for="confirm_password">Confirm Password</label>
                                    <input data-validate="confirm_password" required type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm Password">
                                    <div class="invalid-feedback">Please provide a valid Password.</div>
                                </div>
                            </div>

                            <!--  -->
                        </div>
                        <button type="submit" class="m-0 btn btn-block btn-primary mb-4">Register</button>
                        <p class="mt-2 text-center text-muted">Already have an account? <a href="<?php echo $base_url; ?>/login" class="link"> Login</a></p>
                    </form>
                </div>
            </div>
            <!--  -->

            <!--  -->
        </div>
    </div>

    <?php
    echo web_footer("user");


    if (isset($_GET["r_id"])) {
        $r_id = $_GET["r_id"];
        if (is_user_id($r_id)) {
            $referral_id = $r_id;
            $referral_name = user_name($referral_id);
    ?>
            <script>
                $("#referral_id").val("<?php echo $referral_id; ?>").trigger("change").attr("readonly", true);
                $("#referral_name").val("<?php echo $referral_name; ?>");
            </script>
        <?php
        }
    }

    if (isset($_GET["p_id"])) {
        $p_id = $_GET["p_id"];
        if (is_user_id($p_id)) {
            $placement_id = $p_id;
            $placement_username = user_name($placement_id); ?>
            <script>
                $("#placement_id").val("<?php echo $placement_id; ?>").trigger("change").attr("readonly", true);
                $("#placement_name").val("<?php echo $placement_username; ?>");
            </script>
        <?php
        }
    }

    if (isset($_GET["p_t"])) {
        $p_t = $_GET["p_t"];
        $placement_type = $p_t;
        if ($placement_type == "l") {
        ?>
            <script>
                $("#placement_type").find("option[value=left]").attr("selected", true);
                $("#placement_type").trigger("change");
            </script>
        <?php
        } else 
            if ($placement_type == "r") {
        ?>
            <script>
                $("#placement_type").find("option[value=right]").attr("selected", true);
                $("#placement_type").trigger("change");
            </script>
    <?php
        }
    }


    ?>

</body>

</html>