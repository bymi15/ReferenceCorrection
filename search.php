<?php
include_once("/include/connection.php");
include_once("/include/functions.php");

error_reporting(E_ALL & ~E_NOTICE);
my_session_start();

if (isset($_GET['search'])){

    $search_query = strip_tags($_POST['search']);

    $result = search($search_query, $mysqli);

    echo "<h1> Search Results</h1>";
    echo $result;

} else {
    // The correct POST variables were not sent to this page.
    echo 'Invalid Request';
}

?>
