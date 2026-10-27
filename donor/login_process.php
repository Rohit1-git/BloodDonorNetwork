<?php
session_start();
include("../includes/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM donors WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {

        $donor = $result->fetch_assoc();

        if (password_verify($password, $donor['password'])) {

            $_SESSION['donor_id'] = $donor['id'];
            $_SESSION['donor_name'] = $donor['name'];
            $_SESSION['donor_email'] = $donor['email'];

            header("Location: dashboard.php");
            exit();

        } else {

            echo "<script>
                alert('Invalid Password');
                window.location='login.php';
            </script>";
        }

    } else {

        echo "<script>
            alert('Email Not Registered');
            window.location='login.php';
        </script>";
    }

    $stmt->close();
    $conn->close();
}
?>