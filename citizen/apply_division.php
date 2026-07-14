

<?php
session_start();
include '../config/database.php';

if(!isset($_SESSION['users_id'])){
    header("Location: ../login.php");
    exit();
}

$user_name = $_SESSION['first_name'];
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Land Division</title>


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">


    <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>

        body{
            background:#dfe6f3;
            font-family:Arial, Helvetica, sans-serif;
            background-image: url(./3d-digital-landscape-with-space-sky-nebula.jpg);
            
        }

        .page-container{
            padding:30px;
        }

        /* HEADER */

        .top-section{
            display:flex;
            align-items:flex-start;
            gap:18px;
            margin-bottom:35px;
        }

         .top-header{

            background:gray;

            padding:22px 40px;

            display:flex;
            align-items:center;
            gap:15px;

            
        }

        .back-btn{

            text-decoration:none;
            color:white;

            font-weight:600;
        }  

        .icon-box{
            width:58px;
            height:58px;
            background:#9333ea;
            border-radius:16px;

            display:flex;
            justify-content:center;
            align-items:center;

            color:white;
            font-size:28px;
        }

        .page-title{
            font-size:48px;
            font-weight:700;
            color:wheat;
            line-height:1;
        }

        .page-subtitle{
            font-size:24px;
            color:wheat;
            margin-top:8px;
        }

        /* CARD */

        .main-card{
            background:gray;
            border-radius:20px;
            padding:35px;

            box-shadow:0 4px 12px rgba(0,0,0,0.05);
        }

        .section-title{
            font-size:34px;
            font-weight:700;
            color:#111827;
        }

        .section-subtitle{
            font-size:24px;
            color:#64748b;
            margin-bottom:30px;
        }

        label{
            font-size:22px;
            font-weight:600;
            margin-bottom:12px;
            color:#111827;
        }

        .form-control,
        .form-select{
            height:70px;
            border-radius:14px;
            border:none;
            background:#f1f5f9;
            font-size:22px;
            padding-left:22px;
        }

        textarea.form-control{
            height:160px;
            padding-top:18px;
        }

        .parcel-box{
            background:#f8fafc;
            border:2px solid #e2e8f0;
            border-radius:18px;
            padding:25px;
            margin-top:25px;
        }

        .parcel-title{
            font-size:26px;
            font-weight:700;
            margin-bottom:25px;
        }

        .divider{
            margin:45px 0;
            border-top:2px solid #e5e7eb;
        }

        .badge-count{
            background:#e5e7eb;
            padding:8px 16px;
            border-radius:30px;
            font-size:18px;
            font-weight:600;
        }

        .submit-btn{
            width:100%;
            height:75px;
            border:none;
            border-radius:16px;
            background:#020617;
            color:white;
            font-size:24px;
            font-weight:700;
            margin-top:35px;
            transition:0.3s;
        }

        .submit-btn:hover{
            background:#111827;
        }

        .add-btn{
            background:#9333ea;
            color:white;
            border:none;
            padding:12px 25px;
            border-radius:12px;
            font-size:18px;
            font-weight:600;
        }

        .add-btn:hover{
            background:#7e22ce;
        }

        @media(max-width:768px){

            .page-title{
                font-size:32px;
            }

            .section-title{
                font-size:26px;
            }

            .form-control,
            .form-select{
                font-size:16px;
                height:55px;
            }

            textarea.form-control{
                height:120px;
            }

            label{
                font-size:16px;
            }

            .page-subtitle,
            .section-subtitle{
                font-size:16px;
            }
        }

    </style>

</head>

<body>

<div class="top-header">

    <a href="dashboard.php" class="back-btn">

        <i class="bi bi-arrow-left"></i>
        Back to Dashboard

    </a>

</div>

<div class="page-container">

    <!-- HEADER -->

    <div class="top-section">

        <div class="icon-box">
            <i class="bi bi-bezier2"></i>
        </div>

        <div>

            <div class="page-title">
                Land Division
            </div>

            <div class="page-subtitle">
                Divide your land into multiple parcels
            </div>

        </div>

    </div>

    <!-- MAIN CARD -->

    <div class="main-card">

        <form action="process_land_division.php"
        method="POST"
        enctype="multipart/form-data">

            <!-- ORIGINAL LAND INFO -->

            <div class="section-title">
                Original Land Information
            </div>

            <div class="section-subtitle">
                Enter details of the land to be divided
            </div>

            <div class="mb-4">

                <label>
                    Original Land ID
                </label>

                <input type="text"
                name="original_land_id"
                class="form-control"
                placeholder="Enter land ID"
                required>

            </div>

            <div class="row">

                <div class="col-md-6 mb-4">

                    <label>
                        Land Certificate ID
                    </label>

                    <input type="text"
                    name="land_certificate_id"
                    class="form-control"
                    placeholder="Enter land certificate ID"
                    required>

                </div>

                <div class="col-md-6 mb-4">

                    <label>
                        Total Size (hectares)
                    </label>

                    <input type="number"
                    step="0.01"
                    name="total_size"
                    class="form-control"
                    placeholder="0.00"
                    required>

                </div>

            </div>

            <div class="mb-4">

                <label>
                    Location
                </label>

                <input type="text"
                name="location"
                class="form-control"
                placeholder="Enter location"
                required>

            </div>

            <div class="mb-4">

                <label>
                    Reason for Division
                </label>

                <textarea name="division_reason"
                class="form-control"
                placeholder="Explain the reason for dividing the land"
                required></textarea>

            </div>

            <div class="divider"></div>

            <!-- PARCEL DIVISION PLAN -->

            <div class="d-flex justify-content-between align-items-center mb-4">

                <div>

                    <div class="section-title">
                        Parcel Division Plan
                    </div>

                    <div class="section-subtitle mb-0">
                        Define the size and purpose of each new parcel
                    </div>

                </div>

                <div class="badge-count" id="parcelCount">
                    2 Parcels
                </div>

            </div>

            <div id="parcelContainer">

                <!-- PARCEL 1 -->

                <div class="parcel-box">

                    <div class="parcel-title">
                        Parcel 1
                    </div>

                    <div class="row">

                        <div class="col-md-6 mb-4">

                            <label>
                                Parcel Size (hectares)
                            </label>

                            <input type="number"
                            step="0.01"
                            name="parcel_size[]"
                            class="form-control"
                            placeholder="0.00"
                            required>

                        </div>

                        <div class="col-md-6 mb-4">

                            <label>
                                Parcel Purpose
                            </label>

                            <select name="parcel_purpose[]"
                            class="form-select"
                            required>

                                <option value="">
                                    Select Purpose
                                </option>

                                <option value="Residential">
                                    Residential
                                </option>

                                <option value="Commercial">
                                    Commercial
                                </option>

                                <option value="Agriculture">
                                    Agriculture
                                </option>

                                <option value="Industrial">
                                    Industrial
                                </option>

                            </select>

                        </div>

                    </div>

                </div>

                <!-- PARCEL 2 -->

                <div class="parcel-box">

                    <div class="parcel-title">
                        Parcel 2
                    </div>

                    <div class="row">

                        <div class="col-md-6 mb-4">

                            <label>
                                Parcel Size (hectares)
                            </label>

                            <input type="number"
                            step="0.01"
                            name="parcel_size[]"
                            class="form-control"
                            placeholder="0.00"
                            required>

                        </div>

                        <div class="col-md-6 mb-4">

                            <label>
                                Parcel Purpose
                            </label>

                            <select name="parcel_purpose[]"
                            class="form-select"
                            required>

                                <option value="">
                                    Select Purpose
                                </option>

                                <option value="Residential">
                                    Residential
                                </option>

                                <option value="Commercial">
                                    Commercial
                                </option>

                                <option value="Agriculture">
                                    Agriculture
                                </option>

                                <option value="Industrial">
                                    Industrial
                                </option>

                            </select>

                        </div>

                    </div>

                </div>

            </div>

            <!-- ADD PARCEL -->

            <button type="button"
            class="add-btn mt-4"
            onclick="addParcel()">

                <i class="bi bi-plus-circle"></i>
                Add Parcel

            </button>

            <!-- DOCUMENT -->

            <div class="divider"></div>

            <div class="section-title mb-3">
                Supporting Documents
            </div>

            <div class="mb-4">

                <label>
                    Upload Survey Plan
                </label>

                <input type="file"
                name="survey_plan"
                class="form-control"
                required>

            </div>

            <div class="mb-4">

                <label>
                    Additional Notes
                </label>

                <textarea name="additional_notes"
                class="form-control"
                placeholder="Enter any additional information"></textarea>

            </div>

            <!-- SUBMIT -->

            <button type="submit"
            class="submit-btn">

                Submit Land Division Request

            </button>

        </form>

    </div>

</div>

<script>

    let parcelNumber = 2;

    function addParcel(){

        parcelNumber++;

        document.getElementById("parcelCount").innerHTML =
        parcelNumber + " Parcels";

        let container = document.getElementById("parcelContainer");

        let html = `

            <div class="parcel-box">

                <div class="parcel-title">
                    Parcel ${parcelNumber}
                </div>

                <div class="row">

                    <div class="col-md-6 mb-4">

                        <label>
                            Parcel Size (hectares)
                        </label>

                        <input type="number"
                        step="0.01"
                        name="parcel_size[]"
                        class="form-control"
                        placeholder="0.00"
                        required>

                    </div>

                    <div class="col-md-6 mb-4">

                        <label>
                            Parcel Purpose
                        </label>

                        <select name="parcel_purpose[]"
                        class="form-select"
                        required>

                            <option value="">
                                Select Purpose
                            </option>

                            <option value="Residential">
                                Residential
                            </option>

                            <option value="Commercial">
                                Commercial
                            </option>

                            <option value="Agriculture">
                                Agriculture
                            </option>

                            <option value="Industrial">
                                Industrial
                            </option>

                        </select>

                    </div>

                </div>

            </div>

        `;

        container.insertAdjacentHTML("beforeend", html);
    }

</script>

</body>
</html>