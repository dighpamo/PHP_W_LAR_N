<?php

namespace Controllers;

class AuthController {
    function checkAuth(): bool
    {
        return isset($_SESSION['user_id']);
    }

    function requireAuth(): void
    {
        if (!$this->checkAuth()) {
            $_SESSION['flash'] = "Туда нельзя, там ничего нет";
            header("Location: /");
            exit;
        }
    }

    function redirectAuth(): void {
        if ($this->checkAuth()) {
            header("Location: /");
            exit;
        }
    }
}