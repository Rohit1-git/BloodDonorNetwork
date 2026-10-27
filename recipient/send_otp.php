<?php

session_start();

include("../includes/db.php");

require_once "../vendor/autoload.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


// =====================================================
// CHECK REQUEST
// =====================================================

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    header("Location: forgot_password.php");
    exit();

}


// =====================================================
// GET EMAIL
// =====================================================

$email = trim($_POST['email'] ?? '');


// =====================================================
// VALIDATE EMAIL
// =====================================================

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

    echo "<script>
        alert('Please enter a valid email address.');
        window.location='forgot_password.php';
    </script>";

    exit();

}


// =====================================================
// FIND RECIPIENT
// =====================================================

$stmt = $conn->prepare("
    SELECT id, name, email
    FROM recipients
    WHERE email = ?
    LIMIT 1
");

if (!$stmt) {

    die("Database Error: " . $conn->error);

}

$stmt->bind_param("s", $email);

$stmt->execute();

$result = $stmt->get_result();


if ($result->num_rows !== 1) {

    $stmt->close();

    echo "<script>
        alert('No recipient account found with this email.');
        window.location='forgot_password.php';
    </script>";

    exit();

}


$recipient = $result->fetch_assoc();

$stmt->close();


// =====================================================
// GENERATE 6 DIGIT OTP
// =====================================================

try {

    $otp = str_pad(
        random_int(0, 999999),
        6,
        "0",
        STR_PAD_LEFT
    );

} catch (Exception $e) {

    $otp = str_pad(
        mt_rand(0, 999999),
        6,
        "0",
        STR_PAD_LEFT
    );

}


// =====================================================
// OTP EXPIRY - 10 MINUTES
// =====================================================

$expiry = date(
    "Y-m-d H:i:s",
    time() + 600
);


// =====================================================
// SAVE OTP IN DATABASE
// =====================================================

$update = $conn->prepare("
    UPDATE recipients
    SET
        reset_otp = ?,
        reset_otp_expiry = ?
    WHERE id = ?
");

if (!$update) {

    die("Database Error: " . $conn->error);

}


$update->bind_param(
    "ssi",
    $otp,
    $expiry,
    $recipient['id']
);


if (!$update->execute()) {

    die("OTP Save Error: " . $update->error);

}

$update->close();


// =====================================================
// PHPMailer
// =====================================================

$mail = new PHPMailer(true);


try {

    // =================================================
    // SMTP CONFIGURATION
    // =================================================

    $mail->isSMTP();

    $mail->Host = "smtp.gmail.com";

    $mail->SMTPAuth = true;

    // Gmail account
    $mail->Username = "blooddonornetwork.in@gmail.com";

    // IMPORTANT:
    // Put your NEW Google App Password here.
    // Do NOT use your normal Gmail password.
    //
    // Example:
    // Google App Password: abcd efgh ijkl mnop
    //
    // PHP:
    // $mail->Password = "abcdefghijklmnop";

    $mail->Password = ""; // Use your Gmail App Password 


    // Gmail TLS
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

    $mail->Port = 587;

    // Timeout
    $mail->Timeout = 30;


    // =================================================
    // SENDER
    // =================================================

    $mail->setFrom(
        "blooddonornetwork.in@gmail.com",
        "Blood Donor Network"
    );


    // =================================================
    // RECIPIENT
    // =================================================

    $mail->addAddress(
        $recipient['email'],
        $recipient['name']
    );


    // =================================================
    // EMAIL FORMAT
    // =================================================

    $mail->isHTML(true);


    // =================================================
    // SUBJECT
    // =================================================

    $mail->Subject =
        "Blood Donor Network - Password Reset OTP";


    // =================================================
    // HTML EMAIL
    // =================================================

    $safeName = htmlspecialchars(
        $recipient['name'],
        ENT_QUOTES,
        'UTF-8'
    );


    $mail->Body = "

    <div style='
        font-family:Arial,Helvetica,sans-serif;
        background:#f4f6f8;
        padding:30px;
    '>

        <div style='
            max-width:600px;
            margin:auto;
            background:#ffffff;
            border:1px solid #ddd;
            border-radius:12px;
            padding:30px;
        '>

            <h2 style='
                color:#0d6efd;
                margin-top:0;
            '>
                Blood Donor Network
            </h2>


            <p>
                Hello <strong>{$safeName}</strong>,
            </p>


            <p>
                We received a request to reset
                your recipient account password.
            </p>


            <p>
                Your verification OTP is:
            </p>


            <div style='
                text-align:center;
                margin:25px 0;
            '>

                <span style='
                    display:inline-block;
                    background:#0d6efd;
                    color:#ffffff;
                    font-size:30px;
                    font-weight:bold;
                    letter-spacing:8px;
                    padding:15px 25px;
                    border-radius:8px;
                '>
                    {$otp}
                </span>

            </div>


            <p>
                This OTP is valid for
                <strong>10 minutes</strong>.
            </p>


            <p style='color:#666666;'>
                If you did not request a password reset,
                please ignore this email.
            </p>


            <hr style='border:0;border-top:1px solid #ddd;'>


            <p style='
                color:#888888;
                font-size:13px;
                margin-bottom:0;
            '>

                Blood Donor Network<br>
                Himachal Pradesh, India

            </p>

        </div>

    </div>

    ";


    // =================================================
    // PLAIN TEXT VERSION
    // =================================================

    $mail->AltBody =
        "Blood Donor Network\n\n" .
        "Hello " . $recipient['name'] . ",\n\n" .
        "Your password reset OTP is: " . $otp . "\n\n" .
        "This OTP is valid for 10 minutes.\n\n" .
        "If you did not request this password reset, " .
        "please ignore this email.";


    // =================================================
    // SEND EMAIL
    // =================================================

    $mail->send();


    // =================================================
    // SAVE RESET SESSION
    // =================================================

    $_SESSION['reset_recipient_id'] =
        $recipient['id'];

    $_SESSION['reset_recipient_email'] =
        $recipient['email'];


    // =================================================
    // SUCCESS
    // =================================================

    echo "<script>

        alert('OTP sent successfully to your email.');

        window.location='verify_otp.php';

    </script>";

    exit();


} catch (Exception $e) {

    // =================================================
    // EMAIL ERROR
    // =================================================

    $errorMessage = $mail->ErrorInfo;

    echo "<!DOCTYPE html>
    <html>
    <head>
        <title>Email Error</title>

        <style>

            body {
                font-family: Arial, sans-serif;
                background: #f8f9fa;
                display: flex;
                justify-content: center;
                align-items: center;
                min-height: 100vh;
                margin: 0;
            }

            .error-box {
                width: 90%;
                max-width: 600px;
                background: white;
                padding: 30px;
                border-radius: 12px;
                box-shadow: 0 5px 25px rgba(0,0,0,0.1);
                text-align: center;
            }

            h2 {
                color: #dc3545;
            }

            .error {
                background: #f8d7da;
                color: #842029;
                padding: 15px;
                border-radius: 8px;
                margin: 20px 0;
                word-break: break-word;
            }

            a {
                display: inline-block;
                background: #0d6efd;
                color: white;
                text-decoration: none;
                padding: 10px 20px;
                border-radius: 6px;
            }

        </style>

    </head>

    <body>

        <div class='error-box'>

            <h2>❌ Email Failed</h2>

            <div class='error'>
                SMTP Error: "
                . htmlspecialchars(
                    $errorMessage,
                    ENT_QUOTES,
                    'UTF-8'
                )
                . "
            </div>

            <a href='forgot_password.php'>
                Try Again
            </a>

        </div>

    </body>
    </html>";

}


$conn->close();

?>