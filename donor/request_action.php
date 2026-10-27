<?php

session_start();

include("../includes/db.php");
require_once("../includes/mailer.php");


// =====================================================
// CHECK DONOR LOGIN
// =====================================================

if (!isset($_SESSION['donor_id'])) {

    header("Location: login.php");
    exit();

}

$donor_id = $_SESSION['donor_id'];


// =====================================================
// CHECK POST REQUEST
// =====================================================

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    header("Location: requests.php");
    exit();

}


$request_id = intval($_POST['request_id'] ?? 0);
$action = $_POST['action'] ?? '';


// =====================================================
// VALIDATE ACTION
// =====================================================

if (
    $request_id <= 0 ||
    !in_array($action, ['Accepted', 'Rejected'], true)
) {

    echo "<script>

        alert('Invalid request.');

        window.location='requests.php';

    </script>";

    exit();

}


// =====================================================
// GET DONOR DETAILS
// =====================================================

$stmt = $conn->prepare("
    SELECT
        id,
        name,
        email,
        phone,
        blood_group,
        city
    FROM donors
    WHERE id = ?
");


if (!$stmt) {

    die("Donor query failed: " . $conn->error);

}


$stmt->bind_param(
    "i",
    $donor_id
);

$stmt->execute();

$result = $stmt->get_result();


if ($result->num_rows !== 1) {

    die("Donor account not found.");

}


$donor = $result->fetch_assoc();

$stmt->close();


// =====================================================
// GET BLOOD REQUEST
// =====================================================

$stmt = $conn->prepare("
    SELECT
        id,
        recipient_id,
        recipient_name,
        blood_group,
        city,
        phone,
        hospital,
        message,
        status
    FROM blood_requests
    WHERE id = ?
");


if (!$stmt) {

    die("Request query failed: " . $conn->error);

}


$stmt->bind_param(
    "i",
    $request_id
);

$stmt->execute();

$result = $stmt->get_result();


if ($result->num_rows !== 1) {

    echo "<script>

        alert('Blood request not found.');

        window.location='requests.php';

    </script>";

    exit();

}


$request = $result->fetch_assoc();

$stmt->close();


// =====================================================
// CHECK REQUEST STATUS
// =====================================================

if ($request['status'] !== 'Pending') {

    echo "<script>

        alert('This request has already been processed.');

        window.location='requests.php';

    </script>";

    exit();

}


// =====================================================
// ACCEPT REQUEST
// =====================================================

if ($action === 'Accepted') {


    /*
     * Only accept if the request is still Pending.
     * This prevents two donors from accepting
     * the same request at almost the same time.
     */

    $stmt = $conn->prepare("
        UPDATE blood_requests

        SET
            donor_id = ?,
            status = 'Accepted',
            accepted_at = NOW()

        WHERE id = ?
        AND status = 'Pending'
    ");


    if (!$stmt) {

        die(
            "Accept query failed: " .
            $conn->error
        );

    }


    $stmt->bind_param(
        "ii",
        $donor_id,
        $request_id
    );


    $stmt->execute();


    if ($stmt->affected_rows !== 1) {

        echo "<script>

            alert('This request was already accepted by another donor.');

            window.location='requests.php';

        </script>";

        exit();

    }


    $stmt->close();


    // ================================================
    // EMAIL RECIPIENT
    // ================================================

    $email_sent = false;


    /*
     * Get recipient email
     */

    $stmt = $conn->prepare("
        SELECT
            name,
            email
        FROM recipients
        WHERE id = ?
    ");


    if ($stmt) {

        $stmt->bind_param(
            "i",
            $request['recipient_id']
        );

        $stmt->execute();

        $recipient_result =
            $stmt->get_result();


        if ($recipient_result->num_rows === 1) {

            $recipient =
                $recipient_result->fetch_assoc();


            if (!empty($recipient['email'])) {

                /*
                 * This function should exist
                 * inside includes/mailer.php
                 */

                if (function_exists('sendDonorAcceptedEmail')) {

                    $email_sent =
                        sendDonorAcceptedEmail(
                            $recipient['email'],
                            $recipient['name'],
                            $donor['name'],
                            $donor['phone'],
                            $donor['email'],
                            $request['blood_group'],
                            $request['hospital']
                        );

                }

            }

        }

        $stmt->close();

    }


    // ================================================
    // SUCCESS
    // ================================================

    echo "<script>

        alert('Blood request accepted successfully!');

        window.location='requests.php';

    </script>";

    exit();

}


// =====================================================
// REJECT REQUEST
// =====================================================

if ($action === 'Rejected') {


    $stmt = $conn->prepare("
        UPDATE blood_requests

        SET
            donor_id = NULL,
            status = 'Rejected'

        WHERE id = ?
        AND status = 'Pending'
    ");


    if (!$stmt) {

        die(
            "Reject query failed: " .
            $conn->error
        );

    }


    $stmt->bind_param(
        "i",
        $request_id
    );


    $stmt->execute();


    if ($stmt->affected_rows !== 1) {

        echo "<script>

            alert('This request was already processed.');

            window.location='requests.php';

        </script>";

        exit();

    }


    $stmt->close();


    // ================================================
    // SUCCESS
    // ================================================

    echo "<script>

        alert('Blood request rejected.');

        window.location='requests.php';

    </script>";

    exit();

}

?>