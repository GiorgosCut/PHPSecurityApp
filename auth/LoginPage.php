<?php

	
	//Implement a Content-Security Policy
    //echo "<meta http-equiv='Content-Security-Policy' content='script-src 'self' https://apis.google.com'>";

	
	echo '<h1><a href="index.php"><img src="Logo.jpg.png" style="width:200px;height:200px;"></a></h1>';
	echo "<br><br>";
	echo "<form action='preCaptchaLogin.php' method='POST'>";
	echo "Username: ";
	echo "<input name='txtUsername' type='text' />";
	echo "<br/> Password: ";
	echo "<input name='txtPassword' type='password'/>";
	echo "<br/>";
	echo "<input type='submit' value='Login'/>";
	echo "</form>";
	echo "<br/>Not registered? Click <a href='RegisterPage.php'>Here</a>";
	echo "<br/><br/>Forgot your password? Click <a href='ForgotPasswordPage.php'>Here</a>";
?> 