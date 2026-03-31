<?php
declare(strict_types=1);

session_start();

require_once dirname(__DIR__) . '/vendor/autoload.php';

use App\Authenticator;

$conf = json_decode(file_get_contents(__DIR__."/../config.json"), true);

// Configuration:
define("CLIENT_ID", $conf["client_id"]);
define("CLIENT_SECRET", $conf["client_secret"]);

$authenticator = new Authenticator([
    'op_host' => $conf["issuer"],
    'scope' => ['email', 'profile'],
    'client_id' => CLIENT_ID,
    'redirect_uri' => $conf["redirect_uri"],
    // 'nonce' will be set automatically
    // 'state' will be set automatically
]);


// 1. GESTION DU CHEMIN (BASE PATH)
$requestUri = $_SERVER['REQUEST_URI'];
$basePath = '/connect'; // Ton dossier exposé sur le domaine

// On retire le paramètre de requête (?code=...) pour le matching des routes
$parsedUrl = parse_url($requestUri, PHP_URL_PATH);

// On retire le préfixe "/connect" pour que le routeur travaille sur "/"
if (strpos($parsedUrl, $basePath) === 0) {
    $request = substr($parsedUrl, strlen($basePath));
} else {
    $request = $parsedUrl;
}

// Nettoyage final pour s'assurer que "/" match bien la racine
$request = $request === '' ? '/' : $request;

// On définit nos routes : "URL" => "Fonction ou Fichier"
$routes = [
    '/'          => 'home.php',
    '/login'     => 'login.php',
    '/callback'  => 'callback.php',
    '/profile'   => 'profile.php',
    '/auth-callback'   => 'auth-callback.php',
    '/logout'    => function() {
        unset($_SESSION["USER"]);
        header("Location: https://op.local/logout", true, 302);
        exit;
    },
    '/api/user'  => function() {
        header('Content-Type: application/json');
        echo json_encode(['user' => 'julien']);
    },
];

// 3. LOGIQUE DE ROUTAGE
if (array_key_exists($request, $routes)) {
    $handler = $routes[$request];

    if (is_callable($handler)) {
        $handler();
    } else {
        // PROTECTION : Tes fichiers (home.php, etc.) doivent être
        // dans /connect/rp/ (un niveau au-dessus de public) pour ne pas être accessibles directement
        $filePath = dirname(__DIR__) . '/' . $handler;

        if (file_exists($filePath)) {
            require $filePath;
        } else {
            http_response_code(500);
            echo "Erreur interne : Fichier source introuvable.";
        }
    }
} else {
    http_response_code(404);
    echo "404 - Page non trouvée (Chemin détecté : $request)";
}
