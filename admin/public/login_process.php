<?php

require "../app/core/session.php";
require "../app/core/csrf.php";
require "../app/config/database.php";
require "../app/core/auth.php";
require "../app/core/rate_limiter.php";
require_once "../app/core/activity_log.php";

secureSessionStart();

verifyCsrfToken();

// if(!isset($_POST['csrf_token']) || !verifyCsrfToken($_POST['csrf_token']))
// {
// die("Invalid CSRF token");
// }

$username = $_POST['username'];
$password = $_POST['password'];

if (tooManyAttempts($pdo, $username)) {
    logActivity(
        $pdo,
        'LOGIN_FAILED',
        'Login',
        null,
        't_users',
        null,
        null,
        'Login ditolak karena terlalu banyak percobaan login',
        'FAILED'
    );

    // die("Too many login attempts. Try again later.");
    header("Location: login.php?error=too_many_attempts");
    exit;
}

if (login($pdo, $username, $password)) {
    clearAttempts($pdo, $username);

    logActivity(
        $pdo,
        'LOGIN',
        'Login',
        null,
        't_users',
        null,
        null,
        'User berhasil login',
        'SUCCESS'
    );

    header("Location: index.php");
    exit;
} else {
    recordLoginAttempt($pdo, $username);

    logActivity(
        $pdo,
        'LOGIN_FAILED',
        'Login',
        null,
        't_users',
        null,
        null,
        'Percobaan login gagal untuk username: ' . $username,
        'FAILED'
    );

    header("Location: login.php?error=1");
    exit;
}
