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
$content = $_POST['content'] ?? null;

if (!$postId || $postId < 1) {
    exit("Invalid post ID.");
}

if (!is_string($content)) {
    exit("Invalid post content.");
}

$content = trim($content);

$query = $pdo->prepare(
    "SELECT image FROM post WHERE id = ?"
);

$query->execute([$postId]);

$post = $query->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    exit("Post not found.");
}

if ($content === '' && empty($post['image'])) {
    exit("A post must contain text or an image.");
}

$success = $moderation->updatePost($postId, $content);

if (!$success) {
    exit("Unable to update the post.");
}

header("Location: index.php?page=posts");
exit();
