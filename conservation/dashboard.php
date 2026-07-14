<?php
include '../config/database.php';
include '../config/auth.php';

if($_SESSION['u_role'] != 'conservation'){
    header("Location: ../login.php");
    exit();
}

$sidebarRole   = 'Conservation';
$sidebarActive = 'dashboard';
$sidebarNav = [
    ['key' => 'dashboard', 'label' => 'Dashboard', 'href' => 'dashboard.php', 'icon' => 'bi-speedometer2'],
];
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Conservation Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>

<body>

<?php include '../include/sidebar.php'; ?>

<div class="app-main">

    <div class="page-header">
        <h2>Conservation Dashboard</h2>
        <p>Welcome, <?php echo htmlspecialchars($_SESSION['first_name']); ?></p>
    </div>

    <div class="card">
        <div class="card-body empty-state">
            Final registration queue is not built yet.<br>
            This stage creates the Land Title, registers the owner, assigns
            a Land Certificate Number, and archives documents.
        </div>
    </div>

</div>

</body>
</html>
