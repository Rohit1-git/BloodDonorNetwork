<?php
/*
|--------------------------------------------------------------------------
| BLOOD DONOR NETWORK - HOME PAGE
|--------------------------------------------------------------------------
| InfinityFree-safe version
| Uses __DIR__ for PHP includes.
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/includes/db.php";
require_once __DIR__ . "/includes/header.php";
require_once __DIR__ . "/includes/navbar.php";


/* =====================================================
   STATISTICS
===================================================== */

$donors_count  = 0;
$blood_requests = 0;
$hospitals      = 0;


/* =====================================================
   TOTAL DONORS
===================================================== */

$donorQuery = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM donors"
);

if ($donorQuery) {

    $donorData = mysqli_fetch_assoc($donorQuery);

    $donors_count = (int)($donorData['total'] ?? 0);
}


/* =====================================================
   TOTAL BLOOD REQUESTS
===================================================== */

$tableCheck = mysqli_query(
    $conn,
    "SHOW TABLES LIKE 'blood_requests'"
);

if (
    $tableCheck &&
    mysqli_num_rows($tableCheck) > 0
) {

    $requestQuery = mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total FROM blood_requests"
    );

    if ($requestQuery) {

        $requestData = mysqli_fetch_assoc($requestQuery);

        $blood_requests =
            (int)($requestData['total'] ?? 0);
    }
}


/* =====================================================
   TOTAL HOSPITALS
===================================================== */

$tableCheck = mysqli_query(
    $conn,
    "SHOW TABLES LIKE 'hospitals'"
);

if (
    $tableCheck &&
    mysqli_num_rows($tableCheck) > 0
) {

    $hospitalQuery = mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total FROM hospitals"
    );

    if ($hospitalQuery) {

        $hospitalData =
            mysqli_fetch_assoc($hospitalQuery);

        $hospitals =
            (int)($hospitalData['total'] ?? 0);
    }
}


/* =====================================================
   BLOOD GROUP DATA
===================================================== */

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

$bloodGroupCounts = [];


foreach ($groups as $group) {

    $safeGroup = mysqli_real_escape_string(
        $conn,
        $group
    );

    $groupQuery = mysqli_query(
        $conn,
        "SELECT COUNT(*) AS total
         FROM donors
         WHERE blood_group = '$safeGroup'
         AND availability = 'Available'"
    );

    $count = 0;

    if ($groupQuery) {

        $groupData =
            mysqli_fetch_assoc($groupQuery);

        $count =
            (int)($groupData['total'] ?? 0);
    }

    $bloodGroupCounts[$group] = $count;
}


/* =====================================================
   DONORS BY CITY
===================================================== */

$cityQuery = mysqli_query(
    $conn,
    "SELECT
        city,
        COUNT(*) AS total
     FROM donors
     WHERE availability = 'Available'
     AND city IS NOT NULL
     AND TRIM(city) != ''
     GROUP BY city
     ORDER BY total DESC, city ASC
     LIMIT 8"
);

?>

<main class="modern-home">


<!-- =====================================================
     HERO
===================================================== -->

<section class="modern-hero">

    <div class="hero-glow"></div>

    <div class="container">

        <div class="row align-items-center">

            <!-- LEFT -->

            <div class="col-lg-6">

                <div class="hero-badge">
                    <span class="status-dot"></span>
                    LIVE BLOOD DONOR NETWORK
                </div>

                <h1>
                    Every drop can
                    <span>save a life.</span>
                </h1>

                <p class="hero-text">
                    Find blood donors quickly, request blood
                    during emergencies, or register yourself
                    as a donor and help someone in need.
                </p>

                <div class="hero-buttons">

                    <a
                        href="search.php"
                        class="modern-btn primary-btn"
                    >
                        <i class="fa-solid fa-magnifying-glass"></i>
                        Find a Donor
                    </a>

                    <a
                        href="donor/register.php"
                        class="modern-btn secondary-btn"
                    >
                        <i class="fa-solid fa-droplet"></i>
                        Become a Donor
                    </a>

                </div>

                <div class="hero-trust">

                    <div class="trust-item">
                        <i class="fa-solid fa-circle-check"></i>
                        Verified Donors
                    </div>

                    <div class="trust-item">
                        <i class="fa-solid fa-bolt"></i>
                        Fast Response
                    </div>

                    <div class="trust-item">
                        <i class="fa-solid fa-shield-halved"></i>
                        Secure Network
                    </div>

                </div>

            </div>


            <!-- RIGHT -->

            <div class="col-lg-6">

                <div class="hero-card-wrapper">

                    <div class="hero-card">

                        <div class="hero-card-top">

                            <div>

                                <span class="small-label">
                                    NETWORK STATUS
                                </span>

                                <h3>
                                    <span class="online-dot"></span>
                                    Online
                                </h3>

                            </div>

                            <div class="blood-symbol">

                                <i class="fa-solid fa-droplet"></i>

                            </div>

                        </div>


                        <div class="heartbeat">

                            <svg
                                viewBox="0 0 500 120"
                                preserveAspectRatio="none"
                            >

                                <path
                                    d="M0 60
                                    L90 60
                                    L120 60
                                    L145 20
                                    L175 100
                                    L210 5
                                    L245 110
                                    L275 60
                                    L500 60"
                                />

                            </svg>

                        </div>


                        <div class="hero-stats">

                            <div>

                                <strong>
                                    <?= number_format($donors_count) ?>
                                </strong>

                                <span>
                                    Registered Donors
                                </span>

                            </div>


                            <div>

                                <strong>
                                    <?= number_format($blood_requests) ?>
                                </strong>

                                <span>
                                    Blood Requests
                                </span>

                            </div>

                        </div>

                    </div>

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

        <div class="quick-grid">


            <a
                href="search.php"
                class="quick-card"
            >

                <div class="quick-icon red">

                    <i class="fa-solid fa-magnifying-glass"></i>

                </div>

                <div>

                    <strong>
                        Find Blood
                    </strong>

                    <span>
                        Search available donors
                    </span>

                </div>

                <i class="fa-solid fa-arrow-right arrow"></i>

            </a>



            <a
                href="recipient/register.php"
                class="quick-card emergency"
            >

                <div class="quick-icon">

                    <i class="fa-solid fa-truck-medical"></i>

                </div>

                <div>

                    <strong>
                        Emergency Request
                    </strong>

                    <span>
                        Request blood quickly
                    </span>

                </div>

                <i class="fa-solid fa-arrow-right arrow"></i>

            </a>



            <a
                href="donor/register.php"
                class="quick-card"
            >

                <div class="quick-icon red">

                    <i class="fa-solid fa-hand-holding-droplet"></i>

                </div>

                <div>

                    <strong>
                        Donate Blood
                    </strong>

                    <span>
                        Become a life saver
                    </span>

                </div>

                <i class="fa-solid fa-arrow-right arrow"></i>

            </a>

        </div>

    </div>

</section>



<!-- =====================================================
     NETWORK STATISTICS
===================================================== -->

<section class="stats-section">

    <div class="container">

        <div class="section-heading">

            <div>

                <span class="section-label">
                    OUR NETWORK
                </span>

                <h2>
                    Making help easier to find.
                </h2>

            </div>

            <p>
                Our network connects donors and people
                who need blood when it matters most.
            </p>

        </div>


        <div class="stats-grid">


            <div class="stat-box">

                <div class="stat-icon">
                    <i class="fa-solid fa-users"></i>
                </div>

                <strong>
                    <?= number_format($donors_count) ?>+
                </strong>

                <span>
                    Registered Donors
                </span>

            </div>



            <div class="stat-box">

                <div class="stat-icon">
                    <i class="fa-solid fa-file-medical"></i>
                </div>

                <strong>
                    <?= number_format($blood_requests) ?>+
                </strong>

                <span>
                    Blood Requests
                </span>

            </div>



            <div class="stat-box">

                <div class="stat-icon">
                    <i class="fa-solid fa-hospital"></i>
                </div>

                <strong>
                    <?= number_format($hospitals) ?>+
                </strong>

                <span>
                    Hospitals
                </span>

            </div>



            <div class="stat-box live">

                <div class="stat-icon">
                    <i class="fa-solid fa-headset"></i>
                </div>

                <strong>
                    24/7
                </strong>

                <span>
                    Emergency Support
                </span>

            </div>

        </div>

    </div>

</section>



<!-- =====================================================
     BLOOD GROUPS
===================================================== -->

<section class="blood-section">

    <div class="container">

        <div class="section-heading">

            <div>

                <span class="section-label">
                    BLOOD AVAILABILITY
                </span>

                <h2>
                    Find your blood group.
                </h2>

            </div>

            <a
                href="search.php"
                class="simple-link"
            >
                View all
                <i class="fa-solid fa-arrow-right"></i>
            </a>

        </div>


        <div class="blood-grid">

            <?php foreach ($groups as $group): ?>

                <?php

                $count =
                    $bloodGroupCounts[$group] ?? 0;

                $available =
                    $count > 0;

                ?>

                <a
                    href="search.php?blood_group=<?= urlencode($group) ?>"
                    class="blood-card"
                >

                    <div class="blood-card-header">

                        <span class="blood-type">
                            <?= htmlspecialchars($group) ?>
                        </span>

                        <span
                            class="availability <?= $available ? 'available' : '' ?>"
                        >

                            <?= $available
                                ? 'Available'
                                : 'No donors'
                            ?>

                        </span>

                    </div>


                    <div class="blood-card-middle">

                        <div class="blood-drop">

                            <i class="fa-solid fa-droplet"></i>

                        </div>

                        <div>

                            <strong>
                                <?= $count ?>
                            </strong>

                            <span>
                                Available donors
                            </span>

                        </div>

                    </div>


                    <div class="blood-card-footer">

                        <span>
                            Search <?= htmlspecialchars($group) ?>
                        </span>

                        <i class="fa-solid fa-arrow-up-right-from-square"></i>

                    </div>

                </a>

            <?php endforeach; ?>

        </div>

    </div>

</section>



<!-- =====================================================
     SEARCH CTA
===================================================== -->

<section class="search-section">

    <div class="container">

        <div class="search-panel">

            <div class="search-content">

                <span class="section-label light">
                    NEED BLOOD?
                </span>

                <h2>
                    Find a donor near you.
                </h2>

                <p>
                    Search our donor network by blood group
                    and city to quickly find available donors.
                </p>

                <a
                    href="search.php"
                    class="modern-btn white-btn"
                >

                    Search Donors

                    <i class="fa-solid fa-arrow-right"></i>

                </a>

            </div>


            <div class="search-visual">

                <div class="search-circle circle-one"></div>

                <div class="search-circle circle-two"></div>

                <div class="search-icon">

                    <i class="fa-solid fa-location-dot"></i>

                </div>

            </div>

        </div>

    </div>

</section>



<!-- =====================================================
     CITIES
===================================================== -->

<section class="cities-section">

    <div class="container">

        <div class="section-heading">

            <div>

                <span class="section-label">
                    DONOR NETWORK
                </span>

                <h2>
                    Donors by city.
                </h2>

            </div>

        </div>


        <div class="city-grid">

            <?php

            if (
                $cityQuery &&
                mysqli_num_rows($cityQuery) > 0
            ):

                while (
                    $city =
                    mysqli_fetch_assoc($cityQuery)
                ):

                    $cityName =
                        trim($city['city']);

                    $cityCount =
                        (int)$city['total'];

            ?>

                <a
                    href="search.php?city=<?= urlencode($cityName) ?>"
                    class="city-card"
                >

                    <div class="city-icon">

                        <i class="fa-solid fa-location-dot"></i>

                    </div>

                    <div class="city-content">

                        <strong>
                            <?= htmlspecialchars($cityName) ?>
                        </strong>

                        <span>
                            <?= $cityCount ?>
                            available donors
                        </span>

                    </div>

                    <i class="fa-solid fa-arrow-right city-arrow"></i>

                </a>

            <?php

                endwhile;

            else:

            ?>

                <div class="no-city">

                    <i class="fa-solid fa-location-dot"></i>

                    <h3>
                        No available donors yet
                    </h3>

                    <p>
                        Donor locations will appear here
                        when donors become available.
                    </p>

                </div>

            <?php endif; ?>

        </div>

    </div>

</section>



<!-- =====================================================
     HOW IT WORKS
===================================================== -->

<section class="how-section">

    <div class="container">

        <div class="center-heading">

            <span class="section-label">
                HOW IT WORKS
            </span>

            <h2>
                Simple. Fast. Human.
            </h2>

            <p>
                Three simple steps to connect people
                with the blood they need.
            </p>

        </div>


        <div class="steps-grid">


            <div class="step">

                <div class="step-number">
                    01
                </div>

                <div class="step-icon">

                    <i class="fa-solid fa-user-plus"></i>

                </div>

                <h3>
                    Register
                </h3>

                <p>
                    Create your donor or recipient account
                    with a few simple details.
                </p>

            </div>



            <div class="step">

                <div class="step-number">
                    02
                </div>

                <div class="step-icon">

                    <i class="fa-solid fa-magnifying-glass"></i>

                </div>

                <h3>
                    Find a Match
                </h3>

                <p>
                    Search donors using blood group
                    and location.
                </p>

            </div>



            <div class="step">

                <div class="step-number">
                    03
                </div>

                <div class="step-icon">

                    <i class="fa-solid fa-heart-pulse"></i>

                </div>

                <h3>
                    Make an Impact
                </h3>

                <p>
                    Connect with the right people and
                    help someone in need.
                </p>

            </div>

        </div>

    </div>

</section>



<!-- =====================================================
     FINAL CTA
===================================================== -->

<section class="final-section">

    <div class="container">

        <div class="final-content">

            <div class="final-icon">

                <i class="fa-solid fa-droplet"></i>

            </div>

            <span class="section-label light">
                BE THE DIFFERENCE
            </span>

            <h2>
                Someone needs your blood.
            </h2>

            <p>
                Become part of a community that helps
                connect donors with people who need them.
            </p>

            <div class="final-buttons">

                <a
                    href="donor/register.php"
                    class="modern-btn white-btn"
                >

                    Become a Donor

                    <i class="fa-solid fa-arrow-right"></i>

                </a>


                <a
                    href="search.php"
                    class="modern-btn transparent-btn"
                >
                    Find a Donor
                </a>

            </div>

        </div>

    </div>

</section>



<!-- =====================================================
     AI CHAT BUTTON
===================================================== -->

<button
    id="aiChatButton"
    type="button"
    aria-label="Open AI Blood Assistant"
    title="AI Blood Assistant"
>

    <span class="chat-online"></span>

    <i class="fa-solid fa-robot"></i>

</button>



<!-- =====================================================
     AI CHAT BOX
===================================================== -->

<div id="aiChatBox">

    <div class="ai-chat-header">

        <div class="ai-chat-profile">

            <div class="ai-avatar">

                <i class="fa-solid fa-droplet"></i>

            </div>

            <div>

                <strong>
                    Blood Assistant
                </strong>

                <small>

                    <span></span>

                    Online

                </small>

            </div>

        </div>


        <div class="ai-header-actions">

            <button
                id="aiSoundButton"
                type="button"
                title="Mute assistant voice"
                aria-label="Mute assistant voice"
            >
                <i class="fa-solid fa-volume-high"></i>
            </button>


            <button
                id="aiCloseButton"
                type="button"
                title="Close assistant"
                aria-label="Close assistant"
            >
                <i class="fa-solid fa-xmark"></i>
            </button>

        </div>

    </div>



    <div id="aiMessages">

        <div class="ai-welcome-modern">

            <div class="welcome-avatar">

                <i class="fa-solid fa-robot"></i>

            </div>

            <div>

                <strong>
                    Hi! I'm your Blood Assistant.
                </strong>

                <p>
                    I can help you register,
                    search donors, request blood,
                    or navigate the website.
                </p>

            </div>

        </div>


        <div class="ai-quick-buttons">

            <button
                type="button"
                class="ai-quick-btn"
                data-question="How can I register as a donor?"
            >
                Register as donor
            </button>


            <button
                type="button"
                class="ai-quick-btn"
                data-question="How can I search for a blood donor?"
            >
                Find a donor
            </button>


            <button
                type="button"
                class="ai-quick-btn"
                data-question="How can I request blood?"
            >
                Request blood
            </button>

        </div>

    </div>



    <div id="aiTyping">

        Assistant is typing...

        <div class="typing-dots">

            <i></i>
            <i></i>
            <i></i>

        </div>

    </div>



    <div class="ai-input-area">

        <input
            type="text"
            id="aiInput"
            placeholder="Ask anything..."
            autocomplete="off"
            maxlength="500"
        >


        <button
            type="button"
            id="aiMicButton"
            title="Voice input"
            aria-label="Voice input"
        >

            <i class="fa-solid fa-microphone"></i>

        </button>


        <button
            type="button"
            id="aiSendButton"
            title="Send message"
            aria-label="Send message"
        >

            <i class="fa-solid fa-arrow-up"></i>

        </button>

    </div>

</div>



<style>

/* =====================================================
   GOOGLE FONTS
===================================================== */

@import url(
'https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@500;600;700;800&display=swap'
);


/* =====================================================
   VARIABLES
===================================================== */

:root {

    --red: #d7193f;
    --red-dark: #a80f2d;
    --red-light: #fff1f4;

    --dark: #101318;
    --dark-2: #171b22;

    --white: #ffffff;
    --bg: #f7f8fa;

    --text: #171a20;
    --muted: #707782;

    --border: #e7e9ed;

    --radius: 20px;

    --shadow:
        0 15px 40px rgba(16,19,24,.07);

}


/* =====================================================
   BASE
===================================================== */

.modern-home {

    font-family:
        "DM Sans",
        sans-serif;

    color: var(--text);

    background: var(--bg);

    overflow: hidden;

}


.modern-home a {

    text-decoration: none;

}


.container {

    position: relative;

}


/* =====================================================
   HERO
===================================================== */

.modern-hero {

    position: relative;

    padding:
        100px 0
        120px;

    color: white;

    background:
        radial-gradient(
            circle at 85% 25%,
            rgba(215,25,63,.25),
            transparent 35%
        ),
        var(--dark);

}


.hero-glow {

    position: absolute;

    width: 500px;
    height: 500px;

    right: -250px;
    top: -200px;

    border-radius: 50%;

    background:
        rgba(215,25,63,.15);

    filter: blur(80px);

}


.hero-badge {

    display: inline-flex;

    align-items: center;

    gap: 9px;

    padding: 8px 13px;

    border:
        1px solid
        rgba(255,255,255,.15);

    border-radius: 50px;

    color:
        rgba(255,255,255,.75);

    font-size: 10px;

    font-weight: 700;

    letter-spacing: 1.5px;

    margin-bottom: 25px;

}


.status-dot,
.online-dot {

    width: 7px;
    height: 7px;

    display: inline-block;

    border-radius: 50%;

    background: #39d98a;

    box-shadow:
        0 0 0 5px
        rgba(57,217,138,.12);

}


.modern-hero h1 {

    max-width: 650px;

    margin: 0 0 25px;

    font-family:
        "Manrope",
        sans-serif;

    font-size:
        clamp(45px, 6vw, 75px);

    line-height: 1.02;

    font-weight: 800;

    letter-spacing: -3px;

}


.modern-hero h1 span {

    color: #ff5474;

}


.hero-text {

    max-width: 560px;

    margin-bottom: 32px;

    color:
        rgba(255,255,255,.65);

    font-size: 17px;

    line-height: 1.75;

}


.hero-buttons {

    display: flex;

    flex-wrap: wrap;

    gap: 12px;

}


.modern-btn {

    min-height: 52px;

    display: inline-flex;

    align-items: center;

    justify-content: center;

    gap: 9px;

    padding: 0 21px;

    border-radius: 12px;

    font-size: 13px;

    font-weight: 700;

    transition:
        transform .2s,
        box-shadow .2s,
        background .2s;

}


.modern-btn:hover {

    transform:
        translateY(-3px);

}


.primary-btn {

    color: white;

    background: var(--red);

    box-shadow:
        0 12px 30px
        rgba(215,25,63,.3);

}


.primary-btn:hover {

    color: white;

    background: var(--red-dark);

}


.secondary-btn {

    color: white;

    border:
        1px solid
        rgba(255,255,255,.2);

    background:
        rgba(255,255,255,.05);

}


.secondary-btn:hover {

    color: white;

    background:
        rgba(255,255,255,.1);

}


.hero-trust {

    display: flex;

    flex-wrap: wrap;

    gap: 18px;

    margin-top: 35px;

    padding-top: 25px;

    border-top:
        1px solid
        rgba(255,255,255,.1);

}


.trust-item {

    display: flex;

    align-items: center;

    gap: 7px;

    color:
        rgba(255,255,255,.5);

    font-size: 11px;

}


.trust-item i {

    color: #39d98a;

}


/* =====================================================
   HERO CARD
===================================================== */

.hero-card-wrapper {

    position: relative;

    max-width: 500px;

    margin:
        0 auto;

}


.hero-card {

    padding: 28px;

    border:
        1px solid
        rgba(255,255,255,.1);

    border-radius: 28px;

    background:
        linear-gradient(
            145deg,
            #20252e,
            #111419
        );

    box-shadow:
        0 35px 80px
        rgba(0,0,0,.3);

}


.hero-card-top {

    display: flex;

    align-items: center;

    justify-content: space-between;

}


.small-label {

    display: block;

    color:
        rgba(255,255,255,.4);

    font-size: 9px;

    font-weight: 700;

    letter-spacing: 1.5px;

}


.hero-card h3 {

    display: flex;

    align-items: center;

    gap: 10px;

    margin: 7px 0 0;

    font-size: 17px;

}


.blood-symbol {

    width: 55px;
    height: 55px;

    display: flex;

    align-items: center;
    justify-content: center;

    border-radius: 17px;

    color: white;

    background: var(--red);

    font-size: 22px;

}


.heartbeat {

    margin:
        50px 0 35px;

}


.heartbeat svg {

    width: 100%;

    height: 100px;

}


.heartbeat path {

    fill: none;

    stroke:
        #e52a4f;

    stroke-width: 3;

    stroke-linecap: round;

    stroke-linejoin: round;

    stroke-dasharray: 500;

    animation:
        heartbeatMove
        4s linear infinite;

}


@keyframes heartbeatMove {

    to {

        stroke-dashoffset: -1000;

    }

}


.hero-stats {

    display: grid;

    grid-template-columns:
        repeat(2, 1fr);

    gap: 12px;

}


.hero-stats > div {

    padding: 15px;

    border-radius: 14px;

    background:
        rgba(255,255,255,.05);

}


.hero-stats strong {

    display: block;

    font-family:
        "Manrope",
        sans-serif;

    font-size: 24px;

}


.hero-stats span {

    color:
        rgba(255,255,255,.45);

    font-size: 10px;

}


/* =====================================================
   QUICK ACTIONS
===================================================== */

.quick-section {

    margin-top: -40px;

    position: relative;

    z-index: 10;

    padding-bottom: 50px;

}


.quick-grid {

    display: grid;

    grid-template-columns:
        repeat(3, 1fr);

    gap: 14px;

}


.quick-card {

    display: flex;

    align-items: center;

    gap: 13px;

    min-height: 88px;

    padding: 15px;

    color: var(--text);

    border:
        1px solid
        var(--border);

    border-radius: 17px;

    background: white;

    box-shadow: var(--shadow);

    transition:
        transform .2s,
        box-shadow .2s;

}


.quick-card:hover {

    color: var(--text);

    transform:
        translateY(-5px);

    box-shadow:
        0 20px 45px
        rgba(16,19,24,.11);

}


.quick-card.emergency {

    color: white;

    background: var(--red);

    border-color: var(--red);

}


.quick-card.emergency:hover {

    color: white;

}


.quick-icon {

    width: 45px;
    height: 45px;

    flex-shrink: 0;

    display: flex;

    align-items: center;
    justify-content: center;

    border-radius: 13px;

    background:
        rgba(255,255,255,.15);

}


.quick-icon.red {

    color: var(--red);

    background: var(--red-light);

}


.quick-card strong {

    display: block;

    font-size: 13px;

}


.quick-card span {

    display: block;

    margin-top: 3px;

    color: var(--muted);

    font-size: 10px;

}


.quick-card.emergency span {

    color:
        rgba(255,255,255,.7);

}


.arrow {

    margin-left: auto;

    color: #a8adb5;

    font-size: 11px;

}


/* =====================================================
   SECTIONS
===================================================== */

.stats-section,
.blood-section,
.cities-section,
.how-section {

    padding: 90px 0;

}


.stats-section {

    background: var(--bg);

}


.blood-section,
.cities-section {

    background: white;

}


.section-heading {

    display: flex;

    align-items: flex-end;

    justify-content: space-between;

    gap: 30px;

    margin-bottom: 38px;

}


.section-label {

    display: block;

    margin-bottom: 9px;

    color: var(--red);

    font-size: 10px;

    font-weight: 800;

    letter-spacing: 2px;

}


.section-heading h2,
.center-heading h2 {

    margin: 0;

    font-family:
        "Manrope",
        sans-serif;

    font-size:
        clamp(28px, 4vw, 43px);

    line-height: 1.1;

    letter-spacing: -1.5px;

}


.section-heading p {

    max-width: 400px;

    margin: 0;

    color: var(--muted);

    font-size: 13px;

    line-height: 1.7;

}


/* =====================================================
   STATS
===================================================== */

.stats-grid {

    display: grid;

    grid-template-columns:
        repeat(4, 1fr);

    gap: 14px;

}


.stat-box {

    padding: 25px;

    border:
        1px solid
        var(--border);

    border-radius: 18px;

    background: white;

    transition:
        transform .2s;

}


.stat-box:hover {

    transform:
        translateY(-5px);

}


.stat-box.live {

    color: white;

    background: var(--red);

    border-color: var(--red);

}


.stat-icon {

    width: 40px;
    height: 40px;

    display: flex;

    align-items: center;
    justify-content: center;

    margin-bottom: 22px;

    border-radius: 11px;

    color: var(--red);

    background: var(--red-light);

}


.stat-box.live .stat-icon {

    color: white;

    background:
        rgba(255,255,255,.14);

}


.stat-box strong {

    display: block;

    font-family:
        "Manrope",
        sans-serif;

    font-size: 28px;

}


.stat-box span {

    display: block;

    margin-top: 4px;

    color: var(--muted);

    font-size: 11px;

}


.stat-box.live span {

    color:
        rgba(255,255,255,.7);

}


/* =====================================================
   BLOOD GROUP
===================================================== */

.blood-grid {

    display: grid;

    grid-template-columns:
        repeat(4, 1fr);

    gap: 14px;

}


.blood-card {

    padding: 20px;

    color: var(--text);

    border:
        1px solid
        var(--border);

    border-radius: 18px;

    background: var(--bg);

    transition:
        transform .2s,
        border-color .2s,
        box-shadow .2s;

}


.blood-card:hover {

    color: var(--text);

    transform:
        translateY(-6px);

    border-color:
        rgba(215,25,63,.35);

    box-shadow:
        0 18px 40px
        rgba(16,19,24,.08);

}


.blood-card-header {

    display: flex;

    align-items: center;

    justify-content: space-between;

}


.blood-type {

    font-family:
        "Manrope",
        sans-serif;

    font-size: 27px;

    font-weight: 800;

}


.availability {

    padding: 5px 8px;

    border-radius: 6px;

    color: #7b818b;

    background: #eceef1;

    font-size: 8px;

    font-weight: 700;

}


.availability.available {

    color: #188957;

    background: #e8f8f0;

}


.blood-card-middle {

    display: flex;

    align-items: center;

    gap: 12px;

    margin: 35px 0;

}


.blood-drop {

    width: 43px;
    height: 43px;

    display: flex;

    align-items: center;
    justify-content: center;

    border-radius: 12px;

    color: var(--red);

    background: var(--red-light);

}


.blood-card-middle strong {

    display: block;

    font-size: 20px;

}


.blood-card-middle span {

    color: var(--muted);

    font-size: 9px;

}


.blood-card-footer {

    display: flex;

    align-items: center;

    justify-content: space-between;

    padding-top: 13px;

    border-top:
        1px solid
        var(--border);

    color: var(--muted);

    font-size: 9px;

}


.blood-card-footer i {

    color: var(--red);

}


/* =====================================================
   SIMPLE LINK
===================================================== */

.simple-link {

    display: inline-flex;

    align-items: center;

    gap: 8px;

    color: var(--red);

    font-size: 12px;

    font-weight: 700;

}


.simple-link:hover {

    color: var(--red-dark);

}


/* =====================================================
   SEARCH CTA
===================================================== */

.search-section {

    padding: 20px 0 90px;

    background: white;

}


.search-panel {

    position: relative;

    overflow: hidden;

    display: flex;

    align-items: center;

    justify-content: space-between;

    min-height: 330px;

    padding: 60px;

    border-radius: 28px;

    color: white;

    background:
        linear-gradient(
            120deg,
            #12151b,
            #242934
        );

}


.search-content {

    position: relative;

    z-index: 2;

    max-width: 600px;

}


.section-label.light {

    color: #ff5474;

}


.search-content h2 {

    margin: 10px 0 15px;

    font-family:
        "Manrope",
        sans-serif;

    font-size:
        clamp(32px, 4vw, 48px);

    letter-spacing: -1.5px;

}


.search-content p {

    max-width: 500px;

    color:
        rgba(255,255,255,.6);

    font-size: 13px;

    line-height: 1.7;

    margin-bottom: 25px;

}


.white-btn {

    color: var(--text);

    background: white;

}


.white-btn:hover {

    color: var(--text);

}


.search-visual {

    position: relative;

    width: 300px;
    height: 300px;

}


.search-circle {

    position: absolute;

    border-radius: 50%;

    border:
        1px solid
        rgba(255,255,255,.08);

}


.circle-one {

    width: 300px;
    height: 300px;

}


.circle-two {

    width: 200px;
    height: 200px;

    top: 50px;
    left: 50px;

}


.search-icon {

    position: absolute;

    left: 50%;
    top: 50%;

    transform:
        translate(-50%, -50%);

    width: 75px;
    height: 75px;

    display: flex;

    align-items: center;
    justify-content: center;

    border-radius: 23px;

    color: white;

    background: var(--red);

    font-size: 28px;

    box-shadow:
        0 20px 50px
        rgba(215,25,63,.4);

}


/* =====================================================
   CITIES
===================================================== */

.city-grid {

    display: grid;

    grid-template-columns:
        repeat(4, 1fr);

    gap: 12px;

}


.city-card {

    display: flex;

    align-items: center;

    gap: 12px;

    padding: 15px;

    color: var(--text);

    border:
        1px solid
        var(--border);

    border-radius: 15px;

    background: var(--bg);

    transition: .2s;

}


.city-card:hover {

    color: var(--text);

    transform:
        translateY(-4px);

    border-color:
        rgba(215,25,63,.3);

}


.city-icon {

    width: 40px;
    height: 40px;

    flex-shrink: 0;

    display: flex;

    align-items: center;
    justify-content: center;

    border-radius: 11px;

    color: var(--red);

    background: var(--red-light);

}


.city-content {

    min-width: 0;

    flex: 1;

}


.city-content strong {

    display: block;

    overflow: hidden;

    text-overflow: ellipsis;

    white-space: nowrap;

    font-size: 12px;

}


.city-content span {

    display: block;

    margin-top: 3px;

    color: var(--muted);

    font-size: 9px;

}


.city-arrow {

    color: #aeb3bb;

    font-size: 10px;

}


.no-city {

    grid-column:
        1 / -1;

    padding: 60px;

    text-align: center;

    border:
        1px dashed
        var(--border);

    border-radius: 18px;

}


.no-city i {

    color: #b8bdc5;

    font-size: 30px;

}


.no-city h3 {

    margin: 15px 0 5px;

    font-size: 16px;

}


.no-city p {

    margin: 0;

    color: var(--muted);

    font-size: 12px;

}


/* =====================================================
   HOW IT WORKS
===================================================== */

.how-section {

    background: var(--bg);

}


.center-heading {

    max-width: 650px;

    margin:
        0 auto 55px;

    text-align: center;

}


.center-heading p {

    margin: 14px auto 0;

    color: var(--muted);

    font-size: 13px;

}


.steps-grid {

    display: grid;

    grid-template-columns:
        repeat(3, 1fr);

    gap: 15px;

}


.step {

    position: relative;

    padding: 30px;

    border:
        1px solid
        var(--border);

    border-radius: 20px;

    background: white;

}


.step-number {

    position: absolute;

    top: 20px;
    right: 22px;

    color: #e9ebee;

    font-family:
        "Manrope",
        sans-serif;

    font-size: 40px;

    font-weight: 800;

}


.step-icon {

    width: 53px;
    height: 53px;

    display: flex;

    align-items: center;
    justify-content: center;

    margin-bottom: 25px;

    border-radius: 15px;

    color: var(--red);

    background: var(--red-light);

}


.step h3 {

    margin-bottom: 8px;

    font-family:
        "Manrope",
        sans-serif;

    font-size: 19px;

}


.step p {

    margin: 0;

    color: var(--muted);

    font-size: 12px;

    line-height: 1.7;

}


/* =====================================================
   FINAL CTA
===================================================== */

.final-section {

    padding: 100px 0;

    color: white;

    background:
        radial-gradient(
            circle at 50% 0%,
            rgba(215,25,63,.25),
            transparent 45%
        ),
        var(--dark);

}


.final-content {

    max-width: 700px;

    margin: auto;

    text-align: center;

}


.final-icon {

    width: 65px;
    height: 65px;

    display: flex;

    align-items: center;
    justify-content: center;

    margin:
        0 auto 22px;

    border-radius: 20px;

    color: white;

    background: var(--red);

    font-size: 25px;

}


.final-content h2 {

    margin: 10px 0 15px;

    font-family:
        "Manrope",
        sans-serif;

    font-size:
        clamp(34px, 5vw, 55px);

    letter-spacing: -2px;

}


.final-content p {

    max-width: 530px;

    margin:
        0 auto 28px;

    color:
        rgba(255,255,255,.55);

    font-size: 14px;

    line-height: 1.7;

}


.final-buttons {

    display: flex;

    justify-content: center;

    flex-wrap: wrap;

    gap: 10px;

}


.transparent-btn {

    color: white;

    border:
        1px solid
        rgba(255,255,255,.2);

    background:
        rgba(255,255,255,.05);

}


.transparent-btn:hover {

    color: white;

    background:
        rgba(255,255,255,.1);

}


/* =====================================================
   AI BUTTON
===================================================== */

#aiChatButton {

    position: fixed;

    right: 22px;
    bottom: 22px;

    z-index: 9999;

    width: 58px;
    height: 58px;

    border: none;

    border-radius: 17px;

    color: white;

    background:
        linear-gradient(
            135deg,
            var(--red),
            var(--red-dark)
        );

    font-size: 20px;

    cursor: pointer;

    box-shadow:
        0 15px 35px
        rgba(215,25,63,.35);

    transition: .2s;

}


#aiChatButton:hover {

    transform:
        translateY(-4px)
        scale(1.03);

}


.chat-online {

    position: absolute;

    right: 5px;
    top: 5px;

    width: 9px;
    height: 9px;

    border-radius: 50%;

    border:
        2px solid
        var(--red-dark);

    background: #39d98a;

}


/* =====================================================
   CHAT BOX
===================================================== */

#aiChatBox {

    position: fixed;

    right: 22px;
    bottom: 92px;

    z-index: 9998;

    width: 380px;

    max-width:
        calc(100vw - 30px);

    height: 570px;

    display: none;

    flex-direction: column;

    overflow: hidden;

    border:
        1px solid
        var(--border);

    border-radius: 22px;

    background: white;

    box-shadow:
        0 30px 80px
        rgba(16,19,24,.22);

}


.ai-chat-header {

    display: flex;

    align-items: center;

    justify-content: space-between;

    padding: 16px;

    color: white;

    background: var(--dark);

    border-bottom:
        3px solid
        var(--red);

}


.ai-chat-profile {

    display: flex;

    align-items: center;

    gap: 10px;

}


.ai-avatar {

    width: 42px;
    height: 42px;

    display: flex;

    align-items: center;
    justify-content: center;

    border-radius: 12px;

    color: white;

    background: var(--red);

}


.ai-chat-profile strong {

    display: block;

    font-size: 13px;

}


.ai-chat-profile small {

    display: flex;

    align-items: center;

    gap: 5px;

    margin-top: 3px;

    color:
        rgba(255,255,255,.5);

    font-size: 9px;

}


.ai-chat-profile small span {

    width: 6px;
    height: 6px;

    border-radius: 50%;

    background: #39d98a;

}


.ai-header-actions {

    display: flex;

    align-items: center;

    gap: 6px;

}


#aiSoundButton,
#aiCloseButton {

    width: 34px;
    height: 34px;

    border: none;

    border-radius: 9px;

    color: white;

    background:
        rgba(255,255,255,.08);

    cursor: pointer;

    transition: .2s;

}


#aiSoundButton:hover,
#aiCloseButton:hover {

    background:
        rgba(255,255,255,.16);

}


#aiSoundButton.muted {

    color: #ff9caf;

}


#aiMessages {

    flex: 1;

    overflow-y: auto;

    padding: 16px;

    background: var(--bg);

}


.ai-welcome-modern {

    display: flex;

    gap: 10px;

    padding: 14px;

    border:
        1px solid
        #f5d9df;

    border-radius: 14px;

    background: white;

}


.welcome-avatar {

    width: 36px;
    height: 36px;

    flex-shrink: 0;

    display: flex;

    align-items: center;
    justify-content: center;

    border-radius: 10px;

    color: var(--red);

    background: var(--red-light);

}


.ai-welcome-modern strong {

    font-size: 11px;

}


.ai-welcome-modern p {

    margin: 5px 0 0;

    color: var(--muted);

    font-size: 10px;

    line-height: 1.5;

}


.ai-quick-buttons {

    display: flex;

    flex-wrap: wrap;

    gap: 6px;

    margin-top: 10px;

}


.ai-quick-btn {

    padding: 8px 10px;

    border:
        1px solid
        #f0d7dd;

    border-radius: 50px;

    color: var(--red-dark);

    background: white;

    font-size: 9px;

    cursor: pointer;

}


.ai-quick-btn:hover {

    color: white;

    background: var(--red);

}


.ai-message {

    max-width: 85%;

    padding: 10px 12px;

    margin-top: 10px;

    border-radius: 13px;

    font-size: 11px;

    line-height: 1.6;

    white-space: pre-wrap;

}


.ai-message.bot {

    margin-right: auto;

    border:
        1px solid
        var(--border);

    background: white;

}


.ai-message.user {

    margin-left: auto;

    color: white;

    background: var(--red);

}


#aiTyping {

    display: none;

    padding:
        5px 16px 8px;

    color: #999;

    font-size: 9px;

}


.typing-dots {

    display: inline-flex;

    gap: 3px;

    margin-left: 4px;

}


.typing-dots i {

    width: 5px;
    height: 5px;

    border-radius: 50%;

    background: var(--red);

    animation:
        typing 1s infinite;

}


.typing-dots i:nth-child(2) {

    animation-delay: .15s;

}


.typing-dots i:nth-child(3) {

    animation-delay: .3s;

}


@keyframes typing {

    50% {

        opacity: .3;

        transform:
            translateY(-2px);

    }

}


.ai-input-area {

    display: flex;

    gap: 7px;

    padding: 10px;

    border-top:
        1px solid
        var(--border);

    background: white;

}


#aiInput {

    flex: 1;

    min-width: 0;

    height: 42px;

    padding: 0 14px;

    outline: none;

    border:
        1px solid
        var(--border);

    border-radius: 50px;

    font-size: 11px;

}


#aiInput:focus {

    border-color: var(--red);

}


#aiMicButton {

    width: 42px;
    height: 42px;

    flex-shrink: 0;

    border:
        1px solid
        var(--border);

    border-radius: 50%;

    color: var(--red);

    background: white;

    cursor: pointer;

}


#aiMicButton:hover {

    background: var(--red-light);

}


#aiMicButton.listening {

    color: white;

    background: var(--red);

    animation:
        aiMicPulse 1s infinite;

}


@keyframes aiMicPulse {

    0% {

        box-shadow:
            0 0 0 0
            rgba(215,25,63,.45);

    }

    70% {

        box-shadow:
            0 0 0 10px
            rgba(215,25,63,0);

    }

    100% {

        box-shadow:
            0 0 0 0
            rgba(215,25,63,0);

    }

}


#aiSendButton {

    width: 42px;
    height: 42px;

    border: none;

    border-radius: 50%;

    color: white;

    background: var(--red);

    cursor: pointer;

}


#aiSendButton:disabled,
#aiMicButton:disabled {

    opacity: .5;

    cursor: not-allowed;

}


/* =====================================================
   RESPONSIVE
===================================================== */

@media (max-width: 991px) {

    .modern-hero {

        padding:
            70px 0
            100px;

    }


    .hero-card-wrapper {

        margin-top: 60px;

    }


    .quick-grid {

        grid-template-columns: 1fr;

    }


    .stats-grid {

        grid-template-columns:
            repeat(2, 1fr);

    }


    .blood-grid {

        grid-template-columns:
            repeat(2, 1fr);

    }


    .city-grid {

        grid-template-columns:
            repeat(2, 1fr);

    }


    .search-visual {

        display: none;

    }

}


@media (max-width: 767px) {

    .modern-hero {

        padding:
            55px 0
            80px;

    }


    .modern-hero h1 {

        font-size: 43px;

        letter-spacing: -2px;

    }


    .hero-text {

        font-size: 14px;

    }


    .hero-buttons {

        flex-direction: column;

    }


    .modern-btn {

        width: 100%;

    }


    .hero-trust {

        gap: 10px;

    }


    .trust-item {

        font-size: 9px;

    }


    .stats-section,
    .blood-section,
    .cities-section,
    .how-section {

        padding: 65px 0;

    }


    .section-heading {

        align-items: flex-start;

        flex-direction: column;

    }


    .stats-grid {

        grid-template-columns:
            repeat(2, 1fr);

    }


    .blood-grid {

        grid-template-columns:
            repeat(2, 1fr);

    }


    .city-grid {

        grid-template-columns:
            1fr;

    }


    .steps-grid {

        grid-template-columns:
            1fr;

    }


    .search-panel {

        padding:
            40px 25px;

    }


    .final-section {

        padding:
            75px 20px;

    }


    #aiChatBox {

        right: 10px;

        bottom: 80px;

        width:
            calc(100vw - 20px);

        height:
            min(570px, 75vh);

    }

}


@media (max-width: 480px) {

    .modern-hero h1 {

        font-size: 37px;

    }


    .stats-grid {

        grid-template-columns:
            1fr 1fr;

    }


    .stat-box {

        padding: 18px;

    }


    .blood-grid {

        grid-template-columns:
            1fr 1fr;

    }


    .blood-card {

        padding: 15px;

    }


    .blood-type {

        font-size: 23px;

    }


    .blood-card-middle {

        margin: 25px 0;

    }

}

</style>



<!-- =====================================================
     CHATBOT JAVASCRIPT
===================================================== -->

<script>

document.addEventListener(
    "DOMContentLoaded",
    function () {


        /* =================================================
           ELEMENTS
        ================================================= */

        const chatButton =
            document.getElementById(
                "aiChatButton"
            );

        const chatBox =
            document.getElementById(
                "aiChatBox"
            );

        const closeButton =
            document.getElementById(
                "aiCloseButton"
            );

        const soundButton =
            document.getElementById(
                "aiSoundButton"
            );

        const micButton =
            document.getElementById(
                "aiMicButton"
            );

        const input =
            document.getElementById(
                "aiInput"
            );

        const sendButton =
            document.getElementById(
                "aiSendButton"
            );

        const messages =
            document.getElementById(
                "aiMessages"
            );

        const typing =
            document.getElementById(
                "aiTyping"
            );


        /* =================================================
           SAFETY CHECK
        ================================================= */

        if (
            !chatButton ||
            !chatBox ||
            !closeButton ||
            !soundButton ||
            !micButton ||
            !input ||
            !sendButton ||
            !messages ||
            !typing
        ) {

            console.error(
                "Blood Assistant: Required elements missing."
            );

            return;

        }


        /* =================================================
           SOUND
        ================================================= */

        let soundEnabled =
            localStorage.getItem(
                "bloodAssistantSound"
            ) !== "off";


        function updateSoundButton() {

            if (soundEnabled) {

                soundButton.classList.remove(
                    "muted"
                );

                soundButton.innerHTML =
                    '<i class="fa-solid fa-volume-high"></i>';

                soundButton.title =
                    "Mute assistant voice";

            } else {

                soundButton.classList.add(
                    "muted"
                );

                soundButton.innerHTML =
                    '<i class="fa-solid fa-volume-xmark"></i>';

                soundButton.title =
                    "Unmute assistant voice";

                if (
                    "speechSynthesis" in window
                ) {

                    window.speechSynthesis.cancel();

                }

            }

        }


        updateSoundButton();


        soundButton.addEventListener(
            "click",
            function () {

                soundEnabled =
                    !soundEnabled;

                localStorage.setItem(
                    "bloodAssistantSound",
                    soundEnabled
                        ? "on"
                        : "off"
                );

                updateSoundButton();

            }
        );


        /* =================================================
           TEXT TO SPEECH
        ================================================= */

        function speakReply(text) {

            if (!soundEnabled) {
                return;
            }

            if (
                !("speechSynthesis" in window)
            ) {
                return;
            }

            window.speechSynthesis.cancel();

            const speech =
                new SpeechSynthesisUtterance(
                    text
                );

            speech.lang = "en-IN";

            speech.rate = 0.95;

            speech.pitch = 1;

            speech.volume = 1;

            window.speechSynthesis.speak(
                speech
            );

        }


        /* =================================================
           OPEN CHAT
        ================================================= */

        chatButton.addEventListener(
            "click",
            function () {

                if (
                    chatBox.style.display ===
                    "flex"
                ) {

                    chatBox.style.display =
                        "none";

                    if (
                        "speechSynthesis"
                        in window
                    ) {

                        window.speechSynthesis.cancel();

                    }

                    return;

                }


                chatBox.style.display =
                    "flex";


                setTimeout(
                    function () {

                        input.focus();

                    },
                    150
                );

            }
        );


        /* =================================================
           CLOSE CHAT
        ================================================= */

        closeButton.addEventListener(
            "click",
            function () {

                chatBox.style.display =
                    "none";

                if (
                    "speechSynthesis"
                    in window
                ) {

                    window.speechSynthesis.cancel();

                }

            }
        );


        /* =================================================
           ADD MESSAGE
        ================================================= */

        function addMessage(
            text,
            type
        ) {

            const message =
                document.createElement(
                    "div"
                );

            message.className =
                "ai-message " + type;

            message.textContent =
                text;

            messages.appendChild(
                message
            );

            messages.scrollTop =
                messages.scrollHeight;

        }


        /* =================================================
           SEND MESSAGE
        ================================================= */

        async function sendMessage(
            customMessage = null
        ) {

            const message =
                customMessage !== null
                    ? customMessage.trim()
                    : input.value.trim();


            if (!message) {

                return;

            }


            addMessage(
                message,
                "user"
            );


            input.value = "";

            sendButton.disabled = true;

            micButton.disabled = true;

            typing.style.display =
                "block";


            messages.scrollTop =
                messages.scrollHeight;


            try {

                /*
                ------------------------------------------------
                IMPORTANT PATH FIX

                This automatically creates the correct URL
                relative to the current index.php location.
                ------------------------------------------------
                */

                const aiUrl =
                    new URL(
                        "ai/chat.php",
                        window.location.href
                    ).href;


                console.log(
                    "AI URL:",
                    aiUrl
                );


                const response =
                    await fetch(
                        aiUrl,
                        {
                            method: "POST",

                            headers: {

                                "Content-Type":
                                    "application/json"

                            },

                            body:
                                JSON.stringify({
                                    message:
                                        message
                                })

                        }
                    );


                if (!response.ok) {

                    throw new Error(
                        "HTTP error " +
                        response.status
                    );

                }


                const data =
                    await response.json();


                if (
                    data.success &&
                    data.reply
                ) {

                    addMessage(
                        data.reply,
                        "bot"
                    );

                    speakReply(
                        data.reply
                    );

                } else {

                    const errorMessage =
                        data.message ||
                        "Sorry, I could not process that request.";


                    addMessage(
                        errorMessage,
                        "bot"
                    );

                    speakReply(
                        errorMessage
                    );

                }


            } catch (error) {

                console.error(
                    "AI Chat Error:",
                    error
                );


                const errorMessage =
                    "The AI assistant is temporarily unavailable. Please try again.";


                addMessage(
                    errorMessage,
                    "bot"
                );

                speakReply(
                    errorMessage
                );

            }


            typing.style.display =
                "none";


            sendButton.disabled =
                false;

            micButton.disabled =
                false;


            input.focus();

        }


        /* =================================================
           SEND BUTTON
        ================================================= */

        sendButton.addEventListener(
            "click",
            function () {

                sendMessage();

            }
        );


        /* =================================================
           ENTER KEY
        ================================================= */

        input.addEventListener(
            "keydown",
            function (event) {

                if (
                    event.key === "Enter" &&
                    !event.shiftKey
                ) {

                    event.preventDefault();

                    sendMessage();

                }

            }
        );


        /* =================================================
           QUICK QUESTIONS
        ================================================= */

        document
            .querySelectorAll(
                ".ai-quick-btn"
            )
            .forEach(
                function (button) {

                    button.addEventListener(
                        "click",
                        function () {

                            const question =
                                this.dataset.question;

                            sendMessage(
                                question
                            );

                        }
                    );

                }
            );


        /* =================================================
           VOICE INPUT
        ================================================= */

        const SpeechRecognition =
            window.SpeechRecognition ||
            window.webkitSpeechRecognition;


        let recognition = null;

        let isListening = false;


        if (!SpeechRecognition) {

            micButton.disabled =
                true;

            micButton.title =
                "Voice input is not supported by this browser";

        } else {

            recognition =
                new SpeechRecognition();


            recognition.continuous =
                false;

            recognition.interimResults =
                false;

            recognition.maxAlternatives =
                1;

            recognition.lang =
                "en-IN";


            micButton.addEventListener(
                "click",
                function () {

                    if (isListening) {

                        recognition.stop();

                        return;

                    }


                    try {

                        recognition.start();

                    } catch (error) {

                        console.log(
                            "Microphone start error:",
                            error
                        );

                    }

                }
            );


            recognition.onstart =
                function () {

                    isListening =
                        true;


                    micButton.classList.add(
                        "listening"
                    );


                    micButton.innerHTML =
                        '<i class="fa-solid fa-stop"></i>';


                    micButton.title =
                        "Stop listening";

                };


            recognition.onresult =
                function (event) {

                    const transcript =
                        event
                            .results[0][0]
                            .transcript;


                    input.value =
                        transcript;


                    sendMessage();

                };


            recognition.onerror =
                function (event) {

                    console.log(
                        "Speech recognition error:",
                        event.error
                    );

                };


            recognition.onend =
                function () {

                    isListening =
                        false;


                    micButton.classList.remove(
                        "listening"
                    );


                    micButton.innerHTML =
                        '<i class="fa-solid fa-microphone"></i>';


                    micButton.title =
                        "Voice input";

                };

        }

    }
);

</script>



<?php

/*
|--------------------------------------------------------------------------
| FOOTER
|--------------------------------------------------------------------------
*/

require_once __DIR__ . "/includes/footer.php";

?>