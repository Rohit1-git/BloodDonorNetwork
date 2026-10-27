
<?php

session_start();


// =====================================================
// CHECK REGISTRATION SUCCESS
// =====================================================

if (!isset($_SESSION['recipient_registration_success'])) {

    header("Location: register.php");
    exit();

}


// =====================================================
// GET RECIPIENT ID
// =====================================================

$recipient_id = $_SESSION['recipient_id'] ?? null;


// =====================================================
// CLEAR SUCCESS SESSION
// =====================================================

unset($_SESSION['recipient_registration_success']);

unset($_SESSION['recipient_id']);

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Registration Successful</title>


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


        .success-wrapper {

            min-height:100vh;

            display:flex;

            align-items:center;

            justify-content:center;

            padding:30px 15px;

        }


        .success-card {

            width:100%;

            max-width:620px;

            border:none;

            border-radius:20px;

            overflow:hidden;

        }


        .success-header {

            background:#198754;

            color:white;

            text-align:center;

            padding:30px;

        }


        .success-icon {

            width:100px;

            height:100px;

            margin:0 auto 20px;

            border-radius:50%;

            background:white;

            color:#198754;

            display:flex;

            align-items:center;

            justify-content:center;

            font-size:55px;

        }


        .success-body {

            padding:40px;

        }


        .success-title {

            font-size:32px;

            font-weight:700;

            color:#198754;

        }


        .success-message {

            color:#6c757d;

            font-size:17px;

            line-height:1.6;

        }


        .success-info {

            background:#f8f9fa;

            border-radius:12px;

            padding:20px;

            margin-top:25px;

        }


        .success-info p {

            margin-bottom:12px;

        }


        .success-info p:last-child {

            margin-bottom:0;

        }


        .login-btn {

            height:55px;

            border-radius:10px;

            font-weight:600;

            font-size:17px;

        }


        .home-link {

            text-decoration:none;

            color:#6c757d;

        }


        .home-link:hover {

            color:#198754;

        }

    </style>

</head>


<body>


<div class="container success-wrapper">


    <div class="card shadow-lg success-card">


        <!-- SUCCESS HEADER -->

        <div class="success-header">


            <div class="success-icon">

                <i class="fa-solid fa-check"></i>

            </div>


            <h2 class="mb-1">

                Registration Successful!

            </h2>


            <p class="mb-0">

                Blood Donor Network

            </p>


        </div>


        <!-- BODY -->

        <div class="success-body text-center">


            <h1 class="success-title">

                Welcome! ❤️

            </h1>


            <p class="success-message mt-3">

                Your recipient account has been created
                successfully.

                Your email address has also been verified.

            </p>


            <!-- REGISTRATION DETAILS -->

            <div class="success-info text-start">


                <p>

                    <i class="fa-solid fa-circle-check text-success me-2"></i>

                    <strong>Email Verified</strong>

                </p>


                <p>

                    <i class="fa-solid fa-circle-check text-success me-2"></i>

                    <strong>Recipient Account Created</strong>

                </p>


                <p>

                    <i class="fa-solid fa-circle-check text-success me-2"></i>

                    <strong>Registration Completed</strong>

                </p>


            </div>


            <!-- MESSAGE -->

            <div class="mt-4">


                <h5 class="fw-bold">

                    Your account is ready 🎉

                </h5>


                <p class="text-muted">

                    You can now login to your account
                    and request blood whenever required.

                </p>

            </div>


            <!-- LOGIN BUTTON -->

            <div class="d-grid mt-4">


                <a
                    href="login.php"
                    class="btn btn-primary btn-lg login-btn"
                >

                    <i class="fa-solid fa-right-to-bracket me-2"></i>

                    Login Now

                </a>


            </div>


            <!-- HOME -->

            <div class="mt-3">


                <a
                    href="../index.php"
                    class="home-link"
                >

                    <i class="fa-solid fa-house me-1"></i>

                    Back to Home

                </a>


            </div>


        </div>

    </div>

</div>


</body>

</html>

