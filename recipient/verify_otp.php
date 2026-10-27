
<?php

session_start();

include("../includes/db.php");


// =====================================================
// CHECK RESET SESSION
// =====================================================

if (!isset($_SESSION['reset_recipient_id'])) {

    header("Location: forgot_password.php");
    exit();

}


$recipient_id = (int)$_SESSION['reset_recipient_id'];


// =====================================================
// VERIFY OTP
// =====================================================

if ($_SERVER["REQUEST_METHOD"] === "POST") {


    $otp = trim($_POST['otp'] ?? '');


    // =================================================
    // VALIDATE OTP FORMAT
    // =================================================

    if (!preg_match('/^[0-9]{6}$/', $otp)) {

        echo "<script>

            alert('Please enter a valid 6-digit OTP.');

            window.location='verify_otp.php';

        </script>";

        exit();

    }


    // =================================================
    // GET OTP FROM DATABASE
    // =================================================

    $stmt = $conn->prepare("
        SELECT reset_otp, reset_otp_expiry
        FROM recipients
        WHERE id = ?
        LIMIT 1
    ");


    if (!$stmt) {

        die("Database Error: " . $conn->error);

    }


    $stmt->bind_param(
        "i",
        $recipient_id
    );


    $stmt->execute();


    $result = $stmt->get_result();


    if ($result->num_rows !== 1) {

        echo "<script>

            alert('Recipient account not found.');

            window.location='forgot_password.php';

        </script>";

        exit();

    }


    $recipient = $result->fetch_assoc();


    $stmt->close();


    // =================================================
    // CHECK OTP EXISTS
    // =================================================

    if (
        empty($recipient['reset_otp']) ||
        empty($recipient['reset_otp_expiry'])
    ) {

        echo "<script>

            alert(
                'OTP not found. Please request a new OTP.'
            );

            window.location='forgot_password.php';

        </script>";

        exit();

    }


    // =================================================
    // CHECK OTP
    // =================================================

    if (
        !hash_equals(
            (string)$recipient['reset_otp'],
            (string)$otp
        )
    ) {

        echo "<script>

            alert(
                'Invalid OTP. Please check your email and try again.'
            );

            window.location='verify_otp.php';

        </script>";

        exit();

    }


    // =================================================
    // CHECK OTP EXPIRY
    // =================================================

    $expiryTime =
        strtotime(
            $recipient['reset_otp_expiry']
        );


    $currentTime = time();


    if ($expiryTime === false) {

        echo "<script>

            alert(
                'OTP expiry information is invalid. Please request a new OTP.'
            );

            window.location='forgot_password.php';

        </script>";

        exit();

    }


    if ($currentTime > $expiryTime) {

        echo "<script>

            alert(
                'OTP has expired. Please request a new OTP.'
            );

            window.location='forgot_password.php';

        </script>";

        exit();

    }


    // =================================================
    // OTP VERIFIED
    // =================================================

    $_SESSION['recipient_otp_verified'] = true;


    echo "<script>

        alert('OTP verified successfully!');

        window.location='reset_password.php';

    </script>";

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


    <title>
        Verify OTP - Recipient
    </title>


    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

</head>


<body class="bg-light">


<div class="container">


    <div
        class="row justify-content-center align-items-center"
        style="min-height:100vh;"
    >


        <div class="col-md-5">


            <div class="card shadow-lg border-0">


                <!-- HEADER -->

                <div
                    class="card-header bg-primary text-white text-center"
                >

                    <h3 class="mb-1">

                        Verify OTP

                    </h3>


                    <small>

                        Recipient Account

                    </small>

                </div>


                <!-- BODY -->

                <div class="card-body p-4">


                    <div class="text-center mb-4">


                        <p class="text-muted">

                            Enter the 6-digit OTP sent
                            to your registered email.

                        </p>


                    </div>


                    <form method="POST">


                        <div class="mb-3">


                            <label class="form-label fw-bold">

                                Enter OTP

                            </label>


                            <input
                                type="text"
                                name="otp"
                                class="form-control form-control-lg text-center"
                                maxlength="6"
                                minlength="6"
                                inputmode="numeric"
                                pattern="[0-9]{6}"
                                placeholder="Enter 6-digit OTP"
                                autocomplete="one-time-code"
                                required
                            >


                        </div>


                        <button
                            type="submit"
                            class="btn btn-primary btn-lg w-100"
                        >

                            <i class="fa-solid fa-check"></i>

                            Verify OTP

                        </button>


                    </form>


                    <div class="text-center mt-3">


                        <a
                            href="forgot_password.php"
                            class="text-primary text-decoration-none fw-semibold"
                        >

                            Request New OTP

                        </a>


                    </div>


                </div>


            </div>


        </div>


    </div>


</div>


</body>

</html>

