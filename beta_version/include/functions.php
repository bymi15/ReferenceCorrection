<?php

function my_session_start() {
    /*
    $session_name = 'my_session_id';   // Set a custom session name
    $secure = true;
    // This stops JavaScript being able to access the session id.
    $httponly = true;
    // Forces sessions to only use cookies.
    if (ini_set('session.use_only_cookies', 1) === FALSE) {
        header("Location: ../error.php?err=Could not initiate a safe session (ini_set)");
        exit();
    }
    // Gets current cookies params.
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params($cookieParams["lifetime"],
        $cookieParams["path"],
        $cookieParams["domain"],
        $secure,
        $httponly);
    // Sets the session name to the one set above.
    session_name($session_name);
    */
    session_start();            // Start the PHP session
    session_regenerate_id(true);    // regenerated the session, delete the old one.
}

function getUserIP()
{
    $client  = $_SERVER['HTTP_CLIENT_IP'];
    $forward = $_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if(filter_var($client, FILTER_VALIDATE_IP))
    {
        $ip = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP))
    {
        $ip = $forward;
    }
    else
    {
        $ip = $remote;
    }

    return $ip;
}

function attempt_login($username, $password, $mysqli) {
    $ip_address = getUserIP();

    // Using prepared statements prevents SQL Injections
    if ($stmt = $mysqli->prepare("SELECT id, username, password FROM users WHERE username = ? LIMIT 1")) {

        $stmt->bind_param('s', $username);  // Bind "$username" to parameter.
        $stmt->execute();    // Execute the prepared query.
        $stmt->store_result();

        // get variables from result.
        $stmt->bind_result($user_id, $db_username, $db_password);
        $stmt->fetch();

        if ($stmt->num_rows == 1) {
            // If the user exists we check if the account is locked
            // from too many login attempts

            if (check_brute($user_id, $ip_address, $mysqli) == true) {
                // Account is locked
                // Send an email to user saying their account is locked
                return false;
            } else {
                //For older version of PHP:
                /*
                include_once (dirname(__DIR__) . '/lib/PasswordHash.php');
                $hasher = new PasswordHash(8, FALSE);
                $check = $hasher->CheckPassword($password, $db_password);
                if($check){
                    //password is correct
                }
                */

                //For PHP version 5.5+
                // Check if the password in the database matches
                // the password the user submitted. We are using
                // the password_verify function to avoid timing attacks.
                if (password_verify($password, $db_password)) {
                    // Password is correct!
                    // Get the user-agent string of the user.
                    $user_browser = $_SERVER['HTTP_USER_AGENT'];
                    // XSS protection as we might print this value
                    $user_id = preg_replace("/[^0-9]+/", "", $user_id);
                    $_SESSION['user_id'] = $user_id;
                    // XSS protection as we might print this value
                    $username = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $username);
                    $_SESSION['username'] = $username;
                    $_SESSION['login_string'] = hash('sha512', $db_password . $user_browser);

                    $message = $_SESSION['user_id'] . $_SESSION['username'] . $_SESSION['login_string'];

                    // Login successful.
                    return true;
                } else {
                    // Password is not correct
                    // We record this attempt in the database
                    $now = time();
                    $mysqli->query("INSERT INTO login_attempts(user_id, ip_address, time)
                                    VALUES ('$user_id', '$ip_address', '$now')");
                    return false;
                }
            }

        } else {
            // No user exists.
            return false;
        }
    }
}


/*This function prevents brute-force attacks*/
function check_brute($user_id, $ip_address, $mysqli) {
    // Get timestamp of current time
    $now = time();

    // All login attempts are counted from the past 2 hours.
    $valid_attempts = $now - (2 * 60 * 60);

    if ($stmt = $mysqli->prepare("SELECT time
                             FROM login_attempts
                             WHERE user_id = ?
                             AND ip_address = ?
                            AND time > '$valid_attempts'")) {
        $stmt->bind_param('is', $user_id, $ip_address);

        // Execute the prepared query.
        $stmt->execute();
        $stmt->store_result();

        // If there have been more than 5 failed logins
        if ($stmt->num_rows > 5) {
            return true;
        } else {
            return false;
        }
    }else{
        exit;
    }
}

if(!function_exists('hash_equals'))
{
    function hash_equals($str1, $str2)
    {
        if(strlen($str1) != strlen($str2))
        {
            return false;
        }
        else
        {
            $res = $str1 ^ $str2;
            $ret = 0;
            for($i = strlen($res) - 1; $i >= 0; $i--)
            {
                $ret |= ord($res[$i]);
            }
            return !$ret;
        }
    }
}

function login_check($mysqli) {
    // Check if all session variables are set
    if (isset($_SESSION['user_id'], $_SESSION['username'], $_SESSION['login_string'])) {

        $user_id = $_SESSION['user_id'];
        $username = $_SESSION['username'];
        $login_string = $_SESSION['login_string'];

        // Get the user-agent string of the user.
        $user_browser = $_SERVER['HTTP_USER_AGENT'];

        if ($stmt = $mysqli->prepare("SELECT password
                                      FROM users
                                      WHERE id = ? LIMIT 1")) {
            // Bind "$user_id" to parameter.
            $stmt->bind_param('i', $user_id);
            $stmt->execute();   // Execute the prepared query.
            $stmt->store_result();

            if ($stmt->num_rows == 1) {
                // If the user exists get variables from result.
                $stmt->bind_result($password);
                $stmt->fetch();
                $login_check = hash('sha512', $password . $user_browser);

                if (hash_equals($login_check, $login_string) ){
                    // Logged In!!!!
                    return true;
                } else {
                    // Not logged in
                    return false;
                }
            } else {
                // Not logged in
                return false;
            }
        } else {
            // Not logged in
            return false;
        }
    } else {
        // Not logged in
        return false;
    }
}

function esc_url($url) {

    if ('' == $url) {
        return $url;
    }

    $url = preg_replace('|[^a-z0-9-~+_.?#=!&;,/:%@$\|*\'()\\x80-\\xff]|i', '', $url);

    $strip = array('%0d', '%0a', '%0D', '%0A');
    $url = (string) $url;

    $count = 1;
    while ($count) {
        $url = str_replace($strip, '', $url, $count);
    }

    $url = str_replace(';//', '://', $url);

    $url = htmlentities($url);

    $url = str_replace('&amp;', '&#038;', $url);
    $url = str_replace("'", '&#039;', $url);

    if ($url[0] !== '/') {
        // We're only interested in relative links from $_SERVER['PHP_SELF']
        return '';
    } else {
        return $url;
    }
}

function search($query, $mysqli){
    // Using prepared statements prevents SQL Injections

    /* USING FULL-TEXT SEARCH IN BOOLEAN MODE
    if ($stmt = $mysqli->prepare("SELECT id FROM posts WHERE MATCH (article_title) AGAINST ('" . $query . "' IN BOOLEAN MODE) OR MATCH (article_url) AGAINST ('" . $query . "' IN BOOLEAN MODE) OR MATCH (category) AGAINST ('" . $query . "' IN BOOLEAN MODE)")) {
    */

    //Split the search query up into individual words
    $terms = explode(' ', $query);
    $arr = array();
    foreach ($terms as $term) {
        $term = trim($term);
        if (!empty($term)) {
            $arr[] = "article_title LIKE '%" . $term . "%'";
            $arr[] = "category LIKE '%" . $term . "%'";
            $arr[] = "article_url LIKE '%" . $term . "%'";
            $arr[] = "article_author LIKE '%" . $term . "%'";
        }
    }

    if ($stmt = $mysqli->prepare("SELECT id from posts WHERE " . implode(" OR ", $arr))){
        $stmt->execute();    // Execute the prepared query.
        $stmt->store_result();

        // get the id from result.
        $stmt->bind_result($post_id);

        while ($stmt->fetch()){
            //add it to the array
            $result[] = $post_id;
        }

        $stmt->close();
        return $result;

    } else {
        echo "<h1>Error: database not connected</h1>";
        return 'error';
    }
}

function getCategories(){
    $categories = array("Cardiology & Vascular Medicine", "Endocrinology", "Gastroenterology", "Genetics & Genomics", "Haematology", "Infectious Diseases", "Neurology", "Obstetrics & Gynaecology", "Oncology", "Paediatrics", "Psychiatry", "Public Health", "Respiratory Medicine", "Urology", "Other");
    return $categories;
}

?>
