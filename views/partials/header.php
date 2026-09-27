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
            </div>

            <?php if (is_null($_SESSION['user_id'])): ?>
                <details class="dropdown">
                    <summary class="dropdown-toggle">Войти</summary>
                    <div class="dropdown-menu">

                        <form method="post" action="/login" class="auth-form">
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

                        <form method="post" action="/registration" class="auth-form">
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
                </details>
            <?php else: ?>
                <form method="post" action="/logout" class="logout-form">
                    <button type="submit">Выйти</button>
                </form>
            <?php endif; ?>
        </nav>
    </header>