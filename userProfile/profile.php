<?php
session_start();
require_once '../includes/connection.php';
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$query = $pdo->prepare("SELECT username, fName, lName, bio, profilePicture FROM userAccount WHERE id = ?");
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

<?php if (!empty($user['profilePicture'])): ?>

<img
    src="../uploads/<?= htmlspecialchars($user['profilePicture'], ENT_QUOTES, 'UTF-8') ?>"
    alt="Profile picture"
    width="100"
    height="100"
    style="object-fit: cover; border-radius: 50%;"
>

<?php else: ?>

<p>No profile picture yet.</p>

<?php endif; ?>

<br>
<a href="../logout.php">Logout</a> <br>
<a href="../settings/settings.php">Account settings</a> <br>
<a href="editProfile.php">Edit profile</a> <br>
<a href="../home.php">Back to feed</a>