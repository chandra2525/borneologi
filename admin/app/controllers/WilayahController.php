<?php

require_once __DIR__ . '/../models/Wilayah.php';

class WilayahController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new Wilayah($pdo);
    }

    public function getAllKecamatan()
    {
        return $this->model->getAllKecamatan();
    }

    public function getKecamatanById($id)
    {
        return $this->model->getKecamatanById($id);
    }

    public function getAllDesa($idKecamatan = null)
    {
        return $this->model->getAllDesa($idKecamatan);
    }

    public function getDesaById($id)
    {
        return $this->model->getDesaById($id);
    }

    public function getDetailWilayah($tipe, $id, $data)
    {
        return $this->model->getDetailWilayah(
            $tipe,
            $id,
            $data
        );
    }
}