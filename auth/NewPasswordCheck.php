<?php

    session_start();
    
    //Implement a Content-Security Policy
    //echo "<meta http-equiv='Content-Security-Policy' content='script-src 'self' https://apis.google.com'>";


    $servername = "localhost";
    $rootUser = "user_root";
    $db = "db";
    $rootPassword = "pass";
    
    //connect to the server
	$connection = mysqli_connect($servername, $rootUser, $rootPassword, $db) or die ("could not connect to server.");

    $preSQL = $connection->prepare("UPDATE user SET Password = ? WHERE Email = ?");
    $preSQL->bind_param("ss", $hashedPassword, $userEmail);

    $pass1 = htmlspecialchars(mysqli_real_escape_string($connection, $_POST["txtPassword"]));
    $pass2 = htmlspecialchars(mysqli_real_escape_string($connection, $_POST["txtPasswordAgain"]));
    $option = [
        'cost' => 12,
    ];
    $userEmail = $_SESSION['email'];

    $errorOccured = 0;

    if($pass1 !== $pass2){
        echo "Passwords don't match";
        $errorOccured = 1;
    }

    //query
    $userQuery = "SELECT * FROM user";
    $userResult = $connection -> query($userQuery);

    //find username of user
    //flag variable
    $userFound = 0;
    $cipher = "aes-128-cbc";
    $iv = "some_iv";
	$key = "compSecurity";
    echo "<table border = '1'>";
    if ($userResult -> num_rows > 0){
        while ($userRow = $userResult -> fetch_assoc()){
            if (openssl_decrypt($userRow['Email'], $cipher, $key, $options = 0, $iv) === $_SESSION['email']){
                $userFound = 1;
                $username = openssl_decrypt($userRow['Username'], $cipher, $key, $options = 0, $iv);
                $userEmail = openssl_encrypt($userEmail, $cipher, $key, $options = 0, $iv);
                echo $userEmail;
                //Check if password contains parts of the username
                //First split the username into an array
                $usernameParts = preg_split('/[!£$%^&*\/_+#=;:@~?<>.,0-9]/', $username);
                $flag = True;
                $i = 0;
                $partslen = count($usernameParts);
                while($i < $partslen && $flag === True){
                    $curr = $usernameParts[$i];
                    if($curr !== ''){
                        if(strpos(strtolower($curr), strtolower($pass1))){
                        $flag = False;
                        }
                    }
                    
                    $i = $i + 1;
                }

                if($flag === False){
                    $errorOccured = 2;
                }

                //Check if password 1. contains a number 2. contains uppercase 3. contains lowercase 4. contains special char 5. is of length 12 or more
                $passErrors = [];
                if(!preg_match('@[0-9]@', $pass1)){
                    array_push($passErrors, "Password must contain a number!");
                    $errorOccured = 3;
                }
                if(!preg_match('@[A-Z]@', $pass1)){
                    array_push($passErrors, "Password must contain an uppercase character!");
                    $errorOccured = 3;
                }
                if(!preg_match('@[a-z]@', $pass1)){
                    array_push($passErrors, "Password must contain a lowercase character!");
                    $errorOccured = 3;
                }
                if(!preg_match('@[^\w]@', $pass1)){
                    array_push($passErrors, "Password must contain a special character!");
                    $errorOccured = 3;
                }
                if(strlen($pass1) < 10){
                    array_push($passErrors, "Password needs to be atleast 10 characters long!");
                    $errorOccured = 3;
                }

                	//Check to see if an error has occurred. Then add the details to the database
                if($errorOccured === 0){
                    //create hash for password
                    $hashedPassword = password_hash($pass1, PASSWORD_BCRYPT, $option);
                    if($preSQL->execute() === TRUE){
                        echo "Your password has been changed.";
                        echo '<a href="index.php"><Button>Home Page</Button></a>';
                    }
                }else if($errorOccured == 1){
                    echo '<a href="NewPasswordPage.php"><Button>Go Back</Button></a>';
                }else if($errorOccured == 2){
                    echo "Your password must not contain parts of your username!";
                    echo '<a href="NewPasswordPage.php"><Button>Go Back</Button></a>';
                }else if($errorOccured === 3){
                    $errorInd = 0;
                    while($errorInd < count($passErrors)){
                        echo "<br>";
                        echo $passErrors[$errorInd];
                        $errorInd = $errorInd + 1;	
                    }
                    echo "<br>";echo "<br>";echo "<br>";
                    echo '<a href="NewPasswordPage.php"><Button>Go Back</Button></a>';
                }
        
            }
        }
    }



?>