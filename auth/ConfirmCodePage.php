<?php

    echo '<h1><a href="index.php"><img src="Logo.jpg.png" style="width:200px;height:200px;"></a></h1>';
    echo "<br><br>";
    echo "<form action='ConfirmCodeCheck.php' method='POST'>";
    echo "Enter the confirmation code: ";
    echo "<input name='ConfirmationCode' type='text' />";
    echo "<br/>";
    echo "<input type='submit' value='Confirm'/>";
    echo "</form>";

?> 