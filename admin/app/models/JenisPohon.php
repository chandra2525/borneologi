<?php

class JenisPohon
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        $sql = "SELECT *
            FROM m_jenis_pohon
            WHERE deleted_at IS NULL
            ORDER BY urutan ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function findById($id)
    {
        $sql = "SELECT *
            FROM m_jenis_pohon
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

        $sql = "INSERT INTO m_jenis_pohon
            (kode,nama,nama_latin,deskripsi,urutan,is_active,created_by)
            VALUES
            (:kode,:nama,:nama_latin,:deskripsi,:urutan,:is_active,:created_by)";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute($data);

    }

    public function update($id, $data)
    {
        $sql = "UPDATE m_jenis_pohon
            SET
            kode=:kode,
            nama=:nama,
            nama_latin=:nama_latin,
            deskripsi=:deskripsi,
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
        $sql = "UPDATE m_jenis_pohon
            SET deleted_at = NOW(),
            deleted_by = :user
            WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            "id" => $id,
            "user" => $user_id
        ]);
    }

    public function generateKode()
    {
        $sql = "SELECT kode 
            FROM m_jenis_pohon 
            -- WHERE deleted_at IS NULL
            ORDER BY id DESC 
            LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch();

        if (!$row) {
            return "JEP001";
        }

        $lastKode = $row['kode'];
        $number = (int) substr($lastKode, 3);
        $number++;

        return "JEP" . str_pad($number, 3, "0", STR_PAD_LEFT);
    }

}