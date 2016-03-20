<?php
include_once '/include/connection.php';
include_once '/include/functions.php';

error_reporting(E_ALL & ~E_NOTICE);
my_session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reference Checker: Add Suggestion</title>
    <link rel="stylesheet" type="text/css" href="/css/index.css">
    <script src="/js/form.js"></script>
</head>
<body>
    <div class="container">
        <?php
        include 'header.php';

        if (isset($_GET['ref_index'], $_GET['post_id'])) {
            $post_id = $_GET['post_id'];
            $ref_index = $_GET['ref_index'];

            if (login_check($mysqli) == true) {

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
                    $references = mysqli_fetch_assoc($result)['reference_text'];
                    $reference_list = explode("\n", $references);

                    echo'
                    <div class="navigation">
                        <div class="post_author_section">
                            <div class="left_header">
                                <h3>Username: <a href="#">' . $author_username . '</a></h3>
                                <p><span class="label">Views:</span> ' . $views . '</p>
                                <p><span class="label">Category:</span> <a href="#">' . $category . '</a></p>
                            </div>
                            <p style="font-size:16px;"><span class="label">Article Title:</span> ' . $article_title . '</p>
                            <p><span class="label">Article url:</span> <a href="' . $article_url .'" target="_blank">' . $article_url . '</a></p>
                            <p><span class="label">Author(s): </span>' . $article_author . '</p>
                            <p><span class="label">Date posted: </span>' . $date . '</p>
                        </div>
                    </div>

                    <div class="reference_header">
                        <h3>The current reference is: <span style="color:#1B9AF5">' . $reference_list[$ref_index] . '</span></h3>
                    </div>

                    <div class="suggestion_form">
                        <form method="post" action="process_add_suggestion.php" onsubmit="return validateSuggestion(this, this.correction);">
                            <p class="label">Correction</p>
                            <p><input type="text" name="correction" id="correction" placeholder="Enter the correction you would like to suggest"></p>
                            <p class="label">Additional Comments</p>
                            <p><textarea name=\'comment\' id=\'comment\' placeholder="Any additional comments about this reference?"></textarea></p>
                            <input type="hidden" name="post_id" id="post_id" value="' . $post_id . '">
                            <input type="hidden" name="ref_index" id="ref_index" value="' . $ref_index . '">
                            <input type="hidden" name="by" id="by" value="' . $author_id . '">
                            <button type="submit" class="submit_button">Submit</button>
                            <a href="post.php?id=' . $post_id . '"<button type="button" class="submit_button">Cancel</button></a>
                        </form>
                    </div>
                    ';
            }

        }else { echo'
                <h1 style="text-align: center;">Only registered users may add suggestions.</h1><br><h2 style="text-align: center;">If you would like to add a suggestion, please <a href="login.php">login</a> or <a href="register.php">register</a>.</h2><br>';
            }
        }else{
            echo'
            <h1 style="text-align: center;">Error: Invalid request</h1><br><h2 style="text-align: center;">Return to <a href="index.php">home page</a></h2><br>';
        }
        ?>

<script src="/js/upload_file.js"></script>
</body>
</html>
