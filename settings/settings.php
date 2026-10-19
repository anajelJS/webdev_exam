<?php

session_start();

require_once "../includes/connection.php";
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Account settings | Growum</title>
</head>

<body>
    <h1>Account settings</h1>



    <!-- update username status message-------------------------------------------------------------------------------------------------------------------------------------- -->
    <?php
        $status = $_GET['status'] ?? '';

        if ($status === 'updated') {
            echo "<p>Username updated successfully!</p>";
        } elseif ($status === 'invalid') {
            echo "<p>Please enter a valid username.</p>";
        } elseif ($status === 'failed') {
            echo "<p>Username update failed.</p>";
        } elseif ($status === 'taken') {
            echo "<p>This username is already taken. Please choose another.</p>";
        }
        ?> <br>


    <a href="settings.php?page=username">Change username</a>
    <br>
    <!-- change username  logic-------------------------------------------------------------------------------------------------------------------------------------- -->
    <?php
    $page = $_GET['page'] ?? 'main';
    switch ($page) {
        case 'username':
            include 'changeUsername.php';
            break;
    }
    ?> <br>
    <a href="settings.php?page=password">Change password</a>
    <br>
    <!-- change password  logic-------------------------------------------------------------------------------------------------------------------------------------- -->
    <?php
    $page = $_GET['page'] ?? 'main';
    switch ($page) {
        case 'password':
            include 'changePassword.php';
            break;
    }
    ?> <br>
    <!---------------------------------------------------------------------------------------------------------------------------------------- -->

    <a href="settings.php?page=delete">Delete account</a>
    <br>

    <a href="../home.php">Back</a>


    

</body>

</html>