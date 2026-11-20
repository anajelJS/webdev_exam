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

public function deletePost(int $postId): bool
{
    $query = $this->pdo->prepare(
        "SELECT image FROM post WHERE id = ?"
    );

    $query->execute([$postId]);

    $post = $query->fetch(PDO::FETCH_ASSOC);

    if (!$post) {
        return false;
    }

    $query = $this->pdo->prepare(
        "DELETE FROM post WHERE id = ?"
    );

    $query->execute([$postId]);

    if ($query->rowCount() === 0) {
        return false;
    }

    if (!empty($post['image'])) {

        $imagePath = __DIR__ . '/../uploads/posts/' 
            . basename($post['image']);

        if (is_file($imagePath)) {
            unlink($imagePath);
        }
    }

    return true;
}
public function updatePost(int $postId, string $content): bool
{
    $query = $this->pdo->prepare(
        "UPDATE post
         SET content = ?
         WHERE id = ?"
    );

    return $query->execute([$content, $postId]);
}
public function deleteComment(int $commentId): bool
{
    $query = $this->pdo->prepare(
        "DELETE FROM postComment WHERE id = ?"
    );

    $query->execute([$commentId]);

    return $query->rowCount() > 0;
}
public function updateComment(int $commentId, string $content): bool
{
    $query = $this->pdo->prepare(
        "UPDATE postComment
         SET content = ?
         WHERE id = ?"
    );

    return $query->execute([$content, $commentId]);
}
}
