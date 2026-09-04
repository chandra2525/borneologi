<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/models/Desa.php";
require "../../app/core/permission.php";

Permission::authorize($pdo, 'Desa', 'update');
verifyCsrfToken();

$desaModel = new Desa($pdo);

$data = [
    "id_kecamatan" => $_POST["id_kecamatan"],
    "kode_desa" => $_POST["kode_desa"],
    "nama_desa" => $_POST["nama_desa"],
    "is_active" => $_POST["is_active"],
    "updated_by" => $_SESSION["user_id"]
];

$desaModel->update($_POST["id"], $data);

header("Location: index.php?success=updated");