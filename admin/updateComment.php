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

$commentId = filter_input(
    INPUT_POST,
    'comment_id',
    FILTER_VALIDATE_INT
);

$content = $_POST['content'] ?? null;

if (!$commentId || $commentId < 1) {
    exit("Invalid comment ID.");
}

if (!is_string($content)) {
    exit("Invalid comment content.");
}

$content = trim($content);

if ($content === '' || mb_strlen($content) > 1000) {
    exit("Comment must contain between 1 and 1000 characters.");
}

$query = $pdo->prepare(
    "SELECT id FROM postComment WHERE id = ?"
);

$query->execute([$commentId]);

if (!$query->fetchColumn()) {
    exit("Comment not found.");
}

$success = $moderation->updateComment($commentId, $content);

if (!$success) {
    exit("Unable to update the comment.");
}

header("Location: index.php?page=comments");
exit();
