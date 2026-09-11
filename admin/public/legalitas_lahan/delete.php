<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/LegalitasLahan.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Legalitas Lahan', 'delete');

$legalitasLahanModel = new LegalitasLahan($pdo);

$oldData = $legalitasLahanModel->findById($_POST["id"]);
$result = $legalitasLahanModel->softDelete($_POST["id"], $_SESSION["user_id"]);

if ($result) {
    logActivity(
        $pdo,
        'DELETE',
        'Legalitas Lahan',
        $_POST["id"],
        'm_legalitas_lahan',
        $oldData,
        null,
        'Menghapus data Legalitas Lahan',
        'SUCCESS'
    );
}

header("Location: index.php?success=deleted");