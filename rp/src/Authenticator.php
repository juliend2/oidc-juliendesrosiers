<?php

namespace App;

class Authenticator {
    private $_responseType, $_scope, $_clientID, $_redirectUri, $_stateToken, $_nonce;

    public function __construct($params) {
        $this->_responseType = "code";
        $this->_scope = ["openid", ...($params["scope"] ?? [])];
        $this->_clientID = $params["client_id"];
        $this->_redirectUri = $params["redirect_uri"];
        $this->_nonceSessionKey = "_authenticator_nonce";
        $this->_nonce = $params["nonce"] ?? $this->_generateNonce();
        $this->_stateToken = $params["state"] ?? bin2hex(random_bytes(16));
    }

    public function sendRequest($url) {
        header(
            "Location: $url?".
                "response_type=$this->_responseType".
                "&scope=".urlencode(implode(" ", $this->_scope)).
                "&client_id=".$this->_clientID.
                "&state=$this->_stateToken".
                "&redirect_uri=".urlencode($this->_redirectUri).
                // TODO: See section 15.5.2 for "nonce" field: https://openid.net/specs/openid-connect-core-1_0.html#NonceNotes
                "&nonce=". bin2hex(random_bytes(8)),
            true,
            302
        );
        exit;
    }

    private function _generateNonce() {
        $_SESSION[$this->_nonceSessionKey] = bin2hex(random_bytes(8));
        return $_SESSION[$this->_nonceSessionKey];
    }
}
