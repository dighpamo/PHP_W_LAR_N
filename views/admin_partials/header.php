<?php

use Controllers\AuthController; ?>


<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Статьи</title>
    <link rel="stylesheet" href="/assets/css/admin.css">
</head>

<body>
    <?php flash(); ?>

    <header class="site-header">
        <nav class="site-nav">
            <div class="site-nav__links">
                <a href="/admin/articles">Статьи (Админка)</a>
                <a href="/admin/categories">Категории (Админка)</a>
                <a href="/admin/users">Пользователи (Админка)</a>
            </div>
            <form method="post" action="/logout" class="logout-form">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                <button type="submit">Выйти</button>
            </form>
        </nav>
    </header>