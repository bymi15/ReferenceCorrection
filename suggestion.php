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
    <title>Reference Checker: Suggestion</title>

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
                            <p style="font-size:19px"><span class="glyphicon glyphicon-user"></span><span class="label" style="font-size:19px">Username</span>: <a>' . $author_username . '</a></p>
                            <p><span class="glyphicon glyphicon-eye-open"></span><span class="label">Views:</span> ' . $views . '</p>
                            <p><span class="glyphicon glyphicon-th-list"></span> <span class="label">Category:</span> <a>' . $category . '</a></p>
                        </div>
                        <p style="font-size:16px;"><span class="glyphicon glyphicon-pencil"></span><span class="label">Article Title:</span> ' . $article_title . '</p>
                        <p><span class="glyphicon glyphicon-tag"></span><span class="label">Article url:</span><a target="_blank" href="' . $article_url .'">' . $article_url .'</a></p>
                        <p><span class="glyphicon glyphicon-book"></span><span class="label">Author(s):</span>' . $article_author . '</p>
                        <p><span class="glyphicon glyphicon-calendar"></span><span class="label">Date posted:</span>' . $date . '</p>
                    </div>

                    <div class="references_section container panel panel-default">
                        <div class="panel-heading">
                            <h3 style="display: inline-block;">Suggestions</h3>
                            <a href="post.php?id=' . $post_id . '"><button class="btn btn-primary" style="float: right; margin-top: 15px;">Return to post</button></a>
                        </div>';

                        $sql = "SELECT suggestion.id, suggestion.comment, suggestion.correction, suggestion.vote, suggestion.author, suggestion.error_type, users.username FROM suggestion LEFT JOIN users ON suggestion.author = users.id WHERE post_id=" . $post_id . " AND reference_index=" . $ref_index;

                        $result = mysqli_query($mysqli, $sql);

                        if(mysqli_num_rows($result) <= 0){
                         echo'
                         <div class="suggestion_buttons">
                            <h3>No suggestions have been made.</h3><br>
                            <a style="margin-right: 15px;" href=\'add_suggestion.php?post_id=' . $post_id . '&ref_index=' . $ref_index . '\'><button class="btn btn-primary">Add Suggestion</button></a>
                            <a href=\'post.php?id=' . $post_id . '\'><button class="btn btn-warning">Back to post</button></a>
                        </div>
                        ';
                        }else{
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

                            $row_count = mysqli_num_rows($result);
                            $page_count = 0;
                            if ($row_count !== 0){
                                $page_count = (int)ceil($row_count / $items_per_page);
                                if($page > $page_count) {
                                    $page = 1;
                                }
                            }

                            echo '<ul class="pagination">';
                            for ($i = 1; $i <= $page_count; $i++) {
                            if ($i === $page) { // this is current page
                                echo '<li class="active"><a href="#">' . $i . '</a></li>';
                            } else { // show link to other page
                                echo '<li><a href="/suggestion.php?ref_index=' . $ref_index . '&post_id=' . $post_id . '&page=' . $i . '">' . $i . '</a></li>';
                            }
                            echo '</ul>';

                            echo '<ul class="suggestion_list">';

                            $rows = array();
                            while($row = mysqli_fetch_assoc($result)){
                                $rows[] = $row;
                            }

                            for($i = $offset; $i < $items_per_page + $offset; $i++){
                                if(empty($rows[$i])) continue;

                                //0 = citation, 1 = quotation
                                $error_type = $rows[$i]["error_type"];

                                echo'
                                <div class="suggestion hvr-border-fade">
                                    <div class="left_header">
                                        <h3><span class="label">User:</span> ' . $rows[$i]["username"] . '</h3>
                                        <a href=\'process_vote_suggestion.php?vote=1&id=' . $rows[$i]["id"] . '&post_id=' . $post_id . '&ref_index=' . $ref_index . '\'><img src="img/like.png"></a>
                                        <span class="vote_label">Vote: ' . $rows[$i]["vote"] . '</span>
                                        <a href=\'process_vote_suggestion.php?vote=0&id=' . $rows[$i]["id"] .'&post_id=' . $post_id . '&ref_index=' . $ref_index . '\'><img src="img/dislike.png"></a>
                                    </div>
                                    <div class="right_header">
                                        <h3><span class="label">Suggestion</span></h3>
                                        <p>' . $rows[$i]["correction"] . '</p>
                                        <h3><span class="label">Additional Comments</span></h3>
                                        <p>' . $rows[$i]["comment"] . '</p>
                                    </div>
                                </div>
                                ';
                            }
                        }
                    }
                }
            }else{
                echo'
                <h1 style="text-align: center;">Error: Invalid request</h1><br>
                <h2 style="text-align: center;">Return to <a href="index.php">home page</a></h2><br>';
            }
            ?>
            <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
            <!-- Include all compiled plugins (below), or include individual files as needed -->
            <script src="js/bootstrap.min.js"></script>
</body>
</html>
