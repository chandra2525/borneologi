<?php
require "../../app/core/session.php";
require "../../app/core/csrf.php";

require "../../app/config/database.php";
require "../../app/models/Petani.php";

secureSessionStart();
verifyCsrfToken();

$foto_profil_petani = null;

if (isset($_FILES['foto_profil_petani']) && $_FILES['foto_profil_petani']['error'] == 0) {

    $uploadDir = "../../uploads/petani/";

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $ext = pathinfo($_FILES['foto_profil_petani']['name'], PATHINFO_EXTENSION);

    $fileName = time() . '_' . uniqid() . '.' . $ext;

    $allowed = ['jpg', 'jpeg', 'png', 'webp'];

    if (!in_array(strtolower($ext), $allowed)) {
        die("Format file tidak didukung");
    }

    if ($_FILES['foto_profil_petani']['size'] > 2 * 1024 * 1024) {
        die("Ukuran file maksimal 2MB");
    }

    move_uploaded_file(
        $_FILES['foto_profil_petani']['tmp_name'],
        $uploadDir . $fileName
    );

    $foto_profil_petani = $fileName;
}

$petani = new Petani($pdo);

$data = [
    "nik" => $_POST["nik"],
    "no_kk" => $_POST["no_kk"],
    "nama_lengkap" => $_POST["nama_lengkap"],
    "nama_panggilan" => $_POST["nama_panggilan"],
    "jenis_kelamin" => $_POST["jenis_kelamin"],
    "tanggal_lahir" => $_POST["tanggal_lahir"],
    "nomor_hp" => $_POST["nomor_hp"],
    "id_desa" => $_POST["id_desa"],
    "alamat" => $_POST["alamat"],
    "status_petani" => $_POST["status_petani"],
    "foto_profil_petani" => $foto_profil_petani,
    "is_active" => $_POST["is_active"],
    "created_by" => $_SESSION["user_id"]
];

$petani->create($data);

header("Location: index.php?success=created");
exit;