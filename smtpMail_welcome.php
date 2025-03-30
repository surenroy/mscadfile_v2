<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';




//
//$response=sendSMTPMail(
//    toEmail: 'arindam.personal.25119@gmail.com',
//    name: 'John Doe123',
//    subject: 'OTP For Mscadfile.com',
//    otp: 123456
//);
//
//
//echo $response;



function sendSMTPMailWelcome($toEmail, $name){
    $mail = new PHPMailer(true); // Enable exceptions for error handling

    try {
        // SMTP server configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.hostinger.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'helpdesk@learning.org.in'; 
        $mail->Password = 'Kolkata@9748'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587; 

        // Email sender and recipient
        $mail->setFrom('helpdesk@learning.org.in', 'MS Cad File');
        $mail->addAddress($toEmail);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = "Welcome to MSCadFile.com!";

        $mailBody = '<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            background-color: #007bff;
            color: white;
            padding: 20px 0;
            border-radius: 10px 10px 0 0;
        }
        .content {
            padding: 20px;
            line-height: 1.6;
            color: #333;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #888;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 10px;
            color: white;
            background-color: #28a745;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #218838;
        }
        h2 {
            margin: 0;
            font-size: 24px;
        }
        p {
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>MS Cad File</h2>
        </div>
        <div class="content">
            <p>Dear ' . htmlspecialchars($name) . ',</p>
            <p>Thank you for signing up at MSCadFile.com. We’re excited to have you join our growing community of design professionals and enthusiasts!</p>
            <p>At MSCadFile.com, you can easily access and share CAD files, explore new design ideas, and connect with fellow creators. To get started, simply log in to your account and explore the features we offer.</p>
            <p>Should you have any questions or need assistance, feel free to reply to this email or visit our support page.</p>
            <p>Thank you once again for joining MSCadFile.com. We’re here to help you succeed in your design projects!</p>
            <p>Best regards,</p>
            <p>The MSCadFile Team</p>
            <p> <a href="https://www.mscadfile.com/"> www.MSCadFile.com </a> </p>
        </div>
        <div class="footer">
            <p>&copy; ' . date('Y') . ' MS Cad File. All rights reserved.</p>
        </div>
    </div>
</body>
</html>';



        $mail->Body = $mailBody;

        // Send email
        $mail->send();
        return true;
    } catch (Exception $e) {
        echo 'Mail could not be sent. Error: ', $mail->ErrorInfo;
        return false;
    }
}






?>
