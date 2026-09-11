<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/Kabupaten.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Kabupaten', 'delete');

$kabupatenModel = new Kabupaten($pdo);

$oldData = $kabupatenModel->findById($_POST["id"]);
$result = $kabupatenModel->softDelete($_POST["id"], $_SESSION["user_id"]);

if ($result) {
    logActivity(
        $pdo,
        'DELETE',
        'Kabupaten',
        $_POST["id"],
        'm_kabupaten',
        $oldData,
        null,
        'Menghapus data Kabupaten',
        'SUCCESS'
    );
}

header("Location: index.php?success=deleted");