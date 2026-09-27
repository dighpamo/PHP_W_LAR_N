<?php

/** @var array $users */ ?>

<?php require BASE_PATH . '/views/admin_partials/header.php'; ?>

<main class="site-main">

    <div class="page-header">
        <h1 class="page-title">Статьи</h1>
        <a href="/admin/articles/create" class="btn btn--primary">Создать новую статью</a>
    </div>

    <?php if ($users): ?>
        <div class="article-list">
            <?php foreach ($users as $user): ?>
                <article class="article-card">
                    <h2 class="article-card__title">
                        <a href="/admin/articles/<?= $user['id'] ?>">
                            <?= htmlspecialchars($user['title']) ?>
                        </a>
                    </h2>
                    <div class="article-card__meta">
                        <span>Просмотров: <?= htmlspecialchars($user['views_count']) ?></span>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-state">Тут пока ничего нет</div>
    <?php endif; ?>

</main>

<?php require BASE_PATH . '/views/admin_partials/footer.php'; ?>