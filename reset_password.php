<?php
include_once 'include/functions.php';
?>

<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reference Checker: Reset your password</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

    <!-- Login css -->
    <link rel="stylesheet" type="text/css" href="css/register.css">

    <!-- fonts -->
    <?php
    include_once("fonts.php");
    ?>
</head>
<body>
    <div class="header"><h1><a href="index.php">Reference<span style="color:#0ca3d2">Checker</span></a></h1></div>
    <div class="container">
        <div class="register">
            <form method="post" action="process_reset_password.php">
                <h1>Reset your password</h1>
                <p>Enter your username: <input type="text" name="username" id="username" value="" placeholder="Username"></p>
                <p>Enter your new password: <input type="password" name="password" id="password" value="" placeholder="New password"></p>
                <p>Confirm your new password: <input type="password" name="conf" id="conf" value="" placeholder="Verify new password"></p>
                <input type="hidden" name="q" value="<?php
                    if (isset($_GET['q'])) {
                        echo $_GET['q'];
                    }?>"/>
                <p class="submit"><input type="button" value="Reset" onclick="return genResetHash(this.form,
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
    </div>

    <?php
    include 'footer.php';
    ?>

    <!--My Script-->
    <script src="js/sha512.js"></script>
    <script src="js/form.js"></script>
    <!--Bootstrap-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

</body>
</html>
