
<?php
session_start();

if (isset($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
    exit();
}

include("../includes/header.php");
?>

<style>

/* =========================================================
   PREMIUM ADMIN LOGIN
========================================================= */

:root {
    --red: #dc3545;
    --red-dark: #a91427;
    --red-deep: #730d1c;
    --white: #ffffff;
    --text: #202124;
    --muted: #7c8188;
}


/* =========================================================
   PAGE
========================================================= */

.admin-page {
    min-height: 100vh;

    display: flex;
    align-items: center;

    position: relative;
    overflow: hidden;

    padding: 45px 15px;

    background:
        radial-gradient(
            circle at 10% 20%,
            rgba(220,53,69,.15),
            transparent 28%
        ),
        radial-gradient(
            circle at 90% 80%,
            rgba(169,20,39,.14),
            transparent 30%
        ),
        linear-gradient(
            135deg,
            #fff5f6 0%,
            #f8f9fb 50%,
            #fff 100%
        );
}


/* =========================================================
   BACKGROUND DECORATION
========================================================= */

.bg-circle {
    position: absolute;

    border-radius: 50%;

    pointer-events: none;
}

.bg-circle.one {
    width: 420px;
    height: 420px;

    top: -220px;
    left: -170px;

    background:
        rgba(220,53,69,.07);

    animation:
        floatingOne 8s ease-in-out infinite;
}

.bg-circle.two {
    width: 330px;
    height: 330px;

    right: -140px;
    bottom: -160px;

    background:
        rgba(169,20,39,.07);

    animation:
        floatingTwo 10s ease-in-out infinite;
}

.bg-circle.three {
    width: 80px;
    height: 80px;

    right: 17%;
    top: 12%;

    border: 15px solid rgba(220,53,69,.05);
}


@keyframes floatingOne {

    0%,100% {
        transform: translate(0,0);
    }

    50% {
        transform: translate(20px,25px);
    }

}


@keyframes floatingTwo {

    0%,100% {
        transform: translate(0,0);
    }

    50% {
        transform: translate(-20px,-20px);
    }

}


/* =========================================================
   MAIN CONTAINER
========================================================= */

.admin-container {
    position: relative;

    z-index: 2;

    width: 100%;
}


/* =========================================================
   MAIN CARD
========================================================= */

.admin-card {
    position: relative;

    overflow: hidden;

    max-width: 950px;

    margin: auto;

    display: grid;

    grid-template-columns: 45% 55%;

    border-radius: 30px;

    background:
        rgba(255,255,255,.88);

    border:
        1px solid rgba(255,255,255,.9);

    box-shadow:
        0 35px 90px rgba(92,15,28,.13),
        0 10px 30px rgba(0,0,0,.06);

    backdrop-filter:
        blur(18px);
}


/* =========================================================
   LEFT BRAND PANEL
========================================================= */

.admin-brand {
    position: relative;

    overflow: hidden;

    padding: 48px 42px;

    display: flex;
    flex-direction: column;
    justify-content: center;

    color: #fff;

    background:
        radial-gradient(
            circle at 85% 15%,
            rgba(255,255,255,.15),
            transparent 22%
        ),
        radial-gradient(
            circle at 10% 90%,
            rgba(255,255,255,.10),
            transparent 25%
        ),
        linear-gradient(
            145deg,
            #e63950,
            #b71931 50%,
            #770d1d
        );
}


/* Decorative circles */

.admin-brand::before {
    content: "";

    position: absolute;

    width: 300px;
    height: 300px;

    border-radius: 50%;

    top: -160px;
    right: -120px;

    background:
        rgba(255,255,255,.07);
}


.admin-brand::after {
    content: "";

    position: absolute;

    width: 220px;
    height: 220px;

    border-radius: 50%;

    bottom: -120px;
    left: -100px;

    background:
        rgba(255,255,255,.06);
}


/* =========================================================
   BRAND CONTENT
========================================================= */

.brand-content {
    position: relative;

    z-index: 3;
}


.brand-icon {
    width: 82px;
    height: 82px;

    display: flex;
    align-items: center;
    justify-content: center;

    margin-bottom: 25px;

    border-radius: 24px;

    background:
        rgba(255,255,255,.14);

    border:
        1px solid rgba(255,255,255,.28);

    box-shadow:
        0 15px 35px rgba(0,0,0,.12),
        inset 0 1px rgba(255,255,255,.18);

    font-size: 34px;

    animation:
        iconFloat 4s ease-in-out infinite;
}


@keyframes iconFloat {

    0%,100% {
        transform: translateY(0);
    }

    50% {
        transform: translateY(-7px);
    }

}


.brand-badge {
    display: inline-flex;
    align-items: center;

    gap: 7px;

    margin-bottom: 17px;

    padding: 7px 12px;

    border-radius: 30px;

    background:
        rgba(255,255,255,.12);

    border:
        1px solid rgba(255,255,255,.18);

    color:
        rgba(255,255,255,.9);

    font-size: 11px;

    font-weight: 700;

    letter-spacing: .7px;

    text-transform: uppercase;
}


.brand-badge i {
    font-size: 9px;

    color: #ffd5da;
}


.admin-brand h1 {
    margin-bottom: 14px;

    font-size: 34px;

    line-height: 1.15;

    font-weight: 800;

    letter-spacing: -.8px;
}


.admin-brand h1 span {
    color: #ffd9de;
}


.brand-description {
    max-width: 340px;

    margin-bottom: 32px;

    color:
        rgba(255,255,255,.78);

    font-size: 14px;

    line-height: 1.75;
}


/* =========================================================
   FEATURES
========================================================= */

.brand-features {
    display: flex;
    flex-direction: column;

    gap: 13px;
}


.brand-feature {
    display: flex;
    align-items: center;

    gap: 11px;

    color:
        rgba(255,255,255,.88);

    font-size: 13px;
}


.brand-feature-icon {
    width: 32px;
    height: 32px;

    flex-shrink: 0;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 9px;

    background:
        rgba(255,255,255,.12);

    font-size: 12px;
}


/* =========================================================
   RIGHT LOGIN PANEL
========================================================= */

.admin-login {
    padding: 48px 48px;

    background:
        rgba(255,255,255,.96);
}


/* =========================================================
   LOGIN HEADING
========================================================= */

.login-heading {
    margin-bottom: 32px;
}


.login-heading .mini-title {
    display: block;

    margin-bottom: 8px;

    color: var(--red);

    font-size: 11px;

    font-weight: 800;

    letter-spacing: 1.2px;

    text-transform: uppercase;
}


.login-heading h2 {
    margin-bottom: 7px;

    color: #202124;

    font-size: 28px;

    font-weight: 800;

    letter-spacing: -.5px;
}


.login-heading p {
    margin: 0;

    color: var(--muted);

    font-size: 13px;
}


/* =========================================================
   INPUT GROUP
========================================================= */

.input-group-modern {
    position: relative;

    margin-bottom: 21px;
}


.input-group-modern label {
    display: block;

    margin-bottom: 8px;

    color: #3c4043;

    font-size: 12px;

    font-weight: 750;
}


.input-box {
    position: relative;
}


.input-box > i {
    position: absolute;

    z-index: 2;

    left: 16px;
    top: 50%;

    transform: translateY(-50%);

    color: #a3a7ac;

    transition: .2s ease;
}


.modern-input {
    width: 100%;

    height: 52px;

    padding:
        0 46px 0 45px;

    border:
        1px solid #e2e5e9;

    border-radius: 13px;

    outline: none;

    background: #f9fafb;

    color: #292d32;

    font-size: 14px;

    transition:
        border .2s ease,
        box-shadow .2s ease,
        background .2s ease;
}


.modern-input::placeholder {
    color: #b0b4b9;
}


.modern-input:hover {
    border-color: #d6d9dd;
}


.modern-input:focus {
    border-color: var(--red);

    background: #fff;

    box-shadow:
        0 0 0 4px rgba(220,53,69,.08);
}


.input-box:focus-within > i {
    color: var(--red);
}


/* =========================================================
   PASSWORD TOGGLE
========================================================= */

.password-btn {
    position: absolute;

    z-index: 3;

    right: 8px;
    top: 50%;

    width: 36px;
    height: 36px;

    transform: translateY(-50%);

    display: flex;
    align-items: center;
    justify-content: center;

    border: none;

    border-radius: 9px;

    color: #858a90;

    background: transparent;

    cursor: pointer;

    transition: .2s ease;
}


.password-btn:hover {
    color: var(--red);

    background: #fff0f2;
}


/* =========================================================
   SECURITY NOTICE
========================================================= */

.security-notice {
    display: flex;
    align-items: center;

    gap: 11px;

    margin:
        2px 0 23px;

    padding:
        12px 13px;

    border:
        1px solid #ffe0e4;

    border-radius: 12px;

    background:
        linear-gradient(
            135deg,
            #fff8f9,
            #fff
        );

    color: #7c8187;

    font-size: 11px;

    line-height: 1.45;
}


.security-icon {
    width: 34px;
    height: 34px;

    flex-shrink: 0;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 9px;

    color: var(--red);

    background: #ffecef;
}


/* =========================================================
   LOGIN BUTTON
========================================================= */

.admin-submit {
    position: relative;

    overflow: hidden;

    width: 100%;

    height: 54px;

    border: none;

    border-radius: 13px;

    color: #fff;

    background:
        linear-gradient(
            135deg,
            #e13a50,
            #ad172e
        );

    font-size: 14px;

    font-weight: 750;

    box-shadow:
        0 10px 24px rgba(220,53,69,.25);

    cursor: pointer;

    transition:
        transform .25s ease,
        box-shadow .25s ease;
}


.admin-submit::before {
    content: "";

    position: absolute;

    width: 80px;
    height: 140%;

    top: -20%;

    left: -120px;

    transform:
        rotate(20deg);

    background:
        rgba(255,255,255,.17);

    transition:
        left .65s ease;
}


.admin-submit:hover::before {
    left: 120%;
}


.admin-submit:hover {
    color: #fff;

    transform:
        translateY(-2px);

    box-shadow:
        0 14px 30px rgba(220,53,69,.33);
}


.admin-submit:active {
    transform:
        translateY(0);
}


/* =========================================================
   BOTTOM
========================================================= */

.login-bottom {
    display: flex;
    align-items: center;
    justify-content: center;

    gap: 6px;

    margin-top: 23px;

    color: #a0a4a8;

    font-size: 11px;
}


.login-bottom i {
    color: var(--red);
}


/* =========================================================
   RESPONSIVE
========================================================= */

@media(max-width: 850px) {

    .admin-card {
        max-width: 550px;

        grid-template-columns: 1fr;
    }


    .admin-brand {
        padding: 38px 35px;
    }


    .brand-description {
        max-width: none;
    }


    .brand-features {
        display: grid;

        grid-template-columns:
            repeat(2, 1fr);
    }


    .admin-login {
        padding: 40px 35px;
    }

}


@media(max-width: 576px) {

    .admin-page {
        padding: 25px 10px;
    }


    .admin-card {
        border-radius: 22px;
    }


    .admin-brand {
        padding: 32px 24px;
    }


    .brand-icon {
        width: 70px;
        height: 70px;

        border-radius: 20px;

        font-size: 29px;
    }


    .admin-brand h1 {
        font-size: 28px;
    }


    .brand-description {
        font-size: 13px;
    }


    .brand-features {
        grid-template-columns: 1fr;
    }


    .admin-login {
        padding: 32px 21px;
    }


    .login-heading h2 {
        font-size: 25px;
    }

}


/* =========================================================
   REDUCED MOTION
========================================================= */

@media(prefers-reduced-motion: reduce) {

    *,
    *::before,
    *::after {
        animation: none !important;
        transition: none !important;
    }

}

</style>


<div class="admin-page">


    <!-- Background decoration -->

    <div class="bg-circle one"></div>

    <div class="bg-circle two"></div>

    <div class="bg-circle three"></div>


    <div class="container admin-container">

        <div class="admin-card">


            <!-- =====================================================
                 LEFT BRAND AREA
            ====================================================== -->

            <div class="admin-brand">

                <div class="brand-content">


                    <div class="brand-badge">

                        <i class="fa-solid fa-circle"></i>

                        Blood Bank System

                    </div>


                    <div class="brand-icon">

                        <i class="fa-solid fa-user-shield"></i>

                    </div>


                    <h1>

                        Welcome Back,
                        <span>Admin.</span>

                    </h1>


                    <p class="brand-description">

                        Manage donors, recipients, blood requests,
                        hospitals and the complete blood-bank
                        management system from one secure dashboard.

                    </p>


                    <div class="brand-features">


                        <div class="brand-feature">

                            <div class="brand-feature-icon">

                                <i class="fa-solid fa-users"></i>

                            </div>

                            <span>
                                Manage donors & recipients
                            </span>

                        </div>


                        <div class="brand-feature">

                            <div class="brand-feature-icon">

                                <i class="fa-solid fa-droplet"></i>

                            </div>

                            <span>
                                Monitor blood inventory
                            </span>

                        </div>


                        <div class="brand-feature">

                            <div class="brand-feature-icon">

                                <i class="fa-solid fa-triangle-exclamation"></i>

                            </div>

                            <span>
                                Track emergency requests
                            </span>

                        </div>


                        <div class="brand-feature">

                            <div class="brand-feature-icon">

                                <i class="fa-solid fa-chart-line"></i>

                            </div>

                            <span>
                                View system analytics
                            </span>

                        </div>


                    </div>

                </div>

            </div>


            <!-- =====================================================
                 LOGIN AREA
            ====================================================== -->

            <div class="admin-login">


                <div class="login-heading">

                    <span class="mini-title">

                        Administrator Access

                    </span>

                    <h2>

                        Sign in to Dashboard

                    </h2>

                    <p>

                        Enter your administrator credentials
                        to continue.

                    </p>

                </div>


                <form
                    action="login_process.php"
                    method="POST"
                    id="adminLoginForm"
                >


                    <!-- EMAIL -->

                    <div class="input-group-modern">

                        <label>

                            Email Address

                        </label>

                        <div class="input-box">

                            <i class="fa-solid fa-envelope"></i>

                            <input
                                type="email"
                                name="email"
                                class="modern-input"
                                placeholder="admin@example.com"
                                autocomplete="username"
                                required
                            >

                        </div>

                    </div>


                    <!-- PASSWORD -->

                    <div class="input-group-modern">

                        <label>

                            Password

                        </label>

                        <div class="input-box">

                            <i class="fa-solid fa-lock"></i>

                            <input
                                type="password"
                                name="password"
                                id="adminPassword"
                                class="modern-input"
                                placeholder="Enter your password"
                                autocomplete="current-password"
                                required
                            >


                            <button
                                type="button"
                                class="password-btn"
                                id="passwordToggle"
                                onclick="toggleAdminPassword()"
                                aria-label="Show password"
                            >

                                <i
                                    id="passwordIcon"
                                    class="fa-solid fa-eye"
                                ></i>

                            </button>

                        </div>

                    </div>


                    <!-- SECURITY -->

                    <div class="security-notice">

                        <div class="security-icon">

                            <i class="fa-solid fa-shield-halved"></i>

                        </div>

                        <span>

                            This is a secure administrator area.
                            Never share your login credentials with anyone.

                        </span>

                    </div>


                    <!-- BUTTON -->

                    <button
                        type="submit"
                        class="admin-submit"
                    >

                        <i class="fa-solid fa-right-to-bracket me-2"></i>

                        Sign In to Dashboard

                    </button>


                </form>


                <div class="login-bottom">

                    <i class="fa-solid fa-lock"></i>

                    Secure administrator authentication

                </div>


            </div>

        </div>

    </div>

</div>


<script>

/* =========================================================
   PASSWORD TOGGLE
========================================================= */

function toggleAdminPassword() {

    const password =
        document.getElementById(
            "adminPassword"
        );

    const icon =
        document.getElementById(
            "passwordIcon"
        );

    const button =
        document.getElementById(
            "passwordToggle"
        );


    if (password.type === "password") {

        password.type = "text";

        icon.classList.remove(
            "fa-eye"
        );

        icon.classList.add(
            "fa-eye-slash"
        );

        button.setAttribute(
            "aria-label",
            "Hide password"
        );

    } else {

        password.type = "password";

        icon.classList.remove(
            "fa-eye-slash"
        );

        icon.classList.add(
            "fa-eye"
        );

        button.setAttribute(
            "aria-label",
            "Show password"
        );

    }

}

</script>


<?php
include("../includes/footer.php");
?>

