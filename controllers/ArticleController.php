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
            renderView('404');
            return;
        }
        renderView('public/article', ['article' => $article]);
    }

    public function edit(int $id, array $values): void {
        $this->model->update($id, $values);
        header('Location: admin/articles/' . $id);
        exit;
    }

    public function delete(int $id): void {
        $this->model->delete($id);
        header('Location: /admin/articles/');
        exit;
    }

    public function checkView(string $slug): void {
        if ($_SESSION['user_id'] ?? false && $_SESSION['username'] ?? false) {
            $this->model->inceaseArticleCount($slug);
        }
    }
}