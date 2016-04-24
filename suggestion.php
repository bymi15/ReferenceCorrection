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
    <title>Reference Checker: Suggestions</title>

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

    $by = "Anonymous";
    if (login_check($mysqli) == true) {
        $by = $_SESSION['username'];
    }

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
                            <p style="font-size:19px"><span class="glyphicon glyphicon-user"></span><span class="label" style="font-size:19px">Username:</span> <a>' . $author_username . '</a></p>
                            <p><span class="glyphicon glyphicon-eye-open"></span><span class="label">Views:</span> ' . $views . '</p>
                            <p><span class="glyphicon glyphicon-th-list"></span> <span class="label">Category:</span> <a>' . $category . '</a></p>
                        </div>
                        <p style="font-size:16px;"><span class="glyphicon glyphicon-pencil"></span><span class="label">Article Title:</span> ' . $article_title . '</p>
                        <p><span class="glyphicon glyphicon-tag"></span><span class="label">Article url:</span><a target="_blank" href="' . $article_url .'">' . $article_url .'</a></p>
                        <p><span class="glyphicon glyphicon-book"></span><span class="label">Author(s):</span>' . $article_author . '</p>
                        <p><span class="glyphicon glyphicon-calendar"></span><span class="label">Date posted:</span>' . $date . '</p>
                    </div>

                    <div class="suggestion_form container panel panel-default">
                        <div class="current-reference panel-heading">
                            <h3 style="display: inline-block;">Suggestions</h3>
                            <a href="add_suggestion.php?post_id=' . $post_id . '&ref_index=' . $ref_index . '"><button class="btn btn-primary" style="float: right; margin-top: 15px; margin-left: 10px;"><span class="glyphicon glyphicon-plus-sign"></span> Add a suggestion</button></a>
                            <a href="post.php?id=' . $post_id . '"><button class="btn btn-warning" style="float: right; margin-top: 15px;"><span class="glyphicon glyphicon-menu-left"></span> Return to post</button></a>
                            <h3 style="font-size:19px">The current reference is: <span style="color:#197B9C">' . $reference_list[$ref_index] . '</span></h3>
                        </div>';

                        echo '<a name="c_page"></a>'; //anchor tag

                        $sql = "SELECT suggestion.id, suggestion.comment, suggestion.correction, suggestion.vote, suggestion.author, suggestion.error_type, users.username FROM suggestion LEFT JOIN users ON suggestion.author = users.id WHERE post_id=" . $post_id . " AND reference_index=" . $ref_index . " ORDER BY vote DESC";

                        $result = mysqli_query($mysqli, $sql);

                        if(mysqli_num_rows($result) <= 0){
                         echo'
                         <div class="suggestion_buttons" style="margin-bottom: 0; padding-bottom: 0;">
                            <h4 style="font-family: \'Roboto\', sans-serif; font-weight: bold; margin-bottom: 5px; margin-top: 20px; margin-left: 15px;">No suggestions have been made.</h4>
                        </div>
                        ';
                        }else{
                            $rows = array();
                            while($row = mysqli_fetch_assoc($result)){
                                $rows[] = $row;
                            }

                            //seperate the citation and quotation errors into two different arrays
                            $cRows = array();
                            $qRows = array();
                            for($i = 0; $i < count($rows); $i++){
                                if($rows[$i]["error_type"] == 0){
                                    $cRows[] = $rows[$i];
                                }else{
                                    $qRows[] = $rows[$i];
                                }
                            }

                            //Citation errors
                            echo '
                            <div class="panel panel-heading" style="margin-bottom: 0px;">
                                <h3 style="text-align: center; margin-top: 0px; font-weight: bold;">Citation Errors</h3><p style="margin: 0px; padding 0px; text-align: center; font-style: italic;">Error in reference text</p>
                            ';
                            if(empty($cRows)){
                                echo '
                                <div class="suggestion">
                                    <h3>No citation errors to display</h3>
                                </div>
                            </div>
                                ';
                            }else{
                                //pagination
                                $page = 1;
                                if(isset($_GET['c_page'])) {
                                    $page = filter_input(INPUT_GET, 'c_page', FILTER_VALIDATE_INT);
                                    if($page === false) {
                                        $page = 1;
                                    }
                                }
                                $items_per_page = 3;
                                $offset = ($page - 1) * $items_per_page;

                                $row_count = count($cRows);
                                $page_count = 0;
                                if ($row_count !== 0){
                                    $page_count = (int)ceil($row_count / $items_per_page);
                                    if($page > $page_count) {
                                        $page = 1;
                                    }
                                }
                                echo '<ul class="pagination" style="margin-top: 0px; margin-bottom: 0px;">';

                                for ($i = 1; $i <= $page_count; $i++) {
                                    if ($i === $page) { // this is current page
                                        echo '<li class="active"><a href="#">' . $i . '</a></li>';
                                    } else { // show link to other page
                                        echo '<li><a href="/suggestion.php?ref_index=' . $ref_index . '&post_id=' . $post_id . '&c_page=' . $i . '#c_page">' . $i . '</a></li>';
                                    }
                                }
                                echo '</ul>
                                </div>';

                                for($i = $offset; $i < $items_per_page + $offset; $i++){
                                    if(empty($cRows[$i])) continue;

                                    echo'
                                    <div class="suggestion">
                                        <div class="user-header">
                                            <div class="suggestion-username">
                                                <span class="glyphicon glyphicon-user"></span><span style="font-family: \'Source Sans Pro\', sans-serif; font-weight: bold; font-size: 20px;">User: ' . $cRows[$i]["username"] . '</span>
                                            </div>
                                            <div class="agreement-buttons">
                                                <a href=\'process_vote_suggestion.php?vote=1&id=' . $cRows[$i]["id"] . '&post_id=' . $post_id . '&ref_index=' . $ref_index . '\'><button type="button" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-thumbs-up" style="color:white"></span> I Agree</button></a>
                                                <span style="font-size: 26px; font-weight: bold; margin-left: 10px; margin-right: 10px;">' . $cRows[$i]["vote"] . '</span>
                                                <a data-toggle="modal" data-target="#modal' . $cRows[$i]["id"] . '"><button type="button" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-thumbs-down" style="color:white"></span> I Disagree</button></a>
                                            </div>
                                        </div>


                                        <div class="modal fade" id="modal' . $cRows[$i]["id"] . '" role="dialog">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        <h4 class="modal-title">Disagree with this suggestion?</h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form class="default-textbox" method="post" action="process_reply_suggestion.php">
                                                            <p><span class="glyphicon glyphicon-comment"></span> Why do you disagree? (optional)</p>
                                                            <p><textarea name="comment" id="comment" placeholder="Add a comment to explain why you disagree with this suggestion"></textarea></p>
                                                            <input type="hidden" name="by" value="' . $by . '">
                                                            <input type="hidden" name="id" value="' . $cRows[$i]["id"] . '">
                                                            <input type="hidden" name="post_id" value="' . $post_id . '">
                                                            <input type="hidden" name="ref_index" value="' . $ref_index . '">
                                                            <input type="hidden" name="vote" value="0">
                                                            <br>
                                                            <div style="text-align: center;">
                                                            <button type="submit" class="btn btn-primary">Confirm</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="suggestion-content">
                                            <p class="suggestion-label" ><i class="fa fa-lightbulb-o"></i> Correction</p>
                                            <p>' . $cRows[$i]["correction"] . '</p><br>';
                                            if(!empty($cRows[$i]["comment"])){
                                                echo'
                                                <p class="suggestion-label" ><span class="glyphicon glyphicon-list-alt"></span> Additional Comments</p>
                                                <p>' . $cRows[$i]["comment"] . '</p><br>
                                                ';
                                            }
                                            echo'
                                            <div class="replies-section">
                                                <p class="suggestion-label"><span class="glyphicon glyphicon-comment"></span> Replies</p>';

    //RETRIEVE THE REPLIES FOR THIS SUGGESTION
    $sql = "SELECT comment, reply_by FROM replies WHERE suggestion_id=" . $cRows[$i]["id"];

    $result = mysqli_query($mysqli, $sql);

    if(mysqli_num_rows($result) > 0){
        $replies = array();
        while($row = mysqli_fetch_assoc($result)){
            $replies[] = $row;
        }

        for($x = 0; $x < count($replies); $x++){
            echo'<p class="reply"><span style="color:#3E81AD">' . $replies[$x]["reply_by"] . '</span>: ' . $replies[$x]["comment"] . '</p>';
        }
    }

                                            echo'
                                            </div>
                                        </div>
                                    </div>
                                    ';
                                }
                            }

                            //Quotation errors
                            echo '
                            <div class="panel panel-heading" style="margin-bottom: 0px; margin-top: 10px;">
                                <h3 style="text-align: center; margin-top: 0px; font-weight: bold;">Quotation Errors</h3><p style="margin: 0px; padding 0px; text-align: center; font-style: italic;">Error of interpretation</p>
                            ';
                            if(empty($qRows)){
                                echo '
                                <div class="suggestion">
                                    <h3>No quotation errors to display</h3>
                                </div>
                            </div>
                                ';
                            }else{
                                //pagination
                                $page = 1;
                                if(isset($_GET['q_page'])) {
                                    $page = filter_input(INPUT_GET, 'q_page', FILTER_VALIDATE_INT);
                                    if($page === false) {
                                        $page = 1;
                                    }
                                }
                                $items_per_page = 3;
                                $offset = ($page - 1) * $items_per_page;

                                $row_count = count($qRows);
                                $page_count = 0;
                                if ($row_count !== 0){
                                    $page_count = (int)ceil($row_count / $items_per_page);
                                    if($page > $page_count) {
                                        $page = 1;
                                    }
                                }
                                echo '<a name="q_page"></a>';
                                echo '<ul class="pagination" style="margin-top: 0px; margin-bottom: 0px;">';

                                for ($i = 1; $i <= $page_count; $i++) {
                                    if ($i === $page) { // this is current page
                                        echo '<li class="active"><a href="#">' . $i . '</a></li>';
                                    } else { // show link to other page
                                        echo '<li><a href="/suggestion.php?ref_index=' . $ref_index . '&post_id=' . $post_id . '&q_page=' . $i . '#q_page">' . $i . '</a></li>';
                                    }
                                }
                                echo '</ul>
                            </div>';

                            for($i = $offset; $i < $items_per_page + $offset; $i++){
                                if(empty($qRows[$i])) continue;

                                echo'
                                <div class="suggestion">
                                    <div class="user-header">
                                        <div class="suggestion-username">
                                            <span class="glyphicon glyphicon-user"></span> <span style="font-family: \'Source Sans Pro\', sans-serif; font-weight: bold; font-size: 20px;">User: ' . $qRows[$i]["username"] . '
                                            </span>
                                        </div>
                                        <div class="agreement-buttons">
                                            <a href=\'process_vote_suggestion.php?vote=1&id=' . $qRows[$i]["id"] . '&post_id=' . $post_id . '&ref_index=' . $ref_index . '\'><button type="button" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-thumbs-up" style="color:white"></span> I Agree</button></a>
                                            <span style="font-size: 26px; font-weight: bold; margin-left: 10px; margin-right: 10px;">' . $qRows[$i]["vote"] . '</span>
                                            <a data-toggle="modal" data-target="#modal' . $qRows[$i]["id"] . '"><button type="button" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-thumbs-down" style="color:white"></span> I Disagree</button></a>
                                        </div>
                                    </div>

                                    <div class="modal fade" id="modal' . $qRows[$i]["id"] . '" role="dialog">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    <h4 class="modal-title">Disagree with this suggestion?</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <form class="default-textbox" method="post" action="process_reply_suggestion.php">
                                                        <p><span class="glyphicon glyphicon-comment"></span> Why do you disagree? (optional)</p>
                                                        <p><textarea name="comment" id="comment" placeholder="Add a comment to explain why you disagree with this suggestion"></textarea></p>
                                                            <input type="hidden" name="by" value="' . $by . '">
                                                            <input type="hidden" name="id" value="' . $qRows[$i]["id"] . '">
                                                            <input type="hidden" name="post_id" value="' . $post_id . '">
                                                            <input type="hidden" name="ref_index" value="' . $ref_index . '">
                                                            <input type="hidden" name="vote" value="0">
                                                            <br>
                                                            <div style="text-align: center;">
                                                            <button type="submit" class="btn btn-primary">Confirm</button>
                                                            </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="suggestion-content">
                                        <p class="suggestion-label" ><i class="fa fa-lightbulb-o"></i> Correction</p>
                                        <p>' . $qRows[$i]["correction"] . '</p><br>';
                                        if(!empty($qRows[$i]["comment"])){
                                                echo'
                                                <p class="suggestion-label" ><span class="glyphicon glyphicon-list-alt"></span> Additional Comments</p>
                                                <p>' . $qRows[$i]["comment"] . '</p><br>';
                                        }
                                        echo'
                                        <div class="replies-section">
                                            <p class="suggestion-label"><span class="glyphicon glyphicon-comment"></span> Replies</p>';

    //RETRIEVE THE REPLIES FOR THIS SUGGESTION
    $sql = "SELECT comment, reply_by FROM replies WHERE suggestion_id=" . $qRows[$i]["id"];

    $result = mysqli_query($mysqli, $sql);

    if(mysqli_num_rows($result) > 0){
        $replies = array();
        while($row = mysqli_fetch_assoc($result)){
            $replies[] = $row;
        }

        for($x = 0; $x < count($replies); $x++){
            echo'<p class="reply"><span style="color:#3E81AD">' . $replies[$x]["reply_by"] . '</span>: ' . $replies[$x]["comment"] . '</p>';
        }
    }

                                        echo'
                                        </div>
                                    </div>
                                </div>
                                ';
                            }
                            echo '
                        </div>';
                    }
                }
            }
        }else{
            echo'
            <h1 style="text-align: center;">Error: Invalid request</h1><br>
            <h2 style="text-align: center;">Return to <a href="index.php">home page</a></h2><br>';
        }
        ?>
        </div>

        <?php
        include 'footer.php';
        ?>
        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
</body>
</html>
