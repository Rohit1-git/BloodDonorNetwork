<?php

session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include("../includes/db.php");


// =====================================================
// ADD HOSPITAL
// =====================================================

if (isset($_POST['save'])) {

    $name = mysqli_real_escape_string(
        $conn,
        $_POST['hospital_name']
    );

    $email = mysqli_real_escape_string(
        $conn,
        $_POST['email']
    );

    $phone = mysqli_real_escape_string(
        $conn,
        $_POST['phone']
    );

    $city = mysqli_real_escape_string(
        $conn,
        $_POST['city']
    );

    $address = mysqli_real_escape_string(
        $conn,
        $_POST['address']
    );


    $query = mysqli_query(
        $conn,
        "INSERT INTO hospitals
        (hospital_name, email, phone, city, address)
        VALUES
        ('$name', '$email', '$phone', '$city', '$address')"
    );


    if ($query) {

        echo "
        <script>
            alert('Hospital Added Successfully');
            window.location='hospitals.php';
        </script>
        ";

        exit();

    } else {

        $error = "Error adding hospital. Please try again.";

    }
}


include("../includes/header.php");

?>


<style>

/* =====================================================
   ADD HOSPITAL PAGE
===================================================== */

.hospital-page {

    min-height: 100vh;

    padding: 60px 0;

    background:
        radial-gradient(
            circle at 10% 20%,
            rgba(220,53,69,0.09),
            transparent 30%
        ),
        radial-gradient(
            circle at 90% 80%,
            rgba(13,110,253,0.10),
            transparent 30%
        ),
        #f5f7fb;
}


/* =====================================================
   MAIN CARD
===================================================== */

.hospital-card {

    border: none;

    border-radius: 26px;

    overflow: hidden;

    background: #fff;

    box-shadow:
        0 20px 60px rgba(0,0,0,0.10);
}


/* =====================================================
   LEFT INFORMATION PANEL
===================================================== */

.hospital-info {

    position: relative;

    height: 100%;

    min-height: 620px;

    padding: 45px 35px;

    color: #fff;

    background:
        linear-gradient(
            145deg,
            #dc3545,
            #b91c31,
            #7f1424
        );

    overflow: hidden;
}


/* Decorative circles */

.hospital-info::before {

    content: "";

    position: absolute;

    width: 280px;
    height: 280px;

    border-radius: 50%;

    background:
        rgba(255,255,255,0.08);

    top: -110px;
    right: -100px;
}


.hospital-info::after {

    content: "";

    position: absolute;

    width: 200px;
    height: 200px;

    border-radius: 50%;

    background:
        rgba(255,255,255,0.06);

    bottom: -80px;
    left: -80px;
}


.hospital-info-content {

    position: relative;

    z-index: 2;
}


/* =====================================================
   ICON
===================================================== */

.hospital-icon {

    width: 82px;
    height: 82px;

    display: flex;

    align-items: center;
    justify-content: center;

    border-radius: 24px;

    background:
        rgba(255,255,255,0.15);

    border:
        1px solid rgba(255,255,255,0.25);

    box-shadow:
        0 10px 30px rgba(0,0,0,0.12);

    font-size: 35px;

    margin-bottom: 25px;
}


/* =====================================================
   LEFT TEXT
===================================================== */

.hospital-info h2 {

    font-size: 31px;

    font-weight: 800;

    line-height: 1.2;
}


.hospital-info p {

    color:
        rgba(255,255,255,0.84);

    line-height: 1.7;

    font-size: 14px;
}


/* =====================================================
   BENEFITS
===================================================== */

.hospital-features {

    margin-top: 35px;
}


.hospital-feature {

    display: flex;

    align-items: center;

    gap: 13px;

    margin-bottom: 20px;

    font-size: 14px;
}


.hospital-feature-icon {

    width: 38px;
    height: 38px;

    flex-shrink: 0;

    display: flex;

    align-items: center;
    justify-content: center;

    border-radius: 11px;

    background:
        rgba(255,255,255,0.15);

    border:
        1px solid rgba(255,255,255,0.12);
}


/* =====================================================
   RIGHT FORM
===================================================== */

.hospital-form {

    padding: 45px;
}


.form-heading {

    margin-bottom: 30px;
}


.form-heading h2 {

    color: #222;

    font-weight: 800;

    margin-bottom: 7px;
}


.form-heading p {

    color: #777;

    margin: 0;

    font-size: 14px;
}


/* =====================================================
   ERROR BOX
===================================================== */

.hospital-error {

    display: flex;

    align-items: center;

    gap: 10px;

    padding: 13px 15px;

    margin-bottom: 22px;

    border-radius: 12px;

    background: #fff0f1;

    border: 1px solid #ffd0d5;

    color: #b42332;

    font-size: 14px;

    font-weight: 600;
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

    margin-bottom: 8px;

    color: #444;

    font-size: 13px;

    font-weight: 700;
}


.hospital-form-group .field-icon {

    position: absolute;

    left: 15px;

    bottom: 14px;

    color: #dc3545;

    font-size: 15px;

    z-index: 2;
}


/* =====================================================
   INPUTS
===================================================== */

.hospital-input {

    width: 100%;

    height: 49px;

    padding:
        0 15px 0 43px;

    border:
        1px solid #e1e5eb;

    border-radius: 12px;

    background: #f9fafc;

    color: #333;

    font-size: 14px;

    outline: none;

    transition:
        all .25s ease;
}


.hospital-input::placeholder {

    color: #a0a5ad;
}


.hospital-input:focus {

    border-color: #dc3545;

    background: #fff;

    box-shadow:
        0 0 0 4px
        rgba(220,53,69,0.09);
}


/* =====================================================
   TEXTAREA
===================================================== */

.hospital-textarea {

    width: 100%;

    min-height: 125px;

    padding:
        14px 15px 14px 43px;

    border:
        1px solid #e1e5eb;

    border-radius: 12px;

    background: #f9fafc;

    resize: vertical;

    font-size: 14px;

    outline: none;

    transition: all .25s ease;
}


.hospital-textarea:focus {

    border-color: #dc3545;

    background: #fff;

    box-shadow:
        0 0 0 4px
        rgba(220,53,69,0.09);
}


/* =====================================================
   TEXTAREA ICON
===================================================== */

.textarea-wrapper {

    position: relative;
}


.textarea-wrapper .field-icon {

    position: absolute;

    left: 15px;

    top: 16px;

    color: #dc3545;

    z-index: 2;
}


/* =====================================================
   BUTTONS
===================================================== */

.hospital-save-btn {

    height: 53px;

    border: none;

    border-radius: 13px;

    background:
        linear-gradient(
            135deg,
            #dc3545,
            #b91c31
        );

    color: #fff;

    font-weight: 700;

    font-size: 15px;

    box-shadow:
        0 9px 22px
        rgba(220,53,69,0.25);

    transition:
        all .25s ease;
}


.hospital-save-btn:hover {

    color: #fff;

    transform:
        translateY(-2px);

    box-shadow:
        0 13px 28px
        rgba(220,53,69,0.35);
}


.hospital-cancel-btn {

    height: 53px;

    border-radius: 13px;

    font-weight: 600;

    border:
        1px solid #dee2e6;

    background: #fff;

    color: #555;

    transition: all .25s ease;
}


.hospital-cancel-btn:hover {

    background: #f5f5f5;

    color: #222;

    transform:
        translateY(-1px);
}


/* =====================================================
   BACK LINK
===================================================== */

.back-dashboard {

    display: inline-flex;

    align-items: center;

    gap: 7px;

    margin-top: 22px;

    color: #777;

    text-decoration: none;

    font-size: 13px;

    font-weight: 600;
}


.back-dashboard:hover {

    color: #dc3545;
}


/* =====================================================
   REQUIRED
===================================================== */

.required {

    color: #dc3545;
}


/* =====================================================
   RESPONSIVE
===================================================== */

@media(max-width: 991px) {

    .hospital-info {

        min-height: auto;

        padding: 38px 30px;
    }

    .hospital-form {

        padding: 35px 30px;
    }

}


@media(max-width: 576px) {

    .hospital-page {

        padding: 30px 10px;
    }

    .hospital-card {

        border-radius: 18px;
    }

    .hospital-info {

        padding: 30px 22px;
    }

    .hospital-form {

        padding: 30px 18px;
    }

    .hospital-info h2 {

        font-size: 26px;
    }

}

</style>


<div class="hospital-page">

    <div class="container">

        <div class="row justify-content-center">

            <div class="col-xl-10">

                <div class="hospital-card">

                    <div class="row g-0">


                        <!-- =================================================
                             LEFT INFORMATION PANEL
                        ================================================== -->

                        <div class="col-lg-5">

                            <div class="hospital-info">

                                <div class="hospital-info-content">


                                    <!-- ICON -->

                                    <div class="hospital-icon">

                                        <i class="fa-solid fa-hospital"></i>

                                    </div>


                                    <h2>

                                        Add New
                                        <br>
                                        Hospital

                                    </h2>


                                    <p class="mt-3">

                                        Register a hospital in the blood
                                        donation network and help recipients
                                        connect with nearby healthcare
                                        facilities.

                                    </p>


                                    <!-- FEATURES -->

                                    <div class="hospital-features">


                                        <div class="hospital-feature">

                                            <div class="hospital-feature-icon">

                                                <i class="fa-solid fa-hospital-user"></i>

                                            </div>

                                            <span>
                                                Manage hospital information
                                            </span>

                                        </div>


                                        <div class="hospital-feature">

                                            <div class="hospital-feature-icon">

                                                <i class="fa-solid fa-location-dot"></i>

                                            </div>

                                            <span>
                                                Store hospital location
                                            </span>

                                        </div>


                                        <div class="hospital-feature">

                                            <div class="hospital-feature-icon">

                                                <i class="fa-solid fa-phone"></i>

                                            </div>

                                            <span>
                                                Keep contact details available
                                            </span>

                                        </div>


                                        <div class="hospital-feature">

                                            <div class="hospital-feature-icon">

                                                <i class="fa-solid fa-droplet"></i>

                                            </div>

                                            <span>
                                                Support blood donation services
                                            </span>

                                        </div>


                                        <div class="hospital-feature">

                                            <div class="hospital-feature-icon">

                                                <i class="fa-solid fa-shield-heart"></i>

                                            </div>

                                            <span>
                                                Secure hospital management
                                            </span>

                                        </div>


                                    </div>


                                </div>

                            </div>

                        </div>


                        <!-- =================================================
                             RIGHT FORM
                        ================================================== -->

                        <div class="col-lg-7">

                            <div class="hospital-form">


                                <!-- FORM HEADER -->

                                <div class="form-heading">

                                    <h2>

                                        Hospital Information

                                    </h2>

                                    <p>

                                        Enter the hospital details below
                                        to add it to the system.

                                    </p>

                                </div>


                                <!-- ERROR -->

                                <?php if (isset($error)): ?>

                                    <div class="hospital-error">

                                        <i class="fa-solid fa-circle-exclamation"></i>

                                        <?= htmlspecialchars($error); ?>

                                    </div>

                                <?php endif; ?>


                                <!-- FORM -->

                                <form
                                    method="POST"
                                    id="hospitalForm"
                                >


                                    <div class="row">


                                        <!-- HOSPITAL NAME -->

                                        <div class="col-md-6">

                                            <div class="hospital-form-group">

                                                <label>

                                                    Hospital Name
                                                    <span class="required">*</span>

                                                </label>

                                                <i class="fa-solid fa-hospital field-icon"></i>

                                                <input
                                                    type="text"
                                                    name="hospital_name"
                                                    class="hospital-input"
                                                    placeholder="Enter hospital name"
                                                    required
                                                    autocomplete="organization"
                                                >

                                            </div>

                                        </div>


                                        <!-- EMAIL -->

                                        <div class="col-md-6">

                                            <div class="hospital-form-group">

                                                <label>

                                                    Email Address

                                                </label>

                                                <i class="fa-solid fa-envelope field-icon"></i>

                                                <input
                                                    type="email"
                                                    name="email"
                                                    class="hospital-input"
                                                    placeholder="hospital@example.com"
                                                    autocomplete="email"
                                                >

                                            </div>

                                        </div>


                                        <!-- PHONE -->

                                        <div class="col-md-6">

                                            <div class="hospital-form-group">

                                                <label>

                                                    Phone Number

                                                </label>

                                                <i class="fa-solid fa-phone field-icon"></i>

                                                <input
                                                    type="tel"
                                                    name="phone"
                                                    id="hospitalPhone"
                                                    class="hospital-input"
                                                    placeholder="10 digit phone number"
                                                    maxlength="10"
                                                    pattern="[0-9]{10}"
                                                    autocomplete="tel"
                                                >

                                            </div>

                                        </div>


                                        <!-- CITY -->

                                        <div class="col-md-6">

                                            <div class="hospital-form-group">

                                                <label>

                                                    City
                                                    <span class="required">*</span>

                                                </label>

                                                <i class="fa-solid fa-city field-icon"></i>

                                                <input
                                                    type="text"
                                                    name="city"
                                                    class="hospital-input"
                                                    placeholder="Enter city"
                                                    required
                                                >

                                            </div>

                                        </div>


                                        <!-- ADDRESS -->

                                        <div class="col-12">

                                            <div class="hospital-form-group">

                                                <label>

                                                    Complete Address
                                                    <span class="required">*</span>

                                                </label>


                                                <div class="textarea-wrapper">

                                                    <i class="fa-solid fa-map-location-dot field-icon"></i>

                                                    <textarea
                                                        name="address"
                                                        class="hospital-textarea"
                                                        placeholder="Enter complete hospital address"
                                                        rows="4"
                                                        required
                                                    ></textarea>

                                                </div>

                                            </div>

                                        </div>


                                    </div>


                                    <!-- BUTTONS -->

                                    <div class="row g-3 mt-2">


                                        <div class="col-md-7">

                                            <button
                                                type="submit"
                                                name="save"
                                                class="hospital-save-btn w-100"
                                            >

                                                <i class="fa-solid fa-hospital-circle-check me-2"></i>

                                                Add Hospital

                                            </button>

                                        </div>


                                        <div class="col-md-5">

                                            <a
                                                href="hospitals.php"
                                                class="hospital-cancel-btn btn w-100 d-flex align-items-center justify-content-center"
                                            >

                                                <i class="fa-solid fa-xmark me-2"></i>

                                                Cancel

                                            </a>

                                        </div>


                                    </div>


                                </form>


                                <!-- DASHBOARD -->

                                <div class="text-center">

                                    <a
                                        href="dashboard.php"
                                        class="back-dashboard"
                                    >

                                        <i class="fa-solid fa-arrow-left"></i>

                                        Back to Dashboard

                                    </a>

                                </div>


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
   PHONE NUMBER VALIDATION
===================================================== */

const hospitalPhone =
    document.getElementById("hospitalPhone");

if (hospitalPhone) {

    hospitalPhone.addEventListener(
        "input",
        function () {

            this.value =
                this.value
                    .replace(/\D/g, "")
                    .slice(0, 10);

        }
    );

}


/* =====================================================
   FORM SUBMIT BUTTON
===================================================== */

const hospitalForm =
    document.getElementById("hospitalForm");

if (hospitalForm) {

    hospitalForm.addEventListener(
        "submit",
        function () {

            const button =
                this.querySelector(
                    'button[type="submit"]'
                );

            if (button) {

                button.innerHTML =
                    '<i class="fa-solid fa-spinner fa-spin me-2"></i> Adding Hospital...';

                button.disabled = true;

            }

        }
    );

}

</script>


<?php include("../includes/footer.php"); ?>