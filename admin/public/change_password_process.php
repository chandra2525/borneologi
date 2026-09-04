<?php

require_once "../app/config/database.php";
require_once "../app/core/session.php";
require_once "../app/core/csrf.php";
require_once "../app/core/auth.php";
require_once "../app/core/security.php";
require_once "../app/models/User.php";

secureSessionStart();

checkAuth("dashboard");

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        "success" => false,
        "message" => "Request tidak valid."
    ]);
    exit;
}

verifyCsrfToken();

/*
|--------------------------------------------------------------------------
| Generate CSRF token baru
|--------------------------------------------------------------------------
*/
$newCsrfToken = csrfToken();

if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        "success" => false,
        "message" => "Session tidak ditemukan. Silakan login kembali.",
        "csrf_token" => $newCsrfToken
    ]);
    exit;
}

$userId = $_SESSION['user_id'];
$currentPassword = $_POST['current_password'] ?? '';
$newPassword = $_POST['new_password'] ?? '';
$confirmPassword = $_POST['confirm_password'] ?? '';

/*
|--------------------------------------------------------------------------
| Validasi field
|--------------------------------------------------------------------------
*/
if (
    $currentPassword === '' ||
    $newPassword === '' ||
    $confirmPassword === ''
) {
    echo json_encode([
        "success" => false,
        "message" => "Semua field password wajib diisi.",
        "csrf_token" => $newCsrfToken
    ]);
    exit;
}

/*
|--------------------------------------------------------------------------
| Validasi panjang password
|--------------------------------------------------------------------------
*/
if (strlen($newPassword) < 8) {
    echo json_encode([
        "success" => false,
        "message" => "Password baru minimal 8 karakter.",
        "csrf_token" => $newCsrfToken
    ]);
    exit;
}

/*
|--------------------------------------------------------------------------
| Validasi konfirmasi password
|--------------------------------------------------------------------------
*/
if ($newPassword !== $confirmPassword) {
    echo json_encode([
        "success" => false,
        "message" => "Password baru dan konfirmasi password tidak sama.",
        "csrf_token" => $newCsrfToken
    ]);
    exit;
}

/*
|--------------------------------------------------------------------------
| Ambil user
|--------------------------------------------------------------------------
*/
$userModel = new User($pdo);
$user = $userModel->findById($userId);

if (!$user) {
    $_SESSION = [];
    session_destroy();
    echo json_encode([
        "success" => false,
        "message" => "User tidak ditemukan. Silakan login kembali."
    ]);
    exit;
}

/*
|--------------------------------------------------------------------------
| Verifikasi password lama
|--------------------------------------------------------------------------
*/
if (!passwordVerify($currentPassword, $user['password_hash'])) {
    echo json_encode([
        "success" => false,
        "message" => "Password lama yang Anda masukkan salah.",
        "csrf_token" => $newCsrfToken
    ]);
    exit;
}

/*
|--------------------------------------------------------------------------
| Password baru tidak boleh sama dengan password lama
|--------------------------------------------------------------------------
*/
if (passwordVerify($newPassword, $user['password_hash'])) {
    echo json_encode([
        "success" => false,
        "message" => "Password baru tidak boleh sama dengan password lama.",
        "csrf_token" => $newCsrfToken
    ]);
    exit;
}

/*
|--------------------------------------------------------------------------
| Hash password baru
|--------------------------------------------------------------------------
*/
$newPasswordHash = passwordHash($newPassword);

/*
|--------------------------------------------------------------------------
| Update password
|--------------------------------------------------------------------------
*/
if (!$userModel->updatePassword($userId, $newPasswordHash)) {
    echo json_encode([
        "success" => false,
        "message" => "Password gagal diubah. Silakan coba kembali.",
        "csrf_token" => $newCsrfToken
    ]);
    exit;
}

/*
|--------------------------------------------------------------------------
| Regenerate session
|--------------------------------------------------------------------------
*/
session_regenerate_id(true);

/*
|--------------------------------------------------------------------------
| Generate CSRF token baru setelah session regenerate
|--------------------------------------------------------------------------
*/
$newCsrfToken = csrfToken();

/*
|--------------------------------------------------------------------------
| Berhasil
|--------------------------------------------------------------------------
*/
echo json_encode([
    "success" => true,
    "message" => "Password berhasil diubah.",
    "csrf_token" => $newCsrfToken
]);

exit;