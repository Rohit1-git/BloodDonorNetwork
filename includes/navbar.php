
<?php
// =====================================================
// BLOOD DONOR NETWORK
// NAVBAR
// =====================================================
//
// IMPORTANT:
// Session must be started by the main PHP page BEFORE
// this file is included.
//
// Do NOT call session_start() here.
// =====================================================
?>

<nav class="navbar navbar-expand-lg bdn-navbar">

    <div class="container">

        <!-- BRAND -->
        <a
            href="/BloodDonorNetwork/"
            class="navbar-brand bdn-brand"
        >

            <span class="bdn-logo">
                <i class="fa-solid fa-droplet"></i>
            </span>

            <span class="bdn-brand-name">
                Blood Donor
                <b>Network</b>
            </span>

        </a>


        <!-- MOBILE BUTTON -->
        <button
            class="navbar-toggler bdn-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#mainNavbar"
            aria-controls="mainNavbar"
            aria-expanded="false"
            aria-label="Toggle navigation"
        >

            <i class="fa-solid fa-bars"></i>

        </button>


        <!-- MENU -->
        <div
            class="collapse navbar-collapse"
            id="mainNavbar"
        >

            <ul class="navbar-nav ms-auto align-items-lg-center">


                <!-- HOME -->
                <li class="nav-item">

                    <a
                        href="/BloodDonorNetwork/"
                        class="nav-link bdn-link"
                    >

                        <i class="fa-solid fa-house"></i>

                        Home

                    </a>

                </li>


                <!-- ABOUT -->
                <li class="nav-item">

                    <a
                        href="/BloodDonorNetwork/about.php"
                        class="nav-link bdn-link"
                    >

                        <i class="fa-solid fa-circle-info"></i>

                        About

                    </a>

                </li>


                <!-- SEARCH -->
                <li class="nav-item">

                    <a
                        href="/BloodDonorNetwork/search.php"
                        class="nav-link bdn-link"
                    >

                        <i class="fa-solid fa-magnifying-glass"></i>

                        Find Donor

                    </a>

                </li>


                <!-- CONTACT -->
                <li class="nav-item">

                    <a
                        href="/BloodDonorNetwork/contact.php"
                        class="nav-link bdn-link"
                    >

                        <i class="fa-solid fa-envelope"></i>

                        Contact

                    </a>

                </li>


                <!-- =================================================
                     ADMIN LOGGED IN
                ================================================== -->

                <?php if (isset($_SESSION['admin_id'])): ?>

                    <!-- ADMIN DASHBOARD -->
                    <li class="nav-item ms-lg-3 mt-2 mt-lg-0">

                        <a
                            href="/BloodDonorNetwork/admin/dashboard.php"
                            class="bdn-button bdn-admin"
                        >

                            <i class="fa-solid fa-user-shield"></i>

                            Admin Panel

                        </a>

                    </li>


                    <!-- ADMIN LOGOUT -->
                    <li class="nav-item ms-lg-2 mt-2 mt-lg-0">

                        <a
                            href="/BloodDonorNetwork/admin/logout.php"
                            class="bdn-logout"
                        >

                            <i class="fa-solid fa-right-from-bracket"></i>

                            Logout

                        </a>

                    </li>


                <!-- =================================================
                     DONOR LOGGED IN
                ================================================== -->

                <?php elseif (isset($_SESSION['donor_id'])): ?>

                    <!-- DONOR DASHBOARD -->
                    <li class="nav-item ms-lg-3 mt-2 mt-lg-0">

                        <a
                            href="/BloodDonorNetwork/donor/dashboard.php"
                            class="bdn-button"
                        >

                            <i class="fa-solid fa-user"></i>

                            Dashboard

                        </a>

                    </li>


                    <!-- DONOR LOGOUT -->
                    <li class="nav-item ms-lg-2 mt-2 mt-lg-0">

                        <a
                            href="/BloodDonorNetwork/donor/logout.php"
                            class="bdn-logout"
                        >

                            <i class="fa-solid fa-right-from-bracket"></i>

                            Logout

                        </a>

                    </li>


                <!-- =================================================
                     RECIPIENT LOGGED IN
                ================================================== -->

                <?php elseif (isset($_SESSION['recipient_id'])): ?>

                    <!-- RECIPIENT DASHBOARD -->
                    <li class="nav-item ms-lg-3 mt-2 mt-lg-0">

                        <a
                            href="/BloodDonorNetwork/recipient/dashboard.php"
                            class="bdn-button bdn-blue"
                        >

                            <i class="fa-solid fa-user"></i>

                            Dashboard

                        </a>

                    </li>


                    <!-- RECIPIENT LOGOUT -->
                    <li class="nav-item ms-lg-2 mt-2 mt-lg-0">

                        <a
                            href="/BloodDonorNetwork/recipient/logout.php"
                            class="bdn-logout"
                        >

                            <i class="fa-solid fa-right-from-bracket"></i>

                            Logout

                        </a>

                    </li>


                <!-- =================================================
                     NOT LOGGED IN
                ================================================== -->

                <?php else: ?>

                    <!-- LOGIN DROPDOWN -->
                    <li class="nav-item dropdown ms-lg-3 mt-2 mt-lg-0">

                        <a
                            href="#"
                            class="bdn-login dropdown-toggle"
                            id="loginDropdown"
                            role="button"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                        >

                            <i class="fa-solid fa-right-to-bracket"></i>

                            Login

                        </a>


                        <ul
                            class="dropdown-menu dropdown-menu-end bdn-dropdown"
                            aria-labelledby="loginDropdown"
                        >

                            <!-- DONOR LOGIN -->
                            <li>

                                <a
                                    href="/BloodDonorNetwork/donor/login.php"
                                    class="dropdown-item"
                                >

                                    <i class="fa-solid fa-droplet text-danger"></i>

                                    Donor Login

                                </a>

                            </li>


                            <!-- RECIPIENT LOGIN -->
                            <li>

                                <a
                                    href="/BloodDonorNetwork/recipient/login.php"
                                    class="dropdown-item"
                                >

                                    <i class="fa-solid fa-user text-primary"></i>

                                    Recipient Login

                                </a>

                            </li>


                            <li>

                                <hr class="dropdown-divider">

                            </li>


                            <!-- ADMIN LOGIN -->
                            <li>

                                <a
                                    href="/BloodDonorNetwork/admin/login.php"
                                    class="dropdown-item"
                                >

                                    <i class="fa-solid fa-user-shield"></i>

                                    Admin Login

                                </a>

                            </li>

                        </ul>

                    </li>

                <?php endif; ?>


            </ul>

        </div>

    </div>

</nav>


<style>

/* =====================================================
   NAVBAR
===================================================== */

.bdn-navbar {

    background: #ffffff;

    border-bottom: 1px solid #eeeeee;

    box-shadow:
        0 3px 18px rgba(0, 0, 0, 0.06);

    padding: 11px 0;

}


/* =====================================================
   BRAND
===================================================== */

.bdn-brand {

    display: flex;

    align-items: center;

    text-decoration: none;

    gap: 10px;

}


.bdn-logo {

    width: 42px;

    height: 42px;

    display: flex;

    align-items: center;

    justify-content: center;

    border-radius: 12px;

    background: #dc3545;

    color: #ffffff;

    font-size: 19px;

    box-shadow:
        0 5px 14px rgba(220,53,69,.22);

}


.bdn-brand-name {

    color: #202735;

    font-size: 17px;

    font-weight: 700;

    line-height: 1.1;

}


.bdn-brand-name b {

    display: block;

    color: #dc3545;

    font-weight: 800;

}


/* =====================================================
   LINKS
===================================================== */

.bdn-link {

    color: #4b5563 !important;

    font-size: 13px;

    font-weight: 600;

    padding: 10px 13px !important;

    margin: 0 2px;

    border-radius: 8px;

    transition: .2s;

}


.bdn-link i {

    margin-right: 5px;

    font-size: 12px;

}


.bdn-link:hover {

    color: #dc3545 !important;

    background: #fff1f2;

}


/* =====================================================
   LOGIN BUTTON
===================================================== */

.bdn-login {

    display: inline-flex;

    align-items: center;

    gap: 7px;

    padding: 9px 16px;

    border-radius: 8px;

    background: #dc3545;

    color: #ffffff !important;

    text-decoration: none;

    font-size: 13px;

    font-weight: 700;

    transition: .2s;

}


.bdn-login:hover {

    background: #b02a37;

    color: #ffffff !important;

}


/* =====================================================
   DASHBOARD BUTTON
===================================================== */

.bdn-button {

    display: inline-flex;

    align-items: center;

    justify-content: center;

    gap: 7px;

    padding: 9px 15px;

    border-radius: 8px;

    background: #dc3545;

    color: #ffffff;

    text-decoration: none;

    font-size: 13px;

    font-weight: 700;

    transition: .2s;

}


.bdn-button:hover {

    background: #b02a37;

    color: #ffffff;

    transform: translateY(-1px);

}


.bdn-blue {

    background: #0d6efd;

}


.bdn-blue:hover {

    background: #0b5ed7;

}


/* =====================================================
   ADMIN
===================================================== */

.bdn-admin {

    background: #212529;

}


.bdn-admin:hover {

    background: #000000;

}


/* =====================================================
   LOGOUT
===================================================== */

.bdn-logout {

    display: inline-flex;

    align-items: center;

    justify-content: center;

    gap: 6px;

    padding: 9px 13px;

    border-radius: 8px;

    background: #f1f3f5;

    color: #495057;

    text-decoration: none;

    font-size: 12px;

    font-weight: 700;

    transition: .2s;

}


.bdn-logout:hover {

    background: #dc3545;

    color: #ffffff;

}


/* =====================================================
   DROPDOWN
===================================================== */

.bdn-dropdown {

    min-width: 210px;

    padding: 7px;

    margin-top: 8px;

    border: 1px solid #eeeeee;

    border-radius: 12px;

    box-shadow:
        0 10px 30px rgba(0,0,0,.12);

}


.bdn-dropdown .dropdown-item {

    padding: 10px 12px;

    border-radius: 8px;

    color: #374151;

    font-size: 13px;

    font-weight: 600;

}


.bdn-dropdown .dropdown-item i {

    width: 20px;

}


.bdn-dropdown .dropdown-item:hover {

    background: #f8f9fa;

}


/* =====================================================
   MOBILE BUTTON
===================================================== */

.bdn-toggler {

    width: 42px;

    height: 40px;

    border: 1px solid #e5e7eb !important;

    border-radius: 9px;

    color: #374151;

    background: #ffffff;

}


.bdn-toggler:focus {

    box-shadow:
        0 0 0 3px rgba(220,53,69,.10);

}


/* =====================================================
   MOBILE
===================================================== */

@media (max-width: 991px) {

    .bdn-navbar {

        padding: 9px 0;

    }


    .navbar-collapse {

        padding-top: 12px;

    }


    .bdn-link {

        display: block;

        width: 100%;

        margin: 2px 0;

        padding: 11px 12px !important;

    }


    .bdn-button,
    .bdn-login,
    .bdn-logout {

        width: 100%;

        margin-top: 4px;

    }


    .bdn-dropdown {

        width: 100%;

    }

}


/* =====================================================
   SMALL MOBILE
===================================================== */

@media (max-width: 480px) {

    .bdn-brand-name {

        font-size: 15px;

    }


    .bdn-logo {

        width: 39px;

        height: 39px;

        font-size: 17px;

    }

}

</style>

