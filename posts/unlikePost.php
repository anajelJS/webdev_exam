<?php
session_start();

require_once "../includes/connection.php";
require_once "../classes/like.php";

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

$userId = $_SESSION['user_id'];

$like = new Like($pdo);
$like->unlikePost($userId, $postId);

header("Location: ../home.php");
exit();