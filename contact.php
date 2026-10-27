<?php

include("includes/db.php");
include("includes/header.php");
include("includes/navbar.php");


// =====================================================
// MESSAGE VARIABLES
// =====================================================

$success = "";
$error = "";


// =====================================================
// FORM SUBMISSION
// =====================================================

if (isset($_POST['send'])) {

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');


    // =================================================
    // VALIDATION
    // =================================================

    if ($name === '' || $email === '' || $subject === '' || $message === '') {

        $error = "Please fill in all fields.";

    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = "Please enter a valid email address.";

    }
    elseif (strlen($message) < 5) {

        $error = "Please enter a meaningful message.";

    }
    else {

        // =============================================
        // INSERT MESSAGE
        // =============================================

        $stmt = $conn->prepare(
            "INSERT INTO contact_messages
            (name, email, subject, message)
            VALUES (?, ?, ?, ?)"
        );

        if ($stmt) {

            $stmt->bind_param(
                "ssss",
                $name,
                $email,
                $subject,
                $message
            );

            if ($stmt->execute()) {

                $success =
                    "Your message has been sent successfully. We will contact you soon.";

                $name = "";
                $email = "";
                $subject = "";
                $message = "";

            }
            else {

                $error =
                    "Unable to send your message. Please try again.";

            }

            $stmt->close();

        }
        else {

            $error =
                "Database error. Please try again later.";

        }

    }

}

?>


<!-- =====================================================
     CONTACT HERO
===================================================== -->

<section class="contact-hero">

    <div class="contact-orb orb-one"></div>
    <div class="contact-orb orb-two"></div>

    <div class="container">

        <div class="contact-hero-inner">

            <div class="contact-badge">

                <i class="fa-solid fa-envelope"></i>

                GET IN TOUCH

            </div>

            <h1>
                We're Here to
                <span>Help.</span>
            </h1>

            <p>
                Have a question, need assistance, or want to learn
                more about our blood donor network?
                Send us a message and we'll be happy to help.
            </p>

            <div class="hero-points">

                <span>
                    <i class="fa-solid fa-circle-check"></i>
                    Quick Response
                </span>

                <span>
                    <i class="fa-solid fa-heart-pulse"></i>
                    Donor Support
                </span>

                <span>
                    <i class="fa-solid fa-shield-heart"></i>
                    Community Focused
                </span>

            </div>

        </div>

    </div>

</section>


<!-- =====================================================
     CONTACT MAIN
===================================================== -->

<section class="contact-section">

    <div class="container">

        <div class="contact-layout">


            <!-- =================================================
                 LEFT INFORMATION
            ================================================= -->

            <div class="contact-info-column">

                <div class="info-label">
                    CONTACT INFORMATION
                </div>

                <h2>
                    Let's Start a
                    <span>Conversation.</span>
                </h2>

                <p class="info-intro">
                    Whether you need help finding a donor, want to
                    register as a donor, or have a question about
                    our platform, our team is here for you.
                </p>


                <!-- LOCATION -->

                <div class="contact-info-card">

                    <div class="contact-info-icon red">

                        <i class="fa-solid fa-location-dot"></i>

                    </div>

                    <div>

                        <small>
                            OUR LOCATION
                        </small>

                        <h4>
                            Himachal Pradesh, India
                        </h4>

                    </div>

                </div>


                <!-- EMAIL -->

                <div class="contact-info-card">

                    <div class="contact-info-icon blue">

                        <i class="fa-solid fa-envelope"></i>

                    </div>

                    <div>

                        <small>
                            EMAIL US
                        </small>

                        <h4>
                            blooddonornetwork.in@gmail.com
                        </h4>

                    </div>

                </div>


                <!-- PHONE -->

                <div class="contact-info-card">

                    <div class="contact-info-icon green">

                        <i class="fa-solid fa-phone"></i>

                    </div>

                    <div>

                        <small>
                            CALL US
                        </small>

                        <h4>
                            +91 7876586338
                        </h4>

                    </div>

                </div>


                <!-- EMERGENCY -->

                <div class="emergency-box">

                    <div class="emergency-top">

                        <div class="emergency-icon">

                            <i class="fa-solid fa-headset"></i>

                        </div>

                        <span>
                            SUPPORT
                        </span>

                    </div>

                    <h3>
                        Need Help Finding a Donor?
                    </h3>

                    <p>
                        Use our donor search to find available
                        donors according to blood group and location.
                    </p>

                    <a href="search.php">

                        Search Donors

                        <i class="fa-solid fa-arrow-right"></i>

                    </a>

                </div>


                <!-- SOCIAL STYLE -->

                <div class="contact-note">

                    <i class="fa-solid fa-heart"></i>

                    <span>
                        Every conversation can help someone
                        take one step closer to saving a life.
                    </span>

                </div>

            </div>


            <!-- =================================================
                 FORM
            ================================================= -->

            <div class="contact-form-column">

                <div class="form-card">

                    <div class="form-top">

                        <div>

                            <span>
                                SEND A MESSAGE
                            </span>

                            <h2>
                                How Can We Help?
                            </h2>

                        </div>

                        <div class="form-top-icon">

                            <i class="fa-solid fa-paper-plane"></i>

                        </div>

                    </div>


                    <p class="form-description">
                        Complete the form below and our team will
                        get back to you as soon as possible.
                    </p>


                    <!-- SUCCESS -->

                    <?php if ($success !== "") { ?>

                        <div class="message-alert success">

                            <i class="fa-solid fa-circle-check"></i>

                            <span>
                                <?= htmlspecialchars($success) ?>
                            </span>

                        </div>

                    <?php } ?>


                    <!-- ERROR -->

                    <?php if ($error !== "") { ?>

                        <div class="message-alert error">

                            <i class="fa-solid fa-circle-exclamation"></i>

                            <span>
                                <?= htmlspecialchars($error) ?>
                            </span>

                        </div>

                    <?php } ?>


                    <form method="POST" action="">

                        <!-- NAME + EMAIL -->

                        <div class="form-row">

                            <div class="form-group">

                                <label for="name">
                                    Your Name
                                </label>

                                <div class="input-box">

                                    <i class="fa-solid fa-user"></i>

                                    <input
                                        type="text"
                                        id="name"
                                        name="name"
                                        placeholder="John Doe"
                                        value="<?= htmlspecialchars($name ?? '') ?>"
                                        maxlength="100"
                                        required
                                    >

                                </div>

                            </div>


                            <div class="form-group">

                                <label for="email">
                                    Email Address
                                </label>

                                <div class="input-box">

                                    <i class="fa-solid fa-envelope"></i>

                                    <input
                                        type="email"
                                        id="email"
                                        name="email"
                                        placeholder="you@example.com"
                                        value="<?= htmlspecialchars($email ?? '') ?>"
                                        maxlength="150"
                                        required
                                    >

                                </div>

                            </div>

                        </div>


                        <!-- SUBJECT -->

                        <div class="form-group">

                            <label for="subject">
                                Subject
                            </label>

                            <div class="input-box">

                                <i class="fa-solid fa-tag"></i>

                                <input
                                    type="text"
                                    id="subject"
                                    name="subject"
                                    placeholder="How can we help you?"
                                    value="<?= htmlspecialchars($subject ?? '') ?>"
                                    maxlength="200"
                                    required
                                >

                            </div>

                        </div>


                        <!-- MESSAGE -->

                        <div class="form-group">

                            <div class="message-label">

                                <label for="message">
                                    Your Message
                                </label>

                                <span>
                                    Max 2000 characters
                                </span>

                            </div>

                            <div class="textarea-box">

                                <i class="fa-solid fa-message"></i>

                                <textarea
                                    id="message"
                                    name="message"
                                    rows="7"
                                    maxlength="2000"
                                    placeholder="Tell us how we can help..."
                                    required
                                ><?= htmlspecialchars($message ?? '') ?></textarea>

                            </div>

                        </div>


                        <!-- SUBMIT -->

                        <button
                            type="submit"
                            name="send"
                            class="submit-btn"
                        >

                            <span>
                                <i class="fa-solid fa-paper-plane"></i>
                                Send Message
                            </span>

                            <i class="fa-solid fa-arrow-right"></i>

                        </button>


                        <div class="form-security">

                            <i class="fa-solid fa-lock"></i>

                            Your information is submitted securely.

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</section>


<!-- =====================================================
     QUICK ACTIONS
===================================================== -->

<section class="quick-section">

    <div class="container">

        <div class="quick-heading">

            <span>
                QUICK ACTIONS
            </span>

            <h2>
                Looking for something specific?
            </h2>

        </div>


        <div class="quick-grid">


            <a href="search.php" class="quick-card">

                <div class="quick-icon red">

                    <i class="fa-solid fa-magnifying-glass"></i>

                </div>

                <div>

                    <h4>
                        Find a Donor
                    </h4>

                    <p>
                        Search available blood donors.
                    </p>

                </div>

                <i class="fa-solid fa-arrow-right arrow"></i>

            </a>


            <a href="donor/register.php" class="quick-card">

                <div class="quick-icon green">

                    <i class="fa-solid fa-hand-holding-droplet"></i>

                </div>

                <div>

                    <h4>
                        Become a Donor
                    </h4>

                    <p>
                        Join our donor network today.
                    </p>

                </div>

                <i class="fa-solid fa-arrow-right arrow"></i>

            </a>


        </div>

    </div>

</section>


<!-- =====================================================
     CONTACT CSS
===================================================== -->

<style>

:root {

    --blood-red: #dc3545;
    --blood-dark: #a7192c;
    --blood-light: #fff1f3;

    --dark: #171717;
    --muted: #737373;

}


/* =====================================================
   HERO
===================================================== */

.contact-hero {

    position: relative;

    padding: 90px 0 110px;

    overflow: hidden;

    color: #fff;

    background:
        radial-gradient(
            circle at 80% 20%,
            rgba(255,255,255,.12),
            transparent 30%
        ),
        linear-gradient(
            135deg,
            #b91c35,
            #dc3545 48%,
            #97172b
        );

}


.contact-orb {

    position: absolute;

    border: 1px solid rgba(255,255,255,.10);

    border-radius: 50%;

    pointer-events: none;

}


.orb-one {

    width: 500px;
    height: 500px;

    right: -220px;
    top: -280px;

}


.orb-two {

    width: 280px;
    height: 280px;

    left: -160px;
    bottom: -200px;

}


.contact-hero-inner {

    position: relative;

    z-index: 2;

    max-width: 760px;

    margin: auto;

    text-align: center;

}


.contact-badge {

    display: inline-flex;

    align-items: center;

    gap: 8px;

    padding: 8px 16px;

    border: 1px solid rgba(255,255,255,.2);

    border-radius: 50px;

    background: rgba(255,255,255,.10);

    font-size: 11px;

    font-weight: 700;

    letter-spacing: 1.6px;

    backdrop-filter: blur(8px);

}


.contact-hero h1 {

    margin: 20px 0 14px;

    font-size: clamp(42px, 6vw, 64px);

    line-height: 1.05;

    font-weight: 800;

    letter-spacing: -2px;

}


.contact-hero h1 span {

    color: #ffd8dd;

}


.contact-hero p {

    max-width: 650px;

    margin: auto;

    color: rgba(255,255,255,.82);

    font-size: 16px;

    line-height: 1.8;

}


.hero-points {

    display: flex;

    flex-wrap: wrap;

    justify-content: center;

    gap: 22px;

    margin-top: 28px;

}


.hero-points span {

    display: flex;

    align-items: center;

    gap: 7px;

    color: rgba(255,255,255,.78);

    font-size: 12px;

}


.hero-points i {

    color: #fff;

}


/* =====================================================
   MAIN SECTION
===================================================== */

.contact-section {

    padding: 85px 0;

    background: #f7f8fa;

}


.contact-layout {

    display: grid;

    grid-template-columns: .9fr 1.1fr;

    gap: 60px;

    align-items: start;

}


/* =====================================================
   INFO
===================================================== */

.contact-info-column {

    padding-top: 15px;

}


.info-label {

    color: var(--blood-red);

    font-size: 11px;

    font-weight: 800;

    letter-spacing: 2px;

}


.contact-info-column > h2 {

    margin: 10px 0 15px;

    font-size: 38px;

    line-height: 1.15;

    font-weight: 800;

    letter-spacing: -1px;

}


.contact-info-column > h2 span {

    color: var(--blood-red);

}


.info-intro {

    max-width: 480px;

    margin-bottom: 30px;

    color: var(--muted);

    font-size: 14px;

    line-height: 1.8;

}


/* =====================================================
   INFO CARDS
===================================================== */

.contact-info-card {

    display: flex;

    align-items: center;

    gap: 15px;

    padding: 15px;

    margin-bottom: 12px;

    border: 1px solid #e9e9e9;

    border-radius: 14px;

    background: #fff;

    transition: .25s;

}


.contact-info-card:hover {

    transform: translateX(5px);

    border-color: #f2c4ca;

    box-shadow:
        0 8px 25px rgba(0,0,0,.05);

}


.contact-info-icon {

    width: 48px;
    height: 48px;

    flex-shrink: 0;

    display: flex;

    align-items: center;
    justify-content: center;

    border-radius: 13px;

}


.contact-info-icon.red {

    color: var(--blood-red);

    background: var(--blood-light);

}


.contact-info-icon.blue {

    color: #3276d3;

    background: #eef5ff;

}


.contact-info-icon.green {

    color: #16885f;

    background: #eaf8f2;

}


.contact-info-card small {

    display: block;

    margin-bottom: 4px;

    color: #999;

    font-size: 9px;

    font-weight: 800;

    letter-spacing: 1.3px;

}


.contact-info-card h4 {

    margin: 0;

    color: #333;

    font-size: 13px;

    font-weight: 650;

    overflow-wrap: anywhere;

}


/* =====================================================
   EMERGENCY BOX
===================================================== */

.emergency-box {

    margin-top: 25px;

    padding: 25px;

    overflow: hidden;

    color: #fff;

    border-radius: 18px;

    background:
        radial-gradient(
            circle at 90% 10%,
            rgba(255,255,255,.12),
            transparent 30%
        ),
        linear-gradient(
            135deg,
            #b91c35,
            #dc3545
        );

    box-shadow:
        0 15px 35px rgba(185,28,53,.18);

}


.emergency-top {

    display: flex;

    align-items: center;

    gap: 10px;

}


.emergency-icon {

    width: 38px;
    height: 38px;

    display: flex;

    align-items: center;
    justify-content: center;

    border-radius: 11px;

    background: rgba(255,255,255,.15);

}


.emergency-top span {

    font-size: 10px;

    font-weight: 800;

    letter-spacing: 1.5px;

    color: rgba(255,255,255,.7);

}


.emergency-box h3 {

    margin: 18px 0 7px;

    font-size: 19px;

    font-weight: 750;

}


.emergency-box p {

    margin: 0 0 18px;

    color: rgba(255,255,255,.78);

    font-size: 12px;

    line-height: 1.7;

}


.emergency-box a {

    display: inline-flex;

    align-items: center;

    gap: 10px;

    color: #fff;

    text-decoration: none;

    font-size: 12px;

    font-weight: 700;

}


.emergency-box a:hover {

    gap: 14px;

}


/* =====================================================
   NOTE
===================================================== */

.contact-note {

    display: flex;

    align-items: center;

    gap: 10px;

    margin-top: 20px;

    color: #888;

    font-size: 11px;

    line-height: 1.5;

}


.contact-note i {

    color: var(--blood-red);

}


/* =====================================================
   FORM CARD
===================================================== */

.form-card {

    padding: 38px;

    border: 1px solid #e9e9e9;

    border-radius: 22px;

    background: #fff;

    box-shadow:
        0 15px 45px rgba(0,0,0,.07);

}


.form-top {

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 20px;

}


.form-top > div:first-child span {

    color: var(--blood-red);

    font-size: 10px;

    font-weight: 800;

    letter-spacing: 1.8px;

}


.form-top h2 {

    margin: 6px 0 0;

    font-size: 26px;

    font-weight: 800;

}


.form-top-icon {

    width: 52px;
    height: 52px;

    display: flex;

    align-items: center;
    justify-content: center;

    flex-shrink: 0;

    border-radius: 14px;

    color: var(--blood-red);

    background: var(--blood-light);

    font-size: 20px;

}


.form-description {

    margin: 10px 0 25px;

    color: #888;

    font-size: 12px;

    line-height: 1.6;

}


/* =====================================================
   ALERT
===================================================== */

.message-alert {

    display: flex;

    align-items: center;

    gap: 10px;

    padding: 13px 15px;

    margin-bottom: 22px;

    border-radius: 11px;

    font-size: 12px;

}


.message-alert.success {

    color: #146c43;

    background: #dff5e9;

    border: 1px solid #c3e6d2;

}


.message-alert.error {

    color: #b02a37;

    background: #fbe1e4;

    border: 1px solid #f2c6cc;

}


/* =====================================================
   FORM
===================================================== */

.form-row {

    display: grid;

    grid-template-columns: 1fr 1fr;

    gap: 18px;

}


.form-group {

    margin-bottom: 18px;

}


.form-group label {

    display: block;

    margin-bottom: 7px;

    color: #363636;

    font-size: 12px;

    font-weight: 700;

}


.input-box,
.textarea-box {

    position: relative;

}


.input-box > i,
.textarea-box > i {

    position: absolute;

    color: var(--blood-red);

    font-size: 13px;

}


.input-box > i {

    left: 15px;

    top: 50%;

    transform: translateY(-50%);

}


.textarea-box > i {

    left: 15px;

    top: 16px;

}


.input-box input,
.textarea-box textarea {

    width: 100%;

    border: 1px solid #e3e6e9;

    outline: none;

    color: #333;

    background: #fafbfc;

    transition: .2s;

}


.input-box input {

    height: 47px;

    padding: 0 15px 0 40px;

    border-radius: 10px;

    font-size: 13px;

}


.textarea-box textarea {

    min-height: 160px;

    padding: 13px 15px 13px 40px;

    resize: vertical;

    border-radius: 10px;

    font-size: 13px;

    line-height: 1.6;

}


.input-box input:focus,
.textarea-box textarea:focus {

    border-color: var(--blood-red);

    background: #fff;

    box-shadow:
        0 0 0 4px rgba(220,53,69,.07);

}


.message-label {

    display: flex;

    justify-content: space-between;

    align-items: center;

}


.message-label span {

    color: #aaa;

    font-size: 9px;

}


/* =====================================================
   BUTTON
===================================================== */

.submit-btn {

    width: 100%;

    display: flex;

    align-items: center;

    justify-content: space-between;

    padding: 14px 18px;

    border: none;

    border-radius: 11px;

    color: #fff;

    background:
        linear-gradient(
            135deg,
            #dc3545,
            #b91c35
        );

    font-size: 13px;

    font-weight: 700;

    cursor: pointer;

    transition: .25s;

}


.submit-btn span {

    display: flex;

    align-items: center;

    gap: 9px;

}


.submit-btn:hover {

    transform: translateY(-2px);

    box-shadow:
        0 10px 25px rgba(220,53,69,.25);

}


.form-security {

    display: flex;

    align-items: center;

    justify-content: center;

    gap: 6px;

    margin-top: 15px;

    color: #aaa;

    font-size: 9px;

}


/* =====================================================
   QUICK SECTION
===================================================== */

.quick-section {

    padding: 75px 0;

    background: #fff;

}


.quick-heading {

    margin-bottom: 25px;

}


.quick-heading span {

    color: var(--blood-red);

    font-size: 10px;

    font-weight: 800;

    letter-spacing: 1.8px;

}


.quick-heading h2 {

    margin: 6px 0 0;

    font-size: 25px;

    font-weight: 800;

}


.quick-grid {

    display: grid;

    grid-template-columns: 1fr 1fr;

    gap: 18px;

}


.quick-card {

    display: flex;

    align-items: center;

    gap: 15px;

    padding: 20px;

    border: 1px solid #eee;

    border-radius: 16px;

    color: #333;

    text-decoration: none;

    background: #fff;

    transition: .3s;

}


.quick-card:hover {

    color: #333;

    transform: translateY(-5px);

    box-shadow:
        0 15px 35px rgba(0,0,0,.07);

}


.quick-icon {

    width: 50px;
    height: 50px;

    display: flex;

    align-items: center;
    justify-content: center;

    flex-shrink: 0;

    border-radius: 13px;

}


.quick-icon.red {

    color: var(--blood-red);

    background: var(--blood-light);

}


.quick-icon.green {

    color: #16885f;

    background: #eaf8f2;

}


.quick-card h4 {

    margin: 0 0 4px;

    font-size: 15px;

    font-weight: 750;

}


.quick-card p {

    margin: 0;

    color: #888;

    font-size: 11px;

}


.quick-card .arrow {

    margin-left: auto;

    color: #bbb;

    transition: .2s;

}


.quick-card:hover .arrow {

    color: var(--blood-red);

    transform: translateX(4px);

}


/* =====================================================
   RESPONSIVE
===================================================== */

@media (max-width: 991px) {

    .contact-layout {

        grid-template-columns: 1fr;

        gap: 45px;

    }

    .contact-info-column {

        max-width: 750px;

    }

}


@media (max-width: 767px) {

    .contact-hero {

        padding: 65px 0 80px;

    }

    .contact-hero h1 {

        font-size: 42px;

        letter-spacing: -1px;

    }

    .contact-hero p {

        font-size: 14px;

    }

    .hero-points {

        flex-direction: column;

        align-items: center;

        gap: 10px;

    }

    .contact-section {

        padding: 55px 0;

    }

    .contact-info-column > h2 {

        font-size: 31px;

    }

    .form-card {

        padding: 25px 20px;

        border-radius: 18px;

    }

    .form-row {

        grid-template-columns: 1fr;

        gap: 0;

    }

    .form-top h2 {

        font-size: 22px;

    }

    .quick-grid {

        grid-template-columns: 1fr;

    }

}


@media (max-width: 480px) {

    .contact-hero {

        padding: 55px 0 70px;

    }

    .contact-hero h1 {

        font-size: 36px;

    }

    .contact-info-card h4 {

        font-size: 12px;

    }

    .form-top-icon {

        width: 44px;
        height: 44px;

        font-size: 17px;

    }

    .emergency-box {

        padding: 20px;

    }

    .quick-card {

        padding: 16px;

    }

}

</style>


<?php

include("includes/footer.php");

?>