<?php

    //Implement a Content-Security Policy
    //echo "<meta http-equiv='Content-Security-Policy' content='script-src 'self' https://apis.google.com'>";


    echo '<h1><a href="index.php"><img src="Logo.jpg.png" style="width:200px;height:200px;"></a></h1>';
    echo "<br><br>";
    echo "In order to change password, we need to verify that it is you.";
    echo "<br><br>";
    echo "We will send a confirmation code to your email.";
    echo "<br><br>";
    echo "<form action='ForgotPasswordCheck.php' method='POST'>";
	echo "Enter your email: ";
	echo "<input name='txtEmail' type='text' />";
	echo "<br/><br>";
	echo "<input type='submit' value='Send Email'/>";
	echo "</form>";

?>