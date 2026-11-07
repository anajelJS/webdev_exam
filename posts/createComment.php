<?php
session_start();

require_once "../includes/connection.php";
require_once "../classes/comment.php";
require_once "../classes/auth.php";

$auth = new Auth();

$auth->requireLogin();
$auth->requirePost();
$auth->validateCsrf();

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