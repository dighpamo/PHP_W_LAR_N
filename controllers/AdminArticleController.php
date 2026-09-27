<?php

namespace Controllers;

use Models\ArticleModel;
use Downloader\ImageUploader;
use FFI\Exception;

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
// Спросить Никиту про сессии, значения и какая практика лучше header(...) или явное $this->method
    public function edit(int $id, array $values): void 
    {
        if ($_FILES['image_path']['error'] !== UPLOAD_ERR_NO_FILE) {
            try {
                ImageUploader::verify_image($_FILES['image_path']);
                $values['image_path'] = ImageUploader::save_file($_FILES['image_path']);
            } catch (\Exception $e) {
                $_SESSION['flash'] = $e->getMessage();
                exit;
            }
        }
        try {
            $this->model->update($id, $values);
        } catch (\PDOException $e) {
            // $_SESSION['flash'] = "Что-то пошло не так.";
            $_SESSION['flash'] = $e->getMessage();
        }
        header('Location: /admin/articles/' . $id);
        exit;
    }

    public function create(array $values): void {
        if ($_FILES['image_path']['error'] !== UPLOAD_ERR_NO_FILE) {
            try {
                ImageUploader::verify_image($_FILES['image_path']);
                $values['image_path'] = ImageUploader::save_file($_FILES['image_path']);
                
            } catch (\Exception $e) {
                $_SESSION['flash'] = $e->getMessage();
                exit;
            }
        }
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

    public function delete(int $id): void
    {
        try {
            $this->model->delete($id);
            header('Location: /admin/articles');
            exit;
        } catch (\PDOException $e) {
            $_SESSION['flash'] = "Удаление статьи не удалось.";
            header('Location: /admin/articles/' . $id);
            exit;
        }
    }
}