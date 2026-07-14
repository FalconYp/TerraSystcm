<?php
session_start();
include("../config/database.php");
/** @var mysqli $conn */

if(!isset($_SESSION['users_id'])){
    header("Location: ../login.php");
    exit();
}

if($_SESSION['u_role'] != "committee"){
    header("Location: ../login.php");
    exit();
}

$committee_name = $_SESSION['first_name'];

$query = mysqli_query($conn,"
SELECT
la.land_applications_id,
la.a_type,
la.a_status,
la.submission_date,
la.current_stage,
u.first_name,
u.last_name,
di.land_location,
di.land_size

FROM land_applications la

INNER JOIN users u
ON la.users_id=u.users_id

LEFT JOIN direct_immatriculation di
ON la.land_applications_id=di.land_applications_id

WHERE la.current_stage='Committee Review'

ORDER BY la.submission_date DESC
");

$sidebarRole   = 'Committee';
$sidebarActive = 'dashboard';
$sidebarNav = [
    ['key' => 'dashboard', 'label' => 'Dashboard', 'href' => 'dashboard.php', 'icon' => 'bi-speedometer2'],
];
?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">
<title>Committee Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="../assets/css/dashboard.css">

</head>

<body>

<?php include '../include/sidebar.php'; ?>

<div class="app-main">

<div class="page-header">
    <h2>Committee Dashboard</h2>
    <p>Applications awaiting Mise en Valeur (field) verification.</p>
</div>

<div class="card">
<div class="card-body">

<table class="table table-bordered table-hover mb-0">
<thead class="table-dark">
<tr>
<th>ID</th>
<th>Applicant</th>
<th>Application</th>
<th>Location</th>
<th>Land Size</th>
<th>Status</th>
<th>Date</th>
<th>Action</th>
</tr>
</thead>
<tbody>
<?php if(mysqli_num_rows($query)>0): ?>
<?php while($row=mysqli_fetch_assoc($query)): ?>
<tr>
<td><?php echo $row['land_applications_id']; ?></td>
<td><?php echo htmlspecialchars($row['first_name']." ".$row['last_name']); ?></td>
<td><?php echo htmlspecialchars(ucfirst($row['a_type'])); ?></td>
<td><?php echo htmlspecialchars($row['land_location'] ?? 'N/A'); ?></td>
<td><?php echo $row['land_size'] ? htmlspecialchars($row['land_size']).' m²' : 'N/A'; ?></td>
<td><span class="badge bg-warning"><?php echo htmlspecialchars($row['current_stage']); ?></span></td>
<td><?php echo htmlspecialchars($row['submission_date']); ?></td>
<td>
<a class="btn btn-primary btn-sm" href="review_application.php?id=<?php echo $row['land_applications_id']; ?>">
Review
</a>
</td>
</tr>
<?php endwhile; ?>
<?php else: ?>
<tr>
<td colspan="8" class="text-center">No applications waiting for committee review.</td>
</tr>
<?php endif; ?>
</tbody>
</table>

</div>
</div>

</div>

</body>
</html>
