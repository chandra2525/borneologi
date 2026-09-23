<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/models/Glosarium.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Glosarium', 'create');
verifyCsrfToken();

$gambar = null;

if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {

    $uploadDir = "../../uploads/glosarium/";

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);

    $fileName = time() . '_' . uniqid() . '.' . $ext;

    $allowed = ['jpg', 'jpeg', 'png', 'webp'];

    if (!in_array(strtolower($ext), $allowed)) {
        die("Format file tidak didukung");
    }

    if ($_FILES['gambar']['size'] > 2 * 1024 * 1024) {
        die("Ukuran file maksimal 2MB");
    }

    move_uploaded_file(
        $_FILES['gambar']['tmp_name'],
        $uploadDir . $fileName
    );

    $gambar = $fileName;
}

$glosarium = new Glosarium($pdo);

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
    "created_by" => $_SESSION["user_id"]
];

$newId = $glosarium->create($data);

if ($newId) {
    logActivity(
        $pdo,
        'CREATE',
        'Glosarium',
        $newId,
        't_glosarium',
        null,
        $data,
        'Menambahkan data Glosarium',
        'SUCCESS'
    );
}

header("Location: index.php?success=created");