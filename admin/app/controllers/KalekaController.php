<?php

require_once __DIR__ . '/../models/Kaleka.php';
require_once __DIR__ . '/../core/csrf.php';

class KalekaController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new Kaleka($pdo);
    }

    /*
    =========================
    LIST KALEKA
    =========================
    */
    public function index()
    {
        return $this->model->getAll();
    }

    /*
    =========================
    GET KALEKA BY ID
    =========================
    */
    public function find($id)
    {
        return $this->model->findById($id);
    }

    /*
    =========================
    CREATE KALEKA
    =========================
    */
    public function store($data, $user_id)
    {
        if (!verifyCsrfToken()) {
            die("Invalid CSRF Token");
        }

        $kode_kaleka = trim($data['kode_kaleka']);
        $nama_kaleka = trim($data['nama_kaleka']);
        $id_petani = trim($data['id_petani']);
        $id_desa = trim($data['id_desa']);
        $luas_ha = trim($data['luas_ha']);
        $centroid_lat = trim($data['centroid_lat']);
        $centroid_lng = trim($data['centroid_lng']);
        $geom_area = trim($data['geom_area']);
        $keterangan = trim($data['keterangan']);
        $is_active = isset($data['is_active']) ? 1 : 0;

        return $this->model->create([
            'kode_kaleka' => $kode_kaleka,
            'nama_kaleka' => $nama_kaleka,
            'id_petani' => $id_petani,
            'id_desa' => $id_desa,
            'luas_ha' => $luas_ha,
            'centroid_lat' => $centroid_lat,
            'centroid_lng' => $centroid_lng,
            'geom_area' => $geom_area,
            'keterangan' => $keterangan,
            'is_active' => $is_active,
            'created_by' => $user_id
        ]);
    }

    /*
    =========================
    UPDATE KALEKA
    =========================
    */
    public function update($id, $data, $user_id)
    {
        if (!verifyCsrfToken()) {
            die("Invalid CSRF Token");
        }

        $kode_kaleka = trim($data['kode_kaleka']);
        $nama_kaleka = trim($data['nama_kaleka']);
        $id_petani = trim($data['id_petani']);
        $id_desa = trim($data['id_desa']);
        $luas_ha = trim($data['luas_ha']);
        $centroid_lat = trim($data['centroid_lat']);
        $centroid_lng = trim($data['centroid_lng']);
        $geom_area = trim($data['geom_area']);
        $keterangan = trim($data['keterangan']);
        $is_active = isset($data['is_active']) ? 1 : 0;

        return $this->model->update($id, [
            'kode_kaleka' => $kode_kaleka,
            'nama_kaleka' => $nama_kaleka,
            'id_petani' => $id_petani,
            'id_desa' => $id_desa,
            'luas_ha' => $luas_ha,
            'centroid_lat' => $centroid_lat,
            'centroid_lng' => $centroid_lng,
            'geom_area' => $geom_area,
            'keterangan' => $keterangan,
            'is_active' => $is_active,
            'updated_by' => $user_id
        ]);
    }

    /*
    =========================
    DELETE KALEKA (SOFT DELETE)
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

    public function getDesa()
    {
        return $this->model->getDesa();
    }
}