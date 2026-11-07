
<h2>Latest Posts 🌱</h2>

<?php if (empty($posts)): ?>

    <p>No posts yet. Be the first to share something!</p>

<?php else: ?>

    <?php foreach ($posts as $post): ?>

        <div style="padding: 15px; border: 1px solid #ccc; margin-bottom: 15px;">

            <?php if (!empty($post['profilePicture'])): ?>

                <img
                    src="uploads/<?= htmlspecialchars($post['profilePicture'], ENT_QUOTES, 'UTF-8') ?>"
                    alt="Author's profile picture"
                    width="45"
                    height="45"
                    style="object-fit: cover; border-radius: 50%;"
                >

            <?php endif; ?>

            <strong>
                <?= htmlspecialchars($post['username'], ENT_QUOTES, 'UTF-8') ?>
            </strong>

            <br>

            <small>
                <?= htmlspecialchars($post['createdAt'], ENT_QUOTES, 'UTF-8') ?>
            </small>

            <p style="white-space: pre-wrap;">
                <?= htmlspecialchars($post['content'], ENT_QUOTES, 'UTF-8') ?>
            </p>

            <?php if (!empty($post['image'])): ?>

                <img
                    src="uploads/posts/<?= htmlspecialchars($post['image'], ENT_QUOTES, 'UTF-8') ?>"
                    alt="Post image"
                    style="max-width: 100%; margin-top: 10px;"
                >
                
            <?php endif; ?>
            <?php
                $hasLiked = $like->hasLiked(
                    (int) $_SESSION['user_id'],
                    (int) $post['id']
                );

                $likeCount = (int) ($post['like_count'] ?? 0);
                $commentCount = (int) ($post['comment_count'] ?? 0);
            ?>

            <form
                action="<?= $hasLiked ? 'posts/unlikePost.php' : 'posts/likePost.php' ?>"
                method="POST"
            >
                <input
                    type="hidden"
                    name="csrf_token"
                    value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>"
                >

                <input
                    type="hidden"
                    name="post_id"
                    value="<?= (int) $post['id'] ?>"
                >

                <button type="submit">
                    <?= $hasLiked ? '💔 Unlike' : '❤️ Like' ?>
                    (<?= $likeCount ?>)
                </button>
            </form>
            <p>
                💬 <?= $commentCount ?> comments
            </p>
            <form action="posts/createComment.php" method="POST">

                <input
                    type="hidden"
                    name="csrf_token"
                    value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>"
                >

                <input
                    type="hidden"
                    name="post_id"
                    value="<?= (int) $post['id'] ?>"
                >

                <textarea
                    name="content"
                    placeholder="Write a comment..."
                    maxlength="1000"
                    required
                ></textarea>

                <button type="submit">Post Comment</button>

            </form>
            <?php
             $comments = $comment->getComments((int) $post['id']);
                 ?>

            <div class="comments">

                <?php foreach ($comments as $singleComment): ?>

                    <div class="comment">

                        <strong>
                            <?= htmlspecialchars($singleComment['username'], ENT_QUOTES, 'UTF-8') ?>
                        </strong>

                        <p>
                            <?= nl2br(htmlspecialchars($singleComment['content'], ENT_QUOTES, 'UTF-8')) ?>
                        </p>
                        <?php if ((int) $singleComment['user_id'] === (int) $_SESSION['user_id']): ?>

                        <form action="posts/deleteComment.php" method="POST">

                            <input
                                type="hidden"
                                name="csrf_token"
                                value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>"
                            >

                            <input
                                type="hidden"
                                name="comment_id"
                                value="<?= (int) $singleComment['id'] ?>"
                            >

                            <button type="submit">Delete Comment</button>

                        </form>

                        <?php endif; ?>
                    </div>

                <?php endforeach; ?>

            </div>
                <?php if ((int)$post['user_id'] === (int)$_SESSION['user_id']): ?>

                <form action="posts/deletePost.php" method="POST">
                    <input
                        type="hidden"
                        name="csrf_token"
                        value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>"
                    >
                    <input
                        type="hidden"
                        name="post_id"
                        value="<?= (int)$post['id'] ?>"
                    >

                    <button type="submit" name="deletePost">
                        Delete Post
                    </button>
                </form>

    <?php endif; ?>

        </div>

    <?php endforeach; ?>

<?php endif; ?>
