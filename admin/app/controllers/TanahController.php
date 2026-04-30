<?php

require_once __DIR__ . '/../models/Tanah.php';
require_once __DIR__ . '/../core/csrf.php';

class TanahController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new Tanah($pdo);
    }

    /*
    =========================
    LIST TANAH
    =========================
    */
    public function index()
    {
        return $this->model->getAll();
    }

    /*
    =========================
    GET TANAH BY ID
    =========================
    */
    public function find($id)
    {
        return $this->model->findById($id);
    }

    /*
    =========================
    CREATE TANAH
    =========================
    */
    public function store($data, $user_id)
    {
        if (!verifyCsrfToken()) {
            die("Invalid CSRF Token");
        }

        $kode_tanah = trim($data['kode_tanah']);
        $id_petani = trim($data['id_petani']);
        $id_kaleka = trim($data['id_kaleka']);
        $nama_lahan = trim($data['nama_lahan']);
        $id_legalitas_lahan  = trim($data['id_legalitas_lahan ']);
        $id_status_kawasan = trim($data['id_status_kawasan']);
        $luas_ha = trim($data['luas_ha']);
        $centroid_lat = trim($data['centroid_lat']);
        $centroid_lng = trim($data['centroid_lng']);
        $geom_area = trim($data['geom_area']);
        $alamat_lokasi = trim($data['alamat_lokasi']);
        $keterangan = trim($data['keterangan']);
        $sudah_validasi = trim($data['sudah_validasi']);
        $tanggal_validasi = trim($data['tanggal_validasi']);
        $is_active = isset($data['is_active']) ? 1 : 0;

        return $this->model->create([
            'kode_tanah' => $kode_tanah,
            'id_petani' => $id_petani,
            'id_kaleka' => $id_kaleka,
            'nama_lahan' => $nama_lahan,
            'id_legalitas_lahan ' => $id_legalitas_lahan ,
            'id_status_kawasan' => $id_status_kawasan,
            'luas_ha' => $luas_ha,
            'centroid_lat' => $centroid_lat,
            'centroid_lng' => $centroid_lng,
            'geom_area' => $geom_area,
            'alamat_lokasi' => $alamat_lokasi,
            'keterangan' => $keterangan,
            'sudah_validasi' => $sudah_validasi,
            'tanggal_validasi' => $tanggal_validasi,
            'is_active' => $is_active,
            'created_by' => $user_id
        ]);
    }

    /*
    =========================
    UPDATE TANAH
    =========================
    */
    public function update($id, $data, $user_id)
    {
        if (!verifyCsrfToken()) {
            die("Invalid CSRF Token");
        }

        $kode_tanah = trim($data['kode_tanah']);
        $id_petani = trim($data['id_petani']);
        $id_kaleka = trim($data['id_kaleka']);
        $nama_lahan = trim($data['nama_lahan']);
        $id_legalitas_lahan  = trim($data['id_legalitas_lahan ']);
        $id_status_kawasan = trim($data['id_status_kawasan']);
        $luas_ha = trim($data['luas_ha']);
        $centroid_lat = trim($data['centroid_lat']);
        $centroid_lng = trim($data['centroid_lng']);
        $geom_area = trim($data['geom_area']);
        $alamat_lokasi = trim($data['alamat_lokasi']);
        $keterangan = trim($data['keterangan']);
        $sudah_validasi = trim($data['sudah_validasi']);
        $tanggal_validasi = trim($data['tanggal_validasi']);
        $is_active = isset($data['is_active']) ? 1 : 0;

        return $this->model->update($id, [
            'kode_tanah' => $kode_tanah,
            'id_petani' => $id_petani,
            'id_kaleka' => $id_kaleka,
            'nama_lahan' => $nama_lahan,
            'id_legalitas_lahan ' => $id_legalitas_lahan ,
            'id_status_kawasan' => $id_status_kawasan,
            'luas_ha' => $luas_ha,
            'centroid_lat' => $centroid_lat,
            'centroid_lng' => $centroid_lng,
            'geom_area' => $geom_area,
            'alamat_lokasi' => $alamat_lokasi,
            'keterangan' => $keterangan,
            'sudah_validasi' => $sudah_validasi,
            'tanggal_validasi' => $tanggal_validasi,
            'is_active' => $is_active,
            'updated_by' => $user_id
        ]);
    }

    /*
    =========================
    DELETE TANAH (SOFT DELETE)
    =========================
    */
    public function delete($id, $user_id)
    {
        if (!verifyCsrfToken()) {
            die("Invalid CSRF Token");
        }

        return $this->model->softDelete($id, $user_id);
    }

    public function getPetani()
    {
        return $this->model->getPetani();
    }

    public function getHutanAdat()
    {
        return $this->model->getHutanAdat();
    }

    public function getKaleka()
    {
        return $this->model->getKaleka();
    }

    public function getLegalitasLahan()
    {
        return $this->model->getLegalitasLahan();
    }

    public function getStatusKawasan()
    {
        return $this->model->getStatusKawasan();
    }
}