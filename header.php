<div class="header">
    <h1 class="mainhead">Reference<span style="color:#0ca3d2">Correction</span></h1>
    <?php
    if (login_check($mysqli) == true) {
        echo '<p>Welcome ' . htmlentities($_SESSION['username']) . '</p>';
        echo '<a href="process_logout.php"><input type="button" value="Logout" class="submit_button"/></a>';
    } else {
        echo '<a href="login.php"><input type="button" value="Login" class="submit_button"/></a>';
        echo '<a href="register.php"><input type="button" value="Register" class="submit_button"/></a>';
    }
    ?>
</div>
<div class="navigation">
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="about.php">About</a></li>
        <li>
            <a href="#">Categories</a>
            <ul>
                <li><a href="#">Cardiology &amp; Vascular Medicine</a></li>
                <li><a href="#">Endocrinology</a></li>
                <li><a href="#">Gastroenterology</a></li>
                <li><a href="#">Genetics &amp; Genomics</a></li>
                <li><a href="#">Haematology</a></li>
                <li><a href="#">Infectious Diseases</a></li>
                <li><a href="#">Neurology</a></li>
                <li><a href="#">Obstetrics &amp; Gynaecology</a></li>
                <li><a href="#">Oncology</a></li>
                <li><a href="#">Paediatrics</a></li>
                <li><a href="#">Psychiatry</a></li>
                <li><a href="#">Public Health</a></li>
                <li><a href="#">Respiratory Medicine</a></li>
                <li><a href="#">Urology</a></li>
                <li><a href="#">Other</a></li>
            </ul>
        </li>
        <li><a href="create_post.php">Post</a></li>
    </ul>
    <div class="search_bar">
        <form method="get" action="search.php">
            <input type="text" name="search" placeholder="Search...">
            <button type="button" onclick="form.submit();">Search</button>
        </form>
    </div>
</div>
