<?php

class KelompokTani
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        $sql = "SELECT kt.*, kk.nama as nama_kategori_kelompok, d.nama_desa as nama_desa, ap.nama as nama_akses_perjalanan, kj.nama as nama_kondisi_jalan
            FROM t_kelompok_tani kt
            LEFT JOIN m_kategori_kelompok kk ON kk.id=kt.id_kategori_kelompok
            LEFT JOIN m_desa d ON d.id=kt.id_desa
            LEFT JOIN m_akses_perjalanan ap ON ap.id=kt.id_akses_perjalanan
            LEFT JOIN m_kondisi_jalan kj ON kj.id=kt.id_kondisi_jalan
            WHERE kt.deleted_at IS NULL
            ORDER BY kt.id ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function findById($id)
    {
        $sql = "SELECT *
            FROM t_kelompok_tani
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

        $sql = "INSERT INTO t_kelompok_tani
            (kode_kelompok,nama_kelompok,id_kategori_kelompok,id_desa,alamat,id_akses_perjalanan,id_kondisi_jalan,tahun_bentuk,nomor_sk,tanggal_sk,status_kelompok,is_active,created_by)
            VALUES
            (:kode_kelompok,:nama_kelompok,:id_kategori_kelompok,:id_desa,:alamat,:id_akses_perjalanan,:id_kondisi_jalan,:tahun_bentuk,:nomor_sk,:tanggal_sk,:status_kelompok,:is_active,:created_by)";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute($data);

    }

    public function update($id, $data)
    {
        $sql = "UPDATE t_kelompok_tani
            SET
            kode_kelompok=:kode_kelompok,
            nama_kelompok=:nama_kelompok,
            id_kategori_kelompok=:id_kategori_kelompok,
            id_desa=:id_desa,
            alamat=:alamat,
            id_akses_perjalanan=:id_akses_perjalanan,
            id_kondisi_jalan=:id_kondisi_jalan,
            tahun_bentuk=:tahun_bentuk,
            nomor_sk=:nomor_sk,
            tanggal_sk=:tanggal_sk,
            status_kelompok=:status_kelompok,
            is_active=:is_active,
            updated_by=:updated_by
            WHERE id=:id";

        $stmt = $this->pdo->prepare($sql);

        $data["id"] = $id;

        return $stmt->execute($data);
    }

    public function softDelete($id, $user_id)
    {
        $sql = "UPDATE t_kelompok_tani
            SET deleted_at = NOW(),
            deleted_by = :user
            WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            "id" => $id,
            "user" => $user_id
        ]);
    }

    public function getKategoriKelompok()
    {
        $sql = "SELECT id,nama FROM m_kategori_kelompok
                WHERE deleted_at IS NULL
                ORDER BY id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
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

    public function getAksesPerjalanan()
    {
        $sql = "SELECT id,nama FROM m_akses_perjalanan
                WHERE deleted_at IS NULL
                ORDER BY id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getKondisiJalan()
    {
        $sql = "SELECT id,nama FROM m_kondisi_jalan
                WHERE deleted_at IS NULL
                ORDER BY id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function generateKode()
    {
        $sql = "SELECT kode_kelompok 
            FROM t_kelompok_tani 
            -- WHERE deleted_at IS NULL
            ORDER BY id DESC 
            LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch();

        if (!$row) {
            return "KET001";
        }

        $lastKode = $row['kode_kelompok'];
        $number = (int) substr($lastKode, 3);
        $number++;

        return "KET" . str_pad($number, 3, "0", STR_PAD_LEFT);
    }

}