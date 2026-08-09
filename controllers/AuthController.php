<?php
// controllers/AuthController.php

require_once __DIR__ . '/Controller.php';

class AuthController extends Controller {
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            $userModel = new UserModel();
            $user = $userModel->verifyLogin($username, $password);

            if ($user) {
                $_SESSION['user_id']   = $user['id'];
                $_SESSION['username']  = $user['username'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['full_name'] = $user['full_name'];

                // Redirection selon le rôle
                if ($user['role'] === 'super_admin') {
                    $this->redirect('/super-admin');
                } elseif ($user['role'] === 'client') {
                    $this->redirect('/');
                } else {
                    $this->redirect('/admin');
                }
            } else {
                $checkUser = $userModel->getByUsername($username);
                if ($checkUser && $checkUser['status'] === 'pending') {
                    $error = "Votre compte est en cours de validation par l'administrateur.";
                } else {
                    $error = "Identifiants invalides.";
                }
                $this->render('login', ['error' => $error]);
            }
        } else {
            $this->render('login');
        }
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $type = $_POST['type'] ?? 'client'; // 'client' ou 'agent'
            $data = [
                'full_name' => ($_POST['nom'] ?? '') . ' ' . ($_POST['prenom'] ?? ''),
                'username'  => $_POST['username'] ?? '',
                'password'  => $_POST['password'] ?? '',
                'email'     => $_POST['email'] ?? '',
                'phone_tel' => $_POST['phone'] ?? '',
                'country'   => $_POST['country'] ?? ''
            ];

            $userModel = new UserModel();
            
            // Basic validation
            if (empty($data['username']) || empty($data['password']) || empty($data['email'])) {
                $this->render('login', ['error_register' => 'Tous les champs obligatoires doivent être remplis.', 'type' => $type]);
                return;
            }
            if ($userModel->getByUsername($data['username']) || $userModel->getByUsername($data['email'])) {
                $this->render('login', ['error_register' => 'Nom d\'utilisateur ou email déjà utilisé.', 'type' => $type]);
                return;
            }

            try {
                if ($type === 'agent') {
                    $userId = $userModel->createAgent($data);
                    
                    // Add bureau info
                    require_once __DIR__ . '/../models/BureauModel.php';
                    $bureauModel = new BureauModel();
                    $bureauData = [
                        'nom'            => $_POST['company_name'] ?? '',
                        'adresse'        => $_POST['company_address'] ?? '',
                        'ville'          => $_POST['company_city'] ?? '',
                        'phone_tel'      => $_POST['company_phone'] ?? '',
                        'email'          => $_POST['company_email'] ?? '',
                        'description'    => $_POST['company_desc'] ?? ''
                    ];
                    $bureauModel->create($userId, $bureauData);

                    $this->render('login', ['success_register' => 'Compte agent créé avec succès. Un administrateur doit valider votre compte avant de pouvoir vous connecter.']);
                } else {
                    $userId = $userModel->createClient($data);
                    
                    // Auto login for client
                    $user = $userModel->getById($userId);
                    $_SESSION['user_id']   = $user['id'];
                    $_SESSION['username']  = $user['username'];
                    $_SESSION['user_role'] = $user['role'];
                    $_SESSION['full_name'] = $user['full_name'];
                    
                    $this->redirect('/');
                }
            } catch (\Throwable $e) {
                $this->render('login', ['error_register' => 'Erreur lors de l\'inscription: ' . $e->getMessage(), 'type' => $type]);
            }
        } else {
            $this->redirect('/login');
        }
    }

    public function logout() {
        session_destroy();
        $this->redirect('/login');
    }
}
