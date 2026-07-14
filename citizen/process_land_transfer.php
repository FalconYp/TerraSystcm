<?php

session_start();

include '../config/database.php';
/** @var mysqli $conn */
if(!isset($_SESSION['users_id'])){

    header("Location: ../login.php");
    exit();
}

if(isset($_POST['submit_transfer'])){

    $users_id = $_SESSION['users_id'];

    $land_parcels_id = mysqli_real_escape_string(
        $conn,
        $_POST['land_parcels_id']
    );

    $land_certificate_id = mysqli_real_escape_string(
        $conn,
        $_POST['land_certificate_id']
    );

    $current_owner_name = mysqli_real_escape_string(
        $conn,
        $_POST['current_owner_name']
    );

    $current_owner_id_cards_number = mysqli_real_escape_string(
        $conn,
        $_POST['current_owner_id_cards_number']
    );

    $new_owner_first_name = mysqli_real_escape_string(
        $conn,
        $_POST['new_owner_first_name']
    );

    $new_owner_last_name = mysqli_real_escape_string(
        $conn,
        $_POST['new_owner_last_name']
    );

    $new_owner_id_card = mysqli_real_escape_string(
        $conn,
        $_POST['new_owner_id_card']
    );

    $new_owner_address = mysqli_real_escape_string(
        $conn,
        $_POST['new_owner_address']
    );

    $new_owner_email = mysqli_real_escape_string(
        $conn,
        $_POST['new_owner_email']
    );

    $new_owner_phone = mysqli_real_escape_string(
        $conn,
        $_POST['new_owner_phone']
    );

    $transfer_date = mysqli_real_escape_string(
        $conn,
        $_POST['transfer_date']
    );

    $transfer_price = mysqli_real_escape_string(
        $conn,
        $_POST['transfer_price']
    );

    $witness_name = mysqli_real_escape_string(
        $conn,
        $_POST['witness_name']
    );

    $additional_information = mysqli_real_escape_string(
        $conn,
        $_POST['additional_information']
    );

    /*
    ========================================
    CHECK LAND PARCEL
    ========================================
    */

    $check_parcel = mysqli_query(

        $conn,

        "SELECT * FROM land_parcels
         WHERE land_parcels_id='$land_parcels_id'"
    );

    if(mysqli_num_rows($check_parcel) == 0){

        $_SESSION['error_message'] =
        "Invalid Land Parcel ID.";

        header("Location: apply_transfer.php");
        exit();
    }

    /*
    ========================================
    FILE UPLOAD
    ========================================
    */

    $upload_dir = "../uploads/";

    if(!file_exists($upload_dir)){

        mkdir($upload_dir, 0777, true);
    }

    $document_name =
    time() . "_" .
    $_FILES['sale_agreement_document']['name'];

    $document_tmp =
    $_FILES['sale_agreement_document']['tmp_name'];

    $document_path =
    $upload_dir . $document_name;

    move_uploaded_file(
        $document_tmp,
        $document_path
    );

    /*
    ========================================
    CREATE APPLICATION
    ========================================
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
        'transfer',
        'PENDING',
        'DO_officer Review'
    )

    ";

    mysqli_query($conn, $application_query);

    $land_applications_id =
    mysqli_insert_id($conn);

    /*
    ========================================
    INSERT TRANSFER
    ========================================
    */

    $transfer_query = "

    INSERT INTO land_transfers(

        land_applications_id,
        land_parcels_id,
        transfer_type,
        transfer_date

    )

    VALUES(

        '$land_applications_id',
        '$land_parcels_id',
        'sale',
        '$transfer_date'
    )

    ";

    mysqli_query($conn, $transfer_query);

    /*
    ========================================
    STORE DOCUMENT
    ========================================
    */

    $document_query = "

    INSERT INTO documents(

        land_applications_id,
        document_type,
        file_path,
        application_status

    )

    VALUES(

        '$land_applications_id',
        'Sale Agreement',
        '$document_path',
        'pending'
    )

    ";

    mysqli_query($conn, $document_query);

    /*
    ========================================
    WORKFLOW
    ========================================
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
    ========================================
    SEND NOTIFICATION TO DO OFFICERS
    ========================================
    */

    $do_query = "

    SELECT users_id
    FROM users
    WHERE u_role='DO_officer'

    ";

    $do_result = mysqli_query($conn, $do_query);

    while($do = mysqli_fetch_assoc($do_result)){

        $do_id = $do['users_id'];

        $message =
        "New Land Transfer Application Submitted";

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
    ========================================
    SUCCESS
    ========================================
    */

    $_SESSION['success_message'] =
    "Land Transfer Submitted Successfully";

    header("Location: apply_transfer.php");
    exit();
}
?>