<?php

session_start();

if(!isset($_SESSION['users_id'])){

    header("Location: ../login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Land Transfer</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>

body{

    background:#eef3ff;
    font-family:Arial, Helvetica, sans-serif;
    background-image: url(./3d-digital-landscape-with-space-sky-nebula.jpg);
}

.main-container{

    max-width:950px;
    margin:40px auto;
}

.page-header{

    display:flex;
    align-items:center;
    gap:20px;

    margin-bottom:30px;
}

.header-icon{

    width:60px;
    height:60px;

    background:#ff6b00;

    border-radius:15px;

    display:flex;
    align-items:center;
    justify-content:center;

    color:white;
    font-size:28px;
}

.page-title{

    font-size:42px;
    font-weight:bold;
}

.page-subtitle{

    color:#6b7280;
    font-size:20px;
}

.step-indicator{

    display:flex;
    justify-content:center;
    align-items:center;

    gap:20px;

    margin-bottom:40px;
}

.step{

    width:45px;
    height:45px;

    border-radius:50%;

    background:#d1d5db;

    display:flex;
    align-items:center;
    justify-content:center;

    font-weight:bold;
}

.step.active{

    background:#ff6b00;
    color:white;
}

.form-card{

    background:grey;

    padding:35px;

    border-radius:20px;

    box-shadow:0 5px 20px rgba(0,0,0,0.08);
}

.form-title{

    font-size:30px;
    font-weight:bold;

    margin-bottom:10px;
}

.form-subtitle{

    color:#6b7280;

    margin-bottom:30px;
}

.form-control,
.form-select{

    padding:14px;
    border-radius:12px;
}

.form-label{

    font-weight:600;
}

textarea{

    resize:none;
}

.btn-custom{

    background:#111827;
    color:white;

    border:none;

    padding:14px;

    border-radius:12px;

    width:100%;

    font-weight:bold;

    margin-top:20px;
}

.btn-custom:hover{

    background:#000;
}

.btn-secondary-custom{

    background:#d1d5db;
    color:black;

    border:none;

    padding:14px;

    border-radius:12px;

    width:100%;

    font-weight:bold;

    margin-top:20px;
}

.hidden{

    display:none;
}

.alert{

    border-radius:12px;
}

</style>

</head>

<body>

<div class="main-container">

    <div class="page-header">

        <div class="header-icon">

            <i class="bi bi-arrow-left-right"></i>

        </div>

        <div>

            <div class="page-title">

                Land Transfer

            </div>

            <div class="page-subtitle">

                Transfer land ownership to another party

            </div>

        </div>

    </div>

    <?php

    if(isset($_SESSION['success_message'])){

        echo "

        <div class='alert alert-success'>
            ".$_SESSION['success_message']."
        </div>

        ";

        unset($_SESSION['success_message']);
    }

    if(isset($_SESSION['error_message'])){

        echo "

        <div class='alert alert-danger'>
            ".$_SESSION['error_message']."
        </div>

        ";

        unset($_SESSION['error_message']);
    }

    ?>

    <div class="step-indicator">

        <div class="step active" id="indicator1">1</div>

        <div class="step" id="indicator2">2</div>

        <div class="step" id="indicator3">3</div>

    </div>

    <form action="process_land_transfer.php" method="POST" enctype="multipart/form-data">

        <!-- STEP 1 -->

        <div class="form-card" id="step1">

            <div class="form-title">

                Step 1: Land Information

            </div>

            <div class="form-subtitle">

                Enter the details of the land to be transferred

            </div>

            <div class="mb-4">

                <label class="form-label">

                    Land Parcel ID

                </label>

                <input
                type="number"
                name="land_parcels_id"
                class="form-control"
                required>

            </div>

            <div class="mb-4">

                <label class="form-label">

                    Land Certificate ID

                </label>

                <input
                type="text"
                name="land_certificate_id"
                class="form-control"
                required>

            </div>

            <div class="mb-4">

                <label class="form-label">

                    Current Owner Name

                </label>

                <input
                type="text"
                name="current_owner_name"
                class="form-control"
                required>

            </div>

            <div class="mb-4">

                <label class="form-label">

                    Current Owner ID Card Number

                </label>

                <input
                type="text"
                name="current_owner_id_cards_number"
                class="form-control"
                required>

            </div>

            <button
            type="button"
            class="btn-custom"
            onclick="nextStep(2)">

                Next

            </button>

        </div>

        <!-- STEP 2 -->

        <div class="form-card hidden" id="step2">

            <div class="form-title">

                Step 2: New Owner Information

            </div>

            <div class="form-subtitle">

                Enter details of the new owner

            </div>

            <div class="row">

                <div class="col-md-6 mb-4">

                    <label class="form-label">

                        New Owner First Name

                    </label>

                    <input
                    type="text"
                    name="new_owner_first_name"
                    class="form-control"
                    required>

                </div>

                <div class="col-md-6 mb-4">

                    <label class="form-label">

                        New Owner Last Name

                    </label>

                    <input
                    type="text"
                    name="new_owner_last_name"
                    class="form-control"
                    required>

                </div>

            </div>

            <div class="mb-4">

                <label class="form-label">

                    New Owner ID Card Number

                </label>

                <input
                type="text"
                name="new_owner_id_card"
                class="form-control"
                required>

            </div>

            <div class="mb-4">

                <label class="form-label">

                    New Owner Address

                </label>

                <input
                type="text"
                name="new_owner_address"
                class="form-control"
                required>

            </div>

            <div class="row">

                <div class="col-md-6 mb-4">

                    <label class="form-label">

                        Email

                    </label>

                    <input
                    type="email"
                    name="new_owner_email"
                    class="form-control"
                    required>

                </div>

                <div class="col-md-6 mb-4">

                    <label class="form-label">

                        Phone

                    </label>

                    <input
                    type="text"
                    name="new_owner_phone"
                    class="form-control"
                    required>

                </div>

            </div>

            <div class="row">

                <div class="col-md-6">

                    <button
                    type="button"
                    class="btn-secondary-custom"
                    onclick="previousStep(1)">

                        Previous

                    </button>

                </div>

                <div class="col-md-6">

                    <button
                    type="button"
                    class="btn-custom"
                    onclick="nextStep(3)">

                        Next

                    </button>

                </div>

            </div>

        </div>

        <!-- STEP 3 -->

        <div class="form-card hidden" id="step3">

            <div class="form-title">

                Step 3: Transfer Details

            </div>

            <div class="form-subtitle">

                Provide transfer documentation and finalize

            </div>

            <div class="mb-4">

                <label class="form-label">

                    Transfer Date

                </label>

                <input
                type="date"
                name="transfer_date"
                class="form-control"
                required>

            </div>

            <div class="mb-4">

                <label class="form-label">

                    Transfer Price

                </label>

                <input
                type="number"
                name="transfer_price"
                class="form-control">

            </div>

            <div class="mb-4">

                <label class="form-label">

                    Sale Agreement Document

                </label>

                <input
                type="file"
                name="sale_agreement_document"
                class="form-control"
                required>

            </div>

            <div class="mb-4">

                <label class="form-label">

                    Witness Name

                </label>

                <input
                type="text"
                name="witness_name"
                class="form-control"
                required>

            </div>

            <div class="mb-4">

                <label class="form-label">

                    Additional Information

                </label>

                <textarea
                name="additional_information"
                rows="5"
                class="form-control"></textarea>

            </div>

            <div class="row">

                <div class="col-md-6">

                    <button
                    type="button"
                    class="btn-secondary-custom"
                    onclick="previousStep(2)">

                        Previous

                    </button>

                </div>

                <div class="col-md-6">

                    <button
                    type="submit"
                    name="submit_transfer"
                    class="btn-custom">

                        Submit Transfer

                    </button>

                </div>

            </div>

        </div>

    </form>

</div>

<script>

function nextStep(step){

    document.getElementById('step1').classList.add('hidden');
    document.getElementById('step2').classList.add('hidden');
    document.getElementById('step3').classList.add('hidden');

    document.getElementById('step' + step).classList.remove('hidden');

    document.getElementById('indicator1').classList.remove('active');
    document.getElementById('indicator2').classList.remove('active');
    document.getElementById('indicator3').classList.remove('active');

    document.getElementById('indicator' + step).classList.add('active');
}

function previousStep(step){

    nextStep(step);
}

</script>

</body>
</html>