<?php
require "../../app/core/session.php";
require "../../app/core/csrf.php";

require "../../app/config/database.php";
require "../../app/models/JabatanKelompok.php";

secureSessionStart();
verifyCsrfToken();

$jabatanKelompokModel = new JabatanKelompok($pdo);

$data = [
    "kode" => $_POST["kode"],
    "nama" => $_POST["nama"],
    // "is_pengurus" => $_POST["is_pengurus"],
    "is_pengurus" => isset($_POST["is_pengurus"]) ? 1 : 0,
    "deskripsi" => $_POST["deskripsi"],
    "urutan" => $_POST["urutan"],
    "is_active" => $_POST["is_active"],
    "updated_by" => $_SESSION["user_id"]
];

$jabatanKelompokModel->update($_POST["id"], $data);

header("Location: index.php?success=updated");