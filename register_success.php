<!DOCTYPE html>
<html>

<?php
    if(isset($_GET['user'])){
        $user = strip_tags($_GET['user']);
        echo '<head>
                <title>Registration Successful</title>
                <link rel="stylesheet" href="register.css" />
            </head>
            <body>
                <div class="header"><h1><a href="index.php">Reference<span style="color:#0ca3d2">Correction</span></a></h1></div>
                 <div class="container">
                    <div class="register">
                        <h1>Registration successful!</h1>
                        <p>Hello <strong>' . $user . ',</strong></p>
                        <p>Welcome to Reference Correction.</p>
                        <p>You can now go back to the <a href="login.php">login page</a> and log in.</p><br>
                        <div style="text-align: center;"><a href="login.php" class="submit"><input type="button" style="" value="Return to login page" /></a></div>
                    </div>
                 </div>
            </body>';
    }
?>

</html>
