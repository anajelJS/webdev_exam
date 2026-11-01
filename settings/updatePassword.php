
<?php
session_start();
require_once "../includes/connection.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if (isset($_POST['changePassword'])) {
    if (
        !isset($_POST['csrf_token'], $_SESSION['csrf_token']) ||
        !is_string($_POST['csrf_token']) ||
        !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
) {
    echo "Invalid security token. Please try again.";
    exit();
}
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    if (strlen($newPassword) < 8) {
    echo "New password must be at least 8 characters long";
    exit();
}
    if ($newPassword !== $confirmPassword) {
        echo "New password and confirm password do not match";
        exit();
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
}
