<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/models/Petani.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Penerima Manfaat', 'update');
verifyCsrfToken();

$petaniModel = new Petani($pdo);

$foto_profil_petani = $_POST['foto_lama'] ?? null;

if (
    isset($_FILES['foto_profil_petani']) &&
    $_FILES['foto_profil_petani']['error'] == 0
) {

    $uploadDir = "../../uploads/petani/";

    // Buat folder jika belum ada
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileTmp  = $_FILES['foto_profil_petani']['tmp_name'];
    $fileName = $_FILES['foto_profil_petani']['name'];
    $fileSize = $_FILES['foto_profil_petani']['size'];

    $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    /* Validasi ekstensi */
    $allowed = ['jpg', 'jpeg', 'png', 'webp'];

    if (!in_array($ext, $allowed)) {
        die("Format file tidak didukung");
    }

    /* Validasi ukuran max 2MB */
    if ($fileSize > 2 * 1024 * 1024) {
        die("Ukuran file maksimal 2MB");
    }
    /* Generate nama file baru */
    $newFileName = time() . '_' . uniqid() . '.' . $ext;

    /* Upload file */
    move_uploaded_file($fileTmp, $uploadDir . $newFileName);

    /* Hapus foto lama jika ada */
    if (
        !empty($_POST['foto_lama']) &&
        file_exists($uploadDir . $_POST['foto_lama'])
    ) {
        unlink($uploadDir . $_POST['foto_lama']);
    }

    $foto_profil_petani = $newFileName;
}

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
    "updated_by" => $_SESSION["user_id"]
];

$oldData = $petaniModel->findById($_POST["id"]);
$result = $petaniModel->update($_POST["id"], $data);

if ($result) {
    $newData = $petaniModel->findById($_POST["id"]);
    logActivity(
        $pdo,
        'UPDATE',
        'Penerima Manfaat',
        $_POST["id"],
        't_petani',
        $oldData,
        $newData,
        'Mengubah data Penerima Manfaat',
        'SUCCESS'
    );
}

header("Location: index.php?success=updated");