<?php

require_once "../app/core/session.php";
require_once "../app/core/csrf.php";
require_once "../app/core/activity_log.php";
require_once "../app/config/database.php";

secureSessionStart();

// Logout hanya boleh melalui POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

// Validasi CSRF
verifyCsrfToken();

logActivity(
    $pdo,
    'LOGOUT',
    'Logout',
    null,
    't_users',
    null,
    null,
    'User melakukan logout',
    'SUCCESS'
);

// Hapus seluruh data session
$_SESSION = [];

// Ambil parameter cookie session
$params = session_get_cookie_params();

// Hapus cookie session
if (ini_get("session.use_cookies")) {
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Hancurkan session
session_destroy();

// Mencegah halaman admin disimpan oleh browser/proxy
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Kembali ke login
header("Location: login.php");
exit;
