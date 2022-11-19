<?php

include "../db.php";
if (is_admin_exist()) {
    http_response_404();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php echo web_metadata(); ?>
    <title>Create Admin - <?php echo $web_name; ?></title>
    <?php include("../assets/css/css-files.php"); ?>
    <style>
        .footer {
            display: none;
        }

        body {
            font-family: 'Poppins', sans-serif;
            color: #444 !important;
            font-size: 14px;
            background: #007991;
            background: -webkit-linear-gradient(to left, #37abc2, #104f3c) !important;
            background: linear-gradient(to left, #37abc2, #104f3c) !important;
        }


        .wizard-setup {
            width: 100%;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .page-wrapper .page-body-wrapper {
            background-color: transparent !important;
        }
    </style>
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
                                <h5 class="text-center">Create Admin</h5>
                            </div>
                            <div class="card-body">
                                <form novalidate class="needs-validation" id="_create_admin_form" method="post">
                                    <div class="form-group">
                                        <label for="host">Username</label>
                                        <input type="text" class="form-control form-input" name="user_name" placeholder="Username" value="" required="">
                                        <div class="invalid-feedback">Please provide a valid Username</div>
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input id="email" type="email" class="form-control form-input" name="email" placeholder="Email" value="" required="">
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
                                    <div class="justify-right">
                                        <button class="btn btn-primary btn-submit" type="submit">Submit</button>
                                    </div>
                                </form>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php echo web_footer("admin"); ?>

    </div>

</body>


</html>