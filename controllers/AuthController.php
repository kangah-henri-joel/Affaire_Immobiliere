<?php
// controllers/AuthController.php

require_once __DIR__ . '/Controller.php';

class AuthController extends Controller {
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            $userModel = new UserModel();
            $user = $userModel->getByUsername($username);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $this->redirect('/admin');
            } else {
                $error = "Identifiants invalides.";
                $this->render('login', ['error' => $error]);
            }
        } else {
            $this->render('login');
        }
    }

    public function logout() {
        session_destroy();
        $this->redirect('/login');
    }
}
