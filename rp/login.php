<?php
ini_set('display_errors', '1');
ini_set('log_errors_max_len', '-1');

use App\Authenticator;


/**
+=============================================================================================================+
|                                                                                                             |
|   Ici on reçoit une requête GET de la part du user agent qui veut s'authentifier avec notre OP.             |
|   Cette étape est optionelle dans le sens qu'on aurait pu directement générer un lien avec les paramètres   |
|   dans le HTML, mais on la fait ici parce que ça permet de mieux illustrer en faisant une étape de plus.    |
|                                                                                                             |
+=============================================================================================================+

https://openid.net/specs/openid-connect-core-1_0.html#AuthRequest
Section:
3.1.2.1

*/

if (isset($_COOKIE['user']) && !empty($_COOKIE['user'])) {
    // use the cookie
    print 'cookie: ';
    var_dump($_COOKIE['user']);
    die;
} else {

    $conf = json_decode(file_get_contents(__DIR__."/config.ms.json"), true);
    if ($_GET['with'] === 'google') {
        $conf = json_decode(file_get_contents(__DIR__."/config.google.json"), true);
    }

    $authenticator = new Authenticator([
        'op_host' => $conf["issuer"],
        'scope' => ['email', 'profile'],
        'client_id' => $conf['client_id'],
        'redirect_uri' => $conf["redirect_uri"],
    ]);

    $authenticator->sendRequest($conf['authorization_endpoint']);
}
