<?php

/** @var array $users */ ?>

<?php require BASE_PATH . '/views/admin_partials/header.php'; ?>

<main class="site-main">

    <div class="page-header">
        <h1 class="page-title">Пользователи</h1>
        <!-- <a href="/admin/users/create" class="btn btn--primary">Создать нового пользователя</a> -->
    </div>

    <?php if ($users): ?>
        <div class="article-list">
            <?php foreach ($users as $user): ?>
                <article class="article-card">
                    <h2 class="article-card__title">
                        <a href="/admin/users/<?= $user['id'] ?>">
                            <?= htmlspecialchars($user['username']) ?>
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

<?php require BASE_PATH . '/views/admin_partials/footer.php'; ?>