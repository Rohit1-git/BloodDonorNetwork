<?php
session_start();

if (!isset($_SESSION['donor_id'])) {
    header("Location: login.php");
    exit();
}

include("../includes/db.php");

if (isset($_POST['change_password'])) {

    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    $donor_id = $_SESSION['donor_id'];

    // Fetch current password
    $stmt = $conn->prepare("SELECT password FROM donors WHERE id=?");
    $stmt->bind_param("i", $donor_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $donor = $result->fetch_assoc();

    // Verify current password
    if (!password_verify($current_password, $donor['password'])) {

        echo "<script>
        alert('Current Password is Incorrect!');
        window.location='change_password.php';
        </script>";
        exit();

    }

    // Check new password
    if ($new_password != $confirm_password) {

        echo "<script>
        alert('New Password and Confirm Password do not match!');
        window.location='change_password.php';
        </script>";
        exit();

    }

    // Hash new password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Update password
    $update = $conn->prepare("UPDATE donors SET password=? WHERE id=?");
    $update->bind_param("si", $hashed_password, $donor_id);

    if ($update->execute()) {

        echo "<script>
        alert('Password Changed Successfully!');
        window.location='dashboard.php';
        </script>";

    } else {

        echo "<script>
        alert('Something went wrong!');
        </script>";

    }
}

include("../includes/header.php");
include("../includes/navbar.php");
?>

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-md-6">

            <div class="card shadow">

                <div class="card-header bg-danger text-white">
                    <h3>Change Password</h3>
                </div>

                <div class="card-body">

                    <form method="POST">

                        <div class="mb-3">
                            <label>Current Password</label>
                            <input type="password"
                                   name="current_password"
                                   class="form-control"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label>New Password</label>
                            <input type="password"
                                   name="new_password"
                                   class="form-control"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label>Confirm New Password</label>
                            <input type="password"
                                   name="confirm_password"
                                   class="form-control"
                                   required>
                        </div>

                        <button type="submit"
                                name="change_password"
                                class="btn btn-danger w-100">
                            Change Password
                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

<?php include("../includes/footer.php"); ?>