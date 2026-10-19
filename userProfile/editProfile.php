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