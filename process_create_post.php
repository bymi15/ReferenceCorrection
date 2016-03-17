<?php
include_once("/include/connection.php");
include_once("/include/functions.php");
require_once("/lib/RISReader.php");

error_reporting(E_ALL & ~E_NOTICE);
my_session_start();

if (isset($_POST['post_title'], $_POST['post_url'], $_POST['post_references'], $_POST['post_category'])) {

    $title = strip_tags($_POST['post_title']);
    $url = strip_tags($_POST['post_url']);
    $category = strip_tags($_POST['post_category']);
    $author = strip_tags($_POST['post_author']);

    /*PARSE REFERENCES*/
    $unparsed_references = $_POST['post_references'];

    $ris = new \LibRIS\RISReader();
    $ris->parseString($unparsed_references);

    $references = implode("\n", $ris->getRecords());

    try{
        //begin a transaction
        if(!$mysqli->autocommit(FALSE)){
            echo 'An error occured while creating your post. Please try again later.<br>';
            echo 'Return to the <a href="index.php">homepage</a>';
            exit;
        }
        else{
            //insert the reference list into the references table
            //use prepared statements (to prevent SQL Injection attacks)
            $prep_stmt = "INSERT INTO reference(reference_text) VALUES(?)";
            $stmt = $mysqli->prepare($prep_stmt);
            if ($stmt) {
                $stmt->bind_param('s', $references);
                $stmt->execute();
            } else {
                echo 'Error: insert into references failed to prepare statement<br><br>';
                echo 'An error occured while creating your post. Please try again later.<br>';
                echo 'Return to the <a href="index.php">homepage</a>';
                exit;
            }

            //stores the ID of the inserted reference list
            $reference_id = $stmt->insert_id;

            //insert the post into the posts table (including the reference list id)
            $prep_stmt1 = "INSERT INTO posts(article_references, article_title, article_url, date, category, article_author, author) VALUES(?, ?, ?, ?, ?, ?, ?)";
            $stmt1 = $mysqli->prepare($prep_stmt1);
            if ($stmt1) {
                $stmt1->bind_param('isssssi', $reference_id, $title, $url, date("Y-m-d H:i:s"), $category, $author, $_SESSION['user_id']);
                $stmt1->execute();
            } else {
                echo 'Error: insert into posts failed to prepare statement<br><br>';
                echo 'An error occured while creating your post. Please try again later.<br>';
                echo 'Return to the <a href="index.php">homepage</a>';
                exit;
            }

            //stores the ID of the inserted post
            $post_id = $stmt1->insert_id;

            //commits the transaction
            $mysqli->commit();

            //end the transaction
            $mysqli->autocommit(TRUE);
        }

    }catch (Exception $e){
        //rolls back the atomic transaction
        $mysqli->rollback();
        echo 'An error occured while creating your post. Please try again later.<br>';
        echo 'Return to the <a href="index.php">homepage</a>';
        exit;
    }

    //post has been successfully created
    header('Location: ../post.php?id=' . $post_id);

} else {
    // The correct POST variables were not sent to this page.
    echo 'Invalid Request';
}

?>
