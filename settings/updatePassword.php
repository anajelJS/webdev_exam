<?php
session_start();
require_once "../includes/connection.php";
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if (isset($_POST['updatePassword'])) {
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    if ($newPassword !== $confirmPassword) {
        echo "New password and confirm password do not match";
        exit();
    }

    $query = $pdo->prepare("SELECT password FROM userAccount WHERE id = ?");
    $query->execute([$_SESSION['user_id']]);
    $user = $query->fetch();
    if (password_verify($currentPassword, $user['password'])) {
    echo "Current password is correct!";
} else {
    echo "Current password is incorrect!";
    exit();
}
}