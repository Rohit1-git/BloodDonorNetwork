<?php

session_start();

if (!isset($_SESSION['recipient_id'])) {
    header("Location: login.php");
    exit();
}

include("../includes/db.php");

$recipient_id = $_SESSION['recipient_id'];

$stmt = $conn->prepare("
    SELECT
        br.id,
        br.recipient_name,
        br.blood_group,
        br.city,
        br.hospital,
        br.message,
        br.status,
        br.accepted_at,
        br.created_at,

        d.name AS donor_name,
        d.email AS donor_email,
        d.phone AS donor_phone,
        d.blood_group AS donor_blood_group,
        d.city AS donor_city

    FROM blood_requests br

    LEFT JOIN donors d
        ON br.donor_id = d.id

    WHERE br.recipient_id = ?

    ORDER BY br.id DESC
");

$stmt->bind_param("i", $recipient_id);
$stmt->execute();

$requests = $stmt->get_result();

include("../includes/header.php");
include("../includes/navbar.php");

?>

<style>

    body {
        background: #f7f8fc;
    }

    /* ================================
       PAGE
    ================================= */

    .requests-page {
        padding: 45px 0 70px;
    }

    /* ================================
       HEADER
    ================================= */

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        margin-bottom: 35px;
    }

    .page-title-wrapper {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .page-icon {
        width: 58px;
        height: 58px;
        border-radius: 18px;
        background: linear-gradient(135deg, #dc3545, #a71d2a);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 27px;
        box-shadow: 0 8px 20px rgba(220, 53, 69, 0.25);
    }

    .page-title {
        font-weight: 800;
        margin: 0;
        color: #212529;
    }

    .page-subtitle {
        color: #7a7f87;
        margin: 4px 0 0;
    }

    .dashboard-btn {
        border-radius: 12px;
        padding: 10px 18px;
        font-weight: 600;
    }

    /* ================================
       REQUEST CARD
    ================================= */

    .request-card {
        background: #ffffff;
        border: none;
        border-radius: 20px;
        overflow: hidden;
        margin-bottom: 25px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06);
        transition: all 0.25s ease;
    }

    .request-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 14px 35px rgba(0, 0, 0, 0.09);
    }

    /* ================================
       CARD TOP
    ================================= */

    .request-top {
        padding: 20px 25px;
        background: #fff;
        border-bottom: 1px solid #f0f1f3;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
    }

    .request-number {
        font-weight: 700;
        color: #343a40;
        font-size: 16px;
    }

    .request-date {
        font-size: 13px;
        color: #8a8f98;
        margin-top: 3px;
    }

    /* ================================
       STATUS
    ================================= */

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 8px 14px;
        border-radius: 30px;
        font-size: 13px;
        font-weight: 700;
    }

    .status-pending {
        background: #fff3cd;
        color: #856404;
    }

    .status-accepted {
        background: #d1e7dd;
        color: #0f5132;
    }

    .status-rejected {
        background: #f8d7da;
        color: #842029;
    }

    /* ================================
       CARD BODY
    ================================= */

    .request-body {
        padding: 25px;
    }

    .section-title {
        font-size: 16px;
        font-weight: 800;
        margin-bottom: 18px;
        color: #343a40;
    }

    /* ================================
       REQUEST INFO
    ================================= */

    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 14px;
    }

    .info-box {
        background: #f8f9fb;
        border-radius: 14px;
        padding: 15px;
        border: 1px solid #f0f1f3;
    }

    .info-label {
        font-size: 12px;
        color: #858b94;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .4px;
        margin-bottom: 5px;
    }

    .info-value {
        font-weight: 700;
        color: #343a40;
        word-break: break-word;
    }

    /* ================================
       BLOOD GROUP
    ================================= */

    .blood-group {
        width: 58px;
        height: 58px;
        border-radius: 16px;
        background: linear-gradient(135deg, #dc3545, #b02a37);
        color: white;
        display: flex;
        justify-content: center;
        align-items: center;
        font-weight: 800;
        font-size: 18px;
        box-shadow: 0 6px 15px rgba(220, 53, 69, .2);
    }

    .blood-info {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    /* ================================
       MESSAGE
    ================================= */

    .message-box {
        margin-top: 18px;
        padding: 16px;
        background: #fff8f8;
        border-left: 4px solid #dc3545;
        border-radius: 10px;
        color: #555;
        line-height: 1.6;
    }

    .message-title {
        font-size: 12px;
        font-weight: 700;
        color: #dc3545;
        text-transform: uppercase;
        margin-bottom: 5px;
    }

    /* ================================
       DONOR PANEL
    ================================= */

    .donor-panel {
        height: 100%;
        background: linear-gradient(145deg, #f0fff6, #ffffff);
        border: 1px solid #d9f2e3;
        border-radius: 18px;
        padding: 22px;
    }

    .donor-panel.pending {
        background: linear-gradient(145deg, #fffaf0, #ffffff);
        border-color: #ffe8a1;
    }

    .donor-panel.rejected {
        background: linear-gradient(145deg, #fff5f5, #ffffff);
        border-color: #f5c2c7;
    }

    .donor-heading {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 18px;
    }

    .donor-icon {
        width: 45px;
        height: 45px;
        border-radius: 13px;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 21px;
        background: #d1e7dd;
    }

    .pending .donor-icon {
        background: #fff3cd;
    }

    .rejected .donor-icon {
        background: #f8d7da;
    }

    .donor-heading h5 {
        margin: 0;
        font-weight: 800;
    }

    .donor-heading small {
        color: #7b8189;
    }

    /* ================================
       DONOR INFO
    ================================= */

    .donor-info {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 0;
        border-bottom: 1px solid #e9ecef;
    }

    .donor-info:last-child {
        border-bottom: none;
    }

    .donor-info-icon {
        width: 34px;
        height: 34px;
        border-radius: 9px;
        background: #f1f3f5;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .donor-info-content small {
        display: block;
        color: #858b94;
        font-size: 11px;
        font-weight: 600;
    }

    .donor-info-content strong {
        font-size: 14px;
        color: #343a40;
        word-break: break-word;
    }

    /* ================================
       BUTTONS
    ================================= */

    .action-buttons {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 20px;
    }

    .action-btn {
        border-radius: 11px;
        padding: 10px 17px;
        font-weight: 700;
        border: none;
    }

    /* ================================
       PENDING / REJECTED
    ================================= */

    .status-message {
        text-align: center;
        padding: 25px 15px;
    }

    .status-large-icon {
        width: 65px;
        height: 65px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        margin: 0 auto 15px;
    }

    .pending .status-large-icon {
        background: #fff3cd;
    }

    .rejected .status-large-icon {
        background: #f8d7da;
    }

    .status-message h5 {
        font-weight: 800;
        margin-bottom: 8px;
    }

    .status-message p {
        color: #777;
        margin-bottom: 0;
        line-height: 1.6;
    }

    /* ================================
       EMPTY STATE
    ================================= */

    .empty-card {
        background: white;
        border-radius: 22px;
        padding: 65px 25px;
        text-align: center;
        box-shadow: 0 8px 30px rgba(0,0,0,.05);
    }

    .empty-icon {
        width: 85px;
        height: 85px;
        border-radius: 25px;
        background: #fff0f1;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: auto;
        font-size: 40px;
    }

    .empty-card h4 {
        font-weight: 800;
        margin-top: 20px;
    }

    .empty-card p {
        color: #858b94;
        margin-bottom: 25px;
    }

    /* ================================
       RESPONSIVE
    ================================= */

    @media (max-width: 767px) {

        .requests-page {
            padding: 30px 0 50px;
        }

        .page-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .dashboard-btn {
            width: 100%;
        }

        .page-title {
            font-size: 24px;
        }

        .request-top {
            padding: 17px;
        }

        .request-body {
            padding: 17px;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }

        .donor-panel {
            margin-top: 25px;
        }

        .action-buttons .btn {
            width: 100%;
        }
    }

</style>


<div class="container requests-page">

    <!-- PAGE HEADER -->

    <div class="page-header">

        <div class="page-title-wrapper">

            <div class="page-icon">
                🩸
            </div>

            <div>

                <h2 class="page-title">
                    My Blood Requests
                </h2>

                <p class="page-subtitle">
                    Track your blood requests and donor responses
                </p>

            </div>

        </div>


        <a href="dashboard.php"
           class="btn btn-dark dashboard-btn">

            ← Dashboard

        </a>

    </div>


    <?php if ($requests->num_rows > 0): ?>


        <?php while ($request = $requests->fetch_assoc()): ?>


            <?php

            $status = $request['status'];

            if ($status === 'Pending') {

                $status_class = 'status-pending';
                $status_icon = '⏳';

            } elseif ($status === 'Accepted') {

                $status_class = 'status-accepted';
                $status_icon = '✓';

            } else {

                $status_class = 'status-rejected';
                $status_icon = '✕';

            }

            ?>


            <!-- REQUEST CARD -->

            <div class="request-card">


                <!-- TOP -->

                <div class="request-top">

                    <div>

                        <div class="request-number">
                            Request #<?= htmlspecialchars($request['id']); ?>
                        </div>

                        <div class="request-date">
                            Submitted <?= htmlspecialchars($request['created_at']); ?>
                        </div>

                    </div>


                    <span class="status-badge <?= $status_class; ?>">

                        <?= $status_icon; ?>

                        <?= htmlspecialchars($status); ?>

                    </span>

                </div>


                <!-- BODY -->

                <div class="request-body">

                    <div class="row">


                        <!-- REQUEST DETAILS -->

                        <div class="col-lg-6">

                            <h5 class="section-title">
                                🩸 Request Details
                            </h5>


                            <div class="info-grid">


                                <!-- BLOOD -->

                                <div class="info-box">

                                    <div class="blood-info">

                                        <div class="blood-group">

                                            <?= htmlspecialchars(
                                                $request['blood_group']
                                            ); ?>

                                        </div>

                                        <div>

                                            <div class="info-label">
                                                Required Blood
                                            </div>

                                            <div class="info-value">
                                                <?= htmlspecialchars(
                                                    $request['blood_group']
                                                ); ?>
                                                Blood
                                            </div>

                                        </div>

                                    </div>

                                </div>


                                <!-- CITY -->

                                <div class="info-box">

                                    <div class="info-label">
                                        📍 City
                                    </div>

                                    <div class="info-value">

                                        <?= htmlspecialchars(
                                            $request['city']
                                        ); ?>

                                    </div>

                                </div>


                                <!-- HOSPITAL -->

                                <div class="info-box">

                                    <div class="info-label">
                                        🏥 Hospital
                                    </div>

                                    <div class="info-value">

                                        <?= htmlspecialchars(
                                            $request['hospital']
                                        ); ?>

                                    </div>

                                </div>


                                <!-- REQUEST DATE -->

                                <div class="info-box">

                                    <div class="info-label">
                                        🕐 Requested At
                                    </div>

                                    <div class="info-value">

                                        <?= htmlspecialchars(
                                            $request['created_at']
                                        ); ?>

                                    </div>

                                </div>


                            </div>


                            <!-- MESSAGE -->

                            <div class="message-box">

                                <div class="message-title">
                                    Your Message
                                </div>


                                <?php if (!empty($request['message'])): ?>

                                    <?= nl2br(
                                        htmlspecialchars(
                                            $request['message']
                                        )
                                    ); ?>

                                <?php else: ?>

                                    <span class="text-muted">
                                        No message was provided.
                                    </span>

                                <?php endif; ?>

                            </div>


                        </div>


                        <!-- RIGHT SIDE -->

                        <div class="col-lg-6">


                            <?php if (
                                $status === 'Accepted'
                                &&
                                !empty($request['donor_name'])
                            ): ?>


                                <!-- DONOR FOUND -->

                                <div class="donor-panel">

                                    <div class="donor-heading">

                                        <div class="donor-icon">
                                            👤
                                        </div>

                                        <div>

                                            <h5>
                                                Donor Found
                                            </h5>

                                            <small>
                                                Your request was accepted
                                            </small>

                                        </div>

                                    </div>


                                    <!-- DONOR NAME -->

                                    <div class="donor-info">

                                        <div class="donor-info-icon">
                                            👤
                                        </div>

                                        <div class="donor-info-content">

                                            <small>
                                                Donor Name
                                            </small>

                                            <strong>

                                                <?= htmlspecialchars(
                                                    $request['donor_name']
                                                ); ?>

                                            </strong>

                                        </div>

                                    </div>


                                    <!-- BLOOD GROUP -->

                                    <div class="donor-info">

                                        <div class="donor-info-icon">
                                            🩸
                                        </div>

                                        <div class="donor-info-content">

                                            <small>
                                                Blood Group
                                            </small>

                                            <strong>

                                                <?= htmlspecialchars(
                                                    $request['donor_blood_group']
                                                ); ?>

                                            </strong>

                                        </div>

                                    </div>


                                    <!-- PHONE -->

                                    <div class="donor-info">

                                        <div class="donor-info-icon">
                                            📞
                                        </div>

                                        <div class="donor-info-content">

                                            <small>
                                                Phone
                                            </small>

                                            <strong>

                                                <?php if (
                                                    !empty(
                                                        $request['donor_phone']
                                                    )
                                                ): ?>

                                                    <?= htmlspecialchars(
                                                        $request['donor_phone']
                                                    ); ?>

                                                <?php else: ?>

                                                    <span class="text-muted">
                                                        Not available
                                                    </span>

                                                <?php endif; ?>

                                            </strong>

                                        </div>

                                    </div>


                                    <!-- EMAIL -->

                                    <div class="donor-info">

                                        <div class="donor-info-icon">
                                            ✉️
                                        </div>

                                        <div class="donor-info-content">

                                            <small>
                                                Email
                                            </small>

                                            <strong>

                                                <?php if (
                                                    !empty(
                                                        $request['donor_email']
                                                    )
                                                ): ?>

                                                    <?= htmlspecialchars(
                                                        $request['donor_email']
                                                    ); ?>

                                                <?php else: ?>

                                                    <span class="text-muted">
                                                        Not available
                                                    </span>

                                                <?php endif; ?>

                                            </strong>

                                        </div>

                                    </div>


                                    <!-- CITY -->

                                    <div class="donor-info">

                                        <div class="donor-info-icon">
                                            📍
                                        </div>

                                        <div class="donor-info-content">

                                            <small>
                                                Donor City
                                            </small>

                                            <strong>

                                                <?= htmlspecialchars(
                                                    $request['donor_city']
                                                ); ?>

                                            </strong>

                                        </div>

                                    </div>


                                    <!-- ACCEPTED -->

                                    <div class="donor-info">

                                        <div class="donor-info-icon">
                                            🕐
                                        </div>

                                        <div class="donor-info-content">

                                            <small>
                                                Accepted At
                                            </small>

                                            <strong>

                                                <?= htmlspecialchars(
                                                    $request['accepted_at']
                                                ); ?>

                                            </strong>

                                        </div>

                                    </div>


                                    <!-- ACTIONS -->

                                    <div class="action-buttons">


                                        <?php if (
                                            !empty(
                                                $request['donor_phone']
                                            )
                                        ): ?>

                                            <a
                                                href="tel:<?= htmlspecialchars(
                                                    $request['donor_phone']
                                                ); ?>"
                                                class="btn btn-success action-btn">

                                                📞 Call Donor

                                            </a>

                                        <?php endif; ?>


                                        <?php if (
                                            !empty(
                                                $request['donor_email']
                                            )
                                        ): ?>

                                            <a
                                                href="mailto:<?= htmlspecialchars(
                                                    $request['donor_email']
                                                ); ?>"
                                                class="btn btn-primary action-btn">

                                                ✉️ Email Donor

                                            </a>

                                        <?php endif; ?>


                                    </div>

                                </div>


                            <?php elseif ($status === 'Pending'): ?>


                                <!-- PENDING -->

                                <div class="donor-panel pending">

                                    <div class="status-message">

                                        <div class="status-large-icon">
                                            ⏳
                                        </div>

                                        <h5>
                                            Waiting for a Donor
                                        </h5>

                                        <p>
                                            Your blood request has been
                                            submitted successfully and is
                                            waiting for a matching donor
                                            to respond.
                                        </p>

                                    </div>

                                </div>


                            <?php else: ?>


                                <!-- REJECTED -->

                                <div class="donor-panel rejected">

                                    <div class="status-message">

                                        <div class="status-large-icon">
                                            ❌
                                        </div>

                                        <h5>
                                            Request Rejected
                                        </h5>

                                        <p>
                                            Unfortunately, this request
                                            was rejected. You can submit
                                            a new blood request if you
                                            still need help.
                                        </p>

                                    </div>

                                </div>


                            <?php endif; ?>


                        </div>

                    </div>

                </div>

            </div>


        <?php endwhile; ?>


    <?php else: ?>


        <!-- EMPTY STATE -->

        <div class="empty-card">

            <div class="empty-icon">
                🩸
            </div>

            <h4>
                No Blood Requests Yet
            </h4>

            <p>
                You haven't submitted any blood requests.
                Create a request when you need blood.
            </p>

            <a href="dashboard.php"
               class="btn btn-danger px-4 py-2"
               style="border-radius:12px;font-weight:700;">

                🩸 Send Blood Request

            </a>

        </div>


    <?php endif; ?>


</div>


<?php

$stmt->close();

include("../includes/footer.php");

?>