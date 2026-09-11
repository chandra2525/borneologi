<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/models/User.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Pengguna', 'create');
verifyCsrfToken();

$model = new User($pdo);

$password = $_POST["password"] ?? '';

if (empty($password)) {
    header("Location: index.php?error=password_required");
    exit;
}

$data = [
    "id_role" => $_POST["id_role"],
    "username" => $_POST["username"],
    "email" => $_POST["email"],
    "password_hash" => password_hash($password, PASSWORD_ARGON2ID),
    "nama_lengkap" => $_POST["nama_lengkap"],
    "nomor_hp" => $_POST["nomor_hp"],
    "is_active" => $_POST["is_active"],
    "created_by" => $_SESSION["user_id"]
];

$newId = $model->create($data);

if ($newId) {
    logActivity(
        $pdo,
        'CREATE',
        'Pengguna',
        $newId,
        't_users',
        null,
        $data,
        'Menambahkan data pengguna',
        'SUCCESS'
    );
}

header("Location: index.php?success=created");