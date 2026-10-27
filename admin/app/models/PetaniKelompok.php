<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

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
            ORDER BY pk.id DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }
    public function getByKelompokTani($id_kelompok_tani)
    {
        $sql = "
        SELECT
            pk.id,
            pk.tanggal_gabung,
            pk.tanggal_keluar,
            pk.id_jabatan_kelompok,
            pe.id AS id_petani,
            pe.nik,
            pe.no_kk,
            pe.nama_lengkap,
            pe.nama_panggilan,
            pe.jenis_kelamin,
            pe.tanggal_lahir,
            pe.nomor_hp,
            pe.foto_profil_petani,
            pe.status_petani,
            jk.nama AS nama_jabatan_kelompok,
            de.nama_desa,
            pk.keterangan,
            pk.is_active
            FROM t_petani_kelompok pk
            INNER JOIN t_petani pe
                ON pe.id = pk.id_petani
            LEFT JOIN m_jabatan_kelompok jk
                ON jk.id = pk.id_jabatan_kelompok
            LEFT JOIN m_desa de
                ON de.id = pe.id_desa
            WHERE pk.id_kelompok_tani = :id_kelompok_tani
            AND pk.deleted_at IS NULL
            AND pe.deleted_at IS NULL
            ORDER BY pk.id DESC
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id_kelompok_tani' => $id_kelompok_tani
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($id)
    {
        $sql = "SELECT
            pk.id,
            pk.id_kelompok_tani,
            pk.id_petani,
            pk.id_jabatan_kelompok,
            pk.tanggal_gabung,
            pk.tanggal_keluar,
            pe.nik,
            pe.no_kk,
            pe.nama_lengkap,
            pe.nama_panggilan,
            pe.jenis_kelamin,
            pe.tanggal_lahir,
            pe.nomor_hp,
            pe.id_desa,
            pe.alamat,
            pe.foto_profil_petani,
            jk.nama AS nama_jabatan_kelompok,
            de.nama_desa
            FROM t_petani_kelompok pk
            INNER JOIN t_petani pe
                ON pe.id = pk.id_petani
            LEFT JOIN m_jabatan_kelompok jk
                ON jk.id = pk.id_jabatan_kelompok
            LEFT JOIN m_desa de
                ON de.id = pe.id_desa
            WHERE pk.id = :id
            AND pk.deleted_at IS NULL
            AND pe.deleted_at IS NULL
            LIMIT 1";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            "id" => $id
        ]);

        return $stmt->fetch();
    }

    // Fungsi sbelumnya di delete.php PetaniKelompok
    // public function findById($id)
    // {
    //     $sql = "SELECT *
    //         FROM t_petani_kelompok
    //         WHERE id = :id
    //         AND deleted_at IS NULL
    //         LIMIT 1";

    //     $stmt = $this->pdo->prepare($sql);

    //     $stmt->execute([
    //         "id" => $id
    //     ]);

    //     return $stmt->fetch();
    // }

    public function create($data)
    {

        $sql = "INSERT INTO t_petani_kelompok
            (id_petani,id_kelompok_tani,id_jabatan_kelompok,tanggal_gabung,tanggal_keluar,keterangan,is_active,created_by)
            VALUES
            (:id_petani,:id_kelompok_tani,:id_jabatan_kelompok,:tanggal_gabung,:tanggal_keluar,:keterangan,:is_active,:created_by)";

        $stmt = $this->pdo->prepare($sql);

        // return $stmt->execute($data);
        if ($stmt->execute($data)) {
            return $this->pdo->lastInsertId();
        }

        return false;
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
    public function getPetani($id = null)
    {
        $sql = "
            SELECT
                pe.id,
                pe.nik,
                pe.no_kk,
                pe.nama_lengkap,
                pe.nama_panggilan,
                pe.jenis_kelamin,
                pe.tanggal_lahir,
                pe.nomor_hp,
                pe.alamat,
                pe.status_petani,
                de.nama_desa,
                pe.foto_profil_petani
            FROM t_petani pe
            LEFT JOIN m_desa de
                ON de.id = pe.id_desa
            WHERE pe.deleted_at IS NULL
            AND pe.is_active = 1
        ";
        $params = [];
        if ($id !== null) {
            $sql .= "
                AND id = :id
            ";
            $params['id'] = $id;
        }
        $sql .= "
            ORDER BY pe.id DESC
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        if ($id !== null) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // public function getPetani()
    // {
    //     $sql = "SELECT id,nama_lengkap FROM t_petani
    //             WHERE deleted_at IS NULL
    //             ORDER BY id DESC";

    //     $stmt = $this->pdo->prepare($sql);
    //     $stmt->execute();

    //     return $stmt->fetchAll();
    // }

    public function getKelompokTani()
    {
        $sql = "SELECT id,nama_kelompok FROM t_kelompok_tani
                WHERE deleted_at IS NULL
                ORDER BY id DESC";

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