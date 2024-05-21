<?php

    session_start();
    
    //check for IP change and set new
    if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
        if($_SESSION['IP'] !== $_SERVER['HTTP_X_FORWARDED_FOR'])
        {
            $_SESSION['IP'] = $_SERVER['HTTP_X_FORWARDED_FOR'];
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

    $servername = "localhost";
    $rootUser = "user_root";
    $db = "db";
    $rootPassword = "pass";
    
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
    
        //connect to the server
	$connection = mysqli_connect($servername, $rootUser, $rootPassword, $db) or die ("could not connect to server.");
    
    // Check connection
	if ($connection->connect_error) {
	    die("Connection failed: " . $connection->connect_error);
	}

    $error = 0;
    $errormsg = '';

    
    $description = htmlspecialchars($_POST['description']);
    $description = filter_var($description, FILTER_SANITIZE_STRING);
    
    //check if no description was entered
    if(strlen($description) === 0 || strlen($description) > 2000){
        $error = 1;
        $errormsg = "Description must be between 1 - 2000 words.";
    }
    
    $cipher = "aes-128-cbc";
    $iv = "some_iv";
	$key = "some_key";
    $description = openssl_encrypt($description, $cipher, $key, $options = 0, $iv);
    
    
    //get the last evaluation number and add 1
    
    $userQuery = "SELECT * FROM evaluation";
    $userResult = $connection -> query($userQuery);
                
    while ($userRow = $userResult -> fetch_assoc()){
        $evalNum = $userRow['evaluation_number'];
    }
    
    
    //check for error in image upload
    $targetDir = "img_dir";
    if($_FILES['image']['type'] == "image/jpeg"){
        $fileName = $targetDir . "/" . $evalNum + 1 . ".jpeg";
    }else if($_FILES['image']['type'] == "image/png"){
        $fileName = $targetDir . "/" . $evalNum + 1 . ".png";
    }
    
    mysqli_store_result($connection);

    if (is_array($_FILES)) {
        if (is_uploaded_file($_FILES['image']['tmp_name'])) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $fileName)) {
                echo "File uploaded successfully";
            }else{
                die("file not moved to dir");
            }
        }
    }
    


    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    if($error == 0){
        $preSQL = $connection -> prepare("INSERT INTO evaluation (ID, description, imageURL) VALUES (?,?,?)");
	    if($preSQL === false){
	        die("Error");
	    }
	    $preSQL -> bind_param("iss", $_SESSION['ID'], $description, $fileName);
		//add account to database
    		if($preSQL->execute() === TRUE){
    		    $preSQL -> store_result();
    			$preSQL->close();
    			//clear out the output buffer
    			while (ob_get_status()) 
    			{
    				ob_end_clean();
    			}
    		    echo "<br><br>";
    			echo "Your evaluation has been uploaded.";
                echo '<a href="index.php"><Button>Home Page</Button></a>';
    		}else{
    		    echo "Couldnt upload";
    		}
    }

?>