<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/User.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Pengguna', 'delete');

$userModel = new User($pdo);

$oldData = $userModel->findById($_POST["id"]);
$result = $userModel->softDelete($_POST["id"], $_SESSION["user_id"]);

if ($result) {
    logActivity(
        $pdo,
        'DELETE',
        'Pengguna',
        $_POST["id"],
        't_users',
        $oldData,
        null,
        'Menghapus data pengguna',
        'SUCCESS'
    );
}

header("Location:index.php?success=deleted");