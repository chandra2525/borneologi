<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/AksesPerjalanan.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Akses Perjalanan', 'delete');

$aksesPerjalananModel = new AksesPerjalanan($pdo);

$oldData = $aksesPerjalananModel->findById($_POST["id"]);
$result = $aksesPerjalananModel->softDelete($_POST["id"], $_SESSION["user_id"]);

if ($result) {
    logActivity(
        $pdo,
        'DELETE',
        'Akses Perjalanan',
        $_POST["id"],
        'm_akses_perjalanan',
        $oldData,
        null,
        'Menghapus data Akses Perjalanan',
        'SUCCESS'
    );
}

// header("Location: index.php");
header("Location: index.php?success=deleted");