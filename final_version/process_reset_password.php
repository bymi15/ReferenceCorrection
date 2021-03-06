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
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($salt);
        $stmt->fetch();
        
        $key = hash('sha512', $salt . $username);
        
        include_once 'lib/PasswordHash.php';
        $hasher = new PasswordHash(8, FALSE);
        $password = $hasher->HashPassword($password);
        
        //matching reset keys
        if($key == $hash){
            $prep_stmt = "UPDATE users SET password=? WHERE username=?";
            
            $stmt = $mysqli->prepare($prep_stmt);
            if ($stmt) {
                $stmt->bind_param('ss', $password, $username);
                $stmt->execute();

                echo '<script>alert("Your password has been successfully reset.");</script>';
                echo "<script>setTimeout(\"location.href = 'login.php';\",10);</script>";
            } else {
                echo '<script>alert("A database error has occurred. Please try again.");</script>';
                echo "<script>setTimeout(\"location.href = 'index.php';\",10);</script>";
            }
        }else{
            echo '<script>alert("Invalid request.\nWe cannot process your password reset request.");</script>';
            echo "<script>setTimeout(\"location.href = 'index.php';\",10);</script>";
        }

    }else{
        echo '<script>alert("An error has occurred.\nWe cannot process your password reset request.");</script>';
        echo "<script>setTimeout(\"location.href = 'index.php';\",10);</script>";
    }

}else{
    echo '<script>alert("Invalid POST request");</script>';
    echo "<script>setTimeout(\"location.href = 'index.php';\",10);</script>";
}
?>

