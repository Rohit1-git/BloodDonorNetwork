```php
<?php

session_start();

include("../includes/db.php");

require_once "../vendor/autoload.php";


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    header("Location: forgot_password.php");

    exit();

}


$email = trim($_POST['email'] ?? '');


if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

    echo "<script>

        alert('Please enter a valid email address.');

        window.location='forgot_password.php';

    </script>";

    exit();

}


// =====================================================
// FIND DONOR
// =====================================================

$stmt = $conn->prepare("
    SELECT id, name, email
    FROM donors
    WHERE email = ?
    LIMIT 1
");


$stmt->bind_param("s", $email);

$stmt->execute();

$result = $stmt->get_result();


if ($result->num_rows !== 1) {

    echo "<script>

        alert('No donor account found with this email.');

        window.location='forgot_password.php';

    </script>";

    exit();

}


$donor = $result->fetch_assoc();

$stmt->close();


// =====================================================
// GENERATE OTP
// =====================================================

$otp = str_pad(
    random_int(0, 999999),
    6,
    '0',
    STR_PAD_LEFT
);


$expiry = date(
    "Y-m-d H:i:s",
    time() + 600
);


// =====================================================
// SAVE OTP
// =====================================================

$update = $conn->prepare("
    UPDATE donors
    SET
        reset_otp = ?,
        reset_otp_expiry = ?
    WHERE id = ?
");


$update->bind_param(
    "ssi",
    $otp,
    $expiry,
    $donor['id']
);


if (!$update->execute()) {

    die("Unable to generate OTP.");

}


$update->close();


// =====================================================
// SEND EMAIL
// =====================================================

$mail = new PHPMailer(true);


try {

    // SMTP SETTINGS

    $mail->isSMTP();

    $mail->Host = 'smtp.gmail.com';

    $mail->SMTPAuth = true;

    $mail->Username = 'blooddonornetwork.in@gmail.com';

    $mail->Password = ''; // Use your Gmail App Password

    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

    $mail->Port = 587;


    // EMAIL

    $mail->setFrom(
        'rotu245001@gmail.com',
        'Blood Donor Network'
    );


    $mail->addAddress(
        $donor['email'],
        $donor['name']
    );


    $mail->isHTML(true);


    $mail->Subject =
        'Blood Donor Network - Password Reset OTP';


    $mail->Body = "

        <div style='
            font-family:Arial;
            max-width:600px;
            margin:auto;
            border:1px solid #ddd;
            padding:25px;
        '>

            <h2 style='color:#dc3545;'>
                Blood Donor Network
            </h2>

            <p>
                Hello <strong>"
                . htmlspecialchars($donor['name'])
                . "</strong>,
            </p>

            <p>
                We received a request to reset your password.
            </p>

            <p>
                Your OTP is:
            </p>

            <h1 style='
                letter-spacing:8px;
                color:#dc3545;
            '>
                $otp
            </h1>

            <p>
                This OTP is valid for
                <strong>10 minutes</strong>.
            </p>

            <p>
                If you did not request a password reset,
                you can safely ignore this email.
            </p>

            <hr>

            <p style='color:#777;'>
                Blood Donor Network
            </p>

        </div>

    ";


    $mail->send();


    // SAVE SESSION

    $_SESSION['reset_donor_id'] =
        $donor['id'];

    $_SESSION['reset_donor_email'] =
        $donor['email'];


    echo "<script>

        alert('OTP sent successfully to your email.');

        window.location='verify_otp.php';

    </script>";


} catch (Exception $e) {


    echo "<script>

        alert(
            'Email could not be sent. '
            + "
            . json_encode($mail->ErrorInfo)
            . "
        );

        window.location='forgot_password.php';

    </script>";

}


$conn->close();

?>
```
