<?php
$REQUEST_URI = explode("/", $_SERVER["REQUEST_URI"]);
$web_name = $REQUEST_URI[1];
$web_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/$web_name";

$installer_page = true;
include "../../db.php";
if($has_db_created == "true"){
    http_response_404();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/bootstrap.css">
    <link rel="stylesheet" href="../install.css">
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
                                <h5 class="text-center">Welcome to the Installer</h5>
                            </div>
                            <div class="card-body">
                                <ul>
                                    <li>1. Change base_url in db.php file </li>
                                    <li>2. Import <a class="link" href="../sql.sql">sql</a> file</li>
                                    <li>3. Change database settings in db.php file </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</body>


</html>