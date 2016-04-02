<?php
include_once '/include/connection.php';
include_once '/include/functions.php';

error_reporting(E_ALL & ~E_NOTICE);
my_session_start();

if(isset($_GET['id'], $_GET['ref_index'], $_GET['post_id'], $_GET['vote'])){
    $id = $_GET['id'];
    $ref_index = $_GET['ref_index'];
    $post_id = $_GET['post_id'];

    $vote = 0;
    if($_GET['vote'] == 0){
        $vote = $vote - 1;
    }else{
        $vote = $vote + 1;
    }

    //update the session variables
    //check if vote has already been casted for this particular suggestion
    if (!isset($_SESSION['voted' . $id])) {
        $query = mysqli_query($mysqli, "UPDATE suggestion SET vote = vote + " . $vote . " WHERE id = " . $id);

        if(!$query){
            echo 'Database error while processing your vote';
        }else{
            $_SESSION['voted' . $id] = 1;
            header('Location: ../suggestion.php?post_id=' . $post_id . '&ref_index=' . $ref_index);
        }
    }else{
        echo '<h1 style="text-align: center;"> You have already casted your vote.</h1><br>';
    }

}


?>
