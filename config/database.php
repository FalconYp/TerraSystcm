<?php

// START SESSION
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// DATABASE CONFIGURATION
$host = "localhost";
$username = "root";
$password = "";
$database = "terra_systcm";

// CREATE CONNECTION
$conn = mysqli_connect($host, $username, $password, $database);

// CHECK CONNECTION
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

?>