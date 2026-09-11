<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/Petani.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Penerima Manfaat', 'delete');

$petaniModel = new Petani($pdo);

$oldData = $petaniModel->findById($_POST["id"]);
$result = $petaniModel->softDelete($_POST["id"], $_SESSION["user_id"]);

if ($result) {
    logActivity(
        $pdo,
        'DELETE',
        'Penerima Manfaat',
        $_POST["id"],
        't_petani',
        $oldData,
        null,
        'Menghapus data Penerima Manfaat',
        'SUCCESS'
    );
}

header("Location: index.php?success=deleted");