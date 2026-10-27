
<?php
// =====================================================
// BLOOD DONOR NETWORK
// HEADER
// =====================================================

// Start session only if it has not already started.
// This must happen BEFORE any HTML output.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="theme-color"
        content="#dc3545"
    >

    <meta
        name="description"
        content="Blood Donor Network connects blood donors and recipients quickly during blood emergencies."
    >

    <meta
        name="mobile-web-app-capable"
        content="yes"
    >

    <meta
        name="apple-mobile-web-app-capable"
        content="yes"
    >

    <meta
        name="apple-mobile-web-app-status-bar-style"
        content="default"
    >

    <meta
        name="apple-mobile-web-app-title"
        content="Blood Donor"
    >


    <!-- =================================================
         TITLE
    ================================================== -->

    <title>Blood Donor Network</title>


    <!-- =================================================
         PWA MANIFEST
    ================================================== -->

    <link
        rel="manifest"
        href="/BloodDonorNetwork/manifest.json"
    >


    <!-- =================================================
         APP ICON
    ================================================== -->

    <link
        rel="icon"
        type="image/png"
        sizes="192x192"
        href="/BloodDonorNetwork/assets/images/icon-192.png"
    >

    <link
        rel="apple-touch-icon"
        href="/BloodDonorNetwork/assets/images/icon-192.png"
    >


    <!-- =================================================
         BOOTSTRAP
    ================================================== -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >


    <!-- =================================================
         FONT AWESOME
    ================================================== -->

    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        rel="stylesheet"
    >


    <!-- =================================================
         CUSTOM CSS
    ================================================== -->

    <link
        rel="stylesheet"
        href="/BloodDonorNetwork/assets/css/style.css?v=<?php echo time(); ?>"
    >


    <!-- =================================================
         PWA INSTALL JAVASCRIPT
    ================================================== -->

    <script
    src="/BloodDonorNetwork/assets/js/pwa-install.js"
    defer
></script>

<script
    src="/BloodDonorNetwork/assets/js/scroll-animation.js"
    defer
></script>
    <!-- =================================================
         SERVICE WORKER
    ================================================== -->

    <script>

        if ("serviceWorker" in navigator) {

            window.addEventListener(
                "load",
                function () {

                    navigator.serviceWorker
                        .register(
                            "/BloodDonorNetwork/sw.js",
                            {
                                scope: "/BloodDonorNetwork/"
                            }
                        )

                        .then(
                            function (registration) {

                                console.log(
                                    "Blood Donor Network PWA registered successfully:",
                                    registration.scope
                                );

                            }
                        )

                        .catch(
                            function (error) {

                                console.error(
                                    "Blood Donor Network PWA registration failed:",
                                    error
                                );

                            }
                        );

                }
            );

        }

    </script>

</head>


<body>

