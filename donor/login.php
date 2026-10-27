<?php

include("../includes/header.php");
include("../includes/navbar.php");

?>

<style>

/* =====================================================
   DONOR LOGIN PAGE
===================================================== */

.donor-login-page {

    min-height: 100vh;

    padding: 70px 0;

    background:
        radial-gradient(
            circle at 10% 20%,
            rgba(220,53,69,0.08),
            transparent 30%
        ),
        radial-gradient(
            circle at 90% 80%,
            rgba(220,53,69,0.08),
            transparent 30%
        ),
        #fff7f8;

}


/* =====================================================
   MAIN CARD
===================================================== */

.donor-login-card {

    border: none;

    border-radius: 25px;

    overflow: hidden;

    background: #fff;

    box-shadow:
        0 20px 60px rgba(0,0,0,0.10);

}


/* =====================================================
   LEFT PANEL
===================================================== */

.donor-login-info {

    position: relative;

    min-height: 540px;

    padding: 50px 40px;

    color: white;

    overflow: hidden;

    background:
        linear-gradient(
            145deg,
            #dc3545,
            #a7192c
        );

}


.donor-login-info::before {

    content: "";

    position: absolute;

    width: 310px;
    height: 310px;

    border-radius: 50%;

    background:
        rgba(255,255,255,0.08);

    top: -130px;

    right: -120px;

}


.donor-login-info::after {

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


.donor-login-info-content {

    position: relative;

    z-index: 2;

}


/* =====================================================
   ICON
===================================================== */

.donor-login-icon {

    width: 85px;
    height: 85px;

    display: flex;

    align-items: center;
    justify-content: center;

    border-radius: 25px;

    background:
        rgba(255,255,255,0.16);

    border:
        1px solid
        rgba(255,255,255,0.25);

    font-size: 35px;

    margin-bottom: 28px;

    animation:
        donorPulse 2.5s infinite;

}


@keyframes donorPulse {

    0% {

        box-shadow:
            0 0 0 0
            rgba(255,255,255,0.25);

    }

    70% {

        box-shadow:
            0 0 0 14px
            rgba(255,255,255,0);

    }

    100% {

        box-shadow:
            0 0 0 0
            rgba(255,255,255,0);

    }

}


/* =====================================================
   TEXT
===================================================== */

.donor-login-info h2 {

    font-weight: 800;

    line-height: 1.2;

}


.donor-login-info h2 span {

    color: #ffe1e4;

}


.donor-login-info p {

    color:
        rgba(255,255,255,0.86);

    line-height: 1.7;

}


/* =====================================================
   FEATURES
===================================================== */

.donor-feature-list {

    margin-top: 35px;

}


.donor-feature {

    display: flex;

    align-items: center;

    gap: 13px;

    margin-bottom: 18px;

    font-size: 14px;

}


.donor-feature-icon {

    width: 36px;
    height: 36px;

    flex-shrink: 0;

    display: flex;

    align-items: center;
    justify-content: center;

    border-radius: 10px;

    background:
        rgba(255,255,255,0.15);

}


/* =====================================================
   RIGHT FORM
===================================================== */

.donor-login-form {

    padding: 55px 50px;

}


.login-heading {

    margin-bottom: 35px;

}


.login-heading h2 {

    font-weight: 800;

    color: #222;

    margin-bottom: 8px;

}


.login-heading p {

    color: #777;

    margin: 0;

}


/* =====================================================
   FORM GROUP
===================================================== */

.login-form-group {

    position: relative;

    margin-bottom: 22px;

}


.login-form-group label {

    display: block;

    font-size: 13px;

    font-weight: 700;

    color: #444;

    margin-bottom: 8px;

}


.login-input-wrapper {

    position: relative;

}


.login-input-icon {

    position: absolute;

    left: 15px;

    top: 50%;

    transform:
        translateY(-50%);

    color: #dc3545;

    z-index: 2;

}


.login-input {

    width: 100%;

    height: 52px;

    border:
        1px solid #e1e5eb;

    border-radius: 13px;

    padding:
        0 45px;

    outline: none;

    background: #fffafb;

    font-size: 14px;

    transition:
        all 0.25s ease;

}


.login-input:focus {

    border-color: #dc3545;

    background: #fff;

    box-shadow:
        0 0 0 4px
        rgba(220,53,69,0.10);

}


/* =====================================================
   PASSWORD TOGGLE
===================================================== */

.password-toggle {

    position: absolute;

    right: 14px;

    top: 50%;

    transform:
        translateY(-50%);

    border: none;

    background: transparent;

    color: #888;

    cursor: pointer;

    font-size: 15px;

}


.password-toggle:hover {

    color: #dc3545;

}


/* =====================================================
   FORGOT PASSWORD
===================================================== */

.forgot-password {

    text-align: right;

    margin-top: -8px;

    margin-bottom: 25px;

}


.forgot-password a {

    color: #dc3545;

    font-size: 13px;

    font-weight: 600;

    text-decoration: none;

}


.forgot-password a:hover {

    text-decoration: underline;

}


/* =====================================================
   LOGIN BUTTON
===================================================== */

.donor-login-btn {

    width: 100%;

    height: 52px;

    border: none;

    border-radius: 13px;

    background:
        linear-gradient(
            135deg,
            #dc3545,
            #a7192c
        );

    color: #fff;

    font-size: 15px;

    font-weight: 700;

    cursor: pointer;

    box-shadow:
        0 8px 20px
        rgba(220,53,69,0.25);

    transition:
        all 0.3s ease;

}


.donor-login-btn:hover {

    transform:
        translateY(-2px);

    box-shadow:
        0 12px 27px
        rgba(220,53,69,0.35);

}


/* =====================================================
   DIVIDER
===================================================== */

.login-divider {

    display: flex;

    align-items: center;

    gap: 12px;

    margin: 28px 0;

    color: #aaa;

    font-size: 12px;

}


.login-divider::before,
.login-divider::after {

    content: "";

    flex: 1;

    height: 1px;

    background: #e5e7eb;

}


/* =====================================================
   REGISTER
===================================================== */

.donor-register-link {

    text-align: center;

    color: #777;

    font-size: 14px;

}


.donor-register-link a {

    color: #dc3545;

    font-weight: 700;

    text-decoration: none;

}


.donor-register-link a:hover {

    text-decoration: underline;

}


/* =====================================================
   SECURITY
===================================================== */

.security-badge {

    display: flex;

    align-items: center;

    justify-content: center;

    gap: 7px;

    margin-top: 25px;

    color: #777;

    font-size: 11px;

}


.security-badge i {

    color: #198754;

}


/* =====================================================
   BLOOD DROP DECORATION
===================================================== */

.blood-decoration {

    position: absolute;

    right: 35px;

    bottom: 35px;

    font-size: 55px;

    color:
        rgba(255,255,255,0.10);

    transform:
        rotate(-10deg);

}


/* =====================================================
   MOBILE
===================================================== */

@media(max-width: 991px) {

    .donor-login-info {

        min-height: auto;

        padding: 40px 30px;

    }

    .donor-login-form {

        padding: 40px 30px;

    }

}


@media(max-width: 576px) {

    .donor-login-page {

        padding: 35px 10px;

    }

    .donor-login-card {

        border-radius: 18px;

    }

    .donor-login-info {

        padding: 30px 22px;

    }

    .donor-login-form {

        padding: 35px 20px;

    }

    .donor-login-info h2 {

        font-size: 25px;

    }

}

</style>


<!-- =====================================================
     DONOR LOGIN
===================================================== -->

<div class="donor-login-page">

<div class="container">

<div class="row justify-content-center">

<div class="col-xl-10">

<div class="donor-login-card">

<div class="row g-0">


<!-- =================================================
     LEFT PANEL
================================================= -->

<div class="col-lg-5">

<div class="donor-login-info">

<div class="donor-login-info-content">


<div class="donor-login-icon">

<i class="fa-solid fa-droplet"></i>

</div>


<h2>

Welcome Back,

<br>

<span>Blood Hero!</span>

</h2>


<p class="mt-3">

Login to your Blood Donor Network
account and continue your journey
of saving lives.

</p>


<div class="donor-feature-list">


<div class="donor-feature">

<div class="donor-feature-icon">

<i class="fa-solid fa-heart-pulse"></i>

</div>

<span>
Help save lives through donation
</span>

</div>


<div class="donor-feature">

<div class="donor-feature-icon">

<i class="fa-solid fa-droplet"></i>

</div>

<span>
Manage your blood group profile
</span>

</div>


<div class="donor-feature">

<div class="donor-feature-icon">

<i class="fa-solid fa-location-dot"></i>

</div>

<span>
Connect with nearby recipients
</span>

</div>


<div class="donor-feature">

<div class="donor-feature-icon">

<i class="fa-solid fa-bell"></i>

</div>

<span>
Stay updated about blood requests
</span>

</div>


<div class="donor-feature">

<div class="donor-feature-icon">

<i class="fa-solid fa-shield-heart"></i>

</div>

<span>
Secure donor account
</span>

</div>


</div>


</div>


<div class="blood-decoration">

<i class="fa-solid fa-droplet"></i>

</div>


</div>

</div>


<!-- =================================================
     RIGHT FORM
================================================= -->

<div class="col-lg-7">

<div class="donor-login-form">


<div class="login-heading">

<h2>

Donor Login

</h2>

<p>

Sign in to continue to your donor account.

</p>

</div>


<form
action="login_process.php"
method="POST"
>


<!-- EMAIL -->

<div class="login-form-group">

<label>

Email Address

</label>


<div class="login-input-wrapper">

<i
class="fa-solid fa-envelope login-input-icon"
></i>


<input
type="email"
name="email"
class="login-input"
placeholder="Enter your email"
autocomplete="email"
required
>

</div>

</div>


<!-- PASSWORD -->

<div class="login-form-group">

<label>

Password

</label>


<div class="login-input-wrapper">

<i
class="fa-solid fa-lock login-input-icon"
></i>


<input
type="password"
name="password"
id="donorPassword"
class="login-input"
placeholder="Enter your password"
autocomplete="current-password"
required
>


<button
type="button"
class="password-toggle"
id="toggleDonorPassword"
aria-label="Show password"
>

<i class="fa-solid fa-eye"></i>

</button>


</div>

</div>


<!-- FORGOT PASSWORD -->

<div class="forgot-password">

<a
href="forgot_password.php"
>

<i class="fa-solid fa-key me-1"></i>

Forgot Password?

</a>

</div>


<!-- LOGIN BUTTON -->

<button
type="submit"
class="donor-login-btn"
>

<i
class="fa-solid fa-right-to-bracket me-2"
></i>

Login to Account

</button>


</form>


<!-- DIVIDER -->

<div class="login-divider">

OR

</div>


<!-- REGISTER -->

<div class="donor-register-link">

Don't have an account?

<a
href="register.php"
>

Become a Donor

</a>

</div>


<!-- SECURITY -->

<div class="security-badge">

<i class="fa-solid fa-shield-halved"></i>

Your login information is protected

</div>


</div>

</div>


</div>

</div>

</div>

</div>

</div>

</div>


<!-- =====================================================
     PASSWORD SHOW / HIDE
===================================================== -->

<script>

document.addEventListener(
    "DOMContentLoaded",
    function() {


        const password =
            document.getElementById(
                "donorPassword"
            );


        const toggle =
            document.getElementById(
                "toggleDonorPassword"
            );


        toggle.addEventListener(
            "click",
            function() {


                if (
                    password.type ===
                    "password"
                ) {


                    password.type =
                        "text";


                    this.innerHTML =
                        '<i class="fa-solid fa-eye-slash"></i>';


                    this.setAttribute(
                        "aria-label",
                        "Hide password"
                    );


                } else {


                    password.type =
                        "password";


                    this.innerHTML =
                        '<i class="fa-solid fa-eye"></i>';


                    this.setAttribute(
                        "aria-label",
                        "Show password"
                    );


                }

            }
        );

    }
);

</script>


<?php

include("../includes/footer.php");

?>