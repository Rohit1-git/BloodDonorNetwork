<?php

session_start();


// =====================================================
// CHECK LOGIN
// =====================================================

if (!isset($_SESSION['recipient_id'])) {

    header("Location: login.php");
    exit();

}


include("../includes/db.php");


// =====================================================
// DATABASE CONNECTION CHECK
// =====================================================

if ($conn->connect_error) {

    die(
        "Database Connection Failed: " .
        htmlspecialchars($conn->connect_error)
    );

}


// =====================================================
// RECIPIENT ID
// =====================================================

$id = $_SESSION['recipient_id'];


// =====================================================
// GET RECIPIENT DETAILS
// =====================================================

$stmt = $conn->prepare("
    SELECT *
    FROM recipients
    WHERE id = ?
");


if (!$stmt) {

    die(
        "Database Error: " .
        htmlspecialchars($conn->error)
    );

}


$stmt->bind_param("i", $id);

$stmt->execute();

$result = $stmt->get_result();


if ($result->num_rows !== 1) {

    $stmt->close();

    session_destroy();

    header("Location: login.php");

    exit();

}


$recipient = $result->fetch_assoc();

$stmt->close();


// =====================================================
// RECIPIENT INFORMATION
// =====================================================

$recipientName = $recipient['name'];
$recipientEmail = $recipient['email'];
$recipientPhone = $recipient['phone'];
$recipientBloodGroup = $recipient['blood_group'];
$recipientCity = $recipient['city'];
$recipientHospital = $recipient['hospital'];
$recipientAddress = $recipient['address'];


// =====================================================
// TOTAL REQUESTS
// =====================================================

$totalRequests = 0;

$stmt = $conn->prepare("
    SELECT COUNT(*) AS total
    FROM blood_requests
    WHERE recipient_id = ?
");


if ($stmt) {

    $stmt->bind_param("i", $id);

    $stmt->execute();

    $data = $stmt->get_result()->fetch_assoc();

    $totalRequests = $data['total'] ?? 0;

    $stmt->close();

}


// =====================================================
// PENDING REQUESTS
// =====================================================

$pendingRequests = 0;

$stmt = $conn->prepare("
    SELECT COUNT(*) AS total
    FROM blood_requests
    WHERE recipient_id = ?
    AND status = 'Pending'
");


if ($stmt) {

    $stmt->bind_param("i", $id);

    $stmt->execute();

    $data = $stmt->get_result()->fetch_assoc();

    $pendingRequests = $data['total'] ?? 0;

    $stmt->close();

}


// =====================================================
// ACCEPTED REQUESTS
// =====================================================

$acceptedRequests = 0;

$stmt = $conn->prepare("
    SELECT COUNT(*) AS total
    FROM blood_requests
    WHERE recipient_id = ?
    AND status = 'Accepted'
");


if ($stmt) {

    $stmt->bind_param("i", $id);

    $stmt->execute();

    $data = $stmt->get_result()->fetch_assoc();

    $acceptedRequests = $data['total'] ?? 0;

    $stmt->close();

}


// =====================================================
// REJECTED REQUESTS
// =====================================================

$rejectedRequests = 0;

$stmt = $conn->prepare("
    SELECT COUNT(*) AS total
    FROM blood_requests
    WHERE recipient_id = ?
    AND status = 'Rejected'
");


if ($stmt) {

    $stmt->bind_param("i", $id);

    $stmt->execute();

    $data = $stmt->get_result()->fetch_assoc();

    $rejectedRequests = $data['total'] ?? 0;

    $stmt->close();

}


// =====================================================
// MATCHING AVAILABLE DONORS
// =====================================================

$matchingDonors = 0;

$stmt = $conn->prepare("
    SELECT COUNT(*) AS total
    FROM donors
    WHERE blood_group = ?
    AND city = ?
    AND availability = 'Available'
");


if ($stmt) {

    $stmt->bind_param(
        "ss",
        $recipientBloodGroup,
        $recipientCity
    );

    $stmt->execute();

    $data = $stmt->get_result()->fetch_assoc();

    $matchingDonors = $data['total'] ?? 0;

    $stmt->close();

}


// =====================================================
// RECENT REQUESTS
// =====================================================

$recentRequests = false;

$stmt = $conn->prepare("
    SELECT *
    FROM blood_requests
    WHERE recipient_id = ?
    ORDER BY id DESC
    LIMIT 5
");


if ($stmt) {

    $stmt->bind_param("i", $id);

    $stmt->execute();

    $recentRequests = $stmt->get_result();

    $stmt->close();

}


// =====================================================
// HEADER
// =====================================================

include("../includes/header.php");

include("../includes/navbar.php");

?>


<div class="container py-5">


    <!-- =================================================
         WELCOME
    ================================================== -->

    <div class="card shadow mb-4 border-0">


        <div class="card-header bg-primary text-white p-4">


            <h2 class="mb-2">

                Welcome,
                <?= htmlspecialchars($recipientName); ?>

                ❤️

            </h2>


            <p class="mb-0">

                Manage your blood requests and
                find matching donors easily.

            </p>


        </div>


    </div>



    <!-- =================================================
         DASHBOARD STATISTICS
    ================================================== -->

    <div class="row g-4 mb-4">


        <!-- BLOOD GROUP -->

        <div class="col-lg-3 col-md-6">


            <div class="card shadow h-100 text-center">


                <div class="card-body">


                    <div style="font-size:40px;">
                        🩸
                    </div>


                    <h6 class="text-muted mt-2">
                        Blood Group
                    </h6>


                    <h2 class="text-danger fw-bold">

                        <?= htmlspecialchars(
                            $recipientBloodGroup
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


                    <h6 class="text-muted mt-2">
                        City
                    </h6>


                    <h4 class="fw-bold">

                        <?= htmlspecialchars(
                            $recipientCity
                        ); ?>

                    </h4>


                </div>


            </div>


        </div>



        <!-- MATCHING DONORS -->

        <div class="col-lg-3 col-md-6">


            <div class="card shadow h-100 text-center">


                <div class="card-body">


                    <div style="font-size:40px;">
                        👥
                    </div>


                    <h6 class="text-muted mt-2">
                        Available Donors
                    </h6>


                    <h2 class="text-success fw-bold">

                        <?= $matchingDonors; ?>

                    </h2>


                </div>


            </div>


        </div>



        <!-- TOTAL REQUESTS -->

        <div class="col-lg-3 col-md-6">


            <div class="card shadow h-100 text-center">


                <div class="card-body">


                    <div style="font-size:40px;">
                        📋
                    </div>


                    <h6 class="text-muted mt-2">
                        Total Requests
                    </h6>


                    <h2 class="text-primary fw-bold">

                        <?= $totalRequests; ?>

                    </h2>


                </div>


            </div>


        </div>


    </div>



    <!-- =================================================
         REQUEST STATUS CARDS
    ================================================== -->

    <div class="row g-4 mb-5">


        <!-- PENDING -->

        <div class="col-md-4">


            <div class="card shadow border-0">


                <div class="card-body text-center">


                    <h6 class="text-muted">
                        Pending Requests
                    </h6>


                    <h2 class="text-warning fw-bold">

                        <?= $pendingRequests; ?>

                    </h2>


                    <span class="badge bg-warning text-dark">

                        Waiting

                    </span>


                </div>


            </div>


        </div>



        <!-- ACCEPTED -->

        <div class="col-md-4">


            <div class="card shadow border-0">


                <div class="card-body text-center">


                    <h6 class="text-muted">
                        Accepted Requests
                    </h6>


                    <h2 class="text-success fw-bold">

                        <?= $acceptedRequests; ?>

                    </h2>


                    <span class="badge bg-success">

                        Accepted

                    </span>


                </div>


            </div>


        </div>



        <!-- REJECTED -->

        <div class="col-md-4">


            <div class="card shadow border-0">


                <div class="card-body text-center">


                    <h6 class="text-muted">
                        Rejected Requests
                    </h6>


                    <h2 class="text-danger fw-bold">

                        <?= $rejectedRequests; ?>

                    </h2>


                    <span class="badge bg-danger">

                        Rejected

                    </span>


                </div>


            </div>


        </div>


    </div>



    <!-- =================================================
         PROFILE + QUICK ACTIONS
    ================================================== -->

    <div class="card shadow mb-4">


        <div class="card-body">


            <div class="row g-4">


                <!-- =====================================
                     RECIPIENT INFORMATION
                ====================================== -->

                <div class="col-lg-6">


                    <h4 class="mb-3">

                        👤 My Profile

                    </h4>


                    <table class="table table-bordered">


                        <tr>

                            <th width="35%">
                                Name
                            </th>

                            <td>

                                <?= htmlspecialchars(
                                    $recipientName
                                ); ?>

                            </td>

                        </tr>


                        <tr>

                            <th>
                                Email
                            </th>

                            <td>

                                <?= htmlspecialchars(
                                    $recipientEmail
                                ); ?>

                            </td>

                        </tr>


                        <tr>

                            <th>
                                Phone
                            </th>

                            <td>

                                <?= htmlspecialchars(
                                    $recipientPhone
                                ); ?>

                            </td>

                        </tr>


                        <tr>

                            <th>
                                Blood Group
                            </th>

                            <td>

                                <span class="badge bg-danger fs-6">

                                    <?= htmlspecialchars(
                                        $recipientBloodGroup
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
                                    $recipientCity
                                ); ?>

                            </td>

                        </tr>


                        <tr>

                            <th>
                                Hospital
                            </th>

                            <td>

                                <?= htmlspecialchars(
                                    $recipientHospital
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
                                        $recipientAddress
                                    )
                                ); ?>

                            </td>

                        </tr>


                    </table>


                </div>



                <!-- =====================================
                     QUICK ACTIONS
                ====================================== -->

                <div class="col-lg-6">


                    <h4 class="mb-3">

                        🚑 Quick Actions

                    </h4>


                    <div class="d-grid gap-3">


                        <button
                            type="button"
                            class="btn btn-danger btn-lg"
                            data-bs-toggle="collapse"
                            data-bs-target="#bloodRequestForm"
                        >

                            🩸 Send Blood Request

                        </button>


                        <a
                            href="request_status.php"
                            class="btn btn-success btn-lg"
                        >

                            📋 Request Status

                        </a>


                        <a
                            href="profile.php"
                            class="btn btn-primary btn-lg"
                        >

                            ✏️ Edit Profile

                        </a>


                        <a
                            href="logout.php"
                            class="btn btn-secondary btn-lg"
                        >

                            🚪 Logout

                        </a>


                    </div>


                </div>


            </div>


        </div>


    </div>



    <!-- =================================================
         BLOOD REQUEST FORM
    ================================================== -->

    <div
        class="collapse"
        id="bloodRequestForm"
    >


        <div class="card shadow mb-4">


            <div class="card-header bg-danger text-white">


                <h4 class="mb-0">

                    🩸 Emergency Blood Request

                </h4>


            </div>


            <div class="card-body">


                <form
                    action="send_request.php"
                    method="POST"
                >


                    <div class="row g-3">


                        <!-- BLOOD GROUP -->

                        <div class="col-md-6">


                            <label class="form-label fw-bold">

                                Blood Group

                            </label>


                            <select
                                name="blood_group"
                                class="form-select"
                                required
                            >


                                <option value="">

                                    Select Blood Group

                                </option>


                                <option
                                    value="A+"
                                    <?= $recipientBloodGroup == "A+"
                                        ? "selected"
                                        : ""; ?>
                                >
                                    A+
                                </option>


                                <option
                                    value="A-"
                                    <?= $recipientBloodGroup == "A-"
                                        ? "selected"
                                        : ""; ?>
                                >
                                    A-
                                </option>


                                <option
                                    value="B+"
                                    <?= $recipientBloodGroup == "B+"
                                        ? "selected"
                                        : ""; ?>
                                >
                                    B+
                                </option>


                                <option
                                    value="B-"
                                    <?= $recipientBloodGroup == "B-"
                                        ? "selected"
                                        : ""; ?>
                                >
                                    B-
                                </option>


                                <option
                                    value="AB+"
                                    <?= $recipientBloodGroup == "AB+"
                                        ? "selected"
                                        : ""; ?>
                                >
                                    AB+
                                </option>


                                <option
                                    value="AB-"
                                    <?= $recipientBloodGroup == "AB-"
                                        ? "selected"
                                        : ""; ?>
                                >
                                    AB-
                                </option>


                                <option
                                    value="O+"
                                    <?= $recipientBloodGroup == "O+"
                                        ? "selected"
                                        : ""; ?>
                                >
                                    O+
                                </option>


                                <option
                                    value="O-"
                                    <?= $recipientBloodGroup == "O-"
                                        ? "selected"
                                        : ""; ?>
                                >
                                    O-
                                </option>


                            </select>


                        </div>



                        <!-- CITY -->

                        <div class="col-md-6">


                            <label class="form-label fw-bold">

                                City

                            </label>


                            <input
                                type="text"
                                name="city"
                                class="form-control"
                                value="<?= htmlspecialchars(
                                    $recipientCity
                                ); ?>"
                                required
                            >


                        </div>



                        <!-- HOSPITAL -->

                        <div class="col-md-6">


                            <label class="form-label fw-bold">

                                Hospital

                            </label>


                            <input
                                type="text"
                                name="hospital"
                                class="form-control"
                                value="<?= htmlspecialchars(
                                    $recipientHospital
                                ); ?>"
                                required
                            >


                        </div>



                        <!-- MESSAGE -->

                        <div class="col-12">


                            <label class="form-label fw-bold">

                                Emergency Message

                            </label>


                            <textarea
                                name="message"
                                class="form-control"
                                rows="4"
                                placeholder="Enter emergency details..."
                            ></textarea>


                        </div>



                        <!-- SUBMIT -->

                        <div class="col-12">


                            <button
                                type="submit"
                                class="btn btn-danger btn-lg"
                            >

                                🩸 Submit Blood Request

                            </button>


                            <button
                                type="button"
                                class="btn btn-secondary btn-lg ms-2"
                                data-bs-toggle="collapse"
                                data-bs-target="#bloodRequestForm"
                            >

                                Cancel

                            </button>


                        </div>


                    </div>


                </form>


            </div>


        </div>


    </div>



    <!-- =================================================
         ALERTS
    ================================================== -->


    <?php if ($pendingRequests > 0): ?>


        <div class="alert alert-warning shadow">


            <h5 class="fw-bold">

                ⏳ Pending Request Alert

            </h5>


            <p class="mb-2">

                You currently have

                <strong>
                    <?= $pendingRequests; ?>
                </strong>

                pending blood request(s).

            </p>


            <a
                href="request_status.php"
                class="btn btn-warning"
            >

                View Request Status →

            </a>


        </div>


    <?php endif; ?>



    <?php if ($matchingDonors > 0): ?>


        <div class="alert alert-success shadow">


            <h5 class="fw-bold">

                🩸 Matching Donors Available

            </h5>


            <p class="mb-2">

                We found

                <strong>
                    <?= $matchingDonors; ?>
                </strong>

                available donor(s) with blood group

                <strong>
                    <?= htmlspecialchars(
                        $recipientBloodGroup
                    ); ?>
                </strong>

                in

                <strong>
                    <?= htmlspecialchars(
                        $recipientCity
                    ); ?>
                </strong>.

            </p>


        </div>


    <?php else: ?>


        <div class="alert alert-light border shadow">


            <h5 class="fw-bold">

                🔎 No Matching Donors

            </h5>


            <p class="mb-0">

                Currently, no available donor matching
                your blood group and city was found.

            </p>


        </div>


    <?php endif; ?>



    <!-- =================================================
         RECENT REQUESTS
    ================================================== -->

    <div class="card shadow mt-4 mb-5">


        <div class="card-header bg-danger text-white">


            <h5 class="mb-0">

                📋 Recent Blood Requests

            </h5>


        </div>


        <div class="card-body">


            <div class="table-responsive">


                <table class="table table-bordered table-hover">


                    <thead class="table-danger">


                        <tr>

                            <th>
                                ID
                            </th>

                            <th>
                                Blood Group
                            </th>

                            <th>
                                City
                            </th>

                            <th>
                                Hospital
                            </th>

                            <th>
                                Status
                            </th>

                        </tr>


                    </thead>


                    <tbody>


                    <?php


                    if (
                        $recentRequests &&
                        $recentRequests->num_rows > 0
                    ) {


                        while (
                            $request =
                            $recentRequests->fetch_assoc()
                        ) {


                            $requestStatus =
                                $request['status'] ?? 'Pending';


                    ?>


                        <tr>


                            <td>

                                <?= htmlspecialchars(
                                    $request['id']
                                ); ?>

                            </td>


                            <td>

                                <span class="badge bg-danger">

                                    <?= htmlspecialchars(
                                        $request['blood_group']
                                    ); ?>

                                </span>

                            </td>


                            <td>

                                <?= htmlspecialchars(
                                    $request['city']
                                ); ?>

                            </td>


                            <td>

                                <?= htmlspecialchars(
                                    $request['hospital']
                                ); ?>

                            </td>


                            <td>


                                <?php

                                if (
                                    $requestStatus ===
                                    "Accepted"
                                ) {

                                ?>


                                    <span class="badge bg-success">

                                        ✓ Accepted

                                    </span>


                                <?php

                                } elseif (
                                    $requestStatus ===
                                    "Rejected"
                                ) {

                                ?>


                                    <span class="badge bg-danger">

                                        ✕ Rejected

                                    </span>


                                <?php

                                } else {

                                ?>


                                    <span class="badge bg-warning text-dark">

                                        ⏳ Pending

                                    </span>


                                <?php

                                }

                                ?>


                            </td>


                        </tr>


                    <?php


                        }


                    } else {


                    ?>


                        <tr>

                            <td
                                colspan="5"
                                class="text-center text-muted py-4"
                            >

                                No Blood Requests Found.

                            </td>

                        </tr>


                    <?php


                    }


                    ?>


                    </tbody>


                </table>


            </div>


        </div>


    </div>


</div>


<?php

include("../includes/footer.php");

?>