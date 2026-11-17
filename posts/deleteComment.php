<?php
session_start();

require_once "../includes/connection.php";
require_once "../classes/comment.php";
require_once "../classes/auth.php";

$auth = new Auth();

$auth->requireLogin($pdo);
$auth->requirePost();
$auth->validateCsrf();

$commentId = filter_input(
    INPUT_POST,
    'comment_id',
    FILTER_VALIDATE_INT,
    ['options' => ['min_range' => 1]]
);

if (!$commentId) {
    http_response_code(400);
    exit("Invalid comment ID.");
}

$postId = filter_input(
    INPUT_POST,
    'post_id',
    FILTER_VALIDATE_INT,
    ['options' => ['min_range' => 1]]
);

if (!$postId) {
    http_response_code(400);
    exit("Invalid post ID.");
}

$userId = (int) $_SESSION['user_id'];

$comment = new Comment($pdo);
$deleted = $comment->deleteComment($commentId, $userId);

if (!$deleted) {
    http_response_code(404);
    exit("Comment not found or you do not have permission to delete it.");
}

if (($_POST['return_to'] ?? '') === 'post') {
    header("Location: viewPost.php?id=" . $postId);
} else {
    header("Location: ../home.php#post-" . $postId);
}

exit();
