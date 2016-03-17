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

            $stmt->bind_param('i', $id);
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

                //pagination
                $page = 1;
                if(isset($_GET['page'])) {
                    $page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
                    if($page === false) {
                        $page = 1;
                    }
                }
                $items_per_page = 5;
                $offset = ($page - 1) * $items_per_page;

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

                    <div class="references_section">
                        <h3>References</h3><hr>';

                $row_count = count($reference_list);
                $page_count = 0;
                if ($row_count !== 0){
                    $page_count = (int)ceil($row_count / $items_per_page);
                    if($page > $page_count) {
                        $page = 1;
                    }
                }

                echo '<br><div class="page_number_section">';
                for ($i = 1; $i <= $page_count; $i++) {
                    if ($i === $page) { // this is current page
                        echo '<span class="page_number" id="current_page">' . $i . '</span>';
                    } else { // show link to other page
                        echo '<a href="/post.php?id=' . $id . '&page=' . $i . '"><span class="page_number">' . $i . '</span></a>';
                    }
                }
                echo '</div><br>';

                echo '<ul>';

                for($i = $offset; $i < $items_per_page + $offset; $i++){
                    if(!empty($reference_list[$i])){
                    echo'
                        <li class="reference"><p>' . $reference_list[$i] . '</p>
                            <div class="suggestion_buttons">
                                <button onlick="">Add Suggestions</button>
                                <button type="button" onlick="*">View Suggestions</button>
                                <div class="voting_buttons">
                                    <img style="margin-right:20px;" src="/img/like.png" alt="thumbsUp">
                                    <img src="/img/dislike.png" alt="thumbsdown">
                                </div>
                            </div>
                        </li>
                    ';
                    }
                }

                echo'</ul>

                    </div>
                </div>
                    ';
            }
        }

        ?>
    </div>
</body>
</html>
