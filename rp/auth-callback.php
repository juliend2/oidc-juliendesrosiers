<?php

use GuzzleHttp\Psr7\Request;

$client = new Request();

$tokenRequest = new Request('POST', $conf['token_endpoint'], [
   'Authorization' => 'Basic'. base64_encode($conf['client_id'] .':'. $conf['client_secret']),
   'form_params' => [
      'grant_type' => 'authorization_code',
      'code' => $_GET['code'],
      'redirect_uri' => urlencode($conf['redirect_uri']),
   ],
]);
$res = $client->send($tokenRequest);
echo $res->getBody();

// $oidc = new OpenIDConnectClient($conf['issuer'],
//                                 $conf['client_id'],
//                                 $conf['client_secret']);
// $oidc->providerConfigParam(['token_endpoint'=>$conf['token_endpoint']]);
// // $oidc->addScope(['https://graph.microsoft.com/.default', 'oidc', 'email', 'profile']);

// // this assumes success (to validate check if the access_token property is there and a valid JWT) :
// $token_response = $oidc->requestClientCredentialsToken();

// echo '<pre>';
// var_dump($token_response);
// echo '</pre>';
// $clientCredentialsToken = $token_response->access_token;
// $id_token =  $token_response->id_token;

/**
3.1.3.7.  ID Token Validation
Clients MUST validate the ID Token in the Token Response in the following manner:
1. If the ID Token is encrypted, decrypt it using the keys and algorithms that the
   Client specified during Registration that the OP was to use to encrypt the ID
   Token. If encryption was negotiated with the OP at Registration time and the ID
   Token is not encrypted, the RP SHOULD reject it.
2. The Issuer Identifier for the OpenID Provider (which is typically obtained
   during Discovery) MUST exactly match the value of the iss (issuer) Claim.
3. The Client MUST validate that the aud (audience) Claim contains its client_id
   value registered at the Issuer identified by the iss (issuer) Claim as an
   audience. The aud (audience) Claim MAY contain an array with more than one
   element. The ID Token MUST be rejected if the ID Token does not list the Client
   as a valid audience, or if it contains additional audiences not trusted by the
   Client.
4. If the implementation is using extensions (which are beyond the scope of this
   specification) that result in the azp (authorized party) Claim being present, it
   SHOULD validate the azp value as specified by those extensions.
5. This validation MAY include that when an azp (authorized party) Claim is
   present, the Client SHOULD verify that its client_id is the Claim Value.
6. If the ID Token is received via direct communication between the Client and the
   Token Endpoint (which it is in this flow), the TLS server validation MAY be used
   to validate the issuer in place of checking the token signature. The Client MUST
   validate the signature of all other ID Tokens according to JWS [JWS] using the
   algorithm specified in the JWT alg Header Parameter. The Client MUST use the
   keys provided by the Issuer.
7. The alg value SHOULD be the default of RS256 or the algorithm sent by the Client
   in the id_token_signed_response_alg parameter during Registration.
8. If the JWT alg Header Parameter uses a MAC based algorithm such as HS256, HS384,
   or HS512, the octets of the UTF-8 [RFC3629] representation of the client_secret
   corresponding to the client_id contained in the aud (audience) Claim are used as
   the key to validate the signature. For MAC based algorithms, the behavior is
   unspecified if the aud is multi-valued.
9. The current time MUST be before the time represented by the exp Claim.
10. The iat Claim can be used to reject tokens that were issued too far away from
    the current time, limiting the amount of time that nonces need to be stored to
    prevent attacks. The acceptable range is Client specific.
11. If a nonce value was sent in the Authentication Request, a nonce Claim MUST be
    present and its value checked to verify that it is the same value as the one
    that was sent in the Authentication Request. The Client SHOULD check the nonce
    value for replay attacks. The precise method for detecting replay attacks is
    Client specific.
12. If the acr Claim was requested, the Client SHOULD check that the asserted Claim
    Value is appropriate. The meaning and processing of acr Claim Values is out of
    scope for this specification.
13. If the auth_time Claim was requested, either through a specific request for this
    Claim or by using the max_age parameter, the Client SHOULD check the auth_time
    Claim value and request re-authentication if it determines too much time has
    elapsed since the last End-User authentication.
*/

?>
recevoir le callback
<!-- 
<pre>
<?php
// var_dump($_GET);
// var_dump($clientCredentialsToken);
// var_dump($id_token);
?>
</pre> -->
