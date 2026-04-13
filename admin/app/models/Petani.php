<?php

class Petani
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        $sql = "SELECT p.*, d.nama_desa as nama_desa
            FROM t_petani p
            LEFT JOIN m_desa d ON d.id=p.id_desa
            WHERE p.deleted_at IS NULL
            ORDER BY p.id ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function findById($id)
    {
        $sql = "SELECT *
            FROM t_petani
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

        $sql = "INSERT INTO t_petani
            (nik,no_kk,nama_lengkap,nama_panggilan,jenis_kelamin,tanggal_lahir,nomor_hp,id_desa,alamat,status_petani,is_active,created_by)
            VALUES
            (:nik,:no_kk,:nama_lengkap,:nama_panggilan,:jenis_kelamin,:tanggal_lahir,:nomor_hp,:id_desa,:alamat,:status_petani,:is_active,:created_by)";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute($data);

    }

    public function update($id, $data)
    {
        $sql = "UPDATE t_petani
            SET
            nik=:nik,
            no_kk=:no_kk,
            nama_lengkap=:nama_lengkap,
            nama_panggilan=:nama_panggilan,
            jenis_kelamin=:jenis_kelamin,
            tanggal_lahir=:tanggal_lahir,
            nomor_hp=:nomor_hp,
            id_desa=:id_desa,
            alamat=:alamat,
            status_petani=:status_petani,
            is_active=:is_active,
            updated_by=:updated_by
            WHERE id=:id";

        $stmt = $this->pdo->prepare($sql);

        $data["id"] = $id;

        return $stmt->execute($data);
    }

    public function softDelete($id, $user_id)
    {
        $sql = "UPDATE t_petani
            SET deleted_at = NOW(),
            deleted_by = :user
            WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            "id" => $id,
            "user" => $user_id
        ]);
    }

    public function getDesa()
    {
        $sql = "SELECT id,nama_desa FROM m_desa
                WHERE deleted_at IS NULL
                ORDER BY id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

}