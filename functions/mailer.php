<?php
require '../phpmailer/vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function sendEmail($recipients, $subject, $htmlContent, $textContent, $attachments = []) {
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'arindam.co.in';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'email@arindam.co.in';                     //SMTP username
        $mail->Password   = 'F1@@GsH7FEMpB-Pb';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;          //Enable implicit TLS encryption
        $mail->Port       = 587;                                    //TCP port to connect to

        // Set sender
        $mail->setFrom('email@arindam.co.in', 'Test Mailer');

        //Recipients
        if (is_array($recipients)) {
            foreach ($recipients as $index => $recipient) {
                if ($index === 0) {
                    $mail->addAddress($recipient); // First recipient as normal
                } else {
                    $mail->addBCC($recipient);     // Additional recipients as BCC
                }
            }
        } else {
            $mail->addAddress($recipients); // Single recipient
        }

        // Attachments
        foreach ($attachments as $attachment) {
            $mail->addAttachment($attachment);
        }

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $htmlContent;
        $mail->AltBody = $textContent;

        $mail->send();
        return ['success' => true, 'message' => 'Message has been sent'];
    } catch (Exception $e) {
        return ['success' => false, 'error' => $mail->ErrorInfo];
    }
}





// Example usage:
//$recipients = ['joe@example.net', 'ellen@example.com'];
//$subject = 'Here is the subject';
//$htmlContent = 'This is the HTML message body <b>in bold!</b>';
//$textContent = 'This is the body in plain text for non-HTML mail clients';
//$attachments = ['/var/tmp/file.tar.gz', '/tmp/image.jpg'];
//
//$response = sendEmail($recipients, $subject, $htmlContent, $textContent, $attachments);
//if ($response['success']) {
//    echo $response['message'];
//} else {
//    echo "Error: " . $response['error'];
//}




//$recipients = ['arindam.personal.25119@gmail.com'];
//$subject = 'Here is the subject';
//$htmlContent = 'This is the HTML message body <b>in bold!</b>';
//$textContent = 'This is the body in plain text for non-HTML mail clients';
//$attachments = [];
//
//$response = sendEmail($recipients, $subject, $htmlContent, $textContent, $attachments);
//if ($response['success']) {
//    echo $response['message'];
//} else {
//    echo "Error: " . $response['error'];
//}