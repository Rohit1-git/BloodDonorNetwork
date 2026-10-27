<!-- =====================================================
     SMALL + IMPORTANT FOOTER
===================================================== -->

<footer class="compact-footer">

    <div class="container">

        <div class="footer-grid">

            <!-- ================= BRAND ================= -->

            <div class="footer-brand">

                <a
                    href="/BloodDonorNetwork/index.php"
                    class="footer-logo"
                >

                    <span class="footer-logo-icon">
                        <i class="fa-solid fa-droplet"></i>
                    </span>

                    <span>
                        Blood Donor
                        <strong>Network</strong>
                    </span>

                </a>

                <p>
                    Connecting blood donors, recipients and
                    hospitals to make emergency blood support
                    faster and easier.
                </p>

                <div class="footer-save">

                    <i class="fa-solid fa-heart-pulse"></i>

                    <span>
                        Together, We Save Lives
                    </span>

                </div>

            </div>


            <!-- ================= QUICK LINKS ================= -->

            <div class="footer-column">

                <h6>Quick Links</h6>

                <a href="/BloodDonorNetwork/index.php">
                    <i class="fa-solid fa-house"></i>
                    Home
                </a>

                <a href="/BloodDonorNetwork/about.php">
                    <i class="fa-solid fa-circle-info"></i>
                    About Us
                </a>

                <a href="/BloodDonorNetwork/search.php">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    Search Donor
                </a>

                <a href="/BloodDonorNetwork/contact.php">
                    <i class="fa-solid fa-envelope"></i>
                    Contact Us
                </a>

            </div>


            <!-- ================= DONOR ================= -->

            <div class="footer-column">

                <h6>Blood Services</h6>

                <a href="/BloodDonorNetwork/donor/register.php">
                    <i class="fa-solid fa-hand-holding-droplet"></i>
                    Become a Donor
                </a>

                <a href="/BloodDonorNetwork/donor/login.php">
                    <i class="fa-solid fa-right-to-bracket"></i>
                    Donor Login
                </a>

                <a href="/BloodDonorNetwork/recipient/register.php">
                    <i class="fa-solid fa-user-plus"></i>
                    Request Blood
                </a>

                <a href="/BloodDonorNetwork/recipient/login.php">
                    <i class="fa-solid fa-user"></i>
                    Recipient Login
                </a>

            </div>


            <!-- ================= CONTACT ================= -->

            <div class="footer-column footer-contact">

                <h6>Emergency Support</h6>

                <div>
                    <i class="fa-solid fa-location-dot"></i>
                    <span>Himachal Pradesh, India</span>
                </div>

                <div>
                    <i class="fa-solid fa-envelope"></i>
                    <span>
                        blooddonornetwork.in@gmail.com
                    </span>
                </div>

                <div>
                    <i class="fa-solid fa-phone"></i>
                    <span>
                        +91 7876586338
                    </span>
                </div>

                <div class="support-badge">
                    <i class="fa-solid fa-headset"></i>
                    24/7 Emergency Support
                </div>

            </div>

        </div>


        <!-- ================= DIVIDER ================= -->

        <div class="footer-divider"></div>


        <!-- ================= BOTTOM ================= -->

        <div class="footer-bottom">

            <div>

                © <?= date("Y"); ?>

                <strong>
                    Blood Donor Network
                </strong>

                · All Rights Reserved.

            </div>


            <div class="footer-policy">

                <a href="/BloodDonorNetwork/privacy.php">
                    Privacy Policy
                </a>

                <span>•</span>

                <a href="/BloodDonorNetwork/terms.php">
                    Terms & Conditions
                </a>

            </div>


            <div class="footer-made">

                Made with
                <i class="fa-solid fa-heart"></i>
                for humanity

            </div>

        </div>

    </div>

</footer>


<!-- =====================================================
     FOOTER CSS
===================================================== -->

<style>

.compact-footer {

    position: relative;

    margin-top: 45px;

    background:
        linear-gradient(
            135deg,
            #080b12 0%,
            #111827 50%,
            #080b12 100%
        );

    color: #fff;

    overflow: hidden;

}


/* TOP RED GLOW */

.compact-footer::before {

    content: "";

    position: absolute;

    width: 350px;
    height: 350px;

    top: -280px;
    left: 50%;

    transform: translateX(-50%);

    background:
        rgba(220,53,69,0.14);

    filter: blur(70px);

    border-radius: 50%;

}


/* ================= GRID ================= */

.footer-grid {

    position: relative;

    z-index: 1;

    display: grid;

    grid-template-columns:
        1.4fr
        1fr
        1fr
        1.3fr;

    gap: 35px;

    padding: 42px 0 30px;

}


/* ================= LOGO ================= */

.footer-logo {

    display: inline-flex;

    align-items: center;

    gap: 10px;

    color: #fff;

    text-decoration: none;

    font-size: 19px;

    font-weight: 600;

}

.footer-logo:hover {

    color: #fff;

}

.footer-logo strong {

    color: #ff5967;

    font-weight: 800;

}

.footer-logo-icon {

    width: 42px;
    height: 42px;

    display: flex;

    align-items: center;
    justify-content: center;

    border-radius: 12px;

    background:
        linear-gradient(
            135deg,
            #dc3545,
            #a91d2e
        );

    color: #fff;

    font-size: 19px;

    box-shadow:
        0 6px 18px
        rgba(220,53,69,0.25);

}


/* ================= DESCRIPTION ================= */

.footer-brand p {

    max-width: 350px;

    margin: 14px 0;

    color: #929baa;

    font-size: 12px;

    line-height: 1.7;

}


/* ================= SAVE MESSAGE ================= */

.footer-save {

    display: inline-flex;

    align-items: center;

    gap: 8px;

    color: #ff5967;

    font-size: 12px;

    font-weight: 600;

}

.footer-save i {

    font-size: 16px;

}


/* ================= COLUMNS ================= */

.footer-column {

    display: flex;

    flex-direction: column;

    gap: 9px;

}

.footer-column h6 {

    margin: 2px 0 8px;

    color: #fff;

    font-size: 14px;

    font-weight: 700;

}


/* ================= LINKS ================= */

.footer-column a {

    display: flex;

    align-items: center;

    gap: 8px;

    color: #929baa;

    text-decoration: none;

    font-size: 12px;

    transition: 0.2s;

}

.footer-column a i {

    width: 18px;

    color: #dc3545;

    text-align: center;

    font-size: 11px;

}

.footer-column a:hover {

    color: #fff;

    transform: translateX(3px);

}


/* ================= CONTACT ================= */

.footer-contact > div:not(.support-badge) {

    display: flex;

    align-items: flex-start;

    gap: 9px;

    color: #929baa;

    font-size: 11px;

    line-height: 1.5;

}

.footer-contact > div i {

    width: 18px;

    flex-shrink: 0;

    color: #dc3545;

    text-align: center;

    margin-top: 2px;

}


/* ================= SUPPORT ================= */

.support-badge {

    display: inline-flex;

    align-items: center;

    gap: 7px;

    width: fit-content;

    margin-top: 5px;

    padding: 7px 10px;

    border-radius: 8px;

    color: #ff6572;

    background:
        rgba(220,53,69,0.10);

    border:
        1px solid rgba(220,53,69,0.15);

    font-size: 10px;

    font-weight: 600;

}

.support-badge i {

    color: #ff6572 !important;

}


/* ================= DIVIDER ================= */

.footer-divider {

    height: 1px;

    background:
        rgba(255,255,255,0.08);

}


/* ================= BOTTOM ================= */

.footer-bottom {

    display: flex;

    align-items: center;

    justify-content: space-between;

    gap: 15px;

    padding: 15px 0;

    color: #727c8b;

    font-size: 10px;

}

.footer-bottom strong {

    color: #d5dae1;

}

.footer-policy {

    display: flex;

    align-items: center;

    gap: 8px;

}

.footer-policy a {

    color: #828c9b;

    text-decoration: none;

    transition: 0.2s;

}

.footer-policy a:hover {

    color: #ff5967;

}

.footer-made {

    white-space: nowrap;

}

.footer-made i {

    color: #dc3545;

    margin: 0 3px;

}


/* ================= TABLET ================= */

@media (max-width: 991px) {

    .footer-grid {

        grid-template-columns:
            1fr 1fr;

        gap: 30px;

    }

}


/* ================= MOBILE ================= */

@media (max-width: 575px) {

    .footer-grid {

        grid-template-columns: 1fr;

        text-align: center;

        gap: 25px;

        padding:
            35px 0 25px;

    }

    .footer-logo {

        justify-content: center;

    }

    .footer-brand p {

        margin-left: auto;
        margin-right: auto;

    }

    .footer-save {

        justify-content: center;

    }

    .footer-column {

        align-items: center;

    }

    .footer-contact > div:not(.support-badge) {

        justify-content: center;

        text-align: left;

    }

    .footer-bottom {

        flex-direction: column;

        justify-content: center;

        text-align: center;

        gap: 8px;

    }

    .footer-policy {

        justify-content: center;

        flex-wrap: wrap;

    }

}

</style>


<!-- =====================================================
     BOOTSTRAP JS
===================================================== -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>

</body>
</html>