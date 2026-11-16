<?php

if ($postOwnerId !== $loggedInUserId) {
    return;
}
?>

<form
    action="<?= htmlspecialchars($deletePostAction, ENT_QUOTES, 'UTF-8') ?>"
    method="POST"
    onsubmit="return confirm('Are you sure you want to permanently delete this post? This cannot be undone.');"
>
    <input
        type="hidden"
        name="csrf_token"
        value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>"
    >

    <input
        type="hidden"
        name="post_id"
        value="<?= (int) $postId ?>"
    >

    <button type="submit" name="deletePost">
        Delete Post
    </button>
</form>
