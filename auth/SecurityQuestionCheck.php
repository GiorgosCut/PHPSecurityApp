<?php

    session_start();

    $servername = "localhost";
    $rootUser = "user_root";
    $db = "db";
    $rootPassword = "pass";
	
	//connect to the server
	$connection = mysqli_connect($servername, $rootUser, $rootPassword, $db);
    
    // Check connection
    if ($connection->connect_error) 
    {
      die("Connection failed: " . $connection->connect_error);
    }
    
    $ans = $_POST['answer'];
	$ans = mysqli_real_escape_string($connection, $_POST['answer']);
	$ans = htmlspecialchars($ans);
    $ans = filter_var($ans, FILTER_SANITIZE_STRING);
    
    //query
    $userQuery = "SELECT * FROM user";
    $userResult = $connection -> query($userQuery);
    
    echo "<table border = '1'>";
    if ($userResult -> num_rows > 0){
        $cipher = "aes-128-cbc";
    	$key = "compSecurity";
    	$iv = "some_iv";
	    while ($userRow = $userResult -> fetch_assoc()){
	        if($userRow['ID'] === $_SESSION['ID']){
	            if($ans === openssl_decrypt($userRow['answer'], $cipher, $key, $options = 0, $iv)){
	                if($_SESSION['currMode'] === 'ViewEvalPage'){
	                    echo "<script>window.location.replace('ViewEvaluationPage.php');</script>";
	                }else if($_SESSION['currMode'] === 'NewEvalPage'){
	                    echo "<script>window.location.replace('NewEvaluationPage.php');</script>";
	                }else if($_SESSION['currMode'] === 'index'){
	                    echo "<script>window.location.replace('index.php');</script>";
	                }
	            }
	        }
	    }
    }


?>