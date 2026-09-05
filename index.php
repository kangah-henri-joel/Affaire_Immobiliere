<?php
// index.php

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Autoload classes
spl_autoload_register(function ($class) {
    $dirs = ['controllers', 'models', 'config'];
    foreach ($dirs as $dir) {
        $file = __DIR__ . '/' . $dir . '/' . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Define Base URL for the project with auto-detection for subfolders & web servers
$rawScriptName = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
if ($rawScriptName === '/' || $rawScriptName === '.') {
    $rawScriptName = '';
}

$rawUri = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?? '';
$rawUri = str_replace('\\', '/', $rawUri);

// Auto-detect base path if hosted in subfolder (e.g. /Projet_Affaire)
$folderName = basename(__DIR__);
if (empty($rawScriptName) && !empty($folderName) && (strpos($rawUri, '/' . $folderName . '/') === 0 || $rawUri === '/' . $folderName)) {
    $rawScriptName = '/' . $folderName;
}

define('BASE_URL', rtrim($rawScriptName, '/'));

// Simple Router - Clean URI from base path
$uri = $rawUri;
if (BASE_URL !== '' && strpos($uri, BASE_URL) === 0) {
    $uri = substr($uri, strlen(BASE_URL));
}
// Double safety: if subfolder name remains at front of URI
if (!empty($folderName) && (strpos($uri, '/' . $folderName . '/') === 0 || $uri === '/' . $folderName)) {
    $uri = substr($uri, strlen('/' . $folderName));
}
$uri = trim($uri, '/');

// ── Enregistrer les visites sur les pages publiques ───────────────────────────
$publicPages = ['', 'annonces', 'contact', 'bureaux'];
$isPublicPage = (empty(explode('/', $uri)[0]) || !in_array(explode('/', $uri)[0], ['admin', 'super-admin', 'api', 'login', 'register', 'logout']));
if ($isPublicPage && !isset($_SESSION['user_id'])) {
    // Visitor logging – ne pas bloquer si la table n'existe pas encore
    try {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $ip = explode(',', $ip)[0]; // prendre la première IP si proxyfié
        $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $page = '/' . $uri;
        $token = $_SESSION['client_token'] ?? ($_COOKIE['client_token'] ?? session_id());
        $vModel = new VisitorModel();
        $vModel->log($ip, $ua, $page, $token);
    } catch (\Exception $e) {
        // Silently fail if table doesn't exist yet
    }
}

$routes = [
    // ── Public ────────────────────────────────────────────────────────────────
    '' => ['HomeController', 'index'],
    'login' => ['AuthController', 'login'],
    'register' => ['AuthController', 'register'],
    'logout' => ['AuthController', 'logout'],
    'contact' => ['HomeController', 'contact'],
    'bureaux' => ['HomeController', 'bureaux'],
    'bureau/contact' => ['HomeController', 'bureauContact'],
    'annonces' => ['AnnonceController', 'index'],
    'annonces/map' => ['AnnonceController', 'map'],
    'annonce' => ['AnnonceController', 'view'],
    'annonce/contact' => ['AnnonceController', 'contact'],
    'annonce/comment' => ['AnnonceController', 'saveComment'],
    'annonce/comment/delete' => ['AnnonceController', 'deleteComment'],
    'annonce/message' => ['AnnonceController', 'sendClientMessage'],
    'annonce/media/set-main' => ['AnnonceController', 'setMainMedia'],
    'annonce/like' => ['AnnonceController', 'toggleLike'],
    'demandes' => ['AnnonceController', 'demandes'],
    'demandes/save' => ['AnnonceController', 'saveDemande'],
    'demandes/close' => ['AnnonceController', 'closeDemande'],
    'mes-conversations' => ['AnnonceController', 'findConversation'],
    // ── Admin ─────────────────────────────────────────────────────────────────
    'admin' => ['AdminController', 'index'],
    'admin/annonces' => ['AdminController', 'annonces'],
    'admin/annonces/save' => ['AdminController', 'saveAnnonce'],
    'admin/annonces/publish' => ['AdminController', 'publishAnnonce'],
    'admin/annonces/delete' => ['AdminController', 'deleteAnnonce'],
    'admin/annonces/corbeille' => ['AdminController', 'corbeille'],
    'admin/annonces/restore' => ['AdminController', 'restoreAnnonce'],
    'admin/annonces/hard-delete' => ['AdminController', 'hardDeleteAnnonce'],
    'admin/annonces/empty-trash' => ['AdminController', 'emptyTrash'],
    'admin/leads' => ['AdminController', 'leads'],
    'admin/leads/reply' => ['AdminController', 'replyLead'],
    'admin/publications' => ['AdminController', 'publications'],
    'admin/publications/save' => ['AdminController', 'savePublication'],
    'admin/publications/delete' => ['AdminController', 'deletePublication'],
    'admin/publications/get' => ['AdminController', 'getPublicationJson'],
    'admin/profil' => ['AdminController', 'profile'],
    'admin/consultants' => ['AdminController', 'consultants'],
    'admin/consultants/save' => ['AdminController', 'saveConsultant'],
    'admin/consultants/delete' => ['AdminController', 'deleteConsultant'],
    'admin/settings' => ['AdminController', 'settings'],
    'admin/settings/save' => ['AdminController', 'saveSettings'],
    'admin/journal' => ['AdminController', 'journal'],
    'admin/bureau' => ['AdminController', 'bureau'],
    'admin/bureau/save' => ['AdminController', 'saveBureau'],
    'admin/messages' => ['AdminController', 'messages'],
    'admin/messages/send' => ['AdminController', 'sendMessage'],
    'admin/client-messages' => ['AdminController', 'clientMessages'],
    'admin/client-messages/conversation' => ['AdminController', 'clientConversation'],
    'admin/visitors' => ['AdminController', 'visitors'],
    'admin/affiche' => ['AdminController', 'affiche'],
    'admin/affiche/save' => ['AdminController', 'saveAfficheRecord'],
    // ── Super Admin ───────────────────────────────────────────────────────────
    'super-admin' => ['SuperAdminController', 'index'],
    'super-admin/admins' => ['SuperAdminController', 'admins'],
    'super-admin/admins/save' => ['SuperAdminController', 'saveAdmin'],
    'super-admin/admins/delete' => ['SuperAdminController', 'deleteAdmin'],
    'super-admin/users' => ['SuperAdminController', 'users'],
    'super-admin/users/save' => ['SuperAdminController', 'saveUser'],
    'super-admin/users/validate' => ['SuperAdminController', 'validateUser'],
    'super-admin/users/delete' => ['SuperAdminController', 'deleteUser'],
    'super-admin/users/role' => ['SuperAdminController', 'changeRole'],
    'super-admin/leads' => ['SuperAdminController', 'leads'],
    'super-admin/leads/status' => ['SuperAdminController', 'updateLeadStatus'],
    'super-admin/leads/delete' => ['SuperAdminController', 'deleteLead'],
    'super-admin/annonces' => ['SuperAdminController', 'annonces'],
    'super-admin/annonces/delete' => ['SuperAdminController', 'deleteAnnonce'],
    'super-admin/publications/delete' => ['SuperAdminController', 'deletePublication'],
    'super-admin/settings' => ['SuperAdminController', 'settings'],
    'super-admin/settings/save' => ['SuperAdminController', 'saveSettings'],
    'super-admin/logs' => ['SuperAdminController', 'logs'],
    'super-admin/messages' => ['SuperAdminController', 'adminMessages'],
    'super-admin/messages/conversation' => ['SuperAdminController', 'adminConversation'],
    'super-admin/messages/send' => ['SuperAdminController', 'adminConversation'],
    'super-admin/visitors' => ['SuperAdminController', 'visitors'],
    // ── API ───────────────────────────────────────────────────────────────────
    'api/ai/generate' => ['AIController', 'generate'],
];

// Dynamic routing for /annonce/{id}
$parts = array_values(array_filter(explode('/', $uri), 'strlen'));
$routeKey = implode('/', $parts);
$params = [];

if (!isset($routes[$routeKey]) && !empty($parts) && ($parts[0] === 'Projet_Affaire' || $parts[0] === $folderName)) {
    array_shift($parts);
    $routeKey = implode('/', $parts);
}

if (!isset($routes[$routeKey])) {
    if (count($parts) >= 2 && $parts[0] === 'annonce' && is_numeric($parts[1])) {
        $routeKey = 'annonce';
        $params = array_slice($parts, 1);
    }
}

if (isset($routes[$routeKey])) {
    list($controllerName, $method) = $routes[$routeKey];
    if (class_exists($controllerName)) {
        $controller = new $controllerName();
        if (method_exists($controller, $method)) {
            call_user_func_array([$controller, $method], $params);
        } else {
            die("Method $method not found in $controllerName");
        }
    } else {
        die("Controller $controllerName not found");
    }
} else {
    http_response_code(404);
    include __DIR__ . '/views/layout_header.php';
    echo "<div class='container' style='padding: 100px 0; text-align: center;'>";
    echo "<h1>404 Not Found</h1>";
    echo "<p>La page que vous recherchez n'existe pas.</p>";
    echo "<a href='" . BASE_URL . "/' class='btn'>Retour à l'accueil</a>";
    echo "</div>";
    include __DIR__ . '/views/layout_footer.php';
}
