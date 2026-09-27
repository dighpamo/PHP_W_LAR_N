<?php

/** @var array $articles */ ?>

<?php require BASE_PATH . '/views/admin_partials/header.php'; ?>

<main class="site-main">
    <h1 class="page-title">Статьи</h1>
    <a href="/admin/articles/create">Создать новую статью</a>

    <?php if ($articles): ?>
        <div class="article-list">
            <?php foreach ($articles as $article): ?>
                <article class="article-card">
                    <h2 class="article-card__title">
                        <a href="/admin/articles/<?= $article['id'] ?>"> <!-- htmlspecialchars($article['id']) - спросить Никиту, если не успею сам посмотреть -->

                            <?= htmlspecialchars($article['title']) ?>
                        </a>
                    </h2>
                    <div class="article-card__meta">
                        <span>Просмотров: <?= htmlspecialchars($article['views_count']) ?></span>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-state">Тут пока ничего нет</div>
    <?php endif; ?>
</main>

<?php require BASE_PATH . '/views/admin_partials/footer.php'; ?>