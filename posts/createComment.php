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

$postId = filter_input(INPUT_POST, 'post_id', FILTER_VALIDATE_INT);

if ($postId === false || $postId === null || $postId <= 0) {
    http_response_code(400);
    exit("Invalid post ID.");
}

$content = $_POST['content'] ?? '';

if (!is_string($content)) {
    http_response_code(400);
    exit("Invalid comment.");
}

$content = trim($content);

if ($content === '' || mb_strlen($content, 'UTF-8') > 1000) {
    http_response_code(400);
    exit("Comment must contain between 1 and 1000 characters.");
}

$userId = (int) $_SESSION['user_id'];

$comment = new Comment($pdo);
$comment->addComment($userId, $postId, $content);

header("Location: ../home.php");
exit();