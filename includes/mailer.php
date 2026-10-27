<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';


// =====================================================
// SMTP SETTINGS
// =====================================================

define('MAIL_USERNAME', 'blooddonornetwork.in@gmail.com');

// IMPORTANT:
// Yahan apna NEW Gmail App Password rakho.
// Normal Gmail password mat use karo.
define('MAIL_PASSWORD', ''); // Use your Gmail App Password


// =====================================================
// SEND BLOOD REQUEST EMAIL TO DONOR
// =====================================================

function sendBloodRequestEmail(
    $toEmail,
    $toName,
    $recipientName,
    $bloodGroup,
    $city,
    $hospital
) {

    $mail = new PHPMailer(true);

    try {

        // SMTP
        $mail->isSMTP();

        $mail->Host = 'smtp.gmail.com';

        $mail->SMTPAuth = true;

        $mail->Username = MAIL_USERNAME;

        $mail->Password = MAIL_PASSWORD;

        $mail->SMTPSecure =
            PHPMailer::ENCRYPTION_STARTTLS;

        $mail->Port = 587;


        // Sender
        $mail->setFrom(
            MAIL_USERNAME,
            'Blood Donor Emergency Network'
        );


        // Recipient
        $mail->addAddress(
            $toEmail,
            $toName
        );


        // HTML
        $mail->isHTML(true);

        $mail->Subject =
            'Emergency Blood Request - ' .
            $bloodGroup;


        $mail->Body = '

        <!DOCTYPE html>

        <html>

        <body style="
            font-family:Arial;
            background:#f5f5f5;
            padding:20px;
        ">

        <div style="
            max-width:600px;
            margin:auto;
            background:white;
            padding:30px;
            border-radius:10px;
        ">

        <h2 style="
            color:#dc3545;
            text-align:center;
        ">

        🩸 Blood Donor Emergency Alert

        </h2>


        <p>
        Hello
        <strong>' .
        htmlspecialchars($toName) .
        '</strong>,
        </p>


        <p>
        An emergency blood request matching
        your blood group has been submitted.
        </p>


        <hr>


        <h4>Request Details</h4>


        <p>
        <strong>Recipient:</strong>
        ' .
        htmlspecialchars($recipientName) .
        '
        </p>


        <p>
        <strong>Blood Group:</strong>

        <span style="
            color:#dc3545;
            font-weight:bold;
        ">

        ' .
        htmlspecialchars($bloodGroup) .
        '

        </span>

        </p>


        <p>
        <strong>City:</strong>
        ' .
        htmlspecialchars($city) .
        '
        </p>


        <p>
        <strong>Hospital:</strong>
        ' .
        htmlspecialchars($hospital) .
        '
        </p>


        <hr>


        <p>
        Please login to your Blood Donor Network
        account and check the request.
        </p>


        <div style="
            background:#dc3545;
            color:white;
            padding:15px;
            text-align:center;
            border-radius:5px;
        ">

        <strong>
        Your help can make a difference ❤️
        </strong>

        </div>


        <p style="
            color:#777;
            text-align:center;
            margin-top:20px;
        ">

        Blood Donor Emergency Network

        </p>


        </div>

        </body>

        </html>
        ';


        // Plain text
        $mail->AltBody =
            "Blood Donor Emergency Alert\n\n" .
            "Recipient: " . $recipientName . "\n" .
            "Blood Group: " . $bloodGroup . "\n" .
            "City: " . $city . "\n" .
            "Hospital: " . $hospital . "\n";


        // Send
        $mail->send();

        return true;


    } catch (Exception $e) {

        error_log(
            "Blood Request Email Error: " .
            $mail->ErrorInfo
        );

        return false;
    }
}



// =====================================================
// SEND ACCEPTANCE EMAIL TO RECIPIENT
// =====================================================

function sendDonorAcceptedEmail(
    $recipientEmail,
    $recipientName,
    $donorName,
    $donorPhone,
    $donorEmail,
    $bloodGroup,
    $hospital
) {

    $mail = new PHPMailer(true);

    try {

        // SMTP
        $mail->isSMTP();

        $mail->Host = 'smtp.gmail.com';

        $mail->SMTPAuth = true;

        $mail->Username = MAIL_USERNAME;

        $mail->Password = MAIL_PASSWORD;

        $mail->SMTPSecure =
            PHPMailer::ENCRYPTION_STARTTLS;

        $mail->Port = 587;


        // Sender
        $mail->setFrom(
            MAIL_USERNAME,
            'Blood Donor Emergency Network'
        );


        // Recipient
        $mail->addAddress(
            $recipientEmail,
            $recipientName
        );


        // HTML
        $mail->isHTML(true);

        $mail->Subject =
            'Blood Request Accepted - Donor Found';


        $mail->Body = '

        <!DOCTYPE html>

        <html>

        <body style="
            font-family:Arial;
            background:#f5f5f5;
            padding:20px;
        ">


        <div style="
            max-width:600px;
            margin:auto;
            background:white;
            padding:30px;
            border-radius:10px;
        ">


        <h2 style="
            color:#198754;
            text-align:center;
        ">

        ✅ Blood Request Accepted

        </h2>


        <p>

        Hello
        <strong>' .
        htmlspecialchars($recipientName) .
        '</strong>,

        </p>


        <p>

        Good news! A donor has accepted
        your blood request.

        </p>


        <hr>


        <h4>Donor Details</h4>


        <p>

        <strong>Name:</strong>
        ' .
        htmlspecialchars($donorName) .
        '

        </p>


        <p>

        <strong>Phone:</strong>
        ' .
        htmlspecialchars($donorPhone) .
        '

        </p>


        <p>

        <strong>Email:</strong>
        ' .
        htmlspecialchars($donorEmail) .
        '

        </p>


        <p>

        <strong>Blood Group:</strong>
        ' .
        htmlspecialchars($bloodGroup) .
        '

        </p>


        <p>

        <strong>Hospital:</strong>
        ' .
        htmlspecialchars($hospital) .
        '

        </p>


        <hr>


        <div style="
            background:#198754;
            color:white;
            padding:15px;
            text-align:center;
            border-radius:5px;
        ">

        <strong>
        Please contact the donor for coordination.
        </strong>

        </div>


        <p style="
            color:#777;
            text-align:center;
            margin-top:20px;
        ">

        Blood Donor Emergency Network

        </p>


        </div>


        </body>

        </html>

        ';


        // Plain text
        $mail->AltBody =
            "Blood Request Accepted\n\n" .
            "Donor Name: " . $donorName . "\n" .
            "Donor Phone: " . $donorPhone . "\n" .
            "Donor Email: " . $donorEmail . "\n" .
            "Blood Group: " . $bloodGroup . "\n" .
            "Hospital: " . $hospital . "\n";


        // Send
        $mail->send();

        return true;


    } catch (Exception $e) {

        error_log(
            "Donor Acceptance Email Error: " .
            $mail->ErrorInfo
        );

        return false;
    }
}

?>