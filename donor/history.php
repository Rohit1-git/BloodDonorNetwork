<?php

session_start();

if (!isset($_SESSION['donor_id'])) {
    header("Location: login.php");
    exit();
}

include("../includes/db.php");

$donor_id = $_SESSION['donor_id'];


// =====================================================
// GET DONOR HISTORY
// =====================================================

$stmt = $conn->prepare("
    SELECT
        id,
        created_at,
        accepted_at,
        hospital,
        recipient_name,
        blood_group,
        city,
        status
    FROM blood_requests
    WHERE donor_id = ?
    ORDER BY id DESC
");


if (!$stmt) {
    die("History Query Failed: " . $conn->error);
}


$stmt->bind_param(
    "i",
    $donor_id
);

$stmt->execute();

$result = $stmt->get_result();


include("../includes/header.php");
include("../includes/navbar.php");

?>


<div class="container py-5">


    <!-- HEADER -->

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-bold">
                🩸 Donation History
            </h2>

            <p class="text-muted mb-0">
                Your previous blood request responses
            </p>

        </div>


        <a href="dashboard.php"
           class="btn btn-secondary">

            ← Dashboard

        </a>

    </div>



    <div class="card shadow">


        <div class="card-header bg-success text-white">

            <h3 class="mb-0">
                Donation History
            </h3>

        </div>


        <div class="card-body">


            <?php if ($result->num_rows > 0): ?>


                <div class="table-responsive">


                    <table class="table table-bordered
                                  table-hover align-middle">


                        <thead class="table-dark">

                            <tr>

                                <th>#</th>

                                <th>Date</th>

                                <th>Hospital</th>

                                <th>Recipient</th>

                                <th>Blood Group</th>

                                <th>City</th>

                                <th>Status</th>

                                <th>Accepted At</th>

                            </tr>

                        </thead>


                        <tbody>


                        <?php

                        $count = 1;

                        while (
                            $row = $result->fetch_assoc()
                        ):

                        ?>


                            <tr>


                                <!-- NUMBER -->

                                <td>

                                    <?= $count++; ?>

                                </td>


                                <!-- DATE -->

                                <td>

                                    <?= htmlspecialchars(
                                        $row['created_at']
                                    ); ?>

                                </td>


                                <!-- HOSPITAL -->

                                <td>

                                    <?= htmlspecialchars(
                                        $row['hospital']
                                    ); ?>

                                </td>


                                <!-- RECIPIENT -->

                                <td>

                                    <?= htmlspecialchars(
                                        $row['recipient_name']
                                    ); ?>

                                </td>


                                <!-- BLOOD GROUP -->

                                <td>

                                    <span class="badge bg-danger">

                                        <?= htmlspecialchars(
                                            $row['blood_group']
                                        ); ?>

                                    </span>

                                </td>


                                <!-- CITY -->

                                <td>

                                    <?= htmlspecialchars(
                                        $row['city']
                                    ); ?>

                                </td>


                                <!-- STATUS -->

                                <td>


                                    <?php

                                    if (
                                        $row['status']
                                        === 'Accepted'
                                    ) {

                                        echo '
                                        <span class="badge bg-success">
                                            ✓ Accepted
                                        </span>';

                                    } elseif (
                                        $row['status']
                                        === 'Rejected'
                                    ) {

                                        echo '
                                        <span class="badge bg-danger">
                                            ✕ Rejected
                                        </span>';

                                    } else {

                                        echo '
                                        <span class="badge bg-warning text-dark">
                                            ⏳ Pending
                                        </span>';

                                    }

                                    ?>


                                </td>


                                <!-- ACCEPTED DATE -->

                                <td>

                                    <?php

                                    if (
                                        !empty(
                                            $row['accepted_at']
                                        )
                                    ) {

                                        echo htmlspecialchars(
                                            $row['accepted_at']
                                        );

                                    } else {

                                        echo '
                                        <span class="text-muted">
                                            —
                                        </span>';

                                    }

                                    ?>

                                </td>


                            </tr>


                        <?php endwhile; ?>


                        </tbody>


                    </table>


                </div>


            <?php else: ?>


                <!-- NO HISTORY -->

                <div class="text-center py-5">


                    <div style="font-size:60px;">
                        🩸
                    </div>


                    <h4 class="mt-3">

                        No Donation History Found

                    </h4>


                    <p class="text-muted">

                        You have not responded to any
                        blood requests yet.

                    </p>


                    <a href="requests.php"
                       class="btn btn-danger">

                        View Blood Requests

                    </a>


                </div>


            <?php endif; ?>


        </div>


    </div>


</div>


<?php

$stmt->close();

include("../includes/footer.php");

?>