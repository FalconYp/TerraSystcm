<?php

session_start();

include '../config/database.php';
/** @var mysqli $conn */

error_reporting(E_ALL);
ini_set('display_errors', 1);

if(!isset($_SESSION['users_id'])){

    header("Location: ../login.php");
    exit();
}

if(isset($_POST['submit_direct_immatriculation'])){

    $users_id = $_SESSION['users_id'];

    // FORM VALUES

    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $filliation = $_POST['filliation'];
    $address = $_POST['address'];
    $id_card_number = $_POST['id_card_number'];
    $nationality = $_POST['nationality'];
    $profession = $_POST['profession'];

    $marital_status = $_POST['marital_status'];

    $imm_condition = $_POST['imm_condition'];

    $lot_size = $_POST['lot_size'];

    $land_location = $_POST['land_location'];

    $land_description = $_POST['land_description'];

    // =========================
    // FILE UPLOADS
    // =========================

    $upload_dir = "../uploads/";

    if(!file_exists($upload_dir)){

        mkdir($upload_dir, 0777, true);
    }

    // FRONT

    $front_name = time() .
    "_" .
    $_FILES['front_id_card']['name'];

    $front_tmp =
    $_FILES['front_id_card']['tmp_name'];

    $front_path =
    $upload_dir . $front_name;

    move_uploaded_file($front_tmp, $front_path);

    // BACK

    $back_name = time() .
    "_" .
    $_FILES['back_id_card']['name'];

    $back_tmp =
    $_FILES['back_id_card']['tmp_name'];

    $back_path =
    $upload_dir . $back_name;

    move_uploaded_file($back_tmp, $back_path);

    // =========================
    // INSERT APPLICATION
    // =========================

    $query1 = "
    INSERT INTO land_applications(

        users_id,
        a_type,
        a_status,
        current_stage

    )

    VALUES(

        '$users_id',
        'direct',
        'PENDING',
        'DO_officer Review'
    )
    ";

    mysqli_query($conn, $query1);

    $land_applications_id =
    mysqli_insert_id($conn);

    // =========================
    // LAND PARCEL
    // =========================

    $query2 = "
    INSERT INTO land_parcels(

        land_applications_id,
        p_location,
        surface_area

    )

    VALUES(

        '$land_applications_id',
        '$land_location',
        '$lot_size'
    )
    ";

    mysqli_query($conn, $query2);

    // =========================
    // IMMATRICULATION
    // =========================

    $query3 = "
    INSERT INTO land_immatriculations(

        land_applications_id,
        immatriculation_type,
        mise_en_valeur

    )

    VALUES(

        '$land_applications_id',
        'direct',
        '$imm_condition'
    )
    ";

    mysqli_query($conn, $query3);

    $immatriculation_id =
    mysqli_insert_id($conn);

    // =========================
    // DIRECT IMMATRICULATION
    // =========================

    $query4 = "
    INSERT INTO direct_immatriculation(

        users_id,
        immatriculation_id,
        land_applications_id,
        first_name,
        last_name,
        filliation,
        u_address,
        id_card_number,
        id_card_front,
        id_card_back,
        nationality,
        profession,
        marital_status,
        imm_condition,
        land_size,
        land_location,
        additional_descr

    )

    VALUES(

        '$users_id',
        '$immatriculation_id',
        '$land_applications_id',
        '$first_name',
        '$last_name',
        '$filliation',
        '$address',
        '$id_card_number',
        '$front_path',
        '$back_path',
        '$nationality',
        '$profession',
        '$marital_status',
        '$imm_condition',
        '$lot_size',
        '$land_location',
        '$land_description'
    )
    ";

    $insert =
    mysqli_query($conn, $query4);

    if(!$insert){

        die(mysqli_error($conn));
    }

    // =========================
    // WORKFLOW
    // =========================

    mysqli_query($conn, "

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

    ");

    // =========================
    // SUCCESS
    // =========================

    $_SESSION['success_message'] =
    "Application Submitted Successfully";

    header("Location: apply_immatriculation.php");

    exit();
}

?>