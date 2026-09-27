<?php

/** @var array $article */ ?>

<?php require BASE_PATH . '/views/partials/header.php'; ?>

<main class="site-main">

    <article class="article-page">

        <header class="article-page__header">
            <h1 class="article-page__title"><?= htmlspecialchars($article['title']) ?></h1>

            <div class="article-page__meta">
                <span class="article-page__meta-item">
                    Опубликовано: <?= htmlspecialchars($article['published_at']) ?>
                </span>
                <span class="article-page__meta-item">
                    Просмотров: <?= htmlspecialchars($article['views_count']) ?>
                </span>
                <?php if ($article['category_name']): ?>
                    <a class="article-page__category"
                        href="/categories/<?= htmlspecialchars($article['category_slug']) ?>">
                        <?= htmlspecialchars($article['category_name']) ?>
                    </a>
                <?php else: ?>
                    <span class="article-page__meta-item">Без категории</span>
                <?php endif; ?>
            </div>
        </header>

        <div class="article-page__image">
            <?php if ($article['image_path']): ?>
                <img src="<?= htmlspecialchars($article['image_path']) ?>" alt="<?= htmlspecialchars($article['title']) ?>">
            <?php else: ?>
                <img src="/uploads/House.png" alt="<?= htmlspecialchars($article['title']) ?>">
            <?php endif; ?>
        </div>

        <div class="article-page__content">
            <?= htmlspecialchars($article['content']) ?>
        </div>

    </article>

</main>

<?php require BASE_PATH . '/views/partials/footer.php'; ?>