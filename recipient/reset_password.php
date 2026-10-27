
<?php

session_start();

include("../includes/db.php");


// =====================================================
// CHECK OTP VERIFICATION
// =====================================================

if (
    !isset($_SESSION['reset_recipient_id']) ||
    !isset($_SESSION['recipient_otp_verified']) ||
    $_SESSION['recipient_otp_verified'] !== true
) {

    header("Location: forgot_password.php");
    exit();

}


$recipient_id = (int)$_SESSION['reset_recipient_id'];


// =====================================================
// FORM SUBMIT
// =====================================================

if ($_SERVER["REQUEST_METHOD"] === "POST") {


    $password = $_POST['password'] ?? '';

    $confirm_password = $_POST['confirm_password'] ?? '';


    // =================================================
    // CHECK PASSWORD
    // =================================================

    if ($password !== $confirm_password) {

        echo "<script>

            alert('Passwords do not match.');

            window.location='reset_password.php';

        </script>";

        exit();

    }


    // =================================================
    // PASSWORD LENGTH
    // =================================================

    if (strlen($password) < 6) {

        echo "<script>

            alert('Password must be at least 6 characters.');

            window.location='reset_password.php';

        </script>";

        exit();

    }


    // =================================================
    // HASH PASSWORD
    // =================================================

    $hash = password_hash(
        $password,
        PASSWORD_DEFAULT
    );


    // =================================================
    // UPDATE PASSWORD
    // =================================================

    $stmt = $conn->prepare("
        UPDATE recipients
        SET
            password = ?,
            reset_otp = NULL,
            reset_otp_expiry = NULL
        WHERE id = ?
    ");


    if (!$stmt) {

        die(
            "Database Error: " .
            $conn->error
        );

    }


    $stmt->bind_param(
        "si",
        $hash,
        $recipient_id
    );


    if ($stmt->execute()) {


        // =============================================
        // CLEAR RESET SESSION
        // =============================================

        unset(
            $_SESSION['reset_recipient_id'],
            $_SESSION['reset_recipient_email'],
            $_SESSION['recipient_otp_verified']
        );


        echo "<script>

            alert(
                'Password reset successfully! Please login with your new password.'
            );

            window.location='login.php';

        </script>";


        exit();


    } else {


        echo "<script>

            alert(
                'Unable to reset password. Please try again.'
            );

            window.location='reset_password.php';

        </script>";


        exit();

    }


    $stmt->close();

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
        Reset Password - Recipient
    </title>


    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >


    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
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

                        Reset Password

                    </h3>


                    <small>

                        Recipient Account

                    </small>

                </div>


                <!-- BODY -->

                <div class="card-body p-4">


                    <div class="text-center mb-4">


                        <div
                            class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center"
                            style="width:65px;height:65px;"
                        >

                            <i class="fa-solid fa-lock fa-lg"></i>

                        </div>


                        <p class="text-muted mt-3 mb-0">

                            Create a new password
                            for your account.

                        </p>


                    </div>


                    <form method="POST">


                        <!-- NEW PASSWORD -->

                        <div class="mb-3">


                            <label class="form-label fw-bold">

                                New Password

                            </label>


                            <input
                                type="password"
                                name="password"
                                class="form-control form-control-lg"
                                placeholder="Enter new password"
                                minlength="6"
                                required
                            >


                        </div>


                        <!-- CONFIRM PASSWORD -->

                        <div class="mb-4">


                            <label class="form-label fw-bold">

                                Confirm Password

                            </label>


                            <input
                                type="password"
                                name="confirm_password"
                                class="form-control form-control-lg"
                                placeholder="Confirm new password"
                                minlength="6"
                                required
                            >


                        </div>


                        <!-- RESET BUTTON -->

                        <button
                            type="submit"
                            class="btn btn-primary btn-lg w-100"
                        >

                            <i class="fa-solid fa-key"></i>

                            Reset Password

                        </button>


                    </form>


                </div>


            </div>


        </div>


    </div>


</div>


</body>

</html>

