<?php

require_once __DIR__ . '/../models/Polygon.php';
require_once __DIR__ . '/../core/csrf.php';

class PolygonController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new Polygon($pdo);
    }

    /*
    =========================
    LIST POLYGON
    =========================
    */
    public function index()
    {
        return $this->model->getAll();
    }

    /*
    =========================
    GET POLYGON BY ID
    =========================
    */
    public function find($id)
    {
        return $this->model->findById($id);
    }

    /*
    =========================
    CREATE POLYGON
    =========================
    */
    public function store($data, $user_id)
    {
        if (!verifyCsrfToken()) {
            die("Invalid CSRF Token");
        }

        $kode_polygon = trim($data['kode_polygon']);
        $nama_polygon = trim($data['nama_polygon']);
        $geom_area = trim($data['geom_area']);
        $is_active = isset($data['is_active']) ? 1 : 0;

        return $this->model->create([
            'kode_polygon' => $kode_polygon,
            'nama_polygon' => $nama_polygon,
            'geom_area' => $geom_area,
            'is_active' => $is_active,
            'created_by' => $user_id
        ]);
    }

    /*
    =========================
    UPDATE POLYGON
    =========================
    */
    public function update($id, $data, $user_id)
    {
        if (!verifyCsrfToken()) {
            die("Invalid CSRF Token");
        }

        $kode_polygon = trim($data['kode_polygon']);
        $nama_polygon = trim($data['nama_polygon']);
        $geom_area = trim($data['geom_area']);
        $is_active = isset($data['is_active']) ? 1 : 0;

        return $this->model->update($id, [
            'kode_polygon' => $kode_polygon,
            'nama_polygon' => $nama_polygon,
            'geom_area' => $geom_area,
            'is_active' => $is_active,
            'updated_by' => $user_id
        ]);
    }

    /*
    =========================
    DELETE POLYGON (SOFT DELETE)
    =========================
    */
    public function delete($id, $user_id)
    {
        if (!verifyCsrfToken()) {
            die("Invalid CSRF Token");
        }

        return $this->model->softDelete($id, $user_id);
    }

    public function getHutanAdat()
    {
        return $this->model->getHutanAdat();
    }

    public function getProvinsi()
    {
        return $this->model->getProvinsi();
    }

    public function getKabupaten()
    {
        return $this->model->getKabupaten();
    }

    public function getKecamatan()
    {
        return $this->model->getKecamatan();
    }

    public function getPolygonHAData()
    {
        return $this->model->getPolygonHAData();
    }

    public function getPolygonProvData()
    {
        return $this->model->getPolygonProvData();
    }

    public function getPolygonKabData()
    {
        return $this->model->getPolygonKabData();
    }

    public function getPolygonKecData()
    {
        return $this->model->getPolygonKecData();
    }

    public function getDetailPolygonProv($id)
    {
        return $this->model->getDetailPolygonProv($id);
    }

    public function getDetailPolygonKab($id)
    {
        return $this->model->getDetailPolygonKab($id);
    }

    public function getDetailPolygonKec($id)
    {
        return $this->model->getDetailPolygonKec($id);
    }

    public function getDetailPolygonHa($id)
    {
        return $this->model->getDetailPolygonHa($id);
    }

    public function getPengurusMHA($id)
    {
        return $this->model->getPengurusMHA($id);
    }

}