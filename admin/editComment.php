<?php

if (!defined('GROWUM_ADMIN')) {
    http_response_code(403);
    exit("Access denied.");
}

$commentId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$commentId || $commentId < 1) {
    exit("Invalid comment ID.");
}

$query = $pdo->prepare(
    "SELECT id, content
     FROM postComment
     WHERE id = ?"
);

$query->execute([$commentId]);

$selectedComment = $query->fetch(PDO::FETCH_ASSOC);

if (!$selectedComment) {
    exit("Comment not found.");
}
?>

<h2 id="editCommentForm">Edit Comment</h2>

<form action="updateComment.php" method="POST">

    <input type="hidden" name="csrf_token"
        value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">

    <input type="hidden" name="comment_id"
        value="<?= (int) $selectedComment['id'] ?>">

    <label for="commentContent">Comment content:</label>

    <br>

    <textarea
        id="commentContent"
        name="content"
        rows="5"
        maxlength="1000"
        required
    ><?= htmlspecialchars($selectedComment['content'], ENT_QUOTES, 'UTF-8') ?></textarea>

    <br>

    <button type="submit">Save Changes</button>

    <a href="index.php?page=comments">Cancel</a>

</form>
