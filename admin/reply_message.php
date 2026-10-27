<?php

session_start();


// =====================================================
// CHECK ADMIN LOGIN
// =====================================================

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}


// =====================================================
// DATABASE
// =====================================================

include("../includes/db.php");


// =====================================================
// PHPMailer
// =====================================================

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once("../vendor/autoload.php");


// =====================================================
// GET MESSAGE ID
// =====================================================

$message_id = intval($_GET['id'] ?? $_POST['message_id'] ?? 0);


if ($message_id <= 0) {

    header("Location: contact_messages.php");
    exit();

}


// =====================================================
// GET ORIGINAL CONTACT MESSAGE
// =====================================================

$stmt = $conn->prepare("
    SELECT
        id,
        name,
        email,
        subject,
        message,
        created_at
    FROM contact_messages
    WHERE id = ?
");


if (!$stmt) {

    die(
        "Database Prepare Error: " .
        htmlspecialchars($conn->error)
    );

}


$stmt->bind_param("i", $message_id);

$stmt->execute();

$result = $stmt->get_result();

$message_data = $result->fetch_assoc();

$stmt->close();


if (!$message_data) {

    die("Contact message not found.");

}


// =====================================================
// SEND REPLY
// =====================================================

$success = "";
$error = "";


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $reply_message = trim(
        $_POST['reply_message'] ?? ''
    );


    // Validation

    if (empty($reply_message)) {

        $error = "Please enter a reply message.";

    } else {


        // =================================================
        // PHPMailer
        // =================================================

        $mail = new PHPMailer(true);


        try {

            // SMTP
            $mail->isSMTP();

            $mail->Host = "smtp.gmail.com";

            $mail->SMTPAuth = true;


            // =================================================
            // YOUR GMAIL ACCOUNT
            // =================================================

            $mail->Username = "";

            $mail->Password = ""; // Use your Gmail App Password


            // Security
            $mail->SMTPSecure =
                PHPMailer::ENCRYPTION_STARTTLS;

            $mail->Port = 587;


            // =================================================
            // SENDER
            // =================================================

            $mail->setFrom(
                $mail->Username,
                "Blood Donor Emergency Network"
            );


            // =================================================
            // RECIPIENT
            // =================================================

            $mail->addAddress(
                $message_data['email'],
                $message_data['name']
            );


            // =================================================
            // EMAIL
            // =================================================

            $mail->isHTML(true);


            $mail->Subject =
                "Re: " . $message_data['subject'];


            // Escape reply for HTML

            $safe_reply = nl2br(
                htmlspecialchars($reply_message)
            );


            $safe_name = htmlspecialchars(
                $message_data['name']
            );


            $safe_subject = htmlspecialchars(
                $message_data['subject']
            );


            // =================================================
            // HTML BODY
            // =================================================

            $mail->Body = "

            <!DOCTYPE html>

            <html>

            <head>

                <meta charset='UTF-8'>

                <title>Reply - Blood Donor Network</title>

            </head>


            <body style='
                margin:0;
                padding:20px;
                background:#f5f5f5;
                font-family:Arial,sans-serif;
            '>


                <div style='
                    max-width:650px;
                    margin:auto;
                    background:white;
                    padding:30px;
                    border-radius:10px;
                    box-shadow:0 2px 10px rgba(0,0,0,0.1);
                '>


                    <h2 style='
                        color:#dc3545;
                        text-align:center;
                    '>

                        🩸 Blood Donor Emergency Network

                    </h2>


                    <hr>


                    <p>

                        Hello
                        <strong>
                            {$safe_name}
                        </strong>,

                    </p>


                    <p>

                        Thank you for contacting
                        Blood Donor Emergency Network.

                    </p>


                    <p>

                        <strong>Your Subject:</strong>
                        {$safe_subject}

                    </p>


                    <hr>


                    <h4>
                        Admin Reply
                    </h4>


                    <div style='
                        background:#f8f9fa;
                        padding:20px;
                        border-left:4px solid #dc3545;
                        border-radius:5px;
                        line-height:1.6;
                    '>

                        {$safe_reply}

                    </div>


                    <hr>


                    <p style='color:#777;'>

                        Regards,<br>

                        <strong>
                            Blood Donor Emergency Network
                        </strong>

                    </p>


                    <p style='
                        color:#999;
                        text-align:center;
                        font-size:12px;
                    '>

                        This email was sent in response
                        to your contact request.

                    </p>


                </div>

            </body>

            </html>

            ";


            // =================================================
            // PLAIN TEXT VERSION
            // =================================================

            $mail->AltBody =
                "Hello " .
                $message_data['name'] .
                ",\n\n" .

                "Thank you for contacting " .
                "Blood Donor Emergency Network.\n\n" .

                "Subject: " .
                $message_data['subject'] .
                "\n\n" .

                "Admin Reply:\n\n" .

                $reply_message .
                "\n\n" .

                "Regards,\n" .
                "Blood Donor Emergency Network";


            // =================================================
            // SEND
            // =================================================

            $mail->send();


            // =================================================
            // MARK MESSAGE AS READ
            // =================================================

            $update = $conn->prepare("
                UPDATE contact_messages
                SET status = 'Read'
                WHERE id = ?
            ");


            if ($update) {

                $update->bind_param(
                    "i",
                    $message_id
                );

                $update->execute();

                $update->close();

            }


            $success =
                "Reply sent successfully!";


        } catch (Exception $e) {

            $error =
                "Email could not be sent. " .
                htmlspecialchars(
                    $mail->ErrorInfo
                );

        }

    }

}


include("../includes/header.php");

?>


<div class="admin-layout">


    <?php include("navbar.php"); ?>


    <div class="admin-content">


        <div class="container-fluid py-4">


            <!-- =================================================
                 HEADER
            ================================================= -->

            <div class="d-flex justify-content-between
                        align-items-center mb-4">

                <div>

                    <h2 class="fw-bold">

                        ✉ Reply to Message

                    </h2>

                    <p class="text-muted mb-0">

                        Send a reply to the website visitor.

                    </p>

                </div>


                <a
                    href="contact_messages.php"
                    class="btn btn-secondary"
                >

                    ← Back to Messages

                </a>

            </div>



            <!-- =================================================
                 SUCCESS
            ================================================= -->

            <?php if (!empty($success)): ?>

                <div class="alert alert-success">

                    <strong>✓ Success</strong>

                    <br>

                    <?= htmlspecialchars($success); ?>

                    <br><br>

                    <a
                        href="contact_messages.php"
                        class="btn btn-success btn-sm"
                    >

                        Back to Messages

                    </a>

                </div>

            <?php endif; ?>



            <!-- =================================================
                 ERROR
            ================================================= -->

            <?php if (!empty($error)): ?>

                <div class="alert alert-danger">

                    <strong>⚠ Error</strong>

                    <br>

                    <?= htmlspecialchars($error); ?>

                </div>

            <?php endif; ?>



            <div class="row g-4">


                <!-- =================================================
                     ORIGINAL MESSAGE
                ================================================= -->

                <div class="col-lg-5">


                    <div class="card shadow">


                        <div class="card-header bg-dark text-white">

                            <h5 class="mb-0">

                                📩 Original Message

                            </h5>

                        </div>


                        <div class="card-body">


                            <p>

                                <strong>Name:</strong>

                                <br>

                                <?= htmlspecialchars(
                                    $message_data['name']
                                ); ?>

                            </p>


                            <p>

                                <strong>Email:</strong>

                                <br>

                                <a
                                    href="mailto:<?= htmlspecialchars(
                                        $message_data['email']
                                    ); ?>"
                                >

                                    <?= htmlspecialchars(
                                        $message_data['email']
                                    ); ?>

                                </a>

                            </p>


                            <p>

                                <strong>Subject:</strong>

                                <br>

                                <?= htmlspecialchars(
                                    $message_data['subject']
                                ); ?>

                            </p>


                            <p>

                                <strong>Date:</strong>

                                <br>

                                <?= htmlspecialchars(
                                    $message_data['created_at']
                                ); ?>

                            </p>


                            <hr>


                            <strong>
                                Message:
                            </strong>


                            <div class="bg-light p-3 mt-2 rounded">

                                <?= nl2br(
                                    htmlspecialchars(
                                        $message_data['message']
                                    )
                                ); ?>

                            </div>


                        </div>

                    </div>


                </div>



                <!-- =================================================
                     REPLY FORM
                ================================================= -->

                <div class="col-lg-7">


                    <div class="card shadow">


                        <div class="card-header bg-danger text-white">

                            <h5 class="mb-0">

                                ✉ Write Reply

                            </h5>

                        </div>


                        <div class="card-body">


                            <form
                                method="POST"
                                action=""
                            >


                                <input
                                    type="hidden"
                                    name="message_id"
                                    value="<?= $message_data['id']; ?>"
                                >


                                <!-- Recipient -->

                                <div class="mb-3">

                                    <label class="form-label">

                                        To

                                    </label>

                                    <input
                                        type="email"
                                        class="form-control"
                                        value="<?= htmlspecialchars(
                                            $message_data['email']
                                        ); ?>"
                                        readonly
                                    >

                                </div>


                                <!-- Subject -->

                                <div class="mb-3">

                                    <label class="form-label">

                                        Subject

                                    </label>

                                    <input
                                        type="text"
                                        class="form-control"
                                        value="Re: <?= htmlspecialchars(
                                            $message_data['subject']
                                        ); ?>"
                                        readonly
                                    >

                                </div>


                                <!-- Reply -->

                                <div class="mb-3">

                                    <label class="form-label">

                                        Your Reply

                                    </label>

                                    <textarea
                                        name="reply_message"
                                        rows="10"
                                        class="form-control"
                                        placeholder="Write your reply here..."
                                        required
                                    ></textarea>

                                </div>


                                <!-- Buttons -->

                                <div class="d-flex gap-2">

                                    <button
                                        type="submit"
                                        class="btn btn-danger"
                                    >

                                        📤 Send Reply

                                    </button>


                                    <a
                                        href="contact_messages.php"
                                        class="btn btn-secondary"
                                    >

                                        Cancel

                                    </a>

                                </div>


                            </form>


                        </div>

                    </div>


                </div>


            </div>


        </div>

    </div>

</div>


<?php include("../includes/footer.php"); ?>