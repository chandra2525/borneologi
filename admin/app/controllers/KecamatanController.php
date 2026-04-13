<?php

require_once __DIR__ . '/../models/Kecamatan.php';
require_once __DIR__ . '/../core/csrf.php';

class KecamatanController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new Kecamatan($pdo);
    }

    /*
    =========================
    LIST KECAMATAN
    =========================
    */
    public function index()
    {
        return $this->model->getAll();
    }

    /*
    =========================
    GET KECAMATAN BY ID
    =========================
    */
    public function find($id)
    {
        return $this->model->findById($id);
    }

    /*
    =========================
    CREATE KECAMATAN
    =========================
    */
    public function store($data, $user_id)
    {
        if (!verifyCsrfToken()) {
            die("Invalid CSRF Token");
        }

        $id_kabupaten = trim($data['id_kabupaten']);
        $kode_kecamatan = trim($data['kode_kecamatan']);
        $nama_kecamatan = trim($data['nama_kecamatan']);
        $is_active = isset($data['is_active']) ? 1 : 0;

        return $this->model->create([
            'id_kabupaten' => $id_kabupaten,
            'kode_kecamatan' => $kode_kecamatan,
            'nama_kecamatan' => $nama_kecamatan,
            'is_active' => $is_active,
            'created_by' => $user_id
        ]);
    }

    /*
    =========================
    UPDATE KECAMATAN
    =========================
    */
    public function update($id, $data, $user_id)
    {
        if (!verifyCsrfToken()) {
            die("Invalid CSRF Token");
        }

        $id_kabupaten = trim($data['id_kabupaten']);
        $kode_kecamatan = trim($data['kode_kecamatan']);
        $nama_kecamatan = trim($data['nama_kecamatan']);
        $is_active = isset($data['is_active']) ? 1 : 0;

        return $this->model->update($id, [
            'id_kabupaten' => $id_kabupaten,
            'kode_kecamatan' => $kode_kecamatan,
            'nama_kecamatan' => $nama_kecamatan,
            'is_active' => $is_active,
            'updated_by' => $user_id
        ]);
    }

    /*
    =========================
    DELETE KECAMATAN (SOFT DELETE)
    =========================
    */
    public function delete($id, $user_id)
    {
        if (!verifyCsrfToken()) {
            die("Invalid CSRF Token");
        }
        return $this->model->softDelete($id, $user_id);
    }

    public function getKabupaten()
    {
        return $this->model->getKabupaten();
    }
}