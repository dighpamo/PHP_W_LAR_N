<?php

use Controllers\AuthController; ?>


<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Статьи</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>

<body>
    <?php flash(); ?>

    <header class="site-header">
        <nav class="site-nav">
            <div class="site-nav__links">
                <a href="/">Статьи</a>
                <a href="/categories">Категории</a>
                <a href="/creaters">Авторы</a>
                <?php if ((new AuthController())->checkAuth()): ?>
                    <a href="/admin">Админка</a>
                <?php endif; ?>
            </div>

            <?php if (!(new AuthController())->checkAuth()): ?>
                <details class="dropdown">
                    <summary class="dropdown-toggle">Войти</summary>
                    <div class="dropdown-menu">

                        <input type="radio" name="auth-tab" id="tab-login" class="auth-tab-input" checked>
                        <input type="radio" name="auth-tab" id="tab-register" class="auth-tab-input">

                        <div class="auth-tabs">
                            <label for="tab-login" class="auth-tab">Вход</label>
                            <label for="tab-register" class="auth-tab">Регистрация</label>
                        </div>

                        <div class="auth-panel auth-panel--login">
                            <form method="post" action="/login" class="auth-form">
                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                                <div class="form-group">
                                    <label for="login-username">Имя пользователя</label>
                                    <input type="text" id="login-username" name="username" required maxlength="255">
                                </div>
                                <div class="form-group">
                                    <label for="login-password">Пароль</label>
                                    <input type="password" id="login-password" name="password" required minlength="8">
                                </div>
                                <button type="submit">Войти</button>
                            </form>
                        </div>

                        <div class="auth-panel auth-panel--register">
                            <form method="post" action="/registration" class="auth-form">
                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                                <div class="form-group">
                                    <label for="registration-username">Имя пользователя</label>
                                    <input type="text" id="registration-username" name="username" required maxlength="255">
                                </div>
                                <div class="form-group">
                                    <label for="registration-password">Пароль</label>
                                    <input type="password" id="registration-password" name="password" required minlength="8">
                                </div>
                                <button type="submit">Зарегистрироваться</button>
                            </form>
                        </div>

                    </div>
                </details>
            <?php else: ?>
                <form method="post" action="/logout" class="logout-form">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                    <button type="submit">Выйти</button>
                </form>
            <?php endif; ?>
        </nav>
    </header>