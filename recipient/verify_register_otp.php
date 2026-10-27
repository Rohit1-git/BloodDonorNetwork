
<?php

session_start();

include("../includes/db.php");


// =====================================================
// CHECK DATABASE CONNECTION
// =====================================================

if ($conn->connect_error) {

    die("Database Connection Failed: " . $conn->connect_error);

}


// =====================================================
// CHECK PENDING REGISTRATION
// =====================================================

if (!isset($_SESSION['recipient_registration'])) {

    header("Location: register.php");
    exit();

}


$data = $_SESSION['recipient_registration'];


// =====================================================
// CHECK REQUIRED SESSION DATA
// =====================================================

$required_fields = [
    'name',
    'email',
    'phone',
    'blood_group',
    'city',
    'hospital',
    'address',
    'password',
    'otp',
    'otp_expiry'
];


foreach ($required_fields as $field) {

    if (!isset($data[$field])) {

        unset($_SESSION['recipient_registration']);

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

    if (!hash_equals(
        (string)$data['otp'],
        (string)$entered_otp
    )) {

        echo "<script>

            alert('Invalid OTP. Please check your email and try again.');

            window.location='verify_register_otp.php';

        </script>";

        exit();

    }


    // =================================================
    // CHECK OTP EXPIRY
    // =================================================

    if (time() > strtotime($data['otp_expiry'])) {

        unset($_SESSION['recipient_registration']);

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
        "SELECT id FROM recipients WHERE email = ? LIMIT 1"
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

        unset($_SESSION['recipient_registration']);

        echo "<script>

            alert('This email is already registered.');

            window.location='login.php';

        </script>";

        exit();

    }


    $check->close();


    // =================================================
    // CREATE RECIPIENT ACCOUNT
    // =================================================

    $stmt = $conn->prepare("

        INSERT INTO recipients
        (
            name,
            email,
            phone,
            blood_group,
            city,
            hospital,
            address,
            password
        )

        VALUES (?, ?, ?, ?, ?, ?, ?, ?)

    ");


    if (!$stmt) {

        die(
            "Database Error: " .
            htmlspecialchars($conn->error)
        );

    }


    $stmt->bind_param(

        "ssssssss",

        $data['name'],
        $data['email'],
        $data['phone'],
        $data['blood_group'],
        $data['city'],
        $data['hospital'],
        $data['address'],
        $data['password']

    );


    // =================================================
    // EXECUTE INSERT
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
    // GET NEW RECIPIENT ID
    // =================================================

    $recipient_id = $stmt->insert_id;


    $stmt->close();


    // =================================================
    // SAVE SUCCESS FLAG
    // =================================================

    $_SESSION['recipient_registration_success'] = true;

    $_SESSION['recipient_id'] = $recipient_id;


    // =================================================
    // CLEAR PENDING REGISTRATION DATA
    // =================================================

    unset($_SESSION['recipient_registration']);


    // =================================================
    // CLOSE DATABASE
    // =================================================

    $conn->close();


    // =================================================
    // REDIRECT TO SUCCESS PAGE
    // =================================================

    header("Location: registration_success.php");

    exit();

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

    <title>Verify Recipient Email</title>


    <!-- Bootstrap -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >


    <!-- Font Awesome -->

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >


    <style>

        body {

            background:#f5f7fa;

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

            background:#0d6efd;

            color:white;

            text-align:center;

            padding:25px;

        }


        .otp-header i {

            font-size:40px;

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

            border-color:#0d6efd;

            box-shadow:0 0 0 0.2rem rgba(13,110,253,.15);

        }


        .verify-btn {

            height:55px;

            font-weight:600;

            border-radius:10px;

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


            <i class="fa-solid fa-envelope-circle-check"></i>


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

                    We have sent a 6-digit OTP
                    to your registered email.

                </p>


                <div class="email-box">

                    <i class="fa-solid fa-envelope text-primary"></i>

                    <strong>

                        <?= htmlspecialchars($data['email']) ?>

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
                    class="btn btn-primary btn-lg w-100 verify-btn"
                >

                    <i class="fa-solid fa-circle-check"></i>

                    Verify OTP

                </button>


            </form>


            <!-- INFO -->

            <div class="text-center mt-4">


                <p class="text-muted small mb-1">

                    <i class="fa-solid fa-clock"></i>

                    OTP is valid for 10 minutes.

                </p>


                <p class="text-muted small">

                    Check your Spam/Junk folder if you
                    don't see the email.

                </p>


            </div>


            <hr>


            <!-- BACK -->

            <div class="text-center">


                <a
                    href="register.php"
                    class="text-decoration-none"
                >

                    <i class="fa-solid fa-arrow-left"></i>

                    Register Again

                </a>

            </div>


        </div>

    </div>

</div>


</body>

</html>
