<?php

ini_set('display_errors', '1');
ini_set('log_errors_max_len', '-1');


// microsoft-specific config
$conf = json_decode(file_get_contents(__DIR__."/config.ms.json"), true);

include __DIR__ . '/auth-callback.php';