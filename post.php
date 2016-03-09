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

    <link rel="stylesheet" type="text/css" href="index.css">
</head>
<body>
    <div class="container">
        <?php
        include 'header.php';

        if (isset($_GET['id'])) {
            echo 'HELLLOO!!!!!!';
        }

        ?>
    </div>
</body>
</html>
