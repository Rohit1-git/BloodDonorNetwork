<?php

session_start();


// =====================================================
// CHECK DONOR LOGIN
// =====================================================

if (!isset($_SESSION['donor_id'])) {

    header("Location: login.php");
    exit();

}


include("../includes/db.php");


// =====================================================
// LOGGED-IN DONOR
// =====================================================

$donor_id = $_SESSION['donor_id'];


// =====================================================
// GET DONOR DETAILS
// =====================================================

$stmt = $conn->prepare("
    SELECT *
    FROM donors
    WHERE id = ?
");


if (!$stmt) {

    die(
        "Database Error: " .
        htmlspecialchars($conn->error)
    );

}


$stmt->bind_param(
    "i",
    $donor_id
);

$stmt->execute();

$result = $stmt->get_result();

$donor = $result->fetch_assoc();

$stmt->close();


if (!$donor) {

    session_destroy();

    header("Location: login.php");
    exit();

}


// =====================================================
// DONOR DATA
// =====================================================

$blood_group = $donor['blood_group'];

$city = $donor['city'];


// =====================================================
// TOTAL MATCHING PENDING REQUESTS
// =====================================================

$totalRequest = 0;


$requestStmt = $conn->prepare("
    SELECT COUNT(*) AS total
    FROM blood_requests
    WHERE blood_group = ?
    AND city = ?
    AND status = 'Pending'
");


if ($requestStmt) {

    $requestStmt->bind_param(
        "ss",
        $blood_group,
        $city
    );

    $requestStmt->execute();

    $requestResult =
        $requestStmt->get_result()->fetch_assoc();

    $totalRequest =
        $requestResult['total'] ?? 0;

    $requestStmt->close();

}


// =====================================================
// INCLUDE HEADER
// =====================================================

include("../includes/header.php");

include("../includes/navbar.php");

?>


<div class="container py-5">


    <!-- =================================================
         WELCOME CARD
    ================================================= -->

    <div class="card shadow border-0 mb-4">


        <div class="card-body bg-danger text-white p-4">


            <h2>

                Welcome,
                <?= htmlspecialchars($donor['name']); ?>

                ❤️

            </h2>


            <p class="mb-0">

                Thank you for being a blood donor.
                Your contribution can help save lives.

            </p>


        </div>

    </div>



    <!-- =================================================
         DASHBOARD CARDS
    ================================================= -->

    <div class="row g-4">


        <!-- BLOOD GROUP -->

        <div class="col-lg-3 col-md-6">


            <div class="card shadow h-100 text-center">


                <div class="card-body">


                    <div style="font-size:40px;">
                        🩸
                    </div>


                    <h5 class="mt-2">
                        Blood Group
                    </h5>


                    <h2 class="text-danger">

                        <?= htmlspecialchars(
                            $donor['blood_group']
                        ); ?>

                    </h2>


                </div>

            </div>


        </div>



        <!-- CITY -->

        <div class="col-lg-3 col-md-6">


            <div class="card shadow h-100 text-center">


                <div class="card-body">


                    <div style="font-size:40px;">
                        📍
                    </div>


                    <h5 class="mt-2">
                        City
                    </h5>


                    <h4>

                        <?= htmlspecialchars(
                            $donor['city']
                        ); ?>

                    </h4>


                </div>

            </div>


        </div>



        <!-- AVAILABILITY -->

        <div class="col-lg-3 col-md-6">


            <div class="card shadow h-100 text-center">


                <div class="card-body">


                    <div style="font-size:40px;">
                        ❤️
                    </div>


                    <h5 class="mt-2">
                        Availability
                    </h5>


                    <?php

                    if (
                        $donor['availability'] ===
                        "Available"
                    ):

                    ?>


                        <span class="badge bg-success fs-6">

                            ✓ Available

                        </span>


                    <?php else: ?>


                        <span class="badge bg-secondary fs-6">

                            Unavailable

                        </span>


                    <?php endif; ?>


                </div>

            </div>


        </div>



        <!-- REQUESTS -->

        <div class="col-lg-3 col-md-6">


            <div class="card shadow h-100 text-center">


                <div class="card-body">


                    <div style="font-size:40px;">
                        📋
                    </div>


                    <h5 class="mt-2">
                        Matching Requests
                    </h5>


                    <h2 class="text-primary">

                        <?= $totalRequest; ?>

                    </h2>


                </div>

            </div>


        </div>


    </div>



    <!-- =================================================
         PROFILE
    ================================================= -->

    <div class="card shadow mt-5 mb-4">


        <div class="card-header bg-dark text-white">


            <h4 class="mb-0">
                👤 My Profile
            </h4>


        </div>


        <div class="card-body">


            <div class="table-responsive">


                <table class="table table-bordered">


                    <tr>

                        <th width="30%">
                            Name
                        </th>

                        <td>
                            <?= htmlspecialchars(
                                $donor['name']
                            ); ?>
                        </td>

                    </tr>


                    <tr>

                        <th>
                            Email
                        </th>

                        <td>
                            <?= htmlspecialchars(
                                $donor['email']
                            ); ?>
                        </td>

                    </tr>


                    <tr>

                        <th>
                            Phone
                        </th>

                        <td>
                            <?= htmlspecialchars(
                                $donor['phone']
                            ); ?>
                        </td>

                    </tr>


                    <tr>

                        <th>
                            Gender
                        </th>

                        <td>
                            <?= htmlspecialchars(
                                $donor['gender']
                            ); ?>
                        </td>

                    </tr>


                    <tr>

                        <th>
                            Age
                        </th>

                        <td>
                            <?= htmlspecialchars(
                                $donor['age']
                            ); ?>
                        </td>

                    </tr>


                    <tr>

                        <th>
                            Blood Group
                        </th>

                        <td>

                            <span class="badge bg-danger">

                                <?= htmlspecialchars(
                                    $donor['blood_group']
                                ); ?>

                            </span>

                        </td>

                    </tr>


                    <tr>

                        <th>
                            City
                        </th>

                        <td>
                            <?= htmlspecialchars(
                                $donor['city']
                            ); ?>
                        </td>

                    </tr>


                    <tr>

                        <th>
                            Address
                        </th>

                        <td>
                            <?= nl2br(
                                htmlspecialchars(
                                    $donor['address']
                                )
                            ); ?>
                        </td>

                    </tr>


                    <tr>

                        <th>
                            Availability
                        </th>

                        <td>

                            <?php

                            if (
                                $donor['availability'] ===
                                "Available"
                            ):

                            ?>

                                <span class="badge bg-success">

                                    Available

                                </span>

                            <?php else: ?>

                                <span class="badge bg-secondary">

                                    Unavailable

                                </span>

                            <?php endif; ?>

                        </td>

                    </tr>


                </table>


            </div>


        </div>


    </div>



    <!-- =================================================
         QUICK ACTIONS
    ================================================= -->

    <div class="card shadow mb-4">


        <div class="card-header bg-primary text-white">


            <h4 class="mb-0">
                ⚡ Quick Actions
            </h4>


        </div>


        <div class="card-body text-center">


            <a
                href="profile.php"
                class="btn btn-primary m-2"
            >
                ✏️ Edit Profile
            </a>


            <a
                href="change_password.php"
                class="btn btn-warning m-2"
            >
                🔐 Change Password
            </a>


            <a
                href="history.php"
                class="btn btn-success m-2"
            >
                📜 Donation History
            </a>


            <a
                href="requests.php"
                class="btn btn-info text-white m-2"
            >
                🩸 Blood Requests
            </a>


            <a
                href="logout.php"
                class="btn btn-danger m-2"
            >
                🚪 Logout
            </a>


        </div>


    </div>



    <!-- =================================================
         REQUEST ALERT
    ================================================= -->

    <?php if ($totalRequest > 0): ?>


        <div class="alert alert-danger shadow">


            <h5 class="fw-bold">

                🩸 Blood Request Alert

            </h5>


            <p class="mb-2">

                There are

                <strong>
                    <?= $totalRequest; ?>
                </strong>

                pending blood request(s) matching your
                blood group

                <strong>
                    <?= htmlspecialchars($blood_group); ?>
                </strong>

                in

                <strong>
                    <?= htmlspecialchars($city); ?>
                </strong>.

            </p>


            <a
                href="requests.php"
                class="btn btn-danger"
            >

                View Requests →

            </a>


        </div>


    <?php endif; ?>


</div>


<?php

include("../includes/footer.php");

?>