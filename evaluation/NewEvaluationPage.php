<?php
    session_start();
    
    $_SESSION['currMode'] = 'NewEvalPage';
    
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


    echo '<h1><a href="index.php"><img src="Logo.jpg.png" style="width:200px;height:200px;"></a></h1>';
    echo "<br><br>";

    echo "<form action='NewEvaluationCheck.php' method='POST' enctype='multipart/form-data'>";
	echo "Please enter the description of your evaluation: ";
	echo "<br/><br>";
	echo "<textarea rows='5' cols='60' name='description' placeholder='Description'></textarea>";
	echo "<br/><br>";
	echo "Please upload an image for your evaluation: ";
	echo "<br/><br>";
	echo "<input name='image' id='image' type='file' accept='image/*'>";
	echo "<br/><br>";
	echo "<input type='submit' value='Submit Evaluation'/>";
	echo "</form>";

?>