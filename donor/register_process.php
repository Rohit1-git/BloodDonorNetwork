
<?php

session_start();

include("../includes/db.php");


// =====================================================
// PHPMailer
// =====================================================

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once("../vendor/autoload.php");


// =====================================================
// CHECK REQUEST
// =====================================================

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    header("Location: register.php");
    exit();

}


// =====================================================
// GET FORM DATA
// =====================================================

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$age = (int)($_POST['age'] ?? 0);
$blood_group = trim($_POST['blood_group'] ?? '');
$gender = trim($_POST['gender'] ?? '');
$city = trim($_POST['city'] ?? '');
$address = trim($_POST['address'] ?? '');
$availability = trim($_POST['availability'] ?? '');
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';


// =====================================================
// BASIC VALIDATION
// =====================================================

if (
    empty($name) ||
    empty($email) ||
    empty($phone) ||
    empty($blood_group) ||
    empty($gender) ||
    empty($city) ||
    empty($address) ||
    empty($availability) ||
    empty($password)
) {

    echo "<script>
        alert('Please fill all required fields.');
        window.location='register.php';
    </script>";

    exit();

}


// =====================================================
// EMAIL VALIDATION
// =====================================================

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

    echo "<script>
        alert('Please enter a valid email address.');
        window.location='register.php';
    </script>";

    exit();

}


// =====================================================
// PASSWORD MATCH
// =====================================================

if ($password !== $confirm_password) {

    echo "<script>
        alert('Passwords do not match!');
        window.location='register.php';
    </script>";

    exit();

}


// =====================================================
// PASSWORD LENGTH
// =====================================================

if (strlen($password) < 6) {

    echo "<script>
        alert('Password must be at least 6 characters.');
        window.location='register.php';
    </script>";

    exit();

}


// =====================================================
// CHECK EMAIL EXISTS
// =====================================================

$check = $conn->prepare(
    "SELECT id FROM donors WHERE email=? LIMIT 1"
);

if (!$check) {

    die(
        "Database Error: " .
        htmlspecialchars($conn->error)
    );

}

$check->bind_param("s", $email);

$check->execute();

$result = $check->get_result();


if ($result->num_rows > 0) {

    $check->close();

    echo "<script>
        alert('Email already registered!');
        window.location='register.php';
    </script>";

    exit();

}

$check->close();


// =====================================================
// GENERATE 6 DIGIT OTP
// =====================================================

$otp = (string)random_int(100000, 999999);


// OTP valid for 10 minutes

$otp_expiry = date(
    "Y-m-d H:i:s",
    time() + (10 * 60)
);


// =====================================================
// SAVE REGISTRATION DATA IN SESSION
// =====================================================

$_SESSION['donor_registration'] = [

    'name' => $name,

    'email' => $email,

    'phone' => $phone,

    'age' => $age,

    'blood_group' => $blood_group,

    'gender' => $gender,

    'city' => $city,

    'address' => $address,

    'availability' => $availability,

    'password' => password_hash(
        $password,
        PASSWORD_DEFAULT
    ),

    'otp' => $otp,

    'otp_expiry' => $otp_expiry

];


// =====================================================
// SEND OTP EMAIL
// =====================================================

$mail = new PHPMailer(true);


try {

    // -------------------------------------------------
    // SMTP
    // -------------------------------------------------

    $mail->isSMTP();

    $mail->Host = "smtp.gmail.com";

    $mail->SMTPAuth = true;


    // IMPORTANT:
    // Use your Gmail address here.

    $mail->Username = "";


    // IMPORTANT:
    // Use your Gmail APP PASSWORD here.
    // Do NOT use your normal Gmail password.

    $mail->Password = ""; // Use your Gmail App Password


    $mail->SMTPSecure =
        PHPMailer::ENCRYPTION_STARTTLS;

    $mail->Port = 587;


    // -------------------------------------------------
    // SENDER
    // -------------------------------------------------

    $mail->setFrom(
        $mail->Username,
        "Blood Donor Emergency Network"
    );


    // -------------------------------------------------
    // RECIPIENT
    // -------------------------------------------------

    $mail->addAddress(
        $email,
        $name
    );


    // -------------------------------------------------
    // EMAIL FORMAT
    // -------------------------------------------------

    $mail->isHTML(true);


    $mail->Subject =
        "Donor Registration OTP - Blood Donor Network";


    $safeName =
        htmlspecialchars($name);


    // -------------------------------------------------
    // EMAIL BODY
    // -------------------------------------------------

    $mail->Body = "

    <!DOCTYPE html>

    <html>

    <head>

        <meta charset='UTF-8'>

    </head>


    <body style='
        margin:0;
        padding:20px;
        background:#f5f5f5;
        font-family:Arial,sans-serif;
    '>


        <div style='
            max-width:600px;
            margin:auto;
            background:#ffffff;
            padding:30px;
            border-radius:10px;
            box-shadow:0 2px 10px rgba(0,0,0,0.1);
        '>


            <h2 style='
                color:#dc3545;
                text-align:center;
            '>

                🩸 Blood Donor Network

            </h2>


            <hr>


            <p>

                Hello
                <strong>{$safeName}</strong>,

            </p>


            <p>

                Thank you for registering as a
                blood donor.

            </p>


            <p>

                Please use the following OTP to
                verify your email address:

            </p>


            <div style='
                background:#dc3545;
                color:#ffffff;
                font-size:32px;
                font-weight:bold;
                letter-spacing:8px;
                text-align:center;
                padding:18px;
                border-radius:8px;
                margin:25px 0;
            '>

                {$otp}

            </div>


            <p>

                <strong>This OTP is valid for
                10 minutes.</strong>

            </p>


            <p style='color:#777;'>

                If you did not start this
                registration, you can safely
                ignore this email.

            </p>


            <hr>


            <p style='
                text-align:center;
                color:#777;
            '>

                Blood Donor Emergency Network

            </p>


        </div>


    </body>

    </html>

    ";


    // -------------------------------------------------
    // PLAIN TEXT
    // -------------------------------------------------

    $mail->AltBody =

        "Blood Donor Emergency Network\n\n" .

        "Hello " . $name . ",\n\n" .

        "Your donor registration OTP is: " .
        $otp . "\n\n" .

        "This OTP is valid for 10 minutes.\n\n" .

        "Blood Donor Emergency Network";


    // -------------------------------------------------
    // SEND
    // -------------------------------------------------

    $mail->send();


    // =================================================
    // OTP SENT SUCCESSFULLY
    // =================================================

    echo "<script>

        alert(
            'OTP sent successfully to your email.'
        );

        window.location='verify_register_otp.php';

    </script>";

    exit();


} catch (Exception $e) {


    // Remove pending registration data

    unset(
        $_SESSION['donor_registration']
    );


    error_log(
        "Donor Registration OTP Email Error: " .
        $mail->ErrorInfo
    );


    echo "<script>

        alert(
            'OTP could not be sent. Please check your email configuration.'
        );

        window.location='register.php';

    </script>";

    exit();

}

?>

