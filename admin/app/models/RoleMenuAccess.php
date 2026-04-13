<?php

class RoleMenuAccess {

    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getByRole($role_id)
    {
        $stmt = $this->pdo->prepare("SELECT 
                m.id as menu_id,
                m.nama as menu_name,
                rma.*
            FROM m_menus m
            LEFT JOIN m_role_menu_access rma 
                ON rma.id_menu = m.id AND rma.id_role = ?
            WHERE m.deleted_at IS NULL
            ORDER BY m.urutan ASC
        ");

        $stmt->execute([$role_id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function save($role_id, $menu_id, $data)
    {

        $stmt = $this->pdo->prepare("
            SELECT id 
            FROM m_role_menu_access 
            WHERE id_role=? AND id_menu=?
        ");

        $stmt->execute([$role_id,$menu_id]);

        if($stmt->fetch()){

            $update = $this->pdo->prepare("
                UPDATE m_role_menu_access
                SET 
                can_view=?,
                can_create=?,
                can_update=?,
                can_delete=?,
                can_approve=?,
                can_export=?
                WHERE id_role=? AND id_menu=?
            ");

            $update->execute([
                $data['view'],
                $data['create'],
                $data['update'],
                $data['delete'],
                $data['approve'],
                $data['export'],
                $role_id,
                $menu_id
            ]);

        }else{

            $insert = $this->pdo->prepare("
                INSERT INTO m_role_menu_access
                (id_role,id_menu,can_view,can_create,can_update,can_delete,can_approve,can_export)
                VALUES (?,?,?,?,?,?,?,?)
            ");

            $insert->execute([
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

}