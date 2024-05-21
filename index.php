<?php
    session_start();
    //Implement a Content-Security Policy
    //echo "<meta http-equiv='Content-Security-Policy' content='script-src 'self' https://apis.google.com'>";
    $_SESSION['currMode'] = 'index';
    //Start new session if not started already
    if (isset($_SESSION['user']) && $_SESSION['admin'] == true) {
        
        //check for IP change and set new
        if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            if($_SESSION['IP'] !== $_SERVER['HTTP_X_FORWARDED_FOR'])
            {
                $_SESSION['IP'] = $_SERVER['HTTP_X_FORWARDED_FOR'];
                echo "<script>window.location.replace('SecurityQuestionPage.php');</script>";
            }
        }else{
        
            if($_SESSION['IP'] !== $_SERVER['REMOTE_ADDR']){
                $_SESSION['IP'] = $_SERVER['REMOTE_ADDR'];
                echo "<script>window.location.replace('SecurityQuestionPage.php');</script>";
            }
        }
        
        //Check if user was afk and update the time of last action
        if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 600)) {
            //log out from session
            setcookie(session_name(), '', 100);
            session_unset();
            session_destroy();
            $_SESSION['user'] = '';
            echo "<br><br>";
            echo "<script>window.location.replace('inactivityPage.php');</script>";
        }
        $_SESSION['LAST_ACTIVITY'] = time(); // update last activity time
        
        

        echo '<h1><a href="index.php"><img src="Logo.jpg.png" style="width:200px;height:200px;"></a></h1>';
        echo "<br><br>";
        echo '<a href="LogoutScript.php"><Button>Log out</Button></a>';
        echo '<a href="NewEvaluationPage.php"><Button>Request Evaluation</Button></a>';
        echo '<a href="ViewEvaluationsPage.php"><Button>My Listings</Button></a>';
        echo '<a href="AdminEvalPage.php"><Button>View All Listings</Button></a>';
        
    }elseif(isset($_SESSION['user']) && $_SESSION['admin'] == false){
        
        //check for IP change and set new
        if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            if($_SESSION['IP'] !== $_SERVER['HTTP_X_FORWARDED_FOR'])
            {
                $_SESSION['IP'] = $_SERVER['HTTP_X_FORWARDED_FOR'];
                echo "<script>window.location.replace('SecurityQuestionPage.php');</script>";
            }
        }else{
        
            if($_SESSION['IP'] !== $_SERVER['REMOTE_ADDR']){
                $_SESSION['IP'] = $_SERVER['REMOTE_ADDR'];
                echo "<script>window.location.replace('SecurityQuestionPage.php');</script>";
            }
        }
        
        //Check if user was afk and update the time of last action
        if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 600)) {
            //log out from session
            setcookie(session_name(), '', 100);
            session_unset();
            session_destroy();
            $_SESSION['user'] = '';
            echo "<br><br>";
            echo "<script>window.location.replace('inactivityPage.php');</script>";
        }
        $_SESSION['LAST_ACTIVITY'] = time(); // update last activity time
        

        echo '<h1><a href="index.php"><img src="Logo.jpg.png" style="width:200px;height:200px;"></a></h1>';
        echo "<br><br>";
        echo '<a href="LogoutScript.php"><Button>Log out</Button></a>';
        echo '<a href="NewEvaluationPage.php"><Button>Request Evaluation</Button></a>';
        echo '<a href="ViewEvaluationsPage.php"><Button>My Listings</Button></a>';
    }else{
        echo '<h1><a href="index.php"><img src="Logo.jpg.png" style="width:200px;height:200px;"></h1>';
        echo '<a href="LoginPage.php"><Button>Login</Button></a>';
        echo '<a href="RegisterPage.php"><Button>Register</Button></a>';
        echo '<a href="LoginPage.php"><Button>Request Evaluation</Button></a>';
        echo '<a href="LoginPage.php"><Button>My Listings</Button></a>';
    }



?>