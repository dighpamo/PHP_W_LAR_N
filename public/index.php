<?php


define('BASE_PATH', dirname(__DIR__));
require BASE_PATH . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
$dotenv->load();

use Controllers\ArticleController;
use Controllers\AuthController;
use Controllers\UserController;
use Controllers\CategoryController;

use Controllers\AdminCategoryController;
use Controllers\AdminArticleController;
use Controllers\AdminUserController;

session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (($_SESSION['csrf_token'] ?? '') !== $token) {
        $_SESSION['flash'] = "Привет";
        // http_response_code(403); <-- так будет правильно
        header('Location: /');
        exit;
    }
    unset($_POST['csrf_token']);
}

$user_id = $_SESSION['user_id'] ?? null;

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

if ($requestUri === "/categories/" || $requestUri === "/categories") {
    (new CategoryController())->showAll();
    exit;
}

if (str_starts_with($requestUri, "/categories/")) {
    $slug = substr($requestUri, strlen('/categories/'));
    (new CategoryController())->show($slug);
    exit;
}

if ($requestUri === "/login" && $_SERVER['REQUEST_METHOD'] === 'POST') {
    (new AuthController())->redirectAuth();

    (new UserController())->login($_POST['username'] ?? '', $_POST['password'] ?? '');
    exit;
    
}

if ($requestUri === "/registration" && $_SERVER['REQUEST_METHOD'] === 'POST') {
    (new AuthController())->redirectAuth();

    (new UserController())->registration($_POST['username'] ?? '', $_POST['password'] ?? '');
    exit;
}

if ($requestUri == "/logout" && $_SERVER['REQUEST_METHOD'] == 'POST') {
    (new UserController())->logout();
    exit;
}

if (str_starts_with($requestUri, '/admin')) {
    (new AuthController())->requireAuth();

    
    if (($requestUri === '/admin' || $requestUri === '/admin/articles') && $_SERVER["REQUEST_METHOD"] === "GET") {
        (new AdminArticleController())->showAll();
        exit;
    }
    
    if ($requestUri === '/admin/articles/create' && $_SERVER["REQUEST_METHOD"] === "GET") {
        (new AdminArticleController())->getForm();
        exit;
    }

    if ($requestUri === '/admin/articles' && $_SERVER["REQUEST_METHOD"] === "POST") {
        (new AdminArticleController())->create($_POST);
        exit;
    }

    if (preg_match('#^/admin/articles/(\d+)/delete$#', $requestUri, $m) && $_SERVER["REQUEST_METHOD"] === "POST") {
        (new AdminArticleController())->delete((int)$m[1]);
        exit;
    }

    if (preg_match('#^/admin/articles/(\d+)$#', $requestUri, $m) && $_SERVER["REQUEST_METHOD"] === "GET") {
        (new AdminArticleController())->show((int)$m[1]);
        exit;
    }
    if (preg_match('#^/admin/articles/(\d+)$#', $requestUri, $m) && $_SERVER["REQUEST_METHOD"] === "POST") {
        (new AdminArticleController())->edit((int)$m[1], $_POST);
        exit;
    }
// КАТ
    if (($requestUri === '/categories' || $requestUri === '/admin/categories') && $_SERVER["REQUEST_METHOD"] === "GET") {
        (new AdminCategoryController())->showAll();
        exit;
    }

    if ($requestUri === '/admin/categories/create' && $_SERVER["REQUEST_METHOD"] === "GET") {
        (new AdminCategoryController())->getForm();
        exit;
    }

    if ($requestUri === '/admin/categories' && $_SERVER["REQUEST_METHOD"] === "POST") {
        (new AdminCategoryController())->create($_POST);
        exit;
    }

    if (preg_match('#^/admin/categories/(\d+)/delete$#', $requestUri, $m) && $_SERVER["REQUEST_METHOD"] === "POST") {
        (new AdminCategoryController())->delete((int)$m[1]);
        exit;
    }

    if (preg_match('#^/admin/categories/(\d+)$#', $requestUri, $m) && $_SERVER["REQUEST_METHOD"] === "GET") {
        (new AdminCategoryController())->show((int)$m[1]);
        exit;
    }

    if (preg_match('#^/admin/categories/(\d+)$#', $requestUri, $m) && $_SERVER["REQUEST_METHOD"] === "POST") {
        (new AdminCategoryController())->edit((int)$m[1], $_POST);
        exit;
    }

    if (($requestUri === '/users' || $requestUri === '/admin/users') && $_SERVER["REQUEST_METHOD"] === "GET") {
        (new AdminUserController())->showAll();
        exit;
    }

    if ($requestUri === '/admin/users/create' && $_SERVER["REQUEST_METHOD"] === "GET") {
        (new AdminUserController())->getForm();
        exit;
    }

    // if ($requestUri === '/admin/users' && $_SERVER["REQUEST_METHOD"] === "POST") {
    //     (new AdminUserController())->create($_POST);
    //     exit; <-- тут я задумал, что пока пользователи равны, то пользователь создавать другого не может. Регистрироваться и создавать себя - да, смотреть о других - да, удалять других - да, создавать - нет.
    //  Но если раскоментить и прокинуть + раскоментить в форме - все равно будет всё работать.
    // }

    if (preg_match('#^/admin/users/(\d+)/delete$#', $requestUri, $m) && $_SERVER["REQUEST_METHOD"] === "POST") {
        (new AdminUserController())->delete((int)$m[1]);
        exit;
    }

    if (preg_match('#^/admin/users/(\d+)$#', $requestUri, $m) && $_SERVER["REQUEST_METHOD"] === "GET") {
        (new AdminUserController())->show((int)$m[1]);
        exit;
    }
    if (preg_match('#^/admin/users/(\d+)$#', $requestUri, $m) && $_SERVER["REQUEST_METHOD"] === "POST") {
        (new AdminUserController())->edit((int)$m[1], $_POST);
        exit;
    }

    http_response_code(404);
    renderView('404');
    exit;
}

switch ($requestUri) {
    case '/':
        (new ArticleController())->showAll();
        break;

    default:
        renderView('404');
        break;
}