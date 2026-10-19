
<?php
session_start();
require_once '../includes/connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['uploadPicture'])) {
    header("Location: editProfile.php?status=error");
    exit();
}

$user_id = $_SESSION['user_id'];

if (!isset($_FILES['profilePicture'])) {
    header("Location: editProfile.php?status=noFile");
    exit();
}

$picture = $_FILES['profilePicture'];

if ($picture['error'] !== UPLOAD_ERR_OK) {
    header("Location: editProfile.php?status=uploadError");
    exit();
}

$maxSize = 2 * 1024 * 1024;

if ($picture['size'] > $maxSize) {
    header("Location: editProfile.php?status=tooLarge");
    exit();
}

$finfo = new finfo(FILEINFO_MIME_TYPE);
$fileType = $finfo->file($picture['tmp_name']);

$allowedTypes = [
    'image/jpeg',
    'image/png',
    'image/webp'
];

if (!in_array($fileType, $allowedTypes, true)) {
    header("Location: editProfile.php?status=invalidType");
    exit();
}

$extension = match ($fileType) {
    'image/jpeg' => 'jpg',
    'image/png' => 'png',
    'image/webp' => 'webp'
};

$fileName = bin2hex(random_bytes(16)) . '.' . $extension;

$getOldPicture = $pdo->prepare(
    "SELECT profilePicture FROM userAccount WHERE id = ?"
);

$getOldPicture->execute([$user_id]);
$oldPicture = $getOldPicture->fetchColumn();

$uploadPath = __DIR__ . '/../uploads/' . $fileName;

if (!move_uploaded_file($picture['tmp_name'], $uploadPath)) {
    header("Location: editProfile.php?status=saveError");
    exit();
}

try {
    $updatePicture = $pdo->prepare(
        "UPDATE userAccount SET profilePicture = ? WHERE id = ?"
    );

    $updatePicture->execute([$fileName, $user_id]);

} catch (PDOException $e) {
    if (is_file($uploadPath)) {
        unlink($uploadPath);
    }

    throw $e;
}


if (!empty($oldPicture)) {

    $oldPicturePath = __DIR__ . '/../uploads/' . basename($oldPicture);

    if (is_file($oldPicturePath) && $oldPicturePath !== $uploadPath) {
        unlink($oldPicturePath);
    }
}

header("Location: profile.php?status=pictureUpdated");
exit();
