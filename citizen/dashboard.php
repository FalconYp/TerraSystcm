<?php

session_start();

require_once '../config/database.php';
/** @var mysqli $conn */


if (!isset($_SESSION['users_id'])) {

    header("Location: ../login.php");
    exit();
}

$user_id   = $_SESSION['users_id'];
$user_name = $_SESSION['first_name'];



$query = "
SELECT 
    land_applications.land_applications_id,
    land_applications.a_type,
    land_applications.a_status,
    land_applications.current_stage,
    land_applications.submission_date,

    land_parcels.p_location,
    land_parcels.surface_area

FROM land_applications

LEFT JOIN land_parcels
ON land_applications.land_applications_id = land_parcels.land_applications_id

WHERE land_applications.users_id = '$user_id'

ORDER BY land_applications.submission_date DESC
";

$result = mysqli_query($conn, $query);

$sidebarRole   = 'Citizen';
$sidebarActive = 'dashboard';
$sidebarNav = [
    ['key' => 'dashboard',        'label' => 'Dashboard',            'href' => 'dashboard.php',              'icon' => 'bi-speedometer2'],
    ['key' => 'immatriculation',  'label' => 'Immatriculation',      'href' => 'apply_immatriculation.php',  'icon' => 'bi-file-earmark-text'],
    ['key' => 'verification',     'label' => 'Land Verification',    'href' => 'apply_verification.php',     'icon' => 'bi-search'],
    ['key' => 'transfer',         'label' => 'Land Transfer',        'href' => 'apply_transfer.php',         'icon' => 'bi-arrow-left-right'],
    ['key' => 'division',         'label' => 'Land Division',        'href' => 'apply_division.php',         'icon' => 'bi-arrows-angle-expand'],
];

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Citizen Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">

</head>

<body>

<?php include '../include/sidebar.php'; ?>

<div class="app-main">

    <div class="page-header">
        <h2>Citizen Dashboard</h2>
        <p>Welcome, <?php echo htmlspecialchars($user_name); ?></p>
    </div>

    <div class="page-header">
        <h2 style="font-size:20px;">Available Services</h2>
    </div>

    <div class="services-grid">

        <div class="service-card">
            <div class="service-icon blue"><i class="bi bi-file-earmark-text"></i></div>
            <div class="service-title">Land Immatriculation</div>
            <div class="service-description">Apply for direct immatriculation or concession</div>
            <a href="apply_immatriculation.php" class="service-btn">Open Service</a>
        </div>

        <div class="service-card">
            <div class="service-icon green"><i class="bi bi-search"></i></div>
            <div class="service-title">Land Verification</div>
            <div class="service-description">Verify land ownership and records</div>
            <a href="apply_verification.php" class="service-btn">Open Service</a>
        </div>

        <div class="service-card">
            <div class="service-icon purple"><i class="bi bi-arrow-left-right"></i></div>
            <div class="service-title">Land Transfer</div>
            <div class="service-description">Transfer land ownership</div>
            <a href="apply_transfer.php" class="service-btn">Open Service</a>
        </div>

        <div class="service-card">
            <div class="service-icon orange"><i class="bi bi-arrows-angle-expand"></i></div>
            <div class="service-title">Land Division</div>
            <div class="service-description">Divide land into multiple plots</div>
            <a href="apply_division.php" class="service-btn">Open Service</a>
        </div>

        <div class="service-card">
            <div class="service-icon indigo"><i class="bi bi-clipboard-check"></i></div>
            <div class="service-title">Workflow Tracking</div>
            <div class="service-description">Track your application progress</div>
            <a href="workflow_tracking.php" class="service-btn">Open Service</a>
        </div>

    </div>

    <div class="page-header">
        <h2 style="font-size:20px;">My Applications</h2>
    </div>

    <?php if(mysqli_num_rows($result) == 0): ?>

    <div class="card">
        <div class="card-body empty-state">
            You haven't submitted any applications yet.
        </div>
    </div>

    <?php endif; ?>

    <?php while($row = mysqli_fetch_assoc($result)): ?>

    <div class="application-card">

        <div class="application-top">

            <div>
                <div class="app-id">APP-2026-<?php echo $row['land_applications_id']; ?></div>
                <div class="app-type"><?php echo htmlspecialchars(ucfirst($row['a_type'])); ?></div>
            </div>

            <span class="badge bg-<?php echo strtolower($row['a_status']) === 'approved' ? 'approved' : (strtolower($row['a_status']) === 'declined' ? 'rejected' : 'pending'); ?>">
                <?php echo htmlspecialchars($row['a_status']); ?>
            </span>

        </div>

        <div class="application-grid">

            <div>
                <div class="info-label">Location:</div>
                <div class="info-value"><?php echo htmlspecialchars($row['p_location'] ?? 'N/A'); ?></div>
            </div>

            <div>
                <div class="info-label">Size:</div>
                <div class="info-value"><?php echo $row['surface_area'] ? htmlspecialchars($row['surface_area']).' sqm' : 'N/A'; ?></div>
            </div>

            <div>
                <div class="info-label">Current Stage:</div>
                <div class="info-value"><?php echo htmlspecialchars($row['current_stage']); ?></div>
            </div>

            <div>
                <div class="info-label">Submitted:</div>
                <div class="info-value"><?php echo date("d/m/Y", strtotime($row['submission_date'])); ?></div>
            </div>

        </div>

    </div>

    <?php endwhile; ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
