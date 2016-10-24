<?php
require 'PHPMailerAutoload.php';

$mail = new PHPMailer;

//$mail->SMTPDebug = 3;                               // Enable verbose debug output

$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'SSL0.OVH.NET';  // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'contact@nutricloud.eu';                 // SMTP username
$mail->Password = 'NutriCloud';                           // SMTP password
$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
$mail->Port = 587;                                    // TCP port to connect to

$name = $_POST['name'];
$email_address = $_POST['email'];
$message = $_POST['message'];

$mail->From = 'contact@nutricloud.eu';
$mail->FromName = 'contact@nutricloud.eu';    // Add a recipient
$mail->addAddress('contact@nutricloud.eu');               // Name is optional
    // Optional name
$mail->isHTML(true);                                  // Set email format to HTML

$email_address = $_POST['email'];
$message = $_POST['message'];

$subject = 'name:' . $name . ' email:' .$email_address;

$mail->Subject = $subject;
$mail->Body    = $message;
$mail->AltBody = $message;

if(!$mail->send()) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
} else {
    echo 'Message has been sent';
}
