<?php

if (!defined('GROWUM_ADMIN')) {
    http_response_code(403);
    exit("Access denied.");
}

$userId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$userId || $userId < 1) {
    exit("Invalid user.");
}

$query = $pdo->prepare(
    "SELECT userAccount.id, userAccount.username,
            userAccount.isBanned, role.name AS role
     FROM userAccount
     INNER JOIN role ON userAccount.role_id = role.id
     WHERE userAccount.id = ?"
);

$query->execute([$userId]);

$targetUser = $query->fetch(PDO::FETCH_ASSOC);

if (!$targetUser || $targetUser['role'] === 'admin'
    || (int) $targetUser['isBanned'] === 1) {
    exit("This user cannot be banned.");
}
?>

<h2 id="banForm">Ban User</h2>

<p>
    You are about to ban
    <strong>
        <?= htmlspecialchars($targetUser['username'], ENT_QUOTES, 'UTF-8') ?>
    </strong>.
</p>

<form action="updateBanStatus.php" method="POST"
    onsubmit="return confirm('Are you sure you want to ban this user?');">

    <input type="hidden" name="csrf_token"
        value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">

    <input type="hidden" name="user_id"
        value="<?= (int) $targetUser['id'] ?>">

    <label for="banReason">Reason for banning:</label>

    <br>

    <textarea
        id="banReason"
        name="banReason"
        maxlength="500"
        placeholder="Enter a reason..."
        required
    ></textarea>

    <br>

    <button type="submit" name="action" value="ban">
        Confirm Ban
    </button>

    <a href="index.php?page=users">Cancel</a>

</form>
