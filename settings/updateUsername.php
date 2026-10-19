<?php
session_start();
require_once "../includes/connection.php";
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if (isset($_POST['changeUsername'])) {
    $newUsername = trim($_POST['newUsername'] ?? '');
    $user_id = $_SESSION['user_id'];

    if ($newUsername === '' || strlen($newUsername) > 255) {
        header("Location: settings.php?page=username&status=invalid");
        exit();
    }
/* check if username is already taken   */    
try {
    $updateUsername = $pdo->prepare(
        "UPDATE userAccount SET username = ? WHERE id = ?"
    );

    $updateUsername->execute([$newUsername, $user_id]);

    $_SESSION['username'] = $newUsername;

    header("Location: settings.php?page=username&status=updated");
    exit();

} catch (PDOException $e) {

    if ($e->getCode() === '23000') {
        header("Location: settings.php?page=username&status=taken");
        exit();
    }

    throw $e;
}
} else {
    header("Location: settings.php?page=username&status=error");
    exit();
}