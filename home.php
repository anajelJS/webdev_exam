<?php
session_start();
require_once "includes/connection.php";
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


?>

<h2>
    Welcome <?= htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8') ?>!
    What would you like to do today?
</h2>

<a href="userProfile/profile.php">Profile</a> <br>