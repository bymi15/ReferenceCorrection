<?php
include_once("/include/connection.php");
include_once("/include/functions.php");

error_reporting(E_ALL & ~E_NOTICE);
my_session_start();

if (isset($_POST['correction'], $_POST['comment'], $_POST['post_id'], $_POST['ref_index'], $_POST['by'])) {

    $correction = strip_tags($_POST['correction']);
    $comment = strip_tags($_POST['comment']);
    $post_id = strip_tags($_POST['post_id']);
    $ref_index = strip_tags($_POST['ref_index']);
    $by = strip_tags($_POST['by']);

    //insert the correction and comment into the suggestion table
    //use prepared statements (to prevent SQL Injection attacks)
    $prep_stmt = "INSERT INTO suggestion(post_id, reference_index, correction, comment, author) VALUES(?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($prep_stmt);
    if ($stmt) {
        $stmt->bind_param('iissi', $post_id, $ref_index, $correction, $comment, $by);
        $stmt->execute();
    } else {
        echo 'Error: insert into suggestion failed to prepare statement<br><br>';
        echo 'An error occured while adding your suggestion. Please try again later.<br>';
        echo 'Return to the <a href="index.php">homepage</a>';
        $stmt->close();
        exit;
    }

    //suggestion has been successfully added
    header('Location: ../suggestion.php?post_id=' . $post_id . '&ref_index=' . $ref_index);

} else {
    // The correct POST variables were not sent to this page.
    echo 'Invalid Request';
}

?>
