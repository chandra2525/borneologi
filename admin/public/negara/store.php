<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/models/Negara.php";
require "../../app/core/permission.php";

Permission::authorize($pdo, 'Negara', 'create');
verifyCsrfToken();

$negara = new Negara($pdo);

$data = [
    "kode" => $_POST["kode"],
    "nama" => $_POST["nama"],
    "is_active" => $_POST["is_active"],
    "created_by" => $_SESSION["user_id"]
];

$negara->create($data);

header("Location: index.php?success=created");