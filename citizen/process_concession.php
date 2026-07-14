<?php

session_start();

include '../config/database.php';
/** @var mysqli $conn*/

error_reporting(E_ALL);
ini_set('display_errors', 1);

if(!isset($_SESSION['users_id'])){

    header("Location: ../login.php");
    exit();
}

/*
|--------------------------------------------------------------------------
| CHECK FORM SUBMISSION
|--------------------------------------------------------------------------
*/

if(isset($_POST['submit_concession'])){

    $users_id = $_SESSION['users_id'];

    /*
    |--------------------------------------------------------------------------
    | GET FORM DATA
    |--------------------------------------------------------------------------
    */

    $concession_start_date =
    mysqli_real_escape_string(
        $conn,
        $_POST['concession_start_date']
    );

    $concession_title =
    mysqli_real_escape_string(
        $conn,
        $_POST['concession_title']
    );

    $requested_size =
    mysqli_real_escape_string(
        $conn,
        $_POST['requested_size']
    );

    $concession_duration =
    mysqli_real_escape_string(
        $conn,
        $_POST['concession_duration']
    );

    $desired_location =
    mysqli_real_escape_string(
        $conn,
        $_POST['desired_location']
    );

    $purpose_of_concession =
    mysqli_real_escape_string(
        $conn,
        $_POST['purpose_of_concession']
    );

    /*
    |--------------------------------------------------------------------------
    | CREATE UPLOAD FOLDER
    |--------------------------------------------------------------------------
    */

    $upload_dir = "../uploads/";

    if(!file_exists($upload_dir)){

        mkdir($upload_dir, 0777, true);
    }

    /*
    |--------------------------------------------------------------------------
    | BUSINESS PLAN UPLOAD
    |--------------------------------------------------------------------------
    */

    $business_plan_name =
    time() . "_" .
    $_FILES['business_plan']['name'];

    $business_plan_tmp =
    $_FILES['business_plan']['tmp_name'];

    $business_plan_path =
    $upload_dir . $business_plan_name;

    move_uploaded_file(
        $business_plan_tmp,
        $business_plan_path
    );

    /*
    |--------------------------------------------------------------------------
    | IDENTIFICATION DOCUMENT
    |--------------------------------------------------------------------------
    */

    $identification_name =
    time() . "_" .
    $_FILES['identification_document']['name'];

    $identification_tmp =
    $_FILES['identification_document']['tmp_name'];

    $identification_path =
    $upload_dir . $identification_name;

    move_uploaded_file(
        $identification_tmp,
        $identification_path
    );

    /*
    |--------------------------------------------------------------------------
    | INSERT INTO LAND APPLICATIONS
    |--------------------------------------------------------------------------
    */

    $application_query = "

    INSERT INTO land_applications(

        users_id,
        a_type,
        a_status,
        current_stage

    )

    VALUES(

        '$users_id',
        'concession',
        'PENDING',
        'DO_officer Review'
    )

    ";

    $application_insert =
    mysqli_query($conn, $application_query);

    if(!$application_insert){

        die("LAND APPLICATION ERROR: "
        . mysqli_error($conn));
    }

    $land_applications_id =
    mysqli_insert_id($conn);

    /*
    |--------------------------------------------------------------------------
    | INSERT INTO LAND PARCELS
    |--------------------------------------------------------------------------
    */

    $parcel_query = "

    INSERT INTO land_parcels(

        land_applications_id,
        p_location,
        surface_area

    )

    VALUES(

        '$land_applications_id',
        '$desired_location',
        '$requested_size'
    )

    ";

    $parcel_insert =
    mysqli_query($conn, $parcel_query);

    if(!$parcel_insert){

        die("LAND PARCEL ERROR: "
        . mysqli_error($conn));
    }

    /*
    |--------------------------------------------------------------------------
    | INSERT INTO IMMATRICULATION
    |--------------------------------------------------------------------------
    */

    $imm_query = "

    INSERT INTO land_immatriculations(

        land_applications_id,
        immatriculation_type,
        i_status

    )

    VALUES(

        '$land_applications_id',
        'concession',
        'pending'
    )

    ";

    $imm_insert =
    mysqli_query($conn, $imm_query);

    if(!$imm_insert){

        die("IMMATRICULATION ERROR: "
        . mysqli_error($conn));
    }

    $immatriculation_id =
    mysqli_insert_id($conn);

    /*
    |--------------------------------------------------------------------------
    | INSERT INTO CONCESSION TABLE
    |--------------------------------------------------------------------------
    */

    $concession_query = "

    INSERT INTO concession(

        users_id,
        immatriculation_id,
        land_applications_id,
        concession_start_date,
        concession_title,
        requested_size,
        concession_duration,
        c_location,
        purpose_of_concession,
        business_plan,
        identification_document

    )

    VALUES(

        '$users_id',
        '$immatriculation_id',
        '$land_applications_id',
        '$concession_start_date',
        '$concession_title',
        '$requested_size',
        '$concession_duration',
        '$desired_location',
        '$purpose_of_concession',
        '$business_plan_path',
        '$identification_path'
    )

    ";

    $concession_insert =
    mysqli_query($conn, $concession_query);

    if(!$concession_insert){

        die("CONCESSION ERROR: "
        . mysqli_error($conn));
    }

    /*
    |--------------------------------------------------------------------------
    | WORKFLOW STAGES
    |--------------------------------------------------------------------------
    */

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

    /*
    |--------------------------------------------------------------------------
    | SEND NOTIFICATION TO DO OFFICERS
    |--------------------------------------------------------------------------
    */

    $do_query = "

    SELECT users_id
    FROM users
    WHERE u_role='DO_officer'

    ";

    $do_result =
    mysqli_query($conn, $do_query);

    while($do =
    mysqli_fetch_assoc($do_result)){

        $do_id = $do['users_id'];

        $message =
        "New Concession Application Submitted";

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

    /*
    |--------------------------------------------------------------------------
    | SUCCESS MESSAGE
    |--------------------------------------------------------------------------
    */

    $_SESSION['success_message'] =
    "Concession Application Submitted Successfully";

    /*
    |--------------------------------------------------------------------------
    | REDIRECT
    |--------------------------------------------------------------------------
    */

    header("Location: apply_immatriculation.php");
    exit();
}

else{

    echo "Form not submitted correctly.";
}

?>