CREATE TABLE IF NOT EXISTS access_tokens (
    -- Le token lui-même (la chaîne opaque générée par random_bytes)
    access_token TEXT PRIMARY KEY,
    
    -- L'identifiant unique de l'utilisateur (le "sub" dans OIDC)
    user_id TEXT NOT NULL,
    
    -- Le client (RP) qui a demandé ce token
    client_id TEXT NOT NULL,
    
    -- Les scopes accordés (ex: "openid profile email")
    -- On les stocke sous forme de chaîne séparée par des espaces
    scopes TEXT NOT NULL,

    -- short-lived code that allows us to get a token
    authorization_code TEXT NOT NULL,
    
    -- Date de création (utile pour le débug et le nettoyage)
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    
    -- Date d'expiration (en format ISO8601 pour SQLite : "YYYY-MM-DD HH:MM:SS")
    expires_at DATETIME NOT NULL
);

-- Index pour accélérer la suppression des tokens expirés
CREATE INDEX IF NOT EXISTS idx_token_expiry ON access_tokens(expires_at);
