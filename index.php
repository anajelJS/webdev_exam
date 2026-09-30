<?php
require_once "includes/connection.php";
session_start();

if (isset($_SESSION['user_id'])) {
    // User is already logged in
    header("Location: home.php");
    exit;
}

// User is not logged in
header("Location: login.php");
exit;