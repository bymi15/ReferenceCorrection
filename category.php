<?php
include_once("include/connection.php");
include_once("include/functions.php");

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
    <link rel="stylesheet" href="css/bootstrap.min.css" >
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- Main -->
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <!-- Hover.css -->
    <link rel="stylesheet" type="text/css" href="css/hover.css">
    <!-- Fonts -->
    <?php
    include_once("fonts.php");
    ?>
</head>
<body>
    <?php
    include 'header.php';

    if (isset($_GET['id'])) {
        $x = $_GET['id'];
        $arr = getCategories();
        $category = $arr[$x];

        echo '
        <div class="post_section container">
            <div class="panel panel-default" style="margin-top: 40px;">
                <div class="panel-heading">
                    <div class="panel-title"><h4 style="font-weight:bold">' . $category . '</h4></div>
                </div>';

        include_once 'include/connection.php';

        //Find all the posts under the category requested
        $sql = "SELECT posts.id, posts.article_title, posts.article_author, posts.date, posts.category, posts.author, posts.views, users.username FROM posts LEFT JOIN users ON posts.author = users.id WHERE posts.category = '" . $category . "' ORDER BY date DESC LIMIT 250;";

        $result = mysqli_query($mysqli, $sql);

        if(!$result)
        {
            echo '<h3>Could not display the search results. Please try again later.</h3>';
        }
        else
        {
            if(mysqli_num_rows($result) == 0)
            {
                echo '<h3 style="margin-left: 15px;">No posts were found under this category</h3>';
            }
            else
            {
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
                echo '<ul class="pagination" style="margin-bottom: 1px; margin-top: 5px;">';
                for ($i = 1; $i <= $page_count; $i++) {
                            if ($i === $page) { // this is current page
                                echo '<li class="active"><a href="#">' . $i . '</a></li>';
                            } else { // show link to other page
                                echo '<li><a href="/category.php?id='. $_GET['id'] . '&page=' . $i . '">' . $i . '</a></li>';
                            }
                        }
                        echo '</ul>';

                        echo '<hr>';

                        $rows = array();
                        while($row = mysqli_fetch_assoc($result)){
                            $rows[] = $row;
                        }

                        for($i = $offset; $i < $items_per_page + $offset; $i++){
                            if(empty($rows[$i])) continue;

                            echo '<div class="post hvr-fade" onclick="location.href=\'post.php?id=' . $rows[$i]['id'] . '\';">
                            <p class="post_title"><span class="glyphicon glyphicon-stop" style="margin-right:5px"></span>"' . $rows[$i]['article_title'] . '" <span style="font-weight: normal;">by ' . $rows[$i]['article_author'] . '</span></p>
                            <p class="date">' . $rows[$i]['date'] . '</p>
                            <p><span class="glyphicon glyphicon-th-list"></span> category: <a href="#">' . $rows[$i]['category'] . '</a><span class="glyphicon glyphicon-user"></span> author: <a href="#">' . $rows[$i]['username'] . '</a></p>
                            <p><span class="glyphicon glyphicon-eye-open"></span> views: ' . $rows[$i]['views'] . '</p>
                        </div>';
                    }
                }
            }
            echo'</div></div>';

    }else{
        // The correct GET variables were not sent to this page.
        echo '<h3>Invalid Request</h3>';
    }
    ?>
        </div>
    </div>

    <?php
    include 'footer.php';
    ?>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
</body>
</html>
