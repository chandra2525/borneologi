<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/TipePenanaman.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Tipe Penanaman', 'delete');

$tipePenanamanModel = new TipePenanaman($pdo);

$oldData = $tipePenanamanModel->findById($_POST["id"]);
$result = $tipePenanamanModel->softDelete($_POST["id"], $_SESSION["user_id"]);

if ($result) {
    logActivity(
        $pdo,
        'DELETE',
        'Tipe Penanaman',
        $_POST["id"],
        'm_tipe_penanaman',
        $oldData,
        null,
        'Menghapus data Tipe Penanaman',
        'SUCCESS'
    );
}

header("Location: index.php?success=deleted");