<?php

/** @var array $articles */
/** @var int $totalPages */
/** @var int $currentPage */ ?>

<?php require BASE_PATH . '/views/partials/header.php'; ?>

<main class="site-main">
    <h1 class="page-title">Статьи</h1>

    <?php if ($articles): ?>
        <div class="article-list">
            <?php foreach ($articles as $article): ?>
                <article class="article-card">
                    <div class="article-card__image">
                        <?php if ($article['image_path']): ?>
                            <img src="<?= htmlspecialchars($article['image_path']) ?>" alt="<?= htmlspecialchars($article['title']) ?>">
                        <?php else: ?>
                            <img src="/uploads/House.png" alt="<?= htmlspecialchars($article['title']) ?>">
                        <?php endif; ?>
                    </div>
                    <h2 class="article-card__title">
                        <a href="/articles/<?= htmlspecialchars($article['slug']) ?>">
                            <?= htmlspecialchars($article['title']) ?>
                        </a>
                    </h2>
                    <p class="article-card__preview"><?= htmlspecialchars($article['preview']) ?></p>
                    <div class="article-card__meta">
                        <span>Просмотров: <?= htmlspecialchars($article['views_count']) ?></span>
                        <a href="/categories/<?= htmlspecialchars($article['category_slug']) ?>">Категория: <?= htmlspecialchars($article['category_name'] ?? 'Без категории') ?></a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-state">Тут пока ничего нет</div>
    <?php endif; ?>
    <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <?php if ($i == $currentPage): ?>
                    <!-- Текущая активная страница (без ссылки) -->
                    <span class="page-item active"><?= $i ?></span>
                <?php else: ?>
                    <!-- Ссылка на другую страницу -->
                    <a class="page-item" href="?page=<?= $i ?>"><?= $i ?></a>
                <?php endif; ?>
            <?php endfor; ?>
        </div>
    <?php endif; ?>

</main>

<?php require BASE_PATH . '/views/partials/footer.php'; ?>