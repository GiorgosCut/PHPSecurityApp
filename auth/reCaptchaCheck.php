<?php


    session_start();
    
    //Implement a Content-Security Policy
    //echo "<meta http-equiv='Content-Security-Policy' content='script-src 'self' https://apis.google.com'>";

    
    $secretKey = "some_secret_key";
   // Verify the reCAPTCHA API response 
    $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secretKey.'&response='.$_POST['g-recaptcha-response']); 
             
    // Decode JSON data of API response 
    $responseData = json_decode($verifyResponse); 

    if(!$responseData ->success){
        while (ob_get_status()) 
            {
                ob_end_clean();
            }
        die("Captcha Failed");
    }else{
        while (ob_get_status()) 
            {
                ob_end_clean();
            }
        echo "<script>window.location.href = 'LoginCheck.php'; </script>";
        echo "TRUE";
    }

?>