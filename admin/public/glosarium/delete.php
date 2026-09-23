<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/Glosarium.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Glosarium', 'delete');

secureSessionStart();

$glosariumModel = new Glosarium($pdo);

$oldData = $glosariumModel->findById($_POST["id"]);
$result = $glosariumModel->softDelete($_POST["id"], $_SESSION["user_id"]);

if ($result) {
    logActivity(
        $pdo,
        'DELETE',
        'Glosarium',
        $_POST["id"],
        't_glosarium',
        $oldData,
        null,
        'Menghapus data Glosarium',
        'SUCCESS'
    );
}

header("Location: index.php?success=deleted");