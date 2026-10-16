<?php

class Wilayah
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Daftar kecamatan beserta rekapitulasi.
     */
    public function getAllKecamatan()
    {
        $sql = "
            SELECT
                k.id,
                k.id_kabupaten,
                k.kode_kecamatan,
                k.nama_kecamatan,

                (
                    SELECT COUNT(*)
                    FROM m_desa d
                    WHERE d.id_kecamatan = k.id
                      AND d.deleted_at IS NULL
                      AND d.is_active = 1
                ) AS total_desa,

                (
                    SELECT COUNT(*)
                    FROM t_hutan_adat ha
                    INNER JOIN m_desa d ON d.id = ha.id_desa
                    WHERE d.id_kecamatan = k.id
                      AND d.deleted_at IS NULL
                      AND d.is_active = 1
                      AND ha.deleted_at IS NULL
                      AND ha.is_active = 1
                ) AS total_hutan_adat,

                (
                    SELECT COUNT(*)
                    FROM t_kaleka ka
                    INNER JOIN m_desa d ON d.id = ka.id_desa
                    WHERE d.id_kecamatan = k.id
                      AND d.deleted_at IS NULL
                      AND d.is_active = 1
                      AND ka.deleted_at IS NULL
                      AND ka.is_active = 1
                ) AS total_kaleka,

                (
                    SELECT COUNT(*)
                    FROM t_bank_benih bb
                    INNER JOIN m_desa d ON d.id = bb.id_desa
                    WHERE d.id_kecamatan = k.id
                      AND d.deleted_at IS NULL
                      AND d.is_active = 1
                      AND bb.deleted_at IS NULL
                      AND bb.is_active = 1
                ) AS total_bank_benih,

                (
                    SELECT COUNT(DISTINCT p.id)
                    FROM t_petani p
                    INNER JOIN m_desa d ON d.id = p.id_desa
                    WHERE d.id_kecamatan = k.id
                      AND d.deleted_at IS NULL
                      AND d.is_active = 1
                      AND p.deleted_at IS NULL
                      AND p.is_active = 1
                ) AS total_cbo_penerima_manfaat

            FROM m_kecamatan k
            WHERE k.deleted_at IS NULL
              AND k.is_active = 1
            ORDER BY k.nama_kecamatan ASC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Detail satu kecamatan beserta rekapitulasi.
     */
    public function getKecamatanById($id)
    {
        $sql = "
            SELECT *
            FROM (
                SELECT
                    k.id,
                    k.id_kabupaten,
                    k.kode_kecamatan,
                    k.nama_kecamatan,

                    (
                        SELECT COUNT(*)
                        FROM m_desa d
                        WHERE d.id_kecamatan = k.id
                          AND d.deleted_at IS NULL
                          AND d.is_active = 1
                    ) AS total_desa,

                    (
                        SELECT COUNT(*)
                        FROM t_hutan_adat ha
                        INNER JOIN m_desa d ON d.id = ha.id_desa
                        WHERE d.id_kecamatan = k.id
                          AND d.deleted_at IS NULL
                          AND d.is_active = 1
                          AND ha.deleted_at IS NULL
                          AND ha.is_active = 1
                    ) AS total_hutan_adat,

                    (
                        SELECT COUNT(*)
                        FROM t_kaleka ka
                        INNER JOIN m_desa d ON d.id = ka.id_desa
                        WHERE d.id_kecamatan = k.id
                          AND d.deleted_at IS NULL
                          AND d.is_active = 1
                          AND ka.deleted_at IS NULL
                          AND ka.is_active = 1
                    ) AS total_kaleka,

                    (
                        SELECT COUNT(*)
                        FROM t_bank_benih bb
                        INNER JOIN m_desa d ON d.id = bb.id_desa
                        WHERE d.id_kecamatan = k.id
                          AND d.deleted_at IS NULL
                          AND d.is_active = 1
                          AND bb.deleted_at IS NULL
                          AND bb.is_active = 1
                    ) AS total_bank_benih,

                    (
                        SELECT COUNT(DISTINCT p.id)
                        FROM t_petani p
                        INNER JOIN m_desa d ON d.id = p.id_desa
                        WHERE d.id_kecamatan = k.id
                          AND d.deleted_at IS NULL
                          AND d.is_active = 1
                          AND p.deleted_at IS NULL
                          AND p.is_active = 1
                    ) AS total_cbo_penerima_manfaat

                FROM m_kecamatan k
                WHERE k.id = :id
                  AND k.deleted_at IS NULL
                  AND k.is_active = 1
            ) AS rekap
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Daftar desa beserta rekapitulasi.
     * Jika $idKecamatan null, ambil seluruh desa aktif.
     */
    public function getAllDesa($idKecamatan = null)
    {
        $sql = "
            SELECT
                d.id,
                d.id_kecamatan,
                d.kode_desa,
                d.nama_desa,
                d.tipe_wilayah,
                d.kode_pos,

                (
                    SELECT COUNT(*)
                    FROM t_hutan_adat ha
                    WHERE ha.id_desa = d.id
                    AND ha.deleted_at IS NULL
                    AND ha.is_active = 1
                ) AS total_hutan_adat,

                (
                    SELECT COUNT(*)
                    FROM t_kaleka ka
                    WHERE ka.id_desa = d.id
                    AND ka.deleted_at IS NULL
                    AND ka.is_active = 1
                ) AS total_kaleka,

                (
                    SELECT COUNT(*)
                    FROM t_bank_benih bb
                    WHERE bb.id_desa = d.id
                    AND bb.deleted_at IS NULL
                    AND bb.is_active = 1
                ) AS total_bank_benih,

                (
                    SELECT COUNT(DISTINCT p.id)
                    FROM t_petani p
                    WHERE p.id_desa = d.id
                    AND p.deleted_at IS NULL
                    AND p.is_active = 1
                ) AS total_cbo_penerima_manfaat

            FROM m_desa d
            WHERE d.deleted_at IS NULL
            AND d.is_active = 1
        ";

        $params = [];

        if ($idKecamatan !== null) {
            $sql .= " AND d.id_kecamatan = :id_kecamatan";
            $params['id_kecamatan'] = $idKecamatan;
        }

        $sql .= " ORDER BY d.nama_desa ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Detail satu desa beserta rekapitulasi.
     */
    public function getDesaById($id)
    {
        $sql = "
            SELECT
                d.id,
                d.id_kecamatan,
                d.kode_desa,
                d.nama_desa,
                d.tipe_wilayah,
                d.kode_pos,
                k.nama_kecamatan,
                k.kode_kecamatan,

                (
                    SELECT COUNT(*)
                    FROM t_hutan_adat ha
                    WHERE ha.id_desa = d.id
                      AND ha.deleted_at IS NULL
                      AND ha.is_active = 1
                ) AS total_hutan_adat,

                (
                    SELECT COUNT(*)
                    FROM t_kaleka ka
                    WHERE ka.id_desa = d.id
                      AND ka.deleted_at IS NULL
                      AND ka.is_active = 1
                ) AS total_kaleka,

                (
                    SELECT COUNT(*)
                    FROM t_bank_benih bb
                    WHERE bb.id_desa = d.id
                      AND bb.deleted_at IS NULL
                      AND bb.is_active = 1
                ) AS total_bank_benih,

                (
                    SELECT COUNT(DISTINCT p.id)
                    FROM t_petani p
                    WHERE p.id_desa = d.id
                      AND p.deleted_at IS NULL
                      AND p.is_active = 1
                ) AS total_cbo_penerima_manfaat

            FROM m_desa d
            INNER JOIN m_kecamatan k
                ON k.id = d.id_kecamatan
            WHERE d.id = :id
              AND d.deleted_at IS NULL
              AND d.is_active = 1
              AND k.deleted_at IS NULL
              AND k.is_active = 1
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Daftar data untuk modal.
     * Parameter tipe dan data divalidasi menggunakan whitelist.
     */
    public function getDetailWilayah($tipe, $id, $data)
    {
        $allowed = [
            'kecamatan' => [
                'desa',
                'hutan_adat',
                'kaleka',
                'bank_benih',
                'cbo'
            ],
            'desa' => [
                'hutan_adat',
                'kaleka',
                'bank_benih',
                'cbo'
            ]
        ];

        if (
            !isset($allowed[$tipe]) ||
            !in_array($data, $allowed[$tipe], true)
        ) {
            throw new InvalidArgumentException(
                'Tipe wilayah atau data tidak valid'
            );
        }

        $params = ['id' => $id];

        if ($tipe === 'kecamatan') {
            $whereDesa = 'd.id_kecamatan = :id';
            $whereDesaHA = 'd.id_kecamatan = :id';
            $whereDesaKaleka = 'd.id_kecamatan = :id';
            $whereDesaBenih = 'd.id_kecamatan = :id';
            $whereDesaPetani = 'd.id_kecamatan = :id';
        } else {
            $whereDesa = 'd.id = :id';
            $whereDesaHA = 'ha.id_desa = :id';
            $whereDesaKaleka = 'ka.id_desa = :id';
            $whereDesaBenih = 'bb.id_desa = :id';
            $whereDesaPetani = 'p.id_desa = :id';
        }

        switch ($data) {
            case 'desa':
                $sql = "
                    SELECT
                        d.id,
                        d.kode_desa,
                        d.nama_desa,
                        d.tipe_wilayah,
                        d.kode_pos
                    FROM m_desa d
                    WHERE $whereDesa
                      AND d.deleted_at IS NULL
                      AND d.is_active = 1
                    ORDER BY d.nama_desa ASC
                ";
                break;

            case 'hutan_adat':
                $sql = "
                    SELECT
                        ha.id,
                        ha.kode_hutan_adat,
                        ha.nama_hutan_adat,
                        ha.nomor_sk,
                        ha.tanggal_sk,
                        ha.luas_ha,
                        ha.keterangan,
                        d.nama_desa
                    FROM t_hutan_adat ha
                    INNER JOIN m_desa d ON d.id = ha.id_desa
                    WHERE $whereDesaHA
                      AND ha.deleted_at IS NULL
                      AND ha.is_active = 1
                      AND d.deleted_at IS NULL
                      AND d.is_active = 1
                    ORDER BY ha.nama_hutan_adat ASC
                ";
                break;

            case 'kaleka':
                $sql = "
                    SELECT
                        ka.id,
                        ka.kode_kaleka,
                        ka.nama_kaleka,
                        ka.luas_ha,
                        ka.keterangan,
                        d.nama_desa
                    FROM t_kaleka ka
                    INNER JOIN m_desa d ON d.id = ka.id_desa
                    WHERE $whereDesaKaleka
                      AND ka.deleted_at IS NULL
                      AND ka.is_active = 1
                      AND d.deleted_at IS NULL
                      AND d.is_active = 1
                    ORDER BY ka.nama_kaleka ASC
                ";
                break;

            case 'bank_benih':
                $sql = "
                    SELECT
                        bb.id,
                        bb.nomor_aksesi,
                        bb.nama_lokal,
                        bb.nama_ilmiah,
                        bb.famili_tanaman,
                        bb.tanggal_masuk,
                        bb.jumlah_stok,
                        bb.stok_terpakai,
                        bb.satuan_stok,
                        bb.lokasi_penyimpanan,
                        bb.masa_berlaku_sampai,
                        d.nama_desa
                    FROM t_bank_benih bb
                    INNER JOIN m_desa d ON d.id = bb.id_desa
                    WHERE $whereDesaBenih
                      AND bb.deleted_at IS NULL
                      AND bb.is_active = 1
                      AND d.deleted_at IS NULL
                      AND d.is_active = 1
                    ORDER BY bb.nama_lokal ASC
                ";
                break;

            case 'cbo':
                $sql = "
                    SELECT DISTINCT
                        p.id,
                        p.nik,
                        p.no_kk,
                        p.nama_lengkap,
                        p.nama_panggilan,
                        p.jenis_kelamin,
                        p.nomor_hp,
                        p.status_petani,
                        d.nama_desa
                    FROM t_petani p
                    INNER JOIN m_desa d ON d.id = p.id_desa
                    WHERE $whereDesaPetani
                      AND p.deleted_at IS NULL
                      AND p.is_active = 1
                    ORDER BY p.nama_lengkap ASC
                ";
                break;
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}