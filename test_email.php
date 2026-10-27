<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/vendor/autoload.php';

$mail = new PHPMailer(true);

try {

    // SMTP
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;

    // YOUR GMAIL
    $mail->Username = 'blooddonornetwork.in@gmail.com';

        // YOUR GMAIL APP PASSWORD
    $mail->Password = ''; // Use your Gmail App Password 

    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Sender
    $mail->setFrom(
        $mail->Username,
        'Blood Donor Network'
    );

    // TEST RECEIVER
    $mail->addAddress(
        'YOUR_TEST_EMAIL@gmail.com'
    );

    // Email
    $mail->isHTML(true);

    $mail->Subject = 'Blood Donor Network - Test Email';

    $mail->Body = '
        <h2 style="color:#dc3545;">
            🩸 Blood Donor Network
        </h2>

        <p>
            Email notification system is working successfully!
        </p>

        <p>
            This is a test email.
        </p>

        <h3>✅ SMTP Connection Successful</h3>
    ';

    $mail->AltBody =
        'Blood Donor Network email notification test successful.';

    $mail->send();

    echo "
    <div style='
        font-family:Arial;
        max-width:600px;
        margin:50px auto;
        padding:30px;
        text-align:center;
        border:1px solid #ddd;
        border-radius:10px;
    '>

        <h2 style='color:green;'>
            ✅ Email Sent Successfully
        </h2>

        <p>
            Check the receiver's inbox.
        </p>

    </div>
    ";

} catch (Exception $e) {

    echo "
    <div style='
        font-family:Arial;
        max-width:600px;
        margin:50px auto;
        padding:30px;
        border:1px solid #ddd;
        border-radius:10px;
    '>

        <h2 style='color:red;'>
            ❌ Email Failed
        </h2>

        <p>
            " . htmlspecialchars($mail->ErrorInfo) . "
        </p>

    </div>
    ";
}
?>