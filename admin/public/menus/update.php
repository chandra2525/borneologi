<?php
require "../../app/core/session.php";
require "../../app/core/csrf.php";

require "../../app/config/database.php";
require "../../app/models/Menu.php";

secureSessionStart();
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

$menuModel->update($_POST["id"], $data);

// header("Location: index.php");
header("Location: index.php?success=updated");