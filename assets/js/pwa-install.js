
// =====================================================
// BLOOD DONOR NETWORK
// PWA INSTALL BUTTON
// Adjusted position for AI Assistant
// =====================================================

(function () {

    "use strict";


    // =================================================
    // VARIABLES
    // =================================================

    let deferredPrompt = null;

    const installButtonId = "bdnInstallButton";


    // =================================================
    // CREATE INSTALL BUTTON
    // =================================================

    function createInstallButton() {

        if (
            document.getElementById(installButtonId)
        ) {
            return;
        }


        const button =
            document.createElement("button");


        button.id =
            installButtonId;


        button.type =
            "button";


        button.innerHTML =
            '<i class="fa-solid fa-download"></i>' +
            '<span>Install App</span>';


        button.setAttribute(
            "aria-label",
            "Install Blood Donor Network App"
        );


        // =================================================
        // POSITION
        // =================================================

        button.style.position =
            "fixed";

        button.style.right =
            "20px";

        /*
         * AI Assistant normally sits near
         * bottom: 20px.
         *
         * Install button is placed ABOVE it.
         */

        button.style.bottom =
            "95px";

        button.style.zIndex =
            "9998";


        // =================================================
        // DISPLAY
        // =================================================

        button.style.display =
            "none";

        button.style.alignItems =
            "center";

        button.style.justifyContent =
            "center";

        button.style.gap =
            "8px";


        // =================================================
        // SIZE
        // =================================================

        button.style.padding =
            "12px 18px";

        button.style.minWidth =
            "145px";

        button.style.height =
            "50px";


        // =================================================
        // DESIGN
        // =================================================

        button.style.border =
            "none";

        button.style.borderRadius =
            "12px";

        button.style.background =
            "#dc3545";

        button.style.color =
            "#ffffff";

        button.style.fontSize =
            "14px";

        button.style.fontWeight =
            "700";

        button.style.cursor =
            "pointer";

        button.style.boxShadow =
            "0 8px 25px rgba(220,53,69,.30)";

        button.style.transition =
            "all .25s ease";


        // =================================================
        // HOVER
        // =================================================

        button.addEventListener(
            "mouseenter",
            function () {

                button.style.background =
                    "#b02a37";

                button.style.transform =
                    "translateY(-2px)";

                button.style.boxShadow =
                    "0 12px 30px rgba(220,53,69,.35)";

            }
        );


        button.addEventListener(
            "mouseleave",
            function () {

                button.style.background =
                    "#dc3545";

                button.style.transform =
                    "translateY(0)";

                button.style.boxShadow =
                    "0 8px 25px rgba(220,53,69,.30)";

            }
        );


        // =================================================
        // MOBILE RESPONSIVE
        // =================================================

        const mobileStyle =
            document.createElement("style");


        mobileStyle.textContent = `

            #${installButtonId} {
                animation: bdnInstallSlide .35s ease;
            }

            @keyframes bdnInstallSlide {

                from {
                    opacity: 0;
                    transform: translateY(15px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }

            }

            @media (max-width: 576px) {

                #${installButtonId} {

                    right: 15px !important;

                    bottom: 90px !important;

                    min-width: 135px !important;

                    height: 46px !important;

                    padding: 10px 14px !important;

                    font-size: 13px !important;

                    border-radius: 11px !important;

                }

            }

        `;


        document.head.appendChild(
            mobileStyle
        );


        // =================================================
        // CLICK
        // =================================================

        button.addEventListener(
            "click",
            async function () {

                if (!deferredPrompt) {

                    showInstallInstructions();

                    return;

                }


                try {

                    deferredPrompt.prompt();


                    const result =
                        await deferredPrompt.userChoice;


                    console.log(
                        "PWA install result:",
                        result.outcome
                    );


                }

                catch (error) {

                    console.error(
                        "PWA installation error:",
                        error
                    );

                }


                deferredPrompt =
                    null;


                hideInstallButton();

            }
        );


        document.body.appendChild(
            button
        );

    }


    // =================================================
    // SHOW BUTTON
    // =================================================

    function showInstallButton() {

        const button =
            document.getElementById(
                installButtonId
            );


        if (!button) {
            return;
        }


        button.style.display =
            "flex";

    }


    // =================================================
    // HIDE BUTTON
    // =================================================

    function hideInstallButton() {

        const button =
            document.getElementById(
                installButtonId
            );


        if (!button) {
            return;
        }


        button.style.display =
            "none";

    }


    // =================================================
    // CHECK INSTALLED STATE
    // =================================================

    function isAppInstalled() {

        // Chrome / Edge / Android

        if (
            window.matchMedia(
                "(display-mode: standalone)"
            ).matches
        ) {

            return true;

        }


        // iOS

        if (
            window.navigator.standalone === true
        ) {

            return true;

        }


        return false;

    }


    // =================================================
    // INSTALL PROMPT
    // =================================================

    window.addEventListener(
        "beforeinstallprompt",
        function (event) {

            console.log(
                "PWA installation available."
            );


            event.preventDefault();


            deferredPrompt =
                event;


            if (
                !isAppInstalled()
            ) {

                showInstallButton();

            }

        }
    );


    // =================================================
    // APP INSTALLED
    // =================================================

    window.addEventListener(
        "appinstalled",
        function () {

            console.log(
                "Blood Donor Network installed successfully."
            );


            deferredPrompt =
                null;


            hideInstallButton();

        }
    );


    // =================================================
    // INSTALL INSTRUCTIONS
    // =================================================

    function showInstallInstructions() {

        if (
            document.getElementById(
                "bdnInstallInstructions"
            )
        ) {

            return;

        }


        const wrapper =
            document.createElement("div");


        wrapper.id =
            "bdnInstallInstructions";


        wrapper.innerHTML = `

            <div style="
                position:fixed;
                inset:0;
                background:rgba(0,0,0,.55);
                z-index:10000;
                display:flex;
                align-items:center;
                justify-content:center;
                padding:20px;
            ">

                <div style="
                    background:#ffffff;
                    width:100%;
                    max-width:420px;
                    border-radius:18px;
                    padding:25px;
                    box-shadow:0 20px 50px rgba(0,0,0,.25);
                    text-align:center;
                ">

                    <div style="
                        width:60px;
                        height:60px;
                        margin:0 auto 15px;
                        border-radius:15px;
                        background:#dc3545;
                        color:#ffffff;
                        display:flex;
                        align-items:center;
                        justify-content:center;
                        font-size:25px;
                    ">

                        <i class="fa-solid fa-droplet"></i>

                    </div>


                    <h4 style="
                        margin-bottom:10px;
                        color:#202735;
                        font-weight:800;
                    ">

                        Install Blood Donor Network

                    </h4>


                    <p style="
                        color:#6b7280;
                        font-size:14px;
                        line-height:1.6;
                    ">

                        Use your browser's
                        <strong>Install App</strong>
                        or
                        <strong>Add to Home Screen</strong>
                        option to install Blood Donor Network.

                    </p>


                    <button
                        id="bdnCloseInstall"
                        type="button"
                        style="
                            border:none;
                            background:#dc3545;
                            color:#ffffff;
                            padding:10px 22px;
                            border-radius:9px;
                            font-weight:700;
                            cursor:pointer;
                        "
                    >

                        Got it

                    </button>

                </div>

            </div>

        `;


        document.body.appendChild(
            wrapper
        );


        const closeButton =
            document.getElementById(
                "bdnCloseInstall"
            );


        if (closeButton) {

            closeButton.addEventListener(
                "click",
                function () {

                    wrapper.remove();

                }
            );

        }

    }


    // =================================================
    // INITIALIZE
    // =================================================

    function initializePWAInstall() {

        createInstallButton();


        if (
            isAppInstalled()
        ) {

            hideInstallButton();

        }

    }


    // =================================================
    // PAGE LOAD
    // =================================================

    if (
        document.readyState ===
        "loading"
    ) {

        document.addEventListener(
            "DOMContentLoaded",
            initializePWAInstall
        );

    }

    else {

        initializePWAInstall();

    }


})();

