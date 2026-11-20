<?php

session_start();

require_once '../includes/connection.php';
require_once '../classes/auth.php';
require_once '../classes/moderation.php';

$auth = new Auth();
$moderation = new Moderation($pdo);

$auth->requireAdmin($pdo);
$auth->requirePost();
$auth->validateCsrf();

$postId = filter_input(INPUT_POST, 'post_id', FILTER_VALIDATE_INT);

if (!$postId || $postId < 1) {
    exit("Invalid post ID.");
}

$success = $moderation->deletePost($postId);

if (!$success) {
    exit("Unable to delete this post.");
}

header("Location: index.php?page=posts");
exit();
