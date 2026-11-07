<?php
session_start();

require_once "../includes/connection.php";
require_once "../classes/comment.php";
require_once "../classes/auth.php";

$auth = new Auth();

$auth->requireLogin();
$auth->requirePost();
$auth->validateCsrf();

$commentId = filter_input(INPUT_POST, 'comment_id', FILTER_VALIDATE_INT);


if ($commentId === false || $commentId === null || $commentId <= 0) {
    http_response_code(400);
    exit("Invalid comment ID.");
}

$userId = (int) $_SESSION['user_id'];

$comment = new Comment($pdo);
$deleted = $comment->deleteComment($commentId, $userId);

if (!$deleted) {
    http_response_code(404);
    exit("Comment not found or you do not have permission to delete it.");
}

header("Location: ../home.php");
exit();