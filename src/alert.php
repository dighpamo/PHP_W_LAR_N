<?php
    function flash(?string $message = null): void
    {
        if ($message) {
            $_SESSION['flash'] = $message;
        } else {
            if (!empty($_SESSION['flash'])) {
                echo '<div class="alert">' . htmlspecialchars($_SESSION['flash']) . '</div>';
                unset($_SESSION['flash']);
        }
    }
}