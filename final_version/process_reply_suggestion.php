<?php
include_once 'include/connection.php';
include_once 'include/functions.php';

error_reporting(E_ALL & ~E_NOTICE);
my_session_start();

if (isset($_POST['id'], $_POST['ref_index'], $_POST['post_id'], $_POST['vote'], $_POST['comment'], $_POST['by'])) {
    $id = $_POST['id'];
    $ri = $_POST['ref_index'];
    $pi = $_POST['post_id'];
    $vote = $_POST['vote'];
    $comment = $_POST['comment'];
    $by = $_POST['by'];

    if (isset($_SESSION['voted' . $id])) {
        echo '<script>alert("You have already casted your vote.");</script>';
        echo "<script>setTimeout(\"location.href = 'suggestion.php?post_id=" . $pi . "&ref_index=" . $ri . "';\",10);</script>";
    }else{
        //check if the comment is empty or not
        if(empty($comment)){
            header('Location: ../process_vote_suggestion.php?id=' . $id . '&post_id=' . $pi . '&ref_index=' . $ri . '&vote=' . $vote);
        }else{
            if ($insert_stmt = $mysqli->prepare("INSERT INTO replies (suggestion_id, comment, reply_by) VALUES (?, ?, ?)")) {
                $insert_stmt->bind_param('iss', $id, $comment, $by);
            // Execute the prepared query.
                if ($insert_stmt->execute()) {
                //success
                    header('Location: ../process_vote_suggestion.php?id=' . $id . '&post_id=' . $pi . '&ref_index=' . $ri . '&vote=' . $vote);
                }else{ //error while executing query
                    echo '<script>alert("Database error: INSERT");</script>';
                    echo "<script>setTimeout(\"location.href = 'suggestion.php?post_id=" . $pi . "&ref_index=" . $ri . "';\",10);</script>";
                }
            }
        }
    }
}else{
    echo '<script>alert("Invalid parameters were passed.");</script>';
    echo "<script>setTimeout(\"location.href = 'index.php';\",10);</script>";
}

?>
