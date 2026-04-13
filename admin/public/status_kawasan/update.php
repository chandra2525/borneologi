<?php
require "../../app/core/session.php";
require "../../app/core/csrf.php";

require "../../app/config/database.php";
require "../../app/models/StatusKawasan.php";

secureSessionStart();
verifyCsrfToken();

$statusKawasanModel = new StatusKawasan($pdo);

$data = [
    "kode" => $_POST["kode"],
    "nama" => $_POST["nama"],
    "deskripsi" => $_POST["deskripsi"],
    "urutan" => $_POST["urutan"],
    "is_active" => $_POST["is_active"],
    "updated_by" => $_SESSION["user_id"]
];

$statusKawasanModel->update($_POST["id"], $data);

header("Location: index.php?success=updated");