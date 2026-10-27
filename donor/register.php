
<?php
include("../includes/header.php");
include("../includes/navbar.php");
?>

<style>

/* ================================
   DONOR REGISTER PAGE
================================ */

.donor-page {
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


/* Main Card */

.donor-card {
    border: none;
    border-radius: 25px;
    overflow: hidden;

    background: #fff;

    box-shadow:
        0 20px 60px rgba(0,0,0,0.10);
}


/* ==========================================
   LEFT INFORMATION PANEL
========================================== */

.donor-info {
    position: relative;

    min-height: 100%;

    padding: 45px 35px;

    color: white;

    background:
        linear-gradient(
            145deg,
            #dc3545,
            #b91c31,
            #7f1020
        );

    overflow: hidden;
}


.donor-info::before {
    content: "";

    position: absolute;

    width: 260px;
    height: 260px;

    border-radius: 50%;

    background: rgba(255,255,255,0.08);

    top: -100px;
    right: -100px;
}


.donor-info::after {
    content: "";

    position: absolute;

    width: 180px;
    height: 180px;

    border-radius: 50%;

    background: rgba(255,255,255,0.06);

    bottom: -70px;
    left: -70px;
}


.donor-info-content {
    position: relative;

    z-index: 2;
}


/* Donor Icon */

.donor-icon {
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


.donor-info h2 {
    font-weight: 800;

    line-height: 1.2;
}


.donor-info p {
    color: rgba(255,255,255,0.85);

    line-height: 1.7;
}


/* Benefits */

.donor-benefits {
    margin-top: 35px;
}


.donor-benefit {
    display: flex;
    align-items: center;

    gap: 12px;

    margin-bottom: 18px;

    font-size: 14px;
}


.donor-benefit i {
    width: 34px;
    height: 34px;

    flex-shrink: 0;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 10px;

    background: rgba(255,255,255,0.15);
}


/* ==========================================
   RIGHT FORM
========================================== */

.donor-form {
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


/* ==========================================
   FORM GROUPS
========================================== */

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

    color: #dc3545;

    font-size: 15px;

    z-index: 2;
}


/* Inputs */

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

    resize: vertical;
}


.form-control-custom:focus,
.form-select-custom:focus {

    border-color: #dc3545;

    background: #fff;

    box-shadow:
        0 0 0 4px rgba(220,53,69,0.10);
}


/* ==========================================
   ELIGIBILITY BOX
========================================== */

.donor-eligibility {

    display: flex;

    align-items: center;

    gap: 12px;

    padding: 13px 15px;

    margin-bottom: 25px;

    border-radius: 13px;

    border: 1px solid #ffd7dc;

    background: #fff6f7;
}


.donor-eligibility-icon {

    width: 38px;
    height: 38px;

    flex-shrink: 0;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 10px;

    color: #fff;

    background: #dc3545;
}


.donor-eligibility strong {

    display: block;

    color: #333;

    font-size: 13px;
}


.donor-eligibility small {

    color: #777;

    font-size: 11px;
}


/* ==========================================
   PASSWORD
========================================== */

.password-wrapper {

    position: relative;
}


.password-wrapper .form-control-custom {

    padding-right: 45px;
}


.password-toggle {

    position: absolute;

    right: 10px;
    bottom: 7px;

    width: 34px;
    height: 34px;

    border: none;

    border-radius: 8px;

    background: transparent;

    color: #888;

    cursor: pointer;

    z-index: 3;

    transition: all .2s ease;
}


.password-toggle:hover {

    color: #dc3545;

    background: #fff0f2;
}


.password-hint {

    font-size: 11px;

    color: #999;

    margin-top: 5px;
}


#passwordMessage {

    display: block;

    min-height: 16px;

    margin-top: 5px;

    font-size: 11px;

    font-weight: 600;
}


/* ==========================================
   REGISTER BUTTON
========================================== */

.donor-register-btn {

    width: 100%;

    height: 52px;

    border: none;

    border-radius: 13px;

    background:
        linear-gradient(
            135deg,
            #dc3545,
            #8f1728
        );

    color: white;

    font-weight: 700;

    font-size: 15px;

    box-shadow:
        0 8px 20px rgba(220,53,69,0.25);

    transition: all 0.3s ease;

    cursor: pointer;
}


.donor-register-btn:hover {

    transform: translateY(-2px);

    color: white;

    box-shadow:
        0 12px 25px rgba(220,53,69,0.35);
}


/* ==========================================
   LOGIN
========================================== */

.donor-login {

    text-align: center;

    margin-top: 25px;

    color: #777;

    font-size: 14px;
}


.donor-login a {

    color: #dc3545;

    font-weight: 700;

    text-decoration: none;
}


.donor-login a:hover {

    text-decoration: underline;
}


/* ==========================================
   MOBILE
========================================== */

@media(max-width: 991px) {

    .donor-info {

        min-height: auto;

        padding: 35px 30px;
    }


    .donor-benefits {

        margin-top: 25px;
    }


    .donor-form {

        padding: 35px 25px;
    }

}


@media(max-width: 576px) {

    .donor-page {

        padding: 35px 10px;
    }


    .donor-card {

        border-radius: 18px;
    }


    .donor-info {

        padding: 30px 22px;
    }


    .donor-form {

        padding: 30px 18px;
    }


    .donor-info h2 {

        font-size: 25px;
    }

}

</style>


<div class="donor-page">

<div class="container">

<div class="row justify-content-center">

<div class="col-xl-10">

<div class="donor-card">

<div class="row g-0">


<!-- ==========================================
     LEFT INFORMATION PANEL
========================================== -->

<div class="col-lg-5">

<div class="donor-info">

<div class="donor-info-content">


<div class="donor-icon">

<i class="fa-solid fa-hand-holding-droplet"></i>

</div>


<h2>

Become a Blood Donor.
<br>
Save a Life.

</h2>


<p class="mt-3">

Your blood donation can make a real
difference during emergencies.
Register today and help connect
patients with life-saving blood.

</p>


<div class="donor-benefits">


<div class="donor-benefit">

<i class="fa-solid fa-heart-pulse"></i>

<span>
Help save lives during emergencies
</span>

</div>


<div class="donor-benefit">

<i class="fa-solid fa-droplet"></i>

<span>
Choose your blood group
</span>

</div>


<div class="donor-benefit">

<i class="fa-solid fa-location-dot"></i>

<span>
Connect with recipients near you
</span>

</div>


<div class="donor-benefit">

<i class="fa-solid fa-clock"></i>

<span>
Set your current availability
</span>

</div>


<div class="donor-benefit">

<i class="fa-solid fa-shield-heart"></i>

<span>
Secure donor registration
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

<div class="donor-form">


<div class="form-title">

<h2>
Create Donor Account
</h2>

<p class="mb-0">
Register yourself as a blood donor and help save lives.
</p>

</div>


<!-- ELIGIBILITY -->

<div class="donor-eligibility">

<div class="donor-eligibility-icon">

<i class="fa-solid fa-heart-pulse"></i>

</div>

<div>

<strong>
Donor Eligibility
</strong>

<small>
Donors should generally be 18–65 years old.
Please follow your local blood-bank guidelines.
</small>

</div>

</div>


<form
action="register_process.php"
method="POST"
id="donorRegisterForm"
>


<div class="row">


<!-- NAME -->

<div class="col-md-6">

<div class="form-group-custom">

<label>

Full Name
<span class="text-danger">*</span>

</label>

<i class="fa-solid fa-user"></i>

<input
type="text"
name="name"
class="form-control-custom"
placeholder="Enter your full name"
required
autocomplete="name"
>

</div>

</div>


<!-- EMAIL -->

<div class="col-md-6">

<div class="form-group-custom">

<label>

Email Address
<span class="text-danger">*</span>

</label>

<i class="fa-solid fa-envelope"></i>

<input
type="email"
name="email"
class="form-control-custom"
placeholder="Enter your email"
required
autocomplete="email"
>

</div>

</div>


<!-- PHONE -->

<div class="col-md-6">

<div class="form-group-custom">

<label>

Phone Number
<span class="text-danger">*</span>

</label>

<i class="fa-solid fa-phone"></i>

<input
type="tel"
name="phone"
class="form-control-custom"
placeholder="10 digit phone number"
maxlength="10"
pattern="[0-9]{10}"
inputmode="numeric"
required
autocomplete="tel"
>

</div>

</div>


<!-- AGE -->

<div class="col-md-6">

<div class="form-group-custom">

<label>

Age
<span class="text-danger">*</span>

</label>

<i class="fa-solid fa-cake-candles"></i>

<input
type="number"
name="age"
class="form-control-custom"
placeholder="Enter your age"
min="18"
max="65"
required
>

</div>

</div>


<!-- BLOOD GROUP -->

<div class="col-md-6">

<div class="form-group-custom">

<label>

Blood Group
<span class="text-danger">*</span>

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

<option value="A+">A+</option>

<option value="A-">A-</option>

<option value="B+">B+</option>

<option value="B-">B-</option>

<option value="AB+">AB+</option>

<option value="AB-">AB-</option>

<option value="O+">O+</option>

<option value="O-">O-</option>

</select>

</div>

</div>


<!-- GENDER -->

<div class="col-md-6">

<div class="form-group-custom">

<label>

Gender
<span class="text-danger">*</span>

</label>

<i class="fa-solid fa-venus-mars"></i>

<select
name="gender"
class="form-select-custom"
required
>

<option value="">
Select Gender
</option>

<option value="Male">
Male
</option>

<option value="Female">
Female
</option>

<option value="Other">
Other
</option>

</select>

</div>

</div>


<!-- CITY -->

<div class="col-md-6">

<div class="form-group-custom">

<label>

City
<span class="text-danger">*</span>

</label>

<i class="fa-solid fa-location-dot"></i>

<input
type="text"
name="city"
class="form-control-custom"
placeholder="Enter your city"
required
autocomplete="address-level2"
>

</div>

</div>


<!-- ADDRESS -->

<div class="col-md-6">

<div class="form-group-custom">

<label>

Address
<span class="text-danger">*</span>

</label>

<i class="fa-solid fa-map-location-dot"></i>

<input
type="text"
name="address"
class="form-control-custom"
placeholder="Enter your address"
required
autocomplete="street-address"
>

</div>

</div>


<!-- AVAILABILITY -->

<div class="col-md-6">

<div class="form-group-custom">

<label>

Current Availability
<span class="text-danger">*</span>

</label>

<i class="fa-solid fa-circle-check"></i>

<select
name="availability"
class="form-select-custom"
required
>

<option value="Available">
🟢 Available
</option>

<option value="Unavailable">
🔴 Unavailable
</option>

</select>

</div>

</div>


<!-- PASSWORD -->

<div class="col-md-6">

<div class="form-group-custom">

<label>

Password
<span class="text-danger">*</span>

</label>


<div class="password-wrapper">

<i class="fa-solid fa-lock"></i>

<input
type="password"
name="password"
id="password"
class="form-control-custom"
placeholder="Create password"
required
minlength="6"
autocomplete="new-password"
>


<button
type="button"
class="password-toggle"
onclick="togglePassword('password', this)"
aria-label="Show password"
>

<i class="fa-solid fa-eye"></i>

</button>

</div>


<div class="password-hint">

Minimum 6 characters

</div>

</div>

</div>


<!-- CONFIRM PASSWORD -->

<div class="col-md-6">

<div class="form-group-custom">

<label>

Confirm Password
<span class="text-danger">*</span>

</label>


<div class="password-wrapper">

<i class="fa-solid fa-lock"></i>

<input
type="password"
name="confirm_password"
id="confirm_password"
class="form-control-custom"
placeholder="Confirm password"
required
minlength="6"
autocomplete="new-password"
>


<button
type="button"
class="password-toggle"
onclick="togglePassword('confirm_password', this)"
aria-label="Show password"
>

<i class="fa-solid fa-eye"></i>

</button>

</div>


<div id="passwordMessage"></div>

</div>

</div>


</div>


<!-- BUTTON -->

<div class="mt-2">

<button
type="submit"
class="donor-register-btn"
id="registerButton"
>

<i class="fa-solid fa-hand-holding-droplet me-2"></i>

Create Donor Account

</button>

</div>


</form>


<!-- LOGIN -->

<div class="donor-login">

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


<script>

/* ==========================================
   PASSWORD SHOW / HIDE
========================================== */

function togglePassword(id, button) {

    const input =
        document.getElementById(id);

    const icon =
        button.querySelector("i");


    if (input.type === "password") {

        input.type = "text";

        icon.classList.remove("fa-eye");

        icon.classList.add("fa-eye-slash");

        button.setAttribute(
            "aria-label",
            "Hide password"
        );

    } else {

        input.type = "password";

        icon.classList.remove("fa-eye-slash");

        icon.classList.add("fa-eye");

        button.setAttribute(
            "aria-label",
            "Show password"
        );

    }

}


/* ==========================================
   PASSWORD MATCH
========================================== */

const password =
    document.getElementById("password");

const confirmPassword =
    document.getElementById("confirm_password");

const passwordMessage =
    document.getElementById("passwordMessage");


function checkPassword() {

    if (confirmPassword.value === "") {

        passwordMessage.textContent = "";

        confirmPassword.style.borderColor = "";

        return;

    }


    if (
        password.value ===
        confirmPassword.value
    ) {

        passwordMessage.textContent =
            "✓ Passwords match";

        passwordMessage.style.color =
            "#198754";

        confirmPassword.style.borderColor =
            "#198754";

    } else {

        passwordMessage.textContent =
            "✕ Passwords do not match";

        passwordMessage.style.color =
            "#dc3545";

        confirmPassword.style.borderColor =
            "#dc3545";

    }

}


password.addEventListener(
    "input",
    checkPassword
);

confirmPassword.addEventListener(
    "input",
    checkPassword
);


/* ==========================================
   FORM VALIDATION
========================================== */

document
    .getElementById("donorRegisterForm")
    .addEventListener(
        "submit",
        function(event) {

            if (
                password.value !==
                confirmPassword.value
            ) {

                event.preventDefault();

                passwordMessage.textContent =
                    "✕ Passwords do not match";

                passwordMessage.style.color =
                    "#dc3545";

                confirmPassword.focus();

                return false;

            }

        }
    );


/* ==========================================
   PHONE NUMBER ONLY
========================================== */

const phone =
    document.querySelector(
        'input[name="phone"]'
    );


phone.addEventListener(
    "input",
    function() {

        this.value =
            this.value
                .replace(/\D/g, "")
                .slice(0, 10);

    }
);

</script>


<?php
include("../includes/footer.php");
?>

