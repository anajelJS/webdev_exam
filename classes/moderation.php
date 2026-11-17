<?php

class Moderation
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function banUser(int $userId, string $reason): bool
    {
        $query = $this->pdo->prepare(
            "UPDATE userAccount
             INNER JOIN role ON userAccount.role_id = role.id
             SET userAccount.isBanned = 1,
                 userAccount.banReason = ?
             WHERE userAccount.id = ?
             AND role.name != 'admin'
             AND userAccount.isBanned = 0"
        );

        $query->execute([$reason, $userId]);

        return $query->rowCount() > 0;
    }

    public function unbanUser(int $userId): bool
    {
        $query = $this->pdo->prepare(
            "UPDATE userAccount
             INNER JOIN role ON userAccount.role_id = role.id
             SET userAccount.isBanned = 0,
                 userAccount.banReason = NULL
             WHERE userAccount.id = ?
             AND role.name != 'admin'
             AND userAccount.isBanned = 1"
        );

        $query->execute([$userId]);

        return $query->rowCount() > 0;
    }
}
