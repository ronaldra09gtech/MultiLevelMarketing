<?php

$base = '../../';
$active_page = "support";
include $base . "db.php";
check_admin_login();
if (!isset($_GET["ticket"])) {
    http_response_404();
}

$ticket_id = $_GET["ticket"];
$query = mysqli_query($conn, "SELECT * FROM $tickets_tbl WHERE ticket_id = '$ticket_id'  ");
if (!mysqli_num_rows($query)) {
    http_response_404();
}

$row = mysqli_fetch_array($query);
$status = $row["status"];
$user_id = $row["ticket_creator"];
$closed_date = $row["closed_on"];
$added_date = $row["created_at"];
$active_date = $row["activated_on"];
$active_date = date_time($active_date);
$added_date = date_time($added_date);
$closed_date = date_time($closed_date);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php echo web_metadata(); ?>
    <title>View Ticket - <?php echo $web_name; ?></title>
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
        <?php include $base . "assets/nav/admin/header.php"; ?>
        <!-- Page Header Ends     -->
        <!-- Page Body Start-->
        <div class="page-body-wrapper">
            <!-- Page Sidebar Start-->
            <?php include $base . '/assets/nav/admin/sidebar.php'; ?>
            <!-- Page Sidebar Ends-->
            <div class="page-body">
                <div class="container-fluid">
                    <div class="page-title">
                        <div class="row">
                            <div class="col-6">
                                <h3>Support</h3>
                            </div>
                            <div class="col-6">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?php echo $admin_base_url; ?>"><i class="material-icons-outlined">home</i> </a></li>
                                    <li class="breadcrumb-item"><a href="<?php echo $admin_base_url; ?>/support/">Support</a></li>
                                    <li class="breadcrumb-item active">Ticket#<?php echo $ticket_id; ?></li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Container-fluid starts-->
                <div class="container-fluid">

                    <div class="card">
                        <div class="card-body">
                            <div class="support-detail-heading justify-between">
                                <div class="font-20">Ticket Id: <b>#<?php echo $ticket_id; ?></b> <br> <small> User Id: <b><?php echo $user_id; ?></b></small></div>
                                <?php

                                if ($status == '1') {
                                    echo '<label class="alert text-dark height-max alert-warning d-block">Added on ' . $added_date . '</label>';
                                } else if ($status == '2') {
                                    echo '<label class="alert height-max alert-success d-block">Actived on ' . $active_date . '</label>';
                                } else if ($status == '3') {
                                    echo '<label class="alert height-max alert-danger d-block">Closed on ' . $closed_date . '</label>';
                                }

                                ?>

                            </div>
                        </div>
                    </div>

                    <?php if ($status !== '3') { ?>
                        <div class="card">
                            <div class="card-header justify-between">
                                <h5>Add A Ticket</h5>
                                <button id="_cls_btn" class="btn btn-danger">Close Ticket</button>
                            </div>
                            <div class="card-body add-post">

                                <form images="" id="_reply_ticket_form" class="row needs-validation" novalidate="">
                                    <div class="col-sm-12 mb-3">
                                        <label for="message">Message</label>
                                        <textarea name="message" rows="10" class="form-control" id="message" type="text" placeholder="Message" required=""></textarea>
                                        <div class="invalid-feedback">Please provide a valid message.</div>
                                    </div>

                                </form>

                                <label for="message">Upload Files <small>(optional)</small></label>
                                <form data-form="#_reply_ticket_form" class="dropzone" id="dropZoneUploadForm">
                                    <div class="m-0 dz-message needsclick"><i class="material-icons-outlined">backup</i>
                                        <h6 class="mb-0">Drop files here or click to upload.</h6>
                                    </div>
                                    <button type="submit" class="btn btn-info">Submit Images</button>
                                </form>

                                <div class="btn-showcase justify-right">
                                    <button data-form="#_reply_ticket_form" id="submit_form" class="btn btn-secondary" type="submit">Add reply</button>
                                </div>

                            </div>
                        </div>
                    <?php } ?>

                    <section class="comment-box admin-page"> <?php echo ticket_message($ticket_id); ?></section>


                </div>
                <!-- Container-fluid Ends-->
            </div>


            <!-- footer start-->
            <?php echo web_footer("admin"); ?>
            <!--  -->
            <script src="<?php echo $base_url; ?>/assets/js/dropzone/dropzone.js"></script>
            <script src="<?php echo $base_url; ?>/assets/js/dropzone/dropzone-script.js"></script>
        </div>
    </div>




</body>

</html>