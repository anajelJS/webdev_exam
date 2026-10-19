
<?php
session_start();
require_once '../includes/connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST'
    || !isset($_POST['publishPost'])) {
    header("Location: ../home.php");
    exit();
}

$content = trim($_POST['content'] ?? '');
$user_id = $_SESSION['user_id'];

if ($content === '' || mb_strlen($content, 'UTF-8') > 2000) {
    header("Location: ../home.php?page=createPost&status=invalid");
    exit();
}

$createPost = $pdo->prepare(
    "INSERT INTO post (user_id, content) VALUES (?, ?)"
);

$createPost->execute([$user_id, $content]);

header("Location: ../home.php?status=posted");
exit();
