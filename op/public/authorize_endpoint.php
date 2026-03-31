<?php

/**
According to:
https://openid.net/specs/openid-connect-core-1_0.html#AuthRequest
section: 3.1.2.2

Receiving the request to /authorize endpoint


1. The Authorization Server MUST validate all the OAuth 2.0 parameters according
to the OAuth 2.0 specification. (RFC6749)
    Here are the parameter that are specific to OAuth 2.0, (according to
    https://www.rfc-editor.org/rfc/rfc6749#section-4.1.1) :
        - response_type : verify that it's 'code'
        - client_id : verify that we have that value in our "database"
        - redirect_uri : verify it's the same that's paired with the client we have in our "database".
        - scope: verify it's not empty (OIDC: also verify it contains 'openid')
        - state: verify it's not empty. save it for next steps.
2. Verify that a scope parameter is present and contains the openid scope value.
(If no openid scope value is present, the request may still be a valid OAuth 2.0
request but is not an OpenID Connect request.)
3. The Authorization Server MUST verify that all the REQUIRED parameters are
present and their usage conforms to this specification.

TODO:
4. If the sub (subject) Claim is requested with a specific value for the ID
Token, the Authorization Server MUST only send a positive response if the
End-User identified by that sub value has an active session with the
Authorization Server or has been Authenticated as a result of the request. The
Authorization Server MUST NOT reply with an ID Token or Access Token for a
different user, even if they have an active session with the Authorization
Server. Such a request can be made either using an id_token_hint parameter or by
requesting a specific Claim Value as described in Section 5.5.1, if the claims
parameter is supported by the implementation.
5. When an id_token_hint is present, the OP MUST validate that it was the issuer
of the ID Token. The OP SHOULD accept ID Tokens when the RP identified by the ID
Token has a current session or had a recent session at the OP, even when the exp
time has passed.

*/

function validate_authentication_request($params) {
    foreach (["response_type", "redirect_uri", "client_id", "scope"] as $key) {
        if (!isset($params[$key])) {
            return "$key must be specified";
        }
    }
    if (!in_array("openid", explode(" ", urldecode($params["scope"])))) {
        return "Unsupported scope list"; // no openid is a no-go
    }
    return true;
}

// Prepare the "Authorization Response":

// $response_type = $_GET["response_type"];
// $scope = $_GET["scope"];
// $client_id = $_GET["client_id"];
// $state = $_GET["state"];
// $redirect_uri = $_GET["redirect_uri"];
// $nonce = $_GET["nonce"];

$validation = validate_authentication_request($_GET);
if ($validation === true) {
    // echo "<pre>";
    // var_dump($_GET); die;
    $_SESSION["oidc"] = $_GET;
    header("Location: /login", true, 302);
    exit;
} else {
    // TODO: redirect accordingly with error params
}