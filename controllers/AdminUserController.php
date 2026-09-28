<?php

namespace Controllers;

use Models\UserModel;

class AdminUserController {
    private UserModel $model;

    public function __construct()
    {
        $this->model = new  UserModel();
    }

    public function showAll(): void
    {
        $page = (int)($_GET['page'] ?? 1);
        $perPage = (int)($_GET['perPage'] ?? 10) === 10 ? 10 : 10;
        renderView('admin/users/list', ['users' => $this->model->getAdminIndexes($page, $perPage)]);
    }

    public function show(int $id): void
    {
        $user = $this->model->getUserById($id);
        if (is_null($user)) {
            http_response_code(404);
            renderView('404');
            return;
        }
        renderView("admin/users/form", ["user" => $user]);
    }

    public function getForm(): void
    {
        renderView("admin/users/form", ["users" => null]);
    }
// Спросить Никиту про сессии, значения и какая практика лучше header(...) или явное $this->method
    public function edit(int $id, array $values): void 
    {
        try {
            $this->model->update($id, $values);
        } catch (\PDOException $e) {
            // $_SESSION['flash'] = "Что-то пошло не так.";
            $_SESSION['flash'] = $e->getMessage();
        }
        header('Location: /admin/users/' . $id);
        exit;
    }

    public function create(array $values): void {
        try {
            $this->model->create($values);
            header('Location: /admin/users');
            exit;

        } catch (\PDOException $e) {
            $_SESSION['flash'] = "Создание пользователя не удалось.";
            header('Location: /admin/users/create');
            exit;
        }
    }

    public function delete(int $id): void
    {
        try {
            $this->model->delete($id);
            header('Location: /admin/users');
            exit;
        } catch (\PDOException $e) {
            $_SESSION['flash'] = "Удаление пользователя не удалось.";
            header('Location: /admin/users' . $id);
            exit;
        }
    }
}