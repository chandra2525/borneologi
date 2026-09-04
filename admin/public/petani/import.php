<?php

require_once '../../app/config/database.php';
require_once '../../app/controllers/PetaniController.php';
require_once '../../app/core/session.php';
require_once '../../app/core/csrf.php';
require_once "../../app/core/auth.php";

secureSessionStart();

checkAuth("non_dashboard");

$controller = new PetaniController($pdo);

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    die("User tidak ditemukan.");
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

try {

    if (!isset($_FILES['file_excel'])) {
        throw new Exception(
            "Silahkan pilih file Excel terlebih dahulu."
        );
    }

    $result = $controller->importExcel(
        $_FILES['file_excel'],
        $user_id
    );

    $_SESSION['import_result'] = $result;

    header('Location: index.php?success=imported');
    exit;

} catch (Exception $e) {

    $_SESSION['import_error'] = $e->getMessage();

    header('Location: index.php?error=import');
    exit;
}