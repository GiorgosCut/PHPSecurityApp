<?php

    
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

	//Copy all of the data from the dorm into variables
	$forename =$_POST['txtForename'];
	$surname = $_POST['txtSurname'];
	$username = $_POST['txtUsername'];
	$email1 = $_POST['txtEmail1'];
	$email2 = $_POST['txtEmail2'];
	$password1 = $_POST['txtPassword1'];
	$password2 = $_POST['txtPassword2'];
	$qNum = $_POST['question'];
	$ans = $_POST['answer'];
    $options = [
        'cost' => 12,
    ];
   
   // flag to check values 
    $errorOccured = 0;
    
    //Apply validation and sanitization to the inputs
	//Applying "mysqli_real_escape_string function to the input
	$forename = mysqli_real_escape_string($connection, $_POST['txtForename']);
	$surname = mysqli_real_escape_string($connection, $_POST['txtSurname']);
	$username = mysqli_real_escape_string($connection, $_POST['txtUsername']);
	$email1 = mysqli_real_escape_string($connection, $_POST['txtEmail1']);
	$email2 = mysqli_real_escape_string($connection, $_POST['txtEmail2']);
	$password1 = mysqli_real_escape_string($connection, $_POST['txtPassword1']);
	$password2 = mysqli_real_escape_string($connection, $_POST['txtPassword2']);
	$ans = mysqli_real_escape_string($connection, $_POST['answer']);
    $option = [
        'cost' => 12,
    ];


	//applying htmlspecialchars function to the input
	$forename = htmlspecialchars($forename);
	$surname = htmlspecialchars($surname);
	$username = htmlspecialchars($username);
	$email1 = htmlspecialchars($email1);
	$email2 = htmlspecialchars($email2);
	$password1 = htmlspecialchars($password1);
	$password2 = htmlspecialchars($password2);
	$ans = htmlspecialchars($ans);

	//applying filters to sanitize input 
	$forename = filter_var($forename, FILTER_SANITIZE_STRING);
	$surname = filter_var($surname, FILTER_SANITIZE_STRING);
	$username = filter_var($username, FILTER_SANITIZE_STRING);
	$email1 = filter_var($email1, FILTER_SANITIZE_EMAIL);
	$email2 = filter_var($email2, FILTER_SANITIZE_EMAIL);
	$password1 = filter_var($password1, FILTER_SANITIZE_STRING);
	$password2 = filter_var($password2, FILTER_SANITIZE_STRING);
    $ans = filter_var($ans, FILTER_SANITIZE_STRING);
    
    $userQ = "SELECT * FROM user";
	$userResult = mysqli_query($connection, $userQ);
	if($userResult === false){
	    die("First query false");
	}

  
    
	//Check if username already exists in the database
	//Loop through all records
	while ($userRow = mysqli_fetch_array($userResult))
	{
		//Check to see if the current user's username matches the one from the database
		if($userRow['Username'] === $username)
		{
			echo "The username has already been used. <br/>";
			$errorOccured = 1;
			
		}
	}
    mysqli_data_seek($userResult, 0);
	//Check to see if the email address is registered
	while ($userRow = mysqli_fetch_array($userResult)){
		if($userRow['Email'] === $email1){
			echo "The email has already been used. <br/>";
			$errorOccured = 1;
			
		}
	}



	//Make sure that all text boxes were not blank
	if($forename === ""){
		echo "Please provide a forename.<br/>";
		$errorOccured = 1;
		
	}
	if($surname === ""){
		echo "Please provide a surname.<br/>";
		$errorOccured = 1;
		
	}
	if($username === ""){
		echo "Please provide a username.<br/>";
		$errorOccured = 1;
		
	}
	if($email1 === "" or $email2 === ""){
		echo "Please provide an email.<br/>";
		$errorOccured = 1;
		
	}
	if($password1 === "" or $password2 === ""){
		echo "Please provide a password.<br/>";
		$errorOccured = 1;
		
	}

	if (filter_var($email1, FILTER_VALIDATE_EMAIL) === false) {
		echo("$email1 is not a valid email address");
		$errorOccured = 1;
	}



	//Check to see if emails contain '@'
	if(strpos($email1, "@") === false or strpos($email2, "@") === false){
		echo "Please enter a valid Email address. <br/>";
		$errorOccured = 1;
		
	}

	//Check to make sure the emails match
	if($email1 !== $email2){
		echo "Emails do not match. <br/>";
		$errorOccured = 1;
		
	}

	//Check to see if passwords match
	if($password1 !== $password2){
		echo "Passwords do not match. <br/>";
		$errorOccured = 1;
		
	}

	//Check if password contains parts of the username
	//First split the username into an array
	$usernameParts = preg_split('/[!£$%^&*\/_+#=;:@~?<>.,0-9]/', $username);
	$flag = true;
	$i = 0;
	$partslen = count($usernameParts);
	while($i < $partslen && $flag === true){
		$curr = $usernameParts[$i];
		echo "<br>";
		if($curr != ''){
            if(strpos(strtolower($password1), strtolower($curr)) !== false or strpos(strtolower($curr), strtolower($password1))!== false){
                $flag = false;
                }
            }
		$i = $i + 1;
	}

	if($flag === false){
		$errorOccured = 2;
	}

	//Check if password 1. contains a number 2. contains uppercase 3. contains lowercase 4. contains special char 5. is of length 12 or more
	$passErrors = [];
	if(!preg_match('@[0-9]@', $password1)){
		array_push($passErrors, "Password must contain a number!");
		$errorOccured = 3;
	}
	if(!preg_match('@[A-Z]@', $password1)){
		array_push($passErrors, "Password must contain an uppercase character!");
		$errorOccured = 3;
	}
	if(!preg_match('@[a-z]@', $password1)){
		array_push($passErrors, "Password must contain a lowercase character!");
		$errorOccured = 3;
	}
	if(!preg_match('@[^\w]@', $password1)){
		array_push($passErrors, "Password must contain a special character!");
		$errorOccured = 3;
	}
	if(strlen($password1) < 10){
		array_push($passErrors, "Password needs to be atleast 10 characters long!");
		$errorOccured = 3;
	}
	
	if(strlen($ans) === 0 or strlen($ans) > 100){
        $errorOccured = 4;
	}
	
	//encrypting all data
	$cipher = "aes-128-cbc";
	if(in_array($cipher, openssl_get_cipher_methods())){
	   // $iv_size = openssl_cipher_iv_length($cipher); 
    //     $iv = random_bytes($iv_size); 
        $iv = "some_iv";
	    $key = "compSecurity";
	    $encForename = openssl_encrypt($forename, $cipher, $key, $options = 0, $iv);
	    $encSurname = openssl_encrypt($surname, $cipher, $key,$options =  0,$iv);
	    $encUsername = openssl_encrypt($username, $cipher, $key, $options = 0, $iv);
	    $encEmail = openssl_encrypt($email1, $cipher, $key, $options = 0, $iv);
	    $encAns = openssl_encrypt($ans, $cipher, $key, $options = 0, $iv);
	}else{
	    die("Error encrypting data");
	}

	//Check to see if an error has occurred. Then add the details to the database
	if($errorOccured === 0){
		//create hash for password
		$hashedPassword = password_hash($password1, PASSWORD_BCRYPT, $option);
		//Preparing SQL statement
	    $preSQL = $connection -> prepare("INSERT INTO user (Username, Password, Forename, Surname, Email, q_num, answer, admin) VALUES (?,?,?,?,?,?,?,?)");
	    if($preSQL === false){
	        die("Error");
	    }
	    $admin = 0;
	    $preSQL -> bind_param("sssssisi", $encUsername, $hashedPassword, $encForename, $encSurname, $encEmail, $qNum, $encAns, $admin);
	    if($preSQL === false){
	        die("param bind error");
	    }
		//add account to database
		if($preSQL->execute() === TRUE){
		    $preSQL -> store_result();
			$preSQL->close();
			echo "Hello " . $forename . " \t" .$surname . "<br/>";
			echo "Thanks for registering";
			echo "<br>";echo "<br>";echo "<br>";
	    	echo '<a href="index.php"><Button>HomePage</Button></a>';
		}else{
		    echo $preSQL->error;
		}
	}else if($errorOccured === 1){
		echo '<a href="RegisterPage.php"><Button>Go Back</Button></a>';
	}else if($errorOccured === 2){
		echo "Your password must not contain parts of your username!";
		echo '<a href="RegisterPage.php"><Button>Go Back</Button></a>';
	}else if($errorOccured === 3){
		$errorInd = 0;
		while($errorInd < count($passErrors)){
			echo "<br>";
			echo $passErrors[$errorInd];
			$errorInd = $errorInd + 1;	
		}
		echo "<br>";echo "<br>";echo "<br>";
		echo '<a href="RegisterPage.php"><Button>Go Back</Button></a>';
	}else if($errorOccured === 4){
	    echo "Please give a valid and short answer to the question!";
		echo '<a href="RegisterPage.php"><Button>Go Back</Button></a>';
	}
	
?>