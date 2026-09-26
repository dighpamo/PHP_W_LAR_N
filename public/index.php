<?php
use Controllers\ArticleController;

define('BASE_PATH', dirname(__DIR__));
require BASE_PATH . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
$dotenv->load();

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (strlen($requestUri) > 1) {
    $requestUri = rtrim($requestUri, '/');
}

 
if ($requestUri === "/articles/" || $requestUri === "/articles") {
    header('Location: /');
    exit;
}

if (str_starts_with($requestUri, "/articles/")) {
    $slug = substr($requestUri, strlen('/articles/'));
    (new ArticleController())->show($slug);
    exit;
}

// if ($requestUri === "/admin/articles/")

switch ($requestUri) {
    case '/':
        (new ArticleController())->showAll();
        break;

    case '/login':
        break;

    case '/admin/articles/form':
        break;

    case '/admin/categories/':
        break;

    case '/admin/users/':
        break;

    case '/admin/users/form':
        break;

    case '/admin/settings/form':
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