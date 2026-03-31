<?php

if (!$_SESSION['USER']) {
    header("Location: https://rp.local/login?return_to=/profile", true, 302);
    exit;
}
