<?php

require_once __DIR__ . '/../models/Desa.php';
require_once __DIR__ . '/../core/csrf.php';

class DesaController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new Desa($pdo);
    }

    /*
    =========================
    LIST DESA
    =========================
    */
    public function index()
    {
        return $this->model->getAll();
    }

    /*
    =========================
    GET DESA BY ID
    =========================
    */
    public function find($id)
    {
        return $this->model->findById($id);
    }

    /*
    =========================
    CREATE DESA
    =========================
    */
    public function store($data, $user_id)
    {
        if (!verifyCsrfToken()) {
            die("Invalid CSRF Token");
        }

        $id_kecamatan = trim($data['id_kecamatan']);
        $kode_desa = trim($data['kode_desa']);
        $nama_desa = trim($data['nama_desa']);
        $is_active = isset($data['is_active']) ? 1 : 0;

        return $this->model->create([
            'id_kecamatan' => $id_kecamatan,
            'kode_desa' => $kode_desa,
            'nama_desa' => $nama_desa,
            'is_active' => $is_active,
            'created_by' => $user_id
        ]);
    }

    /*
    =========================
    UPDATE DESA
    =========================
    */
    public function update($id, $data, $user_id)
    {
        if (!verifyCsrfToken()) {
            die("Invalid CSRF Token");
        }

        $id_kecamatan = trim($data['id_kecamatan']);
        $kode_desa = trim($data['kode_desa']);
        $nama_desa = trim($data['nama_desa']);
        $is_active = isset($data['is_active']) ? 1 : 0;

        return $this->model->update($id, [
            'id_kecamatan' => $id_kecamatan,
            'kode_desa' => $kode_desa,
            'nama_desa' => $nama_desa,
            'is_active' => $is_active,
            'updated_by' => $user_id
        ]);
    }

    /*
    =========================
    DELETE DESA (SOFT DELETE)
    =========================
    */
    public function delete($id, $user_id)
    {
        if (!verifyCsrfToken()) {
            die("Invalid CSRF Token");
        }
        return $this->model->softDelete($id, $user_id);
    }

    public function getKecamatan()
    {
        return $this->model->getKecamatan();
    }
}