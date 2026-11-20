<?php

if (!defined('GROWUM_ADMIN')) {
    http_response_code(403);
    exit("Access denied.");
}

$query = $pdo->prepare(
    "SELECT
        post.id,
        post.user_id,
        post.content,
        post.image,
        post.createdAt,
        userAccount.username
     FROM post
     INNER JOIN userAccount
         ON post.user_id = userAccount.id
     ORDER BY post.createdAt DESC, post.id DESC"
);

$query->execute();

$posts = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Manage Posts 📸</h2>

<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Author</th>
        <th>Image</th>
        <th>Content</th>
        <th>Created</th>
        <th>Actions</th>
    </tr>

    <?php foreach ($posts as $post): ?>
        <tr>
            <td><?= (int) $post['id'] ?></td>

            <td>
                <a href="../userProfile/profile.php?id=<?= (int) $post['user_id'] ?>">
                    <?= htmlspecialchars($post['username'], ENT_QUOTES, 'UTF-8') ?>
                </a>
            </td>

            <td>
                <?php if (!empty($post['image'])): ?>
                    <img
                        src="../uploads/posts/<?= htmlspecialchars($post['image'], ENT_QUOTES, 'UTF-8') ?>"
                        alt="Post image"
                        width="100"
                    >
                <?php else: ?>
                    No image
                <?php endif; ?>
            </td>

            <td>
                <?= htmlspecialchars($post['content'] ?? '', ENT_QUOTES, 'UTF-8') ?>
            </td>

            <td>
                <?= htmlspecialchars($post['createdAt'], ENT_QUOTES, 'UTF-8') ?>
            </td>

            <td>
                
            <td>
                <a href="../posts/viewPost.php?id=<?= (int) $post['id'] ?>">
                    View Post
                </a>
                <a href="index.php?page=editPost&id=<?= (int) $post['id'] ?>#editPostForm">
                    Edit Post
                </a>
                <form action="deletePost.php" method="POST"
                    onsubmit="return confirm('Are you sure you want to permanently delete this post?');">

                    <input type="hidden" name="csrf_token"
                        value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">

                    <input type="hidden" name="post_id"
                        value="<?= (int) $post['id'] ?>">

                    <button type="submit">Delete Post</button>

                </form>
            </td>

            </td>
        </tr>
    <?php endforeach; ?>
</table>
