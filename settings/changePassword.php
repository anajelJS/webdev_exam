<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>


<h2>Change password</h2>

<form action="updatePassword.php" method="POST">

    <input
        type="password"
        name="currentPassword"
        placeholder="Current password"
        required
    >
    
    <input
        type="password"
        name="newPassword"
        placeholder="New password"
        required
    >
    <input
        type="password"
        name="confirmPassword"
        placeholder="Confirm new password"
        required
    >

    <input
    type="hidden"
    name="csrf_token"
    value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>"
    >

    <button type="submit" name="changePassword">
        Change password
    </button>

</form>

<a href="settings.php">Back</a>