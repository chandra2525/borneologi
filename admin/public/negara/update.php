<?php
require "../../app/core/session.php";
require "../../app/core/csrf.php";

require "../../app/config/database.php";
require "../../app/models/Negara.php";

secureSessionStart();
verifyCsrfToken();

$negaraModel = new Negara($pdo);

$data = [
    "kode" => $_POST["kode"],
    "nama" => $_POST["nama"],
    "is_active" => $_POST["is_active"],
    "updated_by" => $_SESSION["user_id"]
];

$negaraModel->update($_POST["id"], $data);

header("Location: index.php?success=updated");