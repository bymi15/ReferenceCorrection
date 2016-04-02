<div class="first-nav-bar">
    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><span class="glyphicon glyphicon-home" style="color:white"></span> <span style="color:white">Reference</span><span style="color:#0ca3d2">Checker</span></a>
            <ul class="nav navbar-nav navbar-right">
                <?php
                if (login_check($mysqli) == true) {
                    echo '<span class="navbar-brand">Welcome ' . htmlentities($_SESSION['username']) . "</span>";
                    echo ' <a href="process_logout.php" class="btn btn-primary btn-sm">Logout</a>';
                } else {
                    echo '<a href="login.php" class="btn btn-primary btn-sm">Login</a>
                        <a href="register.php" class="btn btn-primary btn-sm">Register</a>';
                }
                ?>
            </ul>
        </div>
    </nav>
</div>

<!-- Navigation bar and Website icon -->
<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <!-- <a class="navbar-brand" href="#"><span class="glyphicon glyphicon-home"></span> ReferenceChecker</a> -->
        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
            <ul class="nav navbar-nav navbar-left">
                <li><a href="index.php">Home</a></li>
                <li><a href="about.php">About</a></li>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">Categories
                        <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <?php
                                include_once '/include/functions.php';
                                $categories = getCategories();
                                for($i = 0; $i < count($categories); $i++){
                                    echo'
                                        <li><a href="/category.php?id=' . $i . '">' . $categories[$i] . '</a>
                                        </li>
                                    ';
                                }
                            ?>
                        </ul>
                    </li>
                    <li><a href="create_post.php">Post</a></li>
                </ul>
                <div class="col-sm-3 col-md-3 pull-right">
                    <form class="navbar-form" role="search" method="get" action="search.php">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search" name="search" id="search">
                            <div class="input-group-btn">
                                <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </nav>
