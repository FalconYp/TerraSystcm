<?php

session_start();

include '../config/database.php';
/** @var mysqli $conn */

if(!isset($_SESSION['users_id']))
{
    header("Location: ../login.php");
    exit();
}

if($_SESSION['u_role'] != 'DO_officer')
{
    die("Access Denied");
}

if(!isset($_GET['id']))
{
    die("Application ID Missing");
}

$application_id = intval($_GET['id']);

$query = "
SELECT
    la.*,
    u.first_name,
    u.last_name,
    u.email,
    u.phone_number

FROM land_applications la

INNER JOIN users u
ON la.users_id = u.users_id

WHERE la.land_applications_id = '$application_id'
";

$result = mysqli_query($conn,$query);

if(mysqli_num_rows($result) == 0)
{
    die("Application Not Found");
}

$application = mysqli_fetch_assoc($result);

if(isset($_POST['approve']))
{
    // NOTE: a_status stays PENDING here - this is only the first of several
    // approval stages (Committee -> Land Affairs -> Survey -> Conservation).
    // a_status only becomes APPROVED once Conservation issues the certificate.
    mysqli_query($conn,"
    UPDATE land_applications
    SET
        a_status='PENDING',
        current_stage='Committee Review'
    WHERE land_applications_id='$application_id'
    ");

    mysqli_query($conn,"
    INSERT INTO workflow_stages
    (
        land_applications_id,
        actor_role,
        application_status
    )
    VALUES
    (
        '$application_id',
        'DO_officer',
        'APPROVED'
    )
    ");

    mysqli_query($conn,"
    INSERT INTO notifications
    (
        users_id,
        n_message
    )
    VALUES
    (
        '".$application['users_id']."',
        'Your application has been approved by the DO Officer and forwarded to the Committee for a Mise en Valeur (field) verification.'
    )
    ");

    $_SESSION['success_message'] =
    "Application Approved Successfully";

    header("Location: dashboard.php?success=approved");
    exit();
}

if(isset($_POST['reject']))
{
    mysqli_query($conn,"
    UPDATE land_applications
    SET
        a_status='DECLINED',
        current_stage='Rejected By DO Officer'
    WHERE land_applications_id='$application_id'
    ");

    mysqli_query($conn,"
    INSERT INTO workflow_stages
    (
        land_applications_id,
        actor_role,
        application_status
    )
    VALUES
    (
        '$application_id',
        'DO_officer',
        'REJECTED'
    )
    ");

    mysqli_query($conn,"
    INSERT INTO notifications
    (
        users_id,
        n_message
    )
    VALUES
    (
        '".$application['users_id']."',
        'Your application has been rejected by the DO Officer.'
    )
    ");

    $_SESSION['success_message'] =
    "Application Rejected";

    header("Location: dashboard.php?success=rejected");
    exit();
}

?>

<!DOCTYPE html>
<html>
<head>

<title>Review Application</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="../assets/css/dashboard.css">

</head>

<body>

<?php
$sidebarRole   = 'DO Officer';
$sidebarActive = 'dashboard';
$sidebarNav = [
    ['key' => 'dashboard', 'label' => 'Dashboard', 'href' => 'dashboard.php', 'icon' => 'bi-speedometer2'],
];
include '../include/sidebar.php';
?>

<div class="app-main">

<div class="page-header">
    <h2>Application Review</h2>
    <p><a href="dashboard.php" style="color:#2563eb;text-decoration:none;">&larr; Back to Dashboard</a></p>
</div>

<div class="review-card">

<h4>Applicant Information</h4>

<p><strong>Name:</strong>
<?php echo htmlspecialchars($application['first_name'].' '.$application['last_name']); ?></p>

<p><strong>Email:</strong>
<?php echo htmlspecialchars($application['email']); ?></p>

<p><strong>Phone:</strong>
<?php echo htmlspecialchars($application['phone_number']); ?></p>

<p><strong>Application Type:</strong>
<?php echo htmlspecialchars(ucfirst($application['a_type'])); ?></p>

<p><strong>Status:</strong>
<?php echo htmlspecialchars($application['a_status']); ?></p>

<p><strong>Submitted:</strong>
<?php echo htmlspecialchars($application['submission_date']); ?></p>

<form method="POST">

<div class="d-flex gap-3 mt-4">

<button type="submit" name="approve" value="1" class="btn btn-approve">Approve Application</button>

<button type="submit" name="reject" value="1" class="btn btn-reject">Reject Application</button>

</div>

</form>

</div>

</div>

</body>
</html>
