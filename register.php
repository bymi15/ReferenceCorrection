<?php
include_once 'process_register.php';
include_once 'include/functions.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reference Checker: Create an account</title>
    <link rel="stylesheet" type="text/css" href="/css/register.css">
    <script src="js/sha512.js"></script>
    <script src="js/form.js"></script>
</head>
<body>
    <div class="header"><h1><a href="index.php">Reference<span style="color:#0ca3d2">Checker</span></a></h1></div>
    <div class="container">
        <div class="register">
            <form method="post" action="<?php echo esc_url($_SERVER['PHP_SELF']); ?>">
                <h1>Register</h1>
                <p><input type="text" name="username" id="username" value="" placeholder="Username"></p>
                <p><input type="text" name="email" id="email" value="" placeholder="Email"></p>
                <p><input type="password" name="password" id="password" value="" placeholder="Password"></p>
                <p><input type="password" name="password_conf" id="conf" value="" placeholder="Verify Password"></p>
                <p class="submit"><input type="button" value="Register" onclick="return genRegHash(this.form,
                                   this.form.username,
                                   this.form.email,
                                   this.form.password,
                                   this.form.conf);" />
                </p>
            </form>
            <div id="error_message">
                <?php
                    if (!empty($error_msg)) {
                        echo $error_msg;
                    }
                ?>
            </div>

        </div>
        <div class="helper">
            <p>Registered?<a href="login.php">&nbsp; Click here to sign in!</a></p>
        </div>
    </div>

</body>
</html>
