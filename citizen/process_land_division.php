<?php

session_start();

include '../config/database.php';

/** @var mysqli $conn */

if (!isset($_SESSION['users_id'])) {

    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $users_id = $_SESSION['users_id'];

    // =========================================
    // GET FORM DATA SAFELY
    // =========================================

    $original_land_id = mysqli_real_escape_string(
        $conn,
        $_POST['original_land_id'] ?? ''
    );

    $land_certificate_id = mysqli_real_escape_string(
        $conn,
        $_POST['land_certificate_id'] ?? ''
    );

    $total_size = mysqli_real_escape_string(
        $conn,
        $_POST['total_size'] ?? ''
    );

    $location = mysqli_real_escape_string(
        $conn,
        $_POST['location'] ?? ''
    );

    $reason_for_division = mysqli_real_escape_string(
        $conn,
        $_POST['reason_for_division'] ?? ''
    );

    $new_parcels_count = mysqli_real_escape_string(
        $conn,
        $_POST['new_parcels_count'] ?? ''
    );

    $division_type = mysqli_real_escape_string(
        $conn,
        $_POST['division_type'] ?? ''
    );

    // =========================================
    // VALIDATION
    // =========================================

    if (
        empty($original_land_id) ||
        empty($land_certificate_id) ||
        empty($total_size) ||
        empty($location) ||
        empty($reason_for_division) ||
        empty($new_parcels_count) ||
        empty($division_type)
    ) {

        die("All fields are required.");
    }

    // =========================================
    // CHECK IF LAND PARCEL EXISTS
    // =========================================

    $check_parcel = mysqli_query(
        $conn,
        "SELECT land_parcels_id 
         FROM land_parcels 
         WHERE land_parcels_id = '$original_land_id'"
    );

    if (mysqli_num_rows($check_parcel) == 0) {

        die("Land Parcel ID does not exist.");
    }

    // =========================================
    // CREATE APPLICATION
    // =========================================

    $application_query = "
        INSERT INTO land_applications(
            users_id,
            a_type,
            a_status,
            current_stage
        )
        VALUES(
            '$users_id',
            'division',
            'PENDING',
            'DO_officer Review'
        )
    ";

    if (!mysqli_query($conn, $application_query)) {

        die("Application Error: " . mysqli_error($conn));
    }

    $land_applications_id = mysqli_insert_id($conn);

    // =========================================
    // INSERT INTO LAND DIVISIONS
    // =========================================

    $division_query = "
        INSERT INTO land_divisions(
            land_applications_id,
            land_parcels_id,
            division_type,
            new_parcels_count
        )
        VALUES(
            '$land_applications_id',
            '$original_land_id',
            '$division_type',
            '$new_parcels_count'
        )
    ";

    if (!mysqli_query($conn, $division_query)) {

        die("Division Error: " . mysqli_error($conn));
    }

    // =========================================
    // CREATE WORKFLOW
    // =========================================

    $workflow_query = "
        INSERT INTO workflow_stages(
            land_applications_id,
            actor_role,
            application_status
        )
        VALUES(
            '$land_applications_id',
            'DO_officer',
            'Pending Review'
        )
    ";

    mysqli_query($conn, $workflow_query);

    // =========================================
    // SEND NOTIFICATION TO DO OFFICERS
    // =========================================

    $do_query = "
        SELECT users_id
        FROM users
        WHERE u_role = 'DO_officer'
    ";

    $do_result = mysqli_query($conn, $do_query);

    while ($do = mysqli_fetch_assoc($do_result)) {

        $do_id = $do['users_id'];

        $message = "New Land Division Application Submitted";

        $notification_query = "
            INSERT INTO notifications(
                users_id,
                n_message,
                n_status
            )
            VALUES(
                '$do_id',
                '$message',
                'unread'
            )
        ";

        mysqli_query($conn, $notification_query);
    }

    // =========================================
    // SUCCESS
    // =========================================

    $_SESSION['success_message'] =
        "Land Division Submitted Successfully";

    header("Location: land_division.php");
    exit();
}

?>