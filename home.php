
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

$postsQuery = $pdo->prepare(
    "SELECT post.id, post.content, post.createdAt,
            userAccount.username, userAccount.profilePicture
     FROM post
     INNER JOIN userAccount
         ON post.user_id = userAccount.id
     ORDER BY post.createdAt DESC, post.id DESC"
);

$postsQuery->execute();

$posts = $postsQuery->fetchAll(PDO::FETCH_ASSOC);
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


<a href="userProfile/profile.php">Profile</a> <br>
<a href="home.php">Feed</a> <br>
<a href="home.php?page=createPost">Create Post</a> <br>

<?php
$page = $_GET['page'] ?? 'feed';

switch ($page) {
    case 'createPost':
        include 'posts/createPost.php';
        break;

    case 'feed':
        include 'posts/feed.php';
        break;

    default:
        echo "<h3>Page not found.</h3>";
        break;
}
?>
