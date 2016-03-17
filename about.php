<?php
include_once '/include/connection.php';
include_once '/include/functions.php';

error_reporting(E_ALL & ~E_NOTICE);
my_session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reference Correction: About</title>

    <link rel="stylesheet" type="text/css" href="/css/index.css">
    <link href='https://fonts.googleapis.com/css?family=Raleway' rel='stylesheet' type='text/css'>
</head>
<body>
    <div class="container">
        <?php
        include 'header.php';
        ?>
        <div class="header_image">
            <img src="images/research.jpg" alt="research">
            <h2>What is Reference Correction?</h2>
            <h3>About this App</h3>
        </div>
        <div class="about_page">
            <h3>A LITTLE BIT ABOUT REFERENCE CORRECTION</h3><hr>
            <p>Insert description here....</p>
        </div>
    </div>
</body>
</html>
