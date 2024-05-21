<?php

    session_start();
    
    //Implement a Content-Security Policy
    //echo "<meta http-equiv='Content-Security-Policy' content='script-src 'self' https://apis.google.com'>";


    $sentCode = $_SESSION['code'];
    $userCode = $_POST["ConfirmationCode"];
    
    //     //Check if user was afk and update the time of last action
    // if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 600)) {
    //     //log out from session
    //     setcookie(session_name(), '', 100);
    //     session_unset();
    //     session_destroy();
    //     $_SESSION['user'] = '';
    //     echo "<br><br>";
    //     echo "<script>window.location.replace('inactivityPage.php');</script>";
    // }
    // $_SESSION['LAST_ACTIVITY'] = time(); // update last activity time

    if($sentCode == $userCode){
        //clear buffer
        while (ob_get_status()) 
            {
                ob_end_clean();
            }
        if($_SESSION['mode'] === '2FA'){
            $_SESSION['user'] = $_SESSION['user2FA'];
            $_SESSION['ID'] = $_SESSION['pre2FAID'];

            $Url = 'index.php';
            header( "Location: $Url" );
        }else{
            $Url = "NewPasswordPage.php";     
            header( "Location: $Url" );
        }         
        
    }else{
        echo "The confirmation code you enterned is wrong!";
        echo "<br>";
		echo '<a href="ConfirmCodePage.php"><Button>Try Again</Button></a>';
    }

?>