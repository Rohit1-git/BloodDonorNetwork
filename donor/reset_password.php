
<?php

session_start();

include("../includes/db.php");


if (
    !isset($_SESSION['reset_donor_id']) ||
    !isset($_SESSION['donor_otp_verified'])
) {

    header("Location: forgot_password.php");

    exit();

}


if ($_SERVER["REQUEST_METHOD"] === "POST") {


    $password = $_POST['password'] ?? '';

    $confirm_password =
        $_POST['confirm_password'] ?? '';


    // =================================================
    // PASSWORD CHECK
    // =================================================

    if (strlen($password) < 8) {

        echo "<script>

            alert('Password must be at least 8 characters.');

            window.location='reset_password.php';

        </script>";

        exit();

    }


    if ($password !== $confirm_password) {

        echo "<script>

            alert('Passwords do not match.');

            window.location='reset_password.php';

        </script>";

        exit();

    }


    $donor_id = $_SESSION['reset_donor_id'];


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
        UPDATE donors
        SET
            password = ?,
            reset_otp = NULL,
            reset_otp_expiry = NULL
        WHERE id = ?
    ");


    $stmt->bind_param(
        "si",
        $hash,
        $donor_id
    );


    if ($stmt->execute()) {


        // Clear reset session

        unset($_SESSION['reset_donor_id']);

        unset($_SESSION['reset_donor_email']);

        unset($_SESSION['donor_otp_verified']);


        echo "<script>

            alert('Password reset successfully!');

            window.location='login.php';

        </script>";


        $stmt->close();

        $conn->close();

        exit();


    } else {


        echo "<script>

            alert('Password reset failed.');

            window.location='reset_password.php';

        </script>";

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

    <title>Reset Password</title>


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


            <div class="card shadow border-0">

                <div class="card-body p-4">


                    <h3 class="text-center mb-4">

                        Create New Password

                    </h3>


                    <form method="POST">


                        <div class="mb-3">

                            <label class="form-label fw-bold">

                                New Password

                            </label>


                            <input
                                type="password"
                                name="password"
                                class="form-control"
                                minlength="8"
                                required
                            >

                        </div>



                        <div class="mb-3">

                            <label class="form-label fw-bold">

                                Confirm Password

                            </label>


                            <input
                                type="password"
                                name="confirm_password"
                                class="form-control"
                                minlength="8"
                                required
                            >

                        </div>


                        <button
                            type="submit"
                            class="btn btn-danger w-100"
                        >

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

