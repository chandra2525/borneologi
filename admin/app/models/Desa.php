<?php

class Desa
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        // $sql = "SELECT *
        //     FROM m_desa
        //     WHERE deleted_at IS NULL
        //     ORDER BY id ASC";
        
        $sql = "SELECT k.*, p.nama_kecamatan as nama_kecamatan
            FROM m_desa k
            LEFT JOIN m_kecamatan p ON p.id=k.id_kecamatan
            WHERE k.deleted_at IS NULL
            ORDER BY k.id DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function findById($id)
    {
        $sql = "SELECT *
            FROM m_desa
            WHERE id = :id
            AND deleted_at IS NULL
            LIMIT 1";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            "id" => $id
        ]);

        return $stmt->fetch();
    }

    public function create($data)
    {
        $sql = "INSERT INTO m_desa
            (id_kecamatan,kode_desa,nama_desa,is_active,created_by)
            VALUES
            (:id_kecamatan,:kode_desa,:nama_desa,:is_active,:created_by)";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute($data);
    }

    public function update($id, $data)
    {
        $sql = "UPDATE m_desa
            SET
            id_kecamatan=:id_kecamatan,
            kode_desa=:kode_desa,
            nama_desa=:nama_desa,
            is_active=:is_active,
            updated_by=:updated_by
            WHERE id=:id";

        $stmt = $this->pdo->prepare($sql);

        $data["id"] = $id;

        return $stmt->execute($data);
    }

    public function softDelete($id, $user_id)
    {
        $sql = "UPDATE m_desa
            SET deleted_at = NOW(),
            deleted_by = :user
            WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            "id" => $id,
            "user" => $user_id
        ]);
    }

    public function getKecamatan()
    {
        $sql = "SELECT id,nama_kecamatan,kode_kecamatan FROM m_kecamatan
                WHERE deleted_at IS NULL
                ORDER BY nama_kecamatan ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

}