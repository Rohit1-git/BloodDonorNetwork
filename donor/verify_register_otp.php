
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
// CHECK DATABASE CONNECTION
// =====================================================

if ($conn->connect_error) {

    die(
        "Database Connection Failed: " .
        htmlspecialchars($conn->connect_error)
    );

}


// =====================================================
// CHECK PENDING DONOR REGISTRATION
// =====================================================

if (!isset($_SESSION['donor_registration'])) {

    header("Location: register.php");
    exit();

}


$data = $_SESSION['donor_registration'];


// =====================================================
// CHECK REQUIRED REGISTRATION DATA
// =====================================================

$required_fields = [

    'name',
    'email',
    'phone',
    'password',
    'blood_group',
    'gender',
    'age',
    'city',
    'address',
    'availability',
    'otp',
    'otp_expiry'

];


foreach ($required_fields as $field) {

    if (!isset($data[$field])) {

        unset($_SESSION['donor_registration']);

        header("Location: register.php");

        exit();

    }

}


// =====================================================
// OTP VERIFICATION
// =====================================================

if ($_SERVER["REQUEST_METHOD"] === "POST") {


    $entered_otp = trim($_POST['otp'] ?? '');


    // =================================================
    // VALIDATE OTP FORMAT
    // =================================================

    if (!preg_match('/^[0-9]{6}$/', $entered_otp)) {

        echo "<script>

            alert('Please enter a valid 6-digit OTP.');

            window.location='verify_register_otp.php';

        </script>";

        exit();

    }


    // =================================================
    // CHECK OTP
    // =================================================

    if (
        !hash_equals(
            (string)$data['otp'],
            (string)$entered_otp
        )
    ) {

        echo "<script>

            alert('Invalid OTP. Please try again.');

            window.location='verify_register_otp.php';

        </script>";

        exit();

    }


    // =================================================
    // CHECK OTP EXPIRY
    // =================================================

    if (
        !isset($data['otp_expiry']) ||
        time() > strtotime($data['otp_expiry'])
    ) {

        unset($_SESSION['donor_registration']);

        echo "<script>

            alert('OTP has expired. Please register again.');

            window.location='register.php';

        </script>";

        exit();

    }


    // =================================================
    // CHECK EMAIL AGAIN
    // =================================================

    $check = $conn->prepare(
        "SELECT id FROM donors WHERE email = ? LIMIT 1"
    );


    if (!$check) {

        die(
            "Database Error: " .
            htmlspecialchars($conn->error)
        );

    }


    $check->bind_param(
        "s",
        $data['email']
    );


    $check->execute();

    $result = $check->get_result();


    if ($result->num_rows > 0) {

        $check->close();

        unset($_SESSION['donor_registration']);

        echo "<script>

            alert('This email is already registered.');

            window.location='login.php';

        </script>";

        exit();

    }


    $check->close();


    // =================================================
    // INSERT DONOR
    // =================================================

    $stmt = $conn->prepare("

        INSERT INTO donors
        (
            name,
            email,
            phone,
            password,
            blood_group,
            gender,
            age,
            city,
            address,
            availability
        )

        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)

    ");


    if (!$stmt) {

        die(
            "Database Error: " .
            htmlspecialchars($conn->error)
        );

    }


    $stmt->bind_param(

        "ssssssisss",

        $data['name'],
        $data['email'],
        $data['phone'],
        $data['password'],
        $data['blood_group'],
        $data['gender'],
        $data['age'],
        $data['city'],
        $data['address'],
        $data['availability']

    );


    // =================================================
    // CREATE DONOR ACCOUNT
    // =================================================

    if (!$stmt->execute()) {

        $error = $stmt->error;

        $stmt->close();

        echo "<script>

            alert('Registration failed: " .
            addslashes($error) .
            "');

            window.location='register.php';

        </script>";

        exit();

    }


    // =================================================
    // GET DONOR ID
    // =================================================

    $donor_id = $stmt->insert_id;

    $stmt->close();


    // =================================================
    // WELCOME EMAIL
    // =================================================

    $mail = new PHPMailer(true);


    try {


        // =================================================
        // SMTP SETTINGS
        // =================================================

        $mail->isSMTP();

        $mail->Host = "smtp.gmail.com";

        $mail->SMTPAuth = true;


        // -------------------------------------------------
        // YOUR GMAIL
        // -------------------------------------------------

        $mail->Username = "blooddonornetwork.in@gmail.com";


        // -------------------------------------------------
        // IMPORTANT:
        // Use a NEW Gmail App Password here.
        // Do NOT use your normal Gmail password.
        // -------------------------------------------------

        $mail->Password = "";


        $mail->SMTPSecure =
            PHPMailer::ENCRYPTION_STARTTLS;

        $mail->Port = 587;


        // =================================================
        // SENDER
        // =================================================

        $mail->setFrom(
            $mail->Username,
            "Blood Donor Emergency Network"
        );


        // =================================================
        // RECIPIENT
        // =================================================

        $mail->addAddress(
            $data['email'],
            $data['name']
        );


        // =================================================
        // EMAIL SETTINGS
        // =================================================

        $mail->isHTML(true);

        $mail->CharSet = "UTF-8";

        $mail->Subject =
            "Welcome to Blood Donor Network ❤️";


        // =================================================
        // SAFE DATA
        // =================================================

        $safeName =
            htmlspecialchars(
                $data['name'],
                ENT_QUOTES,
                'UTF-8'
            );


        $safeBlood =
            htmlspecialchars(
                $data['blood_group'],
                ENT_QUOTES,
                'UTF-8'
            );


        $safeCity =
            htmlspecialchars(
                $data['city'],
                ENT_QUOTES,
                'UTF-8'
            );


        $safeEmail =
            htmlspecialchars(
                $data['email'],
                ENT_QUOTES,
                'UTF-8'
            );


        // =================================================
        // HTML EMAIL
        // =================================================

        $mail->Body = "

<!DOCTYPE html>

<html>

<head>

    <meta charset='UTF-8'>

    <meta name='viewport'
          content='width=device-width, initial-scale=1.0'>

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
    border-radius:12px;
    box-shadow:0 3px 15px rgba(0,0,0,0.10);
'>


    <!-- HEADER -->

    <div style='text-align:center;'>

        <h1 style='
            color:#dc3545;
            margin-bottom:5px;
        '>

            🩸 Blood Donor Network

        </h1>


        <p style='color:#777;'>

            Blood Donor Emergency Network

        </p>

    </div>


    <hr>


    <!-- WELCOME -->

    <h2 style='color:#dc3545;'>

        Welcome, {$safeName}! ❤️

    </h2>


    <p style='
        font-size:16px;
        line-height:1.6;
    '>

        Congratulations! Your donor account has been
        successfully verified and created.

    </p>


    <p style='
        font-size:16px;
        line-height:1.6;
    '>

        Thank you for joining our Blood Donor Network
        and helping people during emergency situations.

    </p>


    <!-- ACCOUNT DETAILS -->

    <div style='
        background:#f8f9fa;
        padding:20px;
        border-radius:8px;
        margin:20px 0;
    '>


        <p>

            <strong>Name:</strong>

            {$safeName}

        </p>


        <p>

            <strong>Blood Group:</strong>

            {$safeBlood}

        </p>


        <p>

            <strong>City:</strong>

            {$safeCity}

        </p>


        <p>

            <strong>Email:</strong>

            {$safeEmail}

        </p>


        <p style='margin-bottom:0;'>

            <strong>Account Status:</strong>

            <span style='color:#198754;'>

                Verified ✓

            </span>

        </p>


    </div>


    <p style='
        font-size:16px;
        line-height:1.6;
    '>

        Your account is now ready.

        You can login and manage your donor profile
        anytime.

    </p>


    <!-- LOGIN BUTTON -->

    <div style='
        text-align:center;
        margin:30px 0;
    '>


        <a
            href='http://localhost/BloodDonorNetwork/donor/login.php'
            style='
                display:inline-block;
                background:#dc3545;
                color:#ffffff;
                padding:13px 28px;
                border-radius:7px;
                text-decoration:none;
                font-weight:bold;
            '
        >

            Login to Your Account

        </a>


    </div>


    <hr>


    <!-- FOOTER -->

    <p style='
        text-align:center;
        color:#777;
        font-size:13px;
        line-height:1.6;
    '>

        Thank you for helping save lives. ❤️

        <br>

        Blood Donor Emergency Network

    </p>


</div>


</body>

</html>

";


        // =================================================
        // PLAIN TEXT EMAIL
        // =================================================

        $mail->AltBody =

            "Welcome to Blood Donor Network!\n\n" .

            "Hello " .
            $data['name'] .
            ",\n\n" .

            "Your donor account has been successfully " .
            "verified and created.\n\n" .

            "Blood Group: " .
            $data['blood_group'] .
            "\n" .

            "City: " .
            $data['city'] .
            "\n\n" .

            "Thank you for joining Blood Donor Network " .
            "and helping save lives.\n\n" .

            "You can now login to your account.";


        // =================================================
        // SEND EMAIL
        // =================================================

        $mail->send();


        // =================================================
        // SAVE SUCCESS SESSION
        // =================================================

        $_SESSION['donor_registration_success'] = true;

        $_SESSION['donor_id'] = $donor_id;


        // =================================================
        // CLEAR PENDING REGISTRATION
        // =================================================

        unset(
            $_SESSION['donor_registration']
        );


        // =================================================
        // CLOSE DATABASE
        // =================================================

        $conn->close();


        // =================================================
        // REDIRECT TO SUCCESS PAGE
        // =================================================

        header(
            "Location: registration_success.php"
        );

        exit();


    } catch (Exception $e) {


        // =================================================
        // IMPORTANT
        // Account WAS already created.
        // Email failure should NOT delete the account.
        // =================================================


        $_SESSION['donor_registration_success'] = true;

        $_SESSION['donor_id'] = $donor_id;


        unset(
            $_SESSION['donor_registration']
        );


        // Log email error

        error_log(
            "Donor Welcome Email Error: " .
            $mail->ErrorInfo
        );


        $conn->close();


        // =================================================
        // STILL SHOW SUCCESS PAGE
        // =================================================

        header(
            "Location: registration_success.php"
        );

        exit();

    }

}

?>


<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Verify Donor Email</title>


    <!-- Bootstrap -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >


    <style>

        body {

            background:#f8f9fa;

        }


        .otp-wrapper {

            min-height:100vh;

            display:flex;

            align-items:center;

            justify-content:center;

            padding:30px 15px;

        }


        .otp-card {

            width:100%;

            max-width:480px;

            border:none;

            border-radius:18px;

            overflow:hidden;

        }


        .otp-header {

            background:#dc3545;

            color:white;

            text-align:center;

            padding:25px;

        }


        .otp-header i {

            font-size:45px;

            margin-bottom:10px;

        }


        .otp-body {

            padding:35px;

        }


        .otp-input {

            height:65px;

            font-size:28px;

            font-weight:bold;

            letter-spacing:10px;

            text-align:center;

            border-radius:10px;

        }


        .otp-input:focus {

            border-color:#dc3545;

            box-shadow:
                0 0 0 0.2rem
                rgba(220,53,69,.15);

        }


        .verify-btn {

            height:55px;

            border-radius:10px;

            font-weight:600;

        }


        .email-box {

            background:#f8f9fa;

            border-radius:10px;

            padding:12px;

            word-break:break-word;

        }

    </style>

</head>


<body>


<div class="container otp-wrapper">


    <div class="card shadow-lg otp-card">


        <!-- HEADER -->

        <div class="otp-header">


            <div style="font-size:45px;">

                ✉️

            </div>


            <h3 class="mb-1">

                Verify Your Email

            </h3>


            <p class="mb-0">

                Blood Donor Network

            </p>


        </div>


        <!-- BODY -->

        <div class="otp-body">


            <div class="text-center mb-4">


                <h5 class="fw-bold">

                    Enter Verification Code

                </h5>


                <p class="text-muted">

                    We sent a 6-digit OTP to:

                </p>


                <div class="email-box">


                    <strong>

                        <?= htmlspecialchars(
                            $data['email']
                        ) ?>

                    </strong>


                </div>

            </div>


            <!-- OTP FORM -->

            <form
                method="POST"
                autocomplete="off"
            >


                <div class="mb-4">


                    <label
                        class="form-label fw-semibold"
                    >

                        Enter OTP

                    </label>


                    <input
                        type="text"
                        name="otp"
                        class="form-control otp-input"
                        maxlength="6"
                        minlength="6"
                        pattern="[0-9]{6}"
                        inputmode="numeric"
                        autocomplete="one-time-code"
                        placeholder="000000"
                        required
                    >

                </div>


                <button
                    type="submit"
                    class="btn btn-danger btn-lg w-100 verify-btn"
                >

                    Verify OTP

                </button>


            </form>


            <div class="text-center mt-4">


                <p class="text-muted small mb-1">

                    OTP is valid for 10 minutes.

                </p>


                <p class="text-muted small">

                    Check your Spam/Junk folder if you
                    don't see the email.

                </p>

            </div>


            <hr>


            <div class="text-center">


                <a
                    href="register.php"
                    class="text-danger text-decoration-none"
                >

                    ← Register Again

                </a>


            </div>


        </div>

    </div>

</div>


</body>

</html>

