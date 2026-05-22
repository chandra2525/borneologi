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

        SELECT 
            po.id,
            po.kode_polygon,
            po.nama_polygon,
            ST_AsText(po.geom_area),
            kale.id,
            kale.nama_kaleka COLLATE utf8mb4_unicode_ci,
            'kaleka' COLLATE utf8mb4_unicode_ci,
            po.is_active
        FROM t_polygon po
        LEFT JOIN t_kaleka kale 
            ON kale.id = po.relasi_id 
            AND po.relasi_tipe = 'kaleka'
        WHERE 
            po.deleted_at IS NULL
            AND kale.deleted_at IS NULL 
            AND kale.is_active = 1

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

    public function getKaleka()
    {
        $sql = "SELECT id,nama_kaleka FROM t_kaleka
                WHERE deleted_at IS NULL
                ORDER BY id";

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

    public function getPolygonKalekaData()
    {
        $sql = "SELECT
                kale.id,
                kale.kode_kaleka,
                kale.nama_kaleka,
                po.id AS id_polygon,
                ST_AsText(po.geom_area) AS geom_area
                -- ST_AsGeoJSON(po.geom_area) AS geom_area
            FROM t_polygon po
            LEFT JOIN t_kaleka kale ON kale.id=po.relasi_id
            WHERE kale.deleted_at IS NULL AND kale.is_active = 1 AND po.is_active = 1 AND po.deleted_at IS NULL AND po.relasi_tipe = 'kaleka'
            ORDER BY kale.id ASC;";

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
            LEFT JOIN m_desa d 
                ON d.id = ha.id_desa

            LEFT JOIN t_kelompok_tani kt 
                ON kt.id = ha.id_masyarakat_hukum_adat

            LEFT JOIN m_status_kawasan sk 
                ON sk.id = ha.id_status_kawasan

            LEFT JOIN m_kecamatan ke 
                ON ke.id = d.id_kecamatan

            LEFT JOIN m_kabupaten ka 
                ON ka.id = ke.id_kabupaten

            LEFT JOIN m_kategori_kelompok kk 
                ON kk.id = kt.id_kategori_kelompok

            LEFT JOIN t_tanah ta 
                ON ta.id_relasi = ha.id
                AND ta.tipe_relasi = 'hutan_adat'

            LEFT JOIN m_legalitas_lahan ll 
                ON ll.id = ta.id_legalitas_lahan

            WHERE ha.deleted_at IS NULL
            AND ha.id =  :id

            LIMIT 1";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            "id" => $id
        ]);

        return $stmt->fetch();
    }

    public function getDetailPolygonKaleka($id)
    {
        $sql = "SELECT
            kale1.id,
            kale1.nama_kaleka,
            kale1.luas_ha,
            kale1.keterangan,
            kale1.is_active,

            pe2.nama_lengkap,
            pe2.nama_panggilan,
            pe2.jenis_kelamin,
            pe2.tanggal_lahir,
            pe2.foto_profil_petani,
            pe2.status_petani,
            pe2.alamat as alamat_petani,
            de2.nama_desa as desa_petani,
            kec2.nama_kecamatan as kecamatan_petani,
            kab2.nama_kabupaten as kabupaten_petani,

            ta3.nama_lahan,
            ll3.nama as legalitas_lahan,
            sk3.nama as status_kawasan,
            ta3.luas_ha as luas_ha_tanah,
            ta3.sejarah as sejarah_tanah,
            ta3.alamat_lokasi as alamat_lokasi_tanah,
            ta3.keterangan as keterangan_tanah,
            ta3.sudah_validasi as sudah_validasi_tanah,
            ta3.tanggal_validasi as tanggal_validasi_tanah,

            po4.periode_pengecekan AS periode_pengecekan_perairan,
            wa4.nama as warna_air_perairan,
            jp4.nama as jenis_palung_perairan,
            ka4.nama as kecepatan_aliran_perairan,
            po4.kedalaman_cm AS kedalaman_perairan,
            po4.lebar_m AS lebar_perairan,
            po4.debit_lps AS debit_perairan,
            po4.ph AS ph_perairan,
            po4.kekeruhan_ntu AS kekeruhan_perairan,
            po4.catatan AS catatan_perairan,

            io5.periode_pengecekan AS periode_pengecekan_infrastruktur,
            ap5.nama as akses_perjalanan_infrastruktur,
            kj5.nama as kondisi_jalan_infrastruktur,
            io5.jarak_ke_jalan_km AS jarak_ke_jalan_infrastruktur,
            io5.ada_jembatan AS ada_jembatan_infrastruktur,
            io5.ada_listrik AS ada_listrik_infrastruktur,
            io5.ada_internet AS ada_internet_infrastruktur,
            io5.sinyal_seluler AS sinyal_seluler_infrastruktur,
            io5.catatan AS catatan_infrastruktur,
            
            lco6.periode_pengecekan AS periode_pengecekan_land_cover,
            ka6.nama AS kategori_area_land_cover,
            pp6.nama AS penggunaan_pertanian_land_cover,
            pl6.nama AS penggunaan_lainnya_land_cover,
            lco6.persentase_tutupan AS persentase_tutupan_land_cover,
            lco6.catatan AS catatan_land_cover,

            to7.periode_pengecekan AS periode_pengecekan_topografi,
            la7.nama AS lanskap_topografi,
            ft7.nama AS fitur_tambahan_topografi,
            to7.elevasi_mdpl AS elevasi_topografi,
            to7.kemiringan_derajat AS kemiringan_topografi,
            to7.rawan_erosi AS rawan_erosi_topografi,
            to7.arah_lereng AS arah_lereng_topografi,
            to7.catatan AS catatan_topografi,

            po8.periode_pengecekan AS periode_pengecekan_pohon,
            jp8.nama AS jenis_pohon,
            fp8.nama AS fungsi_pohon,
            po8.jumlah_pohon AS jumlah_pohon,
            po8.diameter_rata2_cm AS diameter_rata2_cm_pohon,
            po8.tinggi_rata2_m AS tinggi_rata2_m_pohon,
            po8.kondisi AS kondisi_pohon,
            po8.catatan AS catatan_pohon,

            de1.nama_desa AS desa_kaleka,
            kec1.nama_kecamatan as kecamatan_kaleka,
            kab1.nama_kabupaten as kabupaten_kaleka

        FROM t_kaleka kale1
        LEFT JOIN t_petani pe2 ON pe2.id=kale1.id_petani
        LEFT JOIN m_desa de2 ON de2.id=pe2.id_desa
        LEFT JOIN m_kecamatan kec2 ON kec2.id=de2.id_kecamatan
        LEFT JOIN m_kabupaten kab2 ON kab2.id=kec2.id_kabupaten
        
        LEFT JOIN t_tanah ta3 ON ta3.id_relasi=kale1.id
        LEFT JOIN m_legalitas_lahan ll3 ON ll3.id=ta3.id_legalitas_lahan
        LEFT JOIN m_status_kawasan sk3 ON sk3.id=ta3.id_status_kawasan

        LEFT JOIN t_perairan_observasi po4 ON po4.id_tanah=ta3.id
        LEFT JOIN m_warna_air wa4 ON wa4.id=po4.id_warna_air
        LEFT JOIN m_jenis_palung jp4 ON jp4.id=po4.id_jenis_palung
        LEFT JOIN m_kecepatan_aliran ka4 ON ka4.id=po4.id_kecepatan_aliran

        LEFT JOIN t_infrastruktur_observasi io5 ON io5.id_tanah=ta3.id
        LEFT JOIN m_akses_perjalanan ap5 ON ap5.id=io5.id_akses_perjalanan
        LEFT JOIN m_kondisi_jalan kj5 ON kj5.id=io5.id_kondisi_jalan

        LEFT JOIN t_land_cover_observasi lco6 ON lco6.id_tanah=ta3.id
        LEFT JOIN m_kategori_area ka6 ON ka6.id=lco6.id_kategori_area
        LEFT JOIN m_penggunaan_pertanian pp6 ON pp6.id=lco6.id_penggunaan_pertanian
        LEFT JOIN m_penggunaan_lainnya pl6 ON pl6.id=lco6.id_penggunaan_lainnya

        LEFT JOIN t_topografi_observasi to7 ON to7.id_tanah=ta3.id
        LEFT JOIN m_lanskap la7 ON la7.id=to7.id_lanskap
        LEFT JOIN m_fitur_tambahan ft7 ON ft7.id=to7.id_fitur_tambahan

        LEFT JOIN t_pohon_observasi po8 ON po8.id_tanah=ta3.id
        LEFT JOIN m_jenis_pohon jp8 ON jp8.id=po8.id_jenis_pohon
        LEFT JOIN m_fungsi_pohon fp8 ON fp8.id=po8.id_fungsi_pohon

        LEFT JOIN m_desa de1 ON de1.id=kale1.id_desa
        LEFT JOIN m_kecamatan kec1 ON kec1.id=de1.id_kecamatan
        LEFT JOIN m_kabupaten kab1 ON kab1.id=kec1.id_kabupaten
        WHERE kale1.deleted_at IS NULL AND kale1.is_active = 1 AND kale1.id = :id
        LIMIT 1";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            "id" => $id
        ]);

        return $stmt->fetch();
    }

    public function getDetailPolygonKalekaKelompokPetani($id)
    {
        $sql = "SELECT
                    kt.nama_kelompok,
                    kk.nama AS kategori_kelompok,
                    pk.tanggal_gabung

                FROM t_kaleka kale
                LEFT JOIN t_petani pe ON pe.id=kale.id_petani
                LEFT JOIN t_petani_kelompok pk ON pk.id_petani=pe.id
                LEFT JOIN t_kelompok_tani kt ON kt.id=pk.id_kelompok_tani
                LEFT JOIN m_kategori_kelompok kk ON kk.id=kt.id_kategori_kelompok

                WHERE kale.deleted_at IS NULL AND kale.is_active = 1 AND kale.id = :id
                ORDER BY kt.id ASC";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            "id" => $id
        ]);

        return $stmt->fetchAll();
    }

    public function getPengurusMHA($id)
    {
        $sql = "SELECT 
                pe.nama_lengkap,
                pe.nama_panggilan,
                pe.jenis_kelamin,
                pe.tanggal_lahir,
                pe.alamat,
                pe.status_petani,
                pe.foto_profil_petani
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

    public function getTotalFarmer()
    {
        $sql = "SELECT jenis_kelamin, COUNT(*) as total
            FROM t_petani
            WHERE deleted_at IS NULL AND is_active = 1
            GROUP BY jenis_kelamin";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        $data = $stmt->fetchAll();

        return $data;
    }

    public function getLatLongBenihData()
    {
        $sql = "SELECT
                titik_koleksi_lat,
                titik_koleksi_lng
                FROM t_bank_benih
                WHERE deleted_at IS NULL AND is_active = 1
                ORDER BY id ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        $data = $stmt->fetchAll();

        return $data;
    }
}