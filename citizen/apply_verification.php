
<?php
require_once '../config/database.php';
/** @var mysqli $conn */
$verification_result = null;
$search_query = "";

if(isset($_POST['verify_land'])){

    $search_query = mysqli_real_escape_string(
        $conn,
        $_POST['search_query']
    );

    $search = "%".$search_query."%";

    $query = "

    SELECT 
        land_parcels.land_parcels_id,
        land_parcels.p_location,
        land_parcels.surface_area,
        users.first_name,
        users.last_name,
        users.id_card_number,
        land_applications.current_stage,
        land_applications.a_status

    FROM land_parcels

    LEFT JOIN land_applications
    ON land_parcels.land_applications_id =
    land_applications.land_applications_id

    LEFT JOIN land_ownerships
    ON land_parcels.land_parcels_id =
    land_ownerships.land_parcels_id

    LEFT JOIN users
    ON land_ownerships.users_id = users.users_id

    WHERE
        land_parcels.land_parcels_id LIKE '$search'
        OR users.id_card_number LIKE '$search'
        OR land_parcels.p_location LIKE '$search'

    LIMIT 1

    ";

    $result = mysqli_query($conn, $query);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        Land Verification
    </title>

    

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet">

    <

    <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>

        body{
            background:#dfe6f3;
            font-family:Arial, Helvetica, sans-serif;
            background-image: url(./3d-digital-landscape-with-space-sky-nebula.jpg);
        }

        .top-bar{

            background:white;

            padding:22px 40px;

            box-shadow:0 2px 8px rgba(0,0,0,0.05);
        }
        .page-header{
    display:flex;
    align-items:center;
    gap:20px;
    margin-bottom:30px;
}



        .back-btn{

            text-decoration:none;
            color:#111827;

            font-weight:600;
            font-size:18px;
        }

        .back-btn:hover{

            color:#2563eb;
        }

        .main-container{

            max-width:900px;
            margin:50px auto;
        }

        .title-section{

            display:flex;
            align-items:flex-start;
            gap:18px;

            margin-bottom:35px;
        }

        .icon-box{

            width:60px;
            height:60px;

            background:#16a34a;

            border-radius:16px;

            display:flex;
            justify-content:center;
            align-items:center;

            color:white;

            font-size:28px;
        }

        .page-title{

            font-size:52px;
            font-weight:700;
            color:#0f172a;

            line-height:1;
        }

        .page-subtitle{

            font-size:24px;
            color:#475569;

            margin-top:10px;
        }

        .verification-card{

            background:white;

            padding:35px;

            border-radius:22px;

            box-shadow:0 4px 12px rgba(0,0,0,0.05);
        }

        .card-title{

            font-size:34px;
            font-weight:700;

            color:#111827;
        }

        .card-subtitle{

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

        .form-control{

            height:72px;

            border:none;

            background:#f1f5f9;

            border-radius:14px;

            font-size:22px;

            padding-left:22px;
        }

        .verify-btn{

            width:100%;

            height:72px;

            border:none;

            border-radius:14px;

            background:#020617;

            color:white;

            font-size:22px;
            font-weight:700;

            margin-top:20px;

            transition:0.3s;
        }

        .verify-btn:hover{

            background:#111827;
        }

        .result-card{

            margin-top:35px;

            background:#ffffff;

            border-radius:20px;

            padding:30px;

            box-shadow:0 4px 12px rgba(0,0,0,0.05);
        }

        .result-title{

            font-size:30px;
            font-weight:700;

            margin-bottom:25px;
        }

        .result-row{

            display:flex;
            justify-content:space-between;

            padding:14px 0;

            border-bottom:1px solid #e5e7eb;
        }

        .result-label{

            font-size:20px;
            font-weight:600;

            color:#374151;
        }

        .result-value{

            font-size:20px;
            color:#111827;
        }

        .badge-status{

            padding:8px 18px;

            border-radius:30px;

            color:white;

            font-size:16px;
            font-weight:600;
        }

        .approved{
            background:#16a34a;
        }

        .pending{
            background:#eab308;
        }

        .declined{
            background:#dc2626;
        }

        .not-found{

            background:#fee2e2;
            color:#991b1b;

            padding:18px;

            border-radius:14px;

            font-size:18px;

            margin-top:25px;
        }

        @media(max-width:768px){

            .page-title{
                font-size:34px;
            }

            .page-subtitle,
            .card-subtitle{
                font-size:16px;
            }

            .card-title{
                font-size:24px;
            }

            label{
                font-size:16px;
            }

            .form-control{
                height:58px;
                font-size:16px;
            }

            .verify-btn{
                height:58px;
                font-size:16px;
            }

            .result-row{
                flex-direction:column;
                gap:8px;
            }
        }

    </style>

</head>

<body>

<!-- TOP BAR -->

<div class="top-bar">

    <a href="dashboard.php" class="back-btn">

        <i class="bi bi-arrow-left"></i>
        Back to Dashboard

    </a>

</div>

<!-- MAIN -->

<div class="main-container">

    <!-- TITLE -->

    <div class="title-section">

        <div class="icon-box">

            <i class="bi bi-search"></i>

        </div>

        <div>

            <div class="page-title">

                Land Verification

            </div>

            <div class="page-subtitle">

                Verify land records and ownership details

            </div>

        </div>

    </div>

    <!-- SEARCH CARD -->

    <div class="verification-card">

        <div class="card-title">

            Search for Land Record

        </div>

        <div class="card-subtitle">

            Enter the plot number, land ID, or owner's ID card number

        </div>

        <form action="" method="POST">

            <div class="mb-3">

                <label>

                    Search Query

                </label>

                <input type="text"
                name="search_query"
                class="form-control"
                placeholder="Enter plot number, land ID, or ID card number"
                required>

            </div>

            <button type="submit"
            name="verify_land"
            class="verify-btn">

                <i class="bi bi-search"></i>
                Verify Land

            </button>

        </form>

    </div>

    <!-- RESULT -->

    <?php if(is_array($verification_result)): ?>

        <div class="result-card">

            <div class="result-title">

                Verification Result

            </div>

            <div class="result-row">

                <div class="result-label">

                    Parcel ID

                </div>

                <div class="result-value">

                    <?php echo $verification_result['land_parcels_id']; ?>

                </div>

            </div>

            <div class="result-row">

                <div class="result-label">

                    Owner Name

                </div>

                <div class="result-value">

                    <?php echo $verification_result['first_name']; ?>
                    <?php echo $verification_result['last_name']; ?>

                </div>

            </div>

            <div class="result-row">

                <div class="result-label">

                    ID Card Number

                </div>

                <div class="result-value">

                    <?php echo $verification_result['id_card_number']; ?>

                </div>

            </div>

            <div class="result-row">

                <div class="result-label">

                    Land Location

                </div>

                <div class="result-value">

                    <?php echo $verification_result['p_location']; ?>

                </div>

            </div>

            <div class="result-row">

                <div class="result-label">

                    Surface Area

                </div>

                <div class="result-value">

                    <?php echo $verification_result['surface_area']; ?> m²

                </div>

            </div>

            <div class="result-row">

                <div class="result-label">

                    Current Stage

                </div>

                <div class="result-value">

                    <?php echo $verification_result['current_stage']; ?>

                </div>

            </div>

            <div class="result-row">

                <div class="result-label">

                    Application Status

                </div>

                <div class="result-value">

                    <span class="badge-status
                    <?php
                        if($verification_result['a_status'] == 'APPROVED'){
                            echo 'approved';
                        }elseif($verification_result['a_status'] == 'DECLINED'){
                            echo 'declined';
                        }else{
                            echo 'pending';
                        }
                    ?>">

                        <?php echo $verification_result['a_status']; ?>

                    </span>

                </div>

            </div>

        </div>

    <?php elseif($verification_result == "not_found"): ?>

        <div class="not-found">

            <i class="bi bi-exclamation-circle"></i>
            No matching land record found.

        </div>

    <?php endif; ?>

</div>

</body>
</html>