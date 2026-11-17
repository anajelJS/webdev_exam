<?php

if (!defined('GROWUM_ADMIN')) {
    http_response_code(403);
    exit("Access denied.");
}

$query = $pdo->prepare(
    "SELECT
        userAccount.id,
        userAccount.username,
        userAccount.email,
        userAccount.createdAt,
        userAccount.isBanned,
        userAccount.banReason,
        role.name AS role
     FROM userAccount
     INNER JOIN role
         ON userAccount.role_id = role.id
     ORDER BY userAccount.createdAt DESC, userAccount.id DESC"
);

$query->execute();

$users = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Manage Users 👥</h2>

<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Email</th>
        <th>Role</th>
        <th>Status</th>
        <th>Registered</th>
        <th>Actions</th>
    </tr>

    <?php foreach ($users as $user): ?>
        <tr>
            <td><?= (int) $user['id'] ?></td>

            <td>
                <?= htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8') ?>
            </td>

            <td>
                <?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') ?>
            </td>

            <td>
                <?= htmlspecialchars($user['role'], ENT_QUOTES, 'UTF-8') ?>
            </td>

            <td>
                <?php if ((int) $user['isBanned'] === 1): ?>
                    🔴 Banned
                <?php else: ?>
                    🟢 Active
                <?php endif; ?>
            </td>

            <td>
                <?= htmlspecialchars($user['createdAt'], ENT_QUOTES, 'UTF-8') ?>
            </td>
                    
<td>
    <?php if ($user['role'] === 'admin'): ?>

        Protected

    <?php else: ?>

        <form action="updateBanStatus.php" method="POST">

            <input type="hidden" name="csrf_token"
                value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">

            <input type="hidden" name="user_id"
                value="<?= (int) $user['id'] ?>">

           
    <?php if ((int) $user['isBanned'] === 1): ?>

    <p>
        <strong>Ban reason:</strong>
        <?= htmlspecialchars($user['banReason'] ?? '', ENT_QUOTES, 'UTF-8') ?>
    </p>

    <button type="submit" name="action" value="unban"
        onclick="return confirm('Are you sure you want to unban this user?');">
        Unban
    </button>

            <?php else: ?>

                <a href="index.php?page=banUser&id=<?= (int) $user['id'] ?>#banForm">
                    Ban
                </a>

        <?php endif; ?>


        </form>

    <?php endif; ?>
</td>

        </tr>
    <?php endforeach; ?>
</table>
