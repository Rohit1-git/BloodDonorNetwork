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
    header("Location: hospitals.php");
    exit();
}

$id = intval($_GET['id']);


// =====================================================
// FETCH HOSPITAL
// =====================================================

$stmt = $conn->prepare(
    "SELECT * FROM hospitals WHERE id=?"
);

$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();

$hospital = $result->fetch_assoc();

if (!$hospital) {
    echo "Hospital not found";
    exit();
}


// =====================================================
// UPDATE HOSPITAL
// =====================================================

if (isset($_POST['update'])) {

    $hospital_name = trim($_POST['hospital_name']);
    $email         = trim($_POST['email']);
    $phone         = trim($_POST['phone']);
    $city          = trim($_POST['city']);
    $address       = trim($_POST['address']);


    $update = $conn->prepare(
        "UPDATE hospitals SET
            hospital_name=?,
            email=?,
            phone=?,
            city=?,
            address=?
         WHERE id=?"
    );

    $update->bind_param(
        "sssssi",
        $hospital_name,
        $email,
        $phone,
        $city,
        $address,
        $id
    );


    if ($update->execute()) {

        header("Location: hospitals.php");
        exit();

    } else {

        echo "<script>
                alert('Update Failed');
              </script>";
    }
}


include("../includes/header.php");

?>


<style>

/* =====================================================
   EDIT HOSPITAL PAGE
===================================================== */

.hospital-edit-page {

    min-height: calc(100vh - 80px);

    padding: 45px 20px;

    background:

        radial-gradient(
            circle at 10% 15%,
            rgba(220,53,69,0.08),
            transparent 30%
        ),

        radial-gradient(
            circle at 90% 85%,
            rgba(220,53,69,0.07),
            transparent 30%
        ),

        #f7f9fc;
}


/* =====================================================
   MAIN CARD
===================================================== */

.hospital-edit-card {

    max-width: 1050px;

    margin: auto;

    background: #fff;

    border: none;

    border-radius: 26px;

    overflow: hidden;

    box-shadow:
        0 20px 65px rgba(0,0,0,0.10);
}


/* =====================================================
   LEFT INFORMATION PANEL
===================================================== */

.hospital-edit-info {

    height: 100%;

    min-height: 590px;

    padding: 45px 35px;

    position: relative;

    overflow: hidden;

    color: #fff;

    background:

        linear-gradient(
            145deg,
            #dc3545,
            #8b1524
        );
}


/* Decorative circle */

.hospital-edit-info::before {

    content: "";

    position: absolute;

    width: 300px;
    height: 300px;

    border-radius: 50%;

    background:
        rgba(255,255,255,0.08);

    top: -130px;

    right: -120px;
}


/* Decorative circle */

.hospital-edit-info::after {

    content: "";

    position: absolute;

    width: 220px;
    height: 220px;

    border-radius: 50%;

    background:
        rgba(255,255,255,0.06);

    bottom: -100px;

    left: -90px;
}


.hospital-edit-info-content {

    position: relative;

    z-index: 2;
}


/* =====================================================
   ICON
===================================================== */

.hospital-edit-icon {

    width: 82px;
    height: 82px;

    display: flex;

    align-items: center;
    justify-content: center;

    border-radius: 24px;

    background:
        rgba(255,255,255,0.15);

    border:
        1px solid
        rgba(255,255,255,0.25);

    font-size: 34px;

    margin-bottom: 25px;

    box-shadow:
        0 12px 30px rgba(0,0,0,0.12);
}


/* =====================================================
   ID BADGE
===================================================== */

.hospital-id-badge {

    display: inline-flex;

    align-items: center;

    gap: 7px;

    padding: 7px 13px;

    margin-bottom: 15px;

    border-radius: 20px;

    background:
        rgba(255,255,255,0.13);

    border:
        1px solid
        rgba(255,255,255,0.18);

    font-size: 12px;

    font-weight: 700;
}


/* =====================================================
   LEFT TEXT
===================================================== */

.hospital-edit-info h2 {

    font-weight: 800;

    line-height: 1.2;

    margin-bottom: 15px;
}


.hospital-edit-info p {

    color:
        rgba(255,255,255,0.84);

    font-size: 14px;

    line-height: 1.75;
}


/* =====================================================
   INFO LIST
===================================================== */

.hospital-info-list {

    margin-top: 35px;
}


.hospital-info-item {

    display: flex;

    align-items: center;

    gap: 13px;

    margin-bottom: 20px;

    font-size: 14px;
}


.hospital-info-item i {

    width: 37px;
    height: 37px;

    display: flex;

    align-items: center;
    justify-content: center;

    border-radius: 11px;

    background:
        rgba(255,255,255,0.14);

    flex-shrink: 0;
}


/* =====================================================
   RIGHT FORM
===================================================== */

.hospital-edit-form {

    padding: 45px;
}


.hospital-form-header {

    margin-bottom: 30px;
}


.hospital-form-header h2 {

    font-weight: 800;

    color: #222;

    margin-bottom: 6px;
}


.hospital-form-header p {

    margin: 0;

    color: #888;

    font-size: 14px;
}


/* =====================================================
   FORM GROUP
===================================================== */

.hospital-form-group {

    position: relative;

    margin-bottom: 21px;
}


.hospital-form-group label {

    display: block;

    font-size: 13px;

    font-weight: 700;

    color: #444;

    margin-bottom: 7px;
}


.hospital-form-group > i {

    position: absolute;

    left: 15px;

    bottom: 14px;

    color: #dc3545;

    font-size: 14px;

    z-index: 2;
}


/* =====================================================
   INPUT
===================================================== */

.hospital-control {

    width: 100%;

    height: 50px;

    border:
        1px solid #e1e5eb;

    border-radius: 12px;

    padding:
        0 15px 0 43px;

    background: #f9fafc;

    outline: none;

    color: #333;

    font-size: 14px;

    transition: all .25s ease;
}


.hospital-control:focus {

    border-color: #dc3545;

    background: #fff;

    box-shadow:
        0 0 0 4px
        rgba(220,53,69,0.10);
}


/* =====================================================
   TEXTAREA
===================================================== */

textarea.hospital-control {

    height: 115px;

    padding-top: 14px;

    resize: vertical;
}


/* Textarea icon */

.hospital-textarea-icon {

    top: 37px !important;

    bottom: auto !important;
}


/* =====================================================
   BUTTONS
===================================================== */

.hospital-actions {

    display: flex;

    gap: 12px;

    margin-top: 8px;
}


.update-hospital-btn {

    flex: 1;

    height: 53px;

    border: none;

    border-radius: 13px;

    color: white;

    font-weight: 700;

    font-size: 14px;

    background:

        linear-gradient(
            135deg,
            #dc3545,
            #a51f2d
        );

    box-shadow:
        0 8px 20px
        rgba(220,53,69,0.25);

    transition: all .3s ease;
}


.update-hospital-btn:hover {

    transform:
        translateY(-2px);

    color: white;

    box-shadow:
        0 12px 27px
        rgba(220,53,69,0.35);
}


.cancel-hospital-btn {

    height: 53px;

    padding: 0 25px;

    display: flex;

    align-items: center;

    justify-content: center;

    gap: 8px;

    border-radius: 13px;

    background: #f1f3f5;

    color: #555;

    text-decoration: none;

    font-weight: 600;

    transition: all .25s ease;
}


.cancel-hospital-btn:hover {

    background: #e2e6ea;

    color: #222;
}


/* =====================================================
   RESPONSIVE
===================================================== */

@media(max-width: 991px) {

    .hospital-edit-info {

        min-height: auto;

        padding: 35px 30px;
    }

    .hospital-edit-form {

        padding: 35px 30px;
    }

}


@media(max-width: 576px) {

    .hospital-edit-page {

        padding: 25px 10px;
    }

    .hospital-edit-card {

        border-radius: 18px;
    }

    .hospital-edit-info {

        padding: 30px 22px;
    }

    .hospital-edit-form {

        padding: 30px 18px;
    }

    .hospital-edit-info h2 {

        font-size: 25px;
    }

    .hospital-actions {

        flex-direction: column;
    }

    .cancel-hospital-btn {

        width: 100%;
    }

}

</style>


<div class="hospital-edit-page">

    <div class="container-fluid">

        <div class="hospital-edit-card">

            <div class="row g-0">


                <!-- =================================================
                     LEFT PANEL
                ================================================= -->

                <div class="col-lg-5">

                    <div class="hospital-edit-info">

                        <div class="hospital-edit-info-content">


                            <!-- ICON -->

                            <div class="hospital-edit-icon">

                                <i class="fa-solid fa-hospital"></i>

                            </div>


                            <!-- ID -->

                            <div class="hospital-id-badge">

                                <i class="fa-solid fa-id-card"></i>

                                Hospital #<?= (int)$hospital['id']; ?>

                            </div>


                            <!-- TITLE -->

                            <h2>

                                Update Hospital
                                <br>

                                Information

                            </h2>


                            <p>

                                Keep hospital information
                                accurate so recipients,
                                donors and administrators
                                can easily identify and
                                contact the hospital.

                            </p>


                            <!-- FEATURES -->

                            <div class="hospital-info-list">


                                <div class="hospital-info-item">

                                    <i class="fa-solid fa-hospital-user"></i>

                                    <span>
                                        Update hospital details
                                    </span>

                                </div>


                                <div class="hospital-info-item">

                                    <i class="fa-solid fa-envelope"></i>

                                    <span>
                                        Keep email information current
                                    </span>

                                </div>


                                <div class="hospital-info-item">

                                    <i class="fa-solid fa-phone"></i>

                                    <span>
                                        Maintain hospital contact number
                                    </span>

                                </div>


                                <div class="hospital-info-item">

                                    <i class="fa-solid fa-location-dot"></i>

                                    <span>
                                        Update city and location
                                    </span>

                                </div>


                                <div class="hospital-info-item">

                                    <i class="fa-solid fa-shield-heart"></i>

                                    <span>
                                        Securely manage hospital data
                                    </span>

                                </div>


                            </div>


                        </div>

                    </div>

                </div>



                <!-- =================================================
                     RIGHT FORM
                ================================================= -->

                <div class="col-lg-7">

                    <div class="hospital-edit-form">


                        <div class="hospital-form-header">

                            <h2>

                                Edit Hospital

                            </h2>

                            <p>

                                Update the hospital information
                                and save your changes.

                            </p>

                        </div>



                        <form method="POST">


                            <div class="row">


                                <!-- HOSPITAL NAME -->

                                <div class="col-md-6">

                                    <div class="hospital-form-group">

                                        <label>
                                            Hospital Name
                                        </label>

                                        <i class="fa-solid fa-hospital"></i>

                                        <input
                                            type="text"
                                            name="hospital_name"
                                            class="hospital-control"
                                            placeholder="Enter hospital name"
                                            value="<?= htmlspecialchars($hospital['hospital_name']); ?>"
                                            required
                                        >

                                    </div>

                                </div>



                                <!-- EMAIL -->

                                <div class="col-md-6">

                                    <div class="hospital-form-group">

                                        <label>
                                            Email Address
                                        </label>

                                        <i class="fa-solid fa-envelope"></i>

                                        <input
                                            type="email"
                                            name="email"
                                            class="hospital-control"
                                            placeholder="hospital@example.com"
                                            value="<?= htmlspecialchars($hospital['email']); ?>"
                                        >

                                    </div>

                                </div>



                                <!-- PHONE -->

                                <div class="col-md-6">

                                    <div class="hospital-form-group">

                                        <label>
                                            Phone Number
                                        </label>

                                        <i class="fa-solid fa-phone"></i>

                                        <input
                                            type="text"
                                            name="phone"
                                            class="hospital-control"
                                            placeholder="Enter phone number"
                                            value="<?= htmlspecialchars($hospital['phone']); ?>"
                                        >

                                    </div>

                                </div>



                                <!-- CITY -->

                                <div class="col-md-6">

                                    <div class="hospital-form-group">

                                        <label>
                                            City
                                        </label>

                                        <i class="fa-solid fa-location-dot"></i>

                                        <input
                                            type="text"
                                            name="city"
                                            class="hospital-control"
                                            placeholder="Enter city"
                                            value="<?= htmlspecialchars($hospital['city']); ?>"
                                        >

                                    </div>

                                </div>



                                <!-- ADDRESS -->

                                <div class="col-12">

                                    <div class="hospital-form-group">

                                        <label>
                                            Hospital Address
                                        </label>

                                        <i class="fa-solid fa-map-location-dot hospital-textarea-icon"></i>

                                        <textarea
                                            name="address"
                                            class="hospital-control"
                                            placeholder="Enter complete hospital address"
                                        ><?= htmlspecialchars($hospital['address']); ?></textarea>

                                    </div>

                                </div>


                            </div>



                            <!-- =================================================
                                 ACTIONS
                            ================================================= -->

                            <div class="hospital-actions">


                                <button
                                    type="submit"
                                    name="update"
                                    class="update-hospital-btn"
                                >

                                    <i class="fa-solid fa-floppy-disk me-2"></i>

                                    Save Hospital Changes

                                </button>


                                <a
                                    href="hospitals.php"
                                    class="cancel-hospital-btn"
                                >

                                    <i class="fa-solid fa-xmark"></i>

                                    Cancel

                                </a>


                            </div>


                        </form>


                    </div>

                </div>


            </div>

        </div>

    </div>

</div>


<?php include("../includes/footer.php"); ?>