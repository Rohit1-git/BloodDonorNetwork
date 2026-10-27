
<?php

session_start();

include("../includes/db.php");

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Forgot Password - Recipient</title>


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


                <div class="card-header bg-primary text-white text-center">

                    <h2>

                        Forgot Password

                    </h2>

                    <p class="mb-0">

                        Recipient Account

                    </p>

                </div>


                <div class="card-body p-4">


                    <div class="text-center mb-4">

                        <div
                            class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center"
                            style="width:65px;height:65px;"
                        >

                            <i class="fa-solid fa-key fa-lg"></i>

                        </div>


                        <p class="text-muted mt-3">

                            Enter your registered recipient email
                            address. We will send you a 6-digit OTP.

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
                                class="form-control form-control-lg"
                                placeholder="Enter your registered email"
                                required
                            >


                        </div>


                        <button
                            type="submit"
                            name="send_otp"
                            class="btn btn-primary btn-lg w-100"
                        >

                            <i class="fa-solid fa-paper-plane"></i>

                            Send OTP

                        </button>


                    </form>


                    <hr>


                    <div class="text-center">


                        <a
                            href="login.php"
                            class="text-primary text-decoration-none fw-semibold"
                        >

                            ← Back to Recipient Login

                        </a>


                    </div>


                </div>


            </div>


        </div>


    </div>


</div>


</body>

</html>

