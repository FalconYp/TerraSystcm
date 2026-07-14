<?php

session_start();

include 'config/database.php';

$error = "";

if(isset($_POST['login'])){

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $query = "
    SELECT *
    FROM users
    WHERE email='$email'
    LIMIT 1
    ";

    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) > 0){

        $user = mysqli_fetch_assoc($result);

      
        if(password_verify($password, $user['u_password'])){

            

            $_SESSION['users_id'] = $user['users_id'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name'] = $user['last_name'];
            $_SESSION['u_role'] = $user['u_role'];

           

            switch($user['u_role']){

                case 'citizen':
                    header("Location: citizen/dashboard.php");
                    break;

                case 'DO_officer':
                    header("Location: DO_officer/dashboard.php");
                    break;

                case 'land_officer':
                    header("Location: land_officer/dashboard.php");
                    break;

                case 'committee':
                    header("Location: committee/dashboard.php");
                    break;

                case 'geometre':
                    header("Location: geometre/dashboard.php");
                    break;

                case 'conservation':
                    header("Location: conservation/dashboard.php");
                    break;

                case 'admin':
                    header("Location: admin/dashboard.php");
                    break;

                case 'delege':
                    header("Location: delege/dashboard.php");
                    break;

                case 'notary':
                    header("Location: notary/dashboard.php");
                    break;

                default:
                    header("Location: login.php");
            }

            exit();

        }else{

            $error = "Invalid Password";
        }

    }else{

        $error = "Account Not Found";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
    content="width=device-width, initial-scale=1.0">

    <title>TerraSystcm Login</title>

    <!-- Bootstrap -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet">

    <!-- Bootstrap Icons -->

    <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{

            min-height:100vh;

            display:flex;
            justify-content:center;
            align-items:center;

            background-image: url(./3d-digital-landscape-with-space-sky-nebula.jpg);

            font-family:Arial, Helvetica, sans-serif;
        }

        .login-container{

            width:100%;
            max-width:450px;

            background:white;

            padding:40px;

            border-radius:22px;
             background:rgba(0,0,0,0.05);
            box-shadow:0 8px 30px rgba(0,0,0,0.08);
        }

        .login-header{

            text-align:center;
            margin-bottom:35px;
           
        }

        .login-logo{

            width:85px;
            height:85px;

            margin:auto;
            margin-bottom:20px;

            border-radius:20px;

            background:#2563eb;

            display:flex;
            justify-content:center;
            align-items:center;

            color:white;
            font-size:35px;
        }

        .login-title{

            font-size:30px;
            font-weight:bold;

            color:white;
        }

        .login-subtitle{

            color:white;
            margin-top:8px;
        }

        .form-label{

            font-weight:600;
            margin-bottom:8px;

            color:white;
        }

        .form-control{

            height:55px;

            border-radius:14px;

            border:1px solid #d1d5db;

            padding-left:15px;
        }

        .form-control:focus{

            box-shadow:none;

            border:1px solid #2563eb;
        }

        .login-btn{

            width:100%;

            height:35px;

            border:none;

            border-radius:14px;

            background:#020617;

            color:white;

            font-weight:bold;

            margin-top:10px;

            transition:0.3s;
        }

        .login-btn:hover{

            background:#111827;
        }

        .error-box{

            background:#fee2e2;

            color:#b91c1c;

            padding:12px 15px;

            border-radius:12px;

            margin-bottom:20px;

            font-size:15px;
        }

        .signup-link{

            text-align:center;

            margin-top:25px;

            color:#6b7280;
        }

        .signup-link a{

            text-decoration:none;

            color:#2563eb;

            font-weight:600;
        }

    </style>

</head>

<body>

<div class="login-container">

    

    <div class="login-header">

        <div class="login-logo">

            <i class="bi bi-globe2"></i>

        </div>

        <div class="login-title">

            Terra_systcm

        </div>

        <div class="login-subtitle">

            Secure Land Management Platform

        </div>

    </div>

    <!-- ERROR -->

    <?php if($error != ""): ?>

        <div class="error-box">

            <?php echo $error; ?>

        </div>

    <?php endif; ?>

    <!-- FORM -->

    <form method="POST">

        <!-- EMAIL -->

        <div class="mb-3">

            <label class="form-label">

                Email Address

            </label>

            <input type="email"
            name="email"
            class="form-control"
            placeholder="Enter your email"
            required>

        </div>

        <!-- PASSWORD -->

        <div class="mb-4">

            <label class="form-label">

                Password

            </label>

            <input type="password"
            name="password"
            class="form-control"
            placeholder="Enter your password"
            required>

        </div>

        <!-- BUTTON -->

        <button type="submit"
        name="login"
        class="login-btn">

            <i class="bi bi-box-arrow-in-right"></i>

            Login

        </button>

    </form>

    <!-- REGISTER -->

    <div class="signup-link">

        Don't have an account?

        <a href="register.php">

            Sign up

        </a>

    </div>

</div>

<!-- BOOTSTRAP -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>

    console.log("Login Page Loaded");

</script>

</body>
</html>