<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/Menu.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Menus', 'delete');

$menuModel = new Menu($pdo);

$oldData = $menuModel->findById($_POST["id"]);
$result = $menuModel->softDelete($_POST["id"], $_SESSION["user_id"]);

if ($result) {
    logActivity(
        $pdo,
        'DELETE',
        'Menus',
        $_POST["id"],
        'm_menus',
        $oldData,
        null,
        'Menghapus data Menus',
        'SUCCESS'
    );
}

header("Location: index.php?success=deleted");