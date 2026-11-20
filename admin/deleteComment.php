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

if (!$commentId || $commentId < 1) {
    exit("Invalid comment ID.");
}

$success = $moderation->deleteComment($commentId);

if (!$success) {
    exit("Unable to delete the comment.");
}

header("Location: index.php?page=comments");
exit();
