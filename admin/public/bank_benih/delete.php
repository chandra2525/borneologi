<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/BankBenih.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Bank Benih', 'delete');

secureSessionStart();

$bankBenihModel = new BankBenih($pdo);

$oldData = $bankBenihModel->findById($_POST["id"]);
$result = $bankBenihModel->softDelete($_POST["id"], $_SESSION["user_id"]);

if ($result) {
    logActivity(
        $pdo,
        'DELETE',
        'Bank Benih',
        $_POST["id"],
        't_bank_benih',
        $oldData,
        null,
        'Menghapus data Bank Benih',
        'SUCCESS'
    );
}

header("Location: index.php?success=deleted");