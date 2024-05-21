<?php

    
    session_start();
    
    $_SESSION['currMode'] = 'ViewEvalPage';
    
    //check for IP change and set new
    if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
        if($_SESSION['IP'] !== $_SERVER['HTTP_X_FORWARDED_FOR'])
        {
            $_SESSION['IP'] === $_SERVER['HTTP_X_FORWARDED_FOR'];
            echo "<script>window.location.replace('SecurityQuestionPage.php');</script>";
        }
    }else{
        if($_SESSION['IP'] !== $_SERVER['REMOTE_ADDR']){
            $_SESSION['IP'] === $_SERVER['REMOTE_ADDR'];
            echo "<script>window.location.replace('SecurityQuestionPage.php');</script>";
        }
    }

    //Implement a Content-Security Policy
    //echo "<meta http-equiv='Content-Security-Policy' content='script-src 'self' https://apis.google.com'>";

    //Server and db connection
    $servername = "localhost";
    $rootUser = "user_root";
    $db = "db";
    $rootPassword = "pass";
    
    $cipher = "aes-128-cbc";
    $iv = "some_iv";
	$key = "some_key";
    
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
    
    //create connection
    $conn = new mysqli($servername, $rootUser, $rootPassword, $db);

    //check connection
    if ($conn -> connect_error){
        setcookie(session_name(), '', 100);
        session_unset();
        session_destroy();
        $_SESSION['user'] = '';
    	die ("Connection failed" . $conn -> connect_error);
    }
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    $descriptions = [];
    $images = [];
    
    //query
    $userQuery = "SELECT description, imageURL FROM evaluation WHERE ID = '" . $_SESSION['ID'] . "'";
    $userResult = $conn -> query($userQuery);
    
    if($userResult === false){
	    die("Query false");
	}
    
    echo '<h1><a href="index.php"><img src="Logo.jpg.png" style="width:200px;height:200px;"></a></h1>';
    echo "<br><br>";
    echo '<a href="LogoutScript.php"><Button>Log out</Button></a>';
    echo '<a href="NewEvaluationPage.php"><Button>Request Evaluation</Button></a>';
	$cipher = "aes-128-cbc";
    $iv = "some_iv";
	$key = "some_key";
    if ($userResult -> num_rows > 0){
        $iters = 0;
	    while ($userRow = $userResult -> fetch_assoc()){
	       $iters = $iters + 1;
	       echo "<br><br><br>";
	       echo "<b>Listing " . $iters . "</b>";
	       echo "<br><br>";
	       $imageData = base64_encode(file_get_contents($userRow['imageURL']));
           echo '<img src="data:image/png;base64,'.$imageData.'">';
           echo "<br><br>";
           $description = openssl_decrypt($userRow['description'], $cipher, $key, $options = 0, $iv);
           echo $description;
           echo "<br>";
           echo "=================================================================================================";
	    }
	    
    }else{
        echo "<br><br>";
        echo "<br><br>";
        echo "<b>You have not listed any evaluations yet.</b>";
    }
?>