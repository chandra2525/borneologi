<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/models/Negara.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Negara', 'create');
verifyCsrfToken();

$negara = new Negara($pdo);

$data = [
    "kode" => $_POST["kode"],
    "nama" => $_POST["nama"],
    "is_active" => $_POST["is_active"],
    "created_by" => $_SESSION["user_id"]
];

$newId = $negara->create($data);

if ($newId) {
    logActivity(
        $pdo,
        'CREATE',
        'Negara',
        $newId,
        'm_negara',
        null,
        $data,
        'Menambahkan data Negara',
        'SUCCESS'
    );
}

header("Location: index.php?success=created");