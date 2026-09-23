<?php

require "../../app/core/session.php";
secureSessionStart();

require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/models/BankBenih.php";
require "../../app/core/permission.php";
require "../../app/core/activity_log.php";

Permission::authorize(
    $pdo,
    'Bank Benih',
    'delete'
);

verifyCsrfToken();


if (
    empty($_POST['id']) ||
    !is_numeric($_POST['id'])
) {

    die("ID data tidak valid.");

}


$id =
    (int)$_POST['id'];

$user_id =
    $_SESSION['user_id'];


$model =
    new BankBenih($pdo);


/*
|--------------------------------------------------------------------------
| DATA LAMA
|--------------------------------------------------------------------------
*/

$oldData =
    $model->findById($id);


if (!$oldData) {

    die("Data Bank Benih tidak ditemukan.");

}


/*
|--------------------------------------------------------------------------
| SOFT DELETE
|--------------------------------------------------------------------------
*/

$result =
    $model->softDelete(
        $id,
        $user_id
    );


/*
|--------------------------------------------------------------------------
| LOG
|--------------------------------------------------------------------------
*/

if ($result) {

    logActivity(

        $pdo,

        'DELETE',

        'Bank Benih',

        $id,

        't_bank_benih',

        $oldData,

        null,

        'Menghapus data Bank Benih',

        'SUCCESS'

    );

}


header(
    "Location: index.php?success=deleted"
);

exit;