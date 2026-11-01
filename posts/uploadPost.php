
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
$image = $_FILES['image'] ?? null;
$hasImage = $image !== null
    && $image['error'] !== UPLOAD_ERR_NO_FILE;

    if (($content === '' && !$hasImage) || mb_strlen($content, 'UTF-8') > 2000) {
    header("Location: ../home.php?page=createPost&status=invalid");
    exit();
}

if ($hasImage) {

    if ($image['error'] !== UPLOAD_ERR_OK) {
        exit("Image upload failed.");
    }

    if ($image['size'] > 2 * 1024 * 1024) {
        exit("Image must be smaller than 2 MB.");
    }
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $fileType = $finfo->file($image['tmp_name']);

    $allowedTypes = [
        'image/jpeg',
        'image/png',
        'image/webp'
    ];

    if (!in_array($fileType, $allowedTypes, true)) {
        exit("Only JPG, PNG, and WebP images are allowed.");
    }
}

$imageName = null;

if ($hasImage) {

    $extensions = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp'
    ];

    $extension = $extensions[$fileType];

    $imageName = bin2hex(random_bytes(16)) . '.' . $extension;

    $uploadDirectory = __DIR__ . '/../uploads/posts/';

    if (!is_dir($uploadDirectory)) {
        mkdir($uploadDirectory, 0755, true);
    }

    $destination = $uploadDirectory . $imageName;

    if (!move_uploaded_file($image['tmp_name'], $destination)) {
        exit("Failed to save image.");
    }
}



try {
    $createPost = $pdo->prepare(
        "INSERT INTO post (user_id, content, image) VALUES (?, ?, ?)"
    );

    $createPost->execute([$user_id, $content, $imageName]);

} catch (PDOException $e) {

 
    if ($imageName !== null && is_file($destination)) {
        unlink($destination);
    }

    throw $e;
}

header("Location: ../home.php?page=createPost&status=success");
exit();
