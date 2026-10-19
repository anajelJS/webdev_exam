<h2>Delete account</h2>
<p>
    Are you sure you want to delete your account?
    This action is permanent and cannot be undone.
    Your account information will be permanently deleted.
</p>

<form action="updateDeleteAccount.php" method="POST">

<label for="confirmDelete">
        <input
            type="checkbox"
            id="confirmDelete"
            name="confirmDelete"
            required
        >
        I understand that my account will be permanently deleted.
    </label>
    <br>
    <button type="submit" name="deleteAccount">
        Permanently delete my account
    </button>
    <button type="button" onclick="window.location.href='settings.php'">
        Back
    </button>
</form>
<br>