<?php
include_once '/include/connection.php';
include_once '/include/functions.php';

error_reporting(E_ALL & ~E_NOTICE);
my_session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reference Correction: Home</title>

    <link rel="stylesheet" type="text/css" href="index.css">
</head>
<body>
    <div class="container">
        <?php
        include 'header.php';
        ?>
        <div class="about_section">
            <h3>Welcome to Reference Correction!</h3>
            <p>Reference correction description...</p>
        </div>
        <div class="post_section">
            <h4>Recent posts</h4><hr>
                <div class="post" onclick="location.href='#';">
                    <p class="post_title">Title of article 1 test test test test test test test test test test test test test test test test test test test test (test for length overflow to next divnene) Title  might overflow test test test test testt test test test test Title of article 1 test test test test test test test test test test test test test test test test test test test test (test for length overflow to next divnene) Title  might overflow test test test test testt test test test testTitle of article 1 test test test test test test test test test test test test test test test test test test test test (test for length overflow to next divnene) Title  might overflow test test test test testt test test test test Title of article 1 test test test test test test test test test test test test test test test test test test test test (test for length overflow to next divnene) Title  might overflow test test test test testt test test test testTitle of article 1 test test test test test test test test test test test test test test test test test test test test (test for length overflow to next divnene) Title  might overflow test test test test testt test test test test Title of article 1 test test test test test test test test test test test test test test test test test test test test (test for length overflow to next divnene) Title  might overflow test test test test testt test test test testTitle of article 1 test test test test test test test test test test test test test test test test test test test test (test for length overflow to next divnene) Title  might overflow test test test test testt test test test test Title of article 1 test test test test test test test test test test test test test test test test test test test test (test for length overflow to next divnene) Title  might overflow test test test test testt test test test test </p>
                    <p class="date">24/2/2015</p>
                    <p>category: <a href="#">category</a> author: <a href="#">name</a></p>
                </div>
                <div class="post" onclick="location.href='#';">
                    <p class="post_title">Title of article 2</p>
                    <p class="date">24/2/2015</p>
                    <p>category: <a href="#">category</a> author: <a href="#">name</a></p>
                </div>
                <div class="post" onclick="location.href='#';">
                    <p class="post_title">Title of article 3</p>
                    <p class="date">24/2/2015</p>
                    <p>category: <a href="#">category</a> author: <a href="#">name</a></p>
                </div>
                <div class="post" onclick="location.href='#';">
                    <p class="post_title">Title of article 4</p>
                    <p class="date">24/2/2015</p>
                    <p>category: <a href="#">category</a> author: <a href="#">name</a></p>
                </div>
                <div class="post" onclick="location.href='#';">
                    <p class="post_title">Title of article 5</p>
                    <p class="date">24/2/2015</p>
                    <p>category: <a href="#">category</a> author: <a href="#">name</a></p>
                </div>
                <div class="post" onclick="location.href='#';">
                    <p class="post_title">Title of article 6</p>
                    <p class="date">24/2/2015</p>
                    <p>category: <a href="#">category author: <a href="#">name</a></p>
                </div>
            </ul>
        </div>
    </div>
</body>
</html>
