<?php

session_start();

//Implement a Content-Security Policy
//echo "<meta http-equiv='Content-Security-Policy' content='script-src 'self' https://apis.google.com'>";


//Server and db connection
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
    
    $_SESSION['userIP'] = $_SERVER['REMOTE_ADDR'];

//create connection
$conn = new mysqli($servername, $rootUser, $rootPassword, $db);

if($_SESSION['preLoginUsername'] === '' or $_SESSION['preLoginPassword'] === '' ){
    echo 'Please insert your credentials';
    echo "<br><br>";
	echo '<a href="LoginPage.php"><Button>Go Back</Button></a>';
	exit();
}

//values come from user, through webform
$username = htmlspecialchars($_SESSION['preLoginUsername']);
$password = htmlspecialchars($_SESSION['preLoginPassword']);

//check connection
if ($conn -> connect_error)
{
    setcookie(session_name(), '', 100);
    session_unset();
    session_destroy();
    $_SESSION['user'] = '';
    
    
	die ("Connection failed" . $conn -> connect_error);
	
}

//query
$userQuery = "SELECT * FROM user";
$userResult = $conn -> query($userQuery);

//flag variable
$userFound = 0;

echo "<table border = '1'>";
if ($userResult -> num_rows > 0){
    $cipher = "aes-128-cbc";
	$key = "compSecurity";
	$iv = "some_iv";
	while ($userRow = $userResult -> fetch_assoc()){
	    if(openssl_decrypt($userRow['Username'], $cipher, $key, $options = 0, $iv) === false){
	        echo "false";
	    }
		if (openssl_decrypt($userRow['Username'], $cipher, $key, $options = 0, $iv) === $username){
			$userFound = 1;
			if (password_verify($password ,$userRow['Password'])){
				echo "Hi " .openssl_decrypt($username, $cipher, $key, $options = 0, $iv) . "!";
				echo "<br/>Welcome to our website";
				//Start new session with logged in user's username
				session_start();
				$_SESSION['user2FA'] = openssl_decrypt($username, $cipher, $key, $options = 0, $iv);
				$_SESSION['email2FA'] = $userRow['Email'];
				$_SESSION['pre2FAID'] = $userRow['ID'];
				if($userRow['admin'] == 0){
				    $_SESSION['admin'] = false;
				}elseif($userRow['admin'] == 1){
				    $_SESSION['admin'] = true;
				}else{
				    die("admin error");
				}
				// clear out the output buffer
                while (ob_get_status()) 
				{
					ob_end_clean();
				}
			    echo "done";echo "done";echo "done";
				$homeUrl = "Login2FA.php";
    			echo "<script>window.location.href = 'Login2FA.php';</script>";
			}else{
			    echo "The password your have entered is incorrect.";
			    echo '<a href="index.php"><Button>Home Page</Button></a>';
			}
		}
	}
}
echo "</tables>";

if ($userFound === 0){
    //setcookie(session_name(), '', 100);
    session_unset();
    session_destroy();
    $_SESSION['user'] = '';
	echo "This user was not found in our database";
	echo "<br><br>";
	echo '<a href="index.php"><Button>Home Page</Button></a>';
}

?>