
<?php
session_start();
require_once "../includes/connection.php";

require_once '../classes/auth.php';

$auth = new Auth();

$auth->requireLogin($pdo);
$auth->requirePost();
$auth->validateCsrf();

$currentPassword = $_POST['currentPassword'] ?? '';
$newPassword = $_POST['newPassword'] ?? '';
$confirmPassword = $_POST['confirmPassword'] ?? '';

if (
    !is_string($currentPassword) ||
    !is_string($newPassword) ||
    !is_string($confirmPassword)
) {
    exit("Invalid password input.");
}

if (strlen($newPassword) < 8) {
    exit("New password must be at least 8 characters long.");
}

if ($newPassword !== $confirmPassword) {
    exit("New password and confirm password do not match.");
}

    $query = $pdo->prepare("SELECT password FROM userAccount WHERE id = ?");
    $query->execute([$_SESSION['user_id']]);
    $user = $query->fetch();

    if ($user && password_verify($currentPassword, $user['password'])) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        $query = $pdo->prepare("UPDATE userAccount SET password = ? WHERE id = ?");
        $query->execute([$hashedPassword, $_SESSION['user_id']]);
        
        session_unset();
        session_destroy();
        
        header("Location: ../login.php");
        exit();
    } else {
        echo "Current password is incorrect!";
        exit();
    }

