<?php
session_start();
require_once "includes/connection.php";
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

echo "<h2>Welcome " . $_SESSION['username'] . "! What would you like to do today?</h2>";
?>

<a href="logout.php">Logout</a>