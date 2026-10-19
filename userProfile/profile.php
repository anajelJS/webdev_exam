<?php
session_start();
require_once '../includes/connection.php';
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$query = $pdo->prepare("SELECT username, fName, lName, bio FROM userAccount WHERE id = ?");
$query->execute([$user_id]);

$user = $query->fetch(PDO::FETCH_ASSOC);
?>

<h2>
    @<?= htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8') ?>
</h2>

<h3>
    <?= htmlspecialchars($user['fName'] . ' ' . $user['lName'], ENT_QUOTES, 'UTF-8') ?>
</h3>

<p>
    <?= htmlspecialchars($user['bio'] ?? 'No bio yet.', ENT_QUOTES, 'UTF-8') ?> 
</p>

<a href="../logout.php">Logout</a> <br>
<a href="settings/settings.php">Account settings</a> <br>
<a href="editProfile.php">Edit profile</a> <br>
<a href="home.php">Back to feed</a>