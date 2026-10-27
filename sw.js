
// =====================================================
// BLOOD DONOR NETWORK
// Progressive Web App - Service Worker
// =====================================================

const CACHE_NAME = "blood-donor-network-v2";

const BASE_PATH = "/BloodDonorNetwork/";


// =====================================================
// FILES TO CACHE
// =====================================================

const STATIC_FILES = [
    BASE_PATH,
    BASE_PATH + "index.php",
    BASE_PATH + "offline.html",
    BASE_PATH + "manifest.json"
];


// =====================================================
// INSTALL
// =====================================================

self.addEventListener("install", function (event) {

    console.log(
        "Blood Donor Network Service Worker installing..."
    );

    event.waitUntil(

        caches.open(CACHE_NAME)

            .then(function (cache) {

                return Promise.all(

                    STATIC_FILES.map(function (file) {

                        return fetch(file)
                            .then(function (response) {

                                if (!response.ok) {
                                    throw new Error(
                                        "Failed to cache: " + file
                                    );
                                }

                                return cache.put(
                                    file,
                                    response
                                );

                            })
                            .catch(function (error) {

                                console.warn(
                                    error.message
                                );

                            });

                    })

                );

            })

    );

    // Activate the new worker immediately

    self.skipWaiting();

});


// =====================================================
// ACTIVATE
// =====================================================

self.addEventListener("activate", function (event) {

    console.log(
        "Blood Donor Network Service Worker activated."
    );

    event.waitUntil(

        caches.keys()

            .then(function (cacheNames) {

                return Promise.all(

                    cacheNames.map(function (cacheName) {

                        if (
                            cacheName !== CACHE_NAME
                        ) {

                            console.log(
                                "Deleting old cache:",
                                cacheName
                            );

                            return caches.delete(
                                cacheName
                            );

                        }

                    })

                );

            })

            .then(function () {

                return self.clients.claim();

            })

    );

});


// =====================================================
// FETCH
// =====================================================

self.addEventListener("fetch", function (event) {

    const request = event.request;


    // Only process GET requests

    if (
        request.method !== "GET"
    ) {

        return;

    }


    // Ignore browser extensions

    if (
        request.url.startsWith("chrome-extension://")
    ) {

        return;

    }


    event.respondWith(

        fetch(request)

            .then(function (response) {

                // Cache successful responses

                if (
                    response &&
                    response.status === 200 &&
                    response.type === "basic"
                ) {

                    const responseClone =
                        response.clone();


                    caches.open(
                        CACHE_NAME
                    ).then(function (cache) {

                        cache.put(
                            request,
                            responseClone
                        );

                    });

                }


                return response;

            })

            .catch(function () {

                console.log(
                    "Offline:",
                    request.url
                );


                return caches.match(
                    request
                )

                .then(function (cachedResponse) {

                    if (
                        cachedResponse
                    ) {

                        return cachedResponse;

                    }


                    // Fallback offline page

                    return caches.match(
                        BASE_PATH +
                        "offline.html"
                    );

                });

            })

    );

});

