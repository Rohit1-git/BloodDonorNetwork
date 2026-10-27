<?php

session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include("../includes/db.php");


// =====================================================
// STATISTICS
// =====================================================

$donors = 0;

$result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM donors"
);

if ($result) {
    $donors = mysqli_fetch_assoc($result)['total'];
}


$recipients = 0;

$result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM recipients"
);

if ($result) {
    $recipients = mysqli_fetch_assoc($result)['total'];
}


$requests = 0;

$check = mysqli_query(
    $conn,
    "SHOW TABLES LIKE 'blood_requests'"
);

if ($check && mysqli_num_rows($check) > 0) {

    $result = mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total FROM blood_requests"
    );

    if ($result) {
        $requests = mysqli_fetch_assoc($result)['total'];
    }
}


$hospitals = 0;

$check = mysqli_query(
    $conn,
    "SHOW TABLES LIKE 'hospitals'"
);

if ($check && mysqli_num_rows($check) > 0) {

    $result = mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total FROM hospitals"
    );

    if ($result) {
        $hospitals = mysqli_fetch_assoc($result)['total'];
    }
}


// =====================================================
// AVAILABLE DONORS
// =====================================================

$available = 0;

$result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM donors
     WHERE availability='Available'"
);

if ($result) {
    $available = mysqli_fetch_assoc($result)['total'];
}


$unavailable = max(
    0,
    $donors - $available
);


// Availability percentage

$availabilityPercent = 0;

if ($donors > 0) {

    $availabilityPercent = round(
        ($available / $donors) * 100
    );
}


// =====================================================
// BLOOD GROUP REPORT
// =====================================================

$bloodReport = mysqli_query(
    $conn,
    "SELECT
        blood_group,
        COUNT(*) AS total
     FROM donors
     GROUP BY blood_group
     ORDER BY
        FIELD(
            blood_group,
            'A+',
            'A-',
            'B+',
            'B-',
            'AB+',
            'AB-',
            'O+',
            'O-'
        )"
);


// =====================================================
// HEADER
// =====================================================

include("../includes/header.php");

?>


<style>

/* =====================================================
   REPORT PAGE
===================================================== */

.report-page {

    min-height: 100vh;

    padding: 35px 0 60px;

    background:
        radial-gradient(
            circle at 5% 10%,
            rgba(220,53,69,0.08),
            transparent 28%
        ),
        radial-gradient(
            circle at 95% 90%,
            rgba(13,110,253,0.08),
            transparent 30%
        ),
        #f5f7fb;
}


/* =====================================================
   PAGE HEADER
===================================================== */

.report-header {

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 20px;

    margin-bottom: 30px;
}


.report-heading {

    display: flex;

    align-items: center;

    gap: 15px;
}


.report-heading-icon {

    width: 55px;

    height: 55px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 16px;

    color: #fff;

    font-size: 22px;

    background:
        linear-gradient(
            135deg,
            #dc3545,
            #9f1d2d
        );

    box-shadow:
        0 8px 20px
        rgba(220,53,69,0.25);
}


.report-heading h2 {

    margin: 0;

    font-weight: 800;

    color: #202124;
}


.report-heading p {

    margin: 3px 0 0;

    color: #858b95;

    font-size: 13px;
}


.report-actions {

    display: flex;

    gap: 10px;
}


/* =====================================================
   SUMMARY CARDS
===================================================== */

.report-stat {

    position: relative;

    min-height: 170px;

    padding: 25px;

    border-radius: 20px;

    color: #fff;

    overflow: hidden;

    box-shadow:
        0 12px 30px
        rgba(0,0,0,0.10);

    transition:
        all .25s ease;
}


.report-stat:hover {

    transform:
        translateY(-5px);

    box-shadow:
        0 18px 38px
        rgba(0,0,0,0.14);
}


.report-stat::before {

    content: "";

    position: absolute;

    width: 150px;

    height: 150px;

    border-radius: 50%;

    background:
        rgba(255,255,255,0.10);

    right: -55px;

    top: -55px;
}


.report-stat::after {

    content: "";

    position: absolute;

    width: 90px;

    height: 90px;

    border-radius: 50%;

    background:
        rgba(255,255,255,0.07);

    right: 35px;

    bottom: -55px;
}


.report-stat-content {

    position: relative;

    z-index: 2;
}


.report-stat-icon {

    width: 48px;

    height: 48px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 14px;

    background:
        rgba(255,255,255,0.16);

    margin-bottom: 15px;

    font-size: 19px;
}


.report-stat h1 {

    margin: 0;

    font-size: 34px;

    font-weight: 800;
}


.report-stat p {

    margin: 3px 0 0;

    opacity: .85;

    font-size: 13px;
}


.stat-red {

    background:
        linear-gradient(
            135deg,
            #dc3545,
            #a71d2a
        );
}


.stat-green {

    background:
        linear-gradient(
            135deg,
            #198754,
            #096b40
        );
}


.stat-blue {

    background:
        linear-gradient(
            135deg,
            #0d6efd,
            #084298
        );
}


.stat-orange {

    background:
        linear-gradient(
            135deg,
            #f59f00,
            #d97706
        );
}


/* =====================================================
   MAIN REPORT CARDS
===================================================== */

.report-card {

    border: none;

    border-radius: 20px;

    background: #fff;

    overflow: hidden;

    box-shadow:
        0 10px 35px
        rgba(0,0,0,0.07);
}


.report-card-header {

    display: flex;

    align-items: center;

    justify-content: space-between;

    padding: 20px 23px;

    border-bottom:
        1px solid #f0f1f3;
}


.report-card-title {

    display: flex;

    align-items: center;

    gap: 11px;
}


.report-card-title-icon {

    width: 40px;

    height: 40px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 11px;

    background: #fff0f1;

    color: #dc3545;
}


.report-card-title h5 {

    margin: 0;

    font-weight: 750;

    color: #292d32;
}


.report-card-title small {

    color: #9298a1;

    font-size: 11px;
}


/* =====================================================
   AVAILABILITY CARD
===================================================== */

.availability-card {

    padding: 25px;
}


.availability-top {

    display: flex;

    align-items: center;

    justify-content: space-between;

    margin-bottom: 20px;
}


.availability-number {

    font-size: 38px;

    font-weight: 800;

    color: #198754;
}


.availability-label {

    color: #7d838c;

    font-size: 13px;
}


.availability-circle {

    width: 85px;

    height: 85px;

    border-radius: 50%;

    display: flex;

    align-items: center;

    justify-content: center;

    background:
        conic-gradient(
            #198754
            <?= $availabilityPercent; ?>%,
            #e9f5ef
            <?= $availabilityPercent; ?>%
        );
}


.availability-circle-inner {

    width: 67px;

    height: 67px;

    border-radius: 50%;

    display: flex;

    align-items: center;

    justify-content: center;

    background: #fff;

    color: #198754;

    font-weight: 800;

    font-size: 15px;
}


.availability-progress {

    height: 10px;

    background: #edf1ef;

    border-radius: 20px;

    overflow: hidden;
}


.availability-progress-bar {

    height: 100%;

    width: <?= $availabilityPercent; ?>%;

    background:
        linear-gradient(
            90deg,
            #198754,
            #45c783
        );

    border-radius: 20px;
}


.availability-footer {

    display: flex;

    justify-content: space-between;

    margin-top: 12px;

    color: #858b93;

    font-size: 12px;
}


.availability-footer strong {

    color: #333;
}


/* =====================================================
   BLOOD GROUP TABLE
===================================================== */

.blood-table {

    margin: 0;
}


.blood-table thead th {

    border: none;

    background: #fff7f8;

    color: #777d85;

    font-size: 12px;

    font-weight: 700;

    padding: 14px 20px;
}


.blood-table tbody td {

    padding: 16px 20px;

    vertical-align: middle;

    border-color: #f0f1f3;

    font-size: 13px;
}


.blood-group-badge {

    min-width: 48px;

    display: inline-flex;

    justify-content: center;

    padding: 7px 10px;

    border-radius: 9px;

    background: #fff0f1;

    color: #dc3545;

    font-weight: 800;

    border:
        1px solid #ffd5da;
}


.blood-count {

    font-weight: 700;

    color: #343a40;
}


.blood-progress {

    min-width: 130px;

    height: 7px;

    background: #f0f1f3;

    border-radius: 20px;

    overflow: hidden;
}


.blood-progress-bar {

    height: 100%;

    background:
        linear-gradient(
            90deg,
            #dc3545,
            #f06b78
        );

    border-radius: 20px;
}


.percentage {

    font-weight: 700;

    color: #dc3545;
}


/* =====================================================
   QUICK INSIGHT
===================================================== */

.insight-box {

    padding: 22px;

    border-radius: 17px;

    background:
        linear-gradient(
            135deg,
            #fff5f6,
            #fff
        );

    border:
        1px solid #ffd9dd;
}


.insight-icon {

    width: 45px;

    height: 45px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 13px;

    background: #dc3545;

    color: #fff;

    margin-bottom: 14px;
}


.insight-box h6 {

    font-weight: 800;

    color: #333;
}


.insight-box p {

    margin: 0;

    color: #777;

    font-size: 13px;

    line-height: 1.7;
}


/* =====================================================
   EMPTY STATE
===================================================== */

.empty-report {

    padding: 45px 20px;

    text-align: center;

    color: #999;
}


.empty-report i {

    font-size: 35px;

    color: #dc3545;

    margin-bottom: 12px;
}


/* =====================================================
   RESPONSIVE
===================================================== */

@media(max-width: 768px) {

    .report-header {

        align-items: flex-start;

        flex-direction: column;
    }

    .report-actions {

        width: 100%;
    }

    .report-actions .btn {

        flex: 1;
    }

    .availability-top {

        align-items: flex-start;
    }

}


@media(max-width: 576px) {

    .report-page {

        padding: 25px 10px 45px;
    }

    .report-heading h2 {

        font-size: 22px;
    }

    .report-stat {

        min-height: 145px;

        padding: 20px;
    }

    .report-stat h1 {

        font-size: 29px;
    }

    .blood-progress {

        min-width: 80px;
    }

}

</style>


<div class="admin-layout">

    <?php include("navbar.php"); ?>


    <div class="admin-content">

        <div class="report-page">

            <div class="container-fluid">


                <!-- =================================================
                     HEADER
                ================================================== -->

                <div class="report-header">

                    <div class="report-heading">

                        <div class="report-heading-icon">

                            <i class="fa-solid fa-chart-line"></i>

                        </div>

                        <div>

                            <h2>
                                Reports & Analytics
                            </h2>

                            <p>
                                Blood donation system overview
                            </p>

                        </div>

                    </div>


                    <div class="report-actions">

                        <a
                            href="dashboard.php"
                            class="btn btn-dark"
                        >

                            <i class="fa-solid fa-arrow-left me-1"></i>

                            Dashboard

                        </a>


                        <a
                            href="logout.php"
                            class="btn btn-danger"
                        >

                            <i class="fa-solid fa-right-from-bracket me-1"></i>

                            Logout

                        </a>

                    </div>

                </div>



                <!-- =================================================
                     SUMMARY STATISTICS
                ================================================== -->

                <div class="row g-4">


                    <!-- DONORS -->

                    <div class="col-xl-3 col-md-6">

                        <div class="report-stat stat-red">

                            <div class="report-stat-content">

                                <div class="report-stat-icon">

                                    <i class="fa-solid fa-user-group"></i>

                                </div>

                                <h1>
                                    <?= $donors; ?>
                                </h1>

                                <p>
                                    Total Registered Donors
                                </p>

                            </div>

                        </div>

                    </div>


                    <!-- RECIPIENTS -->

                    <div class="col-xl-3 col-md-6">

                        <div class="report-stat stat-green">

                            <div class="report-stat-content">

                                <div class="report-stat-icon">

                                    <i class="fa-solid fa-users"></i>

                                </div>

                                <h1>
                                    <?= $recipients; ?>
                                </h1>

                                <p>
                                    Registered Recipients
                                </p>

                            </div>

                        </div>

                    </div>


                    <!-- REQUESTS -->

                    <div class="col-xl-3 col-md-6">

                        <div class="report-stat stat-blue">

                            <div class="report-stat-content">

                                <div class="report-stat-icon">

                                    <i class="fa-solid fa-droplet"></i>

                                </div>

                                <h1>
                                    <?= $requests; ?>
                                </h1>

                                <p>
                                    Blood Requests
                                </p>

                            </div>

                        </div>

                    </div>


                    <!-- HOSPITALS -->

                    <div class="col-xl-3 col-md-6">

                        <div class="report-stat stat-orange">

                            <div class="report-stat-content">

                                <div class="report-stat-icon">

                                    <i class="fa-solid fa-hospital"></i>

                                </div>

                                <h1>
                                    <?= $hospitals; ?>
                                </h1>

                                <p>
                                    Registered Hospitals
                                </p>

                            </div>

                        </div>

                    </div>


                </div>



                <!-- =================================================
                     SECOND ROW
                ================================================== -->

                <div class="row g-4 mt-1">


                    <!-- AVAILABILITY -->

                    <div class="col-lg-5">

                        <div class="report-card h-100">


                            <div class="report-card-header">

                                <div class="report-card-title">

                                    <div class="report-card-title-icon">

                                        <i class="fa-solid fa-heart-pulse"></i>

                                    </div>

                                    <div>

                                        <h5>
                                            Donor Availability
                                        </h5>

                                        <small>
                                            Current donor status
                                        </small>

                                    </div>

                                </div>

                                <i
                                    class="fa-solid fa-circle-check text-success"
                                ></i>

                            </div>


                            <div class="availability-card">


                                <div class="availability-top">

                                    <div>

                                        <div class="availability-number">

                                            <?= $available; ?>

                                        </div>

                                        <div class="availability-label">

                                            Currently Available Donors

                                        </div>

                                    </div>


                                    <div class="availability-circle">

                                        <div class="availability-circle-inner">

                                            <?= $availabilityPercent; ?>%

                                        </div>

                                    </div>

                                </div>


                                <div class="availability-progress">

                                    <div
                                        class="availability-progress-bar"
                                    ></div>

                                </div>


                                <div class="availability-footer">

                                    <span>

                                        Available

                                        <strong>
                                            <?= $available; ?>
                                        </strong>

                                    </span>


                                    <span>

                                        Unavailable

                                        <strong>
                                            <?= $unavailable; ?>
                                        </strong>

                                    </span>

                                </div>


                                <div class="insight-box mt-4">

                                    <div class="insight-icon">

                                        <i class="fa-solid fa-lightbulb"></i>

                                    </div>

                                    <h6>

                                        Donor Availability Insight

                                    </h6>

                                    <p>

                                        <?= $availabilityPercent; ?>%
                                        of registered donors are currently
                                        marked as available for blood
                                        donation.

                                    </p>

                                </div>


                            </div>

                        </div>

                    </div>



                    <!-- BLOOD GROUP SUMMARY -->

                    <div class="col-lg-7">

                        <div class="report-card h-100">


                            <div class="report-card-header">

                                <div class="report-card-title">

                                    <div class="report-card-title-icon">

                                        <i class="fa-solid fa-droplet"></i>

                                    </div>

                                    <div>

                                        <h5>
                                            Blood Group Distribution
                                        </h5>

                                        <small>
                                            Registered donor blood groups
                                        </small>

                                    </div>

                                </div>

                                <span class="badge bg-danger">

                                    <?= $donors; ?>
                                    Donors

                                </span>

                            </div>


                            <div class="table-responsive">

                                <table class="table blood-table">

                                    <thead>

                                        <tr>

                                            <th>
                                                Blood Group
                                            </th>

                                            <th>
                                                Donors
                                            </th>

                                            <th>
                                                Distribution
                                            </th>

                                            <th>
                                                Percentage
                                            </th>

                                        </tr>

                                    </thead>


                                    <tbody>


                                    <?php

                                    if (
                                        $bloodReport &&
                                        mysqli_num_rows($bloodReport) > 0
                                    ) {

                                        while (
                                            $row =
                                            mysqli_fetch_assoc(
                                                $bloodReport
                                            )
                                        ) {

                                            $percent = 0;

                                            if ($donors > 0) {

                                                $percent = round(
                                                    (
                                                        $row['total']
                                                        /
                                                        $donors
                                                    ) * 100
                                                );

                                            }

                                    ?>


                                        <tr>


                                            <td>

                                                <span
                                                    class="blood-group-badge"
                                                >

                                                    <?= htmlspecialchars(
                                                        $row['blood_group']
                                                    ); ?>

                                                </span>

                                            </td>


                                            <td>

                                                <span
                                                    class="blood-count"
                                                >

                                                    <?= $row['total']; ?>

                                                </span>

                                                donors

                                            </td>


                                            <td>

                                                <div
                                                    class="blood-progress"
                                                >

                                                    <div
                                                        class="blood-progress-bar"
                                                        style="width: <?= $percent; ?>%;"
                                                    ></div>

                                                </div>

                                            </td>


                                            <td>

                                                <span
                                                    class="percentage"
                                                >

                                                    <?= $percent; ?>%

                                                </span>

                                            </td>


                                        </tr>


                                    <?php

                                        }

                                    } else {

                                    ?>


                                        <tr>

                                            <td
                                                colspan="4"
                                                class="border-0"
                                            >

                                                <div
                                                    class="empty-report"
                                                >

                                                    <i
                                                        class="fa-solid fa-droplet"
                                                    ></i>

                                                    <div>

                                                        No Blood Data Found

                                                    </div>

                                                </div>

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



                <!-- =================================================
                     REPORT FOOTER
                ================================================== -->

                <div class="row g-4 mt-1">


                    <div class="col-md-4">

                        <div class="report-card p-4 text-center">

                            <div
                                class="report-card-title-icon mx-auto mb-3"
                            >

                                <i class="fa-solid fa-user-group"></i>

                            </div>

                            <h4 class="fw-bold mb-1">

                                <?= $donors; ?>

                            </h4>

                            <p class="text-muted mb-0 small">

                                Registered Donors

                            </p>

                        </div>

                    </div>


                    <div class="col-md-4">

                        <div class="report-card p-4 text-center">

                            <div
                                class="report-card-title-icon mx-auto mb-3"
                                style="
                                    background:#eef6ff;
                                    color:#0d6efd;
                                "
                            >

                                <i class="fa-solid fa-droplet"></i>

                            </div>

                            <h4 class="fw-bold mb-1">

                                <?= $requests; ?>

                            </h4>

                            <p class="text-muted mb-0 small">

                                Total Blood Requests

                            </p>

                        </div>

                    </div>


                    <div class="col-md-4">

                        <div class="report-card p-4 text-center">

                            <div
                                class="report-card-title-icon mx-auto mb-3"
                                style="
                                    background:#eefaf3;
                                    color:#198754;
                                "
                            >

                                <i class="fa-solid fa-hospital"></i>

                            </div>

                            <h4 class="fw-bold mb-1">

                                <?= $hospitals; ?>

                            </h4>

                            <p class="text-muted mb-0 small">

                                Connected Hospitals

                            </p>

                        </div>

                    </div>


                </div>


            </div>

        </div>

    </div>

</div>


<?php include("../includes/footer.php"); ?>