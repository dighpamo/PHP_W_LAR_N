<?php

namespace Controllers;

use Models\CategoryModel;

class CategoryController
{
    private CategoryModel $model;

    public function __construct()
    {
        $this->model = new CategoryModel();
    }

    public function showAll(): void
    {
        $page = (int)($_GET['page'] ?? 1);
        $perPage = (int)($_GET['perPage'] ?? 10) === 10 ? 10 : 10;
        $totalCount = $this->model->getTotalCount();
        $totalPages = (int)ceil($totalCount / $perPage);
        if ($page > $totalPages && $totalPages > 0) {
            http_response_code(404);
            renderView('404');
            exit;
        }
        renderView(
            'public/categories',
            [
                'categories' => $this->model->getAll($page, $perPage),
                'totalPages' => $totalPages,
                'currentPage' => $page,
            ]
        );
    }

    public function show(string $slug): void
    {
        $category = $this->model->getBySlug($slug);
        if (is_null($category)) {
            http_response_code(404);
            renderView('404', ['title' => 'Страница не найдена']);
            return;
        }
        renderView('public/category', ['category' => $category]);
    }

    public function edit(int $id, array $values): void
    {
        $this->model->update($id, $values);
        renderView('/admin/categories/' . $id);
        exit;
    }

    public function delete(int $id): void
    {
        $this->model->delete($id);
        header('Location: /admin/categories');
        exit;
    }
}
