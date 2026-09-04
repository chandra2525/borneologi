<?php

require "../../app/core/session.php";
secureSessionStart();

require "../../app/config/database.php";

require "../../app/core/csrf.php";
require "../../app/models/RoleMenuAccess.php";
require "../../app/core/permission.php";

Permission::authorize($pdo, 'Roles', 'update');

verifyCsrfToken();

$model = new RoleMenuAccess($pdo);

$role_id = filter_input(
    INPUT_POST,
    'role_id',
    FILTER_VALIDATE_INT
);

if (!$role_id) {
    http_response_code(400);
    exit('Role tidak valid.');
}

$access = $_POST['access'] ?? [];

/*
|--------------------------------------------------------------------------
| Ambil semua menu
|--------------------------------------------------------------------------
| Kita tidak boleh hanya mengandalkan $_POST['access']
| karena checkbox yang tidak dicentang tidak dikirim browser.
*/

$stmt = $pdo->prepare("
    SELECT id
    FROM m_menus
    WHERE deleted_at IS NULL
");

$stmt->execute();

$menus = $stmt->fetchAll(PDO::FETCH_COLUMN);

/*
|--------------------------------------------------------------------------
| Simpan permission setiap menu
|--------------------------------------------------------------------------
*/

foreach ($menus as $menu_id) {

    $perm = $access[$menu_id] ?? [];

    $data = [
        'view'    => isset($perm['view']) ? 1 : 0,
        'create'  => isset($perm['create']) ? 1 : 0,
        'update'  => isset($perm['update']) ? 1 : 0,
        'delete'  => isset($perm['delete']) ? 1 : 0,
        'approve' => isset($perm['approve']) ? 1 : 0,
        'export'  => isset($perm['export']) ? 1 : 0,
    ];

    $model->save(
        (int) $role_id,
        (int) $menu_id,
        $data
    );
}

header("Location: index.php?success=updated");
exit;