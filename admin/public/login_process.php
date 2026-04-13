<?php

require "../app/core/session.php";
require "../app/core/csrf.php";
require "../app/config/database.php";
require "../app/core/auth.php";
require "../app/core/rate_limiter.php";

secureSessionStart();

verifyCsrfToken();

// if(!isset($_POST['csrf_token']) || !verifyCsrfToken($_POST['csrf_token']))
// {
// die("Invalid CSRF token");
// }

$username = $_POST['username'];
$password = $_POST['password'];

if (login($pdo, $username, $password)) {
    clearAttempts($pdo, $username);
    header("Location: index.php");
    exit;
} else {
    recordLoginAttempt($pdo, $username);
    header("Location: login.php?error=1");
    exit;
}

if (tooManyAttempts($pdo, $username)) {
    die("Too many login attempts. Try again later.");
}