<!-- register.php -->
<?php
session_start();
include 'config/database.php';

$success = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $date_of_birth = $_POST['date_of_birth'];
    $nationality = $_POST['nationality'];
    $profession = trim($_POST['profession']);
    $email = trim($_POST['email']);
    $phone_number = trim($_POST['phone_number']);
    $password = $_POST['u_password'];
    $id_card_number = trim($_POST['id_card_number']);
    $u_role = $_POST['u_role'];

    
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    //check if email exist already
    $check = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {

        $error = "Email already exists.";

    } else {

        $sql = "INSERT INTO users(
                    first_name,
                    last_name,
                    date_of_birth,
                    nationality,
                    profession,
                    email,
                    phone_number,
                    u_password,
                    id_card_number,
                    u_role
                )
                VALUES(
                    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
                )";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "ssssssssss",
            $first_name,
            $last_name,
            $date_of_birth,
            $nationality,
            $profession,
            $email,
            $phone_number,
            $hashed_password,
            $id_card_number,
            $u_role
        );

        if ($stmt->execute()) {

            $success = "Registration successful.";

        } else {

            $error = "Something went wrong.";

        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <title>D-LandVerify Registration</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>

        body{
            background:#eef3ff;
            font-family:Arial, Helvetica, sans-serif;
            background-image: url(./3d-digital-landscape-with-space-sky-nebula.jpg);
        }

        .register-card{
           color: white;
        }

        .logo-icon{
            width:70px;
            height:70px;
            background:green;
            color:white;
            border-radius:50%;
            display:flex;
            align-items:center;
            justify-content:center;
            margin:auto;
            font-size:30px;
        }

        .btn-register{
            background:green;
            color:white;
            border-radius:10px;
            padding:12px;
            font-weight:bold;
        }

        .btn-register:hover{
            background:#111827;
            color:white;
        }
        p.create{
            color: wheat;
        }
        

    </style>

</head>
<body>

<div class="container">

    <div class="register-card">

        <div class="text-center mb-4">

            <div class="logo-icon">
                <i class="bi bi-map"></i>
            </div>

            <h2 class="mt-3 fw-bold">terra_systcm Registration</h2>

            <p class="create"> Create your account</p>

        </div>

        <?php if($success != ""): ?>

            <div class="alert alert-success">
                <?php echo $success; ?>
            </div>

        <?php endif; ?>

        <?php if($error != ""): ?>

            <div class="alert alert-danger">
                <?php echo $error; ?>
            </div>

        <?php endif; ?>

        <form method="POST">

            <div class="row">

                <div class="col-md-6 mb-3">
                    <label class="form-label">First Name</label>
                    <input type="text" name="first_name" class="form-control" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Last Name</label>
                    <input type="text" name="last_name" class="form-control" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Date of Birth</label>
                    <input type="date" name="date_of_birth" class="form-control" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Nationality</label>

                    <select name="nationality" class="form-select" required>

                        <option value="">Select Nationality</option>

                        <option>CAMEROONIAN</option>
                        <option>ETHIOPIAN</option>
                        <option>NIGERIAN</option>
                        <option>MALIAN</option>
                        <option>TCHADIAN</option>
                        <option>GUINEAN</option>
                        <option>SENEGALESE</option>
                        <option>CONGOLESE</option>

                    </select>

                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Profession</label>
                    <input type="text" name="profession" class="form-control">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Phone Number</label>
                    <input type="text" name="phone_number" class="form-control">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">National ID Card Number</label>
                    <input type="text" name="id_card_number" class="form-control">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Role</label>

                    <select name="u_role" class="form-select">

                        <option value="">Select Role</option>

                        <option value="citizen">Citizen</option>
                        <option value="DO_officer">DO Officer</option>
                        <option value="land_officer">Land Officer</option>
                        <option value="committee">Committee</option>
                        <option value="geometre">Geometre</option>
                        <option value="conservation">Conservation</option>
                        <option value="admin">Admin</option>
                        <option value="delege">Delegate</option>
                        <option value="notary">Notary</option>

                    </select>

                </div>

                <div class="col-md-12 mb-4">
                    <label class="form-label">Password</label>
                    <input type="password" name="u_password" class="form-control">
                </div>

            </div>

            <button type="submit" class="btn btn-register w-100">Create Account</button>

        </form>

        <div class="text-center mt-4">Already have an account?
            <a href="login.php">Login</a>

        </div>

    </div>

</div>

<script>

    // SIMPLE PASSWORD VALIDATION

    document.querySelector("form").addEventListener("submit", function(e){

        let password = document.querySelector(
            "input[name='u_password']"
        ).value;

        if(password.length < 6){

            e.preventDefault();

            alert("Password must be at least 6 characters.");

        }

    });

</script>

</body>
</html>