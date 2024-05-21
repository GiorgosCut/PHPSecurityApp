<?php
    session_start();
    
    //Implement a Content-Security Policy
    //echo "<meta http-equiv='Content-Security-Policy' content='script-src 'self' https://apis.google.com'>";
    
    //Check if user was afk and update the time of last action
    // if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 600)) {
    //     //log out from session
    //     setcookie(session_name(), '', 100);
    //     session_unset();
    //     session_destroy();
    //     $_SESSION['user'] = '';
    //     echo "<br><br>";
    //     echo "YES";
    //     echo "<script>window.location.replace('inactivityPage.php');</script>";
    // }
    $_SESSION['LAST_ACTIVITY'] = time(); // update last activity time
    
    $_SESSION['preLoginUsername'] = htmlspecialchars($_POST["txtUsername"]);
    $_SESSION['preLoginPassword'] = htmlspecialchars($_POST["txtPassword"]);

    
    echo "<script>window.location.replace('reCaptcha.html');</script>";


?>