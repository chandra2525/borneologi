<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/Tanah.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Tanah', 'delete');

$tanahModel = new Tanah($pdo);

$oldData = $tanahModel->findById($_POST["id"]);
$result = $tanahModel->softDelete($_POST["id"], $_SESSION["user_id"]);

if ($result) {
    logActivity(
        $pdo,
        'DELETE',
        'Tanah',
        $_POST["id"],
        't_tanah',
        $oldData,
        null,
        'Menghapus data Tanah',
        'SUCCESS'
    );
}

header("Location: index.php?success=deleted");