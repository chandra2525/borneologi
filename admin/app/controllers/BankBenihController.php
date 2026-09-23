<?php

require_once __DIR__ . '/../models/BankBenih.php';
require_once __DIR__ . '/../core/csrf.php';

class BankBenihController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new BankBenih($pdo);
    }


    /**
     * LIST
     */
    public function index()
    {
        return $this->model->getAll();
    }


    /**
     * DETAIL
     */
    public function find($id)
    {
        return $this->model->findById($id);
    }


    /**
     * STATISTICS
     */
    public function statistics()
    {
        return $this->model->getStatistics();
    }


    /**
     * CREATE
     */
    public function store($data, $user_id)
    {
        // if (!verifyCsrfToken()) {
        //     die("Invalid CSRF Token");
        // }

        $nomor_aksesi = trim($data['nomor_aksesi'] ?? '');
        $id_tanah = trim($data['id_tanah'] ?? '');
        $id_negara = trim($data['id_negara'] ?? '');
        $nama_lokal = trim($data['nama_lokal'] ?? '');
        $nama_ilmiah = trim($data['nama_ilmiah'] ?? '');
        $famili_tanaman = trim($data['famili_tanaman'] ?? '');
        $provenance = trim($data['provenance'] ?? '');
        $id_tipe_penyimpanan_benih = trim(
            $data['id_tipe_penyimpanan_benih'] ?? ''
        );
        $tanggal_masuk = trim($data['tanggal_masuk'] ?? '');
        $jumlah_stok = trim($data['jumlah_stok'] ?? '0');
        $satuan_stok = trim($data['satuan_stok'] ?? '');
        $kadar_air_persen = trim($data['kadar_air_persen'] ?? '');
        $viabilitas_persen = trim($data['viabilitas_persen'] ?? '');
        $ketinggian_mdpl = trim($data['ketinggian_mdpl'] ?? '');
        $masa_berlaku_sampai = trim($data['masa_berlaku_sampai'] ?? '');
        $lokasi_penyimpanan = trim($data['lokasi_penyimpanan'] ?? '');
        $titik_koleksi_lat = trim($data['titik_koleksi_lat'] ?? '');
        $titik_koleksi_lng = trim($data['titik_koleksi_lng'] ?? '');
        $foto_benih = trim($data['foto_benih'] ?? '');
        $catatan = trim($data['catatan'] ?? '');

        $is_active = isset($data['is_active'])
            ? ((string) $data['is_active'] === '1' ? 1 : 0)
            : 0;


        /*
         * VALIDASI
         */

        if ($nama_lokal === '') {
            die("Nama Lokal wajib diisi.");
        }

        if ($id_tanah === '') {
            die("Tanah wajib dipilih.");
        }

        if ($id_negara === '') {
            die("Negara wajib dipilih.");
        }

        if ($id_tipe_penyimpanan_benih === '') {
            die("Tipe penyimpanan benih wajib dipilih.");
        }

        if ($tanggal_masuk === '') {
            die("Tanggal masuk wajib diisi.");
        }

        if ($jumlah_stok === '' || !is_numeric($jumlah_stok) || $jumlah_stok < 0) {
            die("Jumlah stok tidak valid.");
        }

        if ($kadar_air_persen !== '') {
            if (
                !is_numeric($kadar_air_persen) ||
                $kadar_air_persen < 0 ||
                $kadar_air_persen > 100
            ) {
                die("Kadar air harus antara 0 sampai 100 persen.");
            }
        }

        if ($viabilitas_persen !== '') {
            if (
                !is_numeric($viabilitas_persen) ||
                $viabilitas_persen < 0 ||
                $viabilitas_persen > 100
            ) {
                die("Viabilitas harus antara 0 sampai 100 persen.");
            }
        }

        if ($titik_koleksi_lat !== '') {
            if (
                !is_numeric($titik_koleksi_lat) ||
                $titik_koleksi_lat < -90 ||
                $titik_koleksi_lat > 90
            ) {
                die("Latitude tidak valid.");
            }
        }

        if ($titik_koleksi_lng !== '') {
            if (
                !is_numeric($titik_koleksi_lng) ||
                $titik_koleksi_lng < -180 ||
                $titik_koleksi_lng > 180
            ) {
                die("Longitude tidak valid.");
            }
        }


        /*
         * NOMOR AKSESI UNIQUE
         */
        if ($nomor_aksesi !== '') {

            $existing = $this->model->existsNomorAksesi(
                $nomor_aksesi
            );

            if ($existing) {
                die("Nomor Aksesi sudah digunakan.");
            }
        }


        return $this->model->create([

            'nomor_aksesi' => $nomor_aksesi,
            'id_tanah' => $id_tanah,
            'id_negara' => $id_negara,
            'nama_lokal' => $nama_lokal,
            'nama_ilmiah' => $nama_ilmiah,
            'famili_tanaman' => $famili_tanaman,
            'provenance' => $provenance,
            'id_tipe_penyimpanan_benih' => $id_tipe_penyimpanan_benih,
            'tanggal_masuk' => $tanggal_masuk,
            'jumlah_stok' => $jumlah_stok,
            'satuan_stok' => $satuan_stok,
            'kadar_air_persen' => $kadar_air_persen !== ''
                ? $kadar_air_persen
                : null,
            'viabilitas_persen' => $viabilitas_persen !== ''
                ? $viabilitas_persen
                : null,
            'ketinggian_mdpl' => $ketinggian_mdpl !== ''
                ? $ketinggian_mdpl
                : null,
            'masa_berlaku_sampai' => $masa_berlaku_sampai !== ''
                ? $masa_berlaku_sampai
                : null,
            'lokasi_penyimpanan' => $lokasi_penyimpanan,
            'titik_koleksi_lat' => $titik_koleksi_lat !== ''
                ? $titik_koleksi_lat
                : null,
            'titik_koleksi_lng' => $titik_koleksi_lng !== ''
                ? $titik_koleksi_lng
                : null,
            'foto_benih' => $foto_benih ?: null,
            'catatan' => $catatan,
            'is_active' => $is_active,
            'created_by' => $user_id
        ]);
    }


    /**
     * UPDATE
     */
    public function update($id, $data, $user_id)
    {
        // if (!verifyCsrfToken()) {
        //     die("Invalid CSRF Token");
        // }

        $nomor_aksesi = trim($data['nomor_aksesi'] ?? '');
        $id_tanah = trim($data['id_tanah'] ?? '');
        $id_negara = trim($data['id_negara'] ?? '');
        $nama_lokal = trim($data['nama_lokal'] ?? '');
        $nama_ilmiah = trim($data['nama_ilmiah'] ?? '');
        $famili_tanaman = trim($data['famili_tanaman'] ?? '');
        $provenance = trim($data['provenance'] ?? '');
        $id_tipe_penyimpanan_benih = trim(
            $data['id_tipe_penyimpanan_benih'] ?? ''
        );
        $tanggal_masuk = trim($data['tanggal_masuk'] ?? '');
        $jumlah_stok = trim($data['jumlah_stok'] ?? '0');
        $satuan_stok = trim($data['satuan_stok'] ?? '');
        $kadar_air_persen = trim($data['kadar_air_persen'] ?? '');
        $viabilitas_persen = trim($data['viabilitas_persen'] ?? '');
        $ketinggian_mdpl = trim($data['ketinggian_mdpl'] ?? '');
        $masa_berlaku_sampai = trim($data['masa_berlaku_sampai'] ?? '');
        $lokasi_penyimpanan = trim($data['lokasi_penyimpanan'] ?? '');
        $titik_koleksi_lat = trim($data['titik_koleksi_lat'] ?? '');
        $titik_koleksi_lng = trim($data['titik_koleksi_lng'] ?? '');
        $foto_benih = trim($data['foto_benih'] ?? '');
        $catatan = trim($data['catatan'] ?? '');

        $is_active = isset($data['is_active'])
            ? ((string) $data['is_active'] === '1' ? 1 : 0)
            : 0;


        if ($nama_lokal === '') {
            die("Nama Lokal wajib diisi.");
        }

        if ($id_tanah === '') {
            die("Tanah wajib dipilih.");
        }

        if ($id_negara === '') {
            die("Negara wajib dipilih.");
        }

        if ($id_tipe_penyimpanan_benih === '') {
            die("Tipe penyimpanan benih wajib dipilih.");
        }

        if (
            $jumlah_stok === '' ||
            !is_numeric($jumlah_stok) ||
            $jumlah_stok < 0
        ) {
            die("Jumlah stok tidak valid.");
        }


        /*
         * NOMOR AKSESI UNIQUE
         */
        if ($nomor_aksesi !== '') {

            $existing = $this->model->existsNomorAksesi(
                $nomor_aksesi,
                $id
            );

            if ($existing) {
                die("Nomor Aksesi sudah digunakan oleh data lain.");
            }
        }


        return $this->model->update($id, [

            'nomor_aksesi' => $nomor_aksesi,
            'id_tanah' => $id_tanah,
            'id_negara' => $id_negara,
            'nama_lokal' => $nama_lokal,
            'nama_ilmiah' => $nama_ilmiah,
            'famili_tanaman' => $famili_tanaman,
            'provenance' => $provenance,
            'id_tipe_penyimpanan_benih' => $id_tipe_penyimpanan_benih,
            'tanggal_masuk' => $tanggal_masuk,
            'jumlah_stok' => $jumlah_stok,
            'satuan_stok' => $satuan_stok,
            'kadar_air_persen' => $kadar_air_persen !== ''
                ? $kadar_air_persen
                : null,
            'viabilitas_persen' => $viabilitas_persen !== ''
                ? $viabilitas_persen
                : null,
            'ketinggian_mdpl' => $ketinggian_mdpl !== ''
                ? $ketinggian_mdpl
                : null,
            'masa_berlaku_sampai' => $masa_berlaku_sampai !== ''
                ? $masa_berlaku_sampai
                : null,
            'lokasi_penyimpanan' => $lokasi_penyimpanan,
            'titik_koleksi_lat' => $titik_koleksi_lat !== ''
                ? $titik_koleksi_lat
                : null,
            'titik_koleksi_lng' => $titik_koleksi_lng !== ''
                ? $titik_koleksi_lng
                : null,
            'foto_benih' => $foto_benih ?: null,
            'catatan' => $catatan,
            'is_active' => $is_active,
            'updated_by' => $user_id
        ]);
    }


    /**
     * DELETE
     */
    public function delete($id, $user_id)
    {
        if (!verifyCsrfToken()) {
            die("Invalid CSRF Token");
        }

        return $this->model->softDelete(
            $id,
            $user_id
        );
    }


    public function getTanah()
    {
        return $this->model->getTanah();
    }


    public function getNegara()
    {
        return $this->model->getNegara();
    }


    public function getTipePenyimpananBenih()
    {
        return $this->model->getTipePenyimpananBenih();
    }
}