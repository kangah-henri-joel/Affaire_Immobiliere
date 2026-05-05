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

// Define Base URL for the project
$scriptName = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$baseUrl = ($scriptName === '/') ? '' : $scriptName;
define('BASE_URL', $baseUrl);

// Simple Router
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = str_replace('\\', '/', $uri);

// Remove the base path from URI to get the relative route
if (BASE_URL !== '' && strpos($uri, BASE_URL) === 0) {
    $uri = substr($uri, strlen(BASE_URL));
}
$uri = trim($uri, '/');

$routes = [
    '' => ['HomeController', 'index'],
    'login' => ['AuthController', 'login'],
    'logout' => ['AuthController', 'logout'],
    'admin' => ['AdminController', 'index'],
    'admin/annonces' => ['AdminController', 'annonces'],
    'admin/annonces/save' => ['AdminController', 'saveAnnonce'],
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
    'annonces' => ['AnnonceController', 'index'],
    'annonces/map' => ['AnnonceController', 'map'],
    'annonce' => ['AnnonceController', 'view'],
    'annonce/contact' => ['AnnonceController', 'contact'],
    'contact' => ['HomeController', 'contact'],
    'api/ai/generate' => ['AIController', 'generate'],
];

// Split URI to find potential parameters
$parts = explode('/', $uri);
$routeKey = $uri;
$params = [];

// Dynamic routing for /annonce/{id} ONLY if it's not an exact route like /annonce/contact
if (!isset($routes[$uri])) {
    if (count($parts) >= 2 && $parts[0] == 'annonce' && is_numeric($parts[1])) {
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
    // 404
    http_response_code(404);
    include __DIR__ . '/views/layout_header.php';
    echo "<div class='container' style='padding: 100px 0; text-align: center;'>";
    echo "<h1>404 Not Found</h1>";
    echo "<p>La page que vous recherchez n'existe pas.</p>";
    echo "<a href='".BASE_URL."/' class='btn'>Retour à l'accueil</a>";
    echo "</div>";
    include __DIR__ . '/views/layout_footer.php';
}
