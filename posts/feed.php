
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

        </div>

    <?php endforeach; ?>

<?php endif; ?>
