<?php

require "../../app/core/session.php";
secureSessionStart();
require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/models/Menu.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Menus', 'create');
verifyCsrfToken();

$menu = new Menu($pdo);

$data = [
    "kode" => $_POST["kode"],
    "nama" => $_POST["nama"],
    "path" => $_POST["path"],
    "icon" => $_POST["icon"],
    "id_parent" => $_POST["id_parent"] ?: null,
    "urutan" => $_POST["urutan"],
    "is_active" => $_POST["is_active"],
    "created_by" => $_SESSION["user_id"]
];

$newId = $menu->create($data);

if ($newId) {
    logActivity(
        $pdo,
        'CREATE',
        'Menus',
        $newId,
        'm_menus',
        null,
        $data,
        'Menambahkan data Menus',
        'SUCCESS'
    );
}

// header("Location:index.php");
header("Location: index.php?success=created");