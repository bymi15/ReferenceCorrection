<?php

    $username = "";
    $password = "";
    $database = "";
    $server = "";

    $mysqli = mysqli_connect($server, $username, $password, $database);
    mysqli_select_db($mysqli, "reference_checker");

    if(mysqli_connect_errno()){
        print "Error! Database could not be found.";
        exit;
    }

?>
