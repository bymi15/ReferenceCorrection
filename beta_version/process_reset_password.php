<?php
include_once 'include/connection.php';
error_reporting(E_ALL & ~E_NOTICE);

$error_msg = "";

if(isset($_POST['p'])){
    //Sanitize the data passed in
    $password = filter_input(INPUT_POST, 'p', FILTER_SANITIZE_STRING);
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $hash = $_POST['q'];

    $salt = "";
    $prep_stmt = "SELECT email FROM users WHERE username = ?";
    $stmt = $mysqli->prepare($prep_stmt);

    if ($stmt) {
        $query->bind_param('s', $username);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($salt);

        $key = hash('sha512', $salt . $username);

        //matching reset keys
        if($key == $hash){
            $prep_stmt = "UPDATE users SET password = :password WHERE username = :username";

            $stmt = $mysqli->prepare($prep_stmt);
            if ($stmt) {
                $query->bind_param(':password', $password);
                $query->bind_param(':username', $username);
                $stmt->execute();

                echo '<script>alert("Your password has been successfully reset.");</script>';
                header('refresh:0.1;url=../login.php');
            } else {
                $stmt->close();
                echo '<script>alert("A database error has occurred. Please try again.");</script>';
                header('refresh:0.1;url=../index.php');
            }
        }else{
            echo '<script>alert("Invalid request.\nWe cannot process your password reset request.");</script>';
            header('refresh:0.1;url=../index.php');
        }

    }else{
        echo '<script>alert("An error has occurred.\nWe cannot process your password reset request.");</script>';
        header('refresh:0.1;url=../index.php');
    }

}else{
    echo '<script>alert("Invalid POST request");</script>';
    header('refresh:0.1;url=../index.php');
}
?>

