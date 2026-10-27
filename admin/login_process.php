<?php
session_start();
include("../includes/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM admin WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        $admin = $result->fetch_assoc();

        if (password_verify($password, $admin['password'])) {

            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['username'];

            header("Location: dashboard.php");
            exit();

        } else {
            echo "<script>alert('Invalid Password'); window.location='login.php';</script>";
        }

    } else {
        echo "<script>alert('Admin Not Found'); window.location='login.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>