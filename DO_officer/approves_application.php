<?php

session_start();

include '../config/database.php';
/** @var mysqli $conn */

if(!isset($_SESSION['users_id']))
{
    header("Location: ../login.php");
    exit();
}

if(!isset($_SESSION['u_role']) || $_SESSION['u_role'] != 'DO_officer')
{
    die("Access Denied");
}

if(!isset($_GET['id']) || empty($_GET['id']))
{
    die("Application ID Missing");
}

$application_id = intval($_GET['id']);

$app_query = mysqli_query($conn, "
    SELECT * FROM land_applications
    WHERE land_applications_id='$application_id'
");

if(!$app_query || mysqli_num_rows($app_query) == 0)
{
    die("Application Not Found");
}

$application = mysqli_fetch_assoc($app_query);
$citizen_id = $application['users_id'];

mysqli_query($conn,"
UPDATE land_applications
SET
a_status='PENDING',
current_stage='Committee Review'
WHERE land_applications_id='$application_id'
");

mysqli_query($conn, "
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

mysqli_query($conn, "
INSERT INTO notifications
(
    users_id,
    n_message
)
VALUES
(
    '$citizen_id',
    'Your application has been approved by the DO Officer and forwarded to the Committee.'
)
");

$_SESSION['success_message'] = "Application Approved Successfully";

header("Location: dashboard.php?success=approved");
exit();

?>
