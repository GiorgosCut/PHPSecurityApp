<?php  


    //Implement a Content-Security Policy
    //echo "<meta http-equiv='Content-Security-Policy' content='script-src 'self' https://apis.google.com'>";

	echo '<h1><a href="index.php"><img src="Logo.jpg.png" style="width:200px;height:200px;"></a></h1>';
	echo "<br><br>";
	echo "<form action='RegisterCheck.php' method='POST'>";
	echo "<h1> Please register your details below: </h1>";
	echo "<pre>";
	echo "<br/>Enter in your Surname: ";
	echo "  	<input name='txtSurname' type='text' />";
	echo "<br/>Enter your Forename";
	echo "	        <input name='txtForename' type='text'/>";
	echo "<br/>Enter in your Username: ";
	echo "	<input name='txtUsername' type='text' />";
	echo "<br/>Enter in your Email address: ";
	echo "	<input name='txtEmail1' type='text' />";
	echo "<br/>Enter your email again: ";
	echo "	<input name='txtEmail2' type='text' />";
	echo "<br/>Enter your password: ";
	echo "	        <input name='txtPassword1' type='password' />";
	echo "<br/>Enter your password again: ";
	echo "	<input name='txtPassword2' type='password' />";
	echo "</pre>";
	echo "<br/>Select a security question<br/><br/>";
	echo "<select id='question' name='question'>";
	echo "<option value='1'> What is your father's surname? </option>";
	echo "<option value='2'> What is your first pet's name? </option>";
	echo "<option value='3'> What is the name of the town you were born in? </option>";
	echo "<option value='4'> What is your favorite color? </option>";
	echo "</select>";
	echo "<br/><br>Enter your answer: ";
	echo "	        <input name='answer' type='text' />";
	echo "<br><br><input type='submit' value='Register'>";
	echo "</form>";
?>