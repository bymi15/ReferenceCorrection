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
        <div class="AboutApp">
            <h3>Welcome to Reference Correction!</h3>
            <p>Reference correction description...</p>
        </div>
        <div class="posts_header">
            <h4>Recent posts</h4><hr>
            <ul>
                <li class="post">
                    <p class="post_title"><a href="">Title of article 1 test test test test test test test test test test test test test test test test test test test test (test for length overflow to next line) Title  might overflow test test test test testt test test test test Title of article 1 test test test test test test test test test test test test test test test test test test test test (test for length overflow to next line) Title  might overflow test test test test testt test test test test </a></p>
                    <p style="color:#777">24/2/2015</p>
                    <p>category: <a href="#">category</a> author: <a href="#">name</a></p>
                </li>
                <li class="post" style="background:#E6E6FA;">
                    <p class="post_title"><a href="">Title of article 2</a></p>
                    <p style="color:#777">24/2/2015</p>
                    <p>category: <a href="#">category</a> author: <a href="#">name</a></p>
                </li>
                <li class="post">
                    <p class="post_title"><a href="">Title of article 3</a></p>
                    <p style="color:#777">24/2/2015</p>
                    <p>category: <a href="#">category</a> author: <a href="#">name</a></p>
                </li>
                <li class="post" style="background:#E6E6FA;">
                    <p class="post_title"><a href="">Title of article 4</a></p>
                    <p style="color:#777">24/2/2015</p>
                    <p>category: <a href="#">category</a> author: <a href="#">name</a></p>
                </li>
                <li class="post">
                    <p class="post_title"><a href="">Title of article 5</a></p>
                    <p style="color:#777">24/2/2015</p>
                    <p>category: <a href="#">category</a> author: <a href="#">name</a></p>
                </li>
                <li class="post" style="background:#E6E6FA;">
                    <p class="post_title"><a href="">Title of article 6</a></p>
                    <p style="color:#777">24/2/2015</p>
                    <p>category: <a href="#">category</a> author: <a href="#">name</a></p>
                </li>
            </ul>
        </div>
    </div>
</body>
</html>
