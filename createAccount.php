<?php
require_once "includes/connection.php";
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (isset($_POST['createAccount'])) {
    $fName = $_POST['fName'];
    $lName = $_POST['lName'];
    $username = $_POST['username'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    $query = $pdo->prepare("INSERT INTO userAccount (fName, lName, username, gender, email, password) VALUES (?, ?, ?, ?, ?, ?)");
    $query->execute([$fName, $lName, $username, $gender, $email, $hashedPassword]);
    header("Location: login.php?status=added");
    exit();
} else {
    echo "Something went wrong. Please try again. ";
    exit();
}
?>  