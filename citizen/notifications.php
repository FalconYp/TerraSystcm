<?php

session_start();

include '../config/database.php';

if(!isset($_SESSION['users_id'])){
    header("Location: ../login.php");
    exit();
}

$user_name = $_SESSION['first_name'];

?>
