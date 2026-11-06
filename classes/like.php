<?php

class Like
{
    private PDO $database;

    public function __construct(PDO $database)
    {
        $this->database = $database;
    }

    public function likePost(int $userId, int $postId): void
    {
        $query = $this->database->prepare(
            "INSERT INTO postLike (user_id, post_id)
             VALUES (?, ?)
             ON DUPLICATE KEY UPDATE id = id"
        );

        $query->execute([$userId, $postId]);
    }

    public function unlikePost(int $userId, int $postId): void
    {
    $query = $this->database->prepare(
        "DELETE FROM postLike
         WHERE user_id = ? AND post_id = ?"
    );

    $query->execute([$userId, $postId]);
}
public function hasLiked(int $userId, int $postId): bool
{
    $query = $this->database->prepare(
        "SELECT 1 FROM postLike
         WHERE user_id = ? AND post_id = ?"
    );

    $query->execute([$userId, $postId]);

    return (bool) $query->fetchColumn();
}
public function countLikes(int $postId): int
{
    $query = $this->database->prepare(
        "SELECT COUNT(*) FROM postLike WHERE post_id = ?"
    );

    $query->execute([$postId]);

    return (int) $query->fetchColumn();
}
}

