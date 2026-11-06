<?php
session_start();

require_once "../includes/connection.php";
require_once "../classes/comment.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../home.php");
    exit();
}

if (
    !isset($_POST['csrf_token'], $_SESSION['csrf_token']) ||
    !is_string($_POST['csrf_token']) ||
    !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
) {
    http_response_code(403);
    exit("Invalid CSRF token.");
}

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