
<?php
if (!defined('GROWUM_ADMIN')) {
    http_response_code(403);
    exit("Access denied.");
}
$userCount = (int) $pdo
    ->query("SELECT COUNT(*) FROM userAccount")
    ->fetchColumn();

$postCount = (int) $pdo
    ->query("SELECT COUNT(*) FROM post")
    ->fetchColumn();

$commentCount = (int) $pdo
    ->query("SELECT COUNT(*) FROM postComment")
    ->fetchColumn();
?>

<h2>Dashboard Overview 🌱</h2>

<p>👥 Total users: <?= $userCount ?></p>
<p>📸 Total posts: <?= $postCount ?></p>
<p>💬 Total comments: <?= $commentCount ?></p>


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
        <th>Registered</th>
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
                <?= htmlspecialchars($user['createdAt'], ENT_QUOTES, 'UTF-8') ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
