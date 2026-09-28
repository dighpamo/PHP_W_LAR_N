<?php

namespace Controllers;

use Models\CategoryModel;
use Downloader\ImageUploader;
// use FFI\Exception; <-- скорее всего появилась пока играл с Exception, посмотреть.

class AdminCategoryController {
    private CategoryModel $model;

    public function __construct()
    {
        $this->model = new  CategoryModel();
    }

    public function showAll(): void
    {
        $page = (int)($_GET['page'] ?? 1);
        $perPage = (int)($_GET['perPage'] ?? 10) === 10 ? 10 : 10;
        renderView('admin/categories/list', ['categories' => $this->model->getAdminIndexes($page, $perPage)]);
    }

    public function show(int $id): void
    {
        $category = $this->model->getById($id);
        if (is_null($category)) {
            http_response_code(404);
            renderView('404');
            return;
        }
        renderView("admin/categories/form", ["category" => $category]);
    }

    public function getForm(): void
    {
        renderView("admin/categories/form", ["categories" => null]);
    }

    public function edit(int $id, array $values): void 
    {
        try {
            $this->model->update($id, $values);
        } catch (\PDOException $e) {
            // $_SESSION['flash'] = "Что-то пошло не так.";
            $_SESSION['flash'] = $e->getMessage();
        }
        header('Location: /admin/categories/' . $id);
        exit;
    }

    public function create(array $values): void {
        try {
            $this->model->create($values);
            header('Location: /admin/categories');
            exit;

        } catch (\PDOException $e) {
            $_SESSION['flash'] = "Создание категории не удалось.";
            // $_SESSION['flash'] = $e->getMessage();
            header('Location: /admin/categories/create');
            exit;
        }
    }

    public function delete(int $id): void
    {
        try {
            $this->model->delete($id);
            header('Location: /admin/categories');
            exit;
        } catch (\PDOException $e) {
            $_SESSION['flash'] = "Удаление категории не удалось.";
            header('Location: /admin/categories' . $id);
            exit;
        }
    }
}