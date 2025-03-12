<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';



//MAIL DATA

 $resetLink="####";
$msgData = [
    'name' => 'John Doe123',
    'message' => "Click the following link to reset your password: $resetLink \nThis link will expire in 1 hour.",
    'downloadLink' => 'https://example.com/complete'
];

$response=sendSMTPMail(
    toEmail: 'surenroy@electrocompsystem.com', 
    subject: 'Welcome to Our Service123', 
    msgData: $msgData
);


echo $response;



function sendSMTPMail($toEmail, $subject, $msgData)
{
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
        $mail->Subject = $subject;

        // Create HTML template for the email body
        $mailBody = '<!DOCTYPE html>
            <html>
            <head>
                <style>
                    .container {
                        font-family: Arial, sans-serif;
                        max-width: 600px;
                        margin: 0 auto;
                        padding: 20px;
                        border: 1px solid #ddd;
                        border-radius: 10px;
                        background-color: #f9f9f9;
                    }
                    .header {
                        text-align: center;
                        background-color: #007bff;
                        color: white;
                        padding: 10px 0;
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
                    }
                    .btn:hover {
                        background-color: #218838;
                    }
                </style>
            </head>
            <body>
                <div class="container">
                    <div class="header">
                        <h2>MS Cad File </h2>
                    </div>
                    <div class="content">
                        <p>Dear ' . htmlspecialchars($msgData['name']) . ',</p>
                        <p>' . htmlspecialchars($msgData['message']) . '</p>';

                    if (isset($msgData['downloadLink'])) {
                        $mailBody .= '<p>Download your file <a href="' . htmlspecialchars($msgData['downloadLink']) . '" class="btn">Click Here</a></p>
                                    <p>You can also open the URL to download your file: ' . htmlspecialchars($msgData['downloadLink']) . '</p>';
                    }

            $mailBody .= ' <p>Thank you.</p>
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
