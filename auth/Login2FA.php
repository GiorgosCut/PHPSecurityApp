<?php

    session_start();

    
    //Implement a Content-Security Policy
    //echo "<meta http-equiv='Content-Security-Policy' content='script-src 'self' https://apis.google.com'>";


    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\SMTP;
    
    require $_SERVER['DOCUMENT_ROOT'] . '/PHPMailer/mail/Exception.php';
    require $_SERVER['DOCUMENT_ROOT'] . '/PHPMailer/mail/PHPMailer.php';
    require $_SERVER['DOCUMENT_ROOT'] . '/PHPMailer/mail/SMTP.php';


    $_SESSION['mode'] = "2FA";
    $randomCode = random_int(100000, 999999);
    //save code to session
    //save code to session
    $_SESSION['code'] = $randomCode;

    $mailText = "Your confirmation code is: " . $randomCode;
    $mail = new PHPMailer(true);
    // create a new object
    try {
        //Server settings
        //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.sendgrid.net';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'apikey';                               //SMTP username
        $mail->Password   = 'some_password';            //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
                    
        //Recipients
        $cipher = "aes-128-cbc";
        $iv = "some_iv";
	    $key = "compSecurity";
        $mail->setFrom('email', 'John');
        
        $mail->addAddress(openssl_decrypt($_SESSION['email2FA'], $cipher, $key, $options = 0, $iv));     //Add a recipient
                    
        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Confirmation code';
        $mail->Body    = $mailText;
        $mail->AltBody = $mailText;
                    
        $mail->send();

    } catch (Exception $e) {
        echo "confirmation code could not be sent";
        echo '<h1><a href="index.php"><img src="Logo.jpg.png" style="width:200px;height:200px;"></a></h1>';
    }
    
    echo '<h1><a href="index.php"><img src="Logo.jpg.png" style="width:200px;height:200px;"></a></h1>';
    
    echo "<br><br>";
    echo "A verification code has been sent to your Email";
    echo "<br><br>Please enter your verification code below.";
    echo "<form action='ConfirmCodeCheck.php' method='POST'>";
    echo "<br>";
    echo "<input name='ConfirmationCode' type='text' />";
    echo "<br>";
    echo "<input type='submit' value='Confirm'/>";
    echo "</form>";
?>