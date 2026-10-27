<?php

// =====================================================
// BLOOD DONOR NETWORK
// SMART AI BLOOD ASSISTANT v3
// ai/chat.php
// =====================================================

session_start();

header("Content-Type: application/json; charset=UTF-8");

ini_set("display_errors", "0");
error_reporting(E_ALL);


// =====================================================
// LOAD CONFIG + DATABASE
// =====================================================

require_once __DIR__ . "/config.php";
require_once __DIR__ . "/../includes/db.php";


// =====================================================
// JSON RESPONSE
// =====================================================

function sendResponse($success, $message = "", $extra = [])
{
    echo json_encode(
        array_merge(
            [
                "success" => $success,
                "message" => $message
            ],
            $extra
        ),
        JSON_UNESCAPED_UNICODE
    );

    exit;
}


// =====================================================
// BASIC REQUEST CHECK
// =====================================================

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    sendResponse(
        false,
        "Only POST requests are allowed."
    );
}


// =====================================================
// READ REQUEST
// =====================================================

$rawInput = file_get_contents("php://input");

if (
    $rawInput === false ||
    trim($rawInput) === ""
) {

    sendResponse(
        false,
        "No request data received."
    );
}


$input = json_decode(
    $rawInput,
    true
);


if (!is_array($input)) {

    sendResponse(
        false,
        "Invalid JSON request."
    );
}


$userMessage = trim(
    (string)($input["message"] ?? "")
);


if ($userMessage === "") {

    sendResponse(
        false,
        "Please enter your question."
    );
}


if (mb_strlen($userMessage) > 2000) {

    sendResponse(
        false,
        "Message is too long. Maximum 2000 characters allowed."
    );
}


$lowerMessage = mb_strtolower(
    $userMessage,
    "UTF-8"
);


// =====================================================
// BLOOD GROUP DETECTION
// =====================================================

$bloodGroups = [
    "AB+",
    "AB-",
    "A+",
    "A-",
    "B+",
    "B-",
    "O+",
    "O-"
];


$detectedBloodGroup = null;


foreach ($bloodGroups as $group) {

    if (
        preg_match(
            '/(?<![A-Za-z0-9])' .
            preg_quote($group, "/") .
            '(?![A-Za-z0-9])/i',
            $userMessage
        )
    ) {

        $detectedBloodGroup = strtoupper($group);

        break;
    }
}


// =====================================================
// CITY DETECTION
// =====================================================

$detectedCity = null;
$knownCities = [];


if (isset($conn) && $conn) {

    $cityQuery = mysqli_query(
        $conn,
        "
        SELECT DISTINCT city
        FROM donors
        WHERE city IS NOT NULL
        AND TRIM(city) <> ''
        ORDER BY city ASC
        "
    );


    if ($cityQuery) {

        while ($cityRow = mysqli_fetch_assoc($cityQuery)) {

            $city = trim(
                $cityRow["city"] ?? ""
            );


            if ($city !== "") {

                $knownCities[] = $city;
            }
        }
    }
}


// Sort longest city names first.
// This prevents a short city name being matched
// before a longer one.

usort(
    $knownCities,
    function ($a, $b) {
        return mb_strlen($b) - mb_strlen($a);
    }
);


foreach ($knownCities as $city) {

    if (
        mb_stripos(
            $userMessage,
            $city,
            0,
            "UTF-8"
        ) !== false
    ) {

        $detectedCity = $city;

        break;
    }
}


// =====================================================
// INTENT DETECTION
// =====================================================

$intent = "general_blood";


// -----------------------------------------------------
// DONOR REGISTRATION
// -----------------------------------------------------

if (
    preg_match(
        '/\b(register|registration|signup|sign up|join)\b/i',
        $userMessage
    )
    ||
    strpos($lowerMessage, "register as donor") !== false
    ||
    strpos($lowerMessage, "donor registration") !== false
    ||
    strpos($lowerMessage, "donor banna") !== false
    ||
    strpos($lowerMessage, "donor kaise bane") !== false
    ||
    strpos($lowerMessage, "donor kaise ban") !== false
) {

    $intent = "donor_registration";
}


// -----------------------------------------------------
// DONOR LOGIN
// -----------------------------------------------------

elseif (
    preg_match(
        '/\b(donor login|login as donor|donor account login)\b/i',
        $userMessage
    )
    ||
    strpos($lowerMessage, "donor login") !== false
) {

    $intent = "donor_login";
}


// -----------------------------------------------------
// TOTAL DONORS
// -----------------------------------------------------

elseif (
    preg_match(
        '/\b(total donors|how many donors|number of donors|donor count|kitne donors|kitne donor)\b/i',
        $userMessage
    )
) {

    $intent = "total_donors";
}


// -----------------------------------------------------
// BLOOD GROUPS
// -----------------------------------------------------

elseif (
    preg_match(
        '/\b(blood groups|blood group available|which blood groups|how many blood group|how many blood groups|available blood groups)\b/i',
        $userMessage
    )
) {

    $intent = "blood_groups";
}


// -----------------------------------------------------
// HOSPITALS
// -----------------------------------------------------

elseif (
    preg_match(
        '/\b(hospital|hospitals|registered hospitals)\b/i',
        $userMessage
    )
) {

    $intent = "hospitals";
}


// -----------------------------------------------------
// BLOOD REQUEST
// -----------------------------------------------------

elseif (
    preg_match(
        '/\b(request blood|blood request|request for blood|need blood|request a blood)\b/i',
        $userMessage
    )
    ||
    strpos($lowerMessage, "blood chahiye") !== false
    ||
    strpos($lowerMessage, "blood required") !== false
    ||
    strpos($lowerMessage, "blood mangwana") !== false
) {

    $intent = "blood_request";
}


// -----------------------------------------------------
// REQUEST STATUS
// -----------------------------------------------------

elseif (
    preg_match(
        '/\b(request status|blood request status|check request|request tracking|track request)\b/i',
        $userMessage
    )
) {

    $intent = "request_status";
}


// -----------------------------------------------------
// DONOR SEARCH / AVAILABILITY
// -----------------------------------------------------

elseif (
    preg_match(
        '/\b(available|availability|donor available|donors available|find donor|find donors|search donor|search donors|search for donor|search for a donor|search for blood donor|search for a blood donor|find blood donor|find a blood donor|blood donor|blood available|available donor|available donors|donor availability|donor kaise dhunde|donor kaise dhoonde|donor kaha milega|donor kahan milega)\b/i',
        $userMessage
    )
) {

    $intent = "donor_search";
}


// =====================================================
// EMERGENCY DETECTION
// =====================================================

$urgency = "normal";


$emergencyWords = [
    "emergency",
    "urgent",
    "urgently",
    "critical",
    "immediately",
    "asap",
    "turant",
    "jaldi",
    "bahut urgent"
];


foreach ($emergencyWords as $word) {

    if (
        mb_stripos(
            $lowerMessage,
            $word,
            0,
            "UTF-8"
        ) !== false
    ) {

        $urgency = "emergency";

        break;
    }
}


// =====================================================
// DIRECT ANSWERS
// =====================================================
// Common website questions are answered directly.
// Gemini is NOT called for these.
// =====================================================


// -----------------------------------------------------
// DONOR REGISTRATION
// -----------------------------------------------------

if ($intent === "donor_registration") {

    sendResponse(
        true,
        "",
        [
            "reply" =>
                "To register as a donor:\n\n" .
                "1. Open the Donor Registration page.\n" .
                "2. Enter your name, email, phone number and blood group.\n" .
                "3. Enter your gender, age and city.\n" .
                "4. Enter your address and other required details.\n" .
                "5. Submit the registration form.\n" .
                "6. Complete email/OTP verification if requested.\n" .
                "7. After successful registration, log in to your donor account.\n\n" .
                "Please make sure your information is correct.",
            "action" => "open_donor_registration",
            "intent" => "donor_registration",
            "detected" => [
                "blood_group" => $detectedBloodGroup,
                "city" => $detectedCity,
                "urgency" => $urgency
            ]
        ]
    );
}


// -----------------------------------------------------
// DONOR LOGIN
// -----------------------------------------------------

if ($intent === "donor_login") {

    sendResponse(
        true,
        "",
        [
            "reply" =>
                "To login as a donor, open the Donor Login page and enter your registered email and password. After login, you can access your donor dashboard and manage your profile and availability.",
            "action" => "open_donor_login",
            "intent" => "donor_login",
            "detected" => [
                "blood_group" => $detectedBloodGroup,
                "city" => $detectedCity,
                "urgency" => $urgency
            ]
        ]
    );
}


// -----------------------------------------------------
// BLOOD REQUEST
// -----------------------------------------------------

if ($intent === "blood_request") {

    sendResponse(
        true,
        "",
        [
            "reply" =>
                "To request blood:\n\n" .
                "1. Login to your recipient account.\n" .
                "2. Open the blood request section.\n" .
                "3. Select the required blood group.\n" .
                "4. Enter the city and hospital.\n" .
                "5. Add a message if needed.\n" .
                "6. Submit the request.\n\n" .
                "The system will look for available matching donors.",
            "action" => "open_blood_request",
            "intent" => "blood_request",
            "detected" => [
                "blood_group" => $detectedBloodGroup,
                "city" => $detectedCity,
                "urgency" => $urgency
            ]
        ]
    );
}


// -----------------------------------------------------
// REQUEST STATUS
// -----------------------------------------------------

if ($intent === "request_status") {

    sendResponse(
        true,
        "",
        [
            "reply" =>
                "To check your blood request status, login to your recipient account and open the Request Status page. You can see whether your request is Pending, Accepted or Rejected.",
            "action" => "open_request_status",
            "intent" => "request_status",
            "detected" => [
                "blood_group" => $detectedBloodGroup,
                "city" => $detectedCity,
                "urgency" => $urgency
            ]
        ]
    );
}


// =====================================================
// DATABASE CONTEXT
// =====================================================

$databaseContext =
    "No database information was required.";


// =====================================================
// TOTAL REGISTERED DONORS
// =====================================================

if ($intent === "total_donors") {

    $result = mysqli_query(
        $conn,
        "
        SELECT COUNT(*) AS total
        FROM donors
        "
    );


    if ($result) {

        $row = mysqli_fetch_assoc($result);

        $total = (int)(
            $row["total"] ?? 0
        );


        sendResponse(
            true,
            "",
            [
                "reply" =>
                    "There are currently " .
                    $total .
                    " registered donor(s) on the Blood Donor Network.",
                "action" => "chat",
                "intent" => "total_donors",
                "detected" => [
                    "blood_group" => $detectedBloodGroup,
                    "city" => $detectedCity,
                    "urgency" => $urgency
                ]
            ]
        );
    }
}


// =====================================================
// BLOOD GROUP AVAILABILITY
// =====================================================

if ($intent === "blood_groups") {

    $result = mysqli_query(
        $conn,
        "
        SELECT
            blood_group,
            COUNT(*) AS total
        FROM donors
        WHERE availability = 'Available'
        GROUP BY blood_group
        ORDER BY blood_group ASC
        "
    );


    $groups = [];
    $totalAvailable = 0;


    if ($result) {

        while ($row = mysqli_fetch_assoc($result)) {

            $group = $row["blood_group"];

            $count = (int)$row["total"];

            $groups[] =
                $group . ": " . $count;

            $totalAvailable += $count;
        }
    }


    if (count($groups) === 0) {

        $reply =
            "There are currently no available donors in the database.";

    } else {

        $reply =
            "There are currently " .
            count($groups) .
            " blood group(s) with available donors.\n\n" .
            "Available blood groups:\n" .
            implode("\n", $groups) .
            "\n\nTotal available donors: " .
            $totalAvailable;
    }


    sendResponse(
        true,
        "",
        [
            "reply" => $reply,
            "action" => "chat",
            "intent" => "blood_groups",
            "detected" => [
                "blood_group" => $detectedBloodGroup,
                "city" => $detectedCity,
                "urgency" => $urgency
            ]
        ]
    );
}


// -----------------------------------------------------
// HOW TO SEARCH FOR A DONOR
// -----------------------------------------------------
// Answer common website-navigation questions directly.
// This avoids unnecessary Gemini API calls/quota usage.

if (
    preg_match(
        '/\b(how can i search|how do i search|how to search|how can i find|how do i find|where can i find)\b/i',
        $userMessage
    )
    &&
    (
        strpos($lowerMessage, "donor") !== false ||
        strpos($lowerMessage, "blood") !== false
    )
) {

    sendResponse(
        true,
        "",
        [
            "reply" =>
                "To search for a blood donor:\n\n" .
                "1. Open the Search Donor page.\n" .
                "2. Select the required blood group.\n" .
                "3. Select or enter the city.\n" .
                "4. Search for available donors.\n" .
                "5. If a matching donor is available, use the available request/contact option provided by the website.\n\n" .
                "🩸 You can search by blood group and city.",
            "action" => "open_donor_search",
            "intent" => "donor_search",
            "detected" => [
                "blood_group" => $detectedBloodGroup,
                "city" => $detectedCity,
                "urgency" => $urgency
            ]
        ]
    );
}


// =====================================================
// BLOOD GROUP + CITY
// =====================================================
// Example:
// "O+ blood available in Shimla"
// =====================================================

if (
    $detectedBloodGroup !== null &&
    $detectedCity !== null
) {

    $stmt = $conn->prepare(
        "
        SELECT COUNT(*) AS total
        FROM donors
        WHERE blood_group = ?
        AND city = ?
        AND availability = 'Available'
        "
    );


    if ($stmt) {

        $stmt->bind_param(
            "ss",
            $detectedBloodGroup,
            $detectedCity
        );


        $stmt->execute();

        $result = $stmt->get_result();

        $row = $result->fetch_assoc();


        $count = (int)(
            $row["total"] ?? 0
        );


        $stmt->close();


        if ($count > 0) {

            $reply =
                "Yes. There are " .
                $count .
                " available " .
                $detectedBloodGroup .
                " donor(s) in " .
                $detectedCity .
                ".";

        } else {

            $reply =
                "No matching " .
                $detectedBloodGroup .
                " donors are currently available in " .
                $detectedCity .
                ".";
        }


        sendResponse(
            true,
            "",
            [
                "reply" => $reply,
                "action" => "chat",
                "intent" => "donor_search",
                "detected" => [
                    "blood_group" => $detectedBloodGroup,
                    "city" => $detectedCity,
                    "urgency" => $urgency
                ]
            ]
        );
    }
}


// =====================================================
// BLOOD GROUP ONLY
// =====================================================
// Example:
// "O+ available"
// =====================================================

if (
    $detectedBloodGroup !== null
) {

    $stmt = $conn->prepare(
        "
        SELECT
            city,
            COUNT(*) AS total
        FROM donors
        WHERE blood_group = ?
        AND availability = 'Available'
        GROUP BY city
        ORDER BY total DESC
        "
    );


    if ($stmt) {

        $stmt->bind_param(
            "s",
            $detectedBloodGroup
        );


        $stmt->execute();

        $result = $stmt->get_result();


        $cities = [];

        $total = 0;


        while ($row = $result->fetch_assoc()) {

            $city = trim(
                $row["city"] ?? "Unknown"
            );


            $count = (int)$row["total"];


            $cities[] =
                $city . ": " . $count;


            $total += $count;
        }


        $stmt->close();


        if ($total === 0) {

            $reply =
                "No available " .
                $detectedBloodGroup .
                " donors are currently found.";

        } else {

            $reply =
                "There are " .
                $total .
                " available " .
                $detectedBloodGroup .
                " donor(s).\n\n" .
                "City-wise availability:\n" .
                implode("\n", $cities);
        }


        sendResponse(
            true,
            "",
            [
                "reply" => $reply,
                "action" => "chat",
                "intent" => "donor_search",
                "detected" => [
                    "blood_group" => $detectedBloodGroup,
                    "city" => $detectedCity,
                    "urgency" => $urgency
                ]
            ]
        );
    }
}


// =====================================================
// CITY ONLY
// =====================================================
// Example:
// "blood available in Shimla"
// =====================================================

if ($detectedCity !== null) {

    $stmt = $conn->prepare(
        "
        SELECT
            blood_group,
            COUNT(*) AS total
        FROM donors
        WHERE city = ?
        AND availability = 'Available'
        GROUP BY blood_group
        ORDER BY blood_group ASC
        "
    );


    if ($stmt) {

        $stmt->bind_param(
            "s",
            $detectedCity
        );


        $stmt->execute();

        $result = $stmt->get_result();


        $groups = [];

        $total = 0;


        while ($row = $result->fetch_assoc()) {

            $group =
                $row["blood_group"];


            $count =
                (int)$row["total"];


            $groups[] =
                $group . ": " . $count;


            $total += $count;
        }


        $stmt->close();


        if ($total === 0) {

            $reply =
                "No available donors are currently found in " .
                $detectedCity .
                ".";

        } else {

            $reply =
                "There are " .
                $total .
                " available donor(s) in " .
                $detectedCity .
                ".\n\n" .
                "Blood-group availability:\n" .
                implode("\n", $groups);
        }


        sendResponse(
            true,
            "",
            [
                "reply" => $reply,
                "action" => "chat",
                "intent" => "donor_search",
                "detected" => [
                    "blood_group" => $detectedBloodGroup,
                    "city" => $detectedCity,
                    "urgency" => $urgency
                ]
            ]
        );
    }
}


// =====================================================
// GENERAL DONOR AVAILABILITY
// =====================================================

if ($intent === "donor_search") {

    $result = mysqli_query(
        $conn,
        "
        SELECT
            blood_group,
            COUNT(*) AS total
        FROM donors
        WHERE availability = 'Available'
        GROUP BY blood_group
        ORDER BY blood_group ASC
        "
    );


    $groups = [];

    $total = 0;


    if ($result) {

        while ($row = mysqli_fetch_assoc($result)) {

            $group =
                $row["blood_group"];


            $count =
                (int)$row["total"];


            $groups[] =
                $group . ": " . $count;


            $total += $count;
        }
    }


    if ($total === 0) {

        $reply =
            "No donors are currently marked as Available.";

    } else {

        $reply =
            "There are currently " .
            $total .
            " available donor(s).\n\n" .
            "Blood-group availability:\n" .
            implode("\n", $groups);
    }


    sendResponse(
        true,
        "",
        [
            "reply" => $reply,
            "action" => "chat",
            "intent" => "donor_search",
            "detected" => [
                "blood_group" => $detectedBloodGroup,
                "city" => $detectedCity,
                "urgency" => $urgency
            ]
        ]
    );
}


// =====================================================
// HOSPITALS
// =====================================================

if ($intent === "hospitals") {

    $table = mysqli_query(
        $conn,
        "SHOW TABLES LIKE 'hospitals'"
    );


    if (
        $table &&
        mysqli_num_rows($table) > 0
    ) {

        $result = mysqli_query(
            $conn,
            "
            SELECT COUNT(*) AS total
            FROM hospitals
            "
        );


        $count = 0;


        if ($result) {

            $row =
                mysqli_fetch_assoc($result);


            $count =
                (int)(
                    $row["total"] ?? 0
                );
        }


        sendResponse(
            true,
            "",
            [
                "reply" =>
                    "There are currently " .
                    $count .
                    " registered hospital(s) in the Blood Donor Network.",
                "action" => "chat",
                "intent" => "hospitals",
                "detected" => [
                    "blood_group" => $detectedBloodGroup,
                    "city" => $detectedCity,
                    "urgency" => $urgency
                ]
            ]
        );

    } else {

        sendResponse(
            true,
            "",
            [
                "reply" =>
                    "The hospitals information is currently unavailable.",
                "action" => "chat",
                "intent" => "hospitals",
                "detected" => [
                    "blood_group" => $detectedBloodGroup,
                    "city" => $detectedCity,
                    "urgency" => $urgency
                ]
            ]
        );
    }
}


// =====================================================
// GEMINI CONFIG CHECK
// =====================================================
// Reached only when a direct database answer was not
// sufficient.
// =====================================================

if (
    !isset($GEMINI_API_KEY) ||
    trim($GEMINI_API_KEY) === ""
) {

    sendResponse(
        false,
        "Gemini API key is missing. Please add it in ai/config.php."
    );
}


if (
    !isset($GEMINI_API_URL) ||
    trim($GEMINI_API_URL) === "AQ."
) {

    sendResponse(
        false,
        "Gemini API URL is missing."
    );
}


if (!function_exists("curl_init")) {

    sendResponse(
        false,
        "PHP cURL extension is not enabled in XAMPP."
    );
}


// =====================================================
// DATABASE CONTEXT FOR GEMINI
// =====================================================

$databaseContext =
    "The question did not require a direct database lookup.";

if ($conn) {

    // Small context for Gemini fallback.

    $result = mysqli_query(
        $conn,
        "
        SELECT COUNT(*) AS total
        FROM donors
        WHERE availability = 'Available'
        "
    );


    if ($result) {

        $row = mysqli_fetch_assoc($result);

        $databaseContext =
            "Current total available donors: " .
            (int)($row["total"] ?? 0);
    }
}


// =====================================================
// SYSTEM PROMPT
// =====================================================

$systemPrompt = <<<PROMPT

You are the official Smart Blood Assistant for the Blood Donor Network website.

YOUR PURPOSE:
You only help with the Blood Donor Network.

You can answer questions about:

- Blood donors
- Blood groups
- Blood availability
- Finding donors
- Donor registration
- Donor login
- Donor availability
- Blood requests
- Recipient accounts
- Request status
- Registered hospitals
- Blood donation education
- Website navigation

IMPORTANT:
Stay strictly on the Blood Donor Network topic.

Do NOT answer unrelated questions such as:

- Programming
- Coding
- Movies
- Games
- Sports
- Politics
- General homework
- Shopping
- Weather
- Food
- Unrelated technology
- Personal questions

For unrelated questions say:

"I can only help with the Blood Donor Network, including donors, blood groups, blood availability, blood requests, donor registration, hospitals, and website navigation."

DATABASE RULES:

Never invent donor numbers.

Never guess blood availability.

Never invent cities.

Never invent hospitals.

Never claim that a donor exists unless database information supports it.

Do not provide private donor information such as:

- Donor name
- Phone number
- Email
- Address

unless the website's existing authorized functionality specifically provides it.

LANGUAGE:

English question -> English answer.

Hindi question -> Hindi answer.

Hinglish question -> Hinglish answer.

Keep answers simple and concise.

If the user asks how to register as a donor, explain the registration steps.

If the user asks how to request blood, explain the website blood request process.

If the user describes a medical emergency, advise contacting the relevant hospital, qualified healthcare professional, or appropriate local emergency service. Do not diagnose or prescribe medicine.

CURRENT DATABASE CONTEXT:

PROMPT;


$systemPrompt .=
    "\n" .
    $databaseContext .
    "\n";


// =====================================================
// DETECTED INFORMATION
// =====================================================

$systemPrompt .=
    "\nDetected Blood Group: " .
    (
        $detectedBloodGroup ??
        "Not detected"
    );


$systemPrompt .=
    "\nDetected City: " .
    (
        $detectedCity ??
        "Not detected"
    );


$systemPrompt .=
    "\nDetected Intent: " .
    $intent;


$systemPrompt .=
    "\nUrgency: " .
    $urgency;


$systemPrompt .=
    "\n\nUSER QUESTION:\n" .
    $userMessage;


// =====================================================
// GEMINI REQUEST
// =====================================================

$requestData = [

    "systemInstruction" => [
        "parts" => [
            [
                "text" => $systemPrompt
            ]
        ]
    ],

    "contents" => [
        [
            "role" => "user",
            "parts" => [
                [
                    "text" => $userMessage
                ]
            ]
        ]
    ],

    "generationConfig" => [
        "maxOutputTokens" => 500
    ]

];


$jsonBody = json_encode(
    $requestData,
    JSON_UNESCAPED_UNICODE
);


if ($jsonBody === false) {

    sendResponse(
        false,
        "Unable to create Gemini request."
    );
}


// =====================================================
// GEMINI URL
// =====================================================

$requestUrl = rtrim($GEMINI_API_URL);


// =====================================================
// GEMINI CURL FUNCTION
// =====================================================

function callGemini(
    $url,
    $jsonBody,
    $timeout = 60
) {

    $ch = curl_init($url);


    if ($ch === false) {

        return [
            "success" => false,
            "http_code" => 0,
            "response" => "",
            "error" => "Unable to initialize Gemini connection."
        ];
    }


    curl_setopt_array(
        $ch,
        [

            CURLOPT_POST => true,

            CURLOPT_RETURNTRANSFER => true,

            CURLOPT_FOLLOWLOCATION => false,

            CURLOPT_CONNECTTIMEOUT => 10,

            CURLOPT_TIMEOUT => $timeout,

            CURLOPT_HTTPHEADER => [

                "Content-Type: application/json",
                "Accept: application/json",
                "x-goog-api-key: " . trim($GLOBALS["GEMINI_API_KEY"])

            ],

            CURLOPT_POSTFIELDS => $jsonBody

        ]
    );


    $response = curl_exec($ch);


    if ($response === false) {

        $error =
            curl_error($ch);

        curl_close($ch);


        return [
            "success" => false,
            "http_code" => 0,
            "response" => "",
            "error" => $error
        ];
    }


    $httpCode =
        curl_getinfo(
            $ch,
            CURLINFO_HTTP_CODE
        );


    curl_close($ch);


    return [
        "success" =>
            ($httpCode >= 200 && $httpCode < 300),

        "http_code" =>
            $httpCode,

        "response" =>
            $response,

        "error" => ""
    ];
}


// =====================================================
// GEMINI CALL
// =====================================================

$geminiResult = callGemini(
    $requestUrl,
    $jsonBody,
    30
);


// =====================================================
// RETRY TEMPORARY SERVER ERRORS
// =====================================================
// Retry only temporary errors such as 429/500/502/503/504.
// =====================================================

if (
    !$geminiResult["success"] &&
    in_array(
        (int)$geminiResult["http_code"],
        [429, 500, 502, 503, 504],
        true
    )
) {

    // Small delay before retry.

    usleep(700000);


    $geminiResult = callGemini(
        $requestUrl,
        $jsonBody,
        60
    );
}


// =====================================================
// CURL ERROR
// =====================================================

if (
    !$geminiResult["success"] &&
    (int)$geminiResult["http_code"] === 0
) {

    sendResponse(
        false,
        "Unable to connect to Gemini AI.",
        [
            "debug" => [
                "curl_error" =>
                    $geminiResult["error"]
            ]
        ]
    );
}


// =====================================================
// DECODE GEMINI RESPONSE
// =====================================================

$responseData = json_decode(
    $geminiResult["response"],
    true
);


if (!is_array($responseData)) {

    sendResponse(
        false,
        "Gemini returned an invalid response.",
        [
            "debug" => [
                "http_code" =>
                    $geminiResult["http_code"]
            ]
        ]
    );
}


// =====================================================
// GEMINI ERROR
// =====================================================

if (
    !$geminiResult["success"]
) {

    $errorMessage =
        $responseData["error"]["message"]
        ??
        "Unknown Gemini API error.";


    // Friendly message for quota/rate-limit errors.
    if (
        stripos($errorMessage, "quota") !== false ||
        stripos($errorMessage, "rate limit") !== false ||
        stripos($errorMessage, "free_tier_requests") !== false
    ) {
        sendResponse(
            false,
            "Gemini API quota has been reached. Please wait for the quota to reset, or check your Gemini API usage/billing plan."
        );
    }

    // Friendly message for temporary high demand.

    if (
        stripos(
            $errorMessage,
            "high demand"
        ) !== false
        ||
        stripos(
            $errorMessage,
            "try again later"
        ) !== false
    ) {

        sendResponse(
            false,
            "Gemini is temporarily busy. Please try again in a moment."
        );
    }


    sendResponse(
        false,
        "Gemini API Error: " .
        $errorMessage,
        [
            "debug" => [
                "http_code" =>
                    $geminiResult["http_code"]
            ]
        ]
    );
}


// =====================================================
// EXTRACT GEMINI RESPONSE
// =====================================================

$reply = "";


if (
    isset($responseData["candidates"]) &&
    is_array($responseData["candidates"])
) {

    foreach (
        $responseData["candidates"]
        as $candidate
    ) {

        if (
            isset(
                $candidate["content"]["parts"]
            )
            &&
            is_array(
                $candidate["content"]["parts"]
            )
        ) {

            foreach (
                $candidate["content"]["parts"]
                as $part
            ) {

                if (
                    isset($part["text"])
                ) {

                    $reply .=
                        $part["text"];
                }
            }
        }
    }
}


// =====================================================
// EMPTY RESPONSE
// =====================================================

if (
    trim($reply) === ""
) {

    sendResponse(
        false,
        "Gemini returned an empty response."
    );
}


// =====================================================
// SUCCESS
// =====================================================

sendResponse(
    true,
    "",
    [

        "reply" =>
            trim($reply),

        "action" =>
            "chat",

        "intent" =>
            $intent,

        "detected" => [

            "blood_group" =>
                $detectedBloodGroup,

            "city" =>
                $detectedCity,

            "urgency" =>
                $urgency

        ]

    ]
);

?>