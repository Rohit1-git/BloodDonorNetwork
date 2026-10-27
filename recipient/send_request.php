<?php

session_start();

include("../includes/db.php");
require_once("../includes/mailer.php");


// =====================================================
// CHECK LOGIN
// =====================================================

if (!isset($_SESSION['recipient_id'])) {

    header("Location: login.php");
    exit();

}


// =====================================================
// CHECK POST
// =====================================================

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    header("Location: dashboard.php");
    exit();

}


$recipient_id = $_SESSION['recipient_id'];


// =====================================================
// GET FORM DATA
// =====================================================

$blood_group = trim($_POST['blood_group'] ?? '');
$city        = trim($_POST['city'] ?? '');
$hospital    = trim($_POST['hospital'] ?? '');
$message     = trim($_POST['message'] ?? '');


// =====================================================
// VALIDATION
// =====================================================

if (
    empty($blood_group) ||
    empty($city) ||
    empty($hospital)
) {

    echo "<script>

        alert('Please fill all required fields.');

        window.location='dashboard.php';

    </script>";

    exit();

}


// =====================================================
// GET RECIPIENT
// =====================================================

$stmt = $conn->prepare("
    SELECT
        id,
        name,
        email,
        phone
    FROM recipients
    WHERE id = ?
");


if (!$stmt) {

    die(
        "Recipient Query Error: " .
        $conn->error
    );

}


$stmt->bind_param(
    "i",
    $recipient_id
);

$stmt->execute();

$result = $stmt->get_result();


if ($result->num_rows !== 1) {

    die("Recipient account not found.");

}


$recipient = $result->fetch_assoc();

$stmt->close();


// =====================================================
// INSERT BLOOD REQUEST
// =====================================================

$stmt = $conn->prepare("
    INSERT INTO blood_requests
    (
        recipient_id,
        recipient_name,
        blood_group,
        city,
        phone,
        hospital,
        message,
        status
    )
    VALUES
    (
        ?,
        ?,
        ?,
        ?,
        ?,
        ?,
        ?,
        'Pending'
    )
");


if (!$stmt) {

    die(
        "Blood Request Prepare Error: " .
        $conn->error
    );

}


$stmt->bind_param(
    "issssss",
    $recipient_id,
    $recipient['name'],
    $blood_group,
    $city,
    $recipient['phone'],
    $hospital,
    $message
);


if (!$stmt->execute()) {

    die(
        "Blood Request Insert Error: " .
        $stmt->error
    );

}


$request_id = $stmt->insert_id;

$stmt->close();


// =====================================================
// FIND MATCHING DONORS
// =====================================================

$stmt = $conn->prepare("
    SELECT
        id,
        name,
        email
    FROM donors
    WHERE blood_group = ?
    AND city = ?
    AND availability = 'Available'
");


if (!$stmt) {

    die(
        "Donor Search Error: " .
        $conn->error
    );

}


$stmt->bind_param(
    "ss",
    $blood_group,
    $city
);

$stmt->execute();

$donor_result = $stmt->get_result();


// =====================================================
// EMAIL NOTIFICATIONS
// =====================================================

$matching_donors = 0;
$email_sent = 0;


while ($donor = $donor_result->fetch_assoc()) {

    $matching_donors++;


    if (empty($donor['email'])) {

        continue;

    }


    $sent = sendBloodRequestEmail(
        $donor['email'],
        $donor['name'],
        $recipient['name'],
        $blood_group,
        $city,
        $hospital
    );


    if ($sent) {

        $email_sent++;

    }

}


$stmt->close();

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1">

<title>Blood Request Submitted</title>


<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

</head>


<body class="bg-light">


<div class="container py-5">


<div
class="card shadow mx-auto"
style="max-width:650px;"
>


<div class="card-body text-center p-5">


<div style="font-size:60px;">
🩸
</div>


<h2 class="text-success fw-bold">

Blood Request Submitted

</h2>


<p class="lead">

Your blood request has been successfully submitted.

</p>


<hr>


<div class="text-start">


<p>

<strong>Request ID:</strong>

<?= htmlspecialchars($request_id); ?>

</p>


<p>

<strong>Recipient:</strong>

<?= htmlspecialchars($recipient['name']); ?>

</p>


<p>

<strong>Blood Group:</strong>

<span class="badge bg-danger">

<?= htmlspecialchars($blood_group); ?>

</span>

</p>


<p>

<strong>City:</strong>

<?= htmlspecialchars($city); ?>

</p>


<p>

<strong>Hospital:</strong>

<?= htmlspecialchars($hospital); ?>

</p>


<p>

<strong>Status:</strong>

<span class="badge bg-warning text-dark">

Pending

</span>

</p>


</div>


<hr>


<?php if ($matching_donors > 0): ?>


<div class="alert alert-success">

<strong>
<?= $matching_donors; ?>
</strong>

matching donor(s) found.

<br>

<strong>
<?= $email_sent; ?>
</strong>

email notification(s) sent.

</div>


<?php else: ?>


<div class="alert alert-warning">

No matching available donors found in

<strong>
<?= htmlspecialchars($city); ?>
</strong>.

Your request has been saved.

</div>


<?php endif; ?>


<div class="mt-4">


<a
href="request_status.php"
class="btn btn-success"
>

View Request Status

</a>


<a
href="dashboard.php"
class="btn btn-primary ms-2"
>

Dashboard

</a>


</div>


</div>

</div>

</div>


</body>

</html>