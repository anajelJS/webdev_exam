<?php
session_start();
require_once '../includes/connection.php';
require_once "../classes/auth.php";

$auth = new Auth();

$auth->requireLogin();
$auth->requirePost();
$auth->validateCsrf();

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