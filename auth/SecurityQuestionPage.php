<?php

    session_start();
    
    $servername = "localhost";
    $rootUser = "id19825700_assigndb";
    $db = "id19825700_assignmentdb";
    $rootPassword = "dYV(6!KC{l4^$@R-";
	//connect to the server
	$connection = mysqli_connect($servername, $rootUser, $rootPassword, $db);
    
    // Check connection
    if ($connection->connect_error) 
    {
      die("Connection failed: " . $connection->connect_error);
    }
    echo "Due to security reasons you need to prove your identity.";
    echo "<br>Please verify that it is you by answering the security question";
    //query
    $userQuery = "SELECT * FROM user";
    $userResult = $connection -> query($userQuery);

    //flag variable
    $userFound = 0;
    $q_num = "";
    
    echo "<table border = '1'>";
    if ($userResult -> num_rows > 0){
        $cipher = "aes-128-cbc";
    	$key = "compSecurity";
    	$iv = "1110000010110101";
	    while ($userRow = $userResult -> fetch_assoc()){
	        if($userRow['ID'] === $_SESSION['ID']){
	            $q_num = $userRow['q_num'];
	        }
	    }
    }
    
    echo "<br>";
    if($q_num == 1){
        echo "<form action='SecurityQuestionCheck.php' method='POST'>";
        echo "<br/><br>What is your father's surname? ";
    	echo "	        <input name='answer' type='text' />";
    	echo "<br><br><input type='submit' value='Submit'>";
    	echo "</form>";
    	echo "<br><br><br>";
        echo '<a href="LogoutScript.php"><Button>Log out</Button></a>';
    }else if($q_num == 2){
        echo "<form action='SecurityQuestionCheck.php' method='POST'>";
        echo "<br/><br>What is your first pet's name? ";
    	echo "	        <input name='answer' type='text' />";
    	echo "<br><br><input type='submit' value='Submit'>";
    	echo "</form>";
    	echo "<br><br><br>";
        echo '<a href="LogoutScript.php"><Button>Log out</Button></a>';
    }else if($q_num == 3){
        echo "<form action='SecurityQuestionCheck.php' method='POST'>";
        echo "<br/><br>What is the name of the town you were born in? ";
    	echo "	        <input name='answer' type='text' />";
    	echo "<br><br><input type='submit' value='Submit'>";
    	echo "</form>";
    	echo "<br><br><br>";
        echo '<a href="LogoutScript.php"><Button>Log out</Button></a>';    	
    }else{
        echo "<form action='SecurityQuestionCheck.php' method='POST'>";
        echo "<br/><br>What is your favorite color? ";
    	echo "	        <input name='answer' type='text' />";
    	echo "<br><br><input type='submit' value='Submit'>";
    	echo "</form>";
    	echo "<br><br><br>";
        echo '<a href="LogoutScript.php"><Button>Log out</Button></a>';
    }
?>