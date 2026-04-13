<?php

require "../../app/core/session.php";
require "../../app/core/csrf.php";

require "../../app/config/database.php";
require "../../app/models/Menu.php";

secureSessionStart();
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

$menu->create($data);

// header("Location:index.php");
header("Location: index.php?success=created");