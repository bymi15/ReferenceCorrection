<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reference Checker: Login</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

    <!-- Login css -->
    <link rel="stylesheet" type="text/css" href="css/login.css">

    <!-- fonts -->
    <?php
    include_once("fonts.php");
    ?>
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
            <p>Forgot your password?<a href="" data-toggle="modal" data-target="#myModal">&nbsp; Click here to reset it!</a></p>
            <p>Have not signed up?<a href="register.php" targer="_blank">&nbsp; Click here to register</a></p>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog">
               <!-- Modal content-->
               <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Reset your password</h4>
                </div>
                <div class="modal-body">
                    <form class="default-textbox" method="post" action="">
                        <p><span class="glyphicon glyphicon-pencil"></span> Enter your username:</p>
                        <input type="text" name="reset_username" id="reset_username" value="" maxlength="50" autofocus>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Submit Request</button>
                </div>
            </div>
        </div>
    </div>

    </div>
    <!--My Script-->
    <script src="js/sha512.js"></script>
    <script src="js/form.js"></script>
    <!--Bootstrap-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>
