<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/Role.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Roles', 'delete');

$roleModel = new Role($pdo);

$oldData = $roleModel->findById($_POST["id"]);
$result = $roleModel->softDelete($_POST["id"], $_SESSION["user_id"]);

if ($result) {
    logActivity(
        $pdo,
        'DELETE',
        'Roles',
        $_POST["id"],
        'm_roles',
        $oldData,
        null,
        'Menghapus data Roles',
        'SUCCESS'
    );
}

// header("Location: index.php");
header("Location: index.php?success=deleted");