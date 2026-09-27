<?php

namespace Controllers;

use Models\UserModel;

class UserController 
{
    private UserModel $model;

    public function __construct()
    {
        $this->model = new UserModel();
    }

    public function registration(string $username, string $password): void {
        if ($username == '') {
            $_SESSION['flash'] = "Введите имя пользователя";
            exit;
        }
        if ($password == '') {
            $_SESSION['flash'] = "Введите пароль";
            exit;
        }
        if (strlen($password) < 8) {
            $_SESSION['flash'] = "В пароле должно быть не меньше 8 символов";
            exit;
        }
        try {
            $this->model->create($username, $password);
            header("Location: /");
            exit;
        } catch (\PDOException $e) {
            $_SESSION['flash'] = "Пользователь с таким именем уже существует";
            header("Location: /");
            exit;
        }

    }

    public function login(string $username, string $password): void {
        if ($username == '') {
            $_SESSION['flash'] = "Введите имя пользователя";
            exit;
        }
        if ($password == '' || mb_strlen($password, 'UTF-8') < 8) {
            $_SESSION['flash'] = "Введен некорректный пароль";
            exit;
        }
        $user_id = $this->model->login($username, $password);
        if (is_int($user_id)){
            $_SESSION['user_id'] = $user_id;

            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

            header("Location: /");
            exit;
        } else {
            $_SESSION['flash'] = "Неверный логин или пароль";
            header("Location: /");
            exit;
        }
    }

    public function logout(): void {
        $_SESSION = [];
        session_destroy();
        header("Location: /");
        exit;
    }
}