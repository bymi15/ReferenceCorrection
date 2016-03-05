<div class="header">
    <h1 class="mainhead">Reference<span style="color:#0ca3d2">Correction</span></h1>
    <?php
    if (login_check($mysqli) == true) {
        echo '<p>Welcome ' . htmlentities($_SESSION['username']) . '</p>';
        echo '<a href="process_logout.php"><input type="button" value="Logout" onclick=""/></a>';
    } else {
        echo '<a href="login.php"><input type="button" value="Login" onclick=""/></a>';
        echo '<a href="register.php"><input type="button" value="Register" onclick=""/></a>';
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
            </ul>
        </li>
        <li><a href="#">Post</a></li>
    </ul>
    <div class="Search_bar">
        <form>
            <input type="text" name="search" placeholder="Search...">
            <a href="#"><button type="button">Search</button></a>
        </form>
    </div>
</div>
