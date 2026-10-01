<?php

/** @var array $category */
/** @var array $articles */ ?>


<?php require BASE_PATH . '/views/partials/header.php'; ?>

<main class="site-main">

    <article class="article-page">

        <header class="article-page__header">
            <h1 class="article-page__title"><?= htmlspecialchars($category['category_name']) ?></h1>
        </header>


        <div class="article-page__content">
            <?php foreach ($articles as $article): ?>
                <a href='/artucles/' . <?= $article['slug'] ?>>
                    <?= htmlspecialchars($article['title']) ?>
                </a>
            <?php endforeach; ?>
        </div>

    </article>

</main>

<?php require BASE_PATH . '/views/partials/footer.php'; ?>