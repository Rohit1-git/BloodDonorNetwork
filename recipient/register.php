<?php
include("../includes/header.php");
include("../includes/navbar.php");
?>

<style>

/* ================================
   RECIPIENT REGISTER PAGE
================================ */

.recipient-page {
    min-height: 100vh;
    padding: 70px 0;
    background:
        radial-gradient(circle at 10% 20%, rgba(13,110,253,0.08), transparent 30%),
        radial-gradient(circle at 90% 80%, rgba(13,110,253,0.08), transparent 30%),
        #f5f8fc;
}

/* Main Card */

.recipient-card {
    border: none;
    border-radius: 25px;
    overflow: hidden;
    background: #fff;
    box-shadow: 0 20px 60px rgba(0,0,0,0.10);
}

/* Left Side */

.recipient-info {
    position: relative;
    min-height: 100%;
    padding: 45px 35px;
    color: white;

    background:
        linear-gradient(
            145deg,
            #0d6efd,
            #084298
        );

    overflow: hidden;
}

.recipient-info::before {
    content: "";
    position: absolute;

    width: 260px;
    height: 260px;

    border-radius: 50%;

    background: rgba(255,255,255,0.08);

    top: -100px;
    right: -100px;
}

.recipient-info::after {
    content: "";
    position: absolute;

    width: 180px;
    height: 180px;

    border-radius: 50%;

    background: rgba(255,255,255,0.06);

    bottom: -70px;
    left: -70px;
}

.recipient-info-content {
    position: relative;
    z-index: 2;
}

.recipient-icon {
    width: 80px;
    height: 80px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 24px;

    background: rgba(255,255,255,0.16);

    border: 1px solid rgba(255,255,255,0.25);

    font-size: 34px;

    margin-bottom: 25px;
}

.recipient-info h2 {
    font-weight: 800;
    line-height: 1.2;
}

.recipient-info p {
    color: rgba(255,255,255,0.85);
    line-height: 1.7;
}

/* Benefits */

.recipient-benefits {
    margin-top: 35px;
}

.recipient-benefit {
    display: flex;
    align-items: center;
    gap: 12px;

    margin-bottom: 18px;

    font-size: 14px;
}

.recipient-benefit i {
    width: 34px;
    height: 34px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 10px;

    background: rgba(255,255,255,0.15);
}

/* Right Side */

.recipient-form {
    padding: 45px;
}

.form-title {
    margin-bottom: 30px;
}

.form-title h2 {
    font-weight: 800;
    color: #222;
}

.form-title p {
    color: #777;
}

/* Form Groups */

.form-group-custom {
    position: relative;
    margin-bottom: 20px;
}

.form-group-custom label {
    display: block;

    font-size: 13px;
    font-weight: 700;

    color: #444;

    margin-bottom: 7px;
}

.form-group-custom i {
    position: absolute;

    left: 14px;
    bottom: 13px;

    color: #0d6efd;

    font-size: 15px;

    z-index: 2;
}

.form-control-custom,
.form-select-custom {
    width: 100%;

    height: 48px;

    border: 1px solid #e1e5eb;

    border-radius: 12px;

    padding: 0 15px 0 42px;

    outline: none;

    background: #f9fafc;

    font-size: 14px;

    transition: all 0.25s ease;
}

textarea.form-control-custom {
    height: auto;
    min-height: 100px;

    padding-top: 13px;
}

.form-control-custom:focus,
.form-select-custom:focus {
    border-color: #0d6efd;

    background: #fff;

    box-shadow:
        0 0 0 4px rgba(13,110,253,0.10);
}

/* Register Button */

.recipient-register-btn {
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

    color: white;

    font-weight: 700;

    font-size: 15px;

    box-shadow:
        0 8px 20px rgba(13,110,253,0.25);

    transition: all 0.3s ease;
}

.recipient-register-btn:hover {
    transform: translateY(-2px);

    box-shadow:
        0 12px 25px rgba(13,110,253,0.35);
}

/* Login */

.recipient-login {
    text-align: center;

    margin-top: 25px;

    color: #777;

    font-size: 14px;
}

.recipient-login a {
    color: #0d6efd;

    font-weight: 700;

    text-decoration: none;
}

.recipient-login a:hover {
    text-decoration: underline;
}

/* Password Hint */

.password-hint {
    font-size: 11px;
    color: #999;

    margin-top: 5px;
}

/* Mobile */

@media(max-width: 991px) {

    .recipient-info {
        min-height: auto;

        padding: 35px 30px;
    }

    .recipient-benefits {
        margin-top: 25px;
    }

    .recipient-form {
        padding: 35px 25px;
    }

}

@media(max-width: 576px) {

    .recipient-page {
        padding: 35px 10px;
    }

    .recipient-card {
        border-radius: 18px;
    }

    .recipient-info {
        padding: 30px 22px;
    }

    .recipient-form {
        padding: 30px 18px;
    }

    .recipient-info h2 {
        font-size: 25px;
    }

}

</style>


<div class="recipient-page">

<div class="container">

<div class="row justify-content-center">

<div class="col-xl-10">

<div class="recipient-card">

<div class="row g-0">


<!-- ==========================================
     LEFT INFORMATION PANEL
========================================== -->

<div class="col-lg-5">

<div class="recipient-info">

<div class="recipient-info-content">


<div class="recipient-icon">

<i class="fa-solid fa-hand-holding-droplet"></i>

</div>


<h2>
Need Blood?
<br>
We're Here To Help.
</h2>


<p class="mt-3">

Register as a recipient and connect with
blood donors when you need blood during
an emergency.

</p>


<div class="recipient-benefits">


<div class="recipient-benefit">

<i class="fa-solid fa-user-plus"></i>

<span>
Create your recipient account
</span>

</div>


<div class="recipient-benefit">

<i class="fa-solid fa-droplet"></i>

<span>
Find matching blood donors
</span>

</div>


<div class="recipient-benefit">

<i class="fa-solid fa-location-dot"></i>

<span>
Search donors by city
</span>

</div>


<div class="recipient-benefit">

<i class="fa-solid fa-hospital"></i>

<span>
Connect with hospitals
</span>

</div>


<div class="recipient-benefit">

<i class="fa-solid fa-shield-heart"></i>

<span>
Secure registration
</span>

</div>


</div>


</div>

</div>

</div>


<!-- ==========================================
     RIGHT FORM
========================================== -->

<div class="col-lg-7">

<div class="recipient-form">


<div class="form-title">

<h2>
Create Recipient Account
</h2>

<p class="mb-0">
Enter your details to get started.
</p>

</div>


<form
action="register_process.php"
method="POST"
>


<div class="row">


<!-- NAME -->

<div class="col-md-6">

<div class="form-group-custom">

<label>
Full Name
</label>

<i class="fa-solid fa-user"></i>

<input
type="text"
name="name"
class="form-control-custom"
placeholder="Enter your full name"
required
>

</div>

</div>


<!-- EMAIL -->

<div class="col-md-6">

<div class="form-group-custom">

<label>
Email Address
</label>

<i class="fa-solid fa-envelope"></i>

<input
type="email"
name="email"
class="form-control-custom"
placeholder="Enter your email"
required
>

</div>

</div>


<!-- PHONE -->

<div class="col-md-6">

<div class="form-group-custom">

<label>
Phone Number
</label>

<i class="fa-solid fa-phone"></i>

<input
type="text"
name="phone"
class="form-control-custom"
placeholder="10 digit phone number"
maxlength="10"
pattern="[0-9]{10}"
required
>

</div>

</div>


<!-- BLOOD GROUP -->

<div class="col-md-6">

<div class="form-group-custom">

<label>
Blood Group Required
</label>

<i class="fa-solid fa-droplet"></i>

<select
name="blood_group"
class="form-select-custom"
required
>

<option value="">
Select Blood Group
</option>

<option>A+</option>
<option>A-</option>
<option>B+</option>
<option>B-</option>
<option>AB+</option>
<option>AB-</option>
<option>O+</option>
<option>O-</option>

</select>

</div>

</div>


<!-- CITY -->

<div class="col-md-6">

<div class="form-group-custom">

<label>
City
</label>

<i class="fa-solid fa-location-dot"></i>

<input
type="text"
name="city"
class="form-control-custom"
placeholder="Enter your city"
required
>

</div>

</div>


<!-- HOSPITAL -->

<div class="col-md-6">

<div class="form-group-custom">

<label>
Hospital Name
</label>

<i class="fa-solid fa-hospital"></i>

<input
type="text"
name="hospital"
class="form-control-custom"
placeholder="Enter hospital name"
required
>

</div>

</div>


<!-- ADDRESS -->

<div class="col-12">

<div class="form-group-custom">

<label>
Address
</label>

<i class="fa-solid fa-map-location-dot"></i>

<textarea
name="address"
class="form-control-custom"
placeholder="Enter your complete address"
rows="3"
required
></textarea>

</div>

</div>


<!-- PASSWORD -->

<div class="col-md-6">

<div class="form-group-custom">

<label>
Password
</label>

<i class="fa-solid fa-lock"></i>

<input
type="password"
name="password"
class="form-control-custom"
placeholder="Create password"
required
>

<div class="password-hint">
Minimum 6 characters recommended
</div>

</div>

</div>


<!-- CONFIRM PASSWORD -->

<div class="col-md-6">

<div class="form-group-custom">

<label>
Confirm Password
</label>

<i class="fa-solid fa-lock"></i>

<input
type="password"
name="confirm_password"
class="form-control-custom"
placeholder="Confirm password"
required
>

</div>

</div>


</div>


<!-- BUTTON -->

<div class="mt-2">

<button
type="submit"
class="recipient-register-btn"
>

<i class="fa-solid fa-user-plus me-2"></i>

Create Recipient Account

</button>

</div>


</form>


<!-- LOGIN -->

<div class="recipient-login">

Already registered?

<a href="login.php">
Login Here
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


<?php
include("../includes/footer.php");
?>