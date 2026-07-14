<?php
include '../config/database.php';
include '../config/auth.php';

if($_SESSION['u_role'] != 'mise_a_jours'){
    header("Location: ../login.php");
    exit();
}

$sidebarRole   = 'Mise A Jour';
$sidebarActive = 'dashboard';
$sidebarNav = [
    ['key' => 'dashboard', 'label' => 'Dashboard', 'href' => 'dashboard.php', 'icon' => 'bi-speedometer2'],
];
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Mise A Jour Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>

<body>

<?php include '../include/sidebar.php'; ?>

<div class="app-main">

    <div class="page-header">
        <h2>Mise A Jour Dashboard</h2>
        <p>Welcome, <?php echo htmlspecialchars($_SESSION['first_name']); ?></p>
    </div>

    <div class="card">
        <div class="card-body empty-state">
            No features implemented yet for this role.<br>
            Note: 'mise_a_jours' isn't a valid role in the users table's
            u_role list yet, so no account can currently reach this page -
            this still needs the ENUM/login-switch decision flagged earlier.
        </div>
    </div>

</div>

</body>
</html>
