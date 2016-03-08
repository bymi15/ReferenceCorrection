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
        ?>
         <?php
            if (login_check($mysqli) == true) { echo'
             <div class="create_post_form">
                <form method="post" action="process_login.php" onsubmit="return genHash(this)" id="post_form">
                    <h1>Create a Post</h1>
                    <p class="label">Article Title</p>
                    <p><input type="text" name="post_title" id="post_title" value="" maxlength="180" placeholder="name of article"></p><br>
                    <p class="label">Author(s)</p>
                    <p><input type="text" name="post_author" id="post_author" value="" maxlength="180" placeholder="name of author(s)"></p><br>
                    <p class="label">Category</p>
                    <p><select name="post_category" id="post_category">
                        <option value="volvo">Endocrinology</option>
                        <option value="saab">Gastroenterology</option>
                        <option value="fiat">Haematology</option>
                        <option value="audi">Neurology</option>
                      </select></p><br>
                    <p class="label">Article URL</p>
                    <p><input type="text" name="post_url" id="post_url" value="" maxlength="180" placeholder="link to article"></p><br>
                    <p class="label">Tags</p>
                    <p><input type="text" name="post_tags" id="post_tags" value="" maxlength="180" placeholder="e.g. cardiology, neurology, genetics"></p><br>
                    <p class="label">References</p>
                    <p><textarea form="post_form" name="post_references" id="post_references" style="resize: none; height:100px; max-height:100px; overflow-y:scroll;"></textarea></p><br>
                    <p>
                    <input type="submit" value="Post" class="submit_button" style=""/>
                    <input type="submit" value="Cancel" class="submit_button" style=""/>
                    </p>
                </form>
            </div>';
            } else {
                echo '<h1 style="text-align: center;">Only registered users may create posts.</h1><br><h2 style="text-align: center;">If you wish to create a post, please <a href="login.php">login</a> or <a href="register.php">register</a>.</h2><br>';
            }
        ?>
</body>
</html>
