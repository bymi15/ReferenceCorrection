<!DOCTYPE html>
<html>
<head>
    <title>Reference Checker: Login</title>
    <link rel="stylesheet" type="text/css" href="/css/login.css">
    <script src="js/sha512.js"></script>
    <script src="js/form.js"></script>
</head>
<body>
    <div class="header"><h1><a href="index.php">Reference<span style="color:#0ca3d2">Checker</span></a></h1></div>
    <div class="container">
        <div class="login">
            <form method="post" action="process_login.php" onsubmit="return genHash(this)">
                <h1>Login</h1>
                <p><input type="text" name="username" id="username" value="" placeholder="Username" maxlength="50"></p>
                <p><input type="password" name="password" id="password" value="" placeholder="Password" maxlength="50"></p>
                <p class="remember_me">
                    <label>
                        <input type="checkbox" name="remember_me" id="remember_me">
                        Remember me on this computer
                    </label>
                </p>
                <p class="submit"><input type="submit" value="Login"/></p>
            </form>
            <div id="error_message">
                <?php
                    if (isset($_GET['error'])) {
                        echo '<p class="error">Incorrect username or password! Please try again.</p>';
                    }
                ?>
            </div>
        </div>
        <div class="login-help">
            <p>Forgot your password?<a href="#">&nbsp; Click here to reset it!</a></p>
            <p>Have not signed up?<a href="register.php" targer="_blank">&nbsp; Click here to register</a></p>
        </div>
    </div>
</body>
</html>
