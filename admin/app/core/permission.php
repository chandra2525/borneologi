<?php

function can($menu, $action)
{

    global $pdo;

    $role = $_SESSION['role_id'];

    $stmt = $pdo->prepare("
        SELECT *
        FROM m_role_menu_access r
        JOIN m_menus m ON m.id=r.id_menu
        WHERE r.id_role=? 
        AND m.kode=?
        AND r.can_" . $action . "=1
    ");

    $stmt->execute([$role, $menu]);

    return $stmt->fetch() ? true : false;

}