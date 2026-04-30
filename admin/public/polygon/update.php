<?php
require "../../app/core/session.php";
require "../../app/core/csrf.php";

require "../../app/config/database.php";
require "../../app/models/Polygon.php";

secureSessionStart();
verifyCsrfToken();

$polygonModel = new Polygon($pdo);

$data = [
    "kode_polygon" => $_POST["kode_polygon"],
    "nama_polygon" => $_POST["nama_polygon"],
    // "geom_area" => $_POST["geom_area"],
    "relasi_id" => $_POST["relasi_id"],
    "relasi_tipe" => $_POST["relasi_tipe"],
    "is_active" => $_POST["is_active"],
    "updated_by" => $_SESSION["user_id"]
];

$polygonModel->update($_POST["id"], $data);

header("Location: index.php?success=updated");