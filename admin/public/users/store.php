<?php

require "../../app/core/session.php";
require "../../app/core/csrf.php";

require "../../app/config/database.php";
require "../../app/models/User.php";

secureSessionStart();
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

$model->create($data);

header("Location: index.php?success=created");