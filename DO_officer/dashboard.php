<?php

session_start();

include '../config/database.php';
/** @var mysqli $conn */


if(
    !isset($_SESSION['users_id']) ||
    $_SESSION['u_role'] != 'DO_officer'
){
    header("Location: ../login.php");
    exit();
}

$user_name = $_SESSION['first_name'] . " " . $_SESSION['last_name'];


$pending_query = mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM land_applications
WHERE a_status='PENDING'
");

$pending = mysqli_fetch_assoc($pending_query)['total'];


$approved_query = mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM land_applications
WHERE a_status='APPROVED'
AND MONTH(submission_date)=MONTH(CURRENT_DATE())
AND YEAR(submission_date)=YEAR(CURRENT_DATE())
");

$approved = mysqli_fetch_assoc($approved_query)['total'];


$rejected_query = mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM land_applications
WHERE a_status='DECLINED'
AND MONTH(submission_date)=MONTH(CURRENT_DATE())
AND YEAR(submission_date)=YEAR(CURRENT_DATE())
");

$rejected = mysqli_fetch_assoc($rejected_query)['total'];


$query = "
SELECT
    land_applications.land_applications_id,
    land_applications.a_type,
    land_applications.a_status,
    land_applications.current_stage,
    land_applications.submission_date,

    users.first_name,
    users.last_name,
    users.email,

    land_parcels.p_location,
    land_parcels.surface_area

FROM land_applications

LEFT JOIN users
ON land_applications.users_id = users.users_id

LEFT JOIN land_parcels
ON land_applications.land_applications_id =
land_parcels.land_applications_id

ORDER BY land_applications.submission_date DESC
";

$result = mysqli_query($conn, $query);

$sidebarRole   = 'DO Officer';
$sidebarActive = 'dashboard';
$sidebarNav = [
    ['key' => 'dashboard', 'label' => 'Dashboard', 'href' => 'dashboard.php', 'icon' => 'bi-speedometer2'],
];

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>DO's Office Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">

</head>

<body>

<?php include '../include/sidebar.php'; ?>

<div class="app-main">

    <div class="page-header">
        <h2>DO's Office Dashboard</h2>
        <p>Welcome, <?php echo htmlspecialchars($user_name); ?></p>
    </div>

    <div class="stats-grid">

        <div class="stat-card">
            <div class="stat-number pending"><?php echo $pending; ?></div>
            <div class="stat-title">Pending Review</div>
        </div>

        <div class="stat-card">
            <div class="stat-number approved"><?php echo $approved; ?></div>
            <div class="stat-title">Approved This Month</div>
        </div>

        <div class="stat-card">
            <div class="stat-number rejected"><?php echo $rejected; ?></div>
            <div class="stat-title">Rejected This Month</div>
        </div>

    </div>

    <div class="page-header">
        <h2 style="font-size:20px;">Applications for Review</h2>
    </div>

    <?php if(mysqli_num_rows($result) == 0): ?>

    <div class="card">
        <div class="card-body empty-state">
            No applications to review right now.
        </div>
    </div>

    <?php endif; ?>

    <?php while($row = mysqli_fetch_assoc($result)): ?>

    <div class="application-card">

        <div class="application-top">

            <div>
                <div class="app-id">APP-2026-<?php echo $row['land_applications_id']; ?></div>
                <div class="app-type">
                    <?php echo htmlspecialchars($row['first_name']." ".$row['last_name']); ?>
                    -
                    <?php echo htmlspecialchars(ucfirst($row['a_type'])); ?>
                </div>
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
                <div class="info-label">Submitted:</div>
                <div class="info-value"><?php echo date("d/m/Y", strtotime($row['submission_date'])); ?></div>
            </div>

            <div>
                <div class="info-label">Applicant Email:</div>
                <div class="info-value"><?php echo htmlspecialchars($row['email']); ?></div>
            </div>

        </div>

        <a href="review_application.php?id=<?php echo $row['land_applications_id']; ?>" class="review-btn">
            <i class="bi bi-eye"></i>
            Review Application
        </a>

    </div>

    <?php endwhile; ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
