<!DOCTYPE html>
<html lang="en">

<head>
  <title>Where's My Prof?</title>
    <meta charset="UTF-8">
    <meta name="author" content="Noah Keck">
    <meta name="description" content="WheresMyProf uses crowdsourced information to provide both professors and students an easy way to share schedule information.">
    <meta name="revised" content="4/20/2020">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" sizes="180x180" href="assets/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/favicon-16x16.png">
    <link rel="manifest" href="site.webmanifest">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat">
    <link rel="stylesheet" href="assets/fontawesome-free-5.12.1/css/all.css">
    <link rel="stylesheet" href="mystyle.css">

</head>

<body>
  <div class="header">
    <h1>Enter your verification code below</h1>
  </div>

  <div style="padding-top: 30px; padding-bottom: 30px; width: 200px; margin: auto;">
    <form name="myForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
      <input name="code" type="text">
      <input type="submit">
    </form>
  </div>
  <div style="padding-top: 30px; padding-bottom: 30px;">
    <p id="verify"></p>
  </div>

  <?php
    // Import PHPMailer classes into the global namespace
    // These must be at the top of your script, not inside a function
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    require 'C:/Program Files/PHP/php-7.4.5/includes/PHPMailer/src/Exception.php';
    require 'C:/Program Files/PHP/php-7.4.5/includes/PHPMailer/src/PHPMailer.php';
    require 'C:/Program Files/PHP/php-7.4.5/includes/PHPMailer/src/SMTP.php';

    $code = "";

    //$secretcode = "123456";
    //$secretcode = strval(mt_rand(100000,999999));

    // First see if this page include POST requests. If yes, validate code. If not, send verification email.

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $code = test_input($_POST["code"]);
    }
    else{
      $secretcode = strval(mt_rand(100000,999999));
      $myfile = fopen("code.txt", "w");
      fwrite($myfile, $secretcode);
      fclose($myfile);

      // Instantiation and passing `true` enables exceptions
      $mail = new PHPMailer(true);

      try {
        //Server settings
        $mail->SMTPOptions = array(                                 // Required options to allow insecure SSL
          'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
          )
        );
        //Enable SMTP debugging
        // SMTP::DEBUG_OFF = off (for production use)
        // SMTP::DEBUG_CLIENT = client messages
        // SMTP::DEBUG_SERVER = client and server messages
        $mail->SMTPDebug = SMTP::DEBUG_OFF;
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                       // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = 'joesmhoe135@gmail.com';                // SMTP username
        $mail->Password   = '********';                        // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

        //Recipients
        $mail->setFrom('joesmhoe135@gmail.com', 'WheresMyProf');
        $mail->addAddress('noahkeck@mindspring.com', 'Noah Keck');  // Name is optional

        // Content
        $mail->isHTML(true);                                        // Set email format to HTML
        $mail->Subject = 'WheresMyProf Account Verification';
        $mail->Body    = "<html><h1>WheresMyProf Verification Code</h1><p>Enter the verification code below to activate your account.</p><p>{$secretcode}</p></html>";
        $mail->AltBody = "Enter code: {$secretcode}";

        $mail->send();                                            // Uncomment for production
        echo "Verification email has been sent.";
      } catch (Exception $e) {
        echo "Verification email could not be sent. Mailer Error: {$mail->ErrorInfo}";
      }
    }

    // Form Validation function
    function test_input($data) {
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
    }

    $myfile = fopen("code.txt", "r");
    $secretcode = fread($myfile,filesize("code.txt"));
    fclose($myfile);

    if ($code == $secretcode){
      echo "Validated!";
    }
    else if ($code != ""){
      echo "Code is invalid.";
    }

  ?>

  <!-- Footer -->
  <footer style="padding-top: 100px">
    <div>
      <p id="copyright"></p>
      <p><a href="tos.html">Terms of Service</a> - <a href="privacy.html">Privacy Policy</a></p>
    </div>
  </footer>

  <script>

    // Writes the copyright statement with the current year. Website was first deployed in March 2020
    document.getElementById("copyright").innerHTML = "Copyright " + new Date().getFullYear() + " - All Rights Reserved";

  </script>

</body>

</html>
