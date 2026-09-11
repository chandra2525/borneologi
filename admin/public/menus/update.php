<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/models/Menu.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Menus', 'update');
verifyCsrfToken();

$menuModel = new Menu($pdo);

$data = [
    "kode" => $_POST["kode"],
    "nama" => $_POST["nama"],
    "path" => $_POST["path"],
    "icon" => $_POST["icon"],
    "id_parent" => $_POST["id_parent"] ?: null,
    "urutan" => $_POST["urutan"],
    "is_active" => $_POST["is_active"],
    "updated_by" => $_SESSION["user_id"]
];

$oldData = $menuModel->findById($_POST["id"]);
$result = $menuModel->update($_POST["id"], $data);

if ($result) {
    $newData = $menuModel->findById($_POST["id"]);
    logActivity(
        $pdo,
        'UPDATE',
        'Menus',
        $_POST["id"],
        'm_menus',
        $oldData,
        $newData,
        'Mengubah data Menus',
        'SUCCESS'
    );
}

// header("Location: index.php");
header("Location: index.php?success=updated");