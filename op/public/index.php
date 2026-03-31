<?php
declare(strict_types=1);

session_start();

require_once dirname(__DIR__) . '/vendor/autoload.php';

// Configuration:
define("CLIENT_ID", "r135fgelgs");

$db = new SQLite3(__DIR__. "/../database.sqlite3");

// On récupère l'URL demandée et on nettoie les paramètres de requête (?foo=bar)
$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// On définit nos routes : "URL" => "Fonction ou Fichier"
$routes = [
    '/'          => 'home.php',
    '/authorize' => 'authorize_endpoint.php',
    '/token'     => 'token_endpoint.php',
    '/login'     => 'login_view.php',
    '/logout'    => function() {
        unset($_SESSION["USER"]);
        header("Location: https://rp.local/login", true, 302); // TODO: make this parameterizable
        exit;
    },
    '/token'    => 'token.php',
    '/api/user'  => function() {
        header('Content-Type: application/json');
        echo json_encode(['user' => 'julien']);
    },
];

// Logique de routage
if (array_key_exists($request, $routes)) {
    $handler = $routes[$request];

    if (is_callable($handler)) {
        $handler();
    } else {
        // On suppose que c'est un fichier dans un dossier "src" ou "controllers"
        require __DIR__ . '/../public/' . $handler;
    }
} else {
    http_response_code(404);
    echo "404 - Page non trouvée";
}
