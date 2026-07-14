<?php

session_start();

include '../config/database.php';
/** @var mysqli $conn */
error_reporting(E_ALL);
ini_set('display_errors', 1);

/*
|--------------------------------------------------------------------------
| SESSION CHECK
|--------------------------------------------------------------------------
*/

if(!isset($_SESSION['users_id']))
{
    header("Location: ../login.php");
    exit();
}

if(!isset($_SESSION['u_role']) ||
   $_SESSION['u_role'] != 'DO_officer')
{
    die("Access Denied");
}

/*
|--------------------------------------------------------------------------
| APPLICATION ID CHECK
|--------------------------------------------------------------------------
*/

if(!isset($_GET['id']) || empty($_GET['id']))
{
    die("Application ID Missing");
}

$application_id = intval($_GET['id']);

/*
|--------------------------------------------------------------------------
| GET APPLICATION
|--------------------------------------------------------------------------
*/

$app_query = mysqli_query(
    $conn,
    "SELECT *
     FROM land_applications
     WHERE land_applications_id='$application_id'"
);

if(!$app_query)
{
    die("Database Error: " . mysqli_error($conn));
}

if(mysqli_num_rows($app_query) == 0)
{
    die("Application Not Found");
}

$application = mysqli_fetch_assoc($app_query);

$citizen_id = $application['users_id'];

/*
|--------------------------------------------------------------------------
| UPDATE APPLICATION STATUS
|--------------------------------------------------------------------------
*/

$update_application = mysqli_query(
    $conn,
    "UPDATE land_applications
     SET
        a_status='DECLINED',
        current_stage='Rejected By DO Officer'
     WHERE land_applications_id='$application_id'"
);

if(!$update_application)
{
    die("Update Error: " . mysqli_error($conn));
}

/*
|--------------------------------------------------------------------------
| WORKFLOW LOG
|--------------------------------------------------------------------------
*/

$workflow = mysqli_query(
    $conn,
    "INSERT INTO workflow_stages
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
    )"
);

if(!$workflow)
{
    die("Workflow Error: " . mysqli_error($conn));
}

/*
|--------------------------------------------------------------------------
| REVIEW HISTORY (OPTIONAL)
|--------------------------------------------------------------------------
*/

$table_check = mysqli_query(
    $conn,
    "SHOW TABLES LIKE 'application_reviews'"
);

if(mysqli_num_rows($table_check) > 0)
{
    mysqli_query(
        $conn,
        "INSERT INTO application_reviews
        (
            land_applications_id,
            reviewed_by,
            reviewer_role,
            review_action
        )
        VALUES
        (
            '$application_id',
            '{$_SESSION['users_id']}',
            'DO_officer',
            'REJECTED'
        )"
    );
}

/*
|--------------------------------------------------------------------------
| NOTIFICATION
|--------------------------------------------------------------------------
*/

$message =
"Your application has been rejected by the DO Officer.";

$notification = mysqli_query(
    $conn,
    "INSERT INTO notifications
    (
        users_id,
        n_message,
        n_status
    )
    VALUES
    (
        '$citizen_id',
        '$message',
        'unread'
    )"
);

if(!$notification)
{
    die("Notification Error: " . mysqli_error($conn));
}

/*
|--------------------------------------------------------------------------
| SUCCESS MESSAGE
|--------------------------------------------------------------------------
*/

$_SESSION['success_message'] =
"Application Rejected Successfully";

/*
|--------------------------------------------------------------------------
| REDIRECT
|--------------------------------------------------------------------------
*/

header("Location: dashboard.php?success=rejected");
exit();

?>