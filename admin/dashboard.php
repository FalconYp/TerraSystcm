<?php

include '../config/database.php';
include '../config/auth.php';

if($_SESSION['u_role'] != 'admin'){
    header("Location: ../login.php");
    exit();
}

$users_total = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM users"))['total'];
$apps_total  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM land_applications"))['total'];
$apps_pending = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM land_applications WHERE a_status='PENDING'"))['total'];

$sidebarRole   = 'Administrator';
$sidebarActive = 'dashboard';
$sidebarNav = [
    ['key' => 'dashboard', 'label' => 'Dashboard', 'href' => 'dashboard.php', 'icon' => 'bi-speedometer2'],
];
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>

<body>

<?php include '../include/sidebar.php'; ?>

<div class="app-main">

    <div class="page-header">
        <h2>Admin Dashboard</h2>
        <p>Welcome, <?php echo htmlspecialchars($_SESSION['first_name']); ?></p>
    </div>

    <div class="stats-grid">

        <div class="stat-card">
            <div class="stat-number pending"><?php echo $users_total; ?></div>
            <div class="stat-title">Total Users</div>
        </div>

        <div class="stat-card">
            <div class="stat-number pending"><?php echo $apps_total; ?></div>
            <div class="stat-title">Total Applications</div>
        </div>

        <div class="stat-card">
            <div class="stat-number pending"><?php echo $apps_pending; ?></div>
            <div class="stat-title">Pending Applications</div>
        </div>

    </div>

    <div class="card">
        <div class="card-body empty-state">
            User management, reports, and system settings pages are not
            built yet (manage_users.php, reports.php, statistics.php,
            system_logs.php are still empty files).
        </div>
    </div>

</div>

</body>
</html>
