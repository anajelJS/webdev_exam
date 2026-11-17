<?php
session_start();

require_once '../includes/connection.php';
require_once '../classes/auth.php';
require_once '../classes/like.php';
require_once '../classes/comment.php';

$auth = new Auth();
$auth->requireLogin($pdo);

$like = new Like($pdo);
$comment = new Comment($pdo);

$loggedInUserId = (int) $_SESSION['user_id'];

$postId = filter_input(
    INPUT_GET,
    'id',
    FILTER_VALIDATE_INT,
    ['options' => ['min_range' => 1]]
);

if (!$postId) {
    http_response_code(400);
    exit("Invalid post ID.");
}


$query = $pdo->prepare(
    "SELECT
        post.id,
        post.user_id,
        post.content,
        post.image,
        post.createdAt,
        userAccount.username,
        userAccount.profilePicture,
        postEngagementView.like_count,
        postEngagementView.comment_count
     FROM post
     INNER JOIN userAccount
         ON post.user_id = userAccount.id
     LEFT JOIN postEngagementView
         ON post.id = postEngagementView.post_id
     WHERE post.id = ?"
);

$query->execute([$postId]);

$post = $query->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    http_response_code(404);
    exit("Post not found.");
}

$hasLiked = $like->hasLiked($loggedInUserId, $postId);
$comments = $comment->getComments($postId);

$likeCount = (int) ($post['like_count'] ?? 0);
$commentCount = (int) ($post['comment_count'] ?? 0);

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>


<h2>Post 🌱</h2>

<div style="max-width: 650px; padding: 15px; border: 1px solid #ccc;">

    <a href="../userProfile/profile.php?id=<?= (int) $post['user_id'] ?>">

        <?php if (!empty($post['profilePicture'])): ?>

            <img
                src="../uploads/<?= htmlspecialchars($post['profilePicture'], ENT_QUOTES, 'UTF-8') ?>"
                alt="Author's profile picture"
                width="45"
                height="45"
                style="object-fit: cover; border-radius: 50%;"
            >

        <?php endif; ?>

        <strong>
            <?= htmlspecialchars($post['username'], ENT_QUOTES, 'UTF-8') ?>
        </strong>

    </a>

    <p>
        <small>
            <?= htmlspecialchars($post['createdAt'], ENT_QUOTES, 'UTF-8') ?>
        </small>
    </p>

    <?php if (!empty($post['image'])): ?>

        <img
            src="../uploads/posts/<?= htmlspecialchars($post['image'], ENT_QUOTES, 'UTF-8') ?>"
            alt="Post image"
            style="max-width: 100%;"
        >

    <?php endif; ?>

    <p style="white-space: pre-wrap;">
        <?= htmlspecialchars($post['content'], ENT_QUOTES, 'UTF-8') ?>
    </p>

    <form
    action="<?= $hasLiked ? 'unlikePost.php' : 'likePost.php' ?>"
    method="POST"
>
    <input
        type="hidden"
        name="csrf_token"
        value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>"
    >

    <input
        type="hidden"
        name="post_id"
        value="<?= (int) $post['id'] ?>"
    >

    <button type="submit">
        <?= $hasLiked ? '💔 Unlike' : '❤️ Like' ?>
        (<?= $likeCount ?>)
    </button>
    <input
        type="hidden"
        name="return_to"
        value="post"
    >
</form>   
    <p>💬 <?= $commentCount ?> comments</p>
    <?php
    $postOwnerId = (int) $post['user_id'];
    $deletePostAction = 'deletePost.php';

    include __DIR__ . '/../includes/components/deletePostButton.php';
?>


</div>

<br>

<h3>Comments 💬</h3>

<form action="createComment.php" method="POST">

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

    <input
        type="hidden"
        name="return_to"
        value="post"
    >

    <textarea
        name="content"
        placeholder="Write a comment..."
        maxlength="1000"
        required
    ></textarea>

    <button type="submit">Post Comment</button>

</form>

<?php if (empty($comments)): ?>

    <p>No comments yet. Be the first to comment!</p>

<?php else: ?>

    <?php foreach ($comments as $singleComment): ?>

        <div class="comment">

            <strong>
                <?= htmlspecialchars(
                    $singleComment['username'],
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>
            </strong>

            <p>
                <?= nl2br(htmlspecialchars(
                    $singleComment['content'],
                    ENT_QUOTES,
                    'UTF-8'
                )) ?>
            </p>

            <?php if (
                (int) $singleComment['user_id'] === $loggedInUserId
            ): ?>

                <form action="deleteComment.php" method="POST">

                    <input
                        type="hidden"
                        name="csrf_token"
                        value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>"
                    >

                    <input
                        type="hidden"
                        name="comment_id"
                        value="<?= (int) $singleComment['id'] ?>"
                    >

                    <input
                        type="hidden"
                        name="post_id"
                        value="<?= (int) $postId ?>"
                    >

                    <input
                        type="hidden"
                        name="return_to"
                        value="post"
                    >

                    <button type="submit">
                        Delete Comment
                    </button>

                </form>

            <?php endif; ?>

        </div>

    <?php endforeach; ?>

<?php endif; ?>

<a href="../userProfile/profile.php?id=<?= (int) $post['user_id'] ?>">
    Back to profile
</a>

<br>

<a href="../home.php">Back to feed</a>

