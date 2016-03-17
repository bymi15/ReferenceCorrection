<?php
include_once '/include/connection.php';
include_once '/include/functions.php';

error_reporting(E_ALL & ~E_NOTICE);
my_session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reference Correction: Create Post</title>
    <link rel="stylesheet" type="text/css" href="/css/index.css">
    <script src="/js/form.js"></script>
</head>
<body>
    <div class="container">
        <?php
        include 'header.php';
        ?>
         <?php
            if (login_check($mysqli) == true) { echo'
             <div class="create_post_form">
                <form method="post" action="process_create_post.php">
                    <h1>Create a Post</h1>
                    <div id="error_message">
                            <?php
                                if (!empty($error_msg)) {
                                    echo $error_msg;
                                }
                            ?>
                    </div>
                    <p class="label">Article Title</p>
                    <p><input type="text" name="post_title" id="post_title" value="" maxlength="500" placeholder="name of article" autofocus="true"></p><br>
                    <p class="label">Author(s)</p>
                    <p><input type="text" name="post_author" id="post_author" value="" maxlength="500" placeholder="name of author(s)"></p><br>
                    <p class="label">Category</p>
                    <p><select name="post_category" id="post_category">
                        <option value="Endocrinology">Endocrinology</option>
                        <option value="Gastroenterology">Gastroenterology</option>
                        <option value="Haematology">Haematology</option>
                        <option value="Neurology">Neurology</option>
                      </select></p><br>
                    <p class="label">Article URL</p>
                    <p><input type="text" name="post_url" id="post_url" value="" maxlength="200" placeholder="link to article"></p><br>
                    <p class="label">References</p>
                    <p><input type="file" name="upload_file" id="upload_file"></p>
                    <p><textarea name="post_references" id="post_references" style="resize: none; height:200px; max-height:200px; overflow-y:scroll;"></textarea></p><br>
                    <p>
                    <input type="submit" value="Post" class="submit_button" style="" onclick="return validatePost(this.form, this.form.post_title, this.form.post_author, this.form.post_url, this.form.post_references);"/>
                    <button value="Cancel" class="submit_button" style="" onclick="return false;"/>Cancel</button>
                    </p>
                </form>
            </div>';
            } else {
                echo '<h1 style="text-align: center;">Only registered users may create posts.</h1><br><h2 style="text-align: center;">If you wish to create a post, please <a href="login.php">login</a> or <a href="register.php">register</a>.</h2><br>';
            }
        ?>

<script src="/js/upload_file.js"></script>
</body>
</html>
