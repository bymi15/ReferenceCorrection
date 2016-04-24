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
        echo "<script>setTimeout(\"location.href = 'login.php';\",10);</script>";
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
            echo "<script>setTimeout(\"location.href = 'login.php';\",10);</script>";
        }else{ //valid user
            $stmt->bind_result($email);
            $stmt->fetch();

            $salt = $email;
            $key = hash('sha512', $salt . $username);
            $reset_url = "http://www.referencechecker.info/reset_password.php?q=" . $key;
            //Send a password reset email to the user
            $to = $email;
            $subject = 'Reference Checker - Reset your password';
            $message = '
                <html>
                <head>Reference Checker - Reset Password!</head>
                <body>
                  <p>Dear ' . $username . ',</p>
                  You have recently requested to reset your password.<br>
                  If this e-mail does not apply to you please ignore it.<br>
                  <p>To reset your password, please click the link below. If you cannot click it, copy and paste it into your web browser.</p><a href='
                 . $reset_url .'>' . $reset_url . '</a>
                <p>Thank you,<br>
                Reference Checker<br>
                Administration</p>
                </body>
                </html>';

            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            $headers .= 'From: ReferenceChecker <admin@referencechecker.info>' . "\r\n";
            mail($to, $subject, $message, $headers); // Send our email


            echo '<script>alert("Your password reset link has been sent to your email address");</script>';
            echo "<script>setTimeout(\"location.href = 'index.php';\",10);</script>";
        }

    } else {
        $stmt->close();
        echo '<script>alert("A database error has occured. Please try again.");</script>';
        echo "<script>setTimeout(\"location.href = 'login.php';\",10);</script>";
    }

}else{
    echo '<script>alert("Invalid request");</script>';
    echo "<script>setTimeout(\"location.href = 'login.php';\",10);</script>";
}
?>

