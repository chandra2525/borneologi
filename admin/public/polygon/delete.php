<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/Polygon.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Polygon', 'delete');

$polygonModel = new Polygon($pdo);

$oldData = $polygonModel->findById($_POST["id"]);
$result = $polygonModel->softDelete($_POST["id"], $_SESSION["user_id"]);

if ($result) {
    logActivity(
        $pdo,
        'DELETE',
        'Polygon',
        $_POST["id"],
        't_polygon',
        $oldData,
        null,
        'Menghapus data Polygon',
        'SUCCESS'
    );
}

header("Location: index.php?success=deleted");