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

<title>Land Immatriculation</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>

body{
    background:#eef3ff;
    font-family:Arial, Helvetica, sans-serif;
    background-image: url(./3d-digital-landscape-with-space-sky-nebula.jpg);
}

.top-bar{

            background:grey;

            padding:22px 40px;

            box-shadow:0 2px 8px rgba(0,0,0,0.05);
        }


.main-container{
    max-width:1100px;
    margin:40px auto;
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


.header-icon{
    width:70px;
    height:70px;
    background:#3b82f6;
    border-radius:18px;
    display:flex;
    align-items:center;
    justify-content:center;
    color:white;
    font-size:35px;
}

.page-title{
    font-size:42px;
    font-weight:bold;
    color: white;
}

.page-subtitle{
    color:white;
    font-size:20px;
}

.tab-container{
    background:grey;
    border-radius:20px;
    padding:6px;
    display:flex;
    margin-bottom:25px;
}

.tab-btn{
    flex:1;
    border:none;
    padding:14px;
    border-radius:15px;
    background:transparent;
    font-weight:600;
    transition:0.3s;
}

.tab-btn.active{
    background:white;
}

.form-card{
    background:gray;
    border-radius:20px;
    padding:35px;
    box-shadow:0 4px 15px rgba(0,0,0,0.05);
}

.form-section-title{
    font-size:32px;
    font-weight:bold;
}

.form-section-subtitle{
    color:#6b7280;
    font-size:18px;
    margin-bottom:35px;
}

.sub-heading{
    font-size:28px;
    font-weight:bold;
    margin-top:40px;
    margin-bottom:25px;
}

.form-label{
    font-weight:600;
}

.form-control,
.form-select{
    border-radius:12px;
    padding:14px;
}

.submit-btn{
    background:#111827;
    color:white;
    border:none;
    padding:15px;
    border-radius:12px;
    width:100%;
    font-size:18px;
    margin-top:30px;
}

.submit-btn:hover{
    background:black;
}

.hidden-form{
    display:none;
}

.radio-group{
    display:flex;
    gap:25px;
}

</style>

</head>

<body>
    <div class="top-bar">

    <a href="dashboard.php" class="back-btn">

        <i class="bi bi-arrow-left"></i>
        Back to Dashboard

    </a>

</div>


<div class="main-container">

<div class="page-header">

<div class="header-icon">
<i class="bi bi-file-earmark-text"></i>
</div>

<div>

<div class="page-title">
Land Immatriculation
</div>

<div class="page-subtitle">
Register your land with our secure system
</div>

</div>

</div>

<!-- SUCCESS MESSAGE -->

<?php

if(isset($_SESSION['success_message'])){

    echo '
    <div class="alert alert-success">
        '.$_SESSION['success_message'].'
    </div>
    ';

    unset($_SESSION['success_message']);
}

?>



<div class="tab-container">

   <button class="tab-btn active" id="directTab">Direct Immatriculation</button>
   <button class="tab-btn" id="concessionTab">Concession</button>

</div>

<!-- DIRECT IMMATRICULATION -->

<div class="form-card" id="directForm">

      <div class="form-section-title">Direct Immatriculation</div>
      <div class="form-section-subtitle">Register land directly with proof of ownership</div>

   <form action="process_direct_immatriculation.php" method="POST" enctype="multipart/form-data">

   <h2 class="sub-heading">Requester's Information</h2>

    <div class="row">

        <div class="col-md-6 mb-4">

           <label class="form-label">First Name</label>

           <input type="text" name="first_name" class="form-control" required>

        </div>

        <div class="col-md-6 mb-4">

            <label class="form-label">Last Name</label>
            
            <input type="text" name="last_name" class="form-control" required>

        </div>

    </div>

    <div class="mb-4">

      <label class="form-label">Filliation</label>

      <input type="text" name="filliation" class="form-control">

    </div>

    <div class="mb-4">

       <label class="form-label">Address</label>

       <input type="text" name="u_address" class="form-control">
    </div>

    <div class="row">

        <div class="col-md-6 mb-4">

          <label class="form-label">ID Card Number</label>

          <input type="text" name="id_card_number" class="form-control">

        </div>

        <div class="col-md-6 mb-4">

            <label class="form-label">Nationality</label>

            <select name="nationality" class="form-select">

                <option value="">Select Nationality</option>

                <option value="CAMEROONIAN">CAMEROONIAN</option>
                <option value="ETHIOPIAN">ETHIOPIAN</option>
                <option value="NIGERIAN">NIGERIAN</option>
                <option value="MALIAN">MALIAN</option>
                <option value="TCHADIAN">TCHADIAN</option>
                <option value="GUINEAN">GUINEAN</option>
                <option value="SENEGALESE">SENEGALESE</option>
                <option value="CONGOLESE">CONGOLESE</option>

            </select>

        </div>

    </div>

    <div class="row">

        <div class="col-md-6 mb-4">

             <label class="form-label">Upload Front ID Card</label>

            <input type="file" name="id_card_front" class="form-control" required>
        </div>

        <div class="col-md-6 mb-4">

           <label class="form-label">Upload Back ID Card</label>

           <input type="file" name="id_card_back" class="form-control" required>

        </div>

    </div>

    <div class="row">

        <div class="col-md-6 mb-4">

           <label class="form-label">Profession</label>

           <input type="text" name="profession" class="form-control">

        </div>

        <div class="col-md-6 mb-4">

            <label class="form-label">Marital Status</label>

            <div class="radio-group">

                <div>
                    <input type="radio" name="marital_status" value="1">Yes

                </div>

                <div>
                    <input type="radio" name="marital_status" value="0">No

                </div>

            </div>

        </div>

    </div>

    <h2 class="sub-heading">Land Information</h2>

    <div class="form-check mb-4">

        <input type="checkbox" name="imm_condition" value="1" class="form-check-input" required>

        <label class="form-check-label">I CONFIRM THE LAND WAS PUT TO PRODUCTIVITY (MISE EN VALEUR) BEFORE AUGUST 5 1974</label>

    </div>

    <div class="mb-4">

         <label class="form-label">Lot Size (M²)</label>

        <input type="number" name="land_size" class="form-control">

    </div>

    <div class="mb-4">

        <label class="form-label">Land Location</label>

        <input type="text" name="land_location" class="form-control">

    </div>

    <div class="mb-4">

       <label class="form-label">Additional Description</label>

       <textarea name="additional_descr" rows="5" class="form-control"></textarea>

    </div>

    <button type="submit" name="submit_direct_immatriculation" class="submit-btn">Submit Direct Immatriculation</button>

   </form>

</div>

<!-- CONCESSION -->

<div class="form-card hidden-form" id="concessionForm">

<div class="form-section-title">Concession</div>

<div class="form-section-subtitle">Apply for land concession from government authorities</div>

<form action="process_concession.php" method="POST" enctype="multipart/form-data">

<h2 class="sub-heading">Concession Information</h2>

<div class="mb-4">

    <label class="form-label">Concession Start Date</label>

    <input type="date" name="concession_start_date" class="form-control" required>

</div>

<div class="mb-4">

<label class="form-label">Concession Title</label>

<input type="text"
name="concession_title"
class="form-control">

</div>

<div class="mb-4">

<label class="form-label">
Requested Size (M²)
</label>

<input type="number"
name="requested_size"
class="form-control">

</div>

<div class="mb-4">

<label class="form-label">
Concession Duration (Years)
</label>

<input type="number"
name="concession_duration"
class="form-control">

</div>

<div class="mb-4">

<label class="form-label">
Desired Location
</label>

<input type="text"
name="desired_location"
class="form-control">

</div>

<div class="mb-4">

<label class="form-label">
Purpose of Concession
</label>

<textarea name="purpose_of_concession"
rows="5"
class="form-control"></textarea>

</div>

<div class="row">

<div class="col-md-6 mb-4">

<label class="form-label">
Business Plan Document
</label>

<input type="file"
name="business_plan"
class="form-control"
required>

</div>

<div class="col-md-6 mb-4">

<label class="form-label">
Identification Document
</label>

<input type="file"
name="identification_document"
class="form-control"
required>

</div>

</div>

<button type="submit"
name="submit_concession"
class="submit-btn">

Submit Concession Application

</button>

</form>

</div>

</div>

<script>

const directTab =
document.getElementById("directTab");

const concessionTab =
document.getElementById("concessionTab");

const directForm =
document.getElementById("directForm");

const concessionForm =
document.getElementById("concessionForm");

directTab.addEventListener("click", function(){

    directTab.classList.add("active");

    concessionTab.classList.remove("active");

    directForm.classList.remove("hidden-form");

    concessionForm.classList.add("hidden-form");

});

concessionTab.addEventListener("click", function(){

    concessionTab.classList.add("active");

    directTab.classList.remove("active");

    concessionForm.classList.remove("hidden-form");

    directForm.classList.add("hidden-form");

});

</script>

</body>
</html>