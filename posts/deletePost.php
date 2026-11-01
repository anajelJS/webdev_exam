<?php
session_start();
require_once '../includes/connection.php';

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

$post_id = filter_input(INPUT_POST, 'post_id', FILTER_VALIDATE_INT);

if ($post_id === false || $post_id === null || $post_id <= 0) {
    http_response_code(400);
    exit("Invalid post ID.");
}

$user_id = $_SESSION['user_id'];
$getImage = $pdo->prepare(
    "SELECT image FROM post
     WHERE id = ? AND user_id = ?"
);

$getImage->execute([$post_id, $user_id]);

$post = $getImage->fetch(PDO::FETCH_ASSOC);


$deletePost = $pdo->prepare(
    "DELETE FROM post
     WHERE id = ? AND user_id = ?"
);

$deletePost->execute([$post_id, $user_id]);
if ($deletePost->rowCount() > 0 && $post && !empty($post['image'])) {
    $imagePath = __DIR__ . '/../uploads/posts/' . basename($post['image']);

    if (is_file($imagePath)) {
        unlink($imagePath);
    }
}
header("Location: ../home.php?status=deleted");
exit();