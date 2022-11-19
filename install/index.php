<?php

$installer_page = true;
include "../db.php";
if ($has_db_created == "true") {
    http_response_404();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jamsrmlm</title>
</head>
<style>
    b,
    br {
        display: none;
    }

    html {
        height: 100%;
    }

    body {
        height: 100%;
        color: #fff !important;
        overflow: hidden;
        font-family: 'Poppins', sans-serif;
        font-size: 14px;
        background: -webkit-linear-gradient(to left, #37abc2, #104f3c) !important;
        background: linear-gradient(to left, #37abc2, #104f3c) !important;
    }

    .wrapper {
        width: 100%;
        height: 100%;
        position: relative;
        display: block;
    }

    .center {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        height: 400px;
        max-width: 100%;
        margin: auto;
        text-align: center;
    }

    .title {
        color: #fff !important;
        font-size: 102px;
        line-height: 102px;
        font-weight: 300;
        margin: 0;
    }

    .version {
        margin-bottom: 30px;
        color: #fff;
    }

    .button {
        display: inline-block;
        text-align: center;
        vertical-align: middle;
        -ms-touch-action: manipulation;
        touch-action: manipulation;
        cursor: pointer;
        font-size: 16px;
        border-radius: 25px;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        color: #fff;
        text-decoration: none;
        height: 50px;
        line-height: 50px;
        padding: 0 80px;
    }

    .button.localhost {
        background-image: linear-gradient(to right, #21AAB0 0%, #00CDAC 51%, #02ccB0 100%)
    }

    .button.cpanel {
        background-image: linear-gradient(to right, #12AAB0 0%, #69CDAC 51%, #02ddB0 100%)
    }

    .button:hover {
        background-position: right center;
    }

    @media only screen and (max-width: 768px) {
        .title {
            font-size: 72px;
            line-height: 72px;
        }
    }
</style>

<body>
    <div class="wrapper">
        <div class="center align-center">
            <h1 class="title font-bold">Jamsrmlm</h1>
            <p class="version">Version 2.0</p>
            <a href="localhost/" class="localhost button">Install on localhost</a>
            <a href="cpanel/" class="cpanel button">Install on cpanel</a>
        </div>
    </div>
</body>

</html>