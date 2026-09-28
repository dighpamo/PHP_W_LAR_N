<?php

/** @var array $user */ ?>

<?php require BASE_PATH . '/views/admin_partials/header.php'; ?>
<?php $toCreate = is_null($user); ?>


<main class="site-main">

    <div class="page-header">
        <h1 class="page-title">
            <?= $toCreate ? "Создание нового пользователя" : "Редактирование пользователя #" . htmlspecialchars($user['id']) ?>
        </h1>

        <?php if (!$toCreate): ?>
            <form class="inline-form" method="post" action="<?= '/admin/users/' . $user['id'] . '/delete' ?>">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                <button type="submit" class="btn btn--danger">Удалить</button>
            </form>
        <?php endif; ?>
    </div>

    <form class="form-card"
        method="post" action="<?= $toCreate ? '/admin/users' : '/admin/users/' . $user['id'] ?>" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

        <?php if (!$toCreate): ?>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-item__label">ID</div>
                    <div class="info-item__value"><?= htmlspecialchars($user['id']) ?></div>
                </div>
                <div class="info-item">
                    <div class="info-item__label">Создано</div>
                    <div class="info-item__value"><?= htmlspecialchars($user['created_at']) ?></div>
                </div>
            </div>
        <?php endif; ?>

        <div class="form-group">
            <label for="user-name">Имя пользователя</label>
            <input type="text"
                id="user-name"
                name="username"
                placeholder="Введите имя пользователя"
                required
                maxlength="255"
                value="<?= $toCreate ? '' : htmlspecialchars($user['username']) ?>">
        </div>
        <div class="form-group">
            <label for="user-name">Пароль пользователя</label>
            <input type="text"
                id="user-password"
                name="password"
                placeholder="Введите имя пользователя"
                required
                maxlength="255"
                value="<?= $toCreate ? '' : htmlspecialchars($user['password_hash']) ?>">
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn--primary">
                <?= $toCreate ? "Создать" : "Сохранить" ?>
            </button>
        </div>

    </form>
</main>

<?php require BASE_PATH . '/views/admin_partials/footer.php'; ?>