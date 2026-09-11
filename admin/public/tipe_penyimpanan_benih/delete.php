<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/TipePenyimpananBenih.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Tipe Penyimpanan Benih', 'delete');

$tipePenyimpananBenihModel = new TipePenyimpananBenih($pdo);

$oldData = $tipePenyimpananBenihModel->findById($_POST["id"]);
$result = $tipePenyimpananBenihModel->softDelete($_POST["id"], $_SESSION["user_id"]);

if ($result) {
    logActivity(
        $pdo,
        'DELETE',
        'Tipe Penyimpanan Benih',
        $_POST["id"],
        'm_tipe_penyimpanan_benih',
        $oldData,
        null,
        'Menghapus data Tipe Penyimpanan Benih',
        'SUCCESS'
    );
}

header("Location: index.php?success=deleted");