<?php
include_once 'include/connection.php';
include_once 'include/functions.php';

error_reporting(E_ALL & ~E_NOTICE);
my_session_start();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Reference Checker: About</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

    <!-- Main -->
    <link rel="stylesheet" type="text/css" href="/css/index.css">

    <!-- fonts -->
    <?php
    include_once("fonts.php");
    ?>

</head>
<body>
    <?php
    include 'header.php';
    ?>
    <!-- Banner with background image -->
    <div class="about-banner jumbotron">
        <p><img src="img/logo.jpg" class="img-circle" alt="Logo" width="200" height="200"></p>
        <h2>What is Reference Checker?</h2>
        <p>About Us</p>
    </div>
    <!-- Text banner with a button that links to home -->
    <div class="text-header jumbotron">
        <div class="container">
            <h2>References are important. Help us fix them.</h2>
            <p>How many times have you tried to chase up a reference only to find that the information is wrong? Or that the resulting paper does not justify the sentence? It is all too common and all too frustrating.</p><p>References are a fundamental part of a research paper but get very little attention. We all know they are important. They provide further information, justify comments and protect against plagiarism. But mistakes are frequent. A Cochrane review of referencing accuracy showed an average citation error rate of 38% (errors in the text details of the reference) and quotation error rate of 20% (errors of interpretation). (Wagner and Middleton, 2008)</p>
            <p style="font-weight: bold;">This website helps to fix those problems. Correct the mistakes and help others to find the information they need.</p>
            <br>
            <h2>How does this work?</h2>
            <p>ReferenceChecker relies on the users to post references from medical research papers (uploaded in a .ris or .enw format). <p>Users can then view these posts and submit their suggestion on how to fix a certain incorrect reference</p><p>These suggestions can be viewed and rated (agree or disagree) by other users.</p><p>The suggestions with the highest ratings (agreements) is always displayed at the top, which means users can easily find the best correction of an erroneous reference.</p><p>Each reference will have a status bar (green, orange, red) which indicates the validity of the reference based on the number of suggestions it has and its net ratings. For instance, if a reference has no suggestions, it will be assumed correct and the status bar will be green.</p>
            <div class="text-center">
                <a href="index.php" class="btn btn-primary">Back to Home <span class="glyphicon glyphicon-home" style="color:white"></a>
            </div>
        </div>
    </div>
    
    <?php
    include 'footer.php';
    ?>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>
