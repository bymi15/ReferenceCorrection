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
</head>
<body>
    <div class="container">
        <?php
        include 'header.php';
        ?>
        <div class="AboutApp">
            <h3>Welcome to Reference Correction!</h3>
            <p>Reference correction description...</p>
        </div>
    </div>
</body>
</html>
