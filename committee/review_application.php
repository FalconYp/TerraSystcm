<?php

session_start();

include '../config/database.php';
/** @var mysqli $conn */

if(!isset($_SESSION['users_id']))
{
    header("Location: ../login.php");
    exit();
}

if($_SESSION['u_role'] != 'committee')
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
    u.phone_number,
    di.land_location,
    di.land_size,
    di.additional_descr

FROM land_applications la

INNER JOIN users u
ON la.users_id = u.users_id

LEFT JOIN direct_immatriculation di
ON la.land_applications_id = di.land_applications_id

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
    $report_notes = mysqli_real_escape_string(
        $conn,
        $_POST['report_notes'] ?? ''
    );

    // Mise en Valeur confirmed on-site. Application moves on to
    // Land Affairs for the legal verification stage.
    mysqli_query($conn,"
    UPDATE land_applications
    SET
        a_status='PENDING',
        current_stage='Land Affairs Review'
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
        'committee',
        'APPROVED'
    )
    ");

    mysqli_query($conn,"
    INSERT INTO application_reviews
    (
        land_applications_id,
        reviewed_by,
        reviewer_role,
        review_action,
        review_comment
    )
    VALUES
    (
        '$application_id',
        '{$_SESSION['users_id']}',
        'committee',
        'APPROVED',
        '$report_notes'
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
        'The Committee has confirmed Mise en Valeur on your land. Your application has been forwarded to Land Affairs for legal verification.'
    )
    ");

    $_SESSION['success_message'] = "Application Approved Successfully";

    header("Location: dashboard.php?success=approved");
    exit();
}

if(isset($_POST['reject']))
{
    $report_notes = mysqli_real_escape_string(
        $conn,
        $_POST['report_notes'] ?? ''
    );

    mysqli_query($conn,"
    UPDATE land_applications
    SET
        a_status='DECLINED',
        current_stage='Rejected By Committee'
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
        'committee',
        'REJECTED'
    )
    ");

    mysqli_query($conn,"
    INSERT INTO application_reviews
    (
        land_applications_id,
        reviewed_by,
        reviewer_role,
        review_action,
        review_comment
    )
    VALUES
    (
        '$application_id',
        '{$_SESSION['users_id']}',
        'committee',
        'REJECTED',
        '$report_notes'
    )
    ");

    $notif_message = $report_notes !== ''
        ? "Your application was rejected by the Committee after field inspection: " . $report_notes
        : "Your application was rejected by the Committee after field inspection (Mise en Valeur condition not satisfied).";

    mysqli_query($conn,"
    INSERT INTO notifications
    (
        users_id,
        n_message
    )
    VALUES
    (
        '".$application['users_id']."',
        '".mysqli_real_escape_string($conn, $notif_message)."'
    )
    ");

    $_SESSION['success_message'] = "Application Rejected";

    header("Location: dashboard.php?success=rejected");
    exit();
}

?>

<!DOCTYPE html>
<html>
<head>

<title>Committee Review - Mise en Valeur</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="../assets/css/dashboard.css">

</head>

<body>

<?php
$sidebarRole   = 'Committee';
$sidebarActive = 'dashboard';
$sidebarNav = [
    ['key' => 'dashboard', 'label' => 'Dashboard', 'href' => 'dashboard.php', 'icon' => 'bi-speedometer2'],
];
include '../include/sidebar.php';
?>

<div class="app-main">

<div class="page-header">
    <h2>Committee Review - Mise en Valeur Verification</h2>
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

<p><strong>Land Location:</strong>
<?php echo htmlspecialchars($application['land_location'] ?? 'N/A'); ?></p>

<p><strong>Land Size:</strong>
<?php echo $application['land_size'] ? htmlspecialchars($application['land_size']).' m²' : 'N/A'; ?></p>

<p><strong>Status:</strong>
<?php echo htmlspecialchars($application['a_status']); ?></p>

<p><strong>Current Stage:</strong>
<?php echo htmlspecialchars($application['current_stage']); ?></p>

<hr>

<form method="POST">

<div class="mb-3">
    <label class="form-label"><strong>Inspection Report / Mise en Valeur Notes</strong></label>
    <textarea name="report_notes" class="form-control" rows="4"
    placeholder="e.g. Site visited on [date]. Cultivation and a permanent structure observed on the parcel. Mise en Valeur condition satisfied."></textarea>
</div>

<div class="d-flex gap-3 mt-4">

<button type="submit" name="approve" value="1" class="btn btn-approve">
    Confirm Mise en Valeur &amp; Approve
</button>

<button type="submit" name="reject" value="1" class="btn btn-reject">
    Reject Application
</button>

</div>

</form>

</div>

</div>

</body>
</html>
