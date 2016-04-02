<?php
include_once '/include/connection.php';
include_once '/include/functions.php';

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
    <title>Reference Checker</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- Hover.css -->
    <link rel="stylesheet" type="text/css" href="css/hover.css">
    <!-- Main -->
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <!-- Fonts -->
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Alegreya+Sans' rel='stylesheet' type='text/css'>

    <link rel="stylesheet" type="text/css" href="/css/index.css">
</head>
<body>
    <?php
    include 'header.php';

    if (isset($_GET['id'])) {

        $id = $_GET['id'];

            //update the view count
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
                $items_per_page = 6;
                $offset = ($page - 1) * $items_per_page;

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

                    <div class="references_section container panel panel-default">
                        <div class="panel-heading">
                        <h3>References</h3>
                        </div>';

                        $row_count = count($reference_list);
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
                                echo '<li><a href="/post.php?id=' . $id . '&page=' . $i . '">' . $i . '</a></li>';
                            }
                        }
                echo '</ul>';

                echo '<ul class="reference_list">';

                /*Retrieve votes from database*//*
                $sql = "SELECT reference_text FROM reference WHERE id=" . $reference_id;
                $result = mysqli_query($mysqli, $sql);

                $references = mysqli_fetch_assoc($result)*/

                /*Display references*/
                for($i = $offset; $i < $items_per_page + $offset; $i++){
                    if(!empty($reference_list[$i])){
                        echo'
                        <li class="reference hvr-border-fade"><p>' . $reference_list[$i] . '</p>
                            <div class="suggestion_buttons">
                                <a href="add_suggestion.php?post_id=' . $id . '&ref_index=' . $i . '"><span class="glyphicon glyphicon-plus-sign"></span> Add Suggestions</a>
                                <a href="suggestion.php?post_id=' . $id . '&ref_index=' . $i . '"><span class="glyphicon glyphicon-eye-open"></span> View Suggestions</a>
                                <div class="voting_buttons">
                                    <span class="glyphicon glyphicon-thumbs-up" style="color:#2380EB"></span>
                                    <span class="glyphicon glyphicon-thumbs-down" style="color:red"></span> <span style="font-weight:bold">Votes:</span>
                                </div>
                            </div>
                        </li>';
                        }
                    }

                echo'</ul>

            </div>
            ';
        }
    }

    ?>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
</body>
</html>
