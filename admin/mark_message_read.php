<?php

session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include("../includes/db.php");


$id = intval($_GET['id'] ?? 0);


if ($id > 0) {

    $stmt = $conn->prepare("
        UPDATE contact_messages
        SET status='Read'
        WHERE id=?
    ");

    $stmt->bind_param("i", $id);

    $stmt->execute();

    $stmt->close();
}


header("Location: contact_messages.php");
exit();

?>