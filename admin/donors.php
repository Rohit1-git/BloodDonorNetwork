<?php

session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include("../includes/db.php");


// =====================================================
// DELETE DONOR
// =====================================================

if (isset($_GET['delete'])) {

    $id = intval($_GET['delete']);

    mysqli_query(
        $conn,
        "DELETE FROM donors WHERE id=$id"
    );

    header("Location: donors.php");
    exit();
}


// =====================================================
// GET DONORS
// =====================================================

$result = mysqli_query(
    $conn,
    "SELECT * FROM donors ORDER BY id DESC"
);


// =====================================================
// STATISTICS
// =====================================================

$totalDonors = 0;
$availableDonors = 0;
$unavailableDonors = 0;

$countResult = mysqli_query(
    $conn,
    "SELECT
        COUNT(*) AS total,
        SUM(CASE WHEN availability='Available' THEN 1 ELSE 0 END) AS available,
        SUM(CASE WHEN availability!='Available' OR availability IS NULL THEN 1 ELSE 0 END) AS unavailable
     FROM donors"
);

if ($countResult) {

    $stats = mysqli_fetch_assoc($countResult);

    $totalDonors = $stats['total'] ?? 0;
    $availableDonors = $stats['available'] ?? 0;
    $unavailableDonors = $stats['unavailable'] ?? 0;
}


include("../includes/header.php");

?>

<style>

/* =====================================================
   DONOR MANAGEMENT PAGE
===================================================== */

.donor-page {
    min-height: 100vh;
    background:
        radial-gradient(
            circle at 5% 10%,
            rgba(220,53,69,0.07),
            transparent 28%
        ),
        radial-gradient(
            circle at 95% 90%,
            rgba(220,53,69,0.06),
            transparent 28%
        ),
        #f6f8fb;

    padding-bottom: 50px;
}


/* =====================================================
   PAGE HEADER
===================================================== */

.donor-header {
    position: relative;

    padding: 28px 30px;

    margin-bottom: 25px;

    border-radius: 20px;

    color: white;

    background:
        linear-gradient(
            135deg,
            #dc3545,
            #b91c31,
            #861526
        );

    box-shadow:
        0 12px 35px rgba(220,53,69,0.20);

    overflow: hidden;
}

.donor-header::before {
    content: "";

    position: absolute;

    width: 220px;
    height: 220px;

    border-radius: 50%;

    background: rgba(255,255,255,0.07);

    top: -110px;
    right: -60px;
}

.donor-header::after {
    content: "";

    position: absolute;

    width: 150px;
    height: 150px;

    border-radius: 50%;

    background: rgba(255,255,255,0.05);

    bottom: -90px;
    left: 20%;
}

.donor-header-content {
    position: relative;
    z-index: 2;
}

.donor-header h2 {
    font-weight: 800;
    margin-bottom: 5px;
}

.donor-header p {
    margin: 0;
    color: rgba(255,255,255,0.82);
}


/* =====================================================
   BACK BUTTON
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
    color: #dc3545;
}


/* =====================================================
   STATISTICS
===================================================== */

.donor-stat {
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

.donor-stat:hover {
    transform: translateY(-4px);

    box-shadow:
        0 14px 35px rgba(0,0,0,0.10);
}

.donor-stat::after {
    content: "";

    position: absolute;

    width: 90px;
    height: 90px;

    border-radius: 50%;

    right: -30px;
    bottom: -30px;

    background: rgba(220,53,69,0.06);
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

.stat-danger .stat-icon {
    background: #fff0f1;
    color: #dc3545;
}

.stat-success .stat-icon {
    background: #eaf8f0;
    color: #198754;
}

.stat-secondary .stat-icon {
    background: #f0f1f3;
    color: #6c757d;
}

.donor-stat h3 {
    font-weight: 800;

    margin: 0;

    color: #222;
}

.donor-stat p {
    margin: 4px 0 0;

    color: #777;

    font-size: 13px;

    font-weight: 600;
}


/* =====================================================
   TABLE CARD
===================================================== */

.donor-table-card {
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

    background: #fff0f1;

    color: #dc3545;
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

.donor-search {
    position: relative;

    width: 260px;
}

.donor-search i {
    position: absolute;

    left: 14px;

    top: 50%;

    transform: translateY(-50%);

    color: #999;
}

.donor-search input {
    width: 100%;

    height: 42px;

    border: 1px solid #e1e5eb;

    border-radius: 11px;

    padding: 0 14px 0 40px;

    outline: none;

    background: #f8f9fb;

    transition: .2s;
}

.donor-search input:focus {
    background: white;

    border-color: #dc3545;

    box-shadow:
        0 0 0 4px rgba(220,53,69,0.08);
}


/* =====================================================
   TABLE
===================================================== */

.donor-table-wrapper {
    overflow-x: auto;
}

.donor-table {
    margin: 0;

    min-width: 950px;
}

.donor-table thead th {
    background: #fff5f6;

    color: #7d1e2b;

    border-bottom: 1px solid #ffd9de;

    border-top: none;

    padding: 15px;

    font-size: 12px;

    text-transform: uppercase;

    letter-spacing: .5px;

    font-weight: 800;

    white-space: nowrap;
}

.donor-table tbody td {
    padding: 16px 15px;

    vertical-align: middle;

    border-color: #f0f0f0;

    color: #444;

    font-size: 14px;
}

.donor-table tbody tr {
    transition: .2s ease;
}

.donor-table tbody tr:hover {
    background: #fffafa;
}


/* =====================================================
   DONOR NAME
===================================================== */

.donor-name {
    display: flex;

    align-items: center;

    gap: 10px;

    font-weight: 700;

    color: #292929;
}

.donor-avatar {
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
            #ffe2e5,
            #fff3f4
        );

    color: #dc3545;

    font-size: 15px;
}


/* =====================================================
   BLOOD GROUP
===================================================== */

.blood-badge {
    display: inline-flex;

    align-items: center;

    justify-content: center;

    min-width: 48px;

    height: 30px;

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
   STATUS
===================================================== */

.status-badge {
    display: inline-flex;

    align-items: center;

    gap: 6px;

    padding: 7px 11px;

    border-radius: 30px;

    font-size: 12px;

    font-weight: 700;
}

.status-available {
    background: #eaf8f0;

    color: #198754;
}

.status-unavailable {
    background: #f1f2f4;

    color: #6c757d;
}

.status-dot {
    width: 7px;
    height: 7px;

    border-radius: 50%;

    background: currentColor;
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

    background: #fff0f1;

    color: #dc3545;

    font-size: 28px;
}

.empty-state h5 {
    font-weight: 700;

    color: #444;
}


/* =====================================================
   RESPONSIVE
===================================================== */

@media(max-width: 768px) {

    .donor-page {
        padding: 20px 0 40px;
    }

    .donor-header {
        padding: 22px 20px;

        border-radius: 16px;
    }

    .donor-header h2 {
        font-size: 22px;
    }

    .table-card-header {
        align-items: stretch;

        flex-direction: column;
    }

    .donor-search {
        width: 100%;
    }

}

@media(max-width: 576px) {

    .donor-header-actions {
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

        <div class="donor-page">

            <div class="container-fluid px-3 px-md-4">


                <!-- =================================================
                     HEADER
                ================================================= -->

                <div class="donor-header">

                    <div class="donor-header-content">

                        <div class="row align-items-center">

                            <div class="col-md-8">

                                <h2>

                                    <i class="fa-solid fa-user-group me-2"></i>

                                    Manage Donors

                                </h2>

                                <p>

                                    View, manage and monitor registered
                                    blood donors.

                                </p>

                            </div>


                            <div class="col-md-4 text-md-end donor-header-actions">

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


                    <!-- TOTAL -->

                    <div class="col-lg-4 col-md-6">

                        <div class="donor-stat stat-danger">

                            <div class="stat-icon">

                                <i class="fa-solid fa-users"></i>

                            </div>

                            <h3>
                                <?= $totalDonors; ?>
                            </h3>

                            <p>
                                Total Registered Donors
                            </p>

                        </div>

                    </div>


                    <!-- AVAILABLE -->

                    <div class="col-lg-4 col-md-6">

                        <div class="donor-stat stat-success">

                            <div class="stat-icon">

                                <i class="fa-solid fa-circle-check"></i>

                            </div>

                            <h3>
                                <?= $availableDonors; ?>
                            </h3>

                            <p>
                                Currently Available
                            </p>

                        </div>

                    </div>


                    <!-- UNAVAILABLE -->

                    <div class="col-lg-4 col-md-6">

                        <div class="donor-stat stat-secondary">

                            <div class="stat-icon">

                                <i class="fa-solid fa-user-clock"></i>

                            </div>

                            <h3>
                                <?= $unavailableDonors; ?>
                            </h3>

                            <p>
                                Currently Unavailable
                            </p>

                        </div>

                    </div>

                </div>



                <!-- =================================================
                     DONOR TABLE
                ================================================= -->

                <div class="donor-table-card">


                    <!-- TABLE HEADER -->

                    <div class="table-card-header">

                        <div class="table-card-title">

                            <div class="table-card-title-icon">

                                <i class="fa-solid fa-hand-holding-droplet"></i>

                            </div>

                            <div>

                                <h5>
                                    Registered Donors
                                </h5>

                                <small>
                                    Manage donor information
                                </small>

                            </div>

                        </div>


                        <!-- SEARCH -->

                        <div class="donor-search">

                            <i class="fa-solid fa-magnifying-glass"></i>

                            <input
                                type="text"
                                id="donorSearch"
                                placeholder="Search donors..."
                            >

                        </div>

                    </div>



                    <!-- TABLE -->

                    <div class="donor-table-wrapper">

                        <table class="table donor-table">

                            <thead>

                                <tr>

                                    <th>ID</th>

                                    <th>Donor</th>

                                    <th>Email</th>

                                    <th>Phone</th>

                                    <th>Blood Group</th>

                                    <th>City</th>

                                    <th>Status</th>

                                    <th>Action</th>

                                </tr>

                            </thead>


                            <tbody id="donorTableBody">


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

                                            <div class="donor-name">

                                                <div class="donor-avatar">

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

                                            <?= htmlspecialchars(
                                                $row['email']
                                            ); ?>

                                        </td>



                                        <!-- PHONE -->

                                        <td>

                                            <i class="fa-solid fa-phone text-danger me-1"></i>

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

                                            <i class="fa-solid fa-location-dot text-danger me-1"></i>

                                            <?= htmlspecialchars(
                                                $row['city']
                                            ); ?>

                                        </td>



                                        <!-- STATUS -->

                                        <td>


                                            <?php

                                            if (
                                                isset($row['availability']) &&
                                                $row['availability'] === "Available"
                                            ):

                                            ?>

                                                <span class="status-badge status-available">

                                                    <span class="status-dot"></span>

                                                    Available

                                                </span>

                                            <?php else: ?>

                                                <span class="status-badge status-unavailable">

                                                    <span class="status-dot"></span>

                                                    Unavailable

                                                </span>

                                            <?php endif; ?>


                                        </td>



                                        <!-- ACTION -->

                                        <td>

                                            <div class="action-buttons">


                                                <a
                                                    href="edit_donor.php?id=<?= $row['id']; ?>"
                                                    class="action-btn edit-btn"
                                                    title="Edit Donor"
                                                >

                                                    <i class="fa-solid fa-pen"></i>

                                                </a>


                                                <a
                                                    href="donors.php?delete=<?= $row['id']; ?>"
                                                    class="action-btn delete-btn"
                                                    title="Delete Donor"
                                                    onclick="return confirm('Are you sure you want to delete this donor?');"
                                                >

                                                    <i class="fa-solid fa-trash"></i>

                                                </a>


                                            </div>

                                        </td>


                                    </tr>


                                <?php endwhile; ?>


                            <?php else: ?>


                                <tr>

                                    <td colspan="8">

                                        <div class="empty-state">

                                            <div class="empty-icon">

                                                <i class="fa-solid fa-users-slash"></i>

                                            </div>

                                            <h5>
                                                No Donors Found
                                            </h5>

                                            <p class="mb-0">
                                                There are currently no registered
                                                blood donors.
                                            </p>

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

    </div>

</div>


<script>

/* =====================================================
   DONOR SEARCH
===================================================== */

const donorSearch =
    document.getElementById("donorSearch");

const donorRows =
    document.querySelectorAll(
        "#donorTableBody tr"
    );


donorSearch.addEventListener(
    "input",
    function () {

        const search =
            this.value.toLowerCase().trim();


        donorRows.forEach(function (row) {

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