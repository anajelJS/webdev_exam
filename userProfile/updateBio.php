<?php
session_start();

require_once '../includes/connection.php';
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if (isset($_POST['updateBio'])) {
    $bio = trim($_POST['bio'] ?? '');
    $user_id = $_SESSION['user_id'];

    if (mb_strlen($bio, 'UTF-8') > 500) {
    header("Location: editProfile.php?status=invalid");
    exit();
}


$updateBio = $pdo->prepare(
    "UPDATE userAccount SET bio = ? WHERE id = ?"
);

$updateBio->execute([$bio, $user_id]);

header("Location: profile.php?status=updated");
exit();

    
}