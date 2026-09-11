<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/Negara.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Negara', 'delete');

$negaraModel = new Negara($pdo);

$oldData = $negaraModel->findById($_POST["id"]);
$result = $negaraModel->softDelete($_POST["id"], $_SESSION["user_id"]);

if ($result) {
    logActivity(
        $pdo,
        'DELETE',
        'Negara',
        $_POST["id"],
        'm_negara',
        $oldData,
        null,
        'Menghapus data Negara',
        'SUCCESS'
    );
}

header("Location: index.php?success=deleted");