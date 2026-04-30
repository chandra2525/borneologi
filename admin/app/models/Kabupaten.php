<?php

class Kabupaten
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        // $sql = "SELECT *
        //     FROM m_kabupaten
        //     WHERE deleted_at IS NULL
        //     ORDER BY id ASC";
        
        $sql = "SELECT k.*, p.nama_provinsi as nama_provinsi
            FROM m_kabupaten k
            LEFT JOIN m_provinsi p ON p.id=k.id_provinsi
            WHERE k.deleted_at IS NULL
            ORDER BY k.id DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function findById($id)
    {
        $sql = "SELECT *
            FROM m_kabupaten
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
        $sql = "INSERT INTO m_kabupaten
            (id_provinsi,kode_kabupaten,nama_kabupaten,tipe,is_active,created_by)
            VALUES
            (:id_provinsi,:kode_kabupaten,:nama_kabupaten,:tipe,:is_active,:created_by)";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute($data);
    }

    public function update($id, $data)
    {
        $sql = "UPDATE m_kabupaten
            SET
            id_provinsi=:id_provinsi,
            kode_kabupaten=:kode_kabupaten,
            nama_kabupaten=:nama_kabupaten,
            tipe=:tipe,
            is_active=:is_active,
            updated_by=:updated_by
            WHERE id=:id";

        $stmt = $this->pdo->prepare($sql);

        $data["id"] = $id;

        return $stmt->execute($data);
    }

    public function softDelete($id, $user_id)
    {
        $sql = "UPDATE m_kabupaten
            SET deleted_at = NOW(),
            deleted_by = :user
            WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            "id" => $id,
            "user" => $user_id
        ]);
    }

    public function getProvinsi()
    {
        $sql = "SELECT id,nama_provinsi,kode_provinsi FROM m_provinsi
                WHERE deleted_at IS NULL
                ORDER BY nama_provinsi ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

}