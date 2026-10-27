<?php

session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include("../includes/db.php");


// =====================================================
// DELETE HOSPITAL
// =====================================================

if (isset($_GET['delete'])) {

    $id = intval($_GET['delete']);

    if ($id > 0) {

        $stmt = $conn->prepare(
            "DELETE FROM hospitals WHERE id=?"
        );

        $stmt->bind_param("i", $id);

        $stmt->execute();

        $stmt->close();
    }

    header("Location: hospitals.php");
    exit();
}


// =====================================================
// FETCH HOSPITALS
// =====================================================

$result = mysqli_query(
    $conn,
    "SELECT * FROM hospitals ORDER BY id DESC"
);


// =====================================================
// HOSPITAL COUNT
// =====================================================

$total_hospitals = 0;

$count_result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM hospitals"
);

if ($count_result) {

    $count_row = mysqli_fetch_assoc($count_result);

    $total_hospitals = $count_row['total'];
}


// =====================================================
// HEADER
// =====================================================

include("../includes/header.php");

?>

<style>

/* =====================================================
   HOSPITAL MANAGEMENT PAGE
===================================================== */

.hospital-page {

    min-height: 100vh;

    padding: 30px 0 60px;

    background:
        radial-gradient(
            circle at 5% 10%,
            rgba(220,53,69,0.08),
            transparent 28%
        ),
        radial-gradient(
            circle at 95% 90%,
            rgba(13,110,253,0.07),
            transparent 30%
        ),
        #f6f8fb;
}


/* =====================================================
   PAGE HEADER
===================================================== */

.hospital-page-header {

    display: flex;

    justify-content: space-between;

    align-items: center;

    margin-bottom: 25px;
}

.hospital-title {

    display: flex;

    align-items: center;

    gap: 15px;
}

.hospital-title-icon {

    width: 55px;
    height: 55px;

    display: flex;

    align-items: center;
    justify-content: center;

    border-radius: 16px;

    color: #fff;

    background:
        linear-gradient(
            135deg,
            #dc3545,
            #a71d2a
        );

    box-shadow:
        0 8px 20px rgba(220,53,69,0.22);

    font-size: 23px;
}

.hospital-title h2 {

    margin: 0;

    font-weight: 800;

    color: #222;
}

.hospital-title p {

    margin: 4px 0 0;

    color: #8a8f98;

    font-size: 14px;
}


/* =====================================================
   HEADER BUTTONS
===================================================== */

.hospital-actions {

    display: flex;

    gap: 10px;
}

.hospital-actions .btn {

    min-height: 45px;

    padding: 10px 17px;

    border-radius: 11px;

    font-weight: 600;
}


/* =====================================================
   STAT CARD
===================================================== */

.hospital-stat {

    position: relative;

    overflow: hidden;

    border: none;

    border-radius: 18px;

    padding: 22px;

    color: #fff;

    background:
        linear-gradient(
            135deg,
            #dc3545,
            #a71d2a
        );

    box-shadow:
        0 10px 30px rgba(220,53,69,0.18);

    margin-bottom: 25px;
}

.hospital-stat::after {

    content: "";

    position: absolute;

    width: 120px;
    height: 120px;

    border-radius: 50%;

    right: -35px;
    top: -45px;

    background:
        rgba(255,255,255,0.10);
}

.hospital-stat-content {

    position: relative;

    z-index: 2;

    display: flex;

    align-items: center;

    justify-content: space-between;
}

.hospital-stat-icon {

    width: 52px;
    height: 52px;

    display: flex;

    align-items: center;
    justify-content: center;

    border-radius: 15px;

    background:
        rgba(255,255,255,0.16);

    font-size: 22px;
}

.hospital-stat h3 {

    margin: 0;

    font-size: 30px;

    font-weight: 800;
}

.hospital-stat p {

    margin: 3px 0 0;

    opacity: .85;

    font-size: 13px;
}


/* =====================================================
   MAIN CARD
===================================================== */

.hospital-card {

    border: none;

    border-radius: 22px;

    overflow: hidden;

    background: #fff;

    box-shadow:
        0 12px 40px rgba(0,0,0,0.08);
}


/* =====================================================
   CARD HEADER
===================================================== */

.hospital-card-header {

    display: flex;

    align-items: center;

    justify-content: space-between;

    padding: 20px 24px;

    border-bottom: 1px solid #edf0f3;

    background: #fff;
}

.hospital-card-header-left {

    display: flex;

    align-items: center;

    gap: 12px;
}

.hospital-card-header-icon {

    width: 40px;
    height: 40px;

    display: flex;

    align-items: center;
    justify-content: center;

    border-radius: 11px;

    background: #fff0f1;

    color: #dc3545;
}

.hospital-card-header h5 {

    margin: 0;

    font-weight: 750;

    color: #252525;
}

.hospital-card-header small {

    color: #8a8f98;
}


/* =====================================================
   TABLE
===================================================== */

.hospital-table-wrapper {

    overflow-x: auto;
}

.hospital-table {

    margin: 0;

    min-width: 950px;

    border-collapse: separate;

    border-spacing: 0;
}

.hospital-table thead th {

    padding: 16px 18px;

    background: #fff6f7;

    color: #6d3a40;

    border: none;

    border-bottom: 1px solid #f0dadd;

    font-size: 12px;

    text-transform: uppercase;

    letter-spacing: .5px;

    font-weight: 800;

    white-space: nowrap;
}

.hospital-table tbody td {

    padding: 17px 18px;

    vertical-align: middle;

    border-bottom: 1px solid #f0f1f3;

    color: #4d5258;

    font-size: 14px;
}

.hospital-table tbody tr {

    transition: all .2s ease;
}

.hospital-table tbody tr:hover {

    background: #fffafa;

    transform: scale(1.002);
}


/* =====================================================
   HOSPITAL NAME
===================================================== */

.hospital-name {

    display: flex;

    align-items: center;

    gap: 12px;

    min-width: 210px;
}

.hospital-avatar {

    width: 42px;
    height: 42px;

    flex-shrink: 0;

    display: flex;

    align-items: center;
    justify-content: center;

    border-radius: 12px;

    color: #dc3545;

    background:
        linear-gradient(
            135deg,
            #fff0f1,
            #ffe1e4
        );

    font-size: 17px;
}

.hospital-name strong {

    display: block;

    color: #2d3035;

    font-weight: 700;
}

.hospital-name small {

    display: block;

    margin-top: 2px;

    color: #999;

    font-size: 11px;
}


/* =====================================================
   CONTACT
===================================================== */

.contact-info {

    display: flex;

    align-items: center;

    gap: 8px;
}

.contact-info i {

    color: #dc3545;

    font-size: 13px;
}


/* =====================================================
   CITY BADGE
===================================================== */

.city-badge {

    display: inline-flex;

    align-items: center;

    gap: 6px;

    padding: 7px 11px;

    border-radius: 9px;

    background: #f1f5ff;

    color: #3659a8;

    font-size: 12px;

    font-weight: 600;
}


/* =====================================================
   ADDRESS
===================================================== */

.address-text {

    max-width: 230px;

    color: #777;

    line-height: 1.5;
}


/* =====================================================
   ACTION BUTTONS
===================================================== */

.hospital-action {

    display: flex;

    gap: 7px;
}

.hospital-action .btn {

    width: 36px;
    height: 36px;

    display: inline-flex;

    align-items: center;
    justify-content: center;

    padding: 0;

    border: none;

    border-radius: 10px;

    transition: all .2s ease;
}

.hospital-edit {

    background: #eef4ff;

    color: #0d6efd;
}

.hospital-edit:hover {

    background: #0d6efd;

    color: #fff;

    transform: translateY(-2px);
}

.hospital-delete {

    background: #fff0f1;

    color: #dc3545;
}

.hospital-delete:hover {

    background: #dc3545;

    color: #fff;

    transform: translateY(-2px);
}


/* =====================================================
   EMPTY STATE
===================================================== */

.hospital-empty {

    padding: 70px 20px !important;

    text-align: center;
}

.hospital-empty-icon {

    width: 75px;
    height: 75px;

    margin: 0 auto 15px;

    display: flex;

    align-items: center;
    justify-content: center;

    border-radius: 22px;

    background: #fff0f1;

    color: #dc3545;

    font-size: 30px;
}

.hospital-empty h5 {

    margin-bottom: 5px;

    font-weight: 750;
}

.hospital-empty p {

    margin: 0;

    color: #999;
}


/* =====================================================
   MOBILE
===================================================== */

@media(max-width: 768px) {

    .hospital-page {

        padding: 20px 0 40px;
    }

    .hospital-page-header {

        align-items: flex-start;

        flex-direction: column;

        gap: 18px;
    }

    .hospital-actions {

        width: 100%;
    }

    .hospital-actions .btn {

        flex: 1;
    }

    .hospital-title h2 {

        font-size: 23px;
    }

    .hospital-card-header {

        padding: 17px;
    }

    .hospital-table tbody td,
    .hospital-table thead th {

        padding: 13px;
    }
}

</style>


<div class="admin-layout">


    <!-- ADMIN NAVBAR -->

    <?php include("navbar.php"); ?>


    <div class="admin-content">


        <div class="hospital-page">

            <div class="container-fluid">


                <!-- =================================================
                     PAGE HEADER
                ================================================= -->

                <div class="hospital-page-header">


                    <div class="hospital-title">

                        <div class="hospital-title-icon">

                            <i class="fa-solid fa-hospital"></i>

                        </div>


                        <div>

                            <h2>
                                Manage Hospitals
                            </h2>

                            <p>
                                View, manage and maintain registered hospitals
                            </p>

                        </div>

                    </div>


                    <div class="hospital-actions">


                        <a
                            href="add_hospital.php"
                            class="btn btn-success"
                        >

                            <i class="fa-solid fa-plus me-1"></i>

                            Add Hospital

                        </a>


                        <a
                            href="dashboard.php"
                            class="btn btn-dark"
                        >

                            <i class="fa-solid fa-arrow-left me-1"></i>

                            Dashboard

                        </a>


                    </div>


                </div>



                <!-- =================================================
                     STATISTICS
                ================================================= -->

                <div class="hospital-stat">

                    <div class="hospital-stat-content">


                        <div>

                            <h3>
                                <?= $total_hospitals; ?>
                            </h3>

                            <p>
                                Total Registered Hospitals
                            </p>

                        </div>


                        <div class="hospital-stat-icon">

                            <i class="fa-solid fa-hospital"></i>

                        </div>


                    </div>

                </div>



                <!-- =================================================
                     HOSPITAL LIST
                ================================================= -->

                <div class="hospital-card">


                    <!-- CARD HEADER -->

                    <div class="hospital-card-header">


                        <div class="hospital-card-header-left">


                            <div class="hospital-card-header-icon">

                                <i class="fa-solid fa-building-columns"></i>

                            </div>


                            <div>

                                <h5>
                                    Hospital Directory
                                </h5>

                                <small>
                                    All registered hospitals
                                </small>

                            </div>


                        </div>


                        <span class="badge bg-danger rounded-pill px-3 py-2">

                            <?= $total_hospitals; ?>
                            Hospitals

                        </span>


                    </div>



                    <!-- TABLE -->

                    <div class="hospital-table-wrapper">


                        <table class="table hospital-table">


                            <thead>

                                <tr>

                                    <th>
                                        ID
                                    </th>

                                    <th>
                                        Hospital
                                    </th>

                                    <th>
                                        Email
                                    </th>

                                    <th>
                                        Phone
                                    </th>

                                    <th>
                                        City
                                    </th>

                                    <th>
                                        Address
                                    </th>

                                    <th>
                                        Action
                                    </th>

                                </tr>

                            </thead>


                            <tbody>


                            <?php

                            if (
                                $result &&
                                mysqli_num_rows($result) > 0
                            ):

                                while (
                                    $row = mysqli_fetch_assoc($result)
                                ):

                            ?>


                                <tr>


                                    <!-- ID -->

                                    <td>

                                        <span class="text-muted fw-bold">

                                            #<?= (int)$row['id']; ?>

                                        </span>

                                    </td>



                                    <!-- HOSPITAL -->

                                    <td>

                                        <div class="hospital-name">


                                            <div class="hospital-avatar">

                                                <i class="fa-solid fa-hospital"></i>

                                            </div>


                                            <div>

                                                <strong>

                                                    <?= htmlspecialchars(
                                                        $row['hospital_name']
                                                    ); ?>

                                                </strong>

                                                <small>
                                                    Registered Hospital
                                                </small>

                                            </div>


                                        </div>

                                    </td>



                                    <!-- EMAIL -->

                                    <td>

                                        <div class="contact-info">

                                            <i class="fa-solid fa-envelope"></i>

                                            <span>

                                                <?= htmlspecialchars(
                                                    $row['email']
                                                ); ?>

                                            </span>

                                        </div>

                                    </td>



                                    <!-- PHONE -->

                                    <td>

                                        <div class="contact-info">

                                            <i class="fa-solid fa-phone"></i>

                                            <span>

                                                <?= htmlspecialchars(
                                                    $row['phone']
                                                ); ?>

                                            </span>

                                        </div>

                                    </td>



                                    <!-- CITY -->

                                    <td>

                                        <span class="city-badge">

                                            <i class="fa-solid fa-location-dot"></i>

                                            <?= htmlspecialchars(
                                                $row['city']
                                            ); ?>

                                        </span>

                                    </td>



                                    <!-- ADDRESS -->

                                    <td>

                                        <div class="address-text">

                                            <?= htmlspecialchars(
                                                $row['address']
                                            ); ?>

                                        </div>

                                    </td>



                                    <!-- ACTION -->

                                    <td>

                                        <div class="hospital-action">


                                            <!-- EDIT -->

                                            <a
                                                href="edit_hospital.php?id=<?= (int)$row['id']; ?>"
                                                class="btn hospital-edit"
                                                title="Edit Hospital"
                                            >

                                                <i class="fa-solid fa-pen"></i>

                                            </a>



                                            <!-- DELETE -->

                                            <a
                                                href="hospitals.php?delete=<?= (int)$row['id']; ?>"
                                                class="btn hospital-delete"
                                                title="Delete Hospital"
                                                onclick="return confirm('Are you sure you want to delete this hospital?');"
                                            >

                                                <i class="fa-solid fa-trash"></i>

                                            </a>


                                        </div>

                                    </td>


                                </tr>


                            <?php

                                endwhile;

                            else:

                            ?>


                                <tr>

                                    <td
                                        colspan="7"
                                        class="hospital-empty"
                                    >


                                        <div class="hospital-empty-icon">

                                            <i class="fa-solid fa-hospital"></i>

                                        </div>


                                        <h5>
                                            No Hospitals Found
                                        </h5>


                                        <p>
                                            There are currently no registered hospitals.
                                        </p>


                                    </td>

                                </tr>


                            <?php

                            endif;

                            ?>


                            </tbody>


                        </table>


                    </div>


                </div>


            </div>

        </div>


    </div>


</div>


<?php include("../includes/footer.php"); ?>