<?php

if (!defined('GROWUM_ADMIN')) {
    http_response_code(403);
    exit("Access denied.");
}

$postId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$postId || $postId < 1) {
    exit("Invalid post ID.");
}

$query = $pdo->prepare(
    "SELECT id, content, image
     FROM post
     WHERE id = ?"
);

$query->execute([$postId]);

$selectedPost = $query->fetch(PDO::FETCH_ASSOC);

if (!$selectedPost) {
    exit("Post not found.");
}
?>

<h2 id="editPostForm">Edit Post</h2>

<?php if (!empty($selectedPost['image'])): ?>
    <img
        src="../uploads/posts/<?= htmlspecialchars($selectedPost['image'], ENT_QUOTES, 'UTF-8') ?>"
        alt="Post image"
        width="200"
    >
<?php endif; ?>

<form action="updatePost.php" method="POST">

    <input type="hidden" name="csrf_token"
        value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">

    <input type="hidden" name="post_id"
        value="<?= (int) $selectedPost['id'] ?>">

    <label for="content">Post content:</label>

    <br>

    <textarea id="content" name="content" rows="5"><?= htmlspecialchars($selectedPost['content'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>

    <br>

    <button type="submit">Save Changes</button>

    <a href="index.php?page=posts">Cancel</a>

</form>
