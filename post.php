<?php
include_once '/include/connection.php';
include_once '/include/functions.php';

error_reporting(E_ALL & ~E_NOTICE);
my_session_start();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Reference Correction</title>

    <link rel="stylesheet" type="text/css" href="/css/index.css">
</head>
<body>
    <div class="container">
        <?php
        include 'header.php';

        if (isset($_GET['id'])) {

            //update the view count
            $id = $_GET['id'];
            if (!isset($_SESSION['viewed' . $id])) {
                $query = mysqli_query($mysqli, "UPDATE posts SET views = views + 1 WHERE id = '{$id}'");

                if(!$query){
                    echo 'database error while incrementing view count';
                }else{
                    $_SESSION['viewed' . $id] = 1;
                }
            }

            //retrieve the post from the database
            $stmt = $mysqli->prepare("SELECT posts.article_title, posts.article_author, posts.date, posts.category, posts.author, posts.article_references, posts.article_url, posts.views, users.username FROM posts LEFT JOIN users ON posts.author = users.id WHERE posts.id = ? LIMIT 1");

            $stmt->bind_param('i', $_GET['id']);
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
                $sql = "SELECT reference_text FROM reference WHERE id=" . $reference_id . " LIMIT 1";
                $result = mysqli_query($mysqli, $sql);
                $references = mysqli_fetch_assoc($result)['reference_text'];

                echo'
                <div class="display_post">
                    <h1>' . $article_title . '</h1>
                    <h3> By ' . $article_author . '</h3><br>
                    <p class="label" style="text-align:center;">Views</p>
                    <p style="text-align:center;">' . $views . '</p>
                    <p class="label">Article Title</p>
                    <p>' . $article_title . '<p><br>
                    <p class="label">Author(s)</p>
                    <p>' . $article_author . '<p><br>
                    <p class="label">Category</p>
                    <p>' . $category . '<p><br>
                    <p class="label">Article URL</p>
                    <p><a href="' . $article_url . '" target="_blank">' . $article_url . '</a><p><br>
                    <p class="label">References</p>
                    <p><textarea name="post_references" id="post_references" style="resize: none; height:200px; max-height:200px; overflow-y:scroll;">' . $references . '</textarea></p><br>
                </div>';
            }
        }

        ?>
    </div>
</body>
</html>
