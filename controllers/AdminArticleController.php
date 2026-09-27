<?php

namespace Controllers;

use Models\ArticleModel;

class AdminArticleController {
    private ArticleModel $model;

    public function __construct()
    {
        $this->model = new  ArticleModel();
    }

    public function showAll(): void
    {
        $page = (int)($_GET['page'] ?? 1);
        $perPage = (int)($_GET['perPage'] ?? 10) === 10 ? 10 : 10;
        renderView('admin/articles/list', ['articles' => $this->model->getAdminIndexes($page, $perPage)]);
    }

    public function show(int $id): void
    {
        $article = $this->model->getById($id);
        if (is_null($article)) {
            http_response_code(404);
            renderView('404');
            return;
        }
        renderView("admin/articles/form", ["article" => $article]);
    }

    public function getForm(): void
    {
        renderView("admin/articles/form", ["article" => null]);
    }
// Спросить никиту про сессии, значения и какая практика лучше header(...) или явное $this->method
    public function edit(int $id, array $values): void 
    {
        try {
            $this->model->update($id, $values);
        } catch (\PDOException $e) {
            $_SESSION['flash'] = "Что-то пошло не так.";
            header('Location: /admin/articles/' . $id);
            exit;
        }
        header('Location: /admin/articles/' . $id);
        exit;
    }

    public function create(array $values): void {
        try {
            $this->model->create($values);
            header('Location: /admin/articles');
            exit;

        } catch (\PDOException $e) {
            $_SESSION['flash'] = "Создание статьи не удалось.";
            header('Location: /admin/articles/create');
            exit;
        }
    }
}