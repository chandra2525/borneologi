<?php

require "../../app/core/session.php";
secureSessionStart();

require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/controllers/BankBenihController.php";
require "../../app/core/permission.php";
require "../../app/core/activity_log.php";

Permission::authorize(
    $pdo,
    'Bank Benih',
    'create'
);

verifyCsrfToken();


/*
|--------------------------------------------------------------------------
| UPLOAD FOTO
|--------------------------------------------------------------------------
*/

$foto_benih = null;

if (
    isset($_FILES['foto_benih']) &&
    $_FILES['foto_benih']['error'] !== UPLOAD_ERR_NO_FILE
) {

    if (
        $_FILES['foto_benih']['error'] !== UPLOAD_ERR_OK
    ) {
        die("Upload foto gagal.");
    }


    $uploadDir =
        "../../uploads/bank_benih/";


    if (!is_dir($uploadDir)) {

        mkdir(
            $uploadDir,
            0755,
            true
        );

    }


    $allowed = [
        'jpg',
        'jpeg',
        'png',
        'webp'
    ];


    $ext = strtolower(
        pathinfo(
            $_FILES['foto_benih']['name'],
            PATHINFO_EXTENSION
        )
    );


    if (!in_array($ext, $allowed, true)) {

        die(
            "Format foto tidak didukung."
        );

    }


    if (
        $_FILES['foto_benih']['size']
        > 2 * 1024 * 1024
    ) {

        die(
            "Ukuran foto maksimal 2 MB."
        );

    }


    $fileName =
        date('YmdHis')
        . '_'
        . bin2hex(random_bytes(6))
        . '.'
        . $ext;


    if (
        !move_uploaded_file(
            $_FILES['foto_benih']['tmp_name'],
            $uploadDir . $fileName
        )
    ) {

        die(
            "Gagal menyimpan foto."
        );

    }


    $foto_benih = $fileName;
}


/*
|--------------------------------------------------------------------------
| DATA
|--------------------------------------------------------------------------
*/

$data = $_POST;

$data['foto_benih'] = $foto_benih;


/*
|--------------------------------------------------------------------------
| CREATE
|--------------------------------------------------------------------------
*/

$controller =
    new BankBenihController($pdo);


$newId = $controller->store(
    $data,
    $_SESSION['user_id']
);

if ($newId) {

    $bankBenihModel =
        new BankBenih($pdo);

    $newData =
        $bankBenihModel->findById($newId);

    logActivity(
        $pdo,
        'CREATE',
        'Bank Benih',
        $newId,
        't_bank_benih',
        null,
        $newData,
        'Menambahkan data Bank Benih',
        'SUCCESS'
    );

    header(
        "Location: index.php?success=created"
    );

    exit;
}


header(
    "Location: create.php?error=create_failed"
);

exit;