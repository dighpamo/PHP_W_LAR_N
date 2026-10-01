<?php

/** @var array $categories */ ?>

<?php require BASE_PATH . '/views/partials/header.php'; ?>

<main class="site-main">
    <h1 class="page-title">Статьи</h1>

    <?php if ($categories): ?>
        <div class="article-list">
            <?php foreach ($categories as $category): ?>
                <article class="article-card">
                    <h2 class="article-card__title">
                        <a href="/categories/<?= htmlspecialchars($category['slug']) ?>">
                            <?= htmlspecialchars($category['category_name']) ?>
                        </a>
                    </h2>
                </article>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-state">Тут пока ничего нет</div>
    <?php endif; ?>
    <?php require BASE_PATH . '/views/partials/pagination.php'; ?>

</main>

<?php require BASE_PATH . '/views/partials/footer.php'; ?>