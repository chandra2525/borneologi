<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/models/Glosarium.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Glosarium', 'update');
verifyCsrfToken();

$glosariumModel = new Glosarium($pdo);

$gambar = $_POST['gambar_lama'] ?? null;

if (
    isset($_FILES['gambar']) &&
    $_FILES['gambar']['error'] == 0
) {

    $uploadDir = "../../uploads/glosarium/";

    // Buat folder jika belum ada
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileTmp  = $_FILES['gambar']['tmp_name'];
    $fileName = $_FILES['gambar']['name'];
    $fileSize = $_FILES['gambar']['size'];

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

    /* Hapus gambar lama jika ada */
    if (
        !empty($_POST['gambar_lama']) &&
        file_exists($uploadDir . $_POST['gambar_lama'])
    ) {
        unlink($uploadDir . $_POST['gambar_lama']);
    }

    $gambar = $newFileName;
}

$data = [
    "istilah" => $_POST["istilah"],
    "istilah_lain" => $_POST["istilah_lain"],
    "kategori" => $_POST["kategori"],
    "definisi_singkat" => $_POST["definisi_singkat"],
    "penjelasan_lengkap" => $_POST["penjelasan_lengkap"],
    "bahasa_asal" => $_POST["bahasa_asal"],
    "pengucapan" => $_POST["pengucapan"],
    "sumber" => $_POST["sumber"],
    "gambar" => $gambar,
    "is_active" => $_POST["is_active"],
    "updated_by" => $_SESSION["user_id"]
];

$oldData = $glosariumModel->findById($_POST["id"]);
$result = $glosariumModel->update($_POST["id"], $data);

if ($result) {
    $newData = $glosariumModel->findById($_POST["id"]);
    logActivity(
        $pdo,
        'UPDATE',
        'Glosarium',
        $_POST["id"],
        't_glosarium',
        $oldData,
        $newData,
        'Mengubah data Glosarium',
        'SUCCESS'
    );
}

header("Location: index.php?success=updated");