<?php

/** @var array $category */ ?>

<?php require BASE_PATH . '/views/admin_partials/header.php'; ?>
<?php $toCreate = is_null($category); ?>


<main class="site-main">

    <div class="page-header">
        <h1 class="page-title">
            <?= $toCreate ? "Создание новой категории" : "Редактирование категории #" . htmlspecialchars($category['id']) ?>
        </h1>

        <?php if (!$toCreate): ?>
            <form class="inline-form" method="post" action="<?= '/admin/categories/' . $category['id'] . '/delete' ?>">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                <button type="submit" class="btn btn--danger">Удалить</button>
            </form>
        <?php endif; ?>
    </div>

    <form class="form-card"
        method="post" action="<?= $toCreate ? '/admin/categories' : '/admin/categories/' . $category['id'] ?>">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

        <?php if (!$toCreate): ?>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-item__label">ID</div>
                    <div class="info-item__value"><?= htmlspecialchars($category['id']) ?></div>
                </div>
                <div class="info-item">
                    <div class="info-item__label">Slug</div>
                    <div class="info-item__value"><?= htmlspecialchars($category['slug']) ?></div>
                </div>
            </div>
        <?php endif; ?>

        <div class="form-group">
            <label for="category-category_name">Название категории</label>
            <input type="text"
                id="category-category_name"
                name="category_name"
                placeholder="Введите название категории"
                required
                maxlength="255"
                value="<?= $toCreate ? '' : htmlspecialchars($category['category_name']) ?>">
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn--primary">
                <?= $toCreate ? "Создать" : "Сохранить" ?>
            </button>
        </div>

    </form>
</main>

<?php require BASE_PATH . '/views/admin_partials/footer.php'; ?>