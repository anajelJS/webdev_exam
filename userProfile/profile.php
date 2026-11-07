<?php
session_start();

require_once '../includes/connection.php';
require_once '../classes/auth.php';

$auth = new Auth();
$auth->requireLogin();

$user_id = (int) $_SESSION['user_id'];

$query = $pdo->prepare(
    "SELECT
        userAccount.username,
        userAccount.fName,
        userAccount.lName,
        userAccount.bio,
        userAccount.profilePicture,
        userPostStatsView.total_posts,
        userPostStatsView.total_likes_received
     FROM userAccount
     LEFT JOIN userPostStatsView
        ON userAccount.id = userPostStatsView.user_id
     WHERE userAccount.id = ?"
);
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
<div class="profile-stats">
    <p>
        🌱 Posts: <?= (int) ($user['total_posts'] ?? 0) ?>
    </p>

    <p>
        ❤️ Likes received: <?= (int) ($user['total_likes_received'] ?? 0) ?>
    </p>
</div>

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