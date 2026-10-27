<!-- <?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$current = basename($_SERVER['PHP_SELF']);
?>

<div class="admin-sidebar">

    <div class="admin-logo">
        <i class="fa-solid fa-droplet text-danger"></i><br>
        <span>Blood Donor</span><br>
        <small>Admin Panel</small>
    </div>

    <ul class="admin-menu">

        <li>
            <a href="dashboard.php" class="<?= $current == 'dashboard.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-gauge-high"></i>
                Dashboard
            </a>
        </li>

        <li>
            <a href="donors.php" class="<?= $current == 'donors.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-user-group"></i>
                Manage Donors
            </a>
        </li>

        <li>
            <a href="recipients.php" class="<?= $current == 'recipients.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-users"></i>
                Manage Recipients
            </a>
        </li>

        <li>
            <a href="request.php" class="<?= $current == 'request.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-droplet"></i>
                Manage Requests
            </a>
        </li>

        <li>
            <a href="hospitals.php" class="<?= $current == 'hospitals.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-hospital"></i>
                Manage Hospitals
            </a>
        </li>

        <li>
            <a href="reports.php" class="<?= $current == 'reports.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-chart-column"></i>
                Reports
            </a>
        </li>

        <li>
            <a href="settings.php" class="<?= $current == 'settings.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-gear"></i>
                Settings
            </a>
        </li>

        <li class="logout">
            <a href="logout.php">
                <i class="fa-solid fa-right-from-bracket"></i>
                Logout
            </a>
        </li>

    </ul>

</div> -->