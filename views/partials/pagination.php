<?php
/** @var int $totalPages */
/** @var int $currentPage */ ?>

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