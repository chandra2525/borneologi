<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/models/Negara.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Negara', 'update');
verifyCsrfToken();

$negaraModel = new Negara($pdo);

$data = [
    "kode" => $_POST["kode"],
    "nama" => $_POST["nama"],
    "is_active" => $_POST["is_active"],
    "updated_by" => $_SESSION["user_id"]
];

$oldData = $negaraModel->findById($_POST["id"]);
$result = $negaraModel->update($_POST["id"], $data);

if ($result) {
    $newData = $negaraModel->findById($_POST["id"]);
    logActivity(
        $pdo,
        'UPDATE',
        'Negara',
        $_POST["id"],
        'm_negara',
        $oldData,
        $newData,
        'Mengubah data Negara',
        'SUCCESS'
    );
}

header("Location: index.php?success=updated");