<?php
session_start();

require_once '../includes/connection.php';
require_once '../classes/auth.php';

$auth = new Auth();
$auth->requireLogin();

$loggedInUserId = (int) $_SESSION['user_id'];

$profileUserId = filter_input(
    INPUT_GET,
    'id',
    FILTER_VALIDATE_INT,
    ['options' => ['min_range' => 1]]
);

if (isset($_GET['id']) && $profileUserId === false) {
    http_response_code(400);
    exit("Invalid profile ID.");
}

if ($profileUserId === null) {
    $profileUserId = $loggedInUserId;
}

$isOwnProfile = ($profileUserId === $loggedInUserId);

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
$query->execute([$profileUserId]);
$user = $query->fetch(PDO::FETCH_ASSOC);
if (!$user) {
    http_response_code(404);
    exit("User not found.");
}
$postsQuery = $pdo->prepare(
    "SELECT
        post.id,
        post.content,
        post.image,
        post.createdAt
     FROM post
     WHERE post.user_id = ?
     ORDER BY post.createdAt DESC, post.id DESC"
);

$postsQuery->execute([$profileUserId]);

$profilePosts = $postsQuery->fetchAll(PDO::FETCH_ASSOC);
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
<?php if ($isOwnProfile): ?>

<a href="../logout.php">Logout</a> <br>
<a href="../settings/settings.php">Account settings</a> <br>
<a href="editProfile.php">Edit profile</a> <br>

<?php endif; ?>

<a href="../home.php">Back to feed</a>
<h3>Posts 🌱</h3>

<?php if (empty($profilePosts)): ?>

    <p>No posts yet.</p>

<?php else: ?>

    <div style="
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
        max-width: 650px;
    ">

        <?php foreach ($profilePosts as $post): ?>

            <div style="
                border: 1px solid #ccc;
                padding: 5px;
            ">

                <?php if (!empty($post['image'])): ?>

                    <img
                        src="../uploads/posts/<?= htmlspecialchars($post['image'], ENT_QUOTES, 'UTF-8') ?>"
                        alt="Post image"
                        style="
                            width: 100%;
                            aspect-ratio: 1;
                            object-fit: cover;
                        "
                    >

                <?php else: ?>

                    <p>
                        <?= htmlspecialchars($post['content'], ENT_QUOTES, 'UTF-8') ?>
                    </p>

                <?php endif; ?>

            </div>

        <?php endforeach; ?>

    </div>

<?php endif; ?>