<?php

define("USERS", [
    [
        "id" => "12345",
        "email" => "julien@desrosiers.org",
        "password" => "joie",
        "code" => "ThEcOdE!",
        "authorization_code" => base64_encode(random_bytes(16)),
        "refresh_token" => base64_encode(random_bytes(16)),
    ],
    [
        "id" => "23456",
        "email" => "dobby@desrosiers.org",
        "password" => "123456",
        "code" => "tHeCoDe!",
        "authorization_code" => base64_encode(random_bytes(16)),
        "refresh_token" => base64_encode(random_bytes(16)),
    ],
]);

function display_login_form($params) {
    ?>
    <form action="/login" method="post">
        <input type="hidden" name="response_type" value="<?= htmlentities($params["response_type"]) ?>" />
        <input type="hidden" name="scope" value="<?= htmlentities($params["scope"]) ?>" />
        <input type="hidden" name="client_id" value="<?= htmlentities($params["client_id"]) ?>" />
        <input type="hidden" name="state" value="<?= htmlentities($params["state"]) ?>" />
        <input type="hidden" name="redirect_uri" value="<?= htmlentities($params["redirect_uri"]) ?>" />
        <input type="hidden" name="nonce" value="<?= htmlentities($params["nonce"]) ?>" />

        <input type="email" name="email" placeholder="email" /><br>
        <input type="password" name="password" placeholder="password"/><br>
        <input type="submit" name="submit" />
    </form>
    <?php
}

function validate_login_form($form) {
    // TODO: implement true logic
    foreach (USERS as $user) {
        if ($form["email"] === $user["email"] && $form["password"] === $user["password"]) {
            return $user;
        }
    }
    return false;
} 

function storeAuthentication($token, $user_id, $client_id, $authorization_code, $scopes, $expires_at) {
  global $db;
  $stmt = $db->prepare(
    "INSERT INTO access_tokens
     (access_token, user_id, client_id, authorization_code, scopes, created_at, expires_at)
     VALUES
     (:token, :user_id, :client_id, :authorization_code, :scopes, CURRENT_TIMESTAMP, :expires_at)"
  );
  $stmt->bindValue(":token", $token, SQLITE3_TEXT);
  $stmt->bindValue(":user_id", $user_id, SQLITE3_TEXT);
  $stmt->bindValue(":client_id", $client_id, SQLITE3_TEXT);
  $stmt->bindValue(":authorization_code", $authorization_code, SQLITE3_TEXT);
  $stmt->bindValue(":scopes", $scopes, SQLITE3_TEXT);
  $stmt->bindValue(":expires_at", $expires_at, SQLITE3_TEXT);
  $success = $stmt->execute();
  if (!$success) {
    print_r($success);
    die();
  }
  return $success;
}

if (!empty($_POST)) {
    if ($user = validate_login_form($_POST)) {
        $code = bin2hex(random_bytes(16));
        $callback_url = $_POST["redirect_uri"]; // FIXME: check host that it contains the right domain
        storeAuthentication($user["code"], $user["id"], $_POST["client_id"], base64_encode(random_bytes(16)), $_POST["scope"], time() + 3600 );
        header(
            "Location: $callback_url?".
                "code=".$user["code"].
                "&state=".$_POST["state"]
            ,
            true,
            302
        );
        exit;
    } else {
        echo "Invalid username and/or password.<br>";
    }
}

display_login_form(array_merge($_GET, $_SESSION["oidc"]));
