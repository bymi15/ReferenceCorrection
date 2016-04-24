<?php
include_once 'include/functions.php';

error_reporting(E_ALL & ~E_NOTICE);
my_session_start();

//Unset all session values
$_SESSION = array();

//Get session parameters
$params = session_get_cookie_params();

//Delete the actual cookie.
setcookie(session_name(),
        '', time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]);

//Destroy the session
session_destroy();

echo '<script>alert("You have been logged out.");</script>';

echo "<script>setTimeout(\"location.href = 'index.php';\",10);</script>";

?>
