<?php

session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include("../includes/db.php");


// =====================================================
// DELETE RECIPIENT
// =====================================================

if (isset($_GET['delete'])) {

    $id = intval($_GET['delete']);

    mysqli_query(
        $conn,
        "DELETE FROM recipients WHERE id=$id"
    );

    header("Location: recipients.php");
    exit();
}


// =====================================================
// FETCH RECIPIENTS
// =====================================================

$result = mysqli_query(
    $conn,
    "SELECT * FROM recipients ORDER BY id DESC"
);


// =====================================================
// STATISTICS
// =====================================================

$totalRecipients = 0;

$countResult = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM recipients"
);

if ($countResult) {

    $stats = mysqli_fetch_assoc($countResult);

    $totalRecipients = $stats['total'] ?? 0;
}


// Count blood groups
$bloodGroups = [];

$bloodResult = mysqli_query(
    $conn,
    "SELECT blood_group, COUNT(*) AS total
     FROM recipients
     GROUP BY blood_group
     ORDER BY total DESC"
);

if ($bloodResult) {

    while ($bloodRow = mysqli_fetch_assoc($bloodResult)) {

        $bloodGroups[] = $bloodRow;
    }
}


// =====================================================
// HEADER
// =====================================================

include("../includes/header.php");

?>


<style>

/* =====================================================
   RECIPIENT MANAGEMENT PAGE
===================================================== */

.recipient-admin-page {

    min-height: 100vh;

    background:
        radial-gradient(
            circle at 5% 10%,
            rgba(13,110,253,0.07),
            transparent 28%
        ),
        radial-gradient(
            circle at 95% 90%,
            rgba(25,135,84,0.07),
            transparent 28%
        ),
        #f6f8fb;

    padding-bottom: 50px;
}


/* =====================================================
   HEADER
===================================================== */

.recipient-admin-header {

    position: relative;

    padding: 28px 30px;

    margin-bottom: 25px;

    border-radius: 20px;

    color: white;

    background:
        linear-gradient(
            135deg,
            #0d6efd,
            #084298
        );

    box-shadow:
        0 12px 35px rgba(13,110,253,0.20);

    overflow: hidden;
}


.recipient-admin-header::before {

    content: "";

    position: absolute;

    width: 230px;
    height: 230px;

    border-radius: 50%;

    background: rgba(255,255,255,0.08);

    top: -120px;
    right: -60px;
}


.recipient-admin-header::after {

    content: "";

    position: absolute;

    width: 150px;
    height: 150px;

    border-radius: 50%;

    background: rgba(255,255,255,0.06);

    bottom: -90px;
    left: 20%;
}


.recipient-header-content {

    position: relative;

    z-index: 2;
}


.recipient-admin-header h2 {

    font-weight: 800;

    margin-bottom: 5px;
}


.recipient-admin-header p {

    margin: 0;

    color: rgba(255,255,255,0.82);

}


/* =====================================================
   DASHBOARD BUTTON
===================================================== */

.dashboard-btn {

    border: 1px solid rgba(255,255,255,0.3);

    background: rgba(255,255,255,0.12);

    color: white;

    border-radius: 11px;

    padding: 10px 17px;

    font-weight: 600;

    transition: .25s ease;
}


.dashboard-btn:hover {

    background: white;

    color: #0d6efd;

}


/* =====================================================
   STAT CARD
===================================================== */

.recipient-stat {

    position: relative;

    background: white;

    border: none;

    border-radius: 18px;

    padding: 22px;

    height: 100%;

    box-shadow:
        0 8px 30px rgba(0,0,0,0.07);

    overflow: hidden;

    transition: .25s ease;
}


.recipient-stat:hover {

    transform: translateY(-4px);

    box-shadow:
        0 14px 35px rgba(0,0,0,0.10);

}


.recipient-stat::after {

    content: "";

    position: absolute;

    width: 90px;
    height: 90px;

    border-radius: 50%;

    right: -30px;
    bottom: -30px;

    background: rgba(13,110,253,0.06);

}


.stat-icon {

    width: 48px;
    height: 48px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 14px;

    font-size: 20px;

    margin-bottom: 14px;
}


.stat-blue .stat-icon {

    background: #eaf2ff;

    color: #0d6efd;
}


.stat-green .stat-icon {

    background: #eaf8f0;

    color: #198754;
}


.stat-red .stat-icon {

    background: #fff0f1;

    color: #dc3545;
}


.recipient-stat h3 {

    font-weight: 800;

    margin: 0;

    color: #222;
}


.recipient-stat p {

    margin: 4px 0 0;

    color: #777;

    font-size: 13px;

    font-weight: 600;
}


/* =====================================================
   TABLE CARD
===================================================== */

.recipient-table-card {

    border: none;

    border-radius: 20px;

    background: white;

    box-shadow:
        0 10px 40px rgba(0,0,0,0.07);

    overflow: hidden;
}


/* =====================================================
   TABLE HEADER
===================================================== */

.table-card-header {

    padding: 22px 25px;

    border-bottom: 1px solid #eee;

    display: flex;

    justify-content: space-between;

    align-items: center;

    gap: 15px;
}


.table-card-title {

    display: flex;

    align-items: center;

    gap: 12px;
}


.table-card-title-icon {

    width: 42px;
    height: 42px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 12px;

    background: #eaf2ff;

    color: #0d6efd;
}


.table-card-title h5 {

    margin: 0;

    font-weight: 800;

    color: #222;
}


.table-card-title small {

    color: #888;
}


/* =====================================================
   SEARCH
===================================================== */

.recipient-search {

    position: relative;

    width: 270px;
}


.recipient-search i {

    position: absolute;

    left: 14px;

    top: 50%;

    transform: translateY(-50%);

    color: #999;
}


.recipient-search input {

    width: 100%;

    height: 42px;

    border: 1px solid #e1e5eb;

    border-radius: 11px;

    padding: 0 14px 0 40px;

    outline: none;

    background: #f8f9fb;

    transition: .2s;
}


.recipient-search input:focus {

    background: white;

    border-color: #0d6efd;

    box-shadow:
        0 0 0 4px rgba(13,110,253,0.08);
}


/* =====================================================
   TABLE
===================================================== */

.recipient-table-wrapper {

    overflow-x: auto;
}


.recipient-table {

    margin: 0;

    min-width: 950px;
}


.recipient-table thead th {

    background: #f0f6ff;

    color: #174a8b;

    border-bottom: 1px solid #dceaff;

    border-top: none;

    padding: 15px;

    font-size: 12px;

    text-transform: uppercase;

    letter-spacing: .5px;

    font-weight: 800;

    white-space: nowrap;
}


.recipient-table tbody td {

    padding: 16px 15px;

    vertical-align: middle;

    border-color: #f0f0f0;

    color: #444;

    font-size: 14px;
}


.recipient-table tbody tr {

    transition: .2s ease;
}


.recipient-table tbody tr:hover {

    background: #f9fbff;
}


/* =====================================================
   RECIPIENT NAME
===================================================== */

.recipient-name {

    display: flex;

    align-items: center;

    gap: 10px;

    font-weight: 700;

    color: #292929;
}


.recipient-avatar {

    width: 38px;
    height: 38px;

    display: flex;

    align-items: center;

    justify-content: center;

    flex-shrink: 0;

    border-radius: 12px;

    background:
        linear-gradient(
            135deg,
            #dceaff,
            #f1f7ff
        );

    color: #0d6efd;

    font-size: 15px;
}


/* =====================================================
   BLOOD GROUP
===================================================== */

.blood-badge {

    display: inline-flex;

    align-items: center;

    justify-content: center;

    min-width: 50px;

    height: 31px;

    padding: 0 10px;

    border-radius: 9px;

    background:
        linear-gradient(
            135deg,
            #dc3545,
            #b91c31
        );

    color: white;

    font-weight: 800;

    font-size: 12px;

    box-shadow:
        0 4px 10px rgba(220,53,69,0.18);
}


/* =====================================================
   CITY
===================================================== */

.city-text {

    display: inline-flex;

    align-items: center;

    gap: 6px;
}


.city-text i {

    color: #0d6efd;
}


/* =====================================================
   ACTION BUTTONS
===================================================== */

.action-buttons {

    display: flex;

    gap: 7px;
}


.action-btn {

    width: 35px;
    height: 35px;

    display: inline-flex;

    align-items: center;

    justify-content: center;

    border-radius: 9px;

    text-decoration: none;

    transition: .2s ease;
}


.edit-btn {

    background: #eaf2ff;

    color: #0d6efd;
}


.edit-btn:hover {

    background: #0d6efd;

    color: white;

    transform: translateY(-2px);
}


.delete-btn {

    background: #fff0f1;

    color: #dc3545;
}


.delete-btn:hover {

    background: #dc3545;

    color: white;

    transform: translateY(-2px);
}


/* =====================================================
   EMPTY STATE
===================================================== */

.empty-state {

    padding: 65px 20px;

    text-align: center;

    color: #777;
}


.empty-icon {

    width: 70px;
    height: 70px;

    display: flex;

    align-items: center;

    justify-content: center;

    margin: 0 auto 15px;

    border-radius: 20px;

    background: #eaf2ff;

    color: #0d6efd;

    font-size: 28px;
}


.empty-state h5 {

    font-weight: 700;

    color: #444;
}


/* =====================================================
   BLOOD GROUP SUMMARY
===================================================== */

.blood-summary {

    margin-top: 25px;

    border: none;

    border-radius: 20px;

    background: white;

    box-shadow:
        0 8px 30px rgba(0,0,0,0.06);

    padding: 22px 25px;
}


.blood-summary h5 {

    font-weight: 800;

    margin-bottom: 18px;
}


.blood-summary-list {

    display: flex;

    flex-wrap: wrap;

    gap: 10px;
}


.blood-summary-item {

    display: flex;

    align-items: center;

    gap: 8px;

    padding: 8px 12px;

    border-radius: 10px;

    background: #fff5f6;

    border: 1px solid #ffe0e3;
}


.blood-summary-item strong {

    color: #dc3545;

}


.blood-summary-item span {

    color: #666;

    font-size: 13px;

}


/* =====================================================
   RESPONSIVE
===================================================== */

@media(max-width: 768px) {

    .recipient-admin-page {

        padding: 20px 0 40px;
    }


    .recipient-admin-header {

        padding: 22px 20px;

        border-radius: 16px;
    }


    .recipient-admin-header h2 {

        font-size: 22px;
    }


    .table-card-header {

        align-items: stretch;

        flex-direction: column;
    }


    .recipient-search {

        width: 100%;
    }

}


@media(max-width: 576px) {

    .recipient-header-actions {

        margin-top: 15px;

        width: 100%;
    }


    .dashboard-btn {

        width: 100%;
    }

}

</style>


<div class="admin-layout">


    <?php include("navbar.php"); ?>


    <div class="admin-content">


        <div class="recipient-admin-page">


            <div class="container-fluid px-3 px-md-4">


                <!-- =================================================
                     PAGE HEADER
                ================================================= -->

                <div class="recipient-admin-header">

                    <div class="recipient-header-content">

                        <div class="row align-items-center">


                            <div class="col-md-8">

                                <h2>

                                    <i class="fa-solid fa-users me-2"></i>

                                    Manage Recipients

                                </h2>

                                <p>

                                    View and manage people who require
                                    blood assistance.

                                </p>

                            </div>


                            <div class="col-md-4 text-md-end recipient-header-actions">

                                <a
                                    href="dashboard.php"
                                    class="btn dashboard-btn"
                                >

                                    <i class="fa-solid fa-arrow-left me-2"></i>

                                    Dashboard

                                </a>

                            </div>


                        </div>

                    </div>

                </div>



                <!-- =================================================
                     STATISTICS
                ================================================= -->

                <div class="row g-4 mb-4">


                    <!-- TOTAL RECIPIENTS -->

                    <div class="col-lg-4 col-md-6">

                        <div class="recipient-stat stat-blue">

                            <div class="stat-icon">

                                <i class="fa-solid fa-users"></i>

                            </div>

                            <h3>

                                <?= $totalRecipients; ?>

                            </h3>

                            <p>

                                Total Recipients

                            </p>

                        </div>

                    </div>


                    <!-- BLOOD GROUPS -->

                    <div class="col-lg-4 col-md-6">

                        <div class="recipient-stat stat-red">

                            <div class="stat-icon">

                                <i class="fa-solid fa-droplet"></i>

                            </div>

                            <h3>

                                <?= count($bloodGroups); ?>

                            </h3>

                            <p>

                                Blood Groups Requested

                            </p>

                        </div>

                    </div>


                    <!-- REQUEST STATUS -->

                    <div class="col-lg-4 col-md-6">

                        <div class="recipient-stat stat-green">

                            <div class="stat-icon">

                                <i class="fa-solid fa-heart-pulse"></i>

                            </div>

                            <h3>

                                <?= $totalRecipients; ?>

                            </h3>

                            <p>

                                Active Recipient Accounts

                            </p>

                        </div>

                    </div>


                </div>



                <!-- =================================================
                     RECIPIENT TABLE
                ================================================= -->

                <div class="recipient-table-card">


                    <!-- TABLE HEADER -->

                    <div class="table-card-header">


                        <div class="table-card-title">


                            <div class="table-card-title-icon">

                                <i class="fa-solid fa-user-injured"></i>

                            </div>


                            <div>

                                <h5>

                                    Registered Recipients

                                </h5>

                                <small>

                                    Manage recipient information

                                </small>

                            </div>


                        </div>



                        <!-- SEARCH -->

                        <div class="recipient-search">

                            <i class="fa-solid fa-magnifying-glass"></i>

                            <input
                                type="text"
                                id="recipientSearch"
                                placeholder="Search recipients..."
                            >

                        </div>


                    </div>



                    <!-- TABLE -->

                    <div class="recipient-table-wrapper">


                        <table class="table recipient-table">


                            <thead>

                                <tr>

                                    <th>ID</th>

                                    <th>Recipient</th>

                                    <th>Email</th>

                                    <th>Phone</th>

                                    <th>Blood Group</th>

                                    <th>City</th>

                                    <th>Action</th>

                                </tr>

                            </thead>



                            <tbody id="recipientTableBody">


                            <?php if (mysqli_num_rows($result) > 0): ?>


                                <?php while ($row = mysqli_fetch_assoc($result)): ?>


                                    <tr>


                                        <!-- ID -->

                                        <td>

                                            <span class="text-muted fw-semibold">

                                                #<?= htmlspecialchars(
                                                    $row['id']
                                                ); ?>

                                            </span>

                                        </td>



                                        <!-- NAME -->

                                        <td>

                                            <div class="recipient-name">

                                                <div class="recipient-avatar">

                                                    <i class="fa-solid fa-user"></i>

                                                </div>

                                                <span>

                                                    <?= htmlspecialchars(
                                                        $row['name']
                                                    ); ?>

                                                </span>

                                            </div>

                                        </td>



                                        <!-- EMAIL -->

                                        <td>

                                            <i class="fa-solid fa-envelope text-primary me-1"></i>

                                            <?= htmlspecialchars(
                                                $row['email']
                                            ); ?>

                                        </td>



                                        <!-- PHONE -->

                                        <td>

                                            <i class="fa-solid fa-phone text-primary me-1"></i>

                                            <?= htmlspecialchars(
                                                $row['phone']
                                            ); ?>

                                        </td>



                                        <!-- BLOOD GROUP -->

                                        <td>

                                            <span class="blood-badge">

                                                <i class="fa-solid fa-droplet me-1"></i>

                                                <?= htmlspecialchars(
                                                    $row['blood_group']
                                                ); ?>

                                            </span>

                                        </td>



                                        <!-- CITY -->

                                        <td>

                                            <span class="city-text">

                                                <i class="fa-solid fa-location-dot"></i>

                                                <?= htmlspecialchars(
                                                    $row['city']
                                                ); ?>

                                            </span>

                                        </td>



                                        <!-- ACTION -->

                                        <td>

                                            <div class="action-buttons">


                                                <a
                                                    href="edit_recipient.php?id=<?= $row['id']; ?>"
                                                    class="action-btn edit-btn"
                                                    title="Edit Recipient"
                                                >

                                                    <i class="fa-solid fa-pen"></i>

                                                </a>



                                                <a
                                                    href="recipients.php?delete=<?= $row['id']; ?>"
                                                    class="action-btn delete-btn"
                                                    title="Delete Recipient"
                                                    onclick="return confirm('Are you sure you want to delete this recipient?');"
                                                >

                                                    <i class="fa-solid fa-trash"></i>

                                                </a>


                                            </div>

                                        </td>


                                    </tr>


                                <?php endwhile; ?>


                            <?php else: ?>


                                <tr>

                                    <td colspan="7">


                                        <div class="empty-state">


                                            <div class="empty-icon">

                                                <i class="fa-solid fa-user-slash"></i>

                                            </div>


                                            <h5>

                                                No Recipients Found

                                            </h5>


                                            <p class="mb-0">

                                                There are currently no registered
                                                recipients.

                                            </p>


                                        </div>


                                    </td>

                                </tr>


                            <?php endif; ?>


                            </tbody>


                        </table>


                    </div>


                </div>



                <!-- =================================================
                     BLOOD GROUP SUMMARY
                ================================================= -->

                <?php if (count($bloodGroups) > 0): ?>


                <div class="blood-summary">


                    <h5>

                        <i class="fa-solid fa-chart-pie text-danger me-2"></i>

                        Recipient Blood Group Summary

                    </h5>


                    <div class="blood-summary-list">


                        <?php foreach ($bloodGroups as $group): ?>


                            <div class="blood-summary-item">

                                <strong>

                                    <?= htmlspecialchars(
                                        $group['blood_group']
                                    ); ?>

                                </strong>


                                <span>

                                    <?= htmlspecialchars(
                                        $group['total']
                                    ); ?>

                                    recipient(s)

                                </span>

                            </div>


                        <?php endforeach; ?>


                    </div>


                </div>


                <?php endif; ?>


            </div>


        </div>


    </div>


</div>


<script>

/* =====================================================
   RECIPIENT SEARCH
===================================================== */

const recipientSearch =
    document.getElementById("recipientSearch");

const recipientRows =
    document.querySelectorAll(
        "#recipientTableBody tr"
    );


recipientSearch.addEventListener(
    "input",
    function () {

        const search =
            this.value
                .toLowerCase()
                .trim();


        recipientRows.forEach(function (row) {

            const text =
                row.textContent.toLowerCase();


            if (text.includes(search)) {

                row.style.display = "";

            } else {

                row.style.display = "none";

            }

        });

    }
);

</script>


<?php include("../includes/footer.php"); ?>