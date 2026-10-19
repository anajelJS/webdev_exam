<?php
session_start();
require_once '../includes/connection.php';
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}
?>

<h2>Edit your profile</h2>

<h3>Change profile picture</h3>

<form action="updatePicture.php" method="POST" enctype="multipart/form-data"> <!-- IMPORTANT: enctypetells the browser to use the correct format for sending files.-->

    <label for="profilePicture">Choose a profile picture:</label>

    <input
        type="file"
        name="profilePicture"
        id="profilePicture"
        accept="image/jpeg,image/png,image/webp"
        required
    >

    <button type="submit" name="uploadPicture">
        Upload picture
    </button>

</form>

<form action="updateBio.php" method="POST">

    <label for="bio">Your bio:</label>
    <br>

    <textarea
        name="bio"
        id="bio"
        maxlength="500"
        placeholder="Tell us a little about yourself..."
    ></textarea>

    <br>

    <button type="submit" name="updateBio">
        Save bio
    </button>

</form>

<a href="profile.php">Cancel</a>