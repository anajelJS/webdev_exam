
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


    <button type="submit" name="changePassword">
        Change password
    </button>

</form>

<a href="settings.php">Back</a>