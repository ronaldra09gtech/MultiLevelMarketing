<?php

$base = '../';
$active_page = "user-tree";
include $base . "db.php";
check_user_login();
$user_id = $loggedin_user_id;

?>
<!DOCTYPE html>
<html lang="en">

<head>
   
    <?php echo web_metadata(); ?>
  
    <title>Tree - <?php echo $web_name; ?></title>
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
                                <h3>Tree</h3>
                            </div>
                            <div class="col-6">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="<?php echo $base_url; ?>"><i class="material-icons-outlined">home</i> </a></li>
                                    <li class="breadcrumb-item active">Tree</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Container-fluid starts-->
                <div class="container-fluid">

                    <div style="min-height:90vh;margin-top: 10px;position: relative;" class="card" id="tree_chart"></div>

                </div>
                <!-- Container-fluid Ends-->
            </div>


            <!-- footer start-->
            <?php echo web_footer("user"); ?>
            <script src="<?php echo $base_url; ?>/assets/js/tree_chart.js"></script>
            <!--  -->
            <script>
                $nodes = <?php echo user_tree_nodes($user_id); ?>;
                init_tree_chart($nodes);
            </script>
            <script>
                $(document).on('click', 'g.node', function() {
                    let $element = $(this);
                    let $nodeId = $element.attr('node-id');
                    let $data = chart.get($nodeId);
                    let $placement_id = $data['pid'];
                    let $placement_type = $data['p_t'];
                    let $title = $data["title"];
                    if ($placement_type == 0) {
                        $placement_type = 'l';
                    } else {
                        $placement_type = 'r';
                    }
                    if ($title == 'Click here') {
                        let $url = $base_url + '/register?r_id=<?php echo $user_id; ?>&p_id=' + $placement_id + '&p_t=' + $placement_type;
                        console.log($url);
                        window.open($url);
                    }
                })
            </script>
        </div>
    </div>




</body>

</html>