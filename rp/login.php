<?php
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

    $authenticator = new Authenticator([
        'op_host' => $conf["issuer"],
        'scope' => ['email', 'profile'],
        'client_id' => CLIENT_ID,
        'redirect_uri' => $conf["redirect_uri"],
        // 'nonce' will be set automatically
        // 'state' will be set automatically
    ]);

    $authenticator->sendRequest($conf['authorization_endpoint']);
}
