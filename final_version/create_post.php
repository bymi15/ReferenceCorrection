<?php
include_once 'include/connection.php';
include_once 'include/functions.php';

error_reporting(E_ALL & ~E_NOTICE);
my_session_start();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Reference Checker: Create Post</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

    <!-- Main -->
    <link rel="stylesheet" type="text/css" href="/css/index.css">

    <!-- fonts -->
    <?php
    include_once 'fonts.php';
    ?>
  </head>

<body>
   <?php
   include 'header.php';

   if (login_check($mysqli) == true) {
    $categories = getCategories();
    echo'
    <div class="create_post_form container">
        <form method="post" action="process_create_post.php" id="create_post_form" onsubmit="return validatePost(this, this.post_title, this.post_author, this.post_url, this.post_references);">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-title">
                        <h1>Create a post</h1>
                    </div>
                    <div id="error_message">
                        <?php
                        if (!empty($error_msg)) {
                            echo $error_msg;
                        }
                        ?>
                    </div>
                </div>

                <p class="label"><span class="glyphicon glyphicon-pencil"></span> Article Title</p>
                <p><input type="text" name="post_title" id="post_title" value="" maxlength="1000" placeholder="name of article" autofocus="true"></p><br>
                <p class="label"><span class="glyphicon glyphicon-book"></span> Author(s)</p>
                <p><input type="text" name="post_author" id="post_author" value="" maxlength="1000" placeholder="name of author(s)"></p><br>
                <p class="label"><span class="glyphicon glyphicon-th-list"></span> Category</p>
                <p><select name="post_category" id="post_category">';

                    for($i = 0; $i < count($categories); $i++){
                        echo'
                        <option value="' . $categories[$i] . '">' . $categories[$i] . '</option>
                        ';
                    }
                    echo'   </select></p><br>
                    <p class="label"><span class="glyphicon glyphicon-tag"></span> Article URL</p>
                    <p><input type="text" name="post_url" id="post_url" value="" maxlength="500" placeholder="link to article"></p><br>
                    <p class="label"><span class="glyphicon glyphicon-list"></span> References</p>
                    <p>References can be uploaded as a <b>.ris</b> or <b>.enw</b> file.</p>
                    <p><input type="file" name="upload_file" id="upload_file"></p>
                    <p><textarea name="post_references" id="post_references" style="resize: none; height:200px; max-height:200px; overflow-y:scroll;" placeholder="If you are unable to attach a file, copy and paste the list of referenes here, but be aware that there might be formatting issues"></textarea></p><br>
                    <p>
                        <input type="submit" value="Post" class="submit_button btn btn-primary"/>
                        <button value="Cancel" class="submit_button btn btn-primary" onclick="location.href = \'index.php\';"/>Cancel</button>
                    </p>
                </form>
            </div>';
        } else {
            echo '<h1 style="text-align: center; font-weight: bold; color: white;">Only registered users may create posts.</h1><br><h2 style="text-align: center; color: white;">If you wish to create a post, please <a href="login.php">login</a> or <a href="register.php">register</a>.</h2><br>';
        }
        ?>
        
        <?php
        include 'footer.php';
        ?>
        <!--My scripts-->
        <script src="js/form.js"></script>
        <script src="js/upload_file.js"></script>
        <!--Bootstrap-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
</body>
</html>
