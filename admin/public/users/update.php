<?php
require "../../app/core/session.php";
require "../../app/core/csrf.php";

require "../../app/config/database.php";
require "../../app/models/User.php";

secureSessionStart();
verifyCsrfToken();

$userModel = new User($pdo);

$data = [
    "id_role" => $_POST["id_role"],
    "username" => $_POST["username"],
    "email" => $_POST["email"],
    "nama_lengkap" => $_POST["nama_lengkap"],
    "nomor_hp" => $_POST["nomor_hp"],
    "is_active" => $_POST["is_active"],
    "updated_by" => $_SESSION["user_id"]
];

if (!empty($_POST["password"])) {
    $data["password_hash"] = password_hash($_POST["password"], PASSWORD_ARGON2ID);
}

$userModel->update($_POST["id"], $data);

header("Location: index.php?success=updated");