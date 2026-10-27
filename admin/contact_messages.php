<?php

session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include("../includes/db.php");


// =====================================================
// GET ALL CONTACT MESSAGES
// =====================================================

$sql = "
    SELECT
        id,
        name,
        email,
        subject,
        message,
        status,
        created_at
    FROM contact_messages
    ORDER BY id DESC
";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Database Error: " . mysqli_error($conn));
}


// =====================================================
// MESSAGE STATISTICS
// =====================================================

$total_messages = 0;
$unread = 0;
$read_messages = 0;


// Total

$count_result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM contact_messages"
);

if ($count_result) {

    $total_messages =
        mysqli_fetch_assoc($count_result)['total'];

}


// Unread

$unread_result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM contact_messages
     WHERE status='Unread'"
);

if ($unread_result) {

    $unread =
        mysqli_fetch_assoc($unread_result)['total'];

}


// Read

$read_messages =
    $total_messages - $unread;


include("../includes/header.php");

?>


<style>

/* =====================================================
   CONTACT MESSAGES ADMIN PAGE
===================================================== */

.messages-page {

    min-height: 100vh;

    padding: 35px 0 60px;

    background:
        radial-gradient(
            circle at 5% 10%,
            rgba(220,53,69,0.08),
            transparent 28%
        ),

        radial-gradient(
            circle at 95% 90%,
            rgba(220,53,69,0.06),
            transparent 30%
        ),

        #f7f9fc;
}


/* =====================================================
   PAGE HEADER
===================================================== */

.messages-header {

    display: flex;

    justify-content: space-between;

    align-items: center;

    margin-bottom: 30px;

}


.messages-heading {

    display: flex;

    align-items: center;

    gap: 15px;

}


.messages-heading-icon {

    width: 58px;
    height: 58px;

    display: flex;

    align-items: center;
    justify-content: center;

    border-radius: 17px;

    color: white;

    font-size: 24px;

    background:
        linear-gradient(
            135deg,
            #dc3545,
            #a71930
        );

    box-shadow:
        0 8px 22px rgba(220,53,69,0.25);

}


.messages-heading h2 {

    margin: 0;

    font-weight: 800;

    color: #252525;

}


.messages-heading p {

    margin: 4px 0 0;

    color: #858585;

    font-size: 14px;

}


/* =====================================================
   DASHBOARD BUTTON
===================================================== */

.dashboard-btn {

    display: inline-flex;

    align-items: center;

    gap: 8px;

    padding: 11px 18px;

    border-radius: 11px;

    background: #212529;

    color: #fff;

    text-decoration: none;

    font-weight: 600;

    transition: .25s ease;

}


.dashboard-btn:hover {

    background: #dc3545;

    color: #fff;

    transform: translateY(-2px);

}


/* =====================================================
   STATISTICS
===================================================== */

.message-stat {

    position: relative;

    padding: 22px;

    border-radius: 18px;

    background: #fff;

    border: 1px solid #edf0f4;

    box-shadow:
        0 8px 30px rgba(0,0,0,0.06);

    overflow: hidden;

    transition: .25s ease;

}


.message-stat:hover {

    transform: translateY(-4px);

    box-shadow:
        0 14px 35px rgba(0,0,0,0.09);

}


.message-stat::after {

    content: "";

    position: absolute;

    width: 100px;
    height: 100px;

    border-radius: 50%;

    right: -40px;
    top: -40px;

    background: rgba(220,53,69,0.06);

}


.stat-icon {

    width: 45px;
    height: 45px;

    display: flex;

    align-items: center;
    justify-content: center;

    border-radius: 13px;

    margin-bottom: 14px;

    font-size: 19px;

}


.stat-icon.red {

    color: #dc3545;

    background: #fff0f2;

}


.stat-icon.yellow {

    color: #d89b00;

    background: #fff8df;

}


.stat-icon.green {

    color: #198754;

    background: #eaf8f1;

}


.message-stat h3 {

    font-size: 28px;

    font-weight: 800;

    margin: 0;

    color: #252525;

}


.message-stat p {

    margin: 4px 0 0;

    color: #858585;

    font-size: 13px;

}


/* =====================================================
   UNREAD ALERT
===================================================== */

.unread-alert {

    display: flex;

    align-items: center;

    gap: 14px;

    padding: 17px 20px;

    margin: 30px 0;

    border-radius: 15px;

    background:
        linear-gradient(
            135deg,
            #fff8e1,
            #fffdf5
        );

    border: 1px solid #ffe7a3;

}


.unread-alert-icon {

    width: 43px;
    height: 43px;

    display: flex;

    align-items: center;
    justify-content: center;

    flex-shrink: 0;

    border-radius: 12px;

    background: #ffc107;

    color: #fff;

}


.unread-alert strong {

    color: #333;

}


.unread-alert span {

    display: block;

    color: #777;

    font-size: 13px;

    margin-top: 2px;

}


/* =====================================================
   MAIN CARD
===================================================== */

.messages-card {

    border: none;

    border-radius: 20px;

    background: #fff;

    overflow: hidden;

    box-shadow:
        0 12px 45px rgba(0,0,0,0.07);

}


.messages-card-header {

    display: flex;

    justify-content: space-between;

    align-items: center;

    padding: 20px 25px;

    color: #fff;

    background:
        linear-gradient(
            135deg,
            #dc3545,
            #a71930
        );

}


.messages-card-header h5 {

    margin: 0;

    font-weight: 700;

}


.messages-card-header span {

    padding: 6px 11px;

    border-radius: 20px;

    background: rgba(255,255,255,0.15);

    font-size: 12px;

}


.messages-card-body {

    padding: 0;

}


/* =====================================================
   TABLE
===================================================== */

.messages-table {

    margin: 0;

    border: none;

}


.messages-table thead th {

    padding: 15px 17px;

    background: #fafbfc;

    border-bottom: 1px solid #e9ecef;

    border-top: none;

    color: #6c757d;

    font-size: 12px;

    text-transform: uppercase;

    letter-spacing: .5px;

    white-space: nowrap;

}


.messages-table tbody td {

    padding: 17px;

    border-color: #f0f1f3;

    vertical-align: middle;

    color: #444;

    font-size: 13px;

}


/* =====================================================
   MESSAGE ROW
===================================================== */

.messages-table tbody tr {

    transition: .2s ease;

}


.messages-table tbody tr:hover {

    background: #fff9fa;

}


.messages-table tbody tr.unread-row {

    background: #fff8f9;

}


.messages-table tbody tr.unread-row:hover {

    background: #fff1f3;

}


/* =====================================================
   USER
===================================================== */

.user-info {

    display: flex;

    align-items: center;

    gap: 11px;

    min-width: 170px;

}


.user-avatar {

    width: 40px;
    height: 40px;

    display: flex;

    align-items: center;
    justify-content: center;

    flex-shrink: 0;

    border-radius: 12px;

    background:
        linear-gradient(
            135deg,
            #dc3545,
            #b91c31
        );

    color: #fff;

    font-weight: 700;

}


.user-name {

    font-weight: 700;

    color: #333;

}


.user-email {

    font-size: 11px;

    color: #888;

    margin-top: 2px;

}


/* =====================================================
   SUBJECT
===================================================== */

.subject-text {

    max-width: 180px;

    font-weight: 600;

    color: #333;

}


/* =====================================================
   MESSAGE PREVIEW
===================================================== */

.message-preview {

    max-width: 300px;

    color: #777;

    line-height: 1.5;

}


.message-preview-text {

    display: -webkit-box;

    -webkit-line-clamp: 2;

    -webkit-box-orient: vertical;

    overflow: hidden;

}


/* =====================================================
   STATUS
===================================================== */

.status-badge {

    display: inline-flex;

    align-items: center;

    gap: 6px;

    padding: 7px 11px;

    border-radius: 30px;

    font-size: 11px;

    font-weight: 700;

}


.status-unread {

    color: #b42318;

    background: #fff0f0;

}


.status-read {

    color: #087443;

    background: #eaf8f1;

}


/* =====================================================
   DATE
===================================================== */

.date-text {

    color: #888;

    font-size: 12px;

    white-space: nowrap;

}


/* =====================================================
   ACTION BUTTONS
===================================================== */

.message-actions {

    min-width: 135px;

}


.message-action {

    display: flex;

    align-items: center;

    justify-content: center;

    gap: 6px;

    width: 100%;

    margin-bottom: 6px;

    padding: 7px 10px;

    border-radius: 9px;

    font-size: 12px;

    font-weight: 600;

    text-decoration: none;

    transition: .2s ease;

}


.message-action:last-child {

    margin-bottom: 0;

}


.message-action:hover {

    transform: translateX(2px);

}


.action-read {

    color: #198754;

    background: #eaf8f1;

}


.action-read:hover {

    color: #fff;

    background: #198754;

}


.action-reply {

    color: #0d6efd;

    background: #edf4ff;

}


.action-reply:hover {

    color: #fff;

    background: #0d6efd;

}


.action-delete {

    color: #dc3545;

    background: #fff0f2;

}


.action-delete:hover {

    color: #fff;

    background: #dc3545;

}


/* =====================================================
   EMPTY STATE
===================================================== */

.empty-message {

    padding: 80px 20px;

    text-align: center;

}


.empty-message-icon {

    width: 85px;
    height: 85px;

    display: flex;

    align-items: center;
    justify-content: center;

    margin: auto;

    border-radius: 25px;

    background: #fff0f2;

    color: #dc3545;

    font-size: 35px;

}


.empty-message h4 {

    margin-top: 20px;

    font-weight: 700;

}


.empty-message p {

    color: #888;

}


/* =====================================================
   RESPONSIVE
===================================================== */

@media(max-width: 991px) {

    .messages-header {

        align-items: flex-start;

        gap: 15px;

    }

    .message-preview {

        max-width: 220px;

    }

}


@media(max-width: 768px) {

    .messages-page {

        padding: 25px 0 40px;

    }


    .messages-header {

        flex-direction: column;

    }


    .dashboard-btn {

        width: 100%;

        justify-content: center;

    }


    .messages-heading h2 {

        font-size: 23px;

    }


    .messages-card-header {

        padding: 18px;

    }


    .messages-table thead {

        display: none;

    }


    .messages-table,
    .messages-table tbody,
    .messages-table tr,
    .messages-table td {

        display: block;

        width: 100%;

    }


    .messages-table tr {

        padding: 18px;

        border-bottom: 1px solid #eee;

    }


    .messages-table td {

        padding: 7px 0 !important;

        border: none !important;

    }


    .messages-table td::before {

        display: block;

        margin-bottom: 4px;

        color: #999;

        font-size: 10px;

        font-weight: 700;

        text-transform: uppercase;

    }


    .messages-table td:nth-child(1)::before {
        content: "ID";
    }

    .messages-table td:nth-child(2)::before {
        content: "Contact";
    }

    .messages-table td:nth-child(3)::before {
        content: "Email";
    }

    .messages-table td:nth-child(4)::before {
        content: "Subject";
    }

    .messages-table td:nth-child(5)::before {
        content: "Message";
    }

    .messages-table td:nth-child(6)::before {
        content: "Status";
    }

    .messages-table td:nth-child(7)::before {
        content: "Date";
    }

    .messages-table td:nth-child(8)::before {
        content: "Actions";
    }


    .message-preview {

        max-width: 100%;

    }


    .message-actions {

        width: 100%;

        max-width: 250px;

    }

}


/* =====================================================
   SMOOTH ANIMATION
===================================================== */

.message-stat,
.messages-card,
.unread-alert {

    animation: fadeUp .5s ease both;

}


@keyframes fadeUp {

    from {

        opacity: 0;

        transform: translateY(12px);

    }

    to {

        opacity: 1;

        transform: translateY(0);

    }

}

</style>


<div class="admin-layout">


    <?php include("navbar.php"); ?>


    <div class="admin-content">


        <div class="messages-page">


            <div class="container-fluid">


                <!-- =================================================
                     HEADER
                ================================================== -->

                <div class="messages-header">


                    <div class="messages-heading">


                        <div class="messages-heading-icon">

                            <i class="fa-solid fa-envelope"></i>

                        </div>


                        <div>

                            <h2>
                                Contact Messages
                            </h2>

                            <p>
                                Manage messages received from website visitors
                            </p>

                        </div>


                    </div>


                    <a
                        href="dashboard.php"
                        class="dashboard-btn"
                    >

                        <i class="fa-solid fa-arrow-left"></i>

                        Dashboard

                    </a>


                </div>



                <!-- =================================================
                     STATISTICS
                ================================================== -->

                <div class="row g-4">


                    <!-- TOTAL -->

                    <div class="col-lg-4 col-md-6">

                        <div class="message-stat">

                            <div class="stat-icon red">

                                <i class="fa-solid fa-envelope"></i>

                            </div>

                            <h3>
                                <?= $total_messages; ?>
                            </h3>

                            <p>
                                Total Messages
                            </p>

                        </div>

                    </div>


                    <!-- UNREAD -->

                    <div class="col-lg-4 col-md-6">

                        <div class="message-stat">

                            <div class="stat-icon yellow">

                                <i class="fa-solid fa-bell"></i>

                            </div>

                            <h3>
                                <?= $unread; ?>
                            </h3>

                            <p>
                                Unread Messages
                            </p>

                        </div>

                    </div>


                    <!-- READ -->

                    <div class="col-lg-4 col-md-6">

                        <div class="message-stat">

                            <div class="stat-icon green">

                                <i class="fa-solid fa-envelope-open"></i>

                            </div>

                            <h3>
                                <?= $read_messages; ?>
                            </h3>

                            <p>
                                Read Messages
                            </p>

                        </div>

                    </div>


                </div>



                <!-- =================================================
                     UNREAD ALERT
                ================================================== -->

                <?php if ($unread > 0): ?>

                    <div class="unread-alert">


                        <div class="unread-alert-icon">

                            <i class="fa-solid fa-bell"></i>

                        </div>


                        <div>

                            <strong>

                                <?= $unread; ?>

                                unread message<?= $unread > 1 ? 's' : ''; ?>

                            </strong>


                            <span>

                                You have new messages that may require your attention.

                            </span>

                        </div>


                    </div>

                <?php endif; ?>



                <!-- =================================================
                     MAIN CARD
                ================================================== -->

                <div class="messages-card">


                    <div class="messages-card-header">


                        <h5>

                            <i class="fa-solid fa-inbox me-2"></i>

                            All Contact Messages

                        </h5>


                        <span>

                            <?= $total_messages; ?> Total

                        </span>


                    </div>



                    <div class="messages-card-body">


                        <?php if (mysqli_num_rows($result) > 0): ?>


                            <div class="table-responsive">


                                <table class="table messages-table">


                                    <thead>

                                        <tr>

                                            <th>ID</th>

                                            <th>Contact</th>

                                            <th>Email</th>

                                            <th>Subject</th>

                                            <th>Message</th>

                                            <th>Status</th>

                                            <th>Date</th>

                                            <th>Actions</th>

                                        </tr>

                                    </thead>


                                    <tbody>


                                    <?php while (
                                        $row = mysqli_fetch_assoc($result)
                                    ): ?>


                                        <tr class="<?= $row['status'] === 'Unread' ? 'unread-row' : ''; ?>">


                                            <!-- ID -->

                                            <td>

                                                <strong>

                                                    #<?= (int)$row['id']; ?>

                                                </strong>

                                            </td>



                                            <!-- CONTACT -->

                                            <td>


                                                <div class="user-info">


                                                    <div class="user-avatar">

                                                        <?= strtoupper(
                                                            substr(
                                                                $row['name'],
                                                                0,
                                                                1
                                                            )
                                                        ); ?>

                                                    </div>


                                                    <div>

                                                        <div class="user-name">

                                                            <?= htmlspecialchars(
                                                                $row['name']
                                                            ); ?>

                                                        </div>


                                                        <div class="user-email">

                                                            Visitor

                                                        </div>

                                                    </div>


                                                </div>


                                            </td>



                                            <!-- EMAIL -->

                                            <td>

                                                <a
                                                    href="mailto:<?= htmlspecialchars($row['email']); ?>"
                                                    class="text-decoration-none"
                                                >

                                                    <?= htmlspecialchars(
                                                        $row['email']
                                                    ); ?>

                                                </a>

                                            </td>



                                            <!-- SUBJECT -->

                                            <td>

                                                <div class="subject-text">

                                                    <?= htmlspecialchars(
                                                        $row['subject']
                                                    ); ?>

                                                </div>

                                            </td>



                                            <!-- MESSAGE -->

                                            <td>


                                                <div class="message-preview">


                                                    <div
                                                        class="message-preview-text"
                                                        title="<?= htmlspecialchars($row['message']); ?>"
                                                    >

                                                        <?= htmlspecialchars(
                                                            $row['message']
                                                        ); ?>

                                                    </div>


                                                </div>


                                            </td>



                                            <!-- STATUS -->

                                            <td>


                                                <?php if (
                                                    $row['status'] === 'Unread'
                                                ): ?>


                                                    <span class="status-badge status-unread">

                                                        <i class="fa-solid fa-circle"></i>

                                                        Unread

                                                    </span>


                                                <?php else: ?>


                                                    <span class="status-badge status-read">

                                                        <i class="fa-solid fa-check"></i>

                                                        Read

                                                    </span>


                                                <?php endif; ?>


                                            </td>



                                            <!-- DATE -->

                                            <td>


                                                <span class="date-text">

                                                    <i class="fa-regular fa-clock me-1"></i>

                                                    <?= htmlspecialchars(
                                                        $row['created_at']
                                                    ); ?>

                                                </span>


                                            </td>



                                            <!-- ACTIONS -->

                                            <td>


                                                <div class="message-actions">


                                                    <?php if (
                                                        $row['status'] === 'Unread'
                                                    ): ?>


                                                        <a
                                                            href="mark_message_read.php?id=<?= (int)$row['id']; ?>"
                                                            class="message-action action-read"
                                                        >

                                                            <i class="fa-solid fa-check"></i>

                                                            Mark Read

                                                        </a>


                                                    <?php endif; ?>


                                                    <a
                                                        href="reply_message.php?id=<?= (int)$row['id']; ?>"
                                                        class="message-action action-reply"
                                                    >

                                                        <i class="fa-solid fa-reply"></i>

                                                        Reply

                                                    </a>


                                                    <a
                                                        href="delete_message.php?id=<?= (int)$row['id']; ?>"
                                                        class="message-action action-delete"
                                                        onclick="return confirm('Are you sure you want to delete this message?');"
                                                    >

                                                        <i class="fa-solid fa-trash"></i>

                                                        Delete

                                                    </a>


                                                </div>


                                            </td>


                                        </tr>


                                    <?php endwhile; ?>


                                    </tbody>


                                </table>


                            </div>


                        <?php else: ?>


                            <!-- EMPTY -->

                            <div class="empty-message">


                                <div class="empty-message-icon">

                                    <i class="fa-solid fa-envelope-open"></i>

                                </div>


                                <h4>

                                    No Contact Messages

                                </h4>


                                <p>

                                    You haven't received any messages yet.

                                </p>


                            </div>


                        <?php endif; ?>


                    </div>


                </div>


            </div>


        </div>


    </div>


</div>


<?php include("../includes/footer.php"); ?>