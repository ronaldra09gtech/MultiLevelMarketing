<?php

$base = '../';
$active_page = "withdraw";
include $base . "db.php";
check_user_login();
$user_id = $loggedin_user_id;
$user_name = user_name($user_id);

?>
<!DOCTYPE html>
<html lang="en">

<head>
 
    <?php echo web_metadata(); ?>
   
    <title>Deposit - <?php echo $web_name; ?></title>
    <link rel="stylesheet" href="<?php echo $base_url; ?>/assets/css/dropzone.css">
    <?php include $base . "assets/css/css-files.php"; ?>
</head>

<body>
    <!-- tap on top starts-->
    <div class="tap-top"><i data-feather="chevrons-up"></i></div>
    <!-- tap on tap ends-->
    <!-- page-wrapper Start-->
    <div class="page-wrapper compact-wrapper" id="pageWrapper">
        <!-- Page Header Start-->
         <?php include '../assets/nav/user/header.php'; ?>
        <!-- Page Header Ends     -->
        <!-- Page Body Start-->
        <div class="page-body-wrapper">
            <!-- Page Sidebar Start-->
            <?php include $base . '/assets/nav/user/sidebar.php'; ?>
            <!-- Page Sidebar Ends-->
            <div class="page-body">
                <div class="container-fluid">
                    <div class="page-title">
                        <div class="row">
                            <div class="col-6">
                                <h3>Withdraw</h3>
                            </div>
                            <div class="col-6">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?php echo $base_url; ?>"><i class="material-icons-outlined">home</i> </a></li>
                                    <li class="breadcrumb-item"><a href="<?php echo $base_url; ?>/support/">Deposit</a></li>
                                    <li class="breadcrumb-item active">Add a ticket</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Container-fluid starts-->
                <div class="container-fluid">

                    <div class="card">
                        <div class="card-header">
                            <h5>Add A Ticket</h5>
                        </div>
                        <div class="card-body add-post">

                            <form images="" id="add_withdraw" class="row needs-validation" novalidate="">
                                <div class="col-lg-6 col-sm-12 mb-3">
                                    <label for="user_name">Username</label>
                                    <input disabled value="<?php echo $user_name; ?>" class="form-control" id="user_name" type="text" placeholder="Username" required="">
                                </div>
                                <div class="col-lg-6 col-sm-12 mb-3">
                                    <label for="user_id"> User Id </label>
                                    <input disabled value="<?php echo $user_id; ?>" class="form-control" id="user_id" type="text" placeholder="User Id" required="">
                                </div>
                                <div class="col-lg-6 col-sm-12 mb-3">
                                    <label for="user_id"> Bank Name </label>
                                    <select class="form-control" name="bank_name" id="">
                                                <option value="">Select</option>
                                                <option value="BPI">BPI / BPI Family Savings Bank</option>
                                                <option value="BDO">BDO Unibank Inc.</option>
                                                <option value="LandBank">LandBank</option>
                                                <option value="Security Bank">Security Bank</option>
                                                <option value="Security Bank">Security Bank</option>
                                                <option value="Union Bank of the Philippines">Union Bank of the Philippines</option>
                                                <option value="Philippine National Bank">Philippine National Bank</option>
                                                <option value="China Banking Corporation">China Banking Corporation</option>
                                                <option value="East West Banking Corporation">East West Banking Corporation</option>
                                                <option value="RCBC">RCBC</option>
                                                <option value="United Coconut Planters Bank">United Coconut Planters Bank</option>
                                                <option value="Philippines Savings Bank">Philippines Savings Bank</option>
                                                <option value="Asia United Bank Corporation">Asia United Bank Corporation</option>
                                                <option value="Phlippine Bank of Communications">Phlippine Bank of Communications</option>
                                                <option value="Development Bank of the Philippines">Development Bank of the Philippines</option>
                                                <option value="AllBank Inc.">AllBank Inc.</option>
                                                <option value="Bangko Mabuhay">Bangko Mabuhay</option>
                                                <option value="Bank of Commerce">Bank of Commerce</option>
                                                <option value="Card Bank Inc.">Card Bank Inc.</option>
                                                <option value="CTBC Bank">CTBC Bank</option>
                                                <option value="Dumaguete City Development Bank">Dumaguete City Development Bank</option>
                                                <option value="Dungganon Bank">Dungganon Bank</option>
                                                <option value="Equicom Savings Bank Inc.">Equicom Savings Bank Inc.</option>
                                                <option value="Maybank Philippines Inc.">Maybank Philippines Inc.</option>
                                                <option value="Mindanao Consolidated CoopBank">Mindanao Consolidated CoopBank</option>
                                                <option value="Partner Rural Bank Inc.">Partner Rural Bank Inc.</option>
                                                <option value="Quezon Capital Rural Bank">Quezon Capital Rural Bank</option>
                                                <option value="RCBC Savings Bank Inc.">RCBC Savings Bank Inc.</option>
                                                <option value="Robinsons Bank Corporation">Robinsons Bank Corporation</option>
                                                <option value="UCPB Savings Bank">UCPB Savings Bank</option>
                                                <option value="Gcash">GCash</option>
                                            </select>
                                </div>
                                <div class="col-lg-6 col-sm-12 mb-3">
                                    <label for="user_id"> Bank Account Number </label>
                                    <input name="bank_acc_num" class="form-control" type="text" placeholder="Bank Account Number" required="">
                                </div>
                                <div class="col-sm-12 mb-3">
                                    <label for="subject">Amount</label>
                                    <input name="amount" class="form-control" id="subject" type="text" placeholder="Minumun of 2000" required="">
                                    <div class="invalid-feedback">Please provide a Ammount.</div>
                                </div>
                                <div class="col-sm-12 mb-3">
                                    <label for="message">Message</label>
                                    <textarea name="message" rows="10" class="form-control" id="message" type="text" placeholder="Message" required=""></textarea>
                                    <div class="invalid-feedback">Please provide a valid message.</div>
                                </div>

                            </form>

                            <label for="message">Upload Files <small>(optional)</small></label>
                            <form data-form="#add_withdraw" class="dropzone" id="dropZoneUploadForm">
                                <div class="m-0 dz-message needsclick"><i class="material-icons-outlined">backup</i>
                                    <h6 class="mb-0">Drop files here or click to upload.</h6>
                                </div>
                                <button type="submit" class="btn btn-info">Submit Images</button>
                            </form>

                            <div class="btn-showcase justify-right">
                                <button data-form="#add_withdraw" id="submit_form" class="btn btn-primary" type="submit">Submit ticket</button>
                            </div>

                        </div>
                    </div>

                </div>
                <!-- Container-fluid Ends-->
            </div>


            <!-- footer start-->
            <?php echo web_footer("user"); ?>
            <!--  -->
            <script src="<?php echo $base_url; ?>/assets/js/dropzone/dropzone.js"></script>
            <script src="<?php echo $base_url; ?>/assets/js/dropzone/dropzone-script.js"></script>
        </div>
    </div>




</body>

</html>
