<?php

include("../includes/header.php");
include("../includes/navbar.php");

?>

<style>

/* =====================================================
   RECIPIENT LOGIN PAGE
===================================================== */

.recipient-login-page {

    min-height: 100vh;

    padding: 70px 0;

    background:
        radial-gradient(
            circle at 10% 20%,
            rgba(13,110,253,0.08),
            transparent 30%
        ),
        radial-gradient(
            circle at 90% 80%,
            rgba(13,110,253,0.08),
            transparent 30%
        ),
        #f5f8fc;

}


/* =====================================================
   MAIN CARD
===================================================== */

.recipient-login-card {

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

.recipient-login-info {

    position: relative;

    min-height: 520px;

    padding: 50px 40px;

    color: white;

    overflow: hidden;

    background:
        linear-gradient(
            145deg,
            #0d6efd,
            #084298
        );

}


.recipient-login-info::before {

    content: "";

    position: absolute;

    width: 300px;
    height: 300px;

    border-radius: 50%;

    background:
        rgba(255,255,255,0.08);

    top: -120px;

    right: -120px;

}


.recipient-login-info::after {

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


.recipient-login-info-content {

    position: relative;

    z-index: 2;

}


/* =====================================================
   ICON
===================================================== */

.recipient-login-icon {

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

}


.recipient-login-info h2 {

    font-weight: 800;

    line-height: 1.2;

}


.recipient-login-info p {

    color:
        rgba(255,255,255,0.85);

    line-height: 1.7;

}


/* =====================================================
   FEATURES
===================================================== */

.login-feature-list {

    margin-top: 35px;

}


.login-feature {

    display: flex;

    align-items: center;

    gap: 13px;

    margin-bottom: 18px;

    font-size: 14px;

}


.login-feature-icon {

    width: 36px;
    height: 36px;

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

.recipient-login-form {

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
   FORM INPUT
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

    color: #0d6efd;

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

    background: #f9fafc;

    font-size: 14px;

    transition:
        all 0.25s ease;

}


.login-input:focus {

    border-color: #0d6efd;

    background: #fff;

    box-shadow:
        0 0 0 4px
        rgba(13,110,253,0.10);

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

    color: #0d6efd;

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

    color: #0d6efd;

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

.recipient-login-btn {

    width: 100%;

    height: 52px;

    border: none;

    border-radius: 13px;

    background:
        linear-gradient(
            135deg,
            #0d6efd,
            #084298
        );

    color: #fff;

    font-size: 15px;

    font-weight: 700;

    cursor: pointer;

    box-shadow:
        0 8px 20px
        rgba(13,110,253,0.25);

    transition:
        all 0.3s ease;

}


.recipient-login-btn:hover {

    transform:
        translateY(-2px);

    box-shadow:
        0 12px 27px
        rgba(13,110,253,0.35);

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

.recipient-register-link {

    text-align: center;

    color: #777;

    font-size: 14px;

}


.recipient-register-link a {

    color: #0d6efd;

    font-weight: 700;

    text-decoration: none;

}


.recipient-register-link a:hover {

    text-decoration: underline;

}


/* =====================================================
   SECURITY BADGE
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
   MOBILE
===================================================== */

@media(max-width: 991px) {

    .recipient-login-info {

        min-height: auto;

        padding: 40px 30px;

    }

    .recipient-login-form {

        padding: 40px 30px;

    }

}


@media(max-width: 576px) {

    .recipient-login-page {

        padding: 35px 10px;

    }

    .recipient-login-card {

        border-radius: 18px;

    }

    .recipient-login-info {

        padding: 30px 22px;

    }

    .recipient-login-form {

        padding: 35px 20px;

    }

    .recipient-login-info h2 {

        font-size: 25px;

    }

}

</style>


<!-- =====================================================
     LOGIN PAGE
===================================================== -->

<div class="recipient-login-page">

<div class="container">

<div class="row justify-content-center">

<div class="col-xl-10">

<div class="recipient-login-card">

<div class="row g-0">


<!-- =================================================
     LEFT INFORMATION
================================================= -->

<div class="col-lg-5">

<div class="recipient-login-info">

<div class="recipient-login-info-content">


<div class="recipient-login-icon">

<i class="fa-solid fa-right-to-bracket"></i>

</div>


<h2>

Welcome Back!

<br>

<span>
Recipient
</span>

</h2>


<p class="mt-3">

Login to your Blood Donor Network
account and manage your blood
requirements easily.

</p>


<div class="login-feature-list">


<div class="login-feature">

<div class="login-feature-icon">

<i class="fa-solid fa-droplet"></i>

</div>

<span>
Find matching blood donors
</span>

</div>


<div class="login-feature">

<div class="login-feature-icon">

<i class="fa-solid fa-location-dot"></i>

</div>

<span>
Search donors by location
</span>

</div>


<div class="login-feature">

<div class="login-feature-icon">

<i class="fa-solid fa-file-medical"></i>

</div>

<span>
Manage your blood requests
</span>

</div>


<div class="login-feature">

<div class="login-feature-icon">

<i class="fa-solid fa-shield-heart"></i>

</div>

<span>
Secure recipient account
</span>

</div>


</div>


</div>

</div>

</div>


<!-- =================================================
     LOGIN FORM
================================================= -->

<div class="col-lg-7">

<div class="recipient-login-form">


<div class="login-heading">

<h2>
Recipient Login
</h2>

<p>
Sign in to continue to your account.
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
id="recipientPassword"
class="login-input"
placeholder="Enter your password"
autocomplete="current-password"
required
>

<button
type="button"
class="password-toggle"
id="togglePassword"
aria-label="Show password"
>

<i class="fa-solid fa-eye"></i>

</button>

</div>

</div>


<!-- FORGOT PASSWORD -->

<div class="forgot-password">

<a href="forgot_password.php">

<i class="fa-solid fa-key me-1"></i>

Forgot Password?

</a>

</div>


<!-- LOGIN -->

<button
type="submit"
class="recipient-login-btn"
>

<i class="fa-solid fa-right-to-bracket me-2"></i>

Login to Account

</button>


</form>


<!-- DIVIDER -->

<div class="login-divider">

OR

</div>


<!-- REGISTER -->

<div class="recipient-register-link">

Don't have an account?

<a href="register.php">

Create Account

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


<script>

document.addEventListener(
    "DOMContentLoaded",
    function() {

        const password =
            document.getElementById(
                "recipientPassword"
            );

        const toggle =
            document.getElementById(
                "togglePassword"
            );


        toggle.addEventListener(
            "click",
            function() {

                if (
                    password.type === "password"
                ) {

                    password.type = "text";

                    this.innerHTML =
                        '<i class="fa-solid fa-eye-slash"></i>';

                    this.setAttribute(
                        "aria-label",
                        "Hide password"
                    );

                } else {

                    password.type = "password";

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