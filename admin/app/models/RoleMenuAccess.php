<?php

class RoleMenuAccess
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getByRole($role_id)
    {
        $stmt = $this->pdo->prepare("
            SELECT 
                m.id AS menu_id,
                m.nama AS menu_name,
                rma.*
            FROM m_menus m

            LEFT JOIN m_role_menu_access rma 
                ON rma.id_menu = m.id 
                AND rma.id_role = ?

            WHERE m.deleted_at IS NULL

            ORDER BY m.id ASC
        ");

        $stmt->execute([$role_id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function save($role_id, $menu_id, $data)
    {
        /*
        |--------------------------------------------------------------------------
        | Cek apakah permission sudah ada
        |--------------------------------------------------------------------------
        */

        $stmt = $this->pdo->prepare("
            SELECT id
            FROM m_role_menu_access
            WHERE id_role = ?
              AND id_menu = ?
            LIMIT 1
        ");

        $stmt->execute([
            $role_id,
            $menu_id
        ]);

        $existing = $stmt->fetch(PDO::FETCH_ASSOC);

        /*
        |--------------------------------------------------------------------------
        | UPDATE
        |--------------------------------------------------------------------------
        */

        if ($existing) {

            $update = $this->pdo->prepare("
                UPDATE m_role_menu_access
                SET
                    can_view = ?,
                    can_create = ?,
                    can_update = ?,
                    can_delete = ?,
                    can_approve = ?,
                    can_export = ?,
                    is_active = 1,
                    deleted_at = NULL
                WHERE id = ?
            ");

            return $update->execute([
                $data['view'],
                $data['create'],
                $data['update'],
                $data['delete'],
                $data['approve'],
                $data['export'],
                $existing['id']
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | INSERT
        |--------------------------------------------------------------------------
        */

        $insert = $this->pdo->prepare("
            INSERT INTO m_role_menu_access
            (
                id_role,
                id_menu,
                can_view,
                can_create,
                can_update,
                can_delete,
                can_approve,
                can_export,
                is_active
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1)
        ");

        return $insert->execute([
            $role_id,
            $menu_id,
            $data['view'],
            $data['create'],
            $data['update'],
            $data['delete'],
            $data['approve'],
            $data['export']
        ]);
    }
}