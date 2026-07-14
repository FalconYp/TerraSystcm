<?php

include 'config/database.php';

if($_SERVER['REQUEST_METHOD'] == "POST"){

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $query = "SELECT * FROM users WHERE email='$email' LIMIT 1";

    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) > 0){

        $user = mysqli_fetch_assoc($result);

        /* temporary password checking
            later use password harsh()
        */

        if($password == $user['u_password']){

            $_SESSION['users_id'] = $user['users_id'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name'] = $user['last_name'];
            $_SESSION['role'] = $user['u_role'];

            /*
                role direction
            */

            switch($user['u_role']){

                case 'citizen':
                    header("Location: citizen/dashboard.php");
                    break;

                case 'DO_officer':
                    header("Location: DO_officer/dashboard.php");
                    break;

                case 'committee':
                    header("Location: committee/dashboard.php");
                    break;

                case 'land_officer':
                    header("Location: land_officer/dashboard.php");
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
                    break;
            }

            exit();

        }else{

            header("Location: login.php?error=1");
            exit();
        }

    }else{

        header("Location: login.php?error=1");
        exit();
    }
}
?>