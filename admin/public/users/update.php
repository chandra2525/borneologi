<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/models/User.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Pengguna', 'update');
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

$oldData = $userModel->findById($_POST["id"]);
$result = $userModel->update($_POST["id"], $data);

if ($result) {
    $newData = $userModel->findById($_POST["id"]);
    logActivity(
        $pdo,
        'UPDATE',
        'Pengguna',
        $_POST["id"],
        't_users',
        $oldData,
        $newData,
        'Mengubah data pengguna',
        'SUCCESS'
    );
}

header("Location: index.php?success=updated");
