<?php

namespace Controllers;

use Models\ArticleModel;

class ArticleController {
    private ArticleModel $model;

    public function __construct()
    {
        $this->model = new ArticleModel();
    }

    public function showAll(): void {
        $page = (int)($_GET['page'] ?? 1);
        $perPage = (int)($_GET['perPage'] ?? 10) === 10 ? 10 : 10;
        renderView('public/home', ['articles' => $this->model->getAll($page, $perPage)]);
    }

    public function show(string $slug): void {
        $article = $this->model->getBySlug($slug);
        if (is_null($article)) {
            http_response_code(404);
            renderView('404', ['title' => 'Страница не найдена']);
        }
        renderView('public/article', ['article' => $article]);
    }
}