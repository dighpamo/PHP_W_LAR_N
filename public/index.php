<?php

define('BASE_PATH', dirname(__DIR__));
require BASE_PATH . '/vendor/autoload.php';

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

use Controllers\ArticleController;

switch ($requestUri) {
    case '/':
    case '/home':
        (new ArticleController())->showAll();
        break;

    case (str_starts_with($requestUri, '/articles/')):
        $slug = substr($requestUri, strlen('/articles/'));
        (new ArticleController())->show($slug);
        break;

    case '/login':
        break;

    case '/articles/form':
        break;

    case '/categories/list':
        break;

    case '/users/list':
        break;

    case '/users/form':
        break;

    case '/settings/form':
        break;

    case '/article':
        break;

    case '/category':
        break;

    default:
        // Если маршрут не найден (404)
        renderView('404', ['title' => 'Страница не найдена']);
        break;
}