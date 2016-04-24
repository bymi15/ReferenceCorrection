<?php
include_once 'include/connection.php';
include_once 'include/functions.php';
error_reporting(E_ALL & ~E_NOTICE);

$error_msg = "";

if(isset($_POST['username'], $_POST['email'], $_POST['p'])){
    //Sanitize and validate the data passed in
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Not a valid email
        $error_msg .= '<p class="error">The email address you entered is not valid</p>';
    }

    $password = filter_input(INPUT_POST, 'p', FILTER_SANITIZE_STRING);
    if (strlen($password) != 128) {
        // The hashed pwd should be 128 characters long.
        // If it's not, something really odd has happened
        $error_msg .= '<p class="error">Invalid password configuration.</p>';
    }

    // Username validity and password validity have been checked client side.
    // This should should be adequate as nobody gains any advantage from
    // breaking these rules.


    $prep_stmt = "SELECT id FROM users WHERE email = ? LIMIT 1";
    $stmt = $mysqli->prepare($prep_stmt);

   // check existing email
    if ($stmt) {
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            // A user with this email address already exists
            $error_msg .= '<p class="error">A user with this email address already exists.</p>';
                        $stmt->close();
        }
    } else {
        $error_msg .= '<p class="error">Database error Line 39</p>';
                $stmt->close();
    }

    // check existing username
    $prep_stmt = "SELECT id FROM users WHERE username = ? LIMIT 1";
    $stmt = $mysqli->prepare($prep_stmt);

    if ($stmt) {
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->store_result();

                if ($stmt->num_rows == 1) {
                        // A user with this username already exists
                        $error_msg .= '<p class="error">A user with this username already exists</p>';
                        $stmt->close();
                }
        } else {
                $error_msg .= '<p class="error">Database error line 55</p>';
                $stmt->close();
        }

    if (empty($error_msg)) {

        // Uses the phpass password hashing library to MD5 hash and salt the password
        include_once 'lib/PasswordHash.php';
        $hasher = new PasswordHash(8, FALSE);
        $password = $hasher->HashPassword($password);

        // Insert the new user into the database
        if ($insert_stmt = $mysqli->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)")) {
            $insert_stmt->bind_param('sss', $username, $email, $password);
            // Execute the prepared query.
            if (! $insert_stmt->execute()) {
                header('Location: ../error.php?err=Registration Error: INSERT');
            }else{
                //Send a welcome email to the user
                $to = $email;
                $subject = 'Welcome to Reference Checker';
                $message = '
                <html>
                <head>Welcome to Reference Checker!</head>
                <body>
                  <p>Dear ' . $username . ',</p>
                  Thanks for signing up!<br><br>
                  Your account has been created, you can now login with your credentials. You are now able to create posts and add suggestions.<br>
                  <br>Get started at: <a href="http://www.referencechecker.info">
                http://www.referencechecker.info</a>
                <p>Best Regards,<br>
                Reference Checker<br>
                Administration</p>
                </body>
                </html>';

                $headers  = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                $headers .= 'From: ReferenceChecker <admin@referencechecker.info>' . "\r\n";
                mail($to, $subject, $message, $headers); // Send our email
            }
        }
        header('Location: ../register_success.php?user=' . $username);
    }
}
?>

