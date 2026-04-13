<?php
require "../../app/core/session.php";
require "../../app/core/csrf.php";

require "../../app/config/database.php";
require "../../app/models/JenisPohon.php";

secureSessionStart();
verifyCsrfToken();

$jenisPohonModel = new JenisPohon($pdo);

$data = [
    "kode" => $_POST["kode"],
    "nama" => $_POST["nama"],
    "nama_latin" => $_POST["nama_latin"],
    "deskripsi" => $_POST["deskripsi"],
    "urutan" => $_POST["urutan"],
    "is_active" => $_POST["is_active"],
    "updated_by" => $_SESSION["user_id"]
];

$jenisPohonModel->update($_POST["id"], $data);

header("Location: index.php?success=updated");