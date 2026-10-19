<?php
session_start();
require_once "includes/connection.php";

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$query = $pdo->prepare(
    "SELECT profilePicture FROM userAccount WHERE id = ?"
);

$query->execute([$user_id]);

$user = $query->fetch(PDO::FETCH_ASSOC);
?>

<?php if (!empty($user['profilePicture'])): ?>

    <img
        src="uploads/<?= htmlspecialchars($user['profilePicture'], ENT_QUOTES, 'UTF-8') ?>"
        alt="Profile picture"
        width="100"
        height="100"
        style="object-fit: cover; border-radius: 50%;"
    >

<?php else: ?>

    <p>No profile picture yet.</p>

<?php endif; ?>

<br>

<h2>
    Welcome <?= htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8') ?>!
    What would you like to do today?
</h2>

<a href="userProfile/profile.php">Profile</a>