<?php

class Polygon
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        // $sql = "SELECT 
        //         id,
        //         kode_polygon,
        //         nama_polygon,
        //         ST_AsText(geom_area) AS geom_area,
        //         relasi_id,
        //         relasi_tipe,
        //         is_active
        //     FROM t_polygon
        //     WHERE deleted_at IS NULL
        //     ORDER BY id ASC";

        $sql = "SELECT 
            po.id,
            po.kode_polygon,
            po.nama_polygon,
            ST_AsText(po.geom_area) AS geom_area,
            ha.id AS relasi_id,
            ha.nama_hutan_adat COLLATE utf8mb4_unicode_ci AS relasi_nama,
            'hutan_adat' COLLATE utf8mb4_unicode_ci AS relasi_tipe,
            po.is_active
        FROM t_polygon po
        LEFT JOIN t_hutan_adat ha 
            ON ha.id = po.relasi_id 
            AND po.relasi_tipe = 'hutan_adat'
        WHERE 
            po.deleted_at IS NULL
            AND ha.deleted_at IS NULL 
            AND ha.is_active = 1

        UNION ALL

        SELECT 
            po.id,
            po.kode_polygon,
            po.nama_polygon,
            ST_AsText(po.geom_area),
            pr.id,
            pr.nama_provinsi COLLATE utf8mb4_unicode_ci,
            'provinsi' COLLATE utf8mb4_unicode_ci,
            po.is_active
        FROM t_polygon po
        LEFT JOIN m_provinsi pr 
            ON pr.id = po.relasi_id 
            AND po.relasi_tipe = 'provinsi'
        WHERE 
            po.deleted_at IS NULL
            AND pr.deleted_at IS NULL 
            AND pr.is_active = 1

        UNION ALL

        SELECT 
            po.id,
            po.kode_polygon,
            po.nama_polygon,
            ST_AsText(po.geom_area),
            kab.id,
            kab.nama_kabupaten COLLATE utf8mb4_unicode_ci,
            'kabupaten' COLLATE utf8mb4_unicode_ci,
            po.is_active
        FROM t_polygon po
        LEFT JOIN m_kabupaten kab 
            ON kab.id = po.relasi_id 
            AND po.relasi_tipe = 'kabupaten'
        WHERE 
            po.deleted_at IS NULL
            AND kab.deleted_at IS NULL 
            AND kab.is_active = 1

        UNION ALL

        SELECT 
            po.id,
            po.kode_polygon,
            po.nama_polygon,
            ST_AsText(po.geom_area),
            kec.id,
            kec.nama_kecamatan COLLATE utf8mb4_unicode_ci,
            'kecamatan' COLLATE utf8mb4_unicode_ci,
            po.is_active
        FROM t_polygon po
        LEFT JOIN m_kecamatan kec 
            ON kec.id = po.relasi_id 
            AND po.relasi_tipe = 'kecamatan'
        WHERE 
            po.deleted_at IS NULL
            AND kec.deleted_at IS NULL 
            AND kec.is_active = 1

        UNION ALL

        -- Tambahan untuk relasi NULL
        SELECT 
            po.id,
            po.kode_polygon,
            po.nama_polygon,
            ST_AsText(po.geom_area) AS geom_area,
            'Tanpa Relasi' COLLATE utf8mb4_unicode_ci AS relasi_id,
            'Tanpa Relasi' COLLATE utf8mb4_unicode_ci AS relasi_nama,
            'tanpa_relasi' COLLATE utf8mb4_unicode_ci AS relasi_tipe,
            po.is_active
        FROM t_polygon po
        WHERE 
            po.deleted_at IS NULL
            AND (po.relasi_id IS NULL OR po.relasi_tipe IS NULL)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function findById($id)
    {
        $sql = "SELECT *
            FROM t_polygon
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

        $sql = "INSERT INTO t_polygon
            (kode_polygon,nama_polygon,geom_area,is_active,created_by)
            VALUES
            (:kode_polygon,:nama_polygon,ST_GeomFromText(:geom_area, 4326),:is_active,:created_by)";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute($data);
    }

    public function update($id, $data)
    {
        $sql = "UPDATE t_polygon
            SET
            kode_polygon=:kode_polygon,
            nama_polygon=:nama_polygon,
            -- geom_area=:geom_area,
            relasi_id=:relasi_id,
            relasi_tipe=:relasi_tipe,
            is_active=:is_active,
            updated_by=:updated_by
            WHERE id=:id";

        $stmt = $this->pdo->prepare($sql);

        $data["id"] = $id;

        return $stmt->execute($data);
    }

    public function softDelete($id, $user_id)
    {
        $sql = "UPDATE t_polygon
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
        $sql = "SELECT kode_polygon 
            FROM t_polygon 
            -- WHERE deleted_at IS NULL
            ORDER BY id DESC 
            LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch();

        if (!$row) {
            return "PLY001";
        }

        $lastKode = $row['kode_polygon'];
        $number = (int) substr($lastKode, 3);
        $number++;

        return "PLY" . str_pad($number, 3, "0", STR_PAD_LEFT);
    }

    public function getHutanAdat()
    {
        $sql = "SELECT id,nama_hutan_adat FROM t_hutan_adat
                WHERE deleted_at IS NULL
                ORDER BY id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getProvinsi()
    {
        $sql = "SELECT id,nama_provinsi FROM m_provinsi
                WHERE deleted_at IS NULL
                ORDER BY nama_provinsi";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getKabupaten()
    {
        $sql = "SELECT id,nama_kabupaten FROM m_kabupaten
                WHERE deleted_at IS NULL
                ORDER BY nama_kabupaten";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getKecamatan()
    {
        $sql = "SELECT id,nama_kecamatan FROM m_kecamatan
                WHERE deleted_at IS NULL
                ORDER BY nama_kecamatan";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    private function parsePolygon($wkt)
    {
        $wkt = str_replace(['POLYGON((', '))'], '', $wkt);

        $points = explode(',', $wkt);

        $result = [];

        foreach ($points as $point) {

            // 🔥 FIX: pakai preg_split biar aman dari spasi berlebih
            $coord = preg_split('/\s+/', trim($point));

            // validasi supaya tidak error
            if (count($coord) >= 2) {
                $lng = (float) $coord[0];
                $lat = (float) $coord[1];

                // skip kalau tidak valid
                if ($lng != 0 && $lat != 0) {
                    $result[] = [$lat, $lng];
                }
            }
        }

        return $result;
    }

    public function getPolygonHAData()
    {
        $sql = "SELECT
                ha.id,
                ha.kode_hutan_adat,
                ha.nama_hutan_adat,
                po.id AS id_polygon,
                ST_AsText(po.geom_area) AS geom_area
                -- ST_AsGeoJSON(po.geom_area) AS geom_area
            FROM t_polygon po
            LEFT JOIN t_hutan_adat ha ON ha.id=po.relasi_id
            WHERE ha.deleted_at IS NULL AND ha.is_active = 1 AND po.is_active = 1 AND po.deleted_at IS NULL AND po.relasi_tipe = 'hutan_adat'
            ORDER BY ha.id ASC;";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        $data = $stmt->fetchAll();

        // convert geom_area
        foreach ($data as &$row) {
            if ($row['geom_area']) {
                $row['geom_area'] = $this->parsePolygon($row['geom_area']);
                // $row['geom_area'] = json_decode($row['geom_area'], true);
            }
        }

        return $data;
    }

    public function getPolygonProvData()
    {
        $sql = "SELECT
                pr.id,
                pr.kode_provinsi ,
                pr.nama_provinsi,
                po.id AS id_polygon,
                ST_AsText(po.geom_area) AS geom_area
            FROM t_polygon po
            LEFT JOIN m_provinsi pr ON pr.id=po.relasi_id
            WHERE pr.deleted_at IS NULL AND pr.is_active = 1 AND po.deleted_at IS NULL AND po.relasi_tipe = 'provinsi'
            ORDER BY pr.id ASC;";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        $data = $stmt->fetchAll();

        // convert geom_area
        foreach ($data as &$row) {
            if ($row['geom_area']) {
                $row['geom_area'] = $this->parsePolygon($row['geom_area']);
            }
        }

        return $data;
    }

    public function getPolygonKabData()
    {
        $sql = "SELECT
                kab.id,
                kab.kode_kabupaten ,
                kab.nama_kabupaten,
                po.id AS id_polygon,
                ST_AsGeoJSON(po.geom_area) AS geom_area
            FROM t_polygon po
            LEFT JOIN m_kabupaten kab ON kab.id=po.relasi_id
            WHERE kab.deleted_at IS NULL AND kab.is_active = 1 AND po.deleted_at IS NULL AND po.relasi_tipe = 'kabupaten'
            ORDER BY kab.id ASC;";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        $data = $stmt->fetchAll();

        // convert geom_area
        foreach ($data as &$row) {
            if ($row['geom_area']) {
                $row['geom_area'] = json_decode($row['geom_area'], true);
            }
        }

        return $data;
    }

    public function getPolygonKecData()
    {
        $sql = "SELECT
                kec.id,
                kec.kode_kecamatan ,
                kec.nama_kecamatan,
                po.id AS id_polygon,
                ST_AsText(po.geom_area) AS geom_area
            FROM t_polygon po
            LEFT JOIN m_kecamatan kec ON kec.id=po.relasi_id
            WHERE kec.deleted_at IS NULL AND kec.is_active = 1 AND po.deleted_at IS NULL AND po.relasi_tipe = 'kecamatan'
            ORDER BY kec.id ASC;";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        $data = $stmt->fetchAll();

        // convert geom_area
        foreach ($data as &$row) {
            if ($row['geom_area']) {
                $row['geom_area'] = $this->parsePolygon($row['geom_area']);
            }
        }

        return $data;
    }

    public function getDetailPolygonProv($id)
    {
        $sql = "SELECT
                pr.id,
                pr.nama_provinsi,
                po.id AS id_polygon
                -- ST_AsText(po.geom_area) AS geom_area
            FROM t_polygon po
            LEFT JOIN m_provinsi pr ON pr.id=po.relasi_id
            WHERE po.id = :id
            LIMIT 1";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            "id" => $id
        ]);

        return $stmt->fetch();
    }

    public function getDetailPolygonKab($id)
    {
        $sql = "SELECT
                kab.id,
                kab.nama_kabupaten,
                po.id AS id_polygon
                -- ST_AsText(po.geom_area) AS geom_area
            FROM t_polygon po
            LEFT JOIN m_kabupaten kab ON kab.id=po.relasi_id
            WHERE po.id = :id
            LIMIT 1";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            "id" => $id
        ]);

        return $stmt->fetch();
    }

    public function getDetailPolygonKec($id)
    {
        $sql = "SELECT
                kec.id,
                kec.nama_kecamatan,
                po.id AS id_polygon
                -- ST_AsText(po.geom_area) AS geom_area
            FROM t_polygon po
            LEFT JOIN m_kecamatan kec ON kec.id=po.relasi_id
            WHERE po.id = :id
            LIMIT 1";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            "id" => $id
        ]);

        return $stmt->fetch();
    }

    public function getDetailPolygonHa($id)
    {
        $sql = "SELECT
            ha.id,
            ha.nama_hutan_adat,
            ha.nomor_sk,
            ha.tanggal_sk,
            sk.nama as status_kawasan,
            d.nama_desa,
            ke.nama_kecamatan,
            ka.nama_kabupaten,
            ha.keterangan,
            
            kt.id as id_kelompok_tani,
            kt.nama_kelompok as nama_masyarakat_hukum_adat,
            kk.nama as nama_kategori_kelompok,
            kt.tahun_bentuk,
            kt.status_kelompok as status_masyarakat_hukum_adat,
            kt.alamat as alamat_masyarakat_hukum_adat,
            kk.deskripsi,
            
            ha.luas_ha,
            ha.is_active,

            ta.nama_lahan,
            ll.nama as legalitas_lahan,
            ta.luas_ha,
            ta.sejarah,
            ta.alamat_lokasi,
            ta.keterangan,
            ta.sudah_validasi,
            ta.tanggal_validasi,

            (
                SELECT COUNT(pk.id_petani)
                FROM t_petani_kelompok pk
                WHERE pk.id_kelompok_tani = kt.id
            ) AS total_anggota_masyarakat_hukum_adat

        FROM t_hutan_adat ha
        LEFT JOIN m_desa d ON d.id=ha.id_desa
        LEFT JOIN t_kelompok_tani kt ON kt.id=ha.id_masyarakat_hukum_adat
        LEFT JOIN m_status_kawasan sk ON sk.id=ha.id_status_kawasan
        LEFT JOIN m_kecamatan ke ON ke.id=d.id_kecamatan
        LEFT JOIN m_kabupaten ka ON ka.id=ke.id_kabupaten
        LEFT JOIN m_kategori_kelompok kk ON kk.id=kt.id_kategori_kelompok
        LEFT JOIN t_tanah ta ON ta.id_relasi=ha.id
        LEFT JOIN m_legalitas_lahan ll ON ll.id=ta.id_legalitas_lahan
        WHERE ha.deleted_at IS NULL AND ha.id = :id AND ta.tipe_relasi = 'hutan_adat'
        LIMIT 1";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            "id" => $id
        ]);

        return $stmt->fetch();
    }
    
    public function getPengurusMHA($id)
    {
        $sql = "SELECT 
                pe.nama_lengkap,
                pe.nama_panggilan,
                pe.jenis_kelamin,
                pe.tanggal_lahir,
                pe.alamat,
                pe.status_petani
            FROM t_petani_kelompok pk
            LEFT JOIN t_petani pe ON pe.id = pk.id_petani
            WHERE pk.id_kelompok_tani = :id
            ORDER BY pe.id ASC";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            "id" => $id
        ]);

        return $stmt->fetchAll();
    }

}