<?php

include("includes/db.php");
include("includes/header.php");
include("includes/navbar.php");


// =====================================================
// BLOOD GROUPS
// =====================================================

$bloodGroups = [
    "A+",
    "A-",
    "B+",
    "B-",
    "AB+",
    "AB-",
    "O+",
    "O-"
];


// =====================================================
// GET FILTER VALUES SAFELY
// =====================================================

$blood = isset($_GET['blood_group'])
    ? trim($_GET['blood_group'])
    : '';

$city = isset($_GET['city'])
    ? trim($_GET['city'])
    : '';


// =====================================================
// VALIDATE BLOOD GROUP
// =====================================================

if ($blood !== '' && !in_array($blood, $bloodGroups, true)) {
    $blood = '';
}


// =====================================================
// SEARCH FLAG
// =====================================================

$hasSearch =
    ($blood !== '' || $city !== '');


// =====================================================
// FETCH DONORS
// =====================================================

$result = null;

if ($hasSearch) {

    $sql = "
        SELECT *
        FROM donors
        WHERE availability = 'Available'
    ";

    $types = "";
    $params = [];

    // -------------------------------------------------
    // BLOOD GROUP FILTER
    // -------------------------------------------------

    if ($blood !== '') {

        $sql .= "
            AND blood_group = ?
        ";

        $types .= "s";
        $params[] = $blood;
    }


    // -------------------------------------------------
    // CITY FILTER
    // -------------------------------------------------

    if ($city !== '') {

        $sql .= "
            AND LOWER(TRIM(city)) = LOWER(TRIM(?))
        ";

        $types .= "s";
        $params[] = $city;
    }


    // -------------------------------------------------
    // ORDER
    // -------------------------------------------------

    $sql .= "
        ORDER BY name ASC
    ";


    // -------------------------------------------------
    // PREPARE
    // -------------------------------------------------

    $stmt = $conn->prepare($sql);


    if ($stmt) {

        if (!empty($params)) {

            $stmt->bind_param(
                $types,
                ...$params
            );

        }

        $stmt->execute();

        $result = $stmt->get_result();

    }

}

?>


<!-- =====================================================
     PAGE HEADER
===================================================== -->

<section class="search-hero">

    <div class="container text-center">

        <div class="search-hero-icon">

            <i class="fa-solid fa-droplet"></i>

        </div>

        <h1>
            Search Blood Donor
        </h1>

        <p>
            Find available blood donors by blood group and city
        </p>

    </div>

</section>



<!-- =====================================================
     SEARCH SECTION
===================================================== -->

<section class="search-section">

    <div class="container">

        <div class="search-card">

            <div class="search-card-header">

                <div>

                    <span class="search-label">
                        FIND DONORS
                    </span>

                    <h2>
                        Search Available Donors
                    </h2>

                    <p>
                        Select a blood group, enter a city,
                        or use both filters.
                    </p>

                </div>

                <div class="search-header-icon">

                    <i class="fa-solid fa-magnifying-glass"></i>

                </div>

            </div>


            <!-- =================================================
                 SEARCH FORM
            ================================================= -->

            <form method="GET" action="search.php">

                <div class="row g-4">


                    <!-- BLOOD GROUP -->

                    <div class="col-lg-5 col-md-6">

                        <label
                            for="blood_group"
                            class="form-label"
                        >

                            <i class="fa-solid fa-droplet me-1 text-danger"></i>

                            Blood Group

                        </label>


                        <select
                            name="blood_group"
                            id="blood_group"
                            class="form-select search-input"
                        >

                            <option value="">
                                All Blood Groups
                            </option>


                            <?php foreach ($bloodGroups as $group): ?>

                                <option
                                    value="<?= htmlspecialchars($group) ?>"
                                    <?= ($blood === $group) ? 'selected' : '' ?>
                                >

                                    <?= htmlspecialchars($group) ?>

                                </option>

                            <?php endforeach; ?>

                        </select>

                    </div>



                    <!-- CITY -->

                    <div class="col-lg-5 col-md-6">

                        <label
                            for="city"
                            class="form-label"
                        >

                            <i class="fa-solid fa-location-dot me-1 text-danger"></i>

                            City

                        </label>


                        <input
                            type="text"
                            name="city"
                            id="city"
                            class="form-control search-input"
                            placeholder="Enter city name"
                            value="<?= htmlspecialchars($city) ?>"
                            autocomplete="off"
                        >

                    </div>



                    <!-- BUTTON -->

                    <div class="col-lg-2 col-md-12 d-flex align-items-end">

                        <button
                            type="submit"
                            class="btn btn-danger search-button w-100"
                        >

                            <i class="fa-solid fa-magnifying-glass me-2"></i>

                            Search

                        </button>

                    </div>

                </div>


                <!-- RESET -->

                <?php if ($hasSearch): ?>

                    <div class="mt-3">

                        <a
                            href="search.php"
                            class="reset-search"
                        >

                            <i class="fa-solid fa-rotate-left me-1"></i>

                            Clear Search

                        </a>

                    </div>

                <?php endif; ?>

            </form>

        </div>



        <!-- =====================================================
             SEARCH RESULT
        ===================================================== -->

        <?php if ($hasSearch): ?>


            <div class="result-heading">

                <div>

                    <span class="search-label">
                        SEARCH RESULTS
                    </span>

                    <h3>
                        Available Blood Donors
                    </h3>

                </div>


                <div class="result-filter">

                    <?php if ($blood !== ''): ?>

                        <span class="filter-badge">

                            <i class="fa-solid fa-droplet"></i>

                            <?= htmlspecialchars($blood) ?>

                        </span>

                    <?php endif; ?>


                    <?php if ($city !== ''): ?>

                        <span class="filter-badge">

                            <i class="fa-solid fa-location-dot"></i>

                            <?= htmlspecialchars($city) ?>

                        </span>

                    <?php endif; ?>

                </div>

            </div>



            <div class="row g-4">


                <?php if ($result && $result->num_rows > 0): ?>


                    <?php while ($row = $result->fetch_assoc()): ?>


                        <div class="col-xl-4 col-md-6">

                            <div class="donor-card">

                                <div class="donor-card-top">


                                    <!-- DONOR AVATAR -->

                                    <div class="donor-avatar">

                                        <i class="fa-solid fa-user"></i>

                                    </div>


                                    <!-- BLOOD GROUP -->

                                    <div class="blood-badge">

                                        <?= htmlspecialchars(
                                            $row['blood_group']
                                        ) ?>

                                    </div>

                                </div>



                                <!-- NAME -->

                                <h4 class="donor-name">

                                    <?= htmlspecialchars(
                                        $row['name']
                                    ) ?>

                                </h4>



                                <!-- CITY -->

                                <div class="donor-info">

                                    <div class="info-icon">

                                        <i class="fa-solid fa-location-dot"></i>

                                    </div>

                                    <div>

                                        <small>
                                            City
                                        </small>

                                        <strong>

                                            <?= htmlspecialchars(
                                                $row['city']
                                            ) ?>

                                        </strong>

                                    </div>

                                </div>



                                <!-- PHONE -->

                                <div class="donor-info">

                                    <div class="info-icon">

                                        <i class="fa-solid fa-phone"></i>

                                    </div>

                                    <div>

                                        <small>
                                            Phone
                                        </small>

                                        <strong>

                                            <?= htmlspecialchars(
                                                $row['phone']
                                            ) ?>

                                        </strong>

                                    </div>

                                </div>



                                <!-- AVAILABILITY -->

                                <div class="donor-footer">

                                    <span class="available-status">

                                        <span class="status-dot"></span>

                                        Available

                                    </span>


                                    <?php if (!empty($row['phone'])): ?>

                                        <a
                                            href="tel:<?= htmlspecialchars($row['phone']) ?>"
                                            class="call-button"
                                        >

                                            <i class="fa-solid fa-phone"></i>

                                            Call

                                        </a>

                                    <?php endif; ?>

                                </div>

                            </div>

                        </div>


                    <?php endwhile; ?>


                <?php else: ?>


                    <!-- NO RESULT -->

                    <div class="col-12">

                        <div class="no-result">

                            <div class="no-result-icon">

                                <i class="fa-solid fa-user-slash"></i>

                            </div>

                            <h4>
                                No Donor Found
                            </h4>

                            <p>
                                We couldn't find an available donor
                                matching your search.
                            </p>

                            <a
                                href="search.php"
                                class="btn btn-outline-danger"
                            >

                                <i class="fa-solid fa-rotate-left me-2"></i>

                                Try Another Search

                            </a>

                        </div>

                    </div>


                <?php endif; ?>


            </div>


        <?php else: ?>


            <!-- =================================================
                 DEFAULT MESSAGE
            ================================================= -->

            <div class="search-info">

                <div class="search-info-icon">

                    <i class="fa-solid fa-hand-holding-droplet"></i>

                </div>

                <div>

                    <h4>
                        Find a Blood Donor
                    </h4>

                    <p>
                        Select a blood group, enter a city,
                        or use both filters to find available donors.
                    </p>

                </div>

            </div>


        <?php endif; ?>

    </div>

</section>



<!-- =====================================================
     CSS
===================================================== -->

<style>

/* =====================================================
   HERO
===================================================== */

.search-hero {

    position: relative;

    padding: 65px 0;

    color: #ffffff;

    overflow: hidden;

    background:
        linear-gradient(
            135deg,
            #dc3545 0%,
            #b91c31 55%,
            #8f1728 100%
        );

}


.search-hero::before {

    content: "";

    position: absolute;

    width: 400px;
    height: 400px;

    border-radius: 50%;

    background: rgba(255,255,255,0.05);

    top: -250px;
    right: -100px;

}


.search-hero::after {

    content: "";

    position: absolute;

    width: 300px;
    height: 300px;

    border-radius: 50%;

    background: rgba(255,255,255,0.04);

    bottom: -220px;
    left: -100px;

}


.search-hero .container {

    position: relative;

    z-index: 2;

}


.search-hero-icon {

    width: 65px;
    height: 65px;

    margin: 0 auto 15px;

    display: flex;

    align-items: center;
    justify-content: center;

    border-radius: 18px;

    background: rgba(255,255,255,0.15);

    border: 1px solid rgba(255,255,255,0.25);

    font-size: 27px;

}


.search-hero h1 {

    font-size: 42px;

    font-weight: 800;

    margin-bottom: 10px;

}


.search-hero p {

    margin: 0;

    color: rgba(255,255,255,0.88);

    font-size: 17px;

}


/* =====================================================
   SECTION
===================================================== */

.search-section {

    padding: 55px 0 80px;

    background: #f8f9fa;

}


/* =====================================================
   SEARCH CARD
===================================================== */

.search-card {

    background: #ffffff;

    border-radius: 22px;

    padding: 30px;

    border: 1px solid #eeeeee;

    box-shadow:
        0 10px 35px rgba(0,0,0,0.07);

    margin-bottom: 50px;

}


.search-card-header {

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 20px;

    margin-bottom: 28px;

}


.search-label {

    display: inline-block;

    color: #dc3545;

    font-size: 11px;

    font-weight: 800;

    letter-spacing: 2px;

    margin-bottom: 6px;

}


.search-card-header h2 {

    font-size: 25px;

    font-weight: 800;

    margin: 0 0 5px;

    color: #212529;

}


.search-card-header p {

    margin: 0;

    color: #6c757d;

    font-size: 14px;

}


.search-header-icon {

    width: 55px;
    height: 55px;

    flex-shrink: 0;

    display: flex;

    align-items: center;
    justify-content: center;

    border-radius: 15px;

    background: #fff0f1;

    color: #dc3545;

    font-size: 21px;

}


/* =====================================================
   FORM
===================================================== */

.form-label {

    font-weight: 600;

    color: #343a40;

    margin-bottom: 8px;

}


.search-input {

    height: 50px;

    border-radius: 11px;

    border: 1px solid #dee2e6;

    padding-left: 15px;

    transition: all 0.2s ease;

}


.search-input:focus {

    border-color: #dc3545;

    box-shadow:
        0 0 0 3px rgba(220,53,69,0.10);

}


.search-button {

    height: 50px;

    border-radius: 11px;

    font-weight: 700;

    border: none;

    background:
        linear-gradient(
            135deg,
            #dc3545,
            #b91c31
        );

    box-shadow:
        0 5px 15px rgba(220,53,69,0.20);

    transition: all 0.2s ease;

}


.search-button:hover {

    transform: translateY(-2px);

    box-shadow:
        0 8px 20px rgba(220,53,69,0.30);

}


.reset-search {

    color: #dc3545;

    text-decoration: none;

    font-size: 13px;

    font-weight: 600;

}


.reset-search:hover {

    color: #a7192c;

    text-decoration: underline;

}


/* =====================================================
   RESULT HEADING
===================================================== */

.result-heading {

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 20px;

    margin-bottom: 25px;

}


.result-heading h3 {

    margin: 0;

    font-size: 25px;

    font-weight: 800;

}


.result-filter {

    display: flex;

    flex-wrap: wrap;

    gap: 7px;

    justify-content: flex-end;

}


.filter-badge {

    display: inline-flex;

    align-items: center;

    gap: 6px;

    padding: 7px 12px;

    border-radius: 20px;

    background: #fff0f1;

    color: #dc3545;

    font-size: 12px;

    font-weight: 700;

}


/* =====================================================
   DONOR CARD
===================================================== */

.donor-card {

    height: 100%;

    padding: 25px;

    background: #ffffff;

    border: 1px solid #eeeeee;

    border-radius: 20px;

    box-shadow:
        0 6px 20px rgba(0,0,0,0.05);

    transition:
        transform 0.25s ease,
        box-shadow 0.25s ease;

}


.donor-card:hover {

    transform: translateY(-7px);

    box-shadow:
        0 15px 35px rgba(0,0,0,0.10);

}


.donor-card-top {

    display: flex;

    align-items: center;

    justify-content: space-between;

    margin-bottom: 18px;

}


.donor-avatar {

    width: 58px;
    height: 58px;

    display: flex;

    align-items: center;
    justify-content: center;

    border-radius: 16px;

    background: #fff0f1;

    color: #dc3545;

    font-size: 23px;

}


.blood-badge {

    min-width: 48px;

    height: 48px;

    display: flex;

    align-items: center;
    justify-content: center;

    border-radius: 50%;

    background: #dc3545;

    color: #ffffff;

    font-weight: 800;

    font-size: 14px;

    box-shadow:
        0 5px 12px rgba(220,53,69,0.20);

}


.donor-name {

    font-size: 20px;

    font-weight: 800;

    margin-bottom: 20px;

    color: #212529;

    text-transform: capitalize;

}


/* =====================================================
   DONOR INFO
===================================================== */

.donor-info {

    display: flex;

    align-items: center;

    gap: 12px;

    margin-bottom: 13px;

}


.info-icon {

    width: 38px;
    height: 38px;

    min-width: 38px;

    display: flex;

    align-items: center;
    justify-content: center;

    border-radius: 10px;

    background: #f8f9fa;

    color: #dc3545;

}


.donor-info small {

    display: block;

    color: #8a8f98;

    font-size: 11px;

    margin-bottom: 2px;

}


.donor-info strong {

    display: block;

    color: #343a40;

    font-size: 14px;

    font-weight: 600;

}


/* =====================================================
   DONOR FOOTER
===================================================== */

.donor-footer {

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 10px;

    border-top: 1px solid #eeeeee;

    padding-top: 18px;

    margin-top: 20px;

}


.available-status {

    display: inline-flex;

    align-items: center;

    gap: 7px;

    color: #198754;

    font-size: 13px;

    font-weight: 700;

}


.status-dot {

    width: 8px;
    height: 8px;

    background: #198754;

    border-radius: 50%;

    box-shadow:
        0 0 0 4px rgba(25,135,84,0.10);

}


.call-button {

    display: inline-flex;

    align-items: center;

    gap: 6px;

    padding: 8px 13px;

    border-radius: 9px;

    background: #dc3545;

    color: #ffffff;

    text-decoration: none;

    font-size: 12px;

    font-weight: 700;

    transition: 0.2s;

}


.call-button:hover {

    background: #bb2d3b;

    color: #ffffff;

    transform: translateY(-1px);

}


/* =====================================================
   NO RESULT
===================================================== */

.no-result {

    background: #ffffff;

    border: 1px solid #eeeeee;

    border-radius: 20px;

    padding: 55px 20px;

    text-align: center;

    box-shadow:
        0 5px 18px rgba(0,0,0,0.04);

}


.no-result-icon {

    width: 75px;
    height: 75px;

    margin: 0 auto 18px;

    display: flex;

    align-items: center;
    justify-content: center;

    border-radius: 50%;

    background: #fff0f1;

    color: #dc3545;

    font-size: 28px;

}


.no-result h4 {

    font-weight: 800;

    margin-bottom: 8px;

}


.no-result p {

    color: #777;

    margin-bottom: 20px;

}


/* =====================================================
   INFO BOX
===================================================== */

.search-info {

    display: flex;

    align-items: center;

    gap: 20px;

    background: #ffffff;

    border: 1px solid #eeeeee;

    border-radius: 18px;

    padding: 25px;

    box-shadow:
        0 5px 18px rgba(0,0,0,0.04);

}


.search-info-icon {

    width: 60px;
    height: 60px;

    min-width: 60px;

    display: flex;

    align-items: center;
    justify-content: center;

    border-radius: 15px;

    background: #fff0f1;

    color: #dc3545;

    font-size: 24px;

}


.search-info h4 {

    font-size: 18px;

    font-weight: 800;

    margin-bottom: 5px;

}


.search-info p {

    margin: 0;

    color: #6c757d;

    font-size: 14px;

}


/* =====================================================
   TABLET
===================================================== */

@media (max-width: 991.98px) {

    .search-hero {

        padding: 55px 0;

    }

    .search-hero h1 {

        font-size: 36px;

    }

}


/* =====================================================
   MOBILE
===================================================== */

@media (max-width: 767.98px) {

    .search-section {

        padding: 35px 0 60px;

    }

    .search-card {

        padding: 22px;

        border-radius: 18px;

    }

    .search-card-header {

        align-items: flex-start;

    }

    .search-header-icon {

        display: none;

    }

    .search-hero h1 {

        font-size: 30px;

    }

    .search-hero p {

        font-size: 14px;

    }

    .result-heading {

        align-items: flex-start;

        flex-direction: column;

    }

    .result-filter {

        justify-content: flex-start;

    }

    .search-info {

        align-items: flex-start;

        padding: 20px;

    }

}


/* =====================================================
   SMALL MOBILE
===================================================== */

@media (max-width: 400px) {

    .search-hero {

        padding: 45px 15px;

    }

    .search-hero-icon {

        width: 55px;
        height: 55px;

        font-size: 23px;

    }

    .search-hero h1 {

        font-size: 26px;

    }

    .search-card {

        padding: 18px;

    }

    .search-card-header h2 {

        font-size: 21px;

    }

    .donor-card {

        padding: 20px;

    }

}

</style>



<?php

include("includes/footer.php");

?>