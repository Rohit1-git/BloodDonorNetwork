<?php

session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include("../includes/db.php");


// =====================================================
// CHECK ID
// =====================================================

if (!isset($_GET['id'])) {
    header("Location: donors.php");
    exit();
}

$id = intval($_GET['id']);


// =====================================================
// FETCH DONOR
// =====================================================

$result = mysqli_query(
    $conn,
    "SELECT * FROM donors WHERE id=$id"
);

$donor = mysqli_fetch_assoc($result);

if (!$donor) {
    header("Location: donors.php");
    exit();
}


// =====================================================
// UPDATE DONOR
// =====================================================

if (isset($_POST['update'])) {

    $name = mysqli_real_escape_string(
        $conn,
        trim($_POST['name'])
    );

    $email = mysqli_real_escape_string(
        $conn,
        trim($_POST['email'])
    );

    $phone = mysqli_real_escape_string(
        $conn,
        trim($_POST['phone'])
    );

    $blood_group = mysqli_real_escape_string(
        $conn,
        $_POST['blood_group']
    );

    $city = mysqli_real_escape_string(
        $conn,
        trim($_POST['city'])
    );

    $availability = mysqli_real_escape_string(
        $conn,
        $_POST['availability']
    );


    mysqli_query(
        $conn,

        "UPDATE donors SET

        name='$name',
        email='$email',
        phone='$phone',
        blood_group='$blood_group',
        city='$city',
        availability='$availability'

        WHERE id=$id"
    );


    header("Location: donors.php");
    exit();
}


include("../includes/header.php");

?>

<style>

/* =====================================================
   PAGE
===================================================== */

.edit-donor-page {

    min-height: 100vh;

    padding: 35px 0 70px;

    background:
        radial-gradient(
            circle at 5% 10%,
            rgba(220,53,69,0.08),
            transparent 28%
        ),

        radial-gradient(
            circle at 95% 90%,
            rgba(220,53,69,0.07),
            transparent 30%
        ),

        #f7f9fc;
}


/* =====================================================
   MAIN CARD
===================================================== */

.edit-donor-card {

    border: none;

    border-radius: 24px;

    overflow: hidden;

    background: #fff;

    box-shadow:
        0 18px 55px rgba(0,0,0,0.09);
}


/* =====================================================
   HEADER
===================================================== */

.edit-donor-header {

    position: relative;

    padding: 32px 35px;

    color: #fff;

    background:
        linear-gradient(
            135deg,
            #dc3545,
            #b91c31,
            #8f1728
        );

    overflow: hidden;
}


.edit-donor-header::before {

    content: "";

    position: absolute;

    width: 240px;
    height: 240px;

    border-radius: 50%;

    background: rgba(255,255,255,0.08);

    top: -120px;
    right: -70px;
}


.edit-donor-header::after {

    content: "";

    position: absolute;

    width: 160px;
    height: 160px;

    border-radius: 50%;

    background: rgba(255,255,255,0.06);

    bottom: -90px;
    left: -60px;
}


.header-content {

    position: relative;

    z-index: 2;

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 20px;
}


.header-left {

    display: flex;

    align-items: center;

    gap: 16px;
}


.header-icon {

    width: 62px;
    height: 62px;

    display: flex;

    align-items: center;
    justify-content: center;

    flex-shrink: 0;

    border-radius: 18px;

    background: rgba(255,255,255,0.16);

    border: 1px solid rgba(255,255,255,0.25);

    font-size: 25px;
}


.edit-donor-header h2 {

    margin: 0;

    font-size: 25px;

    font-weight: 800;
}


.edit-donor-header p {

    margin: 5px 0 0;

    color: rgba(255,255,255,0.82);

    font-size: 13px;
}


/* =====================================================
   BACK BUTTON
===================================================== */

.back-btn {

    display: inline-flex;

    align-items: center;

    gap: 8px;

    padding: 11px 17px;

    border-radius: 12px;

    color: #fff;

    text-decoration: none;

    background: rgba(255,255,255,0.13);

    border: 1px solid rgba(255,255,255,0.18);

    font-size: 13px;

    font-weight: 600;

    transition: .25s ease;
}


.back-btn:hover {

    color: #dc3545;

    background: #fff;

    transform: translateY(-2px);
}


/* =====================================================
   BODY
===================================================== */

.edit-donor-body {

    padding: 35px;
}


/* =====================================================
   DONOR PROFILE CARD
===================================================== */

.donor-profile-card {

    display: flex;

    align-items: center;

    gap: 18px;

    margin-bottom: 25px;

    padding: 20px;

    border: 1px solid #edf0f2;

    border-radius: 18px;

    background:
        linear-gradient(
            135deg,
            #ffffff,
            #fff7f8
        );

    box-shadow:
        0 8px 25px rgba(0,0,0,0.05);

    transition: .25s ease;
}


.donor-profile-card:hover {

    transform: translateY(-2px);

    box-shadow:
        0 12px 30px rgba(0,0,0,0.07);
}


/* AVATAR */

.donor-avatar {

    width: 68px;
    height: 68px;

    display: flex;

    align-items: center;
    justify-content: center;

    flex-shrink: 0;

    border-radius: 19px;

    background:
        linear-gradient(
            135deg,
            #dc3545,
            #a91e2c
        );

    color: #fff;

    font-size: 26px;

    box-shadow:
        0 8px 18px rgba(220,53,69,0.22);
}


/* PROFILE INFO */

.donor-profile-info {

    flex: 1;
}


.donor-profile-info h5 {

    margin: 0 0 5px;

    color: #222;

    font-size: 19px;

    font-weight: 800;
}


.donor-profile-info p {

    margin: 0;

    color: #777;

    font-size: 13px;
}


.donor-profile-meta {

    display: flex;

    flex-wrap: wrap;

    gap: 8px;

    margin-top: 10px;
}


/* PROFILE BADGES */

.profile-badge {

    display: inline-flex;

    align-items: center;

    gap: 5px;

    padding: 6px 10px;

    border-radius: 8px;

    background: #f1f3f5;

    color: #555;

    font-size: 11px;

    font-weight: 700;
}


.profile-badge.blood {

    background: #fff0f2;

    color: #dc3545;
}


.profile-badge.available {

    background: #eaf8f0;

    color: #198754;
}


.profile-badge.unavailable {

    background: #f0f1f3;

    color: #6c757d;
}


/* =====================================================
   DONOR ID
===================================================== */

.donor-id-box {

    display: flex;

    align-items: center;

    gap: 12px;

    padding: 14px 16px;

    margin-bottom: 30px;

    border-radius: 14px;

    background:
        linear-gradient(
            135deg,
            #fff7f8,
            #fff
        );

    border: 1px solid #ffd9de;
}


.donor-id-icon {

    width: 42px;
    height: 42px;

    display: flex;

    align-items: center;
    justify-content: center;

    flex-shrink: 0;

    border-radius: 12px;

    background: #dc3545;

    color: #fff;
}


.donor-id-box strong {

    display: block;

    font-size: 13px;

    color: #333;
}


.donor-id-box small {

    color: #888;

    font-size: 11px;
}


/* =====================================================
   FORM SECTION TITLE
===================================================== */

.form-section-title {

    display: flex;

    align-items: center;

    gap: 10px;

    margin: 5px 0 22px;

    color: #dc3545;

    font-size: 16px;

    font-weight: 800;
}


.form-section-title i {

    width: 36px;
    height: 36px;

    display: flex;

    align-items: center;
    justify-content: center;

    border-radius: 11px;

    background: #fff0f2;

    color: #dc3545;
}


/* =====================================================
   FORM GROUP
===================================================== */

.form-group-custom {

    margin-bottom: 20px;
}


.form-label-custom {

    display: block;

    margin-bottom: 7px;

    color: #333;

    font-size: 13px;

    font-weight: 700;
}


.required {

    color: #dc3545;
}


/* =====================================================
   INPUT
===================================================== */

.input-wrapper {

    position: relative;
}


.input-wrapper > i {

    position: absolute;

    left: 15px;

    top: 50%;

    transform: translateY(-50%);

    color: #dc3545;

    z-index: 2;

    font-size: 14px;
}


.form-control-custom,
.form-select-custom {

    width: 100%;

    min-height: 49px;

    padding: 10px 14px 10px 43px;

    border: 1px solid #e0e4e9;

    border-radius: 12px;

    outline: none;

    background: #fafbfc;

    color: #333;

    font-size: 14px;

    transition: all .25s ease;
}


.form-control-custom:focus,
.form-select-custom:focus {

    border-color: #dc3545;

    background: #fff;

    box-shadow:
        0 0 0 4px rgba(220,53,69,0.08);
}


.form-control-custom:hover,
.form-select-custom:hover {

    border-color: #cfd4da;
}


/* =====================================================
   AVAILABILITY
===================================================== */

.availability-wrapper {

    display: grid;

    grid-template-columns: 1fr 1fr;

    gap: 12px;
}


.availability-option {

    position: relative;
}


.availability-option input {

    position: absolute;

    opacity: 0;

    pointer-events: none;
}


.availability-label {

    display: flex;

    align-items: center;

    gap: 11px;

    min-height: 49px;

    padding: 10px 14px;

    border-radius: 13px;

    border: 1px solid #e1e5ea;

    background: #fafbfc;

    cursor: pointer;

    transition: .25s ease;
}


.availability-label:hover {

    border-color: #dc3545;

    background: #fff7f8;
}


.availability-dot {

    width: 13px;
    height: 13px;

    border-radius: 50%;

    flex-shrink: 0;
}


.available-dot {

    background: #198754;

    box-shadow:
        0 0 0 4px rgba(25,135,84,0.10);
}


.unavailable-dot {

    background: #6c757d;

    box-shadow:
        0 0 0 4px rgba(108,117,125,0.10);
}


.availability-label strong {

    display: block;

    font-size: 13px;

    color: #333;
}


.availability-label small {

    display: block;

    margin-top: 2px;

    color: #888;

    font-size: 10px;
}


.availability-option input:checked
+ .availability-label {

    border-color: #dc3545;

    background: #fff5f6;

    box-shadow:
        0 0 0 3px rgba(220,53,69,0.06);
}


/* =====================================================
   FORM FOOTER
===================================================== */

.form-footer {

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 15px;

    margin-top: 25px;

    padding-top: 25px;

    border-top: 1px solid #edf0f2;
}


.form-footer-info {

    color: #888;

    font-size: 12px;
}


.form-footer-actions {

    display: flex;

    gap: 10px;
}


/* =====================================================
   UPDATE BUTTON
===================================================== */

.update-btn {

    min-height: 49px;

    padding: 0 23px;

    border: none;

    border-radius: 12px;

    background:
        linear-gradient(
            135deg,
            #dc3545,
            #b91c31
        );

    color: #fff;

    font-size: 14px;

    font-weight: 700;

    box-shadow:
        0 8px 20px rgba(220,53,69,0.22);

    transition: .25s ease;
}


.update-btn:hover {

    color: #fff;

    transform: translateY(-2px);

    box-shadow:
        0 12px 26px rgba(220,53,69,0.32);
}


/* =====================================================
   CANCEL BUTTON
===================================================== */

.cancel-btn {

    display: inline-flex;

    align-items: center;

    justify-content: center;

    gap: 7px;

    min-height: 49px;

    padding: 0 20px;

    border-radius: 12px;

    background: #f1f3f5;

    color: #555;

    text-decoration: none;

    font-size: 14px;

    font-weight: 600;

    transition: .25s ease;
}


.cancel-btn:hover {

    color: #333;

    background: #e5e7ea;
}


/* =====================================================
   INPUT PLACEHOLDER
===================================================== */

.form-control-custom::placeholder {

    color: #a5a9ae;

    font-size: 13px;
}


/* =====================================================
   MOBILE
===================================================== */

@media(max-width: 768px) {

    .edit-donor-page {

        padding: 20px 8px 45px;
    }


    .edit-donor-header {

        padding: 25px 20px;
    }


    .header-content {

        align-items: flex-start;

        flex-direction: column;
    }


    .header-left {

        width: 100%;
    }


    .edit-donor-header h2 {

        font-size: 21px;
    }


    .back-btn {

        width: 100%;

        justify-content: center;
    }


    .edit-donor-body {

        padding: 22px 17px;
    }


    .donor-profile-card {

        align-items: flex-start;

        flex-direction: column;

        padding: 18px;
    }


    .donor-avatar {

        width: 58px;
        height: 58px;

        font-size: 22px;
    }


    .donor-profile-info {

        width: 100%;
    }


    .donor-profile-meta {

        gap: 6px;
    }


    .availability-wrapper {

        grid-template-columns: 1fr;
    }


    .form-footer {

        align-items: stretch;

        flex-direction: column;
    }


    .form-footer-actions {

        width: 100%;

        flex-direction: column;
    }


    .update-btn,
    .cancel-btn {

        width: 100%;
    }

}

</style>


<div class="admin-layout">


    <?php include("navbar.php"); ?>


    <div class="admin-content">


        <div class="edit-donor-page">


            <div class="container-fluid">


                <div class="row justify-content-center">


                    <div class="col-xl-9 col-lg-10">


                        <div class="edit-donor-card">


                            <!-- =================================================
                                 HEADER
                            ================================================== -->

                            <div class="edit-donor-header">


                                <div class="header-content">


                                    <div class="header-left">


                                        <div class="header-icon">

                                            <i class="fa-solid fa-user-pen"></i>

                                        </div>


                                        <div>

                                            <h2>
                                                Edit Donor
                                            </h2>

                                            <p>
                                                Update donor information and availability
                                            </p>

                                        </div>


                                    </div>


                                    <a
                                        href="donors.php"
                                        class="back-btn"
                                    >

                                        <i class="fa-solid fa-arrow-left"></i>

                                        Back to Donors

                                    </a>


                                </div>


                            </div>



                            <!-- =================================================
                                 BODY
                            ================================================== -->

                            <div class="edit-donor-body">


                                <!-- =================================================
                                     DONOR PROFILE CARD
                                ================================================== -->

                                <div class="donor-profile-card">


                                    <!-- AVATAR -->

                                    <div class="donor-avatar">

                                        <i class="fa-solid fa-user"></i>

                                    </div>


                                    <!-- PROFILE INFORMATION -->

                                    <div class="donor-profile-info">


                                        <h5>

                                            <?= htmlspecialchars(
                                                $donor['name']
                                            ); ?>

                                        </h5>


                                        <p>

                                            <i class="fa-solid fa-envelope me-1"></i>

                                            <?= htmlspecialchars(
                                                $donor['email']
                                            ); ?>

                                        </p>


                                        <div class="donor-profile-meta">


                                            <!-- ID -->

                                            <span class="profile-badge">

                                                <i class="fa-solid fa-id-card"></i>

                                                ID #<?= (int)$donor['id']; ?>

                                            </span>


                                            <!-- BLOOD -->

                                            <span class="profile-badge blood">

                                                <i class="fa-solid fa-droplet"></i>

                                                <?= htmlspecialchars(
                                                    $donor['blood_group']
                                                ); ?>

                                            </span>


                                            <!-- CITY -->

                                            <span class="profile-badge">

                                                <i class="fa-solid fa-location-dot"></i>

                                                <?= htmlspecialchars(
                                                    $donor['city']
                                                ); ?>

                                            </span>


                                            <!-- AVAILABILITY -->

                                            <?php if (
                                                $donor['availability']
                                                === 'Available'
                                            ): ?>

                                                <span class="profile-badge available">

                                                    <i class="fa-solid fa-circle-check"></i>

                                                    Available

                                                </span>

                                            <?php else: ?>

                                                <span class="profile-badge unavailable">

                                                    <i class="fa-solid fa-circle-xmark"></i>

                                                    Unavailable

                                                </span>

                                            <?php endif; ?>


                                        </div>


                                    </div>


                                </div>



                                <!-- =================================================
                                     DONOR ID
                                ================================================== -->

                                <div class="donor-id-box">


                                    <div class="donor-id-icon">

                                        <i class="fa-solid fa-id-card"></i>

                                    </div>


                                    <div>

                                        <strong>

                                            Donor ID #<?= (int)$donor['id']; ?>

                                        </strong>

                                        <small>

                                            Editing registered donor information

                                        </small>

                                    </div>


                                </div>



                                <!-- =================================================
                                     FORM
                                ================================================== -->

                                <form method="POST">


                                    <!-- =================================================
                                         PERSONAL INFORMATION
                                    ================================================== -->

                                    <div class="form-section-title">

                                        <i class="fa-solid fa-user"></i>

                                        Personal Information

                                    </div>


                                    <div class="row">


                                        <!-- NAME -->

                                        <div class="col-md-6">

                                            <div class="form-group-custom">


                                                <label
                                                    class="form-label-custom"
                                                >

                                                    Full Name

                                                    <span class="required">
                                                        *
                                                    </span>

                                                </label>


                                                <div class="input-wrapper">

                                                    <i class="fa-solid fa-user"></i>


                                                    <input
                                                        type="text"
                                                        name="name"
                                                        class="form-control-custom"
                                                        value="<?= htmlspecialchars(
                                                            $donor['name']
                                                        ); ?>"
                                                        placeholder="Enter donor name"
                                                        required
                                                    >

                                                </div>


                                            </div>

                                        </div>



                                        <!-- EMAIL -->

                                        <div class="col-md-6">

                                            <div class="form-group-custom">


                                                <label
                                                    class="form-label-custom"
                                                >

                                                    Email Address

                                                    <span class="required">
                                                        *
                                                    </span>

                                                </label>


                                                <div class="input-wrapper">

                                                    <i class="fa-solid fa-envelope"></i>


                                                    <input
                                                        type="email"
                                                        name="email"
                                                        class="form-control-custom"
                                                        value="<?= htmlspecialchars(
                                                            $donor['email']
                                                        ); ?>"
                                                        placeholder="Enter email address"
                                                        required
                                                    >

                                                </div>


                                            </div>

                                        </div>



                                        <!-- PHONE -->

                                        <div class="col-md-6">

                                            <div class="form-group-custom">


                                                <label
                                                    class="form-label-custom"
                                                >

                                                    Phone Number

                                                    <span class="required">
                                                        *
                                                    </span>

                                                </label>


                                                <div class="input-wrapper">

                                                    <i class="fa-solid fa-phone"></i>


                                                    <input
                                                        type="text"
                                                        name="phone"
                                                        class="form-control-custom"
                                                        value="<?= htmlspecialchars(
                                                            $donor['phone']
                                                        ); ?>"
                                                        placeholder="10 digit phone number"
                                                        maxlength="10"
                                                        pattern="[0-9]{10}"
                                                        inputmode="numeric"
                                                        required
                                                    >

                                                </div>


                                            </div>

                                        </div>



                                        <!-- CITY -->

                                        <div class="col-md-6">

                                            <div class="form-group-custom">


                                                <label
                                                    class="form-label-custom"
                                                >

                                                    City

                                                    <span class="required">
                                                        *
                                                    </span>

                                                </label>


                                                <div class="input-wrapper">

                                                    <i class="fa-solid fa-location-dot"></i>


                                                    <input
                                                        type="text"
                                                        name="city"
                                                        class="form-control-custom"
                                                        value="<?= htmlspecialchars(
                                                            $donor['city']
                                                        ); ?>"
                                                        placeholder="Enter city"
                                                        required
                                                    >

                                                </div>


                                            </div>

                                        </div>


                                    </div>



                                    <!-- =================================================
                                         BLOOD INFORMATION
                                    ================================================== -->

                                    <div class="form-section-title mt-3">

                                        <i class="fa-solid fa-droplet"></i>

                                        Blood Information

                                    </div>


                                    <div class="row">


                                        <!-- BLOOD GROUP -->

                                        <div class="col-md-6">

                                            <div class="form-group-custom">


                                                <label
                                                    class="form-label-custom"
                                                >

                                                    Blood Group

                                                    <span class="required">
                                                        *
                                                    </span>

                                                </label>


                                                <div class="input-wrapper">

                                                    <i class="fa-solid fa-droplet"></i>


                                                    <select
                                                        name="blood_group"
                                                        class="form-select-custom"
                                                        required
                                                    >


                                                        <?php

                                                        $groups = [
                                                            "A+",
                                                            "A-",
                                                            "B+",
                                                            "B-",
                                                            "AB+",
                                                            "AB-",
                                                            "O+",
                                                            "O-"
                                                        ];


                                                        foreach (
                                                            $groups
                                                            as $group
                                                        ) {

                                                            $selected =
                                                                (
                                                                    $donor[
                                                                        'blood_group'
                                                                    ]
                                                                    == $group
                                                                )
                                                                ? 'selected'
                                                                : '';

                                                            echo '
                                                            <option
                                                                value="' .
                                                                htmlspecialchars(
                                                                    $group
                                                                ) . '"
                                                                ' .
                                                                $selected . '
                                                            >
                                                                ' .
                                                                htmlspecialchars(
                                                                    $group
                                                                ) . '
                                                            </option>
                                                            ';
                                                        }

                                                        ?>


                                                    </select>

                                                </div>


                                            </div>

                                        </div>



                                        <!-- AVAILABILITY -->

                                        <div class="col-md-6">

                                            <div class="form-group-custom">


                                                <label
                                                    class="form-label-custom"
                                                >

                                                    Donor Availability

                                                    <span class="required">
                                                        *
                                                    </span>

                                                </label>


                                                <div class="availability-wrapper">


                                                    <!-- AVAILABLE -->

                                                    <div
                                                        class="availability-option"
                                                    >


                                                        <input
                                                            type="radio"
                                                            name="availability"
                                                            id="available"
                                                            value="Available"

                                                            <?= (
                                                                $donor[
                                                                    'availability'
                                                                ]
                                                                == "Available"
                                                            )
                                                                ? 'checked'
                                                                : ''; ?>
                                                        >


                                                        <label
                                                            for="available"
                                                            class="availability-label"
                                                        >

                                                            <span
                                                                class="availability-dot available-dot"
                                                            ></span>


                                                            <span>

                                                                <strong>
                                                                    Available
                                                                </strong>

                                                                <small>
                                                                    Ready to donate
                                                                </small>

                                                            </span>

                                                        </label>


                                                    </div>



                                                    <!-- UNAVAILABLE -->

                                                    <div
                                                        class="availability-option"
                                                    >


                                                        <input
                                                            type="radio"
                                                            name="availability"
                                                            id="unavailable"
                                                            value="Unavailable"

                                                            <?= (
                                                                $donor[
                                                                    'availability'
                                                                ]
                                                                == "Unavailable"
                                                            )
                                                                ? 'checked'
                                                                : ''; ?>
                                                        >


                                                        <label
                                                            for="unavailable"
                                                            class="availability-label"
                                                        >

                                                            <span
                                                                class="availability-dot unavailable-dot"
                                                            ></span>


                                                            <span>

                                                                <strong>
                                                                    Unavailable
                                                                </strong>

                                                                <small>
                                                                    Not available now
                                                                </small>

                                                            </span>

                                                        </label>


                                                    </div>


                                                </div>


                                            </div>

                                        </div>


                                    </div>



                                    <!-- =================================================
                                         FORM FOOTER
                                    ================================================== -->

                                    <div class="form-footer">


                                        <div class="form-footer-info">

                                            <i
                                                class="fa-solid fa-circle-info me-1"
                                            ></i>

                                            Make sure the donor information
                                            is correct.

                                        </div>


                                        <div class="form-footer-actions">


                                            <!-- CANCEL -->

                                            <a
                                                href="donors.php"
                                                class="cancel-btn"
                                            >

                                                <i class="fa-solid fa-xmark"></i>

                                                Cancel

                                            </a>


                                            <!-- UPDATE -->

                                            <button
                                                type="submit"
                                                name="update"
                                                class="update-btn"
                                            >

                                                <i
                                                    class="fa-solid fa-floppy-disk me-2"
                                                ></i>

                                                Update Donor

                                            </button>


                                        </div>


                                    </div>


                                </form>


                            </div>


                        </div>


                    </div>


                </div>


            </div>


        </div>


    </div>


</div>



<script>

/* =====================================================
   PHONE NUMBER ONLY
===================================================== */

const phoneInput =
    document.querySelector('input[name="phone"]');

if (phoneInput) {

    phoneInput.addEventListener(
        "input",
        function () {

            this.value = this.value
                .replace(/\D/g, "")
                .slice(0, 10);

        }
    );

}

</script>


<?php include("../includes/footer.php"); ?>