<?php

if (!defined('GROWUM_ADMIN')) {
    http_response_code(403);
    exit("Access denied.");
}

$query = $pdo->prepare(
    "SELECT
        postComment.id,
        postComment.user_id,
        postComment.post_id,
        postComment.parent_comment_id,
        postComment.content,
        postComment.createdAt,
        userAccount.username
     FROM postComment
     INNER JOIN userAccount
         ON postComment.user_id = userAccount.id
     ORDER BY postComment.createdAt DESC, postComment.id DESC"
);

$query->execute();

$comments = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Manage Comments 💬</h2>

<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Author</th>
        <th>Comment</th>
        <th>Type</th>
        <th>Created</th>
        <th>Actions</th>
    </tr>

    <?php foreach ($comments as $comment): ?>
        <tr>
            <td><?= (int) $comment['id'] ?></td>

            <td>
                <a href="../userProfile/profile.php?id=<?= (int) $comment['user_id'] ?>">
                    <?= htmlspecialchars($comment['username'], ENT_QUOTES, 'UTF-8') ?>
                </a>
            </td>

            <td>
                <?= htmlspecialchars($comment['content'], ENT_QUOTES, 'UTF-8') ?>
            </td>

            <td>
                <?php if ($comment['parent_comment_id'] !== null): ?>
                    Reply
                <?php else: ?>
                    Comment
                <?php endif; ?>
            </td>

            <td>
                <?= htmlspecialchars($comment['createdAt'], ENT_QUOTES, 'UTF-8') ?>
            </td>

                        
            <td>
                <a href="../posts/viewPost.php?id=<?= (int) $comment['post_id'] ?>">
                    View Post
                </a>
                <a href="index.php?page=editComment&id=<?= (int) $comment['id'] ?>#editCommentForm">
                    Edit Comment
                </a>

                <form action="deleteComment.php" method="POST"
                    onsubmit="return confirm('Are you sure you want to permanently delete this comment and any of its replies?');">

                    <input type="hidden" name="csrf_token"
                        value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">

                    <input type="hidden" name="comment_id"
                        value="<?= (int) $comment['id'] ?>">

                    <button type="submit">Delete Comment</button>

                </form>
            </td>

        </tr>
    <?php endforeach; ?>
</table>
