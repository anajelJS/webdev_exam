<?php
session_start();
require_once "../includes/connection.php";
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}


if (
    isset($_POST['deleteAccount']) &&
    isset($_POST['confirmDelete']) 
) {
    $deleteAccount = $pdo->prepare("DELETE FROM userAccount WHERE id = ?");
    $deleteAccount->execute([$_SESSION['user_id']]);


    $_SESSION = [];
    session_destroy();


    header("Location: settings.php?page=delete&status=deleted");
    exit();
} else {

header("Location: settings.php?page=delete&status=failed");
exit();

}

?>