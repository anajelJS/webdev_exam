<?php
require_once "includes/connection.php";
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (isset($_POST['createAccount'])) {
    if (!empty($_POST['website'])) {
    exit("Registration rejected.");
    }



    $fName = $_POST['fName'];
    $lName = $_POST['lName'];
    $username = $_POST['username'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (strlen($password) < 8) {
        echo "Password must be at least 8 characters long";
        exit();
    }


    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    try {
    $query = $pdo->prepare("INSERT INTO userAccount (fName, lName, username, gender, email, password) VALUES (?, ?, ?, ?, ?, ?)");
    $query->execute([$fName, $lName, $username, $gender, $email, $hashedPassword]);
    header("Location: login.php?status=added");
    exit();
} catch (PDOException $e) {
    if ($e->getCode() === '23000') {
        header("Location: signup.php?status=taken");
        exit();
    }
    throw $e;
}
} else {
    echo "A user with this username or email already exists. Please choose another username or email. ";
    exit();
} 
?>  