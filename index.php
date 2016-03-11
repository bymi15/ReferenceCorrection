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

    <link rel="stylesheet" type="text/css" href="/css/index.css">
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

                <!-- PHP CODE - RETRIEVES THE POSTS FROM THE DATABASE -->
                <?php
                include_once '/include/connection.php';

                $sql = "SELECT posts.id, posts.article_title, posts.article_author, posts.date, posts.category, posts.author, users.username FROM posts LEFT JOIN users ON posts.author = users.id LIMIT 10";

                $result = mysqli_query($mysqli, $sql);

                if(!$result)
                {
                    echo '<h3>Could not display the recent posts. Please refresh the page and try again later.</h3>';
                }
                else
                {
                    if(mysqli_num_rows($result) == 0)
                    {
                        echo '<h3>No posts to be displayed.</h3>';
                    }
                    else
                    {
                        //for each post
                        while($row = mysqli_fetch_assoc($result))
                        {
                            echo '<div class="post" onclick="location.href=\'post.php?id=' . $row['id'] . '\';">
                                    <p class="post_title">"' . $row['article_title'] . '" <span style="font-weight: normal;">by ' . $row['article_author'] . '</span></p>
                                    <p class="date">' . $row['date'] . '</p>
                                    <p>category: <a href="#">' . $row['category'] . '</a> posted by: <a href="#">' . $row['username'] . '</a></p>
                                </div>';
                        }
                    }
                }
                ?>

                <!--

                <div class="post" onclick="location.href='#';">
                    <p class="post_title">Title of article 1</p>
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
                -->
        </div>
    </div>
</body>
</html>
