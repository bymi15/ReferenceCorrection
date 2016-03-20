<?php
include_once '/include/connection.php';
include_once '/include/functions.php';

error_reporting(E_ALL & ~E_NOTICE);
my_session_start();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Reference Checker: Suggestion</title>
    <link rel="stylesheet" type="text/css" href="css/index.css">
</head>
<body>
    <div class="container">
        <?php
            include 'header.php';
            if (isset($_GET['ref_index'], $_GET['post_id'])) {
                $post_id = $_GET['post_id'];
                $ref_index = $_GET['ref_index'];

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
                    </div>';

                    $sql = "SELECT suggestion.comment, suggestion.correction, suggestion.vote, suggestion.author, users.username FROM suggestion LEFT JOIN users ON suggestion.author = users.id WHERE post_id=" . $post_id . " AND reference_index=" . $ref_index;

                    $result = mysqli_query($mysqli, $sql);
                    if(mysqli_num_rows($result) > 0){
                        while($row_data = mysqli_fetch_assoc($result)){
                            echo'
                                <div class="suggestion">
                                    <div class="left_header">
                                        <h3><span class="label">User:</span> ' . $row_data["username"] . '</h3>
                                        <img src="img/like.png">
                                        <span class="vote_label">Vote: ' . $row_data["vote"] . '</span>
                                        <img src="img/dislike.png">
                                    </div>
                                    <div class="right_header">
                                        <h3><span class="label">Suggestion</span></h3>
                                        <p>' . $row_data["correction"] . '</p>
                                        <h3><span class="label">Additional Comments</span></h3>
                                        <p>' . $row_data["comment"] . '</p>
                                    </div>
                                </div>
                            ';
                        }
                        echo'
                        <div class="buttons">
                            <a href=\'add_suggestion.php?post_id=' . $post_id . '&ref_index=' . $ref_index . '\'>Add Suggestion</a>
                            <a href=\'post.php?id=' . $post_id . '\'>Back to post</a>
                        </div>
                        ';
                    }else{
                        echo'
                            <div class="buttons">
                            <h3>No suggestions have been made.</h3>
                            <a style="text-decoration: none;" href=\'add_suggestion.php?post_id=' . $post_id . '&ref_index=' . $ref_index . '\'>Add Suggestion</a>
                            <a style="text-decoration: none;" href=\'post.php?id=' . $post_id . '\'>Back to post</a>
                            </div>
                        ';
                    }
                }
        }else{
            echo'
            <h1 style="text-align: center;">Error: Invalid request</h1><br>
            <h2 style="text-align: center;">Return to <a href="index.php">home page</a></h2><br>';
        }
        ?>
    </div>
</body>
</html>
