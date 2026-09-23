<?php

require "../app/core/session.php";

secureSessionStart();

session_regenerate_id(true);

$_SESSION['initiated'] = true;
$_SESSION['last_activity'] = time();

$fingerprint = hash(
    'sha256',
    $_SERVER['HTTP_USER_AGENT'] ?? ''
);

if (!isset($_SESSION['fingerprint'])) {
    $_SESSION['fingerprint'] = $fingerprint;
}

$_SESSION['user_id'] = 5;
$_SESSION['username'] = 'borneo';
$_SESSION['role'] = 1;

header("Location: index.php");
exit;