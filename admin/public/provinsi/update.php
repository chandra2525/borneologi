<?php
require "../../app/core/session.php";
require "../../app/core/csrf.php";

require "../../app/config/database.php";
require "../../app/models/Provinsi.php";

secureSessionStart();
verifyCsrfToken();

$provinsiModel = new Provinsi($pdo);

$data = [
    "kode_provinsi" => $_POST["kode_provinsi"],
    "nama_provinsi" => $_POST["nama_provinsi"],
    "is_active" => $_POST["is_active"],
    "updated_by" => $_SESSION["user_id"]
];

$provinsiModel->update($_POST["id"], $data);

// header("Location: index.php");
header("Location: index.php?success=updated");