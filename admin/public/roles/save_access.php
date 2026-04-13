<?php
require "../../app/core/session.php";
secureSessionStart();

require "../../app/config/database.php";
require "../../app/core/csrf.php";
require "../../app/models/RoleMenuAccess.php";

verifyCsrfToken();

$model = new RoleMenuAccess($pdo);

$role_id = $_POST['role_id'];

foreach ($_POST['access'] as $menu_id => $perm) {

    $data = [
        "view" => $perm['view'] ?? 0,
        "create" => $perm['create'] ?? 0,
        "update" => $perm['update'] ?? 0,
        "delete" => $perm['delete'] ?? 0,
        "approve" => $perm['approve'] ?? 0,
        "export" => $perm['export'] ?? 0,
    ];

    $model->save($role_id, $menu_id, $data);

}

// header("Location: index.php?role_id=" . $role_id . "&success=1");
header("Location: index.php?success=updated");