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

$userId = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
$action = $_POST['action'] ?? '';

if (!$userId || $userId < 1) {
    exit("Invalid user ID.");
}

if ($userId === (int) $_SESSION['user_id']) {
    exit("You cannot change your own ban status.");
}

switch ($action) {

    case 'ban':

        $reason = $_POST['banReason'] ?? '';

        if (!is_string($reason)) {
            exit("Invalid ban reason.");
        }

        $reason = trim($reason);

        if ($reason === '' || mb_strlen($reason) > 500) {
            exit("Ban reason must contain between 1 and 500 characters.");
        }

        $success = $moderation->banUser($userId, $reason);

        break;

    case 'unban':

        $success = $moderation->unbanUser($userId);

        break;

    default:
        exit("Invalid action.");
}

if (!$success) {
    exit("Unable to update the user's ban status.");
}

header("Location: index.php?page=users");
exit();
