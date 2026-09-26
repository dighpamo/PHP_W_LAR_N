<?php /** @var array $articles */ ?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>Статьи</title>
</head>

<body>
    <?php if ($articles): ?>
        <?php foreach ($articles as $article): ?>
            <div>
                <div><?= htmlspecialchars($article['title']) ?></div>
                <div><?= htmlspecialchars($article['preview']) ?></div>
                <div><?= htmlspecialchars($article['slug']) ?></div>
                <div><?= htmlspecialchars($article['image_path']) ?></div>
                <div><?= htmlspecialchars($article['category_id']) ?></div>
                <div><?= htmlspecialchars($article['views_count']) ?></div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div>Тут пока ничего нет</div>
    <?php endif; ?>
</body>

</html>