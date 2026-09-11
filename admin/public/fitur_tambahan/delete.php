<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/FiturTambahan.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Fitur Tambahan', 'delete');

secureSessionStart();

$fiturTambahanModel = new FiturTambahan($pdo);

$oldData = $fiturTambahanModel->findById($_POST["id"]);
$result = $fiturTambahanModel->softDelete($_POST["id"], $_SESSION["user_id"]);

if ($result) {
    logActivity(
        $pdo,
        'DELETE',
        'Fitur Tambahan',
        $_POST["id"],
        'm_fitur_tambahan',
        $oldData,
        null,
        'Menghapus data Fitur Tambahan',
        'SUCCESS'
    );
}

header("Location: index.php?success=deleted");