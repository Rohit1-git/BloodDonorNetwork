<?php
session_start();

if (!isset($_SESSION['donor_id'])) {
    header("Location: login.php");
    exit();
}

include("../includes/db.php");
include("../includes/header.php");
include("../includes/navbar.php");

$donor_id = $_SESSION['donor_id'];

// Get donor details
$stmt = $conn->prepare("
    SELECT blood_group, city
    FROM donors
    WHERE id = ?
");

$stmt->bind_param("i", $donor_id);
$stmt->execute();

$donor = $stmt->get_result()->fetch_assoc();
$stmt->close();

$blood_group = $donor['blood_group'];
$city = $donor['city'];

// Matching requests
$stmt = $conn->prepare("
    SELECT *
    FROM blood_requests
    WHERE blood_group = ?
    AND city = ?
    AND status = 'Pending'
    ORDER BY id DESC
");

$stmt->bind_param(
    "ss",
    $blood_group,
    $city
);

$stmt->execute();

$requests = $stmt->get_result();
?>

<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="fw-bold">
                🩸 Blood Requests
            </h2>

            <p class="text-muted mb-0">
                Matching requests for
                <strong><?= htmlspecialchars($blood_group) ?></strong>
                in
                <strong><?= htmlspecialchars($city) ?></strong>
            </p>
        </div>

        <a href="dashboard.php" class="btn btn-secondary">
            ← Dashboard
        </a>

    </div>


    <?php if ($requests->num_rows > 0): ?>

        <div class="row g-4">

        <?php while ($request = $requests->fetch_assoc()): ?>

            <div class="col-md-6 col-lg-4">

                <div class="card shadow h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between">

                            <h5 class="fw-bold">
                                <?= htmlspecialchars($request['recipient_name']) ?>
                            </h5>

                            <span class="badge bg-warning text-dark">
                                Pending
                            </span>

                        </div>

                        <hr>

                        <p>
                            <strong>Blood Group:</strong>
                            <span class="badge bg-danger">
                                <?= htmlspecialchars($request['blood_group']) ?>
                            </span>
                        </p>

                        <p>
                            <strong>City:</strong>
                            <?= htmlspecialchars($request['city']) ?>
                        </p>

                        <p>
                            <strong>Hospital:</strong>
                            <?= htmlspecialchars($request['hospital']) ?>
                        </p>

                        <?php if (!empty($request['message'])): ?>

                            <p>
                                <strong>Message:</strong><br>
                                <?= htmlspecialchars($request['message']) ?>
                            </p>

                        <?php endif; ?>

                        <div class="d-flex gap-2 mt-4">

                            <form
                                action="request_action.php"
                                method="POST"
                                class="w-50"
                            >

                                <input
                                    type="hidden"
                                    name="request_id"
                                    value="<?= $request['id'] ?>"
                                >

                                <input
                                    type="hidden"
                                    name="action"
                                    value="Accepted"
                                >

                                <button
                                    type="submit"
                                    class="btn btn-success w-100"
                                >
                                    ✓ Accept
                                </button>

                            </form>


                            <form
                                action="request_action.php"
                                method="POST"
                                class="w-50"
                            >

                                <input
                                    type="hidden"
                                    name="request_id"
                                    value="<?= $request['id'] ?>"
                                >

                                <input
                                    type="hidden"
                                    name="action"
                                    value="Rejected"
                                >

                                <button
                                    type="submit"
                                    class="btn btn-outline-danger w-100"
                                >
                                    ✕ Reject
                                </button>

                            </form>

                        </div>

                    </div>

                </div>

            </div>

        <?php endwhile; ?>

        </div>

    <?php else: ?>

        <div class="card shadow">

            <div class="card-body text-center py-5">

                <div style="font-size:60px;">
                    🩸
                </div>

                <h4 class="mt-3">
                    No Blood Requests
                </h4>

                <p class="text-muted">
                    There are currently no pending matching
                    blood requests in your city.
                </p>

            </div>

        </div>

    <?php endif; ?>

</div>

<?php include("../includes/footer.php"); ?>