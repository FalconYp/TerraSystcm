<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// USER MUST LOGIN
if (!isset($_SESSION['users_id'])) {

    header("Location: ../login.php");
    exit();
}
?>