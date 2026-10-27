
<?php

session_start();

include("../includes/db.php");

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Forgot Password - Donor</title>

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

    <div class="row justify-content-center align-items-center"
         style="min-height:100vh;">

        <div class="col-md-5">


            <div class="card shadow border-0">


                <div class="card-body p-4">


                    <div class="text-center mb-4">

                        <div
                            class="bg-danger text-white rounded-circle d-inline-flex align-items-center justify-content-center"
                            style="width:65px;height:65px;"
                        >

                            <i class="fa-solid fa-key fa-lg"></i>

                        </div>


                        <h3 class="mt-3">

                            Forgot Password

                        </h3>


                        <p class="text-muted">

                            Enter your registered donor email.

                        </p>

                    </div>


                    <form
                        method="POST"
                        action="send_otp.php"
                    >


                        <div class="mb-3">

                            <label class="form-label fw-bold">

                                Email Address

                            </label>


                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                placeholder="Enter your email"
                                required
                            >

                        </div>


                        <button
                            type="submit"
                            name="send_otp"
                            class="btn btn-danger w-100"
                        >

                            <i class="fa-solid fa-paper-plane"></i>

                            Send OTP

                        </button>


                    </form>


                    <div class="text-center mt-3">

                        <a
                            href="login.php"
                            class="text-decoration-none"
                        >

                            ← Back to Login

                        </a>

                    </div>


                </div>

            </div>


        </div>

    </div>

</div>


</body>

</html>