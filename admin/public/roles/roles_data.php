<?php

require '../../app/config/database.php';
require '../../app/models/Role.php';

$roleModel = new Role($pdo);

$roles = $roleModel->getAll();

$data = [];

foreach ($roles as $r) {
    $data[] = [
        "kode" => $r["kode"],
        "nama" => $r["nama"],
        "deskripsi" => $r["deskripsi"],
        "urutan" => $r["urutan"],
        "status" => $r["is_active"] ? "Aktif" : "Nonaktif",
        "aksi" => '
            <button class="btn btn-primary btn-edit"
            data-id="' . $r["id"] . '">Edit</button>

            <button class="btn btn-danger btn-delete"
            data-id="' . $r["id"] . '">Delete</button>

            '
    ];
}

echo json_encode(["data" => $data]);