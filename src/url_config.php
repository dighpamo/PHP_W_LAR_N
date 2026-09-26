<?php

function renderView(string $viewName, $data = []): void
{
    extract($data);

    $viewPath = BASE_PATH . "/views/" . $viewName . ".php";

    if (file_exists($viewPath)) {
        include $viewPath;
    } else {
        http_response_code(500);
        echo 'Ошибка сервера';
        exit;
    }
}