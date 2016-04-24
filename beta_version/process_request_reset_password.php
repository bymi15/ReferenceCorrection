<?php
include_once 'include/connection.php';
error_reporting(E_ALL & ~E_NOTICE);

$error_msg = "";

if(isset($_POST['reset_username'])){
    //Sanitize and validate the data passed in
    $username = filter_input(INPUT_POST, 'reset_username', FILTER_SANITIZE_STRING);
    $email = "";

    if(empty($username)){
        echo '<script>alert("Please enter your username and try again.");</script>';
        header('refresh:0.1;url=../login.php');
    }

    $prep_stmt = "SELECT email FROM users WHERE username = ? LIMIT 1";
    $stmt = $mysqli->prepare($prep_stmt);

    //check existing username
    if ($stmt) {
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->store_result();

        //no such username exists
        if ($stmt->num_rows != 1) {
            $stmt->close();
            echo '<script>alert("The username you entered is invalid. Please try again.");</script>';
            header('refresh:0.1;url=../login.php');
        }else{ //valid user
            $stmt->bind_result($email);
            $stmt->fetch();

            $salt = $email;
            $key = hash('sha512', $salt . $username);
            $reset_url = "reset_password.php?q=" . $key;

            //Send a password reset email to the user
            $to = $email;
            $subject = 'Reference Checker - Reset your password';
            $message = '
            Dear ' . $username . "\r\n" . ',

            If this e-mail does not apply to you please ignore it. It appears that you have requested a password reset at our website' . "\r\n" . 'To reset your password, please click the link below. If you cannot click it, please copy and paste it into your web browser.' . "\n\n" . $reset_url . "\r\n" . 'Thank you,' . "\n" . 'Reference Checker - Administration';

            $headers = 'From:noreply@referencechecker.com' . "\r\n";
            mail($to, $subject, $message, $headers); // Send our email

            echo '<script>alert("Your password reset link has been sent to your email address");</script>';
            header('refresh:0.1;url=../index.php');
        }

    } else {
        $stmt->close();
        echo '<script>alert("A database error has occured. Please try again.");</script>';
        header('refresh:0.1;url=../login.php');
    }

}else{
    echo '<script>alert("Invalid request");</script>';
    header('refresh:0.1;url=../login.php');
}
?>

