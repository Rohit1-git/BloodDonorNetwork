<?php

session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include("../includes/db.php");

$id = $_SESSION['admin_id'];


// =====================================================
// CHANGE PASSWORD
// =====================================================

if (isset($_POST['change'])) {

    $old     = $_POST['old_password'];
    $new     = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    $stmt = $conn->prepare(
        "SELECT password FROM admin WHERE id=?"
    );

    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();


    if ($admin && password_verify($old, $admin['password'])) {

        if ($new === $confirm) {

            if (strlen($new) < 6) {

                echo "
                <script>
                alert('Password must contain at least 6 characters.');
                </script>";

            } else {

                $hash = password_hash(
                    $new,
                    PASSWORD_DEFAULT
                );

                $update = $conn->prepare(
                    "UPDATE admin SET password=? WHERE id=?"
                );

                $update->bind_param(
                    "si",
                    $hash,
                    $id
                );

                $update->execute();


                echo "
                <script>
                alert('Password Changed Successfully');
                window.location='settings.php';
                </script>";
            }

        } else {

            echo "
            <script>
            alert('New Passwords do not match');
            </script>";
        }

    } else {

        echo "
        <script>
        alert('Current Password Incorrect');
        </script>";
    }
}


include("../includes/header.php");

?>


<style>

/* =====================================================
   ADMIN SETTINGS PAGE
===================================================== */

.settings-page {
    min-height: calc(100vh - 80px);
    padding: 35px 0;
    background:
        radial-gradient(
            circle at 10% 10%,
            rgba(220,53,69,0.08),
            transparent 30%
        ),
        radial-gradient(
            circle at 90% 90%,
            rgba(13,110,253,0.07),
            transparent 30%
        ),
        #f6f8fb;
}


/* =====================================================
   MAIN SETTINGS CARD
===================================================== */

.settings-card {
    border: none;
    border-radius: 25px;
    overflow: hidden;
    background: #fff;

    box-shadow:
        0 20px 60px rgba(0,0,0,0.10);
}


/* =====================================================
   LEFT SECURITY PANEL
===================================================== */

.security-panel {
    position: relative;

    min-height: 100%;

    padding: 45px 35px;

    color: #fff;

    background:
        linear-gradient(
            145deg,
            #dc3545,
            #b91c31,
            #7f1525
        );

    overflow: hidden;
}


.security-panel::before {
    content: "";

    position: absolute;

    width: 280px;
    height: 280px;

    border-radius: 50%;

    background: rgba(255,255,255,0.07);

    top: -120px;
    right: -110px;
}


.security-panel::after {
    content: "";

    position: absolute;

    width: 190px;
    height: 190px;

    border-radius: 50%;

    background: rgba(255,255,255,0.06);

    bottom: -80px;
    left: -70px;
}


.security-content {
    position: relative;
    z-index: 2;
}


/* =====================================================
   SECURITY ICON
===================================================== */

.security-icon {
    width: 82px;
    height: 82px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 25px;

    background: rgba(255,255,255,0.15);

    border:
        1px solid
        rgba(255,255,255,0.25);

    font-size: 35px;

    margin-bottom: 25px;

    backdrop-filter: blur(5px);
}


.security-panel h2 {
    font-weight: 800;
    line-height: 1.2;
}


.security-panel p {
    color: rgba(255,255,255,0.85);
    line-height: 1.7;
}


/* =====================================================
   SECURITY FEATURES
===================================================== */

.security-features {
    margin-top: 35px;
}


.security-feature {
    display: flex;
    align-items: center;

    gap: 12px;

    margin-bottom: 18px;

    font-size: 14px;
}


.security-feature i {
    width: 36px;
    height: 36px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 11px;

    background: rgba(255,255,255,0.14);

    flex-shrink: 0;
}


/* =====================================================
   RIGHT FORM
===================================================== */

.settings-form {
    padding: 45px;
}


.settings-title {
    margin-bottom: 30px;
}


.settings-title h2 {
    font-weight: 800;
    color: #222;
}


.settings-title p {
    color: #777;
    margin-bottom: 0;
}


/* =====================================================
   FORM GROUP
===================================================== */

.password-group {
    position: relative;

    margin-bottom: 23px;
}


.password-group label {
    display: block;

    margin-bottom: 8px;

    font-size: 13px;

    font-weight: 700;

    color: #444;
}


.password-input-wrapper {
    position: relative;
}


.password-input {
    width: 100%;

    height: 52px;

    border:
        1px solid
        #e0e4ea;

    border-radius: 13px;

    background: #f9fafc;

    padding:
        0 48px
        0 45px;

    outline: none;

    font-size: 14px;

    transition:
        all .25s ease;
}


.password-input:focus {
    border-color: #dc3545;

    background: #fff;

    box-shadow:
        0 0 0 4px
        rgba(220,53,69,0.09);
}


.password-left-icon {
    position: absolute;

    left: 15px;
    top: 50%;

    transform:
        translateY(-50%);

    color: #dc3545;

    z-index: 2;
}


.password-toggle {
    position: absolute;

    right: 12px;
    top: 50%;

    transform:
        translateY(-50%);

    border: none;

    background: transparent;

    color: #777;

    cursor: pointer;

    width: 32px;
    height: 32px;

    border-radius: 8px;

    transition: .2s;
}


.password-toggle:hover {
    color: #dc3545;

    background: #fff0f1;
}


/* =====================================================
   PASSWORD STRENGTH
===================================================== */

.password-strength {
    margin-top: 9px;
}


.strength-bars {
    display: flex;

    gap: 5px;

    margin-bottom: 5px;
}


.strength-bar {
    height: 4px;

    flex: 1;

    border-radius: 10px;

    background: #e9ecef;

    transition: .3s;
}


.strength-text {
    font-size: 11px;

    color: #999;
}


/* =====================================================
   PASSWORD MATCH
===================================================== */

.password-match {
    display: block;

    margin-top: 6px;

    font-size: 11px;

    min-height: 16px;
}


/* =====================================================
   SECURITY NOTE
===================================================== */

.security-note {
    display: flex;

    align-items: flex-start;

    gap: 12px;

    padding: 14px 16px;

    margin-bottom: 25px;

    border-radius: 13px;

    background: #fff7f7;

    border: 1px solid #ffdfe2;

    color: #666;

    font-size: 12px;

    line-height: 1.5;
}


.security-note i {
    color: #dc3545;

    font-size: 17px;

    margin-top: 2px;
}


/* =====================================================
   BUTTONS
===================================================== */

.settings-buttons {
    display: flex;

    gap: 12px;

    margin-top: 25px;
}


.change-password-btn {
    flex: 1;

    min-height: 52px;

    border: none;

    border-radius: 13px;

    background:
        linear-gradient(
            135deg,
            #dc3545,
            #b91c31
        );

    color: #fff;

    font-size: 15px;

    font-weight: 700;

    box-shadow:
        0 8px 20px
        rgba(220,53,69,0.25);

    transition: all .25s ease;
}


.change-password-btn:hover {
    color: #fff;

    transform: translateY(-2px);

    box-shadow:
        0 12px 28px
        rgba(220,53,69,0.35);
}


.back-btn {
    min-height: 52px;

    padding:
        0 22px;

    border-radius: 13px;

    display: inline-flex;

    align-items: center;
    justify-content: center;

    gap: 7px;

    font-weight: 600;
}


/* =====================================================
   PAGE HEADER
===================================================== */

.settings-page-header {
    display: flex;

    justify-content: space-between;

    align-items: center;

    margin-bottom: 25px;
}


.settings-page-header h3 {
    font-weight: 800;

    margin: 0;

    color: #222;
}


.settings-page-header small {
    color: #888;
}


/* =====================================================
   MOBILE
===================================================== */

@media(max-width: 991px) {

    .security-panel {
        min-height: auto;

        padding: 35px 30px;
    }

    .security-features {
        margin-top: 25px;
    }

    .settings-form {
        padding: 35px 30px;
    }

}


@media(max-width: 576px) {

    .settings-page {
        padding: 20px 10px;
    }

    .settings-card {
        border-radius: 18px;
    }

    .security-panel {
        padding: 30px 22px;
    }

    .settings-form {
        padding: 30px 18px;
    }

    .settings-page-header {
        align-items: flex-start;
        gap: 10px;
        flex-direction: column;
    }

    .settings-title h2 {
        font-size: 24px;
    }

    .settings-buttons {
        flex-direction: column;
    }

    .back-btn {
        width: 100%;
    }

}

</style>


<div class="admin-layout">

    <?php include("navbar.php"); ?>


    <div class="admin-content">

        <div class="settings-page">

            <div class="container-fluid">


                <!-- =================================================
                     PAGE HEADER
                ================================================= -->

                <div class="settings-page-header">

                    <div>

                        <h3>

                            <i class="fa-solid fa-gear text-danger me-2"></i>

                            Admin Settings

                        </h3>

                        <small>
                            Manage your account security and password
                        </small>

                    </div>


                    <a
                        href="dashboard.php"
                        class="btn btn-dark"
                    >

                        <i class="fa-solid fa-arrow-left me-1"></i>

                        Dashboard

                    </a>

                </div>



                <!-- =================================================
                     SETTINGS CARD
                ================================================= -->

                <div class="settings-card">

                    <div class="row g-0">


                        <!-- =========================================
                             LEFT PANEL
                        ========================================== -->

                        <div class="col-lg-5">

                            <div class="security-panel">

                                <div class="security-content">


                                    <div class="security-icon">

                                        <i class="fa-solid fa-shield-halved"></i>

                                    </div>


                                    <h2>

                                        Protect Your
                                        <br>
                                        Admin Account

                                    </h2>


                                    <p class="mt-3">

                                        Keep your administrator account
                                        secure by using a strong and
                                        unique password.

                                    </p>



                                    <div class="security-features">


                                        <div class="security-feature">

                                            <i class="fa-solid fa-lock"></i>

                                            <span>
                                                Secure password protection
                                            </span>

                                        </div>


                                        <div class="security-feature">

                                            <i class="fa-solid fa-key"></i>

                                            <span>
                                                Change your password anytime
                                            </span>

                                        </div>


                                        <div class="security-feature">

                                            <i class="fa-solid fa-shield-heart"></i>

                                            <span>
                                                Protect sensitive admin data
                                            </span>

                                        </div>


                                        <div class="security-feature">

                                            <i class="fa-solid fa-user-shield"></i>

                                            <span>
                                                Authorized administrator access
                                            </span>

                                        </div>


                                    </div>


                                </div>

                            </div>

                        </div>



                        <!-- =========================================
                             RIGHT FORM
                        ========================================== -->

                        <div class="col-lg-7">

                            <div class="settings-form">


                                <div class="settings-title">

                                    <h2>

                                        Change Password

                                    </h2>

                                    <p>

                                        Update your administrator password
                                        to keep your account secure.

                                    </p>

                                </div>



                                <form
                                    method="POST"
                                    id="passwordForm"
                                >


                                    <!-- CURRENT PASSWORD -->

                                    <div class="password-group">

                                        <label>

                                            Current Password

                                        </label>


                                        <div class="password-input-wrapper">

                                            <i
                                                class="fa-solid fa-lock password-left-icon"
                                            ></i>


                                            <input
                                                type="password"
                                                name="old_password"
                                                id="old_password"
                                                class="password-input"
                                                placeholder="Enter current password"
                                                required
                                            >


                                            <button
                                                type="button"
                                                class="password-toggle"
                                                onclick="togglePassword(
                                                    'old_password',
                                                    this
                                                )"
                                            >

                                                <i class="fa-solid fa-eye"></i>

                                            </button>

                                        </div>

                                    </div>



                                    <!-- NEW PASSWORD -->

                                    <div class="password-group">

                                        <label>

                                            New Password

                                        </label>


                                        <div class="password-input-wrapper">

                                            <i
                                                class="fa-solid fa-key password-left-icon"
                                            ></i>


                                            <input
                                                type="password"
                                                name="new_password"
                                                id="new_password"
                                                class="password-input"
                                                placeholder="Create new password"
                                                minlength="6"
                                                required
                                            >


                                            <button
                                                type="button"
                                                class="password-toggle"
                                                onclick="togglePassword(
                                                    'new_password',
                                                    this
                                                )"
                                            >

                                                <i class="fa-solid fa-eye"></i>

                                            </button>

                                        </div>


                                        <!-- STRENGTH -->

                                        <div class="password-strength">

                                            <div class="strength-bars">

                                                <span
                                                    class="strength-bar"
                                                    id="bar1"
                                                ></span>

                                                <span
                                                    class="strength-bar"
                                                    id="bar2"
                                                ></span>

                                                <span
                                                    class="strength-bar"
                                                    id="bar3"
                                                ></span>

                                                <span
                                                    class="strength-bar"
                                                    id="bar4"
                                                ></span>

                                            </div>


                                            <span
                                                class="strength-text"
                                                id="strengthText"
                                            >
                                                Minimum 6 characters
                                            </span>

                                        </div>

                                    </div>



                                    <!-- CONFIRM PASSWORD -->

                                    <div class="password-group">

                                        <label>

                                            Confirm New Password

                                        </label>


                                        <div class="password-input-wrapper">

                                            <i
                                                class="fa-solid fa-circle-check password-left-icon"
                                            ></i>


                                            <input
                                                type="password"
                                                name="confirm_password"
                                                id="confirm_password"
                                                class="password-input"
                                                placeholder="Confirm new password"
                                                minlength="6"
                                                required
                                            >


                                            <button
                                                type="button"
                                                class="password-toggle"
                                                onclick="togglePassword(
                                                    'confirm_password',
                                                    this
                                                )"
                                            >

                                                <i class="fa-solid fa-eye"></i>

                                            </button>

                                        </div>


                                        <span
                                            class="password-match"
                                            id="passwordMatch"
                                        ></span>

                                    </div>



                                    <!-- SECURITY NOTE -->

                                    <div class="security-note">

                                        <i class="fa-solid fa-circle-info"></i>

                                        <span>

                                            Use at least 6 characters.
                                            For better security, combine
                                            uppercase letters, lowercase
                                            letters, numbers and symbols.

                                        </span>

                                    </div>



                                    <!-- BUTTONS -->

                                    <div class="settings-buttons">


                                        <button
                                            type="submit"
                                            name="change"
                                            class="change-password-btn"
                                            id="changePasswordBtn"
                                        >

                                            <i
                                                class="fa-solid fa-key me-2"
                                            ></i>

                                            Change Password

                                        </button>


                                        <a
                                            href="dashboard.php"
                                            class="btn btn-outline-secondary back-btn"
                                        >

                                            <i
                                                class="fa-solid fa-arrow-left"
                                            ></i>

                                            Back

                                        </a>


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
   PASSWORD SHOW / HIDE
===================================================== */

function togglePassword(id, button) {

    const input =
        document.getElementById(id);

    const icon =
        button.querySelector("i");


    if (input.type === "password") {

        input.type = "text";

        icon.classList.remove("fa-eye");

        icon.classList.add("fa-eye-slash");

    } else {

        input.type = "password";

        icon.classList.remove("fa-eye-slash");

        icon.classList.add("fa-eye");

    }

}



/* =====================================================
   PASSWORD STRENGTH
===================================================== */

const newPassword =
    document.getElementById("new_password");

const strengthText =
    document.getElementById("strengthText");

const bars = [

    document.getElementById("bar1"),
    document.getElementById("bar2"),
    document.getElementById("bar3"),
    document.getElementById("bar4")

];


newPassword.addEventListener(
    "input",
    function() {

        const value = this.value;

        let score = 0;


        if (value.length >= 6) {
            score++;
        }

        if (value.length >= 8) {
            score++;
        }

        if (/[A-Z]/.test(value)) {
            score++;
        }

        if (
            /[0-9]/.test(value) &&
            /[^A-Za-z0-9]/.test(value)
        ) {
            score++;
        }


        bars.forEach(
            function(bar) {

                bar.style.background =
                    "#e9ecef";

            }
        );


        if (value.length === 0) {

            strengthText.textContent =
                "Minimum 6 characters";

            strengthText.style.color =
                "#999";

            return;

        }


        let color = "#dc3545";

        let text = "Weak";


        if (score === 2) {

            color = "#fd7e14";

            text = "Fair";

        }

        else if (score === 3) {

            color = "#ffc107";

            text = "Good";

        }

        else if (score >= 4) {

            color = "#198754";

            text = "Strong";

        }


        for (
            let i = 0;
            i < score;
            i++
        ) {

            bars[i].style.background =
                color;

        }


        strengthText.textContent =
            "Password strength: " + text;

        strengthText.style.color =
            color;

    }
);



/* =====================================================
   PASSWORD MATCH
===================================================== */

const confirmPassword =
    document.getElementById(
        "confirm_password"
    );

const passwordMatch =
    document.getElementById(
        "passwordMatch"
    );


function checkPasswordMatch() {

    if (
        confirmPassword.value === ""
    ) {

        passwordMatch.textContent = "";

        confirmPassword.style.borderColor =
            "";

        return;

    }


    if (
        newPassword.value ===
        confirmPassword.value
    ) {

        passwordMatch.innerHTML =
            "✓ Passwords match";

        passwordMatch.style.color =
            "#198754";

        confirmPassword.style.borderColor =
            "#198754";

    } else {

        passwordMatch.innerHTML =
            "✕ Passwords do not match";

        passwordMatch.style.color =
            "#dc3545";

        confirmPassword.style.borderColor =
            "#dc3545";

    }

}


newPassword.addEventListener(
    "input",
    checkPasswordMatch
);

confirmPassword.addEventListener(
    "input",
    checkPasswordMatch
);



/* =====================================================
   FORM VALIDATION
===================================================== */

document
    .getElementById("passwordForm")
    .addEventListener(
        "submit",
        function(event) {

            if (
                newPassword.value !==
                confirmPassword.value
            ) {

                event.preventDefault();

                alert(
                    "New Password and Confirm Password must match."
                );

                confirmPassword.focus();

                return false;

            }


            if (
                newPassword.value.length < 6
            ) {

                event.preventDefault();

                alert(
                    "Password must contain at least 6 characters."
                );

                newPassword.focus();

                return false;

            }

        }
    );

</script>


<?php include("../includes/footer.php"); ?>