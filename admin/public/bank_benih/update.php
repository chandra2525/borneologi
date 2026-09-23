<?php

require "../../app/core/session.php";
require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/controllers/BankBenihController.php";
require "../../app/core/permission.php";
require "../../app/core/activity_log.php";

secureSessionStart();


/*
|--------------------------------------------------------------------------
| PERMISSION
|--------------------------------------------------------------------------
*/

Permission::authorize(
    $pdo,
    'Bank Benih',
    'update'
);


/*
|--------------------------------------------------------------------------
| CSRF
|--------------------------------------------------------------------------
*/

verifyCsrfToken();


/*
|--------------------------------------------------------------------------
| VALIDASI ID
|--------------------------------------------------------------------------
*/

if (
    !isset($_POST['id']) ||
    !is_numeric($_POST['id'])
) {

    header(
        "Location: index.php?error=invalid_id"
    );

    exit;
}


$id = (int)$_POST['id'];


/*
|--------------------------------------------------------------------------
| MODEL
|--------------------------------------------------------------------------
*/

$bankBenihModel =
    new BankBenih($pdo);


/*
|--------------------------------------------------------------------------
| CEK DATA
|--------------------------------------------------------------------------
*/

$oldData =
    $bankBenihModel->findById($id);


if (!$oldData) {

    header(
        "Location: index.php?error=not_found"
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| FOTO LAMA
|--------------------------------------------------------------------------
*/

$fotoLama =
    $oldData['foto_benih'] ?? null;

$fotoBenih =
    $fotoLama;

$fotoBaru =
    null;


/*
|--------------------------------------------------------------------------
| UPLOAD FOTO BARU
|--------------------------------------------------------------------------
*/

if (
    isset($_FILES['foto_benih']) &&
    $_FILES['foto_benih']['error']
        !== UPLOAD_ERR_NO_FILE
) {


    /*
     * Error upload
     */

    if (
        $_FILES['foto_benih']['error']
        !== UPLOAD_ERR_OK
    ) {

        header(
            "Location: edit.php?id={$id}&error=upload_failed"
        );

        exit;
    }


    /*
     * Maksimal 2 MB
     */

    if (
        $_FILES['foto_benih']['size']
        > 2 * 1024 * 1024
    ) {

        header(
            "Location: edit.php?id={$id}&error=file_too_large"
        );

        exit;
    }


    /*
     * MIME validation
     */

    $tmpFile =
        $_FILES['foto_benih']['tmp_name'];


    $finfo =
        new finfo(FILEINFO_MIME_TYPE);


    $mime =
        $finfo->file($tmpFile);


    $allowedMime = [

        'image/jpeg' => 'jpg',

        'image/png' => 'png',

        'image/webp' => 'webp'

    ];


    if (
        !isset($allowedMime[$mime])
    ) {

        header(
            "Location: edit.php?id={$id}&error=invalid_file"
        );

        exit;
    }


    /*
     * Pastikan benar-benar image
     */

    $imageInfo =
        @getimagesize($tmpFile);


    if ($imageInfo === false) {

        header(
            "Location: edit.php?id={$id}&error=invalid_image"
        );

        exit;
    }


    /*
     * Folder upload
     */

    $uploadDir =
        "../../uploads/bank_benih/";


    if (!is_dir($uploadDir)) {

        if (
            !mkdir(
                $uploadDir,
                0755,
                true
            )
        ) {

            header(
                "Location: edit.php?id={$id}&error=upload_folder"
            );

            exit;
        }
    }


    /*
     * Generate nama file
     */

    $extension =
        $allowedMime[$mime];


    $fileName =
        date('YmdHis')
        . '_'
        . bin2hex(
            random_bytes(8)
        )
        . '.'
        . $extension;


    $destination =
        $uploadDir . $fileName;


    /*
     * Simpan foto
     */

    if (
        !move_uploaded_file(
            $tmpFile,
            $destination
        )
    ) {

        header(
            "Location: edit.php?id={$id}&error=upload_failed"
        );

        exit;
    }


    $fotoBaru =
        $fileName;

    $fotoBenih =
        $fileName;
}


/*
|--------------------------------------------------------------------------
| DATA POST
|--------------------------------------------------------------------------
*/

$data = $_POST;


/*
 * Pastikan foto menggunakan
 * nama file yang benar
 */

$data['foto_benih'] =
    $fotoBenih;


/*
|--------------------------------------------------------------------------
| UPDATE MELALUI CONTROLLER
|--------------------------------------------------------------------------
*/

$controller =
    new BankBenihController($pdo);


try {

    $result =
        $controller->update(
            $id,
            $data,
            $_SESSION['user_id']
        );


} catch (Throwable $e) {

    /*
     * Jika database gagal,
     * hapus foto baru.
     */

    if (
        $fotoBaru !== null
    ) {

        $newFile =
            "../../uploads/bank_benih/"
            . $fotoBaru;


        if (
            file_exists($newFile)
        ) {

            unlink($newFile);

        }

    }


    error_log(
        "Bank Benih UPDATE ERROR: "
        . $e->getMessage()
    );


    header(
        "Location: edit.php?id={$id}&error=update_failed"
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| UPDATE GAGAL
|--------------------------------------------------------------------------
*/

if (!$result) {

    /*
     * Hapus foto baru jika database gagal
     */

    if (
        $fotoBaru !== null
    ) {

        $newFile =
            "../../uploads/bank_benih/"
            . $fotoBaru;


        if (
            file_exists($newFile)
        ) {

            unlink($newFile);

        }

    }


    header(
        "Location: edit.php?id={$id}&error=update_failed"
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| HAPUS FOTO LAMA
|--------------------------------------------------------------------------
|
| Hanya dilakukan setelah database
| berhasil diperbarui.
|
*/

if (
    $fotoBaru !== null &&
    !empty($fotoLama)
) {

    $oldFile =
        "../../uploads/bank_benih/"
        . basename($fotoLama);


    if (
        file_exists($oldFile)
    ) {

        unlink($oldFile);

    }
}


/*
|--------------------------------------------------------------------------
| DATA BARU
|--------------------------------------------------------------------------
*/

$newData =
    $bankBenihModel->findById($id);


/*
|--------------------------------------------------------------------------
| ACTIVITY LOG
|--------------------------------------------------------------------------
*/

logActivity(

    $pdo,

    'UPDATE',

    'Bank Benih',

    $id,

    't_bank_benih',

    $oldData,

    $newData,

    'Mengubah data Bank Benih',

    'SUCCESS'

);


/*
|--------------------------------------------------------------------------
| REDIRECT
|--------------------------------------------------------------------------
*/

header(
    "Location: index.php?success=updated"
);

exit;