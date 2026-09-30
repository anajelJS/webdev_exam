<?php
session_start();
require_once "includes/connection.php";
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


if (isset($_POST['loginAccount'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $query = $pdo->prepare("SELECT * FROM userAccount WHERE username = ?");
$query->execute([$username]);
$user = $query->fetch(); 

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['username'] = $username; 
    $_SESSION['user_id'] = $user['id']; 
    header("Location: home.php"); 
    exit(); 
} else {
    echo "Something went wrong. Please try again. ";
}
}