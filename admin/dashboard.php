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

// Donors
$donors = 0;

$result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM donors"
);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $donors = $row['total'] ?? 0;
}


// Recipients
$recipients = 0;

$result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM recipients"
);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $recipients = $row['total'] ?? 0;
}


// Blood Requests
$requests = 0;

$table = mysqli_query(
    $conn,
    "SHOW TABLES LIKE 'blood_requests'"
);

if ($table && mysqli_num_rows($table) > 0) {

    $result = mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total FROM blood_requests"
    );

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $requests = $row['total'] ?? 0;
    }
}


// Hospitals
$hospitals = 0;

$table = mysqli_query(
    $conn,
    "SHOW TABLES LIKE 'hospitals'"
);

if ($table && mysqli_num_rows($table) > 0) {

    $result = mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total FROM hospitals"
    );

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $hospitals = $row['total'] ?? 0;
    }
}


// Contact Messages
$contact_messages = 0;

$contact_table = mysqli_query(
    $conn,
    "SHOW TABLES LIKE 'contact_messages'"
);

if (
    $contact_table &&
    mysqli_num_rows($contact_table) > 0
) {

    $result = mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM contact_messages"
    );

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $contact_messages = $row['total'] ?? 0;
    }
}


// Unread Messages
$unread_messages = 0;

if (
    $contact_table &&
    mysqli_num_rows($contact_table) > 0
) {

    $result = mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM contact_messages
         WHERE status='Unread'"
    );

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $unread_messages = $row['total'] ?? 0;
    }
}


// =====================================================
// LATEST BLOOD REQUESTS
// =====================================================

$latest = false;

$table = mysqli_query(
    $conn,
    "SHOW TABLES LIKE 'blood_requests'"
);

if (
    $table &&
    mysqli_num_rows($table) > 0
) {

    $latest = mysqli_query(
        $conn,
        "SELECT *
         FROM blood_requests
         ORDER BY id DESC
         LIMIT 5"
    );
}


// =====================================================
// HEADER
// =====================================================

include("../includes/header.php");

?>

<style>

/* =====================================================
   ADMIN DASHBOARD
===================================================== */

:root {

    --admin-red: #dc3545;
    --admin-dark-red: #9f1729;
    --admin-light-red: #fff1f3;

    --admin-dark: #15171a;

    --text-dark: #25282c;
    --text-muted: #858b92;

    --border: #edf0f3;
}


/* =====================================================
   PAGE
===================================================== */

.admin-dashboard {

    min-height: 100vh;

    padding: 30px 0 60px;

    background:

        radial-gradient(
            circle at 10% 10%,
            rgba(220,53,69,.07),
            transparent 25%
        ),

        radial-gradient(
            circle at 90% 90%,
            rgba(220,53,69,.05),
            transparent 25%
        ),

        #f7f8fa;
}


/* =====================================================
   WELCOME HEADER
===================================================== */

.dashboard-welcome {

    position: relative;

    overflow: hidden;

    padding: 28px 30px;

    margin-bottom: 25px;

    border-radius: 22px;

    color: white;

    background:

        radial-gradient(
            circle at 90% 10%,
            rgba(255,255,255,.14),
            transparent 25%
        ),

        linear-gradient(
            135deg,
            #dc3545,
            #a91427,
            #74101f
        );

    box-shadow:
        0 15px 35px
        rgba(220,53,69,.18);
}


.dashboard-welcome::before {

    content: "";

    position: absolute;

    width: 250px;
    height: 250px;

    border-radius: 50%;

    top: -150px;
    right: -70px;

    background:
        rgba(255,255,255,.07);
}


.dashboard-welcome::after {

    content: "";

    position: absolute;

    width: 150px;
    height: 150px;

    border-radius: 50%;

    bottom: -90px;
    left: 35%;

    background:
        rgba(255,255,255,.05);
}


.dashboard-welcome-content {

    position: relative;

    z-index: 2;
}


.welcome-small {

    display: block;

    margin-bottom: 5px;

    color:
        rgba(255,255,255,.75);

    font-size: 12px;

    font-weight: 600;

    text-transform: uppercase;

    letter-spacing: 1px;
}


.dashboard-welcome h2 {

    margin: 0;

    font-size: 27px;

    font-weight: 800;
}


.dashboard-welcome p {

    margin: 7px 0 0;

    color:
        rgba(255,255,255,.78);

    font-size: 13px;
}


.dashboard-actions {

    position: relative;

    z-index: 3;

    display: flex;

    gap: 10px;
}


.dashboard-actions .btn {

    border-radius: 11px;

    padding: 10px 15px;

    font-size: 13px;

    font-weight: 600;
}


.dashboard-actions .logout-btn {

    color: #fff;

    border:
        1px solid
        rgba(255,255,255,.25);

    background:
        rgba(255,255,255,.12);
}


.dashboard-actions .logout-btn:hover {

    color: var(--admin-red);

    background: #fff;
}


/* =====================================================
   STATISTICS
===================================================== */

.stats-grid {

    margin-bottom: 25px;
}


.stat-card {

    position: relative;

    overflow: hidden;

    min-height: 155px;

    padding: 23px;

    border-radius: 20px;

    border: 1px solid
        rgba(255,255,255,.8);

    background: #fff;

    box-shadow:
        0 8px 25px
        rgba(0,0,0,.055);

    transition:
        transform .25s ease,
        box-shadow .25s ease;
}


.stat-card:hover {

    transform:
        translateY(-5px);

    box-shadow:
        0 15px 35px
        rgba(0,0,0,.09);
}


.stat-card::after {

    content: "";

    position: absolute;

    width: 100px;
    height: 100px;

    border-radius: 50%;

    right: -40px;
    bottom: -45px;

    background:
        rgba(255,255,255,.16);
}


.stat-icon {

    width: 48px;
    height: 48px;

    display: flex;

    align-items: center;
    justify-content: center;

    margin-bottom: 15px;

    border-radius: 14px;

    color: #fff;

    font-size: 20px;
}


.stat-number {

    margin: 0;

    color: #fff;

    font-size: 31px;

    font-weight: 800;

    line-height: 1;
}


.stat-title {

    margin-top: 8px;

    margin-bottom: 0;

    color:
        rgba(255,255,255,.82);

    font-size: 13px;

    font-weight: 600;
}


/* STAT COLORS */

.stat-donor {

    background:
        linear-gradient(
            135deg,
            #e63950,
            #b71931
        );
}


.stat-recipient {

    background:
        linear-gradient(
            135deg,
            #20a464,
            #087443
        );
}


.stat-request {

    background:
        linear-gradient(
            135deg,
            #287df0,
            #1255b8
        );
}


.stat-hospital {

    color: #fff;

    background:
        linear-gradient(
            135deg,
            #f0ad19,
            #d58900
        );
}


/* =====================================================
   MESSAGE CARDS
===================================================== */

.message-card {

    display: flex;

    align-items: center;

    justify-content: space-between;

    padding: 20px 22px;

    border-radius: 18px;

    border: 1px solid var(--border);

    background: #fff;

    box-shadow:
        0 7px 22px
        rgba(0,0,0,.045);

    transition: .25s ease;
}


.message-card:hover {

    transform:
        translateY(-3px);

    box-shadow:
        0 12px 28px
        rgba(0,0,0,.08);
}


.message-label {

    margin-bottom: 4px;

    color: var(--text-muted);

    font-size: 12px;

    font-weight: 600;

    text-transform: uppercase;

    letter-spacing: .5px;
}


.message-number {

    margin: 0;

    color: var(--text-dark);

    font-size: 27px;

    font-weight: 800;
}


.message-icon {

    width: 50px;
    height: 50px;

    display: flex;

    align-items: center;
    justify-content: center;

    border-radius: 15px;

    font-size: 21px;
}


.message-icon.red {

    color: var(--admin-red);

    background: #fff0f2;
}


.message-icon.orange {

    color: #ed8a00;

    background: #fff5df;
}


/* =====================================================
   SECTION TITLE
===================================================== */

.section-heading {

    display: flex;

    align-items: center;

    justify-content: space-between;

    margin: 32px 0 16px;
}


.section-heading h4 {

    display: flex;

    align-items: center;

    gap: 9px;

    margin: 0;

    color: var(--text-dark);

    font-size: 18px;

    font-weight: 800;
}


.section-heading h4 i {

    color: var(--admin-red);
}


.section-heading span {

    color: var(--text-muted);

    font-size: 12px;
}


/* =====================================================
   QUICK ACTIONS
===================================================== */

.quick-actions {

    padding: 22px;

    border-radius: 20px;

    background: #fff;

    border: 1px solid var(--border);

    box-shadow:
        0 8px 25px
        rgba(0,0,0,.045);
}


.quick-box {

    position: relative;

    display: flex;

    align-items: center;

    gap: 14px;

    min-height: 82px;

    padding: 15px;

    border-radius: 15px;

    color: var(--text-dark);

    text-decoration: none;

    border:
        1px solid #edf0f3;

    background: #fafbfc;

    transition:
        all .25s ease;
}


.quick-box:hover {

    color: var(--admin-red);

    border-color:
        rgba(220,53,69,.22);

    background:
        #fff6f7;

    transform:
        translateY(-3px);

    box-shadow:
        0 8px 20px
        rgba(220,53,69,.08);
}


.quick-icon {

    width: 45px;
    height: 45px;

    flex-shrink: 0;

    display: flex;

    align-items: center;
    justify-content: center;

    border-radius: 13px;

    color: var(--admin-red);

    background:
        #fff0f2;

    font-size: 18px;

    transition: .25s ease;
}


.quick-box:hover .quick-icon {

    color: #fff;

    background:
        var(--admin-red);

    transform:
        scale(1.05);
}


.quick-text strong {

    display: block;

    font-size: 13px;

    font-weight: 750;
}


.quick-text small {

    display: block;

    margin-top: 3px;

    color: var(--text-muted);

    font-size: 10px;
}


.quick-arrow {

    margin-left: auto;

    color: #b6bbc1;

    font-size: 12px;
}


/* =====================================================
   REQUEST TABLE
===================================================== */

.request-card {

    overflow: hidden;

    border: 1px solid var(--border);

    border-radius: 20px;

    background: #fff;

    box-shadow:
        0 8px 25px
        rgba(0,0,0,.045);
}


.request-header {

    display: flex;

    align-items: center;

    justify-content: space-between;

    padding:
        18px 22px;

    border-bottom:
        1px solid var(--border);
}


.request-header h5 {

    display: flex;

    align-items: center;

    gap: 9px;

    margin: 0;

    color: var(--text-dark);

    font-size: 16px;

    font-weight: 800;
}


.request-header h5 i {

    color: var(--admin-red);
}


.request-header span {

    color: var(--text-muted);

    font-size: 11px;
}


.request-table {

    margin: 0;
}


.request-table thead th {

    padding:
        14px 18px;

    border: none;

    color: #777e86;

    background: #fafbfc;

    font-size: 11px;

    font-weight: 700;

    text-transform: uppercase;

    letter-spacing: .5px;
}


.request-table tbody td {

    padding:
        15px 18px;

    vertical-align: middle;

    border-color: #f0f1f3;

    color: #444;

    font-size: 13px;
}


.request-table tbody tr {

    transition: .2s ease;
}


.request-table tbody tr:hover {

    background: #fff8f9;
}


/* =====================================================
   BLOOD GROUP
===================================================== */

.blood-badge {

    display: inline-flex;

    align-items: center;

    justify-content: center;

    min-width: 42px;

    height: 30px;

    padding:
        0 9px;

    border-radius: 9px;

    color: #fff;

    background:
        var(--admin-red);

    font-size: 11px;

    font-weight: 800;

    box-shadow:
        0 4px 10px
        rgba(220,53,69,.18);
}


/* =====================================================
   STATUS
   ONLY ACCEPTED + REJECTED
===================================================== */

.status-badge {

    display: inline-flex;

    align-items: center;

    gap: 7px;

    padding: 7px 13px;

    border-radius: 30px;

    font-size: 10px;

    font-weight: 700;

    white-space: nowrap;

    border: 1px solid transparent;
}


.status-dot {

    width: 7px;
    height: 7px;

    flex-shrink: 0;

    border-radius: 50%;
}


/* ACCEPTED */

.status-badge.accepted {

    color: #087443;

    background: #dff7ea;

    border-color: #b8ebd0;

}


.status-badge.accepted .status-dot {

    background: #20a464;

    box-shadow:
        0 0 0 3px
        rgba(32,164,100,.15);
}


/* REJECTED */

.status-badge.rejected {

    color: #9f1729;

    background: #ffe5e9;

    border-color: #ffc5cc;

}


.status-badge.rejected .status-dot {

    background: #dc3545;

    box-shadow:
        0 0 0 3px
        rgba(220,53,69,.15);
}


/* OTHER STATUS */

.status-badge.other {

    color: #687078;

    background: #f2f4f6;

    border-color: #e1e4e7;

}


.status-badge.other .status-dot {

    background: #999;
}


/* =====================================================
   EMPTY STATE
===================================================== */

.empty-state {

    padding:
        50px 20px;

    text-align: center;

    color: #999;
}


.empty-state-icon {

    width: 65px;
    height: 65px;

    margin:
        0 auto 12px;

    display: flex;

    align-items: center;
    justify-content: center;

    border-radius: 18px;

    color: #b7bcc1;

    background: #f4f5f6;

    font-size: 25px;
}


/* =====================================================
   RESPONSIVE
===================================================== */

@media(max-width: 991px) {

    .dashboard-welcome {

        padding: 24px;
    }

}


@media(max-width: 767px) {

    .dashboard-welcome {

        padding: 22px;
    }


    .dashboard-welcome h2 {

        font-size: 23px;
    }


    .dashboard-actions {

        margin-top: 18px;
    }


    .stat-card {

        min-height: 140px;
    }


    .quick-actions {

        padding: 15px;
    }


    .request-table {

        min-width: 650px;
    }

}


@media(max-width: 576px) {

    .admin-dashboard {

        padding-top: 20px;
    }


    .dashboard-welcome {

        border-radius: 17px;
    }


    .dashboard-actions {

        width: 100%;
    }


    .dashboard-actions .btn {

        flex: 1;
    }


    .section-heading {

        margin-top: 25px;
    }

}

</style>


<div class="admin-dashboard">

    <?php include("navbar.php"); ?>


    <div class="container-fluid">


        <!-- =====================================================
             WELCOME HEADER
        ====================================================== -->

        <div class="dashboard-welcome">

            <div class="row align-items-center">

                <div class="col-lg-8">

                    <div class="dashboard-welcome-content">

                        <span class="welcome-small">

                            <i class="fa-solid fa-chart-line me-1"></i>

                            Administration Dashboard

                        </span>


                        <h2>

                            Welcome back,

                            <?= htmlspecialchars(
                                $_SESSION['admin_name'] ?? 'Admin'
                            ); ?>

                            👋

                        </h2>


                        <p>

                            Monitor your blood-bank system,
                            manage users and respond to requests.

                        </p>

                    </div>

                </div>


                <div class="col-lg-4">

                    <div class="dashboard-actions justify-content-lg-end">

                        <a
                            href="contact_messages.php"
                            class="btn btn-light"
                        >

                            <i class="fa-solid fa-envelope me-1"></i>

                            Messages

                            <?php if ($unread_messages > 0): ?>

                                <span class="badge bg-danger ms-1">

                                    <?= $unread_messages; ?>

                                </span>

                            <?php endif; ?>

                        </a>


                        <a
                            href="logout.php"
                            class="btn logout-btn"
                        >

                            <i class="fa-solid fa-right-from-bracket me-1"></i>

                            Logout

                        </a>

                    </div>

                </div>

            </div>

        </div>


        <!-- =====================================================
             STATISTICS
        ====================================================== -->

        <div class="row g-4 stats-grid">


            <!-- DONORS -->

            <div class="col-xl-3 col-md-6">

                <div class="stat-card stat-donor">

                    <div class="stat-icon">

                        <i class="fa-solid fa-user-group"></i>

                    </div>

                    <h2 class="stat-number">

                        <?= $donors; ?>

                    </h2>

                    <p class="stat-title">

                        Total Blood Donors

                    </p>

                </div>

            </div>


            <!-- RECIPIENTS -->

            <div class="col-xl-3 col-md-6">

                <div class="stat-card stat-recipient">

                    <div class="stat-icon">

                        <i class="fa-solid fa-users"></i>

                    </div>

                    <h2 class="stat-number">

                        <?= $recipients; ?>

                    </h2>

                    <p class="stat-title">

                        Registered Recipients

                    </p>

                </div>

            </div>


            <!-- REQUESTS -->

            <div class="col-xl-3 col-md-6">

                <div class="stat-card stat-request">

                    <div class="stat-icon">

                        <i class="fa-solid fa-droplet"></i>

                    </div>

                    <h2 class="stat-number">

                        <?= $requests; ?>

                    </h2>

                    <p class="stat-title">

                        Blood Requests

                    </p>

                </div>

            </div>


            <!-- HOSPITALS -->

            <div class="col-xl-3 col-md-6">

                <div class="stat-card stat-hospital">

                    <div class="stat-icon">

                        <i class="fa-solid fa-hospital"></i>

                    </div>

                    <h2 class="stat-number">

                        <?= $hospitals; ?>

                    </h2>

                    <p class="stat-title">

                        Registered Hospitals

                    </p>

                </div>

            </div>

        </div>


        <!-- =====================================================
             MESSAGE STATISTICS
        ====================================================== -->

        <div class="row g-4">

            <div class="col-md-6">

                <div class="message-card">

                    <div>

                        <div class="message-label">

                            Contact Messages

                        </div>

                        <h3 class="message-number">

                            <?= $contact_messages; ?>

                        </h3>

                    </div>

                    <div class="message-icon red">

                        <i class="fa-solid fa-envelope"></i>

                    </div>

                </div>

            </div>


            <div class="col-md-6">

                <div class="message-card">

                    <div>

                        <div class="message-label">

                            Unread Messages

                        </div>

                        <h3 class="message-number">

                            <?= $unread_messages; ?>

                        </h3>

                    </div>

                    <div class="message-icon orange">

                        <i class="fa-solid fa-bell"></i>

                    </div>

                </div>

            </div>

        </div>


        <!-- =====================================================
             QUICK MANAGEMENT
        ====================================================== -->

        <div class="section-heading">

            <h4>

                <i class="fa-solid fa-layer-group"></i>

                Quick Management

            </h4>

            <span>

                Manage your blood-bank system

            </span>

        </div>


        <div class="quick-actions">

            <div class="row g-3">


                <!-- DONORS -->

                <div class="col-xl-3 col-md-6">

                    <a
                        href="donors.php"
                        class="quick-box"
                    >

                        <div class="quick-icon">

                            <i class="fa-solid fa-user-group"></i>

                        </div>

                        <div class="quick-text">

                            <strong>
                                Manage Donors
                            </strong>

                            <small>
                                View and manage donors
                            </small>

                        </div>

                        <i class="fa-solid fa-chevron-right quick-arrow"></i>

                    </a>

                </div>


                <!-- RECIPIENTS -->

                <div class="col-xl-3 col-md-6">

                    <a
                        href="recipients.php"
                        class="quick-box"
                    >

                        <div class="quick-icon">

                            <i class="fa-solid fa-users"></i>

                        </div>

                        <div class="quick-text">

                            <strong>
                                Recipients
                            </strong>

                            <small>
                                Manage recipient accounts
                            </small>

                        </div>

                        <i class="fa-solid fa-chevron-right quick-arrow"></i>

                    </a>

                </div>


                <!-- REQUESTS -->

                <div class="col-xl-3 col-md-6">

                    <a
                        href="request.php"
                        class="quick-box"
                    >

                        <div class="quick-icon">

                            <i class="fa-solid fa-file-medical"></i>

                        </div>

                        <div class="quick-text">

                            <strong>
                                Blood Requests
                            </strong>

                            <small>
                                Review blood requests
                            </small>

                        </div>

                        <i class="fa-solid fa-chevron-right quick-arrow"></i>

                    </a>

                </div>


                <!-- HOSPITALS -->

                <div class="col-xl-3 col-md-6">

                    <a
                        href="hospitals.php"
                        class="quick-box"
                    >

                        <div class="quick-icon">

                            <i class="fa-solid fa-hospital"></i>

                        </div>

                        <div class="quick-text">

                            <strong>
                                Hospitals
                            </strong>

                            <small>
                                Manage hospitals
                            </small>

                        </div>

                        <i class="fa-solid fa-chevron-right quick-arrow"></i>

                    </a>

                </div>


                <!-- REPORTS -->

                <div class="col-xl-3 col-md-6">

                    <a
                        href="reports.php"
                        class="quick-box"
                    >

                        <div class="quick-icon">

                            <i class="fa-solid fa-chart-pie"></i>

                        </div>

                        <div class="quick-text">

                            <strong>
                                Reports
                            </strong>

                            <small>
                                View system reports
                            </small>

                        </div>

                        <i class="fa-solid fa-chevron-right quick-arrow"></i>

                    </a>

                </div>


                <!-- SETTINGS -->

                <div class="col-xl-3 col-md-6">

                    <a
                        href="settings.php"
                        class="quick-box"
                    >

                        <div class="quick-icon">

                            <i class="fa-solid fa-gear"></i>

                        </div>

                        <div class="quick-text">

                            <strong>
                                Settings
                            </strong>

                            <small>
                                Configure system
                            </small>

                        </div>

                        <i class="fa-solid fa-chevron-right quick-arrow"></i>

                    </a>

                </div>


                <!-- CONTACT -->

                <div class="col-xl-3 col-md-6">

                    <a
                        href="contact_messages.php"
                        class="quick-box"
                    >

                        <div class="quick-icon">

                            <i class="fa-solid fa-envelope"></i>

                        </div>

                        <div class="quick-text">

                            <strong>
                                Contact Messages
                            </strong>

                            <small>
                                Read user messages
                            </small>

                        </div>


                        <?php if ($unread_messages > 0): ?>

                            <span
                                class="badge bg-danger"
                                style="
                                    position:absolute;
                                    top:8px;
                                    right:8px;
                                "
                            >

                                <?= $unread_messages; ?>

                            </span>

                        <?php endif; ?>


                        <i class="fa-solid fa-chevron-right quick-arrow"></i>

                    </a>

                </div>


            </div>

        </div>


        <!-- =====================================================
             LATEST REQUESTS
        ====================================================== -->

        <div class="section-heading">

            <h4>

                <i class="fa-solid fa-clock-rotate-left"></i>

                Latest Blood Requests

            </h4>

            <span>

                Most recent requests

            </span>

        </div>


        <div class="request-card">


            <div class="request-header">

                <h5>

                    <i class="fa-solid fa-droplet"></i>

                    Recent Requests

                </h5>

                <span>

                    Latest 5 records

                </span>

            </div>


            <div class="table-responsive">

                <table class="table request-table">

                    <thead>

                        <tr>

                            <th>ID</th>

                            <th>Recipient</th>

                            <th>Blood Group</th>

                            <th>City</th>

                            <th>Status</th>

                        </tr>

                    </thead>


                    <tbody>


                    <?php if (
                        $latest &&
                        mysqli_num_rows($latest) > 0
                    ): ?>


                        <?php while (
                            $row =
                            mysqli_fetch_assoc($latest)
                        ): ?>


                            <tr>


                                <!-- ID -->

                                <td>

                                    <strong>

                                        #<?= htmlspecialchars(
                                            $row['id']
                                        ); ?>

                                    </strong>

                                </td>


                                <!-- RECIPIENT -->

                                <td>

                                    <div
                                        class="d-flex align-items-center gap-2"
                                    >

                                        <div
                                            style="
                                                width:34px;
                                                height:34px;
                                                border-radius:10px;
                                                display:flex;
                                                align-items:center;
                                                justify-content:center;
                                                background:#fff0f2;
                                                color:#dc3545;
                                            "
                                        >

                                            <i
                                                class="fa-solid fa-user"
                                            ></i>

                                        </div>


                                        <strong>

                                            <?= htmlspecialchars(
                                                $row['recipient_name'] ?? 'Unknown'
                                            ); ?>

                                        </strong>

                                    </div>

                                </td>


                                <!-- BLOOD GROUP -->

                                <td>

                                    <span class="blood-badge">

                                        <?= htmlspecialchars(
                                            $row['blood_group'] ?? 'N/A'
                                        ); ?>

                                    </span>

                                </td>


                                <!-- CITY -->

                                <td>

                                    <i
                                        class="fa-solid fa-location-dot me-1"
                                        style="color:#dc3545;"
                                    ></i>

                                    <?= htmlspecialchars(
                                        $row['city'] ?? 'N/A'
                                    ); ?>

                                </td>


                                <!-- STATUS -->

                                <td>

                                    <?php

                                    $status = strtolower(
                                        trim(
                                            $row['status'] ?? ''
                                        )
                                    );


                                    if ($status === 'accepted') {

                                        ?>

                                        <span class="status-badge accepted">

                                            <span class="status-dot"></span>

                                            Accepted

                                        </span>

                                        <?php

                                    } elseif ($status === 'rejected') {

                                        ?>

                                        <span class="status-badge rejected">

                                            <span class="status-dot"></span>

                                            Rejected

                                        </span>

                                        <?php

                                    } else {

                                        ?>

                                        <span class="status-badge other">

                                            <span class="status-dot"></span>

                                            <?= htmlspecialchars(
                                                $row['status'] ?? 'Pending'
                                            ); ?>

                                        </span>

                                        <?php

                                    }

                                    ?>

                                </td>


                            </tr>


                        <?php endwhile; ?>


                    <?php else: ?>


                        <tr>

                            <td colspan="5">

                                <div class="empty-state">

                                    <div class="empty-state-icon">

                                        <i
                                            class="fa-solid fa-droplet"
                                        ></i>

                                    </div>


                                    <strong>

                                        No Blood Requests

                                    </strong>


                                    <div class="small mt-1">

                                        There are currently no blood
                                        requests to display.

                                    </div>

                                </div>

                            </td>

                        </tr>


                    <?php endif; ?>


                    </tbody>

                </table>

            </div>

        </div>


    </div>

</div>


<?php include("../includes/footer.php"); ?>