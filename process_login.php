<?php
include_once("include/connection.php");
include_once("include/functions.php");

error_reporting(E_ALL & ~E_NOTICE);
my_session_start();

if (isset($_POST['username'], $_POST['p'])) {
    $username = strip_tags($_POST['username']);
    $password = strip_tags($_POST['p']); // The hashed password.

    if (attempt_login($username, $password, $mysqli)) {
        // Login success
        header('Location: ../index.php');
    } else {
        // Login failed
        header('Location: ../login.php?error=1');
    }
} else {
    // The correct POST variables were not sent to this page.
    echo 'Invalid Request';
}

?>
