<?php
// controllers/Controller.php

abstract class Controller {
    protected function render($view, $data = []) {
        extract($data);
        $viewPath = __DIR__ . '/../views/' . $view . '.php';
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            die("View $view not found.");
        }
    }

    protected function json($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function redirect($url) {
        if (strpos($url, 'http') !== 0) {
            $url = BASE_URL . $url;
        }
        header("Location: $url");
        exit;
    }
}
