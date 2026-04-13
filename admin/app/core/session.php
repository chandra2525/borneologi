<?php

function secureSessionStart()
{

    $cookieParams = session_get_cookie_params();

    session_set_cookie_params([
        'lifetime' => 0,
        'path' => $cookieParams["path"],
        'domain' => $cookieParams["domain"],
        'secure' => isset($_SERVER['HTTPS']),
        'httponly' => true,
        'samesite' => 'Strict'
    ]);

    ini_set('session.use_strict_mode', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_secure', isset($_SERVER['HTTPS']));

    session_start();

    if (!isset($_SESSION['initiated'])) {
        session_regenerate_id(true);
        $_SESSION['initiated'] = true;
    }

}

$timeout = 1800; // 30 menit

if (
    isset($_SESSION['last_activity']) &&
    (time() - $_SESSION['last_activity'] > $timeout)
) {
    session_unset();
    session_destroy();
}

$_SESSION['last_activity'] = time();

$fingerprint = hash(
    'sha256',
    $_SERVER['HTTP_USER_AGENT']
);

if (!isset($_SESSION['fingerprint'])) {
    $_SESSION['fingerprint'] = $fingerprint;
}

if ($_SESSION['fingerprint'] !== $fingerprint) {
    session_unset();
    session_destroy();
}