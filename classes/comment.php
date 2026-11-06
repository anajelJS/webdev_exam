<?php

class Comment
{
    private PDO $database;

    public function __construct(PDO $database)
    {
        $this->database = $database;
    }
    public function addComment(int $userId, int $postId, string $content): void
{
    $query = $this->database->prepare(
        "INSERT INTO postComment (user_id, post_id, content)
         VALUES (?, ?, ?)"
    );

    $query->execute([$userId, $postId, $content]);
}
public function getComments(int $postId): array
{
    $query = $this->database->prepare(
        "SELECT postComment.id,
                postComment.user_id,
                postComment.content,
                postComment.createdAt,
                userAccount.username,
                userAccount.profilePicture
         FROM postComment
         INNER JOIN userAccount
             ON postComment.user_id = userAccount.id
         WHERE postComment.post_id = ?
           AND postComment.parent_comment_id IS NULL
         ORDER BY postComment.createdAt ASC, postComment.id ASC"
    );

    $query->execute([$postId]);

    return $query->fetchAll(PDO::FETCH_ASSOC);
}
public function deleteComment(int $commentId, int $userId): bool
{
    $query = $this->database->prepare(
        "DELETE FROM postComment
         WHERE id = ? AND user_id = ?"
    );

    $query->execute([$commentId, $userId]);

    return $query->rowCount() > 0;
}
}