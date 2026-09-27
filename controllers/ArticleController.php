<?php

namespace Controllers;

use Models\ArticleModel;
use Models\CategoryModel;

class ArticleController {
    private ArticleModel $model;

    public function __construct()
    {
        $this->model = new ArticleModel();
    }

    public function showAll(): void {
        $page = (int)($_GET['page'] ?? 1);
        $perPage = (int)($_GET['perPage'] ?? 10) === 10 ? 10 : 10;
        $totalCount = $this->model->getTotalCount();
        $totalPages = (int)ceil($totalCount / $perPage);
        if ($page > $totalPages) {
            http_response_code(404);
            renderView('404');
            exit;
        }
        renderView(
            'public/home',
            [
                'articles' => $this->model->getAll($page, $perPage),
                'categories' => (new CategoryModel())->getAll(),
                'totalPages' => $totalPages,
                'currentPage' => $page,
            ]
        );
    }

    public function show(string $slug): void {
        if (!isset($_SESSION['viewed'][$slug]) && isset($_SESSION['user_id'])) {
            $_SESSION['viewed'][$slug] = true;
            $this->model->increaseArticleCount($slug);
        }
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
}