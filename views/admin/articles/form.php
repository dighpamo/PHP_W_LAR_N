<?php

/** @var array $article */ ?>

<?php require BASE_PATH . '/views/admin_partials/header.php'; ?>
<?php $toCreate = is_null($article); ?>


<main class="site-main">

    <div class="page-header">
        <h1 class="page-title">
            <?= $toCreate ? "Создание новой статьи" : "Редактирование статьи #" . htmlspecialchars($article['id']) ?>
        </h1>

        <?php if (!$toCreate): ?>
            <form class="inline-form" method="post" action="<?= '/admin/articles/' . $article['id'] . '/delete' ?>">
                <button type="submit" class="btn btn--danger">Удалить</button>
            </form>
        <?php endif; ?>
    </div>

    <form class="form-card"
        method="post"
        action="<?= $toCreate ? '/admin/articles' : '/admin/articles/' . $article['id'] ?>"
        enctype="multipart/form-data">

        <?php if (!$toCreate): ?>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-item__label">ID</div>
                    <div class="info-item__value"><?= htmlspecialchars($article['id']) ?></div>
                </div>
                <div class="info-item">
                    <div class="info-item__label">Slug</div>
                    <div class="info-item__value"><?= htmlspecialchars($article['slug']) ?></div>
                </div>
                <div class="info-item">
                    <div class="info-item__label">Просмотров</div>
                    <div class="info-item__value"><?= htmlspecialchars($article['views_count']) ?></div>
                </div>
                <div class="info-item">
                    <div class="info-item__label">Опубликовано</div>
                    <div class="info-item__value"><?= htmlspecialchars($article['published_at']) ?></div>
                </div>
                <div class="info-item">
                    <div class="info-item__label">Создано</div>
                    <div class="info-item__value"><?= htmlspecialchars($article['created_at']) ?></div>
                </div>
            </div>
        <?php endif; ?>

        <div class="form-group">
            <label for="article-name">Название статьи</label>
            <input type="text"
                id="article-name"
                name="title"
                placeholder="Введите название статьи"
                required
                maxlength="255"
                value="<?= $toCreate ? '' : htmlspecialchars($article['title']) ?>">
        </div>

        <div class="form-group">
            <label for="article-content">Содержимое статьи</label>
            <textarea id="article-content"
                name="content"
                placeholder="Введите текст статьи"
                required><?= $toCreate ? '' : htmlspecialchars($article['content']) ?></textarea>
        </div>

        <div class="form-group">
            <label for="article-image">Изображение статьи</label>
            <?php if (!$toCreate && $article['image_path']): ?>
                <div class="current-image">
                    <img src="<?= htmlspecialchars($article['image_path']) ?>"
                        alt="Текущее изображение">
                </div>
            <?php endif; ?>
            <input type="file"
                id="article-image"
                name="image_path"
                accept="image/jpeg,image/png,image/gif,image/webp">
            <small class="form-hint">JPG, PNG, GIF или WebP, до 5 МБ</small>
        </div>

        <div class="form-group">
            <label for="article-category_id">Категория</label>
            <input type="text"
                id="article-category_id"
                name="category_id"
                placeholder="ID категории"
                value="<?= $toCreate ? '' : htmlspecialchars($article['category_id']) ?>">
            <small class="form-hint">Оставьте пустым, если категория не нужна</small>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn--primary">
                <?= $toCreate ? "Создать" : "Сохранить" ?>
            </button>
        </div>

    </form>
</main>

<?php require BASE_PATH . '/views/admin_partials/footer.php'; ?>