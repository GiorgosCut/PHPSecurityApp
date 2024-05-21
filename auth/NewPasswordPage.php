<?php


//Implement a Content-Security Policy
//echo "<meta http-equiv='Content-Security-Policy' content='script-src 'self' https://apis.google.com'>";

echo '<h1><a href="index.php"><img src="Logo.jpg.png" style="width:200px;height:200px;"></a></h1>';
echo "<br><br>";
echo "<form action='NewPasswordCheck.php' method='POST'>";
echo "Enter your new password: ";
echo "<input name='txtPassword' type='text' />";
echo "<br/> Enter your new password again: ";
echo "<input name='txtPasswordAgain' type='password'/>";
echo "<br/>";
echo "<input type='submit' value='Confirm password'/>";
echo "</form>";

?>