
<?php

session_start();


// =====================================================
// CHECK LOGIN
// =====================================================

if (!isset($_SESSION['recipient_id'])) {

    header("Location: login.php");
    exit();

}


include("../includes/db.php");


// =====================================================
// DATABASE CONNECTION
// =====================================================

if ($conn->connect_error) {

    die(
        "Database Connection Failed: " .
        htmlspecialchars($conn->connect_error)
    );

}


// =====================================================
// RECIPIENT ID
// =====================================================

$recipient_id = $_SESSION['recipient_id'];


// =====================================================
// UPDATE PROFILE
// =====================================================

if ($_SERVER["REQUEST_METHOD"] === "POST") {


    $name = trim($_POST['name'] ?? '');

    $email = trim($_POST['email'] ?? '');

    $phone = trim($_POST['phone'] ?? '');

    $blood_group = trim($_POST['blood_group'] ?? '');

    $city = trim($_POST['city'] ?? '');

    $hospital = trim($_POST['hospital'] ?? '');

    $address = trim($_POST['address'] ?? '');


    // -------------------------------------------------
    // VALIDATION
    // -------------------------------------------------

    if (
        empty($name) ||
        empty($email) ||
        empty($phone) ||
        empty($blood_group) ||
        empty($city) ||
        empty($hospital) ||
        empty($address)
    ) {

        echo "<script>

            alert('Please fill all fields.');

            window.location='profile.php';

        </script>";

        exit();

    }


    // -------------------------------------------------
    // EMAIL VALIDATION
    // -------------------------------------------------

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        echo "<script>

            alert('Please enter a valid email address.');

            window.location='profile.php';

        </script>";

        exit();

    }


    // -------------------------------------------------
    // CHECK EMAIL USED BY ANOTHER RECIPIENT
    // -------------------------------------------------

    $check = $conn->prepare("
        SELECT id
        FROM recipients
        WHERE email = ?
        AND id != ?
    ");


    if (!$check) {

        die(
            "Database Error: " .
            htmlspecialchars($conn->error)
        );

    }


    $check->bind_param(
        "si",
        $email,
        $recipient_id
    );


    $check->execute();


    $result = $check->get_result();


    if ($result->num_rows > 0) {

        $check->close();

        echo "<script>

            alert('This email is already registered by another recipient.');

            window.location='profile.php';

        </script>";

        exit();

    }


    $check->close();


    // -------------------------------------------------
    // UPDATE PROFILE
    // -------------------------------------------------

    $update = $conn->prepare("
        UPDATE recipients
        SET
            name = ?,
            email = ?,
            phone = ?,
            blood_group = ?,
            city = ?,
            hospital = ?,
            address = ?
        WHERE id = ?
    ");


    if (!$update) {

        die(
            "Update Prepare Error: " .
            htmlspecialchars($conn->error)
        );

    }


    $update->bind_param(
        "sssssssi",
        $name,
        $email,
        $phone,
        $blood_group,
        $city,
        $hospital,
        $address,
        $recipient_id
    );


    if ($update->execute()) {


        // -------------------------------------------------
        // UPDATE SESSION NAME
        // -------------------------------------------------

        $_SESSION['recipient_name'] = $name;


        echo "<script>

            alert('Profile updated successfully!');

            window.location='profile.php';

        </script>";


        $update->close();

        $conn->close();

        exit();


    } else {


        echo "<script>

            alert('Profile update failed!');

            window.location='profile.php';

        </script>";

    }


    $update->close();

}


// =====================================================
// GET CURRENT PROFILE
// =====================================================

$stmt = $conn->prepare("
    SELECT
        name,
        email,
        phone,
        blood_group,
        city,
        hospital,
        address
    FROM recipients
    WHERE id = ?
");


if (!$stmt) {

    die(
        "Profile Prepare Error: " .
        htmlspecialchars($conn->error)
    );

}


$stmt->bind_param(
    "i",
    $recipient_id
);


$stmt->execute();


$result = $stmt->get_result();


if ($result->num_rows !== 1) {

    $stmt->close();

    session_destroy();

    header("Location: login.php");

    exit();

}


$recipient = $result->fetch_assoc();


$stmt->close();


// =====================================================
// HEADER
// =====================================================

include("../includes/header.php");

include("../includes/navbar.php");

?>


<div class="container py-5">


    <!-- =================================================
         PAGE HEADER
    ================================================== -->

    <div class="card shadow border-0 mb-4">


        <div class="card-header bg-primary text-white p-4">


            <h2 class="mb-1">

                👤 My Profile

            </h2>


            <p class="mb-0">

                Update your recipient information.

            </p>


        </div>


    </div>



    <!-- =================================================
         PROFILE FORM
    ================================================== -->

    <div class="card shadow">


        <div class="card-body p-4">


            <form method="POST">


                <div class="row g-4">


                    <!-- NAME -->

                    <div class="col-md-6">


                        <label class="form-label fw-bold">

                            Name

                        </label>


                        <input
                            type="text"
                            name="name"
                            class="form-control"
                            value="<?= htmlspecialchars(
                                $recipient['name']
                            ); ?>"
                            required
                        >


                    </div>



                    <!-- EMAIL -->

                    <div class="col-md-6">


                        <label class="form-label fw-bold">

                            Email

                        </label>


                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            value="<?= htmlspecialchars(
                                $recipient['email']
                            ); ?>"
                            required
                        >


                    </div>



                    <!-- PHONE -->

                    <div class="col-md-6">


                        <label class="form-label fw-bold">

                            Phone

                        </label>


                        <input
                            type="tel"
                            name="phone"
                            class="form-control"
                            value="<?= htmlspecialchars(
                                $recipient['phone']
                            ); ?>"
                            required
                        >


                    </div>



                    <!-- BLOOD GROUP -->

                    <div class="col-md-6">


                        <label class="form-label fw-bold">

                            Blood Group

                        </label>


                        <select
                            name="blood_group"
                            class="form-select"
                            required
                        >


                            <option value="">

                                Select Blood Group

                            </option>


                            <?php

                            $bloodGroups = [
                                "A+",
                                "A-",
                                "B+",
                                "B-",
                                "AB+",
                                "AB-",
                                "O+",
                                "O-"
                            ];


                            foreach (
                                $bloodGroups
                                as $group
                            ):

                            ?>


                                <option
                                    value="<?= $group; ?>"
                                    <?= (
                                        $recipient[
                                            'blood_group'
                                        ] === $group
                                    )
                                    ? "selected"
                                    : ""; ?>
                                >

                                    <?= $group; ?>

                                </option>


                            <?php endforeach; ?>


                        </select>


                    </div>



                    <!-- CITY -->

                    <div class="col-md-6">


                        <label class="form-label fw-bold">

                            City

                        </label>


                        <input
                            type="text"
                            name="city"
                            class="form-control"
                            value="<?= htmlspecialchars(
                                $recipient['city']
                            ); ?>"
                            required
                        >


                    </div>



                    <!-- HOSPITAL -->

                    <div class="col-md-6">


                        <label class="form-label fw-bold">

                            Hospital

                        </label>


                        <input
                            type="text"
                            name="hospital"
                            class="form-control"
                            value="<?= htmlspecialchars(
                                $recipient['hospital']
                            ); ?>"
                            required
                        >


                    </div>



                    <!-- ADDRESS -->

                    <div class="col-12">


                        <label class="form-label fw-bold">

                            Address

                        </label>


                        <textarea
                            name="address"
                            class="form-control"
                            rows="4"
                            required
                        ><?= htmlspecialchars(
                            $recipient['address']
                        ); ?></textarea>


                    </div>



                    <!-- BUTTONS -->

                    <div class="col-12">


                        <hr>


                        <button
                            type="submit"
                            class="btn btn-primary btn-lg"
                        >

                            💾 Update Profile

                        </button>


                        <a
                            href="dashboard.php"
                            class="btn btn-secondary btn-lg ms-2"
                        >

                            ← Back to Dashboard

                        </a>


                    </div>


                </div>


            </form>


        </div>


    </div>


</div>


<?php

include("../includes/footer.php");

?>

