<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/models/PetaniKelompok.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

echo ("asd");
error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

Permission::authorize($pdo, 'Detail Kelompok CBO', 'create');
verifyCsrfToken();

$petaniKelompok = new PetaniKelompok($pdo);
echo ($_POST["tanggal_keluar"]);
echo ($_POST["tanggal_keluar"]);

$data = [
    "id_petani" => $_POST["id_petani"],
    "id_kelompok_tani" => $_POST["id_kelompok_tani"],
    "id_jabatan_kelompok" => $_POST["id_jabatan_kelompok"],
    "tanggal_gabung" => $_POST["tanggal_gabung"],
    "tanggal_keluar" => $_POST["tanggal_keluar"] == '' ? '0000-00-00' : $_POST["tanggal_keluar"],
    // "is_pengurus" => $_POST["is_pengurus"],
    // "is_pengurus" => isset($_POST["is_pengurus"]) ? 1 : 0,
    "keterangan" => $_POST["keterangan"],
    "is_active" => $_POST["is_active"],
    "created_by" => $_SESSION["user_id"]
];

$newId = $petaniKelompok->create($data);

if ($newId) {
    logActivity(
        $pdo,
        'CREATE',
        'Detail Kelompok CBO',
        $newId,
        't_petani_kelompok',
        null,
        $data,
        'Menambahkan data Detail Kelompok CBO',
        'SUCCESS'
    );
}

header(
    'Location: detail_list.php?id_kelompok_tani='
    . $_POST["id_kelompok_tani"]
    . '&success=created'
);