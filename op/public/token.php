<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
Implements section 3.1.3.3
*/


$expiration_time = 3600;
$jwt_algo = "EdDSA";
$private_key = trim(file_get_contents(__DIR__."/../../vault/private.dsa"));
$access_token = base64_encode(random_bytes(16));  // TODO: persist in db
$refresh_token = base64_encode(random_bytes(16)); // TODO: persist in db
$jwt_data = [
    "iss" => "op.local",
    "aud" => "rp.local",
    "iat" => time(),
    "exp" => time() + $expiration_time,
];

$response = [
    "access_token" => $access_token,
    "token_type" => "Bearer",
    "refresh_token" => $refresh_token,
    "expires_in" => $expiration_time,
    "id_token" => JWT::encode($jwt_data, $private_key, $jwt_algo),
];



header("Content-Type: application/json");
header("Cache-Control: no-store");
echo json_encode($response);

// {
//    "access_token": "SlAV32hkKG",
//    "token_type": "Bearer",
//    "refresh_token": "8xLOxBtZp8",
//    "expires_in": 3600,
//    "id_token": "eyJhbGciOiJSUzI1NiIsImtpZCI6IjFlOWdkazcifQ.ewogImlzc
//      yI6ICJodHRwOi8vc2VydmVyLmV4YW1wbGUuY29tIiwKICJzdWIiOiAiMjQ4Mjg5
//      NzYxMDAxIiwKICJhdWQiOiAiczZCaGRSa3F0MyIsCiAibm9uY2UiOiAibi0wUzZ
//      fV3pBMk1qIiwKICJleHAiOiAxMzExMjgxOTcwLAogImlhdCI6IDEzMTEyODA5Nz
//      AKfQ.ggW8hZ1EuVLuxNuuIJKX_V8a_OMXzR0EHR9R6jgdqrOOF4daGU96Sr_P6q
//      Jp6IcmD3HP99Obi1PRs-cwh3LO-p146waJ8IhehcwL7F09JdijmBqkvPeB2T9CJ
//      NqeGpe-gccMg4vfKjkM8FcGvnzZUN4_KSP0aAp1tOJ1zZwgjxqGByKHiOtX7Tpd
//      QyHE5lcMiKPXfEIQILVq0pc_E2DzL7emopWoaoZTF_m0_N0YzFC6g6EJbOEoRoS
//      K5hoDalrcvRYLSrQAZZKflyuVCyixEoV9GfNQC3_osjzw2PAithfubEEBLuVVk4
//      XUVrWOLrLl0nx7RkKU8NXNHq-rvKMzqg"
//   }
