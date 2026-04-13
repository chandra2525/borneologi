<?php

class PetaniKelompok
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        $sql = "SELECT pk.*, p.nama_lengkap as nama_petani, kt.nama_kelompok as nama_kelompok_tani, jk.nama as nama_jabatan_kelompok
            FROM t_petani_kelompok pk
            LEFT JOIN t_petani p ON p.id=pk.id_petani
            LEFT JOIN t_kelompok_tani kt ON kt.id=pk.id_kelompok_tani
            LEFT JOIN m_jabatan_kelompok jk ON jk.id=pk.id_jabatan_kelompok
            WHERE pk.deleted_at IS NULL
            ORDER BY pk.id ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function findById($id)
    {
        $sql = "SELECT *
            FROM t_petani_kelompok
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

        $sql = "INSERT INTO t_petani_kelompok
            (id_petani,id_kelompok_tani,id_jabatan_kelompok,tanggal_gabung,tanggal_keluar,is_pengurus,keterangan,is_active,created_by)
            VALUES
            (:id_petani,:id_kelompok_tani,:id_jabatan_kelompok,:tanggal_gabung,:tanggal_keluar,:is_pengurus,:keterangan,:is_active,:created_by)";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute($data);

    }

    public function update($id, $data)
    {
        $sql = "UPDATE t_petani_kelompok
            SET
            id_petani=:id_petani,
            id_kelompok_tani=:id_kelompok_tani,
            id_jabatan_kelompok=:id_jabatan_kelompok,
            tanggal_gabung=:tanggal_gabung,
            tanggal_keluar=:tanggal_keluar,
            is_pengurus=:is_pengurus,
            keterangan=:keterangan,
            is_active=:is_active,
            updated_by=:updated_by
            WHERE id=:id";

        $stmt = $this->pdo->prepare($sql);

        $data["id"] = $id;

        return $stmt->execute($data);
    }

    public function softDelete($id, $user_id)
    {
        $sql = "UPDATE t_petani_kelompok
            SET deleted_at = NOW(),
            deleted_by = :user
            WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            "id" => $id,
            "user" => $user_id
        ]);
    }

    public function getPetani()
    {
        $sql = "SELECT id,nama_lengkap FROM t_petani
                WHERE deleted_at IS NULL
                ORDER BY id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getKelompokTani()
    {
        $sql = "SELECT id,nama_kelompok FROM t_kelompok_tani
                WHERE deleted_at IS NULL
                ORDER BY id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getJabatanKelompok()
    {
        $sql = "SELECT id,nama FROM m_jabatan_kelompok
                WHERE deleted_at IS NULL
                ORDER BY id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

}