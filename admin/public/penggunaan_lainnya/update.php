<?php
require "../../app/core/session.php";
require "../../app/core/csrf.php";

require "../../app/config/database.php";
require "../../app/models/PenggunaanLainnya.php";

secureSessionStart();
verifyCsrfToken();

$penggunaanLainnyaModel = new PenggunaanLainnya($pdo);

$data = [
    "kode" => $_POST["kode"],
    "nama" => $_POST["nama"],
    "deskripsi" => $_POST["deskripsi"],
    "urutan" => $_POST["urutan"],
    "is_active" => $_POST["is_active"],
    "updated_by" => $_SESSION["user_id"]
];

$penggunaanLainnyaModel->update($_POST["id"], $data);

header("Location: index.php?success=updated");