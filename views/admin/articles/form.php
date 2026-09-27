<?php

/** @var array $article */ ?>

<?php require BASE_PATH . '/views/admin_partials/header.php'; ?>
<?php $toCreate = is_null($article); ?>


<main class="site-main">
    <h1 class="page-title"><?= $toCreate ? "Создание новой статьи" : "Статья" . htmlspecialchars($article['id']) ?></h1>
    <?php if (!$toCreate): ?>
        <form class="article-block" method="post" action="<?= '/admin/articles/' . $article['id'] . '/delete' ?>">
            <button type="submit">Удалить</button>
        </form>
    <?php endif; ?>

    <form class="article-block" method="post" action="<?= $toCreate ? '/admin/articles' : '/admin/articles/' . $article['id'] ?>" enctype="multipart/form-data">
        <?php if (!$toCreate): ?>
            <div>
                <p>ID:</p>
                <p><?= htmlspecialchars($article['id']); ?></p>
            </div>
        <?php endif; ?>
        <div>
            <label>Название статьи:</label>
            <input id="article-name" name="title" placeholder="Введите название статьи" type="text" required maxlength="255" value=<?= $toCreate ? '' : htmlspecialchars($article['title']) ?>>
        </div>
        <?php if (!$toCreate): ?>
            <div>
                <p>SLUG:</p>
                <p><?= htmlspecialchars($article['slug']); ?></p>
            </div>
        <?php endif; ?>
        <div>
            <label>Содержимое статьи:</label>
            <textarea id="article-content" name="content" placeholder="Введите текст статьи" required><?= $toCreate ? '' : htmlspecialchars($article['content']) ?></textarea>
        </div>
        <div>
            <label for="article-image">Изображение статьи:</label>
            <?php if (!$toCreate && $article['image_path']): ?>
                <div>
                    <img src="<?= htmlspecialchars($article['image_path']) ?>" alt="Текущее изображение" width="200">
                </div>
            <?php endif; ?>
            <input type="file" id="article-image" name="image_path" accept="image/jpeg,image/png,image/gif,image/webp">
            <small>JPG, PNG, GIF или WebP, до 5 МБ</small>
        </div>
        <div>
            <label>Категория для статьи:</label>
            <input id="article-category_id" name="category_id" placeholder="Введите название категории" value=<?= $toCreate ? '' : htmlspecialchars($article['category_id']) ?>>
        </div>
        <?php if (!$toCreate): ?>
            <div>
                <p>Количество просмотров:</p>
                <p><?= htmlspecialchars($article['views_count']); ?></p>
            </div>
        <?php endif; ?>
        <?php if (!$toCreate): ?>
            <div>
                <p>Дата публикации:</p>
                <p><?= htmlspecialchars($article['published_at']); ?></p>
            </div>
        <?php endif; ?>
        <?php if (!$toCreate): ?>
            <div>
                <p>Дата создания:</p>
                <p><?= htmlspecialchars($article['created_at']); ?></p>
            </div>
        <?php endif; ?>
        <button type="submit"><?= $toCreate ? "Создать" : "Изменить" ?></button>
    </form>
</main>

<?php require BASE_PATH . '/views/admin_partials/footer.php'; ?>