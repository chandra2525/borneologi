<?php

class Kecamatan
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        // $sql = "SELECT *
        //     FROM m_kecamatan
        //     WHERE deleted_at IS NULL
        //     ORDER BY id ASC";
        
        $sql = "SELECT k.*, p.nama_kabupaten as nama_kabupaten
            FROM m_kecamatan k
            LEFT JOIN m_kabupaten p ON p.id=k.id_kabupaten
            WHERE k.deleted_at IS NULL
            ORDER BY k.id DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function findById($id)
    {
        $sql = "SELECT *
            FROM m_kecamatan
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
        $sql = "INSERT INTO m_kecamatan
            (id_kabupaten,kode_kecamatan,nama_kecamatan,is_active,created_by)
            VALUES
            (:id_kabupaten,:kode_kecamatan,:nama_kecamatan,:is_active,:created_by)";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute($data);
    }

    public function update($id, $data)
    {
        $sql = "UPDATE m_kecamatan
            SET
            id_kabupaten=:id_kabupaten,
            kode_kecamatan=:kode_kecamatan,
            nama_kecamatan=:nama_kecamatan,
            is_active=:is_active,
            updated_by=:updated_by
            WHERE id=:id";

        $stmt = $this->pdo->prepare($sql);

        $data["id"] = $id;

        return $stmt->execute($data);
    }

    public function softDelete($id, $user_id)
    {
        $sql = "UPDATE m_kecamatan
            SET deleted_at = NOW(),
            deleted_by = :user
            WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            "id" => $id,
            "user" => $user_id
        ]);
    }

    public function getKabupaten()
    {
        $sql = "SELECT id,nama_kabupaten,kode_kabupaten FROM m_kabupaten
                WHERE deleted_at IS NULL
                ORDER BY nama_kabupaten ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

}