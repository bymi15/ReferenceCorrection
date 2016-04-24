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
    <title>Reference Checker: Add Suggestions</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- Hover.css -->
    <link rel="stylesheet" type="text/css" href="css/hover.css">
    <!-- Main -->
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <!-- Fonts -->
    <?php
    include_once 'fonts.php';
    ?>
</head>
<body>
        <?php
        include 'header.php';

        if (isset($_GET['ref_index'], $_GET['post_id'])) {
            $post_id = $_GET['post_id'];
            $ref_index = $_GET['ref_index'];

            $by = '';

            if (login_check($mysqli) == true) {
                $by = $_SESSION['user_id'];

                //retrieve the post from the database
                $stmt = $mysqli->prepare("SELECT posts.article_title, posts.article_author, posts.date, posts.category, posts.author, posts.article_references, posts.article_url, posts.views, users.username FROM posts LEFT JOIN users ON posts.author = users.id WHERE posts.id = ? LIMIT 1");

                $stmt->bind_param('i', $post_id);
                $stmt->execute();    // Execute the prepared query.
                $stmt->store_result(); //Store its results

                $stmt->bind_result($article_title, $article_author, $date, $category, $author_id, $reference_id, $article_url, $views, $author_username);
                $stmt->fetch();

                if($stmt->num_rows != 1)
                {
                    echo '<h1>Error: the post does not exist</h1>';
                    echo '<h3>Could not display the post. Please refresh the page and try again later.</h3>';
                }
                else
                {
                    $sql = "SELECT reference_text FROM reference WHERE id=" . $reference_id;
                    $result = mysqli_query($mysqli, $sql);

                    $data = mysqli_fetch_assoc($result);
                    $references = $data['reference_text'];

                    $reference_list = explode("\n", $references);

                   echo'
                    <div class="post_author_section container">
                        <div class="left_header">
                            <p style="font-size:19px"><span class="glyphicon glyphicon-user"></span><span class="label" style="font-size:19px">Username</span>: <a href="#">' . $author_username . '</a></p>
                            <p><span class="glyphicon glyphicon-eye-open"></span><span class="label">Views:</span> ' . $views . '</p>
                            <p><span class="glyphicon glyphicon-th-list"></span> <span class="label">Category:</span> <a href="#">' . $category . '</a></p>
                        </div>
                        <p style="font-size:16px;"><span class="glyphicon glyphicon-pencil"></span><span class="label">Article Title:</span> ' . $article_title . '</p>
                        <p><span class="glyphicon glyphicon-tag"></span><span class="label">Article url:</span><a href="' . $article_url .'">' . $article_url .'</a></p>
                        <p><span class="glyphicon glyphicon-book"></span><span class="label">Author(s):</span>' . $article_author . '</p>
                        <p><span class="glyphicon glyphicon-calendar"></span><span class="label">Date posted:</span>' . $date . '</p>
                    </div>

                    <div class="suggestion_form container panel panel-default">

                        <div class="current-reference panel-heading">
                        <h3>Add a suggestion</h3>
                        <hr>
                        <h3 style="font-size:19px">The current reference is: <span style="color:#197B9C">' . $reference_list[$ref_index] . '</span></h3>
                        </div>

                        <form method="post" action="process_add_suggestion.php" onsubmit="return validateSuggestion(this, this.correction);">
                            <p><i class="fa fa-lightbulb-o"></i> Correction</p>
                            <p><input type="text" name="correction" placeholder="Enter the correction you would like to suggest." autofocus></p>
                            <p><i class="fa fa-file-text"></i> What type of error is it?</p>
                            <div class="btn-group" data-toggle="buttons">
                                <label class="btn btn-default"><input type="radio" name="error_type" value="citation">Citation</label>
                                <label class="btn btn-default"><input type="radio" name="error_type" value="quotation">Quotation</label>
                            </div>
                            <br><br>
                            <p><span class="glyphicon glyphicon-comment"></span> Additional Comments</p>
                            <p><textarea name="comment" id="comment" placeholder="Additional comments for this reference?"></textarea></p>
                            <input type="hidden" name="post_id" id="post_id" value="' . $post_id . '">
                            <input type="hidden" name="ref_index" id="ref_index" value="' . $ref_index . '">
                            <input type="hidden" name="by" id="by" value="' . $by . '">
                            <p>
                                <button type="submit" class="submit_button btn btn-primary">Submit</button>
                                <a href="post.php?id=' . $post_id . '"<button type="button" class="submit_button btn btn-warning">Cancel</button></a>
                            </p>
                        </form>
                    </div>
                    ';
            }

        }else {
            echo'<h1 style="text-align: center; font-weight: bold; color: white;">Only registered users may add suggestions.</h1><br><h2 style="text-align: center; color: white;">If you wish to add a suggestion, please <a href="login.php">login</a> or <a href="register.php">register</a>.</h2><br>';
        }
        }else{
            echo'
            <h1 style="text-align: center; font-weight: bold; color: white;">Error: Invalid request</h1><br><h2 style="text-align: center; color: white;">Return to <a href="index.php">home page</a></h2><br>';
        }
        ?>
    </div>
    <?php
    include 'footer.php';
    ?>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="js/bootstrap.min.js"></script>
    <!-- My script -->
    <script src="/js/upload_file.js"></script>
    <script src="/js/form.js"></script>
</body>
</html>
