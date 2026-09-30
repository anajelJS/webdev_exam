<?php
session_start();
require_once "includes/connection.php";
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


echo "<h2>Welcome " . $_SESSION['username'] . "! What would you like to do today?</h2>";
?>

<a href="logout.php">Logout</a>