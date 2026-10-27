<?php

session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include("../includes/db.php");


// =====================================================
// DELETE REQUEST
// =====================================================

if (isset($_GET['delete']) && isset($_GET['id'])) {

    $id = intval($_GET['id']);

    if ($id > 0) {

        mysqli_query(
            $conn,
            "DELETE FROM blood_requests WHERE id=$id"
        );
    }

    header("Location: request.php");
    exit();
}


// =====================================================
// UPDATE REQUEST STATUS
// =====================================================

if (isset($_GET['action']) && isset($_GET['id'])) {

    $id = intval($_GET['id']);

    $action = $_GET['action'];

    if ($action === "accept") {

        $status = "Accepted";

    } elseif ($action === "reject") {

        $status = "Rejected";

    } else {

        $status = "Pending";
    }


    $status = mysqli_real_escape_string(
        $conn,
        $status
    );


    mysqli_query(
        $conn,
        "UPDATE blood_requests
         SET status='$status'
         WHERE id=$id"
    );


    header("Location: request.php");
    exit();
}


// =====================================================
// GET REQUESTS
// =====================================================

$result = mysqli_query(
    $conn,
    "SELECT *
     FROM blood_requests
     ORDER BY id DESC"
);


// =====================================================
// STATISTICS
// =====================================================

$totalRequests = 0;
$pendingRequests = 0;
$acceptedRequests = 0;
$rejectedRequests = 0;


$statsResult = mysqli_query(
    $conn,
    "SELECT
        COUNT(*) AS total,
        SUM(CASE WHEN status='Pending' THEN 1 ELSE 0 END) AS pending,
        SUM(CASE WHEN status='Accepted' THEN 1 ELSE 0 END) AS accepted,
        SUM(CASE WHEN status='Rejected' THEN 1 ELSE 0 END) AS rejected
     FROM blood_requests"
);


if ($statsResult) {

    $stats = mysqli_fetch_assoc($statsResult);

    $totalRequests =
        $stats['total'] ?? 0;

    $pendingRequests =
        $stats['pending'] ?? 0;

    $acceptedRequests =
        $stats['accepted'] ?? 0;

    $rejectedRequests =
        $stats['rejected'] ?? 0;
}


include("../includes/header.php");

?>


<style>

/* =====================================================
   BLOOD REQUEST MANAGEMENT
===================================================== */

.request-admin-page {

    min-height: 100vh;

    padding-bottom: 50px;

    background:
        radial-gradient(
            circle at 5% 10%,
            rgba(220,53,69,0.08),
            transparent 28%
        ),
        radial-gradient(
            circle at 95% 90%,
            rgba(13,110,253,0.07),
            transparent 28%
        ),
        #f6f8fb;
}


/* =====================================================
   HEADER
===================================================== */

.request-header {

    position: relative;

    padding: 30px;

    margin-bottom: 25px;

    border-radius: 21px;

    color: white;

    background:
        linear-gradient(
            135deg,
            #dc3545,
            #b91c31,
            #7f1324
        );

    box-shadow:
        0 14px 38px rgba(220,53,69,0.22);

    overflow: hidden;
}


.request-header::before {

    content: "";

    position: absolute;

    width: 250px;
    height: 250px;

    border-radius: 50%;

    background:
        rgba(255,255,255,0.08);

    top: -130px;

    right: -60px;
}


.request-header::after {

    content: "";

    position: absolute;

    width: 160px;
    height: 160px;

    border-radius: 50%;

    background:
        rgba(255,255,255,0.06);

    bottom: -90px;

    left: 18%;
}


.request-header-content {

    position: relative;

    z-index: 2;
}


.request-header h2 {

    margin: 0 0 6px;

    font-weight: 800;
}


.request-header p {

    margin: 0;

    color:
        rgba(255,255,255,0.82);

}


/* =====================================================
   DASHBOARD BUTTON
===================================================== */

.dashboard-btn {

    border:
        1px solid rgba(255,255,255,0.30);

    background:
        rgba(255,255,255,0.12);

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

.request-stat {

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


.request-stat:hover {

    transform:
        translateY(-4px);

    box-shadow:
        0 15px 38px rgba(0,0,0,0.10);
}


.request-stat::after {

    content: "";

    position: absolute;

    width: 90px;
    height: 90px;

    border-radius: 50%;

    right: -30px;

    bottom: -30px;

    background:
        rgba(220,53,69,0.06);
}


.request-stat-icon {

    width: 48px;
    height: 48px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 14px;

    font-size: 20px;

    margin-bottom: 14px;
}


.stat-total
.request-stat-icon {

    background: #fff0f1;

    color: #dc3545;
}


.stat-pending
.request-stat-icon {

    background: #fff7df;

    color: #d39e00;
}


.stat-accepted
.request-stat-icon {

    background: #eaf8f0;

    color: #198754;
}


.stat-rejected
.request-stat-icon {

    background: #f1f2f4;

    color: #6c757d;
}


.request-stat h3 {

    margin: 0;

    font-weight: 800;

    color: #222;
}


.request-stat p {

    margin: 4px 0 0;

    color: #777;

    font-size: 13px;

    font-weight: 600;
}


/* =====================================================
   REQUEST TABLE CARD
===================================================== */

.request-table-card {

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

.request-table-header {

    padding: 22px 25px;

    border-bottom:
        1px solid #eee;

    display: flex;

    justify-content: space-between;

    align-items: center;

    gap: 15px;
}


.request-title {

    display: flex;

    align-items: center;

    gap: 12px;
}


.request-title-icon {

    width: 44px;
    height: 44px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 13px;

    background: #fff0f1;

    color: #dc3545;

    font-size: 18px;
}


.request-title h5 {

    margin: 0;

    font-weight: 800;

    color: #222;
}


.request-title small {

    color: #888;
}


/* =====================================================
   FILTER AREA
===================================================== */

.request-tools {

    display: flex;

    align-items: center;

    gap: 10px;
}


.request-search {

    position: relative;

    width: 240px;
}


.request-search i {

    position: absolute;

    left: 14px;

    top: 50%;

    transform:
        translateY(-50%);

    color: #999;
}


.request-search input {

    width: 100%;

    height: 42px;

    border:
        1px solid #e1e5eb;

    border-radius: 11px;

    padding:
        0 14px 0 40px;

    outline: none;

    background: #f8f9fb;

    transition: .2s;
}


.request-search input:focus {

    background: white;

    border-color: #dc3545;

    box-shadow:
        0 0 0 4px
        rgba(220,53,69,0.08);
}


.request-filter {

    height: 42px;

    border:
        1px solid #e1e5eb;

    border-radius: 11px;

    padding: 0 12px;

    background: #f8f9fb;

    outline: none;

    color: #555;

    font-size: 13px;

    font-weight: 600;
}


.request-filter:focus {

    border-color: #dc3545;
}


/* =====================================================
   TABLE
===================================================== */

.request-table-wrapper {

    overflow-x: auto;
}


.request-table {

    margin: 0;

    min-width: 1000px;
}


.request-table thead th {

    background:
        #fff5f6;

    color:
        #7d1e2b;

    border-bottom:
        1px solid #ffd9de;

    padding: 15px;

    font-size: 12px;

    text-transform:
        uppercase;

    letter-spacing:
        .5px;

    font-weight: 800;

    white-space: nowrap;
}


.request-table tbody td {

    padding: 16px 15px;

    vertical-align: middle;

    border-color: #f0f0f0;

    color: #444;

    font-size: 14px;
}


.request-table tbody tr {

    transition: .2s ease;
}


.request-table tbody tr:hover {

    background:
        #fffafa;
}


/* =====================================================
   REQUEST ID
===================================================== */

.request-id {

    color: #999;

    font-weight: 700;

}


/* =====================================================
   RECIPIENT NAME
===================================================== */

.request-recipient {

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

    border-radius: 12px;

    background:
        linear-gradient(
            135deg,
            #ffe2e5,
            #fff4f5
        );

    color: #dc3545;
}


/* =====================================================
   BLOOD BADGE
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
        0 4px 10px
        rgba(220,53,69,0.18);
}


/* =====================================================
   CITY
===================================================== */

.city-info {

    display: inline-flex;

    align-items: center;

    gap: 6px;
}


.city-info i {

    color: #dc3545;
}


/* =====================================================
   PHONE
===================================================== */

.phone-info {

    display: inline-flex;

    align-items: center;

    gap: 7px;
}


.phone-info i {

    color: #198754;
}


/* =====================================================
   STATUS BADGES
===================================================== */

.request-status {

    display: inline-flex;

    align-items: center;

    gap: 7px;

    padding:
        7px 12px;

    border-radius: 30px;

    font-size: 12px;

    font-weight: 700;
}


.status-dot {

    width: 7px;
    height: 7px;

    border-radius: 50%;

    background:
        currentColor;
}


.status-pending {

    background:
        #fff5d9;

    color:
        #b58100;
}


.status-accepted {

    background:
        #eaf8f0;

    color:
        #198754;
}


.status-rejected {

    background:
        #fff0f1;

    color:
        #dc3545;
}


/* =====================================================
   ACTION BUTTONS
===================================================== */

.request-actions {

    display: flex;

    gap: 6px;

    align-items: center;
}


.request-action {

    width: 35px;
    height: 35px;

    display: inline-flex;

    align-items: center;

    justify-content: center;

    border-radius: 9px;

    text-decoration: none;

    transition: .2s ease;

    border: none;
}


/* ACCEPT */

.accept-action {

    background:
        #eaf8f0;

    color:
        #198754;
}


.accept-action:hover {

    background:
        #198754;

    color: white;

    transform:
        translateY(-2px);
}


/* REJECT */

.reject-action {

    background:
        #fff5d9;

    color:
        #b58100;
}


.reject-action:hover {

    background:
        #ffc107;

    color: #212529;

    transform:
        translateY(-2px);
}


/* DELETE */

.delete-action {

    background:
        #fff0f1;

    color:
        #dc3545;
}


.delete-action:hover {

    background:
        #dc3545;

    color: white;

    transform:
        translateY(-2px);
}


/* =====================================================
   EMPTY STATE
===================================================== */

.empty-state {

    padding:
        65px 20px;

    text-align:
        center;

    color:
        #777;
}


.empty-icon {

    width: 70px;
    height: 70px;

    display: flex;

    align-items: center;

    justify-content: center;

    margin:
        0 auto 15px;

    border-radius: 20px;

    background:
        #fff0f1;

    color:
        #dc3545;

    font-size:
        28px;
}


.empty-state h5 {

    font-weight:
        700;

    color:
        #444;
}


/* =====================================================
   MOBILE
===================================================== */

@media(max-width: 900px) {

    .request-table-header {

        flex-direction: column;

        align-items: stretch;
    }


    .request-tools {

        width: 100%;
    }


    .request-search {

        flex: 1;

        width: auto;
    }

}


@media(max-width: 768px) {

    .request-admin-page {

        padding:
            20px 0 40px;
    }


    .request-header {

        padding:
            22px 20px;

        border-radius:
            16px;
    }


    .request-header h2 {

        font-size:
            22px;
    }


    .request-tools {

        flex-direction:
            column;
    }


    .request-search {

        width:
            100%;
    }


    .request-filter {

        width:
            100%;
    }

}

</style>


<div class="admin-layout">


    <?php include("navbar.php"); ?>


    <div class="admin-content">


        <div class="request-admin-page">


            <div class="container-fluid px-3 px-md-4">


                <!-- =================================================
                     PAGE HEADER
                ================================================= -->

                <div class="request-header">

                    <div class="request-header-content">

                        <div class="row align-items-center">


                            <div class="col-md-8">

                                <h2>

                                    <i class="fa-solid fa-droplet me-2"></i>

                                    Manage Blood Requests

                                </h2>

                                <p>

                                    Review, approve and manage emergency
                                    blood requests.

                                </p>

                            </div>


                            <div
                                class="col-md-4 text-md-end mt-3 mt-md-0"
                            >

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

                    <div class="col-xl-3 col-md-6">

                        <div class="request-stat stat-total">

                            <div class="request-stat-icon">

                                <i class="fa-solid fa-list-check"></i>

                            </div>

                            <h3>

                                <?= $totalRequests; ?>

                            </h3>

                            <p>

                                Total Requests

                            </p>

                        </div>

                    </div>



                    <!-- PENDING -->

                    <div class="col-xl-3 col-md-6">

                        <div class="request-stat stat-pending">

                            <div class="request-stat-icon">

                                <i class="fa-solid fa-clock"></i>

                            </div>

                            <h3>

                                <?= $pendingRequests; ?>

                            </h3>

                            <p>

                                Pending Requests

                            </p>

                        </div>

                    </div>



                    <!-- ACCEPTED -->

                    <div class="col-xl-3 col-md-6">

                        <div class="request-stat stat-accepted">

                            <div class="request-stat-icon">

                                <i class="fa-solid fa-circle-check"></i>

                            </div>

                            <h3>

                                <?= $acceptedRequests; ?>

                            </h3>

                            <p>

                                Accepted Requests

                            </p>

                        </div>

                    </div>



                    <!-- REJECTED -->

                    <div class="col-xl-3 col-md-6">

                        <div class="request-stat stat-rejected">

                            <div class="request-stat-icon">

                                <i class="fa-solid fa-circle-xmark"></i>

                            </div>

                            <h3>

                                <?= $rejectedRequests; ?>

                            </h3>

                            <p>

                                Rejected Requests

                            </p>

                        </div>

                    </div>


                </div>



                <!-- =================================================
                     REQUEST TABLE
                ================================================= -->

                <div class="request-table-card">


                    <!-- TABLE HEADER -->

                    <div class="request-table-header">


                        <div class="request-title">


                            <div class="request-title-icon">

                                <i class="fa-solid fa-hand-holding-droplet"></i>

                            </div>


                            <div>

                                <h5>

                                    Blood Requests

                                </h5>

                                <small>

                                    Review and manage incoming requests

                                </small>

                            </div>


                        </div>



                        <!-- SEARCH + FILTER -->

                        <div class="request-tools">


                            <div class="request-search">

                                <i class="fa-solid fa-magnifying-glass"></i>

                                <input
                                    type="text"
                                    id="requestSearch"
                                    placeholder="Search requests..."
                                >

                            </div>



                            <select
                                id="statusFilter"
                                class="request-filter"
                            >

                                <option value="all">
                                    All Status
                                </option>

                                <option value="pending">
                                    Pending
                                </option>

                                <option value="accepted">
                                    Accepted
                                </option>

                                <option value="rejected">
                                    Rejected
                                </option>

                            </select>


                        </div>


                    </div>



                    <!-- TABLE -->

                    <div class="request-table-wrapper">


                        <table class="table request-table">


                            <thead>

                                <tr>

                                    <th>ID</th>

                                    <th>Recipient</th>

                                    <th>Blood Group</th>

                                    <th>City</th>

                                    <th>Phone</th>

                                    <th>Status</th>

                                    <th>Action</th>

                                </tr>

                            </thead>



                            <tbody id="requestTableBody">


                            <?php if (
                                $result &&
                                mysqli_num_rows($result) > 0
                            ): ?>


                                <?php while (
                                    $row =
                                    mysqli_fetch_assoc($result)
                                ): ?>


                                    <tr
                                        data-status="<?= strtolower(
                                            htmlspecialchars(
                                                $row['status']
                                            )
                                        ); ?>"
                                    >


                                        <!-- ID -->

                                        <td>

                                            <span class="request-id">

                                                #<?= htmlspecialchars(
                                                    $row['id']
                                                ); ?>

                                            </span>

                                        </td>



                                        <!-- RECIPIENT -->

                                        <td>

                                            <div class="request-recipient">

                                                <div class="recipient-avatar">

                                                    <i class="fa-solid fa-user"></i>

                                                </div>

                                                <span>

                                                    <?= htmlspecialchars(
                                                        $row['recipient_name']
                                                    ); ?>

                                                </span>

                                            </div>

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

                                            <span class="city-info">

                                                <i class="fa-solid fa-location-dot"></i>

                                                <?= htmlspecialchars(
                                                    $row['city']
                                                ); ?>

                                            </span>

                                        </td>



                                        <!-- PHONE -->

                                        <td>

                                            <span class="phone-info">

                                                <i class="fa-solid fa-phone"></i>

                                                <?= htmlspecialchars(
                                                    $row['phone']
                                                ); ?>

                                            </span>

                                        </td>



                                        <!-- STATUS -->

                                        <td>


                                            <?php if (
                                                $row['status'] === "Pending"
                                            ): ?>


                                                <span
                                                    class="request-status status-pending"
                                                >

                                                    <span class="status-dot"></span>

                                                    Pending

                                                </span>


                                            <?php elseif (
                                                $row['status'] === "Accepted"
                                            ): ?>


                                                <span
                                                    class="request-status status-accepted"
                                                >

                                                    <span class="status-dot"></span>

                                                    Accepted

                                                </span>


                                            <?php else: ?>


                                                <span
                                                    class="request-status status-rejected"
                                                >

                                                    <span class="status-dot"></span>

                                                    Rejected

                                                </span>


                                            <?php endif; ?>


                                        </td>



                                        <!-- ACTIONS -->

                                        <td>


                                            <div class="request-actions">


                                                <!-- ACCEPT -->

                                                <?php if (
                                                    $row['status'] !== "Accepted"
                                                ): ?>


                                                    <a
                                                        href="request.php?action=accept&id=<?= (int)$row['id']; ?>"
                                                        class="request-action accept-action"
                                                        title="Accept Request"
                                                    >

                                                        <i class="fa-solid fa-check"></i>

                                                    </a>


                                                <?php endif; ?>



                                                <!-- REJECT -->

                                                <?php if (
                                                    $row['status'] !== "Rejected"
                                                ): ?>


                                                    <a
                                                        href="request.php?action=reject&id=<?= (int)$row['id']; ?>"
                                                        class="request-action reject-action"
                                                        title="Reject Request"
                                                        onclick="return confirm('Reject this blood request?');"
                                                    >

                                                        <i class="fa-solid fa-xmark"></i>

                                                    </a>


                                                <?php endif; ?>



                                                <!-- DELETE -->

                                                <a
                                                    href="request.php?delete=1&id=<?= (int)$row['id']; ?>"
                                                    class="request-action delete-action"
                                                    title="Delete Request"
                                                    onclick="return confirm('⚠️ Are you sure you want to permanently delete this blood request?');"
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

                                                <i class="fa-solid fa-droplet"></i>

                                            </div>


                                            <h5>

                                                No Blood Requests Found

                                            </h5>


                                            <p class="mb-0">

                                                There are currently no blood
                                                requests to display.

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
   SEARCH + STATUS FILTER
===================================================== */

const searchInput =
    document.getElementById("requestSearch");

const statusFilter =
    document.getElementById("statusFilter");

const requestRows =
    document.querySelectorAll(
        "#requestTableBody tr[data-status]"
    );


function filterRequests() {

    const search =
        searchInput.value
            .toLowerCase()
            .trim();

    const selectedStatus =
        statusFilter.value;


    requestRows.forEach(function(row) {

        const rowText =
            row.textContent
                .toLowerCase();

        const rowStatus =
            row.dataset.status;


        const matchesSearch =
            rowText.includes(search);


        const matchesStatus =
            selectedStatus === "all" ||
            rowStatus === selectedStatus;


        if (
            matchesSearch &&
            matchesStatus
        ) {

            row.style.display = "";

        } else {

            row.style.display = "none";

        }

    });

}


searchInput.addEventListener(
    "input",
    filterRequests
);


statusFilter.addEventListener(
    "change",
    filterRequests
);

</script>


<?php

include("../includes/footer.php");

?>