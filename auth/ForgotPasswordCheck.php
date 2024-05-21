<?php
    
    session_start();

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\SMTP;
    
    require $_SERVER['DOCUMENT_ROOT'] . '/PHPMailer/mail/Exception.php';
    require $_SERVER['DOCUMENT_ROOT'] . '/PHPMailer/mail/PHPMailer.php';
    require $_SERVER['DOCUMENT_ROOT'] . '/PHPMailer/mail/SMTP.php';


    //Server and db connection
    $servername = "localhost";
    $rootUser = "user_root";
    $db = "db";
    $rootPassword = "root_pass";
    

    //create connection
    $conn = new mysqli($servername, $rootUser, $rootPassword, $db);

    //values come from user, through webform
    $email = mysqli_real_escape_string($conn, $_POST["txtEmail"]);
    $email = htmlspecialchars($email);

    //check connection
    if ($conn -> connect_error){
        die ("Connection failed" . $conn -> connect_error);
    }

    $errorOccured = False;

    if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
		echo("$email is not a valid email address");
		$errorOccured = True;
	}

    //query
    $userQuery = "SELECT * FROM user";
    $userResult = $conn -> query($userQuery);

    //if it was a valid email
    if($errorOccured === False){
        //flag
        $userFound = False;
        $cipher = "aes-128-cbc";
    	$key = "compSecurity";
    	$iv = "some_iv";
        echo "<table border = '1'";
        if($userResult -> num_rows > 0){
            while($userRow = $userResult -> fetch_assoc()){
                if(openssl_decrypt($userRow['Email'], $cipher, $key, $options = 0, $iv) === $email){
                    //user Found
                    echo "User was found";
                    $userFound = True;
                    $randomCode = random_int(100000, 999999);
                    //save code to session
                    $_SESSION['code'] = $randomCode;
                    $_SESSION['email'] = $email;
                    $mailText = "Your confirmation code is: " . $randomCode;
                    $mail = new PHPMailer(true);
                    // create a new object
                    try {
                        //Server settings
                        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
                        $mail->isSMTP();                                            //Send using SMTP
                        $mail->Host       = 'smtp.sendgrid.net';                     //Set the SMTP server to send through
                        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                        $mail->Username   = 'apikey';                               //SMTP username
                        $mail->Password   = 'password';            					//SMTP password
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
                    
                        //Recipients
                        $mail->setFrom('email.com', 'Michael');
                        $mail->addAddress($email);     //Add a recipient
                    
                        //Content
                        $mail->isHTML(true);                                  //Set email format to HTML
                        $mail->Subject = 'Confirmation code';
                        $mail->Body    = $mailText;
                        $mail->AltBody = $mailText;
                    
                        $mail->send();
                        echo 'Message has been sent';
                        $_SESSION['mode'] = "PasswordChange";
                        echo '<a href="ConfirmCodePage.php"><Button>Enter Confirmation code</Button></a>';

                    } catch (Exception $e) {
                        echo "email error";
                        echo "confirmation code could not be sent";
                    }
                }
            }
        }
        if($userFound === False){
            echo "<br>";
            echo "User email was not found.<br>";
            echo '<a href="ForgotPasswordPage.php"><Button>Try Again</Button></a>';
        }
    }
?>