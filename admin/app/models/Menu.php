<?php

class Menu
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        $sql = "SELECT m.*, p.nama as parent_name
                FROM m_menus m
                LEFT JOIN m_menus p ON m.id_parent = p.id
                WHERE m.deleted_at IS NULL
                ORDER BY m.urutan ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
        // return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getParents()
    {
        $sql = "SELECT id,nama FROM m_menus 
                WHERE id_parent IS NULL 
                AND deleted_at IS NULL
                ORDER BY urutan";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();

        // return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($id)
    {
        $sql = "SELECT *
            FROM m_menus
            WHERE id = :id
            AND deleted_at IS NULL
            LIMIT 1";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            "id" => $id
        ]);

        return $stmt->fetch();
        // $stmt = $this->pdo->prepare("
        //     SELECT * FROM m_menus
        //     WHERE id = ? AND deleted_at IS NULL
        // ");

        // $stmt->execute([$id]);

        // return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {

        $sql = "INSERT INTO m_menus
            (kode,nama,path,icon,id_parent,urutan,is_active,created_by)
            VALUES
            (:kode,:nama,:path,:icon,:id_parent,:urutan,:is_active,:created_by)";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute($data);
    }

    public function update($id, $data)
    {
        $sql = "UPDATE m_menus
                SET
                kode=:kode,
                nama=:nama,
                path=:path,
                icon=:icon,
                id_parent=:id_parent,
                urutan=:urutan,
                is_active=:is_active,
                updated_by=:updated_by
                WHERE id=:id";

        $stmt = $this->pdo->prepare($sql);

        $data["id"] = $id;

        return $stmt->execute($data);
    }

    public function softDelete($id, $user_id)
    {
        $sql = "UPDATE m_menus
            SET deleted_at = NOW(),
            deleted_by = :user
            WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            "id" => $id,
            "user" => $user_id
        ]);

        // $stmt = $this->pdo->prepare("
        //     UPDATE m_menus
        //     SET deleted_at=NOW(),
        //         deleted_by=?
        //     WHERE id=?
        // ");

        // return $stmt->execute([$user,$id]);
    }

    public function generateKode()
    {
        $sql = "SELECT kode 
            FROM m_menus 
            -- WHERE deleted_at IS NULL
            ORDER BY id DESC 
            LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch();

        if (!$row) {
            return "MNU001";
        }

        $lastKode = $row['kode'];
        $number = (int) substr($lastKode, 3);
        $number++;

        return "MNU" . str_pad($number, 3, "0", STR_PAD_LEFT);
    }
}